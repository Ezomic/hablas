<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ReviewDeck from '@/components/ReviewDeck.vue';
import { store as storeReview } from '@/routes/review/reviews';
import type { ReviewCard } from '@/types/review';

const props = defineProps<{
    cards: ReviewCard[];
    dueRemaining: number;
    speechLocale: string | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Review', href: '/review' }],
    },
});

function reviewUrl(cardId: number): string {
    return storeReview(cardId).url;
}
</script>

<template>
    <Head title="Review" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Review</h1>

        <ReviewDeck
            :cards="props.cards"
            :review-url="reviewUrl"
            :due-remaining="props.dueRemaining"
            :speech-locale="props.speechLocale"
            count-noun="card"
            empty-message="All caught up, no cards due for review."
        />
    </div>
</template>
