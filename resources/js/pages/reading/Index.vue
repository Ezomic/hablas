<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
import { showMilestone } from '@/lib/milestone';
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
    questions: Question[];
}

const props = defineProps<{
    passage: Passage | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Reading practice', href: '/reading' }],
    },
});

const { submitOrQueue } = useOfflineSync();

const answers = ref<string[]>(
    props.passage ? props.passage.questions.map(() => '') : [],
);
const score = ref<number | null>(null);
const isSubmitting = ref(false);
const isQueued = ref(false);
const errorMessage = ref<string | null>(null);

const allAnswered = computed(() =>
    answers.value.every((answer) => answer !== ''),
);

async function submit() {
    if (!props.passage || isSubmitting.value) {
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
    <Head title="Reading practice" />

    <div class="mx-auto flex max-w-2xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Reading practice</h1>

        <template v-if="props.passage">
            <Card>
                <CardHeader>
                    <CardDescription>
                        <Badge variant="secondary">{{
                            props.passage.cefrLevel
                        }}</Badge>
                    </CardDescription>
                    <CardTitle class="text-xl">{{
                        props.passage.title
                    }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <p class="leading-relaxed whitespace-pre-line">
                        {{ props.passage.body }}
                    </p>
                </CardContent>
            </Card>

            <form class="flex flex-col gap-6" @submit.prevent="submit">
                <Card
                    v-for="(question, index) in props.passage.questions"
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
            No reading passages available at your level yet.
        </p>
    </div>
</template>
