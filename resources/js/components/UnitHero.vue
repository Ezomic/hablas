<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import ProgressRing from '@/components/ProgressRing.vue';
import StarRow from '@/components/StarRow.vue';
import { Button } from '@/components/ui/button';
import WordGems from '@/components/WordGems.vue';
import type { UnitProgress } from '@/types/unit';

const props = defineProps<{
    progress: UnitProgress;
    canContinue: boolean;
    started: boolean;
    busy: boolean;
}>();

const emit = defineEmits<{ continue: [] }>();

const { t } = useI18n();
</script>

<template>
    <section
        class="flex flex-col gap-4 rounded-xl border bg-card p-4"
        data-testid="unit-hero"
    >
        <div class="flex items-center gap-4">
            <ProgressRing
                :value="props.progress.percent"
                :label="
                    t('progress.unitPercent', {
                        percent: props.progress.percent,
                    })
                "
            />
            <div class="flex min-w-0 flex-col gap-1">
                <StarRow :stars="props.progress.stars" size="size-6" />
                <p class="text-sm">
                    {{
                        t('progress.wordsKnown', {
                            known: props.progress.known,
                            total: props.progress.total,
                        })
                    }}
                </p>
                <p
                    class="text-xs text-muted-foreground"
                    data-testid="level-progress"
                >
                    {{
                        t('progress.level', {
                            level: props.progress.level.code,
                            known: props.progress.level.known,
                            total: props.progress.level.total,
                            percent: props.progress.level.percent,
                        })
                    }}
                </p>
            </div>
        </div>

        <WordGems :words="props.progress.words" />

        <Button
            v-if="props.canContinue"
            size="lg"
            class="h-12 text-base"
            :disabled="props.busy"
            data-testid="continue-button"
            @click="emit('continue')"
        >
            {{ props.started ? t('progress.continue') : t('progress.start') }}
        </Button>
    </section>
</template>
