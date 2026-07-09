<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

export interface ProgressSnapshot {
    language: { code: string; name: string };
    blendedLevel: string | null;
    skillLevels: Record<string, string>;
    streak: { currentLength: number; longestLength: number };
    unitCompletionPercentage: number;
    topErrorTags: { category: string; count: number }[];
}

defineProps<{
    snapshot: ProgressSnapshot;
}>();

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

const errorTagLabels: Record<string, string> = {
    wrong_gender: 'Wrong gender',
    ser_estar_confusion: 'Ser/estar confusion',
    false_friend: 'False friend',
    wrong_tense: 'Wrong tense',
    portunol_slip: 'Portuñol slip',
    other: 'Other',
};

function pluralizeDays(count: number): string {
    return count === 1 ? 'day' : 'days';
}
</script>

<template>
    <div class="flex flex-col gap-4">
        <Card>
            <CardHeader>
                <CardDescription>{{ snapshot.language.name }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ snapshot.blendedLevel ?? '—' }}
                </CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-2">
                <div
                    v-for="skill in Object.keys(skillLabels)"
                    :key="skill"
                    class="flex items-center justify-between border-b pb-2 text-sm last:border-b-0"
                >
                    <span>{{ skillLabels[skill] }}</span>
                    <span class="font-medium">{{
                        snapshot.skillLevels[skill] ?? '—'
                    }}</span>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardDescription>Streak</CardDescription>
                <CardTitle class="text-4xl">
                    {{ snapshot.streak.currentLength }}
                    {{ pluralizeDays(snapshot.streak.currentLength) }}
                </CardTitle>
            </CardHeader>
            <CardContent class="text-sm text-muted-foreground">
                Longest streak: {{ snapshot.streak.longestLength }}
                {{ pluralizeDays(snapshot.streak.longestLength) }}
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardDescription>Units completed</CardDescription>
                <CardTitle class="text-4xl">
                    {{ snapshot.unitCompletionPercentage }}%
                </CardTitle>
            </CardHeader>
        </Card>

        <Card v-if="snapshot.topErrorTags.length > 0">
            <CardHeader>
                <CardDescription>Frequently mixed up</CardDescription>
            </CardHeader>
            <CardContent class="flex flex-col gap-2">
                <div
                    v-for="tag in snapshot.topErrorTags"
                    :key="tag.category"
                    class="flex items-center justify-between text-sm"
                >
                    <span>{{
                        errorTagLabels[tag.category] ?? tag.category
                    }}</span>
                    <span class="text-muted-foreground">{{ tag.count }}×</span>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
