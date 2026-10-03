<script setup lang="ts">
const props = defineProps<{ glosses?: [string, string][] }>();
</script>

<template>
    <p
        v-if="props.glosses?.length"
        class="text-sm text-muted-foreground"
        data-testid="glosses"
    >
        {{
            props.glosses
                .map(([word, meaning]) => `${word}: ${meaning}`)
                .join(', ')
        }}
    </p>
</template>
