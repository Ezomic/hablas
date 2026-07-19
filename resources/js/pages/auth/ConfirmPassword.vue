<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    index as confirmOptions,
    store as confirmStore,
} from '@/actions/Laravel/Passkeys/Http/Controllers/PasskeyConfirmationController';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/password/confirm';
import { store as sendConfirmCode } from '@/routes/user/confirm-code';

defineOptions({
    layout: {
        title: 'Confirm it’s you',
        description:
            'This is a secure area of the application. Please confirm it’s you before continuing.',
    },
});

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
    <Head title="Confirm it’s you" />

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
        label="Confirm with passkey"
        loading-label="Confirming..."
        separator="Or confirm with an emailed code"
    />

    <form @submit.prevent="submit">
        <div class="space-y-6">
            <div class="grid gap-2">
                <Label for="password">Confirmation code</Label>
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
                        Email me a confirmation code
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <Button
                    class="w-full"
                    :disabled="confirmForm.processing"
                    data-test="confirm-password-button"
                >
                    <Spinner v-if="confirmForm.processing" />
                    Confirm
                </Button>
            </div>
        </div>
    </form>
</template>
