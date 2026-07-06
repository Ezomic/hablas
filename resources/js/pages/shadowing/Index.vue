<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { getCsrfToken } from '@/lib/csrf';
import { store as storeAttempt } from '@/routes/shadowing/attempts';

interface Exercise {
    id: number;
    target_transcript: string;
    audio_url: string | null;
}

const props = defineProps<{
    exercise: Exercise | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Shadowing practice', href: '/shadowing' }],
    },
});

const isSupported =
    'SpeechRecognition' in window || 'webkitSpeechRecognition' in window;
const isRecording = ref(false);
const transcriptGuess = ref<string | null>(null);
const score = ref<number | null>(null);
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
    score.value = null;
    transcriptGuess.value = null;

    const recognition = new SpeechRecognitionCtor();
    recognition.lang = 'es-ES';
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

    const response = await fetch(storeAttempt(props.exercise.id).url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify({ transcript_guess: transcriptGuess.value }),
    });

    const data = (await response.json()) as { score: number };
    score.value = data.score;
}
</script>

<template>
    <Head title="Shadowing practice" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Shadowing practice</h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>{{ props.exercise.target_transcript }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p v-if="!isSupported" class="text-sm text-muted-foreground">
                    Your browser doesn't support speech recognition. Try Chrome
                    on desktop.
                </p>
                <Button v-else :disabled="isRecording" @click="startRecording">
                    {{ isRecording ? 'Listening…' : 'Repeat this phrase' }}
                </Button>

                <p v-if="transcriptGuess" class="text-sm text-muted-foreground">
                    You said: "{{ transcriptGuess }}"
                </p>
                <p v-if="score !== null" class="text-lg font-medium">
                    Match score: {{ score }}%
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
            No shadowing exercises available yet.
        </p>
    </div>
</template>
