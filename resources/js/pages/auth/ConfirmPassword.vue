<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';
import AppSpinner from '@/components/AppSpinner.vue';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useLayoutText } from '@/composables/useLayoutText';
import { store } from '@/routes/password/confirm';
import { store as sendConfirmCode } from '@/routes/user/confirm-code';

const { t } = useI18n();

useLayoutText(() => ({
    title: t('auth.confirm.title'),
    description: t('auth.confirm.description'),
}));

defineProps<{
    status?: string;
}>();

const sendForm = useForm({});

// Fortify's ConfirmablePasswordController reads $request->input('password'),
// so the one-time code has to travel under that field name.
const confirmForm = useForm({ password: '' });

const sendCode = () =>
    sendForm.post(sendConfirmCode().url, { preserveScroll: true });

const submit = () =>
    confirmForm.post(store().url, {
        onFinish: () => confirmForm.reset('password'),
    });
</script>

<template>
    <Head :title="$t('auth.confirm.title')" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <PasskeyVerify
        :routes="{
            options: confirmOptions(),
            submit: confirmStore(),
        }"
        :label="$t('auth.confirm.passkey')"
        :loading-label="$t('auth.confirm.passkeyLoading')"
        :separator="$t('auth.confirm.orEmailedCode')"
    />

    <form @submit.prevent="submit">
        <div class="space-y-6">
            <div class="grid gap-2">
                <Label for="password">{{ $t('auth.confirm.codeLabel') }}</Label>
                <Input
                    id="password"
                    type="text"
                    v-model="confirmForm.password"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    placeholder="123456"
                    data-test="confirm-code-input"
                />

                <InputError :message="confirmForm.errors.password" />

                <div class="text-xs text-muted-foreground">
                    <button
                        type="button"
                        class="underline underline-offset-4"
                        @click="sendCode"
                        :disabled="sendForm.processing"
                        data-test="send-confirm-code-button"
                    >
                        {{ $t('auth.confirm.sendCode') }}
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <Button
                    class="w-full"
                    :disabled="confirmForm.processing"
                    data-test="confirm-password-button"
                >
                    <AppSpinner v-if="confirmForm.processing" />
                    {{ $t('auth.confirm.submit') }}
                </Button>
            </div>
        </div>
    </form>
</template>
