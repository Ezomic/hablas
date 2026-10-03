<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ReviewDeck from '@/components/ReviewDeck.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { check as checkAnswer } from '@/routes/review/answers';
import { store as storeReview } from '@/routes/review/reviews';
import type { ReviewCard } from '@/types/review';

const props = defineProps<{
    cards: ReviewCard[];
    dueRemaining: number;
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.review'), href: '/review' }]);

function reviewUrl(cardId: number): string {
    return storeReview(cardId).url;
}

function answerUrl(cardId: number): string {
    return checkAnswer(cardId).url;
}
</script>

<template>
    <Head :title="t('nav.review')" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">{{ t('nav.review') }}</h1>

        <ReviewDeck
            :cards="props.cards"
            :review-url="reviewUrl"
            :answer-url="answerUrl"
            :due-remaining="props.dueRemaining"
            :speech-locale="props.speechLocale"
            count-noun="card"
            :empty-message="t('review.emptyDue')"
        />
    </div>
</template>
