<script setup lang="ts">
import type { Directive } from 'vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import SpeakButton from '@/components/SpeakButton.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { errorTagLabels } from '@/lib/errorTagLabels';
import { fetchJson } from '@/lib/http';
import { pluralize } from '@/lib/pluralize';
import type { ErrorTag, Rating, ReviewCard } from '@/types/review';

const props = withDefaults(
    defineProps<{
        cards: ReviewCard[];
        reviewUrl: (cardId: number) => string;
        answerUrl: (cardId: number) => string;
        countNoun: string;
        emptyMessage: string;
        dueRemaining?: number;
        speechLocale?: string | null;
    }>(),
    { dueRemaining: 0, speechLocale: null },
);

const CHECK_TIMEOUT_MS = 8000;

type Verdict = 'correct' | 'wrong' | 'unchecked';

const { submitOrQueue } = useOfflineSync();

const queue = ref<ReviewCard[]>([...props.cards]);
const revealed = ref(false);
const isSubmitting = ref(false);
const submitFailed = ref(false);
const queuedOffline = ref(false);
const pendingMiss = ref(false);
const typedAnswer = ref('');
const isChecking = ref(false);
const verdict = ref<Verdict | null>(null);
const suggestedRating = ref<Rating | null>(null);

const ratings: { value: Rating; label: string }[] = [
    { value: 'again', label: 'Again' },
    { value: 'hard', label: 'Hard' },
    { value: 'good', label: 'Good' },
    { value: 'easy', label: 'Easy' },
];

const verdictRatings: Record<Verdict, Rating | null> = {
    correct: 'good',
    wrong: 'again',
    unchecked: null,
};

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

const isProduction = computed(() => queue.value[0]?.direction === 'production');

// The answer field is rebuilt for every production card, so focusing it as
// it mounts puts the cursor in place for each new word.
const vAutofocus: Directive<HTMLElement> = {
    mounted: (element) => element.focus(),
};

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
    typedAnswer.value = '';
    verdict.value = null;
    suggestedRating.value = null;
}

// Giving up on a word is a miss, so Again is the likely rating. It also
// works while a check is hanging, so a dead connection never freezes the card.
function showAnswer() {
    revealed.value = true;
    isChecking.value = false;

    if (isProduction.value) {
        suggestedRating.value = 'again';
    }
}

async function checkAnswer() {
    const card = queue.value[0];
    const answer = typedAnswer.value.trim();

    if (!card || isChecking.value || revealed.value || answer === '') {
        return;
    }

    isChecking.value = true;

    const result = await grade(card, answer);

    if (queue.value[0] !== card || revealed.value) {
        return;
    }

    verdict.value = result;
    suggestedRating.value = verdictRatings[result];
    revealed.value = true;
    isChecking.value = false;
}

// Offline, failing or too slow, the learner still sees the word and rates it
// themselves, as on a recognition card.
async function grade(card: ReviewCard, answer: string): Promise<Verdict> {
    let timer: ReturnType<typeof setTimeout> | undefined;

    try {
        const response = await Promise.race([
            fetchJson(
                props.answerUrl(card.id),
                'POST',
                JSON.stringify({ answer }),
            ),
            new Promise<never>((_, reject) => {
                timer = setTimeout(
                    () => reject(new Error('timeout')),
                    CHECK_TIMEOUT_MS,
                );
            }),
        ]);

        if (!response.ok) {
            return 'unchecked';
        }

        const result = (await response.json()) as { correct: boolean };

        return result.correct ? 'correct' : 'wrong';
    } catch {
        return 'unchecked';
    } finally {
        clearTimeout(timer);
    }
}

// Reviewing is the most repetitive screen in the app, so the whole loop is
// reachable from the keyboard: space or enter reveals, then 1 to 4 rate.
// A production card is answered in its own field instead, and once checked,
// enter takes the suggested rating.
// A held key repeats, which would rate every card in the queue unseen, and
// enter on a focused button is that button's own click, so it must not also
// take the suggested rating.
function handleKeydown(event: KeyboardEvent) {
    if (
        event.repeat ||
        event.metaKey ||
        event.ctrlKey ||
        event.altKey ||
        isTyping(event) ||
        (event.key === 'Enter' && event.target instanceof HTMLButtonElement)
    ) {
        return;
    }

    if (
        !queue.value[0] ||
        isSubmitting.value ||
        isChecking.value ||
        pendingMiss.value
    ) {
        return;
    }

    if (!revealed.value) {
        if (
            !isProduction.value &&
            (event.key === ' ' || event.key === 'Enter')
        ) {
            event.preventDefault();
            revealed.value = true;
        }

        return;
    }

    if (event.key === 'Enter' && suggestedRating.value) {
        event.preventDefault();
        rate(suggestedRating.value);

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
                    v-if="queue[0].kind === 'vocabulary' && !isProduction"
                    :text="queue[0].front"
                    :locale="props.speechLocale"
                    :audio-url="queue[0].audioUrl"
                    :audio-slow-url="queue[0].audioSlowUrl"
                />
            </CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <form
                v-if="isProduction && !revealed"
                class="flex flex-col gap-2"
                @submit.prevent="checkAnswer"
            >
                <Label :for="`answer-${queue[0].id}`">
                    {{
                        queue[0].needsArticle
                            ? 'Type the word, with its article'
                            : 'Type the word'
                    }}
                </Label>
                <div class="flex gap-2">
                    <Input
                        :id="`answer-${queue[0].id}`"
                        v-model="typedAnswer"
                        v-autofocus
                        :disabled="isChecking"
                        maxlength="200"
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="false"
                    />
                    <Button
                        type="submit"
                        :disabled="isChecking || !typedAnswer.trim()"
                    >
                        Check
                    </Button>
                </div>
            </form>

            <div v-if="revealed && isProduction" class="flex flex-col gap-1">
                <p class="flex items-center gap-2 text-lg font-medium">
                    {{ queue[0].back }}
                    <SpeakButton
                        :text="queue[0].back"
                        :locale="props.speechLocale"
                        :audio-url="queue[0].audioUrl"
                        :audio-slow-url="queue[0].audioSlowUrl"
                    />
                </p>
                <p
                    v-if="verdict === 'correct'"
                    class="text-sm font-medium text-green-600 dark:text-green-500"
                >
                    Correct
                </p>
                <p
                    v-else-if="verdict === 'wrong'"
                    class="text-sm font-medium text-red-600 dark:text-red-500"
                >
                    You wrote “{{ typedAnswer.trim() }}”.
                </p>
                <p
                    v-else-if="verdict === 'unchecked'"
                    class="text-sm text-muted-foreground"
                >
                    Couldn't check your answer, so compare it yourself. You
                    wrote “{{ typedAnswer.trim() }}”.
                </p>
            </div>
            <p v-else-if="revealed" class="text-lg text-muted-foreground">
                {{ queue[0].back }}
            </p>

            <Button
                v-if="!revealed"
                :variant="isProduction ? 'ghost' : 'default'"
                @click="showAnswer"
            >
                Show answer
                <kbd
                    v-if="!isProduction"
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
                    :variant="
                        rating.value === suggestedRating ? 'default' : 'outline'
                    "
                    :disabled="isSubmitting"
                    @click="rate(rating.value)"
                >
                    {{ rating.label }}
                    <kbd
                        class="ml-1 rounded border px-1 text-xs font-normal opacity-70"
                        >{{
                            rating.value === suggestedRating ? '↵' : index + 1
                        }}</kbd
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
