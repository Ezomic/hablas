<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Spinner } from '@/components/ui/spinner';
import { fetchJson } from '@/lib/http';
import { answer, results, skip } from '@/routes/placement';

interface PlacementTestItem {
    id: number;
    skill: string;
    prompt: string;
    options: string[];
}

const props = defineProps<{
    item: PlacementTestItem | null;
    language: { code: string; name: string };
    dontKnowResponse: string;
    progress: number;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Placement test', href: '/placement' }],
    },
});

const skillLabels: Record<string, string> = {
    reading: 'Reading',
    listening: 'Listening',
    speaking: 'Speaking',
    writing: 'Writing',
};

const currentItem = ref(props.item);
const progress = ref(props.progress);
const selectedAnswer = ref<string | null>(null);
const isSubmitting = ref(false);
const submitFailed = ref(false);

async function submit(answerValue: string) {
    const item = currentItem.value;

    if (!item || !answerValue || isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    submitFailed.value = false;

    try {
        const response = await fetchJson(
            answer(item.id).url,
            'POST',
            JSON.stringify({ response: answerValue }),
        );

        if (!response.ok) {
            if (response.status === 409) {
                // Our local currentItem is stale (e.g. answered from another
                // tab) — there's no valid "retry" for the same item id, so
                // resync from the server instead of looping on the same 409.
                router.reload();

                return;
            }

            submitFailed.value = true;

            return;
        }

        const payload = (await response.json()) as
            | { done: true }
            | { done: false; item: PlacementTestItem; progress: number };

        if (payload.done) {
            router.visit(results().url);

            return;
        }

        currentItem.value = payload.item;
        progress.value = payload.progress;
        selectedAnswer.value = null;
    } finally {
        isSubmitting.value = false;
    }
}

const skipForm = useForm({});

function skipTest() {
    skipForm.post(skip().url);
}
</script>

<template>
    <Head :title="`${props.language.name} placement test`" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">
                {{ props.language.name }} placement test
            </h1>
            <p class="mt-1 text-muted-foreground">
                Answer each question — the next one adjusts to how you're doing.
                This sets your starting CEFR level for reading, listening,
                speaking, and writing separately.
            </p>

            <div v-if="currentItem" class="mt-4 flex flex-col gap-1.5">
                <div
                    class="flex items-center justify-between text-sm text-muted-foreground"
                >
                    <span>Progress</span>
                    <span>{{ progress }}%</span>
                </div>
                <Progress :model-value="progress" />
            </div>
        </div>

        <div v-if="currentItem" class="flex flex-col gap-6">
            <h2 class="text-lg font-medium">
                {{ skillLabels[currentItem.skill] ?? currentItem.skill }}
            </h2>

            <div class="flex flex-col gap-3">
                <p class="font-medium">{{ currentItem.prompt }}</p>
                <RadioGroup v-model="selectedAnswer">
                    <div
                        v-for="option in currentItem.options"
                        :key="option"
                        class="flex items-center gap-2"
                    >
                        <RadioGroupItem
                            :id="`option-${option}`"
                            :value="option"
                        />
                        <Label :for="`option-${option}`">{{ option }}</Label>
                    </div>
                </RadioGroup>
            </div>

            <InputError
                v-if="submitFailed"
                message="Couldn't save that answer — try again."
            />

            <div class="flex flex-col gap-3">
                <Button
                    :disabled="!selectedAnswer || isSubmitting"
                    @click="submit(selectedAnswer ?? '')"
                >
                    <Spinner v-if="isSubmitting" />
                    Next
                </Button>
                <!--
                    Records as incorrect (see PlacementTestResponse::DONT_KNOW),
                    stepping the staircase down — an honest signal instead of a
                    guess. Enabled without a selection, since not knowing is the
                    whole point.
                -->
                <Button
                    variant="ghost"
                    :disabled="isSubmitting"
                    @click="submit(props.dontKnowResponse)"
                >
                    I don't know
                </Button>
            </div>
        </div>

        <div class="border-t pt-6 text-center text-sm text-muted-foreground">
            Not ready for a test?
            <button
                type="button"
                class="underline underline-offset-4"
                :disabled="skipForm.processing"
                @click="skipTest"
            >
                Skip and start at A1
            </button>
        </div>
    </div>
</template>
