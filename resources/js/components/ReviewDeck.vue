<script setup lang="ts">
import type { Directive } from 'vue';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import ChoiceExercise from '@/components/lesson/ChoiceExercise.vue';
import LetterBoxes from '@/components/lesson/LetterBoxes.vue';
import SpeakButton from '@/components/SpeakButton.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useOfflineSync } from '@/composables/useOfflineSync';
import { fetchJson } from '@/lib/http';
import type { ErrorTag, Rating, ReviewCard } from '@/types/review';

const props = withDefaults(
    defineProps<{
        cards: ReviewCard[];
        reviewUrl: (cardId: number) => string;
        answerUrl: (cardId: number) => string;
        countNoun: 'card' | 'weakSpot';
        emptyMessage: string;
        dueRemaining?: number;
        speechLocale?: string | null;
    }>(),
    { dueRemaining: 0, speechLocale: null },
);

const CHECK_TIMEOUT_MS = 8000;

type Verdict = 'correct' | 'wrong' | 'unchecked';

const { t } = useI18n();
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
const picked = ref<string | null>(null);

const ratings: Rating[] = ['again', 'hard', 'good', 'easy'];

const verdictRatings: Record<Verdict, Rating | null> = {
    correct: 'good',
    wrong: 'again',
    unchecked: null,
};

const errorTags: ErrorTag[] = [
    'wrong_gender',
    'ser_estar_confusion',
    'false_friend',
    'wrong_tense',
    'portunol_slip',
    'other',
];

const tally = ref<Record<Rating, number>>({
    again: 0,
    hard: 0,
    good: 0,
    easy: 0,
});

const reviewed = computed(() =>
    ratings.reduce((total, rating) => total + tally.value[rating], 0),
);

const isFinished = computed(
    () => queue.value.length === 0 && reviewed.value > 0,
);

const isProduction = computed(() => queue.value[0]?.direction === 'production');

const isTyped = computed(() => queue.value[0]?.exercise === 'type');

const isChoice = computed(() => queue.value[0]?.options != null);

const isFlip = computed(() => !isTyped.value && !isChoice.value);

const isGraded = computed(
    () => verdict.value === 'correct' || verdict.value === 'wrong',
);

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
    picked.value = null;
    verdict.value = null;
    suggestedRating.value = null;
}

// Giving up on a word is a miss, so Again is the likely rating. It also
// works while a check is hanging, so a dead connection never freezes the card.
function showAnswer() {
    revealed.value = true;
    isChecking.value = false;

    if (isChoice.value) {
        verdict.value = 'wrong';
        suggestedRating.value = 'again';

        return;
    }

    if (isProduction.value) {
        suggestedRating.value = 'again';
    }
}

