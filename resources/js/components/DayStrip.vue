<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Check, Flame, Layers } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import ProgressRing from '@/components/ProgressRing.vue';
import { index as reviewIndex } from '@/routes/review';
import type { DayProgress } from '@/types/unit';

const props = defineProps<{ day: DayProgress }>();

const { t } = useI18n();

const reached = computed(() => props.day.words >= props.day.goal);
const percent = computed(() =>
    Math.round(
        (Math.min(props.day.words, props.day.goal) / props.day.goal) * 100,
    ),
);
</script>

<template>
    <section
        class="flex items-center gap-4 rounded-xl border bg-card p-3"
        data-testid="day-strip"
    >
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <ProgressRing
                :value="percent"
                :size="56"
                :text="reached ? '✓' : `${props.day.words}/${props.day.goal}`"
                :label="
                    t('day.words', {
                        words: props.day.words,
                        goal: props.day.goal,
                    })
                "
            />
            <p class="min-w-0 text-sm" data-testid="day-goal">
                <span
                    v-if="reached"
                    class="flex items-center gap-1 font-medium"
                >
                    <Check class="size-4 text-primary" />
                    {{ t('day.reached') }}
                </span>
                <template v-else>
                    {{
                        t('day.words', {
                            words: props.day.words,
                            goal: props.day.goal,
                        })
                    }}
                </template>
            </p>
        </div>

        <p
            class="flex items-center gap-1 text-sm"
            :class="props.day.streak > 0 ? '' : 'text-muted-foreground'"
            data-testid="day-streak"
        >
            <Flame
                class="size-5"
                :class="props.day.streak > 0 ? 'text-primary' : ''"
            />
            {{ props.day.streak }}
        </p>

        <Link
            :href="reviewIndex().url"
            class="flex items-center gap-1 rounded-md px-2 py-1 text-sm hover:bg-muted"
            data-testid="day-due"
            :aria-label="t('day.due', props.day.due)"
        >
            <Layers
                class="size-5"
                :class="
                    props.day.due > 0 ? 'text-primary' : 'text-muted-foreground'
                "
            />
            {{ props.day.due }}
        </Link>
    </section>
</template>
