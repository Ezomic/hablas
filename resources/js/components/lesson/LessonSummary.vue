<script setup lang="ts">
import { Check, Gem, Trophy, X } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import RemediationActions from '@/components/RemediationActions.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { NextLesson, Remediation, RunSummary } from '@/types/lesson';

const props = defineProps<{
    summary: RunSummary;
    isCheck: boolean;
    next: NextLesson | null;
    starting?: boolean;
    remediation?: Remediation | null;
    locale?: string | null;
}>();

const emit = defineEmits<{
    next: [];
    unit: [];
    practice: [];
    retake: [];
}>();

const { t } = useI18n();

const families: Record<string, string> = {
    choice: 'lesson.summary.family.choice',
    writing: 'lesson.summary.family.writing',
    listening: 'lesson.summary.family.listening',
    speaking: 'lesson.summary.family.speaking',
};

function familyLabel(family: string | number): string {
    const key = families[family];

    return key === undefined ? String(family) : t(key);
}

const missing = computed(() =>
    props.summary.items.filter((item) => !item.mastered),
);

const mastered = computed(() =>
    props.summary.items.filter((item) => item.mastered),
);

function milestoneText(milestone: {
    type: string;
    count?: number;
    level?: string;
}): string {
    return t(`lesson.summary.milestone.${milestone.type}`, {
        count: milestone.count ?? 0,
        level: milestone.level ?? '',
    });
}

const nextIsOpen = computed(
    () =>
        props.next !== null &&
        ['available', 'in_progress'].includes(props.next.state),
);
</script>

