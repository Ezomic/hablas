<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { Props as ManagePasskeysProps } from '@/components/ManagePasskeys.vue';
import ManagePasskeys from '@/components/ManagePasskeys.vue';
import type { Props as ManageTwoFactorProps } from '@/components/ManageTwoFactor.vue';
import ManageTwoFactor from '@/components/ManageTwoFactor.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { edit } from '@/routes/security';

type Props = ManagePasskeysProps & ManageTwoFactorProps;

defineProps<Props>();

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('settings.security.headTitle'), href: edit() },
]);
</script>

<template>
    <Head :title="t('settings.security.headTitle')" />

    <h1 class="sr-only">{{ t('settings.security.headTitle') }}</h1>

    <ManageTwoFactor
        :canManageTwoFactor="canManageTwoFactor"
        :requiresConfirmation="requiresConfirmation"
        :twoFactorEnabled="twoFactorEnabled"
    />

    <ManagePasskeys
        :canManagePasskeys="canManagePasskeys"
        :passkeys="passkeys"
    />
</template>
