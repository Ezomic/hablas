<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SpeakButton from '@/components/SpeakButton.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { errorTagLabels } from '@/lib/errorTagLabels';
import { pluralize } from '@/lib/pluralize';
import type { ErrorTag, Rating, ReviewCard } from '@/types/review';

const props = withDefaults(
    defineProps<{
        cards: ReviewCard[];
        reviewUrl: (cardId: number) => string;
        countNoun: string;
        emptyMessage: string;
        dueRemaining?: number;
        speechLocale?: string | null;
    }>(),
    { dueRemaining: 0, speechLocale: null },
);

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

const tally = ref<Record<Rating, number>>({
    again: 0,
    hard: 0,
    good: 0,
    easy: 0,
});

const reviewed = computed(() =>
    ratings.reduce((total, rating) => total + tally.value[rating.value], 0),
);

const isFinished = computed(
    () => queue.value.length === 0 && reviewed.value > 0,
);

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
            advance(rating);

            return;
        }

        if (!result.response.ok) {
            submitFailed.value = true;

            return;
        }

        advance(rating);
    } finally {
        isSubmitting.value = false;
    }
}

function advance(rating: Rating) {
    tally.value[rating]++;
    queue.value.shift();
    revealed.value = false;
    pendingMiss.value = false;
}

// Reviewing is the most repetitive screen in the app, so the whole loop is
// reachable from the keyboard: space or enter reveals, then 1 to 4 rate.
function handleKeydown(event: KeyboardEvent) {
    if (event.metaKey || event.ctrlKey || event.altKey || isTyping(event)) {
        return;
    }

    if (!queue.value[0] || isSubmitting.value || pendingMiss.value) {
        return;
    }

    if (!revealed.value) {
        if (event.key === ' ' || event.key === 'Enter') {
            event.preventDefault();
            revealed.value = true;
        }

        return;
    }

    const rating = ratings[Number(event.key) - 1];

    if (rating) {
        event.preventDefault();
        rate(rating.value);
    }
}

function isTyping(event: KeyboardEvent): boolean {
    const target = event.target;

    if (!(target instanceof HTMLElement)) {
        return false;
    }

    return (
        target.isContentEditable ||
        ['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName)
    );
}

onMounted(() => window.addEventListener('keydown', handleKeydown));
onUnmounted(() => window.removeEventListener('keydown', handleKeydown));
</script>

<template>
    <p v-if="queuedOffline" class="text-sm text-muted-foreground">
        You're offline, so ratings are saved and will sync once you're back
        online.
    </p>

    <Card v-if="queue[0]">
        <CardHeader>
            <CardTitle class="flex items-center gap-2 text-2xl">
                {{ queue[0].front }}
                <SpeakButton
                    v-if="queue[0].kind === 'vocabulary'"
                    :text="queue[0].front"
                    :locale="props.speechLocale"
                />
            </CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <p v-if="revealed" class="text-lg text-muted-foreground">
                {{ queue[0].back }}
            </p>

            <Button v-if="!revealed" @click="revealed = true">
                Show answer
                <kbd
                    class="ml-1 rounded border px-1 text-xs font-normal opacity-70"
                    >space</kbd
                >
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
                    v-for="(rating, index) in ratings"
                    :key="rating.value"
                    variant="outline"
                    :disabled="isSubmitting"
                    @click="rate(rating.value)"
                >
                    {{ rating.label }}
                    <kbd
                        class="ml-1 rounded border px-1 text-xs font-normal opacity-70"
                        >{{ index + 1 }}</kbd
                    >
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

    <Card v-else-if="isFinished">
        <CardHeader>
            <CardTitle class="text-2xl">Session complete</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <p class="text-sm text-muted-foreground">
                {{ reviewed }} {{ pluralize(props.countNoun, reviewed) }}
                reviewed.
            </p>

            <div class="grid grid-cols-4 gap-2 text-center">
                <div
                    v-for="rating in ratings"
                    :key="rating.value"
                    class="rounded-md border p-2"
                >
                    <div class="text-xl font-semibold">
                        {{ tally[rating.value] }}
                    </div>
                    <div class="text-xs text-muted-foreground">
                        {{ rating.label }}
                    </div>
                </div>
            </div>

            <p v-if="props.dueRemaining" class="text-sm text-muted-foreground">
                {{ props.dueRemaining }} more
                {{ pluralize(props.countNoun, props.dueRemaining) }} still due.
                Start another session whenever you're ready.
            </p>
            <p v-else class="text-sm text-muted-foreground">
                Nothing else due right now.
            </p>
        </CardContent>
    </Card>

    <p v-else class="text-muted-foreground">{{ props.emptyMessage }}</p>
</template>
