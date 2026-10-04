<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';

const props = defineProps<{ locale: string | null }>();

const { t } = useI18n();

const emit = defineEmits<{ insert: [character: string] }>();

const spanish = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', '¿', '¡'];
const portuguese = ['á', 'à', 'â', 'ã', 'ç', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú'];

const french = [
    'à',
    'â',
    'ç',
    'é',
    'è',
    'ê',
    'ë',
    'î',
    'ï',
    'ô',
    'œ',
    'ù',
    'û',
    'ü',
    'ÿ',
    '«',
    '»',
];
const italian = ['à', 'è', 'é', 'ì', 'ò', 'ù'];

const keysByLanguage: Record<string, string[]> = {
    es: spanish,
    pt: portuguese,
    fr: french,
    it: italian,
};

const keys = computed(
    () => keysByLanguage[props.locale?.slice(0, 2) ?? 'es'] ?? spanish,
);
</script>

<template>
    <div
        class="flex flex-wrap gap-1"
        role="group"
        :aria-label="t('lesson.accentKeys')"
    >
        <Button
            v-for="key in keys"
            :key="key"
            type="button"
            variant="outline"
            size="sm"
            class="h-10 min-w-10 text-base"
            @mousedown.prevent
            @click="emit('insert', key)"
        >
            {{ key }}
        </Button>
    </div>
</template>
