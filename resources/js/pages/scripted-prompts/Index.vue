<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { showMilestone } from '@/lib/milestone';
import { index } from '@/routes/scripted-prompts';
import { store as storeAttempt } from '@/routes/scripted-prompts/attempts';

interface Exercise {
    id: number;
    prompt_text: string;
}

const props = defineProps<{
    exercise: Exercise | null;
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.scriptedPrompts'), href: index() }]);

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

    const data = (await result.response.json()) as { score: number };
    score.value = data.score;
    showMilestone(data);
}
</script>

<template>
    <Head :title="t('nav.scriptedPrompts')" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">
            {{ t('nav.scriptedPrompts') }}
        </h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>{{ props.exercise.prompt_text }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p v-if="!isSupported" class="text-sm text-muted-foreground">
                    {{ t('practice.noSpeechRecognition') }}
                </p>
                <Button v-else :disabled="isRecording" @click="startRecording">
                    {{
                        isRecording
                            ? t('practice.listening')
                            : t('scriptedPrompts.answer')
                    }}
                </Button>

                <p v-if="transcriptGuess" class="text-sm text-muted-foreground">
                    {{ t('practice.youSaid', { text: transcriptGuess }) }}
                </p>
                <p v-if="isQueued" class="text-sm text-muted-foreground">
                    {{ t('practice.offlineAttempt') }}
                </p>
                <p v-else-if="score !== null" class="text-lg font-medium">
                    {{ t('scriptedPrompts.score', { score }) }}
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
            {{ t('scriptedPrompts.empty') }}
        </p>
    </div>
</template>
