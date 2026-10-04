<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { showMilestone } from '@/lib/milestone';
import { index } from '@/routes/pronunciation-drills';
import { store as storeAttempt } from '@/routes/pronunciation-drills/attempts';

interface Exercise {
    id: number;
    word_a: string;
    word_a_translation_en: string;
    word_b: string;
    word_b_translation_en: string;
    target_word: string;
    audioUrl: string | null;
    audioSlowUrl: string | null;
}

const props = defineProps<{
    exercise: Exercise | null;
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.pronunciationDrills'), href: index() }]);

const { submitOrQueue } = useOfflineSync();

function wordPair(exercise: Exercise): string {
    return `${exercise.word_a} (${exercise.word_a_translation_en}) · ${exercise.word_b} (${exercise.word_b_translation_en})`;
}

const isSupported =
    'SpeechRecognition' in window || 'webkitSpeechRecognition' in window;
const isRecording = ref(false);
const transcriptGuess = ref<string | null>(null);
const isCorrect = ref<boolean | null>(null);
const isQueued = ref(false);
const errorMessage = ref<string | null>(null);

function startRecording() {
    if (!props.exercise) {
        return;
    }

    const SpeechRecognitionCtor =
        window.SpeechRecognition ?? window.webkitSpeechRecognition;

    if (!SpeechRecognitionCtor) {
        return;
    }

    errorMessage.value = null;
    isCorrect.value = null;
    isQueued.value = false;
    transcriptGuess.value = null;

    const recognition = new SpeechRecognitionCtor();

    // Left on the browser default when the server has no tag for this
    // language, rather than asserting one that would mistranscribe.
    if (props.speechLocale) {
        recognition.lang = props.speechLocale;
    }

    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onresult = (event) => {
        transcriptGuess.value = event.results[0][0].transcript;
        void submitAttempt();
    };

    recognition.onerror = () => {
        isRecording.value = false;
        errorMessage.value = t('practice.notHeard');
    };

    recognition.onend = () => {
        isRecording.value = false;
    };

    isRecording.value = true;
    recognition.start();
}

async function submitAttempt() {
    if (!props.exercise || !transcriptGuess.value) {
        return;
    }

    const result = await submitOrQueue(storeAttempt(props.exercise.id).url, {
        transcript_guess: transcriptGuess.value,
    });

    if (result.queued) {
        isQueued.value = true;

        return;
    }

    if (!result.response.ok) {
        errorMessage.value = t('practice.attemptFailed');

        return;
    }

    const data = (await result.response.json()) as { is_correct: boolean };
    isCorrect.value = data.is_correct;
    showMilestone(data);
}
</script>

<template>
    <Head :title="t('nav.pronunciationDrills')" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">
            {{ t('nav.pronunciationDrills') }}
        </h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>
                    <span class="font-bold">{{
                        props.exercise.target_word
                    }}</span>
                    <span class="text-muted-foreground">
                        {{ t('pronunciation.versus') }}
                    </span>
                    <span class="text-muted-foreground">{{
                        props.exercise.target_word === props.exercise.word_a
                            ? props.exercise.word_b
                            : props.exercise.word_a
                    }}</span>
                </CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    {{ wordPair(props.exercise) }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ t('pronunciation.say') }}
                    <span class="font-bold text-foreground">{{
                        props.exercise.target_word
                    }}</span>
                </p>

                <p v-if="!isSupported" class="text-sm text-muted-foreground">
                    {{ t('practice.noSpeechRecognition') }}
                </p>
                <Button v-else :disabled="isRecording" @click="startRecording">
                    {{
                        isRecording
                            ? t('practice.listening')
                            : t('pronunciation.sayWord')
                    }}
                </Button>

                <p v-if="transcriptGuess" class="text-sm text-muted-foreground">
                    {{ t('practice.youSaid', { text: transcriptGuess }) }}
                </p>
                <p v-if="isQueued" class="text-sm text-muted-foreground">
                    {{ t('practice.offlineAttempt') }}
                </p>
                <p
                    v-else-if="isCorrect === true"
                    class="text-lg font-medium text-green-600 dark:text-green-500"
                >
                    {{ t('pronunciation.correct') }}
                </p>
                <p
                    v-else-if="isCorrect === false"
                    class="text-lg font-medium text-red-600 dark:text-red-500"
                >
                    {{ t('pronunciation.tryAgain') }}
                </p>
                <p
                    v-if="errorMessage"
                    class="text-sm text-red-600 dark:text-red-500"
                >
                    {{ errorMessage }}
                </p>
            </CardContent>
        </Card>

        <p v-else class="text-muted-foreground">
            {{ t('pronunciation.empty') }}
        </p>
    </div>
</template>
