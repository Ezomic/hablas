<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { fetchJson } from '@/lib/http';
import { skillLabel } from '@/lib/skillLabels';
import { answer, index, results, skip } from '@/routes/placement';

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
    skill: string | null;
    canSkip: boolean;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('placement.breadcrumb'), href: index() }]);

const retakeSkillName = computed(() =>
    props.skill ? skillLabel(props.skill).toLowerCase() : null,
);

const title = computed(() =>
    retakeSkillName.value
        ? t('placement.retakeTitle', {
              language: props.language.name,
              skill: retakeSkillName.value,
          })
        : t('placement.title', { language: props.language.name }),
);

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
    <Head :title="title" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ title }}</h1>
            <p v-if="retakeSkillName" class="mt-1 text-muted-foreground">
                {{ t('placement.introRetake', { skill: retakeSkillName }) }}
            </p>
            <p v-else class="mt-1 text-muted-foreground">
                {{ t('placement.intro') }}
            </p>

            <div v-if="currentItem" class="mt-4 flex flex-col gap-1.5">
                <div
                    class="flex items-center justify-between text-sm text-muted-foreground"
                >
                    <span>{{ t('placement.progress') }}</span>
                    <span>{{ progress }}%</span>
                </div>
                <Progress :model-value="progress" />
            </div>
        </div>

        <div v-if="currentItem" class="flex flex-col gap-6">
            <h2 class="text-lg font-medium">
                {{ skillLabel(currentItem.skill) }}
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
                :message="t('placement.saveFailed')"
            />

            <div class="flex flex-col gap-3">
                <Button
                    :disabled="!selectedAnswer || isSubmitting"
                    @click="submit(selectedAnswer ?? '')"
                >
                    <AppSpinner v-if="isSubmitting" />
                    {{ t('common.next') }}
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
                    {{ t('placement.dontKnow') }}
                </Button>
            </div>
        </div>

        <div
            v-if="props.canSkip"
            class="border-t pt-6 text-center text-sm text-muted-foreground"
        >
            {{ t('placement.notReady') }}
            <button
                type="button"
                class="underline underline-offset-4"
                :disabled="skipForm.processing"
                @click="skipTest"
            >
                {{ t('placement.skip') }}
            </button>
        </div>
    </div>
</template>
