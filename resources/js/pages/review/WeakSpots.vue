<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ReviewDeck from '@/components/ReviewDeck.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { check as checkAnswer } from '@/routes/review/answers';
import { store as storeReview } from '@/routes/review/weak-spots/reviews';
import type { ReviewCard } from '@/types/review';

const props = defineProps<{
    cards: ReviewCard[];
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('nav.review'), href: '/review' },
    { title: t('nav.weakSpots'), href: '/review/weak-spots' },
]);

function reviewUrl(cardId: number): string {
    return storeReview(cardId).url;
}

function answerUrl(cardId: number): string {
    return checkAnswer(cardId).url;
}
</script>

<template>
    <Head :title="t('nav.weakSpots')" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ t('nav.weakSpots') }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ t('review.weakSpotsIntro') }}
            </p>
        </div>

        <ReviewDeck
            :cards="props.cards"
            :review-url="reviewUrl"
            :answer-url="answerUrl"
            :speech-locale="props.speechLocale"
            count-noun="weakSpot"
            :empty-message="t('review.emptyWeakSpots')"
        />
    </div>
</template>
