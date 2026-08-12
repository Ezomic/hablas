<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Volume2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { useSpeech } from '@/composables/useSpeech';
import { showMilestone } from '@/lib/milestone';
import { store as storeAttempt } from '@/routes/listening/attempts';

interface Question {
    prompt: string;
    options: string[];
}

interface Exercise {
    id: number;
    title: string;
    cefrLevel: string;
    transcript: string;
    audioUrl: string | null;
    questions: Question[];
}

const props = defineProps<{
    exercise: Exercise | null;
    speechLocale: string | null;
    maxReplays: number;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Listening practice', href: '/listening' }],
    },
});

const { submitOrQueue } = useOfflineSync();
const { isSupported, isSpeaking, speak } = useSpeech(() => props.speechLocale);

const answers = ref<string[]>(
    props.exercise ? props.exercise.questions.map(() => '') : [],
);
const playCount = ref(0);
const score = ref<number | null>(null);
const isSubmitting = ref(false);
const isQueued = ref(false);
const errorMessage = ref<string | null>(null);

// The first play is the clip itself, so only what follows counts as a replay.
const replaysUsed = computed(() => Math.max(0, playCount.value - 1));
const replaysLeft = computed(() => props.maxReplays - replaysUsed.value);
const canPlay = computed(
    () =>
        playCount.value === 0 ||
        (replaysLeft.value > 0 && score.value === null),
);
const hasStarted = computed(() => playCount.value > 0);
const allAnswered = computed(() =>
    answers.value.every((answer) => answer !== ''),
);

function play() {
    if (!props.exercise || !canPlay.value) {
        return;
    }

    playCount.value++;
    speak(props.exercise.transcript, props.exercise.audioUrl);
}

async function submit() {
    if (!props.exercise || isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    errorMessage.value = null;
    isQueued.value = false;

    try {
        const result = await submitOrQueue(
            storeAttempt(props.exercise.id).url,
            {
                answers: answers.value,
                replays_used: replaysUsed.value,
            },
        );

        if (result.queued) {
            isQueued.value = true;

            return;
        }

        if (!result.response.ok) {
            errorMessage.value = "Couldn't submit that. Try again.";

            return;
        }

        const data = (await result.response.json()) as { score: number };
        score.value = data.score;
        showMilestone(data);
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Head title="Listening practice" />

    <div class="mx-auto flex max-w-2xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Listening practice</h1>

        <template v-if="props.exercise">
            <Card>
                <CardHeader>
                    <CardDescription>
                        <Badge variant="secondary">{{
                            props.exercise.cefrLevel
                        }}</Badge>
                    </CardDescription>
                    <CardTitle class="text-xl">{{
                        props.exercise.title
                    }}</CardTitle>
                </CardHeader>
                <CardContent class="flex flex-col gap-3">
                    <p
                        v-if="!isSupported && !props.exercise.audioUrl"
                        class="text-sm text-muted-foreground"
                    >
                        Your browser can't play this clip. Try Chrome on
                        desktop.
                    </p>

                    <Button
                        v-else
                        :disabled="!canPlay || isSpeaking"
                        @click="play"
                    >
                        <Volume2 class="size-4" />
                        {{ hasStarted ? 'Play again' : 'Play the clip' }}
                    </Button>

                    <p class="text-sm text-muted-foreground">
                        {{ replaysLeft }} replay{{
                            replaysLeft === 1 ? '' : 's'
                        }}
                        left
                    </p>
                </CardContent>
            </Card>

            <form
                v-if="hasStarted"
                class="flex flex-col gap-6"
                @submit.prevent="submit"
            >
                <Card
                    v-for="(question, index) in props.exercise.questions"
                    :key="index"
                >
                    <CardHeader>
                        <CardTitle class="text-base">{{
                            question.prompt
                        }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <RadioGroup
                            v-model="answers[index]"
                            :disabled="score !== null"
                            class="flex flex-col gap-2"
                        >
                            <div
                                v-for="option in question.options"
                                :key="option"
                                class="flex items-center gap-2"
                            >
                                <RadioGroupItem
                                    :id="`q${index}-${option}`"
                                    :value="option"
                                />
                                <Label :for="`q${index}-${option}`">{{
                                    option
                                }}</Label>
                            </div>
                        </RadioGroup>
                    </CardContent>
                </Card>

                <Button
                    v-if="score === null"
                    type="submit"
                    :disabled="!allAnswered || isSubmitting"
                >
                    Check answers
                </Button>
            </form>

            <p v-else class="text-muted-foreground">
                Play the clip to see the questions.
            </p>

            <p v-if="isQueued" class="text-sm text-muted-foreground">
                You're offline, so this is saved and will be scored once you're
                back online.
            </p>
            <p v-else-if="score !== null" class="text-lg font-medium">
                You scored {{ score }}%.
            </p>
            <p
                v-if="errorMessage"
                class="text-sm text-red-600 dark:text-red-500"
            >
                {{ errorMessage }}
            </p>
        </template>

        <p v-else class="text-muted-foreground">
            No listening clips available at your level yet.
        </p>
    </div>
</template>
