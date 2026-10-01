<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Check, Lock } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { Progress } from '@/components/ui/progress';
import { Spinner } from '@/components/ui/spinner';
import { store as startRun } from '@/routes/lessons/runs';
import type { UnitLessonOverview, UnitLessonRow } from '@/types/lesson';

const props = defineProps<{
    unitId: number;
    overview: UnitLessonOverview;
    isHeldBack: boolean;
}>();

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
    const parts = [
        listening ? `listening in ${listening}` : null,
        speaking ? `speaking in ${speaking}` : null,
    ].filter((part) => part !== null);

    return parts.length ? `Skipped ${parts.join(' and ')} exercises.` : null;
});

function start(row: UnitLessonRow, kind?: 'test_out') {
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
            return 'Continue';
        case 'completed':
            return 'Replay';
        default:
            return 'Start';
    }
}

function status(row: UnitLessonRow): string {
    switch (row.state) {
        case 'locked':
            return row.stage === 'check'
                ? 'Opens after the lessons before it'
                : 'Finish the lesson before it first';
        case 'opens_tomorrow':
            return 'Opens tomorrow';
        case 'coming':
            return 'Coming soon';
        case 'in_progress':
            return 'In progress';
        case 'completed':
            return row.bestAccuracy === null
                ? 'Done'
                : `Done, best ${Math.round(row.bestAccuracy * 100)}% first time`;
        default:
            return 'Ready';
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
                Clear your reviews first, then start this unit.
            </AlertDescription>
        </Alert>

        <Card>
            <CardHeader class="gap-2">
                <CardTitle class="text-base">Mastered</CardTitle>
                <p class="text-sm text-muted-foreground" data-testid="mastery">
                    {{ props.overview.mastery.mastered }} of
                    {{ props.overview.mastery.total }} mastered
                </p>
                <Progress
                    :model-value="masteryPercent"
                    aria-label="Words mastered"
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
                    <Spinner v-if="starting === row.lessonId" />
                    {{ label(row) }}
                </Button>
                <Badge v-else-if="row.state === 'coming'" variant="outline"
                    >Soon</Badge
                >
            </li>
        </ol>

        <p
            v-if="props.overview.contentPending"
            class="text-sm text-muted-foreground"
            data-testid="content-pending"
        >
            Sentence and grammar lessons for this unit are coming. The unit
            stays in progress until they are done and you have passed its check.
        </p>

        <Button
            v-if="props.overview.canTestOut && checkLesson?.lessonId"
            variant="outline"
            :disabled="starting !== null"
            @click="start(checkLesson, 'test_out')"
        >
            Take the unit check now
        </Button>
    </section>
</template>
