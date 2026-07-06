<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { getCsrfToken } from '@/lib/csrf';
import { store as storeReview } from '@/routes/review/reviews';

interface ReviewCard {
    id: number;
    front: string;
    back: string;
}

type Rating = 'again' | 'hard' | 'good' | 'easy';

const props = defineProps<{
    cards: ReviewCard[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Review', href: '/review' }],
    },
});

const queue = ref([...props.cards]);
const revealed = ref(false);
const isSubmitting = ref(false);
const submitFailed = ref(false);

const ratings: { value: Rating; label: string }[] = [
    { value: 'again', label: 'Again' },
    { value: 'hard', label: 'Hard' },
    { value: 'good', label: 'Good' },
    { value: 'easy', label: 'Easy' },
];

async function rate(rating: Rating) {
    const card = queue.value[0];

    if (!card || isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;
    submitFailed.value = false;

    try {
        const response = await fetch(storeReview(card.id).url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({ rating }),
        });

        if (!response.ok) {
            submitFailed.value = true;

            return;
        }

        queue.value.shift();
        revealed.value = false;
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <Head title="Review" />

    <div class="mx-auto flex max-w-xl flex-col gap-6 p-4">
        <h1 class="text-2xl font-semibold">Review</h1>

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
                    {{ queue.length }} card{{ queue.length === 1 ? '' : 's' }}
                    left
                </p>

                <p
                    v-if="submitFailed"
                    class="text-sm font-medium text-red-600 dark:text-red-500"
                >
                    Couldn't save that rating — try again.
                </p>
            </CardContent>
        </Card>

        <p v-else class="text-muted-foreground">
            All caught up — no cards due for review.
        </p>
    </div>
</template>
