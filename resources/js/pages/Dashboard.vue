<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { computed, ref } from 'vue';
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
import { show as showProgressShare } from '@/routes/progress/share';
import { index as reviewIndex } from '@/routes/review';
import { index as weakSpotIndex } from '@/routes/review/weak-spots';
import type { LanguageOption } from '@/types';

interface Streak {
    currentLength: number;
    longestLength: number;
    freezeDaysRemaining: number;
}

interface NextUnit {
    id: number;
    title: string;
    taskDescription: string;
}

interface Props {
    language: Pick<LanguageOption, 'code' | 'name'> | null;
    blendedLevel?: string | null;
    blendedLevelCeiling?: string[];
    skillLevels?: Record<string, string>;
    streak?: Streak;
    dueReviewCount?: number;
    weakSpotReviewCount?: number;
    sessionNeedsRemediation?: boolean;
    nextUnit?: NextUnit | null;
    canActivatePortuguese?: boolean;
}

const props = defineProps<Props>();

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

const ceilingSkillNames = computed(() =>
    (props.blendedLevelCeiling ?? [])
        .map((skill) => skillLabels[skill] ?? skill)
        .join(' and '),
);
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
                <p
                    v-if="ceilingSkillNames"
                    class="mb-4 text-sm text-muted-foreground"
                >
                    Your overall level is held by {{ ceilingSkillNames }}, which
                    the placement test sets and daily practice doesn't yet move.
                    Your other skills have already climbed higher.
                </p>
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

        <Card v-else-if="props.nextUnit">
            <CardHeader>
                <CardDescription>Next up</CardDescription>
                <CardTitle class="text-2xl">{{
                    props.nextUnit.title
                }}</CardTitle>
            </CardHeader>
            <CardContent>
                <p class="text-sm text-muted-foreground">
                    {{ props.nextUnit.taskDescription }}
                </p>
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
                    Cards you've missed a few times in a row, set aside until you
                    get them right once more.
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
