<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useInterfaceLocale } from '@/composables/useInterfaceLocale';

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
            :data-test="`interface-locale-${locale}`"
            class="rounded px-2.5 py-1 transition-colors"
            :class="
                locale === current
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:text-foreground'
            "
            @click="change(locale)"
        >
            {{ t(`interfaceLocale.${locale}`) }}
        </button>
    </div>
</template>
