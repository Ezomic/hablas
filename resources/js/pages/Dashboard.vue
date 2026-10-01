<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { computed, ref } from 'vue';
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
import { pluralizeDays } from '@/lib/pluralize';
import { skillLabels } from '@/lib/skillLabels';
import { dashboard } from '@/routes';
import { activatePortuguese } from '@/routes/language';
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
    canActivatePortuguese?: boolean;
}

const props = defineProps<Props>();

function startLesson(unit: NextUnit) {
    if (unit.lesson !== null) {
        router.post(
            startRun({ unit: unit.id, lesson: unit.lesson.lessonId }).url,
        );
    }
}

function activatePortugueseTrack() {
    router.post(activatePortuguese().url);
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const breakdownOpen = ref(false);

const ceilingSkills = computed(() => props.blendedLevelCeiling ?? []);

const ceilingSkillNames = computed(() => {
    const names = ceilingSkills.value.map((skill) =>
        (skillLabels[skill] ?? skill).toLowerCase(),
    );

    return names.length > 1
        ? `${names.slice(0, -1).join(', ')} and ${names[names.length - 1]}`
        : (names[0] ?? '');
});
</script>

<template>
    <Head title="Dashboard" />

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
                        Your overall level is held by {{ ceilingSkillNames }}.
                        Practice {{ ceilingSkillNames }} to move it, or re-take
                        the {{ ceilingSkillNames }} placement if that level
                        looks wrong.
                    </p>
                    <p v-else>
                        Your overall level is held by {{ ceilingSkillNames }}.
                        Practice them to move it, or re-take a placement if one
                        of those levels looks wrong.
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
                        Per-skill breakdown
                        <ChevronDown
                            class="size-4 transition-transform"
                            :class="{ 'rotate-180': breakdownOpen }"
                        />
                    </CollapsibleTrigger>
                    <CollapsibleContent class="mt-4 flex flex-col gap-2">
                        <div
                            v-for="skill in Object.keys(skillLabels)"
                            :key="skill"
                            class="flex items-center justify-between border-b pb-2 text-sm last:border-b-0"
                        >
                            <span>{{ skillLabels[skill] }}</span>
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
                    View placement results
                </Link>
            </CardContent>
        </Card>

        <p v-else class="text-muted-foreground">No active language yet.</p>

        <Card v-if="props.canActivatePortuguese">
            <CardHeader>
                <CardDescription>New language unlocked</CardDescription>
                <CardTitle class="text-2xl"
                    >Ready to start Portuguese?</CardTitle
                >
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    You've reached A2 in Spanish. Portuguese shares a lot with
                    Spanish, but we'll flag the differences as you go.
                </p>
                <Button @click="activatePortugueseTrack"
                    >Start learning Portuguese</Button
                >
            </CardContent>
        </Card>

        <Card v-if="props.sessionNeedsRemediation">
            <CardHeader>
                <CardDescription>Next up</CardDescription>
                <CardTitle class="text-2xl"
                    >Reinforce what's tricky first</CardTitle
                >
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    Your recent reviews have had a lot of misses — revisit those
                    before starting something new.
                </p>
                <Button as-child>
                    <Link :href="reviewIndex().url">Review now</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.nextUnit">
            <CardHeader>
                <CardDescription>{{
                    props.nextUnit.lesson?.resumes
                        ? 'Continue'
                        : props.sessionNeedsRemediation
                          ? 'In progress'
                          : 'Next up'
                }}</CardDescription>
                <CardTitle class="text-2xl">{{
                    props.nextUnit.title
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    <template v-if="props.nextUnit.lesson">
                        {{
                            props.nextUnit.lesson.resumes ? 'Continue' : 'Start'
                        }}
                        lesson {{ props.nextUnit.lesson.number }} of
                        {{ props.nextUnit.lesson.count }}:
                        {{ props.nextUnit.lesson.title }}
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
                        props.nextUnit.lesson.resumes
                            ? `Continue lesson ${props.nextUnit.lesson.number} of ${props.nextUnit.lesson.count}`
                            : `Start lesson ${props.nextUnit.lesson.number} of ${props.nextUnit.lesson.count}`
                    }}
                </Button>
                <Button v-else as-child>
                    <Link :href="showUnit(props.nextUnit.id).url"
                        >Start unit</Link
                    >
                </Button>
            </CardContent>
        </Card>

        <Card
            v-for="result in props.unseenLessonResults ?? []"
            :key="result.runId"
        >
            <CardHeader>
                <CardDescription>Results are in</CardDescription>
                <CardTitle class="text-2xl"
                    >{{ result.unitTitle }}: {{ result.lessonTitle }}</CardTitle
                >
            </CardHeader>
            <CardContent>
                <Button as-child variant="outline">
                    <Link :href="showRun(result.runId).url">See results</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.dueReviewCount">
            <CardHeader>
                <CardDescription>Review</CardDescription>
                <CardTitle class="text-4xl">
                    {{ props.dueReviewCount }}
                    {{ props.dueReviewCount === 1 ? 'card' : 'cards' }} due
                </CardTitle>
            </CardHeader>
            <CardContent>
                <Button as-child>
                    <Link :href="reviewIndex().url">Start review</Link>
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
                <CardDescription>Weak spots</CardDescription>
                <CardTitle class="text-4xl">
                    {{ props.weakSpotReviewCount }}
                    {{ props.weakSpotReviewCount === 1 ? 'card' : 'cards' }} to
                    revisit
                </CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    Cards you've missed a few times in a row, set aside until
                    you get them right once more.
                </p>
                <Button as-child variant="outline">
                    <Link :href="weakSpotIndex().url">Review weak spots</Link>
                </Button>
            </CardContent>
        </Card>

        <Card v-if="props.streak">
            <CardHeader>
                <CardDescription>Streak</CardDescription>
                <CardTitle class="text-4xl">
                    {{ props.streak.currentLength }}
                    {{ pluralizeDays(props.streak.currentLength) }}
                </CardTitle>
            </CardHeader>
            <CardContent
                class="flex flex-col gap-1 text-sm text-muted-foreground"
            >
                <span
                    >Longest streak: {{ props.streak.longestLength }}
                    {{ pluralizeDays(props.streak.longestLength) }}</span
                >
                <span
                    >Freeze days remaining:
                    {{ props.streak.freezeDaysRemaining }}</span
                >
                <span
                    >A missed day uses a freeze day instead of breaking your
                    streak.</span
                >
                <span v-if="props.streak.daysUntilNextFreezeDay !== null"
                    >Earn one back in
                    {{ props.streak.daysUntilNextFreezeDay }} more active
                    {{
                        pluralizeDays(props.streak.daysUntilNextFreezeDay)
                    }}.</span
                >
            </CardContent>
        </Card>

        <Card v-if="props.language">
            <CardHeader>
                <CardDescription>Share</CardDescription>
                <CardTitle class="text-2xl">Share your progress</CardTitle>
            </CardHeader>
            <CardContent>
                <Button as-child variant="outline">
                    <Link :href="showProgressShare().url"
                        >Get shareable link</Link
                    >
                </Button>
            </CardContent>
        </Card>
    </div>
</template>
