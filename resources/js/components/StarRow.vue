<script setup lang="ts">
import { Star } from '@lucide/vue';
import { useI18n } from 'vue-i18n';

const props = withDefaults(defineProps<{ stars: number; size?: string }>(), {
    size: 'size-5',
});

const { t } = useI18n();
</script>

<template>
    <span
        class="flex gap-0.5"
        role="img"
        :aria-label="t('progress.stars', { count: props.stars }, props.stars)"
        data-testid="star-row"
    >
        <Star
            v-for="n in 3"
            :key="n"
            :class="[
                props.size,
                n <= props.stars
                    ? 'fill-amber-400 text-amber-400'
                    : 'text-muted-foreground/40',
            ]"
            aria-hidden="true"
        />
    </span>
</template>
