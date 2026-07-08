<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { store as storeAttempt } from '@/routes/scripted-prompts/attempts';

interface Exercise {
    id: number;
    prompt_text: string;
}

const props = defineProps<{
    exercise: Exercise | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Scripted prompts', href: '/scripted-prompts' }],
    },
});

const { submitOrQueue } = useOfflineSync();

const isSupported =
    'SpeechRecognition' in window || 'webkitSpeechRecognition' in window;
const isRecording = ref(false);
const transcriptGuess = ref<string | null>(null);
const score = ref<number | null>(null);
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
    score.value = null;
    isQueued.value = false;
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

    const result = await submitOrQueue(storeAttempt(props.exercise.id).url, {
        transcript_guess: transcriptGuess.value,
    });

    if (result.queued) {
        isQueued.value = true;

        return;
    }

    const data = (await result.response.json()) as { score: number };
    score.value = data.score;
}
</script>

<template>
    <Head title="Scripted prompts" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Scripted prompts</h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>{{ props.exercise.prompt_text }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p v-if="!isSupported" class="text-sm text-muted-foreground">
                    Your browser doesn't support speech recognition. Try Chrome
                    on desktop.
                </p>
                <Button v-else :disabled="isRecording" @click="startRecording">
                    {{ isRecording ? 'Listening…' : 'Answer out loud' }}
                </Button>

                <p v-if="transcriptGuess" class="text-sm text-muted-foreground">
                    You said: "{{ transcriptGuess }}"
                </p>
                <p v-if="isQueued" class="text-sm text-muted-foreground">
                    You're offline — this attempt is saved and will be scored
                    once you're back online.
                </p>
                <p v-else-if="score !== null" class="text-lg font-medium">
                    Keyword match: {{ score }}%
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
            No scripted prompts available yet.
        </p>
    </div>
</template>
