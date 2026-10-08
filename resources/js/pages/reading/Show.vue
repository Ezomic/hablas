<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import SpeakButton from '@/components/SpeakButton.vue';
import StoryPlayer from '@/components/StoryPlayer.vue';
import type { StorySegment } from '@/components/StoryPlayer.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { showMilestone } from '@/lib/milestone';
import { index, show } from '@/routes/reading';
import { store as storeAttempt } from '@/routes/reading/attempts';

interface Question {
    prompt: string;
    options: string[];
}

interface Passage {
    id: number;
    title: string;
    body: string;
    cefrLevel: string;
    glosses: Record<string, string>;
    locale: string | null;
    segments: StorySegment[];
    questions: Question[];
}

const props = defineProps<{
    passage: Passage;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('reading.title'), href: index() },
    { title: props.passage.title, href: show(props.passage.id) },
]);

const { submitOrQueue } = useOfflineSync();

const answers = ref<string[]>(props.passage.questions.map(() => ''));
const correct = ref<string[] | null>(null);
const score = ref<number | null>(null);
const isSubmitting = ref(false);
const isQueued = ref(false);
const errorMessage = ref<string | null>(null);
const opened = ref<string | null>(null);

const allAnswered = computed(() =>
    answers.value.every((answer) => answer !== ''),
);

const paragraphs = computed(() =>
    props.passage.body.split(/\n{2,}/).map((paragraph) =>
        paragraph.split(/(\s+)/).map((token) => {
            const word = token
                .toLowerCase()
                .replace(/^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/gu, '');

            return {
                token,
                word,
                meaning: props.passage.glosses[word] ?? null,
            };
        }),
    ),
);

const openedMeaning = computed(() =>
    opened.value === null ? null : props.passage.glosses[opened.value],
);

function toggle(word: string) {
    opened.value = opened.value === word ? null : word;
}

async function submit() {
    if (isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    errorMessage.value = null;
    isQueued.value = false;

    try {
        const result = await submitOrQueue(storeAttempt(props.passage.id).url, {
            answers: answers.value,
        });

        if (result.queued) {
            isQueued.value = true;

            return;
        }

        if (!result.response.ok) {
            errorMessage.value = t('practice.submitFailed');

            return;
        }

        const data = (await result.response.json()) as {
            score: number;
            correct: string[];
        };
        score.value = data.score;
        correct.value = data.correct;
        showMilestone(data);
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Head :title="props.passage.title" />

    <div class="mx-auto flex max-w-2xl flex-col gap-6 p-4">
        <p class="text-sm text-muted-foreground">{{ t('reading.read') }}</p>

        <Card>
            <CardHeader>
                <div class="flex items-center justify-between gap-2">
                    <Badge variant="secondary">{{
                        props.passage.cefrLevel
                    }}</Badge>
                    <StoryPlayer
                        v-if="props.passage.segments.some((s) => s.audioUrl)"
                        :segments="props.passage.segments"
                    />
                    <SpeakButton
                        v-else
                        :text="props.passage.body"
                        :locale="props.passage.locale"
                        :label="t('reading.listen')"
                    />
                </div>
                <CardTitle class="text-xl">{{ props.passage.title }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p
                    v-for="(paragraph, p) in paragraphs"
                    :key="p"
                    class="text-lg leading-relaxed"
                    data-testid="paragraph"
                >
                    <template v-for="(part, i) in paragraph" :key="i">
                        <button
                            v-if="part.meaning !== null"
                            type="button"
                            class="rounded-sm underline decoration-dotted underline-offset-4"
                            :class="{
                                'bg-amber-100 dark:bg-amber-900':
                                    opened === part.word,
                            }"
                            @click="toggle(part.word)"
                        >
                            {{ part.token }}
                        </button>
                        <template v-else>{{ part.token }}</template>
                    </template>
                </p>
                <p
                    class="min-h-6 text-sm"
                    :class="
                        openedMeaning === null
                            ? 'text-muted-foreground'
                            : 'font-medium'
                    "
                    data-testid="gloss"
                >
                    <template v-if="openedMeaning !== null">
                        {{
                            t('reading.gloss', {
                                word: opened,
                                meaning: openedMeaning,
                            })
                        }}
                    </template>
                    <template v-else>{{ t('reading.tapWord') }}</template>
                </p>
            </CardContent>
        </Card>

        <h2 class="text-lg font-semibold">
            {{ t('reading.questionsHeading') }}
        </h2>

        <form class="flex flex-col gap-4" @submit.prevent="submit">
            <Card v-for="(question, q) in props.passage.questions" :key="q">
                <CardHeader>
                    <CardTitle class="text-base">{{
                        question.prompt
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-2">
                    <RadioGroup
                        v-model="answers[q]"
                        :disabled="score !== null"
                        class="flex flex-col gap-2"
                    >
                        <div
                            v-for="option in question.options"
                            :key="option"
                            class="flex items-center gap-2"
                        >
                            <RadioGroupItem
                                :id="`q${q}-${option}`"
                                :value="option"
                            />
                            <Label :for="`q${q}-${option}`">{{ option }}</Label>
                        </div>
                    </RadioGroup>
                    <template v-if="correct !== null">
                        <p
                            v-if="answers[q] === correct[q]"
                            class="text-sm font-medium text-green-700 dark:text-green-400"
                            data-testid="right"
                        >
                            {{ t('reading.correct') }}
                        </p>
                        <p
                            v-else
                            class="text-sm font-medium text-red-700 dark:text-red-400"
                            data-testid="wrong"
                        >
                            {{ t('reading.answerWas', { answer: correct[q] }) }}
                        </p>
                    </template>
                </CardContent>
            </Card>

            <Button
                v-if="score === null"
                type="submit"
                :disabled="!allAnswered || isSubmitting"
            >
                {{ t('practice.checkAnswers') }}
            </Button>
        </form>

        <p v-if="isQueued" class="text-sm text-muted-foreground">
            {{ t('practice.offlineQueued') }}
        </p>
        <p v-else-if="score !== null" class="text-lg font-medium">
            {{ t('practice.scored', { score }) }}
        </p>
        <p v-if="errorMessage" class="text-sm text-red-600 dark:text-red-500">
            {{ errorMessage }}
        </p>

        <Link
            v-if="score !== null"
            :href="index()"
            class="text-sm underline underline-offset-4"
        >
            {{ t('reading.back') }}
        </Link>
    </div>
</template>
