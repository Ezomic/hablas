<script setup lang="ts">
import SpeakButton from '@/components/SpeakButton.vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export interface UnitVocabularyItem {
    id: number;
    term: string;
    translation: string;
    partOfSpeech: string;
    isCognate: boolean;
    contrastNote: string | null;
}

export interface UnitGrammarPoint {
    id: number;
    title: string;
    explanation: string;
}

const props = defineProps<{
    vocabularyItems: UnitVocabularyItem[];
    grammarPoints: UnitGrammarPoint[];
    speechLocale: string | null;
}>();
</script>

<template>
    <div class="flex flex-col gap-8">
        <section
            v-if="props.vocabularyItems.length"
            class="flex flex-col gap-3"
        >
            <h2 class="text-lg font-medium">Vocabulary</h2>

            <Card v-for="item in props.vocabularyItems" :key="item.id">
                <CardContent class="flex flex-col gap-1 py-4">
                    <div class="flex items-baseline justify-between gap-4">
                        <span
                            class="flex items-center gap-1 text-lg font-medium"
                        >
                            {{ item.term }}
                            <SpeakButton
                                :text="item.term"
                                :locale="props.speechLocale"
                            />
                        </span>
                        <span class="text-muted-foreground">{{
                            item.translation
                        }}</span>
                    </div>
                    <div
                        class="flex items-center gap-2 text-xs text-muted-foreground"
                    >
                        <span>{{ item.partOfSpeech }}</span>
                        <Badge v-if="item.isCognate" variant="outline"
                            >cognate</Badge
                        >
                    </div>
                    <p v-if="item.contrastNote" class="text-sm text-amber-600">
                        {{ item.contrastNote }}
                    </p>
                </CardContent>
            </Card>
        </section>

        <section v-if="props.grammarPoints.length" class="flex flex-col gap-3">
            <h2 class="text-lg font-medium">Grammar</h2>

            <Card v-for="point in props.grammarPoints" :key="point.id">
                <CardHeader>
                    <CardTitle class="text-base">{{ point.title }}</CardTitle>
                </CardHeader>
                <CardContent class="text-sm text-muted-foreground">
                    {{ point.explanation }}
                </CardContent>
            </Card>
        </section>
    </div>
</template>
