<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ReviewDeck from '@/components/ReviewDeck.vue';
import { store as storeReview } from '@/routes/review/weak-spots/reviews';
import type { ReviewCard } from '@/types/review';

const props = defineProps<{
    cards: ReviewCard[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Review', href: '/review' },
            { title: 'Weak spots', href: '/review/weak-spots' },
        ],
    },
});

function reviewUrl(cardId: number): string {
    return storeReview(cardId).url;
}
</script>

<template>
    <Head title="Weak spots" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Weak spots</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Cards you've missed a few times in a row. Get one right to move
                it back into your normal review rotation.
            </p>
        </div>

        <ReviewDeck
            :cards="props.cards"
            :review-url="reviewUrl"
            count-noun="weak spot"
            empty-message="No weak spots right now, nicely done."
        />
    </div>
</template>
