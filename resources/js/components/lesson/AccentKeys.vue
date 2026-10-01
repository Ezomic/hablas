<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{ locale: string | null }>();

const emit = defineEmits<{ insert: [character: string] }>();

const spanish = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', '¿', '¡'];
const portuguese = ['á', 'à', 'â', 'ã', 'ç', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú'];

const keys = computed(() =>
    props.locale?.startsWith('pt') ? portuguese : spanish,
);
</script>

<template>
    <div class="flex flex-wrap gap-1" role="group" aria-label="Accent keys">
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
