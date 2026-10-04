<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import RetakeSkillButton from '@/components/RetakeSkillButton.vue';
import ReviewForecast from '@/components/ReviewForecast.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { joinList } from '@/lib/intlLocale';
import { skillKeys, skillLabel } from '@/lib/skillLabels';
import { dashboard } from '@/routes';
import { activate } from '@/routes/language';
import { show as showRun } from '@/routes/lesson-runs';
import { store as startRun } from '@/routes/lessons/runs';
import { results as placementResults } from '@/routes/placement';
import { show as showProgressShare } from '@/routes/progress/share';
import { index as reviewIndex } from '@/routes/review';
import { index as weakSpotIndex } from '@/routes/review/weak-spots';
import { show as showUnit } from '@/routes/units';
import type { LanguageOption } from '@/types';
import type { ReviewForecast as Forecast } from '@/types/forecast';

interface Streak {
    currentLength: number;
    longestLength: number;
    freezeDaysRemaining: number;
    daysUntilNextFreezeDay: number | null;
}

interface NextLesson {
    lessonId: number;
    title: string;
    number: number;
    count: number;
    resumes: boolean;
    remediation: 'retake' | 'practice' | null;
    missing: number;
}

interface NextUnit {
    id: number;
    title: string;
    taskDescription: string;
    lesson: NextLesson | null;
}

interface UnseenResult {
    runId: number;
    unitTitle: string;
    lessonTitle: string;
    isCheck: boolean;
}

interface Props {
    language: Pick<LanguageOption, 'code' | 'name'> | null;
    blendedLevel?: string | null;
    blendedLevelCeiling?: string[];
    retakeAvailableOn?: Record<string, string | null>;
    skillLevels?: Record<string, string>;
    streak?: Streak;
    dueReviewCount?: number;
    weakSpotReviewCount?: number;
    reviewForecast?: Forecast;
    sessionNeedsRemediation?: boolean;
    nextUnit?: NextUnit | null;
    unseenLessonResults?: UnseenResult[];
    activatableLanguages?: { code: string; name: string }[];
}

const props = defineProps<Props>();

const { t } = useI18n();

function startLesson(unit: NextUnit) {
    if (unit.lesson !== null) {
        router.post(
            startRun({ unit: unit.id, lesson: unit.lesson.lessonId }).url,
            unit.lesson.remediation === null
                ? {}
                : { kind: unit.lesson.remediation },
        );
    }
}

function activateLanguage(code: string) {
    router.post(activate(code).url);
}

useBreadcrumbs(() => [{ title: t('nav.dashboard'), href: dashboard() }]);

const breakdownOpen = ref(false);

const ceilingSkills = computed(() => props.blendedLevelCeiling ?? []);

const ceilingSkillNames = computed(() =>
    joinList(
        ceilingSkills.value.map((skill) => skillLabel(skill).toLowerCase()),
    ),
);
</script>

