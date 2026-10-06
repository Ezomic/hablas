<script setup lang="ts">
import { Gem } from '@lucide/vue';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import SpeakButton from '@/components/SpeakButton.vue';
import { Badge } from '@/components/ui/badge';
import { clipUrl, text } from '@/lib/lessonPayload';
import type { ExerciseBase } from '@/types/lesson';

const props = defineProps<{
    exercise: ExerciseBase;
    locale: string | null;
    matched?: boolean;
}>();

const speaker = ref<{ play: () => void } | null>(null);

const { t } = useI18n();

interface Example {
    text: string;
    english: string;
    audioUrl: string | null;
    audioSlowUrl: string | null;
}

const isGrammar = computed(() => props.exercise.format === 'teach_grammar');

const number = computed(() => Number(props.exercise.payload.number) || 0);
const of = computed(() => Number(props.exercise.payload.of) || 0);

// A word is heard as it arrives, so the sound comes before the spelling.
// A browser that refuses sound before a tap simply stays quiet.
onMounted(() => {
    if (!isGrammar.value) {
        speaker.value?.play();
    }
});

const examples = computed<Example[]>(() => {
    const raw = props.exercise.payload.examples;

    return Array.isArray(raw)
        ? raw.map((example) => ({
              text: text((example as Record<string, unknown>).text),
              english: text((example as Record<string, unknown>).english),
              audioUrl: clipUrl((example as Record<string, unknown>).audioUrl),
              audioSlowUrl: clipUrl(
                  (example as Record<string, unknown>).audioSlowUrl,
              ),
          }))
        : [];
});
</script>

<template>
    <section
        v-if="!isGrammar"
        class="flex flex-col items-center gap-4 rounded-xl border bg-card p-6 text-center"
        data-testid="teach-word"
    >
        <p
            class="flex items-center gap-2 text-sm text-muted-foreground"
            data-testid="teach-heading"
        >
            <Gem
                class="size-4 transition-colors"
                :class="
                    props.matched
                        ? 'fill-primary text-primary'
                        : 'text-muted-foreground'
                "
                data-testid="teach-gem"
                :data-lit="props.matched ? 'true' : 'false'"
            />
            {{
                number > 0 && of > 0
                    ? t('lesson.teach.newWordOf', { number, of })
                    : t('lesson.teach.newWord')
            }}
        </p>
        <p class="flex items-center gap-1 text-3xl font-semibold">
            <span :lang="props.locale ?? undefined">{{
                text(props.exercise.payload.term)
            }}</span>
            <SpeakButton
                ref="speaker"
                :text="text(props.exercise.payload.term)"
                :locale="props.locale"
                :audio-url="clipUrl(props.exercise.payload.audioUrl)"
                :audio-slow-url="clipUrl(props.exercise.payload.audioSlowUrl)"
            />
        </p>
        <p class="text-xl">{{ text(props.exercise.payload.translation) }}</p>
        <div class="flex items-center gap-2 text-xs text-muted-foreground">
            <span>{{ text(props.exercise.payload.part_of_speech) }}</span>
            <Badge v-if="props.exercise.payload.is_cognate" variant="outline">{{
                t('lesson.teach.cognate')
            }}</Badge>
        </div>
        <p
            v-if="text(props.exercise.payload.contrast_note)"
            class="rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-900 dark:bg-amber-950 dark:text-amber-100"
        >
            {{ text(props.exercise.payload.contrast_note) }}
        </p>
    </section>

    <section
        v-else
        class="flex flex-col gap-4 rounded-xl border bg-card p-6"
        data-testid="teach-grammar"
    >
        <p class="text-sm text-muted-foreground">
            {{ t('lesson.teach.grammar') }}
        </p>
        <h2 class="text-xl font-semibold">
            {{ text(props.exercise.payload.title) }}
        </h2>
        <p class="text-sm leading-relaxed">
            {{ text(props.exercise.payload.explanation) }}
        </p>
        <ul v-if="examples.length" class="flex flex-col gap-2">
            <li
                v-for="example in examples"
                :key="example.text"
                class="flex items-center justify-between gap-2 rounded-md bg-muted px-3 py-2"
            >
                <span>
                    <span
                        class="block font-medium"
                        :lang="props.locale ?? undefined"
                        >{{ example.text }}</span
                    >
                    <span class="block text-sm text-muted-foreground">{{
                        example.english
                    }}</span>
                </span>
                <span class="flex shrink-0 items-center">
                    <SpeakButton
                        :text="example.text"
                        :locale="props.locale"
                        :audio-url="example.audioUrl"
                        :audio-slow-url="example.audioSlowUrl"
                    />
                </span>
            </li>
        </ul>
    </section>
</template>
