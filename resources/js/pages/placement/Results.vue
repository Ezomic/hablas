<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Check, Minus, X } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import RetakeSkillButton from '@/components/RetakeSkillButton.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { skillLabel } from '@/lib/skillLabels';
import { dashboard } from '@/routes';
import {
    index as placementIndex,
    results as placementResults,
} from '@/routes/placement';

interface BreakdownItem {
    prompt: string;
    yourAnswer: string | null;
    correctAnswer: string;
    status: 'correct' | 'incorrect' | 'dont_know';
}

interface SkillResult {
    skill: string;
    level: string | null;
    items: BreakdownItem[];
}

const props = defineProps<{
    language: { code: string; name: string };
    result: {
        completedAt: string | null;
        blendedLevel: string | null;
        skipped: boolean;
        skills: SkillResult[];
    };
    retakeAvailableOn: Record<string, string | null>;
    openAttempt: { skill: string | null } | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [
    {
        title: t('placement.results.breadcrumb'),
        href: placementResults(),
    },
]);

const statusMeta: Record<
    BreakdownItem['status'],
    { icon: typeof Check; label: () => string; class: string }
> = {
    correct: {
        icon: Check,
        label: () => t('placement.results.status.correct'),
        class: 'text-green-600 dark:text-green-500',
    },
    incorrect: {
        icon: X,
        label: () => t('placement.results.status.incorrect'),
        class: 'text-destructive',
    },
    dont_know: {
        icon: Minus,
        label: () => t('placement.results.status.dontKnow'),
        class: 'text-muted-foreground',
    },
};

const skillsWithItems = props.result.skills.filter(
    (skill) => skill.items.length > 0,
);

const openAttemptText = computed(() =>
    props.openAttempt?.skill
        ? t('placement.results.openSkill', {
              skill: skillLabel(props.openAttempt.skill).toLowerCase(),
          })
        : t('placement.results.openTest'),
);
</script>

<template>
    <Head
        :title="t('placement.results.title', { language: props.language.name })"
    />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">
                {{
                    t('placement.results.title', {
                        language: props.language.name,
                    })
                }}
            </h1>
            <p class="mt-1 text-muted-foreground">
                {{ t('placement.results.intro') }}
            </p>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>{{
                    t('placement.results.startingLevel')
                }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-semibold">
                        {{ props.result.blendedLevel ?? '—' }}
                    </span>
                    <span class="text-muted-foreground">{{
                        t('placement.results.blendedLevel')
                    }}</span>
                </div>
                <div class="grid grid-cols-2 gap-x-6 gap-y-2 sm:grid-cols-4">
                    <div
                        v-for="skill in props.result.skills"
                        :key="skill.skill"
                        class="flex flex-col"
                    >
                        <span class="text-sm text-muted-foreground">
                            {{ skillLabel(skill.skill) }}
                        </span>
                        <span class="font-medium">{{
                            skill.level ?? '—'
                        }}</span>
                        <RetakeSkillButton
                            v-if="!props.openAttempt"
                            class="mt-2"
                            :skill="skill.skill"
                            :available-on="
                                props.retakeAvailableOn[skill.skill] ?? null
                            "
                        />
                    </div>
                </div>
                <p
                    v-if="!props.openAttempt"
                    class="text-sm text-muted-foreground"
                >
                    {{ t('placement.results.retakeHint') }}
                </p>
                <div
                    v-else
                    class="flex flex-wrap items-center justify-between gap-3 rounded-md border p-3 text-sm"
                >
                    <span>{{ openAttemptText }}</span>
                    <Button size="sm" as-child>
                        <Link :href="placementIndex().url">{{
                            t('common.continue')
                        }}</Link>
                    </Button>
                </div>
            </CardContent>
        </Card>

        <p v-if="props.result.skipped" class="text-muted-foreground">
            {{ t('placement.results.skipped') }}
            <Link
                :href="placementIndex().url"
                class="text-foreground underline underline-offset-4"
                >{{ t('placement.results.takeTest') }}</Link
            >
            {{ t('placement.results.skippedAfter') }}
        </p>

        <div v-else class="flex flex-col gap-6">
            <h2 class="text-lg font-medium">
                {{ t('placement.results.questionByQuestion') }}
            </h2>

            <Card v-for="skill in skillsWithItems" :key="skill.skill">
                <CardHeader>
                    <CardTitle class="flex items-center justify-between">
                        <span>{{ skillLabel(skill.skill) }}</span>
                        <span class="text-sm font-normal text-muted-foreground">
                            {{ skill.level ?? '—' }}
                        </span>
                    </CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-4">
                    <div
                        v-for="(item, index) in skill.items"
                        :key="index"
                        class="flex gap-3"
                    >
                        <component
                            :is="statusMeta[item.status].icon"
                            class="mt-0.5 size-5 shrink-0"
                            :class="statusMeta[item.status].class"
                            :aria-label="statusMeta[item.status].label()"
                        />
                        <div class="flex flex-col gap-1">
                            <p class="font-medium">{{ item.prompt }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ t('placement.results.yourAnswer') }}
                                <span
                                    v-if="item.yourAnswer !== null"
                                    class="text-foreground"
                                >
                                    {{ item.yourAnswer }}
                                </span>
                                <span v-else class="italic">{{
                                    t('placement.results.didntKnowAnswer')
                                }}</span>
                            </p>
                            <p
                                v-if="item.status !== 'correct'"
                                class="text-sm text-muted-foreground"
                            >
                                {{ t('placement.results.correctAnswer') }}
                                <span class="text-foreground">
                                    {{ item.correctAnswer }}
                                </span>
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <div class="border-t pt-6">
            <Button as-child>
                <Link :href="dashboard().url">{{
                    t('placement.results.toDashboard')
                }}</Link>
            </Button>
        </div>
    </div>
</template>