<template>
    <section class="flex flex-col gap-4" data-testid="summary">
        <h2 class="text-2xl font-semibold">
            {{
                props.summary.unitCompleted
                    ? t('lesson.summary.unitComplete')
                    : props.isCheck
                      ? t('lesson.summary.checkFinished')
                      : t('lesson.summary.lessonComplete')
            }}
        </h2>

        <p
            v-if="props.summary.unitCompleted"
            class="text-sm text-muted-foreground"
        >
            {{ t('lesson.summary.unitProven') }}
        </p>

        <ul
            v-if="props.summary.milestones.length"
            class="flex flex-col gap-2"
            data-testid="milestones"
        >
            <li
                v-for="milestone in props.summary.milestones"
                :key="milestone.type"
                class="flex items-center gap-3 rounded-xl border border-primary/40 bg-primary/10 px-4 py-3 text-sm font-medium"
            >
                <Trophy class="size-5 shrink-0 text-primary" />
                {{ milestoneText(milestone) }}
            </li>
        </ul>

        <Card v-if="props.summary.newlyKnown.length">
            <CardHeader>
                <CardTitle class="text-base">{{
                    t('lesson.summary.newlyKnown', {
                        n: props.summary.newlyKnown.length,
                    })
                }}</CardTitle>
            </CardHeader>
            <CardContent
                class="flex flex-col gap-1 text-sm"
                data-testid="newly-known"
            >
                <p
                    v-for="word in props.summary.newlyKnown"
                    :key="word.term"
                    class="flex items-center gap-2"
                >
                    <Gem class="size-4 shrink-0 fill-primary text-primary" />
                    <span :lang="props.locale ?? undefined">{{
                        word.term
                    }}</span>
                    <span class="text-muted-foreground">{{
                        word.translation
                    }}</span>
                </p>
            </CardContent>
        </Card>

        <Card v-if="Object.keys(props.summary.accuracy).length">
            <CardHeader>
                <CardTitle class="text-base">{{
                    t('lesson.summary.accuracy')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-1 text-sm">
                <p
                    v-for="(value, family) in props.summary.accuracy"
                    :key="family"
                    class="flex justify-between"
                >
                    <span>{{ familyLabel(family) }}</span>
                    <span class="font-medium"
                        >{{ Math.round(value * 100) }}%</span
                    >
                </p>
            </CardContent>
        </Card>

        <Card v-if="props.summary.retried.length">
            <CardHeader>
                <CardTitle class="text-base">{{
                    t('lesson.summary.retried')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="text-sm">
                {{ props.summary.retried.join(', ') }}
            </CardContent>
        </Card>

        <template v-if="props.isCheck">
            <Card v-if="mastered.length">
                <CardHeader>
                    <CardTitle class="text-base">{{
                        t('lesson.summary.proven', { n: mastered.length })
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-1 text-sm">
                    <p
                        v-for="item in mastered"
                        :key="item.term"
                        class="flex items-center gap-2"
                    >
                        <Check class="size-4 text-green-600" />
                        <span :lang="props.locale ?? undefined">{{
                            item.term
                        }}</span>
                    </p>
                </CardContent>
            </Card>

            <Card v-if="missing.length">
                <CardHeader>
                    <CardTitle class="text-base">{{
                        t('lesson.summary.notProven', { n: missing.length })
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-1 text-sm">
                    <p
                        v-for="item in missing"
                        :key="item.term"
                        class="flex items-center gap-2"
                    >
                        <X class="size-4 text-red-600" />
                        <span :lang="props.locale ?? undefined">{{
                            item.term
                        }}</span>
                        <span
                            v-if="item.translation"
                            class="text-muted-foreground"
                            >{{ `(${item.translation})` }}</span
                        >
                    </p>
                </CardContent>
            </Card>

            <Card v-if="props.summary.answers.length">
                <CardHeader>
                    <CardTitle class="text-base">{{
                        t('lesson.summary.everyAnswer')
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-2 text-sm">
                    <p
                        v-for="(answer, index) in props.summary.answers"
                        :key="index"
                        class="flex flex-col"
                    >
                        <span class="text-muted-foreground">{{
                            answer.prompt
                        }}</span>
                        <span
                            :class="
                                answer.correct
                                    ? 'text-green-700 dark:text-green-300'
                                    : 'text-red-700 dark:text-red-300'
                            "
                            :lang="
                                answer.given && answer.learnedLanguage
                                    ? (props.locale ?? undefined)
                                    : undefined
                            "
                            >{{
                                answer.given || t('lesson.summary.noAnswer')
                            }}</span
                        >
                        <i18n-t
                            v-if="!answer.correct"
                            keypath="lesson.summary.correct"
                            scope="global"
                            tag="span"
                            class="font-medium"
                        >
                            <template #expected>
                                <span
                                    :lang="
                                        answer.learnedLanguage
                                            ? (props.locale ?? undefined)
                                            : undefined
                                    "
                                    >{{ answer.expected }}</span
                                >
                            </template>
                        </i18n-t>
                    </p>
                </CardContent>
            </Card>

            <p
                v-if="props.summary.cardsEnrolled"
                class="text-sm text-muted-foreground"
            >
                {{
                    t('lesson.summary.cardsJoined', props.summary.cardsEnrolled)
                }}
            </p>
        </template>

        <RemediationActions
            v-if="props.remediation"
            :remediation="props.remediation"
            :busy="props.starting"
            @practice="emit('practice')"
            @retake="emit('retake')"
        />

        <div class="flex flex-col gap-2">
            <Button
                v-if="nextIsOpen && props.next"
                :disabled="props.starting"
                @click="emit('next')"
            >
                {{
                    props.next.stage === 'check'
                        ? t('lesson.summary.takeCheck')
                        : t('lesson.summary.nextLesson', {
                              title: props.next.title,
                          })
                }}
            </Button>
            <p
                v-else-if="props.next?.state === 'opens_tomorrow'"
                class="text-sm text-muted-foreground"
            >
                {{ t('lesson.summary.checkLater') }}
            </p>
            <Button variant="outline" @click="emit('unit')">{{
                t('lesson.summary.backToUnit')
            }}</Button>
        </div>
    </section>
</template>
