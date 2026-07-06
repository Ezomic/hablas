<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { ref } from 'vue';
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
import { dashboard } from '@/routes';

interface Streak {
    currentLength: number;
    longestLength: number;
    freezeDaysRemaining: number;
}

interface Props {
    language: { code: string; name: string } | null;
    blendedLevel?: string | null;
    skillLevels?: Record<string, string>;
    streak?: Streak;
}

const props = defineProps<Props>();

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

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

const breakdownOpen = ref(false);

function pluralizeDays(count: number): string {
    return count === 1 ? 'day' : 'days';
}
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
    </div>
</template>
