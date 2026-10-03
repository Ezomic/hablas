<script setup lang="ts">
import { Mic } from '@lucide/vue';
import { computed, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import ListenPlayer from '@/components/lesson/ListenPlayer.vue';
import SpeakButton from '@/components/SpeakButton.vue';
import { Button } from '@/components/ui/button';
import { MAX_TRIES, useSpeakingTries } from '@/composables/useSpeakingTries';
import { clipUrl, text } from '@/lib/lessonPayload';
import type { RecognizerFactory } from '@/lib/speechRecognizer';
import { cn } from '@/lib/utils';
import type { WordVerdict } from '@/types/lesson';

const props = defineProps<{
    format: string;
    payload: Record<string, unknown>;
    locale: string | null;
    scoreUrl: string;
    replayLimit: number | null;
    disabled?: boolean;
    factory?: RecognizerFactory;
}>();

const emit = defineEmits<{
    change: [transcripts: string[]];
    denied: [];
}>();

const { t } = useI18n();
const speaking = useSpeakingTries(
    () => props.locale,
    () => props.scoreUrl,
    props.factory,
);

const isRepeat = computed(() => props.format === 'speak_repeat');
const heardFirst = computed(() => props.payload.audioRole !== 'model');
const prompt = computed(() => text(props.payload.prompt));
const audioUrl = computed(() => clipUrl(props.payload.audioUrl));
const audioSlowUrl = computed(() => clipUrl(props.payload.audioSlowUrl));
const last = computed(() => speaking.tries.value.at(-1) ?? null);
const used = computed(() => speaking.tries.value.length);

watch(speaking.transcripts, (transcripts) => emit('change', transcripts), {
    immediate: true,
});
watch(speaking.failure, (failure) => {
    if (failure === 'denied') {
        emit('denied');
    }
});

const verdictStyle: Record<WordVerdict, string> = {
    exact: 'bg-green-100 text-green-900 dark:bg-green-950 dark:text-green-100',
    accent: 'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-100',
    other_word: 'bg-red-100 text-red-900 dark:bg-red-950 dark:text-red-100',
    wrong: 'bg-red-100 text-red-900 dark:bg-red-950 dark:text-red-100',
    missed: 'border border-dashed text-muted-foreground',
};
</script>

<template>
    <section class="flex flex-col gap-5" data-testid="speak-exercise">
        <p class="text-sm text-muted-foreground">
            {{
                isRepeat
                    ? t('lesson.instruction.speakRepeat')
                    : heardFirst
                      ? t('lesson.instruction.speakQuestion')
                      : t('lesson.instruction.speakCue')
            }}
        </p>

        <template v-if="isRepeat">
            <h2 class="text-2xl font-semibold" data-testid="prompt">
                {{ text(props.payload.text) }}
            </h2>
            <p v-if="text(props.payload.english)" class="text-muted-foreground">
                {{ text(props.payload.english) }}
            </p>
            <div>
                <SpeakButton
                    :text="text(props.payload.text)"
                    :locale="props.locale"
                    :audio-url="audioUrl"
                    :audio-slow-url="audioSlowUrl"
                    :label="t('lesson.speak.hearModel')"
                />
            </div>
        </template>

        <template v-else-if="heardFirst">
            <ListenPlayer
                :text="prompt"
                :locale="props.locale"
                :audio-url="audioUrl"
                :audio-slow-url="audioSlowUrl"
                :replay-limit="props.replayLimit"
                offers-slower
            />
            <h2
                v-if="prompt"
                class="text-center text-xl font-semibold"
                data-testid="prompt"
            >
                {{ prompt }}
            </h2>
        </template>

        <h2 v-else class="text-2xl font-semibold" data-testid="prompt">
            {{ prompt }}
        </h2>

        <div class="flex flex-col items-center gap-2">
            <Button
                type="button"
                size="icon"
                class="size-20 rounded-full"
                :variant="
                    speaking.isListening.value ? 'destructive' : 'default'
                "
                :disabled="
                    props.disabled ||
                    !speaking.canTry.value ||
                    speaking.isListening.value
                "
                :aria-label="
                    speaking.isListening.value
                        ? t('lesson.speak.listening')
                        : t('lesson.speak.tap')
                "
                data-testid="speak-mic"
                @click="speaking.record"
            >
                <Mic class="size-9" />
            </Button>
            <p class="text-sm text-muted-foreground" aria-live="polite">
                {{
                    speaking.isListening.value
                        ? t('lesson.speak.listening')
                        : speaking.canTry.value
                          ? t('lesson.speak.tap')
                          : t('lesson.speak.noTriesLeft')
                }}
            </p>
            <p
                v-if="used > 0"
                class="text-xs text-muted-foreground"
                data-testid="speak-tries"
            >
                {{ t('lesson.speak.tryCount', { used, max: MAX_TRIES }) }}
            </p>
            <p
                v-if="speaking.failure.value === 'failed'"
                class="text-sm text-amber-700 dark:text-amber-300"
                role="alert"
            >
                {{ t('lesson.speak.notHeard') }}
            </p>
        </div>

        <div v-if="last" class="flex flex-col gap-2" data-testid="speak-result">
            <p class="text-sm">
                {{ t('lesson.speak.youSaid') }}
                <span class="font-semibold" data-testid="speak-heard">{{
                    last.transcript
                }}</span>
            </p>
            <template v-if="last.result">
                <p class="flex flex-wrap gap-1.5">
                    <span
                        v-for="(word, index) in last.result.words"
                        :key="index"
                        :class="
                            cn(
                                'rounded-md px-2 py-1 text-sm',
                                verdictStyle[word.verdict],
                            )
                        "
                        :data-verdict="word.verdict"
                        >{{ word.word }}</span
                    >
                </p>
                <p
                    v-if="!isRepeat && last.result.missed > 0"
                    class="text-sm text-muted-foreground"
                >
                    {{ t('lesson.speak.missingKeywords', last.result.missed) }}
                </p>
                <p
                    class="text-sm text-muted-foreground"
                    data-testid="speak-score"
                >
                    {{
                        last.result.correct
                            ? t('lesson.speak.goodTry', {
                                  score: Math.round(last.result.score),
                              })
                            : t('lesson.speak.tryAgain', {
                                  score: Math.round(last.result.score),
                              })
                    }}
                </p>
            </template>
            <p v-else class="text-sm text-muted-foreground">
                {{ t('lesson.speak.notScored') }}
            </p>
        </div>
    </section>
</template>