function pick(option: string) {
    if (revealed.value || !isChoice.value) {
        return;
    }

    picked.value = option;
    verdict.value = option === queue.value[0]?.back ? 'correct' : 'wrong';
    suggestedRating.value = verdictRatings[verdict.value];
    revealed.value = true;
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
// enter on a focused button or link is its own click, so it must not also
// take the suggested rating.
function handleKeydown(event: KeyboardEvent) {
    if (
        event.repeat ||
        event.metaKey ||
        event.ctrlKey ||
        event.altKey ||
        isTyping(event) ||
        (event.key === 'Enter' && isActivatable(event.target))
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
        if (isFlip.value && (event.key === ' ' || event.key === 'Enter')) {
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

    if (rating && !isGraded.value) {
        event.preventDefault();
        rate(rating);
    }
}

function termLang(
    card: ReviewCard,
    side: 'front' | 'back',
): string | undefined {
    if (card.kind !== 'vocabulary') {
        return undefined;
    }

    const targetSide = card.direction === 'production' ? 'back' : 'front';

    return side === targetSide ? (props.speechLocale ?? undefined) : undefined;
}

// Enter on a focused button, link or role=button element is its own click.
function isActivatable(target: EventTarget | null): boolean {
    return (
        target instanceof HTMLElement &&
        (target instanceof HTMLButtonElement ||
            target instanceof HTMLAnchorElement ||
            target.getAttribute('role') === 'button')
    );
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
        {{ t('review.deck.offline') }}
    </p>

    <Card v-if="queue[0]">
        <CardHeader>
            <CardTitle class="flex items-center gap-2 text-2xl">
                <span :lang="termLang(queue[0], 'front')">{{
                    queue[0].front
                }}</span>
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
            <ChoiceExercise
                v-if="isChoice"
                :key="queue[0].id"
                :model-value="picked"
                prompt=""
                :instruction="
                    t(
                        queue[0].exercise === 'choose_word'
                            ? 'review.deck.chooseWord'
                            : 'review.deck.chooseMeaning',
                    )
                "
                :options="queue[0].options ?? []"
                :answer="revealed ? queue[0].back : null"
                :disabled="revealed || isSubmitting"
                @update:model-value="pick"
            />

            <form
                v-if="isTyped && !revealed"
                class="flex flex-col gap-2"
                @submit.prevent="checkAnswer"
            >
                <Label
                    :id="`answer-label-${queue[0].id}`"
                    :for="`answer-${queue[0].id}`"
                >
                    {{
                        queue[0].needsArticle
                            ? t('review.deck.typeWithArticle')
                            : t('review.deck.type')
                    }}
                </Label>
                <div class="flex gap-2">
                    <LetterBoxes
                        v-if="queue[0].mask"
                        :key="queue[0].id"
                        v-model="typedAnswer"
                        :mask="queue[0].mask"
                        :labelledby="`answer-label-${queue[0].id}`"
                        :locale="termLang(queue[0], 'back') ?? null"
                        :disabled="isChecking"
                        @submit="checkAnswer"
                    />
                    <Input
                        v-else
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
                        {{ t('review.deck.check') }}
                    </Button>
                </div>
            </form>

            <div v-if="revealed && isProduction" class="flex flex-col gap-1">
                <p class="flex items-center gap-2 text-lg font-medium">
                    <span :lang="termLang(queue[0], 'back')">{{
                        queue[0].back
                    }}</span>
                    <SpeakButton
                        :text="queue[0].back"
                        :locale="props.speechLocale"
                        :audio-url="queue[0].audioUrl"
                        :audio-slow-url="queue[0].audioSlowUrl"
                    />
                </p>
                <p
                    v-if="verdict === 'unchecked'"
                    class="text-sm text-muted-foreground"
                >
                    {{
                        t('review.deck.unchecked', {
                            answer: typedAnswer.trim(),
                        })
                    }}
                </p>
            </div>
            <p
                v-else-if="revealed && !isChoice"
                class="text-lg text-muted-foreground"
                :lang="termLang(queue[0], 'back')"
            >
                {{ queue[0].back }}
            </p>

            <p
                v-if="verdict === 'correct'"
                class="text-sm font-medium text-green-600 dark:text-green-500"
                data-testid="verdict"
            >
                {{ t('review.deck.correct') }}
            </p>
            <p
                v-else-if="
                    verdict === 'wrong' && (picked ?? typedAnswer.trim())
                "
                class="text-sm font-medium text-red-600 dark:text-red-500"
                data-testid="verdict"
            >
                {{
                    t(
                        isChoice
                            ? 'review.deck.youChose'
                            : 'review.deck.youWrote',
                        {
                            answer: picked ?? typedAnswer.trim(),
                        },
                    )
                }}
            </p>

            <Button
                v-if="!revealed"
                :variant="isFlip ? 'default' : 'ghost'"
                @click="showAnswer"
            >
                {{
                    isChoice
                        ? t('review.deck.dontKnow')
                        : t('review.deck.showAnswer')
                }}
                <kbd
                    v-if="isFlip"
                    class="ml-1 rounded border px-1 text-xs font-normal opacity-70"
                    >{{ t('review.deck.spaceKey') }}</kbd
                >
            </Button>

            <div v-else-if="pendingMiss" class="flex flex-col gap-3">
                <p class="text-sm font-medium">
                    {{ t('review.deck.whatWentWrong') }}
                </p>
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
                        {{ t(`review.errorTags.${tag}`) }}
                    </Button>
                </div>
                <Button
                    variant="ghost"
                    :disabled="isSubmitting"
                    @click="tagMiss(null)"
                >
                    {{ t('review.deck.notSure') }}
                </Button>
            </div>

            <Button
                v-else-if="isGraded && suggestedRating"
                class="h-11"
                :disabled="isSubmitting"
                @click="rate(suggestedRating)"
            >
                {{ t('common.continue') }}
                <kbd
                    class="ml-1 hidden rounded border px-1 text-xs font-normal opacity-70 sm:inline"
                    >↵</kbd
                >
            </Button>

            <div v-else class="grid grid-cols-4 gap-2">
                <Button
                    v-for="(rating, index) in ratings"
                    :key="rating"
                    :variant="
                        rating === suggestedRating ? 'default' : 'outline'
                    "
                    class="min-w-0 px-2"
                    :disabled="isSubmitting"
                    @click="rate(rating)"
                >
                    {{ t(`review.rating.${rating}`) }}
                    <kbd
                        class="ml-1 hidden rounded border px-1 text-xs font-normal opacity-70 sm:inline"
                        >{{ rating === suggestedRating ? '↵' : index + 1 }}</kbd
                    >
                </Button>
            </div>

            <p class="text-sm text-muted-foreground">
                {{ t(`review.deck.left.${props.countNoun}`, queue.length) }}
            </p>

            <p
                v-if="submitFailed"
                class="text-sm font-medium text-red-600 dark:text-red-500"
            >
                {{ t('review.deck.saveFailed') }}
            </p>
        </CardContent>
    </Card>

    <Card v-else-if="isFinished">
        <CardHeader>
            <CardTitle class="text-2xl">{{
                t('review.deck.complete')
            }}</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
            <p class="text-sm text-muted-foreground">
                {{ t(`review.deck.reviewed.${props.countNoun}`, reviewed) }}
            </p>

            <div class="grid grid-cols-4 gap-2 text-center">
                <div
                    v-for="rating in ratings"
                    :key="rating"
                    class="rounded-md border p-2"
                >
                    <div class="text-xl font-semibold">
                        {{ tally[rating] }}
                    </div>
                    <div class="text-xs text-muted-foreground">
                        {{ t(`review.rating.${rating}`) }}
                    </div>
                </div>
            </div>

            <p v-if="props.dueRemaining" class="text-sm text-muted-foreground">
                {{
                    t(
                        `review.deck.stillDue.${props.countNoun}`,
                        props.dueRemaining,
                    )
                }}
            </p>
            <p v-else class="text-sm text-muted-foreground">
                {{ t('review.deck.nothingDue') }}
            </p>
        </CardContent>
    </Card>

    <p v-else class="text-muted-foreground">{{ props.emptyMessage }}</p>
</template>
