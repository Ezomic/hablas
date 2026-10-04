<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
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
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { showMilestone } from '@/lib/milestone';
import { index } from '@/routes/reading';
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

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('reading.title'), href: index() }]);

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
            errorMessage.value = t('practice.submitFailed');

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
    <Head :title="t('reading.title')" />

    <div class="mx-auto flex max-w-2xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">{{ t('reading.title') }}</h1>

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
                    {{ t('practice.checkAnswers') }}
                </Button>
            </form>

            <p v-if="isQueued" class="text-sm text-muted-foreground">
                {{ t('practice.offlineQueued') }}
            </p>
            <p v-else-if="score !== null" class="text-lg font-medium">
                {{ t('practice.scored', { score }) }}
            </p>
            <p
                v-if="errorMessage"
                class="text-sm text-red-600 dark:text-red-500"
            >
                {{ errorMessage }}
            </p>
        </template>

        <p v-else class="text-muted-foreground">
            {{ t('reading.empty') }}
        </p>
    </div>
</template>