<template>
    <Head :title="t('nav.dashboard')" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <Card v-if="props.language">
            <CardHeader>
                <CardDescription>{{ props.language.name }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ props.blendedLevel ?? '—' }}
                </CardTitle>
            </CardHeader>
            <CardContent>
                <div
                    v-if="ceilingSkills.length"
                    class="mb-4 flex flex-col gap-3 text-sm text-muted-foreground"
                >
                    <p v-if="ceilingSkills.length === 1">
                        {{
                            t('dashboard.ceiling.one', {
                                skill: ceilingSkillNames,
                            })
                        }}
                    </p>
                    <p v-else>
                        {{
                            t('dashboard.ceiling.many', {
                                skills: ceilingSkillNames,
                            })
                        }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <RetakeSkillButton
                            v-for="skill in ceilingSkills"
                            :key="skill"
                            :skill="skill"
                            :available-on="
                                props.retakeAvailableOn?.[skill] ?? null
                            "
                        />
                    </div>
                </div>
                <Collapsible v-model:open="breakdownOpen">
                    <CollapsibleTrigger
                        class="flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
                    >
                        {{ t('dashboard.breakdown') }}
                        <ChevronDown
                            class="size-4 transition-transform"
                            :class="{ 'rotate-180': breakdownOpen }"
                        />
                    </CollapsibleTrigger>
                    <CollapsibleContent class="mt-4 flex flex-col gap-2">
                        <div
                            v-for="skill in skillKeys"
                            :key="skill"
                            class="flex items-center justify-between border-b pb-2 text-sm last:border-b-0"
                        >
                            <span>{{ skillLabel(skill) }}</span>
                            <span class="font-medium">{{
                                props.skillLevels?.[skill] ?? '—'
                            }}</span>
                        </div>
                    </CollapsibleContent>
                </Collapsible>
                <Link
                    :href="placementResults().url"
                    class="mt-4 inline-block text-sm text-muted-foreground underline underline-offset-4 hover:text-foreground"
                >
                    {{ t('dashboard.viewPlacement') }}
                </Link>
            </CardContent>
        </Card>

        <p v-else class="text-muted-foreground">
            {{ t('common.noActiveLanguage') }}
        </p>

        <Card v-for="option in props.activatableLanguages" :key="option.code">
            <CardHeader>
                <CardDescription>{{
                    t('dashboard.activate.label')
                }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    t('dashboard.activate.title', { language: option.name })
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    {{ t('dashboard.activate.body') }}
                </p>
                <Button @click="activateLanguage(option.code)">{{
                    t('dashboard.activate.start', { language: option.name })
                }}</Button>
            </CardContent>
        </Card>

        <Card v-if="props.sessionNeedsRemediation">
            <CardHeader>
                <CardDescription>{{ t('dashboard.nextUp') }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    t('dashboard.reinforce.title')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    {{ t('dashboard.reinforce.body') }}
                </p>
                <Button as-child>
                    <Link :href="reviewIndex().url">{{
                        t('common.reviewNow')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.nextUnit">
            <CardHeader>
                <CardDescription>{{
                    props.nextUnit.lesson?.resumes
                        ? t('dashboard.continue')
                        : props.sessionNeedsRemediation
                          ? t('dashboard.inProgress')
                          : t('dashboard.nextUp')
                }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    props.nextUnit.title
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    <template v-if="props.nextUnit.lesson?.remediation">
                        {{
                            t(
                                props.nextUnit.lesson.remediation === 'retake'
                                    ? 'lesson.dashboard.retakeNote'
                                    : 'lesson.dashboard.practiceNote',
                                props.nextUnit.lesson.missing,
                            )
                        }}
                    </template>
                    <template v-else-if="props.nextUnit.lesson">
                        {{
                            t(
                                props.nextUnit.lesson.resumes
                                    ? 'dashboard.continueLine'
                                    : 'dashboard.startLine',
                                {
                                    number: props.nextUnit.lesson.number,
                                    count: props.nextUnit.lesson.count,
                                    title: props.nextUnit.lesson.title,
                                },
                            )
                        }}
                    </template>
                    <template v-else>{{
                        props.nextUnit.taskDescription
                    }}</template>
                </p>
                <Button
                    v-if="props.nextUnit.lesson"
                    @click="startLesson(props.nextUnit)"
                >
                    {{
                        props.nextUnit.lesson.remediation === 'retake'
                            ? t('lesson.dashboard.retakeButton')
                            : props.nextUnit.lesson.remediation === 'practice'
                              ? t('lesson.dashboard.practiceButton')
                              : props.nextUnit.lesson.resumes
                                ? t('dashboard.continueButton', {
                                      number: props.nextUnit.lesson.number,
                                      count: props.nextUnit.lesson.count,
                                  })
                                : t('dashboard.startButton', {
                                      number: props.nextUnit.lesson.number,
                                      count: props.nextUnit.lesson.count,
                                  })
                    }}
                </Button>
                <Button v-else as-child>
                    <Link :href="showUnit(props.nextUnit.id).url">{{
                        t('dashboard.startUnit')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>

        <Card
            v-for="result in props.unseenLessonResults ?? []"
            :key="result.runId"
        >
            <CardHeader>
                <CardDescription>{{
                    t('dashboard.results.label')
                }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    t('dashboard.results.title', {
                        unit: result.unitTitle,
                        lesson: result.lessonTitle,
                    })
                }}</CardTitle>
            </CardHeader>
            <CardContent>
                <Button as-child variant="outline">
                    <Link :href="showRun(result.runId).url">{{
                        t('dashboard.results.see')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.dueReviewCount">
            <CardHeader>
                <CardDescription>{{ t('nav.review') }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ t('dashboard.cardsDue', props.dueReviewCount) }}
                </CardTitle>
            </CardHeader>
            <CardContent>
                <Button as-child>
                    <Link :href="reviewIndex().url">{{
                        t('dashboard.startReview')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>

        <ReviewForecast
            v-if="props.reviewForecast"
            :forecast="props.reviewForecast"
            :weak-spots="props.weakSpotReviewCount ?? 0"
        />

        <Card v-if="props.weakSpotReviewCount">
            <CardHeader>
                <CardDescription>{{ t('nav.weakSpots') }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{
                        t(
                            'dashboard.weakSpots.count',
                            props.weakSpotReviewCount,
                        )
                    }}
                </CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    {{ t('dashboard.weakSpots.body') }}
                </p>
                <Button as-child variant="outline">
                    <Link :href="weakSpotIndex().url">{{
                        t('dashboard.weakSpots.review')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.streak">
            <CardHeader>
                <CardDescription>{{
                    t('dashboard.streak.title')
                }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ t('common.days', props.streak.currentLength) }}
                </CardTitle>
            </CardHeader>
            <CardContent
                class="flex flex-col gap-1 text-sm text-muted-foreground"
            >
                <span>{{
                    t('dashboard.streak.longest', {
                        days: t('common.days', props.streak.longestLength),
                    })
                }}</span>
                <span>{{
                    t('dashboard.streak.freezeDays', {
                        count: props.streak.freezeDaysRemaining,
                    })
                }}</span>
                <span>{{ t('dashboard.streak.freezeNote') }}</span>
                <span v-if="props.streak.daysUntilNextFreezeDay !== null">{{
                    t(
                        'dashboard.streak.earnBack',
                        props.streak.daysUntilNextFreezeDay,
                    )
                }}</span>
            </CardContent>
        </Card>

        <Card v-if="props.language">
            <CardHeader>
                <CardDescription>{{
                    t('dashboard.share.label')
                }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    t('progress.share.title')
                }}</CardTitle>
            </CardHeader>
            <CardContent>
                <Button as-child variant="outline">
                    <Link :href="showProgressShare().url">{{
                        t('dashboard.share.link')
                    }}</Link>
                </Button>
            </CardContent>
        </Card>
    </div>
</template>
