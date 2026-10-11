<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useInterfaceLocale } from '@/composables/useInterfaceLocale';

withDefaults(defineProps<{ compact?: boolean }>(), { compact: false });

const { t } = useI18n();
const { current, supported, change } = useInterfaceLocale();
</script>

<template>
    <div
        v-if="supported.length > 1"
        role="group"
        :aria-label="t('interfaceLocale.switcherLabel')"
        class="inline-flex rounded-md border p-0.5 text-sm"
        data-test="interface-locale-switcher"
    >
        <button
            v-for="locale in supported"
            :key="locale"
            type="button"
            :lang="locale"
            :aria-pressed="locale === current"
            :aria-label="compact ? t(`interfaceLocale.${locale}`) : undefined"
            :data-test="`interface-locale-${locale}`"
            class="rounded transition-colors"
            :class="[
                compact ? 'px-2 py-1 text-xs font-medium' : 'px-2.5 py-1',
                locale === current
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:text-foreground',
            ]"
            @click="change(locale)"
        >
            {{
                compact ? locale.toUpperCase() : t(`interfaceLocale.${locale}`)
            }}
        </button>
    </div>
</template>
