<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import {
    DropdownMenuLabel,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { useInterfaceLocale } from '@/composables/useInterfaceLocale';
import type { InterfaceLocale } from '@/i18n';

const { t } = useI18n();
const { current, supported, change } = useInterfaceLocale();
</script>

<template>
    <template v-if="supported.length > 1">
        <DropdownMenuSeparator />
        <DropdownMenuLabel
            class="text-xs font-normal text-muted-foreground"
            data-test="interface-locale-menu-label"
        >
            {{ t('interfaceLocale.switcherLabel') }}
        </DropdownMenuLabel>
        <DropdownMenuRadioGroup
            :model-value="current"
            @update:model-value="(value) => change(value as InterfaceLocale)"
        >
            <DropdownMenuRadioItem
                v-for="locale in supported"
                :key="locale"
                :value="locale"
                :lang="locale"
                :data-test="`interface-locale-menu-${locale}`"
            >
                {{ t(`interfaceLocale.${locale}`) }}
            </DropdownMenuRadioItem>
        </DropdownMenuRadioGroup>
    </template>
</template>
