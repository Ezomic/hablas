<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { store as storeAttempt } from '@/routes/pronunciation-drills/attempts';

interface Exercise {
    id: number;
    word_a: string;
    word_a_translation_en: string;
    word_b: string;
    word_b_translation_en: string;
    target_word: string;
    audio_url: string | null;
}

const props = defineProps<{
    exercise: Exercise | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Pronunciation drills', href: '/pronunciation-drills' },
        ],
    },
});

const { submitOrQueue } = useOfflineSync();

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
    recognition.lang = 'pt-PT';
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onresult = (event) => {
        transcriptGuess.value = event.results[0][0].transcript;
        void submitAttempt();
    };

    recognition.onerror = () => {
        isRecording.value = false;
        errorMessage.value = "We couldn't hear that clearly. Try again.";
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
        errorMessage.value = "Couldn't submit that attempt. Try again.";

        return;
    }

    const data = (await result.response.json()) as { is_correct: boolean };
    isCorrect.value = data.is_correct;
}
</script>

<template>
    <Head title="Pronunciation drills" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Pronunciation drills</h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>
                    <span class="font-bold">{{
                        props.exercise.target_word
                    }}</span>
                    <span class="text-muted-foreground"> vs. </span>
                    <span class="text-muted-foreground">{{
                        props.exercise.target_word === props.exercise.word_a
                            ? props.exercise.word_b
                            : props.exercise.word_a
                    }}</span>
                </CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p class="text-sm text-muted-foreground">
                    {{ props.exercise.word_a }} ({{
                        props.exercise.word_a_translation_en
                    }}) &middot; {{ props.exercise.word_b }} ({{
                        props.exercise.word_b_translation_en
                    }})
                </p>
                <p class="text-sm text-muted-foreground">
                    Say:
                    <span class="font-bold text-foreground">{{
                        props.exercise.target_word
                    }}</span>
                </p>

                <p v-if="!isSupported" class="text-sm text-muted-foreground">
                    Your browser doesn't support speech recognition. Try Chrome
                    on desktop.
                </p>
                <Button v-else :disabled="isRecording" @click="startRecording">
                    {{ isRecording ? 'Listening…' : 'Say this word' }}
                </Button>

                <p v-if="transcriptGuess" class="text-sm text-muted-foreground">
                    You said: "{{ transcriptGuess }}"
                </p>
                <p v-if="isQueued" class="text-sm text-muted-foreground">
                    You're offline — this attempt is saved and will be scored
                    once you're back online.
                </p>
                <p
                    v-else-if="isCorrect === true"
                    class="text-lg font-medium text-green-600 dark:text-green-500"
                >
                    Correct!
                </p>
                <p
                    v-else-if="isCorrect === false"
                    class="text-lg font-medium text-red-600 dark:text-red-500"
                >
                    Try again.
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
            No pronunciation drills available yet.
        </p>
    </div>
</template>
