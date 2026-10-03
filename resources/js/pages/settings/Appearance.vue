<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { edit } from '@/routes/appearance';

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('settings.appearance.headTitle'), href: edit() },
]);
</script>

<template>
    <Head :title="t('settings.appearance.headTitle')" />

    <h1 class="sr-only">{{ t('settings.appearance.headTitle') }}</h1>

    <div class="space-y-6">
        <Heading
            variant="small"
            :title="t('settings.appearance.headTitle')"
            :description="t('settings.appearance.description')"
        />
        <AppearanceTabs />
    </div>
</template>
