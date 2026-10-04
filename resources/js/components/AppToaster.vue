<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Toaster } from '@/components/ui/sonner';

const { t } = useI18n();
</script>

<template>
    <Toaster
        :container-aria-label="t('common.notifications')"
        :toast-options="{ closeButtonAriaLabel: t('common.closeNotification') }"
    />
</template>
