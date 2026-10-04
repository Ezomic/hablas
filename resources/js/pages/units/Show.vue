<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
} from '@/components/ui/card';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import UnitLessonList from '@/components/UnitLessonList.vue';
import type {
    UnitGrammarPoint,
    UnitVocabularyItem,
} from '@/components/UnitReference.vue';
import UnitReference from '@/components/UnitReference.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { skillLabel } from '@/lib/skillLabels';
import { index as unitsIndex } from '@/routes/units';
import { store as completeUnit } from '@/routes/units/completion';
import type { UnitLessonOverview } from '@/types/lesson';

interface Unit {
    id: number;
    title: string;
    taskDescription: string;
    cefrLevel: string;
    primarySkill: string;
    contrastNote: string | null;
}

const props = defineProps<{
    unit: Unit;
    vocabularyItems: UnitVocabularyItem[];
    grammarPoints: UnitGrammarPoint[];
    isCompleted: boolean;
    availability?: string;
    lessons?: UnitLessonOverview | null;
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.units'), href: unitsIndex() }]);

const form = useForm({});
const referenceOpen = ref(false);

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
                    skillLabel(props.unit.primarySkill)
                }}</Badge>
            </div>
            <h1 class="text-2xl font-semibold">{{ props.unit.title }}</h1>
            <p class="text-muted-foreground">
                {{ props.unit.taskDescription }}
            </p>
        </div>

        <Card v-if="props.unit.contrastNote">
            <CardHeader>
                <CardDescription>{{ t('units.watchOut') }}</CardDescription>
            </CardHeader>
            <CardContent class="text-sm">
                {{ props.unit.contrastNote }}
            </CardContent>
        </Card>

        <template v-if="props.lessons">
            <UnitLessonList
                :unit-id="props.unit.id"
                :overview="props.lessons"
                :is-held-back="props.availability === 'held_back'"
            />

            <Collapsible
                v-model:open="referenceOpen"
                class="flex flex-col gap-3"
            >
                <CollapsibleTrigger
                    class="flex w-fit items-center gap-1 text-sm font-medium text-muted-foreground hover:text-foreground"
                >
                    {{ t('units.wordsAndGrammar') }}
                    <ChevronDown
                        class="size-4 transition-transform"
                        :class="{ 'rotate-180': referenceOpen }"
                    />
                </CollapsibleTrigger>
                <CollapsibleContent>
                    <UnitReference
                        :vocabulary-items="props.vocabularyItems"
                        :grammar-points="props.grammarPoints"
                        :speech-locale="props.speechLocale"
                    />
                </CollapsibleContent>
            </Collapsible>
        </template>

        <template v-else>
            <UnitReference
                :vocabulary-items="props.vocabularyItems"
                :grammar-points="props.grammarPoints"
                :speech-locale="props.speechLocale"
            />

            <p v-if="props.isCompleted" class="text-sm text-muted-foreground">
                {{ t('units.alreadyCompleted') }}
            </p>

            <Button :disabled="form.processing" @click="complete">
                <AppSpinner v-if="form.processing" />
                {{
                    props.isCompleted
                        ? t('units.markAgain')
                        : t('units.complete')
                }}
            </Button>
        </template>
    </div>
</template>
