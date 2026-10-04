<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Check, Lock } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import RemediationActions from '@/components/RemediationActions.vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { store as startRun } from '@/routes/lessons/runs';
import type { UnitLessonOverview, UnitLessonRow } from '@/types/lesson';

const props = defineProps<{
    unitId: number;
    overview: UnitLessonOverview;
    isHeldBack: boolean;
}>();

const { t } = useI18n();

const starting = ref<number | null>(null);
const error = ref<string | null>(null);

const masteryPercent = computed(() =>
    props.overview.mastery.total === 0
        ? 0
        : Math.round(
              (props.overview.mastery.mastered / props.overview.mastery.total) *
                  100,
          ),
);

const checkLesson = computed(
    () => props.overview.lessons.find((row) => row.stage === 'check') ?? null,
);

const skippedNote = computed(() => {
    const { listening, speaking } = props.overview.skipped;

    if (listening && speaking) {
        return t('unitLessons.skippedBoth', {
            listening: t(
                'unitLessons.skippedListeningPart',
                { count: listening },
                listening,
            ),
            speaking: t(
                'unitLessons.skippedSpeakingPart',
                { count: speaking },
                speaking,
            ),
        });
    }

    if (listening) {
        return t('unitLessons.skippedListening', { listening }, listening);
    }

    return speaking
        ? t('unitLessons.skippedSpeaking', { speaking }, speaking)
        : null;
});

function start(
    row: { lessonId: number | null },
    kind?: 'test_out' | 'practice' | 'retake',
) {
    if (row.lessonId === null) {
        return;
    }

    starting.value = row.lessonId;
    error.value = null;
    router.post(
        startRun({ unit: props.unitId, lesson: row.lessonId }).url,
        kind === undefined ? {} : { kind },
        {
            onError: (errors) => {
                error.value = errors.lesson ?? errors.kind ?? null;
            },
            onFinish: () => (starting.value = null),
        },
    );
}

function label(row: UnitLessonRow): string {
    switch (row.state) {
        case 'in_progress':
            return t('unitLessons.continue');
        case 'completed':
            return t('unitLessons.replay');
        default:
            return t('unitLessons.start');
    }
}

function status(row: UnitLessonRow): string {
    switch (row.state) {
        case 'locked':
            return row.stage === 'check'
                ? t('unitLessons.status.lockedCheck')
                : t('unitLessons.status.locked');
        case 'opens_tomorrow':
            return t('unitLessons.status.opensTomorrow');
        case 'coming':
            return t('unitLessons.status.coming');
        case 'remediation':
            return t('lesson.remediation.status');
        case 'in_progress':
            return t('unitLessons.status.inProgress');
        case 'completed':
            return row.bestAccuracy === null
                ? t('unitLessons.status.done')
                : t('unitLessons.status.doneBest', {
                      percent: Math.round(row.bestAccuracy * 100),
                  });
        default:
            return t('unitLessons.status.ready');
    }
}

function playable(row: UnitLessonRow): boolean {
    return (
        row.lessonId !== null &&
        ['available', 'in_progress', 'completed'].includes(row.state) &&
        !(row.stage === 'check' && row.state === 'completed')
    );
}
</script>

<template>
    <section class="flex flex-col gap-4" data-testid="lesson-list">
        <Alert v-if="error" variant="destructive" role="alert">
            <AlertDescription>{{ error }}</AlertDescription>
        </Alert>

        <Alert v-else-if="props.isHeldBack">
            <AlertDescription>
                {{ t('unitLessons.heldBack') }}
            </AlertDescription>
        </Alert>

        <Card>
            <CardHeader class="gap-2">
                <CardTitle class="text-base">{{
                    t('unitLessons.mastered')
                }}</CardTitle>
                <p class="text-sm text-muted-foreground" data-testid="mastery">
                    {{
                        t('unitLessons.masteredCount', {
                            mastered: props.overview.mastery.mastered,
                            total: props.overview.mastery.total,
                        })
                    }}
                </p>
                <Progress
                    :model-value="masteryPercent"
                    :aria-label="t('unitLessons.wordsMastered')"
                />
                <p v-if="skippedNote" class="text-xs text-muted-foreground">
                    {{ skippedNote }}
                </p>
            </CardHeader>
        </Card>

        <ol class="flex flex-col gap-3">
            <li
                v-for="row in props.overview.lessons"
                :key="row.stage"
                class="flex items-center gap-3 rounded-xl border bg-card p-4"
                :class="{ 'border-dashed bg-muted/40': row.state === 'coming' }"
                :data-testid="`lesson-${row.stage}`"
            >
                <span
                    class="flex size-9 shrink-0 items-center justify-center rounded-full border text-sm font-medium"
                    :class="
                        row.state === 'completed'
                            ? 'border-green-600 bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300'
                            : 'text-muted-foreground'
                    "
                    aria-hidden="true"
                >
                    <Check v-if="row.state === 'completed'" class="size-4" />
                    <Lock
                        v-else-if="
                            ['locked', 'opens_tomorrow'].includes(row.state)
                        "
                        class="size-4"
                    />
                    <template v-else>{{ row.position }}</template>
                </span>

                <span class="flex min-w-0 flex-1 flex-col">
                    <span class="font-medium">{{ row.title }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        status(row)
                    }}</span>
                </span>

                <Button
                    v-if="playable(row)"
                    :variant="row.state === 'completed' ? 'outline' : 'default'"
                    :disabled="
                        starting !== null ||
                        (props.isHeldBack && row.state === 'available')
                    "
                    @click="start(row)"
                >
                    <AppSpinner v-if="starting === row.lessonId" />
                    {{ label(row) }}
                </Button>
                <Badge v-else-if="row.state === 'coming'" variant="outline">{{
                    t('unitLessons.soon')
                }}</Badge>
            </li>
        </ol>

        <Card v-if="props.overview.remediation">
            <CardHeader>
                <RemediationActions
                    :remediation="props.overview.remediation"
                    :busy="starting !== null"
                    @practice="start(props.overview.remediation, 'practice')"
                    @retake="start(props.overview.remediation, 'retake')"
                />
            </CardHeader>
        </Card>

        <p
            v-if="props.overview.contentPending"
            class="text-sm text-muted-foreground"
            data-testid="content-pending"
        >
            {{ t('unitLessons.contentPending') }}
        </p>

        <Button
            v-if="props.overview.canTestOut && checkLesson?.lessonId"
            variant="outline"
            :disabled="starting !== null"
            @click="start(checkLesson, 'test_out')"
        >
            {{ t('unitLessons.takeCheck') }}
        </Button>
    </section>
</template>
