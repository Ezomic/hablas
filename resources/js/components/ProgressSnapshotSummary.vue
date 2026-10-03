<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { skillKeys, skillLabel } from '@/lib/skillLabels';

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

const { t, te } = useI18n();

function errorTagLabel(category: string): string {
    const key = `progress.errorTags.${category}`;

    return te(key) ? t(key) : category;
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
                    v-for="skill in skillKeys"
                    :key="skill"
                    class="flex items-center justify-between border-b pb-2 text-sm last:border-b-0"
                >
                    <span>{{ skillLabel(skill) }}</span>
                    <span class="font-medium">{{
                        snapshot.skillLevels[skill] ?? '—'
                    }}</span>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardDescription>{{
                    t('dashboard.streak.title')
                }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ t('common.days', snapshot.streak.currentLength) }}
                </CardTitle>
            </CardHeader>
            <CardContent class="text-sm text-muted-foreground">
                {{
                    t('dashboard.streak.longest', {
                        days: t('common.days', snapshot.streak.longestLength),
                    })
                }}
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardDescription>{{
                    t('progress.unitsCompleted')
                }}</CardDescription>
                <CardTitle class="text-4xl">
                    {{ snapshot.unitCompletionPercentage }}%
                </CardTitle>
            </CardHeader>
        </Card>

        <Card v-if="snapshot.topErrorTags.length > 0">
            <CardHeader>
                <CardDescription>{{ t('progress.mixedUp') }}</CardDescription>
            </CardHeader>
            <CardContent class="flex flex-col gap-2">
                <div
                    v-for="tag in snapshot.topErrorTags"
                    :key="tag.category"
                    class="flex items-center justify-between text-sm"
                >
                    <span>{{ errorTagLabel(tag.category) }}</span>
                    <span class="text-muted-foreground">{{ tag.count }}×</span>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
