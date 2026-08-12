<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import SpeakButton from '@/components/SpeakButton.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { skillLabels } from '@/lib/skillLabels';
import { store as completeUnit } from '@/routes/units/completion';

interface Unit {
    id: number;
    title: string;
    taskDescription: string;
    cefrLevel: string;
    primarySkill: string;
    contrastNote: string | null;
}

interface VocabularyItem {
    id: number;
    term: string;
    translation: string;
    partOfSpeech: string;
    isCognate: boolean;
    contrastNote: string | null;
}

interface GrammarPoint {
    id: number;
    title: string;
    explanation: string;
}

const props = defineProps<{
    unit: Unit;
    vocabularyItems: VocabularyItem[];
    grammarPoints: GrammarPoint[];
    isCompleted: boolean;
    speechLocale: string | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Unit', href: '/dashboard' }],
    },
});

const form = useForm({});

function complete() {
    form.post(completeUnit(props.unit.id).url);
}
</script>

<template>
    <Head :title="props.unit.title" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <Badge variant="secondary">{{ props.unit.cefrLevel }}</Badge>
                <Badge variant="outline">{{
                    skillLabels[props.unit.primarySkill] ??
                    props.unit.primarySkill
                }}</Badge>
            </div>
            <h1 class="text-2xl font-semibold">{{ props.unit.title }}</h1>
            <p class="text-muted-foreground">
                {{ props.unit.taskDescription }}
            </p>
        </div>

        <Card v-if="props.unit.contrastNote">
            <CardHeader>
                <CardDescription>Watch out for</CardDescription>
            </CardHeader>
            <CardContent class="text-sm">
                {{ props.unit.contrastNote }}
            </CardContent>
        </Card>

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

        <p v-if="props.isCompleted" class="text-sm text-muted-foreground">
            You've already completed this unit. Its cards are in your review
            deck.
        </p>

        <Button :disabled="form.processing" @click="complete">
            <Spinner v-if="form.processing" />
            {{
                props.isCompleted
                    ? 'Mark complete again'
                    : 'Complete unit and add to review deck'
            }}
        </Button>
    </div>
</template>
