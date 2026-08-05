<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { errorTagLabels } from '@/lib/errorTagLabels';
import { pluralize } from '@/lib/pluralize';
import type { ErrorTag, Rating, ReviewCard } from '@/types/review';

const props = defineProps<{
    cards: ReviewCard[];
    reviewUrl: (cardId: number) => string;
    countNoun: string;
    emptyMessage: string;
}>();

const { submitOrQueue } = useOfflineSync();

const queue = ref<ReviewCard[]>([...props.cards]);
const revealed = ref(false);
const isSubmitting = ref(false);
const submitFailed = ref(false);
const queuedOffline = ref(false);
const pendingMiss = ref(false);

const ratings: { value: Rating; label: string }[] = [
    { value: 'again', label: 'Again' },
    { value: 'hard', label: 'Hard' },
    { value: 'good', label: 'Good' },
    { value: 'easy', label: 'Easy' },
];

const errorTags = Object.keys(errorTagLabels) as ErrorTag[];

// Only a missed grammar card asks what went wrong. Plain vocabulary misses
// stay a simple right or wrong, so tagging them would be noise.
function rate(rating: Rating) {
    const card = queue.value[0];

    if (!card || isSubmitting.value) {
        return;
    }

    if (rating === 'again' && card.kind === 'grammar') {
        pendingMiss.value = true;

        return;
    }

    void submit(rating, null);
}

function tagMiss(errorTag: ErrorTag | null) {
    void submit('again', errorTag);
}

async function submit(rating: Rating, errorTag: ErrorTag | null) {
    const card = queue.value[0];

    if (!card || isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    submitFailed.value = false;
    queuedOffline.value = false;

    try {
        const result = await submitOrQueue(props.reviewUrl(card.id), {
            rating,
            error_tag_category: errorTag,
        });

        if (result.queued) {
            queuedOffline.value = true;
            advance();

            return;
        }

        if (!result.response.ok) {
            submitFailed.value = true;

            return;
        }

        advance();
    } finally {
        isSubmitting.value = false;
    }
}

function advance() {
    queue.value.shift();
    revealed.value = false;
    pendingMiss.value = false;
}
</script>

<template>
    <p v-if="queuedOffline" class="text-sm text-muted-foreground">
        You're offline, so ratings are saved and will sync once you're back
        online.
    </p>

    <Card v-if="queue[0]">
        <CardHeader>
            <CardTitle class="text-2xl">{{ queue[0].front }}</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <p v-if="revealed" class="text-lg text-muted-foreground">
                {{ queue[0].back }}
            </p>

            <Button v-if="!revealed" @click="revealed = true">
                Show answer
            </Button>

            <div v-else-if="pendingMiss" class="flex flex-col gap-3">
                <p class="text-sm font-medium">What went wrong?</p>
                <div class="grid grid-cols-2 gap-2">
                    <Button
                        v-for="tag in errorTags"
                        :key="tag"
                        :variant="
                            tag === queue[0].suggestedErrorTag
                                ? 'default'
                                : 'outline'
                        "
                        :disabled="isSubmitting"
                        @click="tagMiss(tag)"
                    >
                        {{ errorTagLabels[tag] }}
                    </Button>
                </div>
                <Button
                    variant="ghost"
                    :disabled="isSubmitting"
                    @click="tagMiss(null)"
                >
                    Not sure
                </Button>
            </div>

            <div v-else class="grid grid-cols-4 gap-2">
                <Button
                    v-for="rating in ratings"
                    :key="rating.value"
                    variant="outline"
                    :disabled="isSubmitting"
                    @click="rate(rating.value)"
                >
                    {{ rating.label }}
                </Button>
            </div>

            <p class="text-sm text-muted-foreground">
                {{ queue.length }}
                {{ pluralize(props.countNoun, queue.length) }} left
            </p>

            <p
                v-if="submitFailed"
                class="text-sm font-medium text-red-600 dark:text-red-500"
            >
                Couldn't save that rating, try again.
            </p>
        </CardContent>
    </Card>

    <p v-else class="text-muted-foreground">{{ props.emptyMessage }}</p>
</template>
