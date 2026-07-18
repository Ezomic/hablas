<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Check, Minus, X } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';

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
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Placement results', href: '/placement/results' }],
    },
});

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

const statusMeta: Record<
    BreakdownItem['status'],
    { icon: typeof Check; label: string; class: string }
> = {
    correct: { icon: Check, label: 'Correct', class: 'text-green-600 dark:text-green-500' },
    incorrect: { icon: X, label: 'Incorrect', class: 'text-destructive' },
    dont_know: { icon: Minus, label: "Didn't know", class: 'text-muted-foreground' },
};

const skillsWithItems = props.result.skills.filter((skill) => skill.items.length > 0);
</script>

<template>
    <Head :title="`${props.language.name} placement results`" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">
                {{ props.language.name }} placement results
            </h1>
            <p class="mt-1 text-muted-foreground">
                This is the starting point the test set for you. Your blended
                level is the lowest of the four skills — deliberately
                conservative, so nothing feels out of reach.
            </p>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Your starting level</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-semibold">
                        {{ props.result.blendedLevel ?? '—' }}
                    </span>
                    <span class="text-muted-foreground">blended level</span>
                </div>
                <div class="grid grid-cols-2 gap-x-6 gap-y-2 sm:grid-cols-4">
                    <div
                        v-for="skill in props.result.skills"
                        :key="skill.skill"
                        class="flex flex-col"
                    >
                        <span class="text-sm text-muted-foreground">
                            {{ skillLabels[skill.skill] ?? skill.skill }}
                        </span>
                        <span class="font-medium">{{ skill.level ?? '—' }}</span>
                    </div>
                </div>
            </CardContent>
        </Card>

        <p v-if="props.result.skipped" class="text-muted-foreground">
            You skipped the placement test, so every skill starts at A1. Take the
            test any time to set a more accurate level.
        </p>

        <div v-else class="flex flex-col gap-6">
            <h2 class="text-lg font-medium">Question by question</h2>

            <Card v-for="skill in skillsWithItems" :key="skill.skill">
                <CardHeader>
                    <CardTitle class="flex items-center justify-between">
                        <span>{{ skillLabels[skill.skill] ?? skill.skill }}</span>
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
                            :aria-label="statusMeta[item.status].label"
                        />
                        <div class="flex flex-col gap-1">
                            <p class="font-medium">{{ item.prompt }}</p>
                            <p class="text-sm text-muted-foreground">
                                Your answer:
                                <span
                                    v-if="item.yourAnswer !== null"
                                    class="text-foreground"
                                >
                                    {{ item.yourAnswer }}
                                </span>
                                <span v-else class="italic">didn't know</span>
                            </p>
                            <p
                                v-if="item.status !== 'correct'"
                                class="text-sm text-muted-foreground"
                            >
                                Correct answer:
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
                <Link :href="dashboard().url">Continue to dashboard</Link>
            </Button>
        </div>
    </div>
</template>
