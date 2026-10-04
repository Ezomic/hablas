<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Spinner } from '@/components/ui/spinner';

const { t } = useI18n();
</script>

<template>
    <Spinner :aria-label="t('common.loading')" />
</template>
