<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { useLayoutText } from '@/composables/useLayoutText';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

const { t } = useI18n();

useLayoutText(() => ({
    title: t('auth.verifyEmail.title'),
    description: t('auth.verifyEmail.description'),
}));

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head :title="$t('auth.verifyEmail.title')" />

    <div
        v-if="status === 'verification-link-sent'"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ $t('auth.verifyEmail.linkSent') }}
    </div>

    <Form
        v-bind="send.form()"
        class="space-y-6 text-center"
        v-slot="{ processing }"
    >
        <Button :disabled="processing" variant="secondary">
            <AppSpinner v-if="processing" />
            {{ $t('auth.verifyEmail.resend') }}
        </Button>

        <TextLink :href="logout()" as="button" class="mx-auto block text-sm">
            {{ $t('nav.logOut') }}
        </TextLink>
    </Form>
</template>
