<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Check, ChevronDown } from '@lucide/vue';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import DayStrip from '@/components/DayStrip.vue';
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
import UnitHero from '@/components/UnitHero.vue';
import UnitLessonList from '@/components/UnitLessonList.vue';
import type {
    UnitGrammarPoint,
    UnitVocabularyItem,
} from '@/components/UnitReference.vue';
import UnitReference from '@/components/UnitReference.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { skillLabel } from '@/lib/skillLabels';
import { store as startRun } from '@/routes/lessons/runs';
import { index as unitsIndex, show as showUnit } from '@/routes/units';
import { store as completeUnit } from '@/routes/units/completion';
import type { UnitLessonOverview } from '@/types/lesson';
import type {
    DayProgress,
    UnitProgress,
    UnitStruggles,
    UnitTraining,
} from '@/types/unit';

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
    progress: UnitProgress;
    day: DayProgress;
    struggles: UnitStruggles;
    training: UnitTraining;
    speechLocale: string | null;
}>();

const { t } = useI18n();

// The last crumb is drawn as the current page, so Units has to come before the
// unit's own title or it is not a link back to the list.
useBreadcrumbs(() => [
    { title: t('nav.units'), href: unitsIndex() },
    { title: props.unit.title, href: showUnit(props.unit.id) },
]);

const form = useForm({});
const referenceOpen = ref(false);

const nextLesson = computed(
    () =>
        props.lessons?.lessons.find(
            (row) =>
                row.lessonId !== null &&
                ['available', 'in_progress'].includes(row.state),
        ) ?? null,
);

const started = computed(
    () =>
        props.lessons?.lessons.some((row) =>
            ['in_progress', 'completed'].includes(row.state),
        ) ?? false,
);

const starting = ref(false);

function continueUnit() {
    if (nextLesson.value?.lessonId == null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({
            unit: props.unit.id,
            lesson: nextLesson.value.lessonId,
        }).url,
        {},
        { onFinish: () => (starting.value = false) },
    );
}

const trainedSkills = computed(() =>
    props.training.skills.filter((row) => row.total > 0),
);

function takeTest(skill: string) {
    if (props.training.lessonId === null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({
            unit: props.unit.id,
            lesson: props.training.lessonId,
        }).url,
        { kind: 'skill_test', skill },
        { onFinish: () => (starting.value = false) },
    );
}

function practiseSkill(skill: string) {
    if (props.training.lessonId === null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({
            unit: props.unit.id,
            lesson: props.training.lessonId,
        }).url,
        { kind: 'practice', skill },
        { onFinish: () => (starting.value = false) },
    );
}

function practiseStruggles() {
    if (props.struggles.lessonId === null) {
        return;
    }

    starting.value = true;
    router.post(
        startRun({
            unit: props.unit.id,
            lesson: props.struggles.lessonId,
        }).url,
        { kind: 'practice' },
        { onFinish: () => (starting.value = false) },
    );
}

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

        <DayStrip :day="props.day" />

        <UnitHero
            v-if="props.lessons"
            :progress="props.progress"
            :percent="props.training.percent"
            :skills="{
                total: trainedSkills.length,
                mastered: trainedSkills.filter((row) => row.mastered).length,
            }"
            :can-continue="
                nextLesson !== null && props.availability !== 'held_back'
            "
            :started="started"
            :busy="starting"
            @continue="continueUnit"
        />

        <Card v-if="props.lessons" data-testid="training">
            <CardHeader>
                <CardDescription>{{
                    t('units.training.title')
                }}</CardDescription>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <ul class="flex flex-col gap-3">
                    <li
                        v-for="row in props.training.skills.filter(
                            (item) => item.total > 0,
                        )"
                        :key="row.skill"
                        class="flex items-center gap-3"
                        :data-testid="`training-${row.skill}`"
                    >
                        <div class="flex min-w-0 flex-1 flex-col gap-1">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">{{
                                    skillLabel(row.skill)
                                }}</span>
                                <span class="text-muted-foreground">
                                    {{ row.done }}/{{ row.total }}
                                </span>
                            </div>
                            <div
                                class="h-2 overflow-hidden rounded-full bg-muted"
                            >
                                <div
                                    class="h-full rounded-full bg-primary"
                                    :style="{ width: `${row.percent}%` }"
                                />
                            </div>
                        </div>
                        <Badge v-if="row.mastered" variant="secondary">
                            <Check class="size-4 text-green-600" />
                            {{ t('units.training.mastered') }}
                        </Badge>
                        <Button
                            v-else-if="row.percent >= 100"
                            size="sm"
                            :disabled="
                                starting || props.training.lessonId === null
                            "
                            data-testid="take-test"
                            @click="takeTest(row.skill)"
                        >
                            {{ t('units.training.test') }}
                        </Button>
                        <Button
                            v-else
                            size="sm"
                            variant="outline"
                            :disabled="
                                starting || props.training.lessonId === null
                            "
                            data-testid="practise-skill"
                            @click="practiseSkill(row.skill)"
                        >
                            {{ t('units.training.practise') }}
                        </Button>
                    </li>
                </ul>
                <p class="text-sm text-muted-foreground">
                    {{ t('units.training.note') }}
                </p>
                <p class="border-t pt-4 text-sm font-medium">
                    {{ t('units.struggles.title') }}
                </p>
                <div class="flex flex-col gap-3" data-testid="struggles">
                    <template v-if="props.struggles.count > 0">
                        <p class="font-medium">
                            {{
                                t(
                                    'units.struggles.count',
                                    { count: props.struggles.count },
                                    props.struggles.count,
                                )
                            }}
                        </p>
                        <ul
                            class="flex flex-wrap gap-2"
                            data-testid="struggle-words"
                        >
                            <li
                                v-for="item in props.struggles.items"
                                :key="item.label"
                            >
                                <Badge variant="outline">
                                    {{ item.label
                                    }}<span
                                        v-if="item.meaning"
                                        class="ml-1 text-muted-foreground"
                                        >{{ item.meaning }}</span
                                    >
                                </Badge>
                            </li>
                        </ul>
                        <Button
                            class="w-fit"
                            :disabled="
                                starting || props.struggles.lessonId === null
                            "
                            data-testid="practise-struggles"
                            @click="practiseStruggles"
                        >
                            {{ t('units.struggles.practise') }}
                        </Button>
                    </template>
                    <p v-else class="text-sm text-muted-foreground">
                        {{ t('units.struggles.none') }}
                    </p>
                </div>
            </CardContent>
        </Card>

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
