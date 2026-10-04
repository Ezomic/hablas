<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { showMilestone } from '@/lib/milestone';
import { index } from '@/routes/writing';
import { store as storeAttempt } from '@/routes/writing/attempts';

interface Exercise {
    id: number;
    type: 'fill_in_template' | 'guided_paragraph' | 'sentence_transformation';
    prompt: string;
    template: { text: string } | null;
}

const props = defineProps<{
    exercise: Exercise | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('writing.title'), href: index() }]);

const answerPlaceholder = 'Escribe tu respuesta...';
const correctCheer = '¡Correcto!';

const { submitOrQueue } = useOfflineSync();

const response = ref('');
const isCorrect = ref<boolean | null>(null);
const isQueued = ref(false);
const isSubmitting = ref(false);
const submitFailed = ref(false);

async function submit() {
    if (!props.exercise || !response.value) {
        return;
    }

    isSubmitting.value = true;
    isQueued.value = false;
    submitFailed.value = false;

    try {
        const result = await submitOrQueue(
            storeAttempt(props.exercise.id).url,
            { response: response.value },
        );

        if (result.queued) {
            isQueued.value = true;

            return;
        }

        if (!result.response.ok) {
            submitFailed.value = true;

            return;
        }

        const data = (await result.response.json()) as {
            is_correct: boolean;
        };
        isCorrect.value = data.is_correct;
        showMilestone(data);
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Head :title="t('writing.title')" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">{{ t('writing.title') }}</h1>

        <Card v-if="props.exercise">
            <CardHeader>
                <CardTitle>{{ props.exercise.prompt }}</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col gap-4">
                <p
                    v-if="props.exercise.template"
                    class="rounded-md bg-muted p-3 font-mono text-sm"
                >
                    {{ props.exercise.template.text }}
                </p>

                <Textarea
                    v-if="props.exercise.type === 'guided_paragraph'"
                    v-model="response"
                    rows="5"
                    :placeholder="answerPlaceholder"
                />
                <Input
                    v-else
                    v-model="response"
                    :placeholder="answerPlaceholder"
                />

                <Button :disabled="isSubmitting || !response" @click="submit">
                    {{ t('writing.submit') }}
                </Button>

                <p v-if="isQueued" class="text-sm text-muted-foreground">
                    {{ t('practice.offlineAnswer') }}
                </p>
                <p
                    v-else-if="isCorrect === true"
                    class="text-lg font-medium text-green-600 dark:text-green-500"
                >
                    {{ correctCheer }}
                </p>
                <p
                    v-else-if="isCorrect === false"
                    class="text-lg font-medium text-red-600 dark:text-red-500"
                >
                    {{ t('writing.notQuite') }}
                </p>
                <p
                    v-if="submitFailed"
                    class="text-sm font-medium text-red-600 dark:text-red-500"
                >
                    {{ t('writing.answerFailed') }}
                </p>
            </CardContent>
        </Card>

        <p v-else class="text-muted-foreground">
            {{ t('writing.empty') }}
        </p>
    </div>
</template>
