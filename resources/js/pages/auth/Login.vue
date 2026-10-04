<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import AppSpinner from '@/components/AppSpinner.vue';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { useLayoutText } from '@/composables/useLayoutText';
import { register } from '@/routes';
import { store as login } from '@/routes/login';
import { store as requestCode } from '@/routes/login/code';
import {
    login as passkeyLogin,
    loginOptions as passkeyLoginOptions,
} from '@/routes/passkey';
import { redirect as ssoRedirect } from '@/routes/sso';

const { t } = useI18n();

useLayoutText(() => ({
    title: t('auth.login.title'),
    description: t('auth.login.description'),
}));

defineProps<{
    status?: string;
}>();

const step = ref<'email' | 'code'>('email');

const emailForm = useForm({ email: '' });

const codeForm = useForm({ email: '', code: '', remember: false });

const submitEmail = () => {
    emailForm.post(requestCode().url, {
        preserveScroll: true,
        onSuccess: () => {
            codeForm.email = emailForm.email;
            step.value = 'code';
        },
    });
};

const submitCode = () => {
    codeForm.post(login().url, {
        onFinish: () => codeForm.reset('code'),
    });
};

const useDifferentEmail = () => {
    step.value = 'email';
    codeForm.code = '';
    codeForm.clearErrors();
};
</script>

<template>
    <Head :title="$t('auth.login.headTitle')" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Button class="w-full" as-child>
        <a :href="ssoRedirect().url" data-test="sso-button">
            {{ $t('auth.login.sso') }}
        </a>
    </Button>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <Separator class="w-full" />
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-background px-2 text-muted-foreground">
                {{ $t('auth.login.orContinueWith') }}
            </span>
        </div>
    </div>

    <div class="mb-6 flex items-center">
        <Label for="remember" class="flex items-center space-x-3">
            <Checkbox id="remember" v-model="codeForm.remember" :tabindex="3" />
            <span>{{ $t('auth.login.rememberMe') }}</span>
        </Label>
    </div>

    <PasskeyVerify
        :routes="{
            options: passkeyLoginOptions(),
            submit: passkeyLogin({ query: { remember: codeForm.remember } }),
        }"
    />

    <form
        v-if="step === 'email'"
        @submit.prevent="submitEmail"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-2">
            <Label for="email">{{ $t('common.emailAddress') }}</Label>
            <Input
                id="email"
                type="email"
                v-model="emailForm.email"
                required
                autofocus
                :tabindex="1"
                autocomplete="email webauthn"
                placeholder="email@example.com"
            />
            <InputError :message="emailForm.errors.email" />
        </div>

        <Button
            type="submit"
            class="w-full"
            :tabindex="2"
            :disabled="emailForm.processing"
            data-test="request-code-button"
        >
            <AppSpinner v-if="emailForm.processing" />
            {{ $t('auth.login.requestCode') }}
        </Button>

        <div class="text-center text-sm text-muted-foreground">
            {{ $t('auth.login.noAccount') }}
            <TextLink :href="register()" :tabindex="4">{{
                $t('auth.login.signUp')
            }}</TextLink>
        </div>
    </form>

    <form v-else @submit.prevent="submitCode" class="flex flex-col gap-6">
        <div class="grid gap-2">
            <Label for="code">{{ $t('auth.login.codeLabel') }}</Label>
            <Input
                id="code"
                type="text"
                v-model="codeForm.code"
                required
                autofocus
                :tabindex="1"
                inputmode="numeric"
                autocomplete="one-time-code"
                placeholder="123456"
                data-test="code-input"
            />
            <p class="text-xs text-muted-foreground">
                {{ $t('auth.login.codeSent', { email: codeForm.email }) }}
            </p>
            <InputError :message="codeForm.errors.code" />
            <InputError :message="codeForm.errors.email" />
        </div>

        <Button
            type="submit"
            class="w-full"
            :tabindex="2"
            :disabled="codeForm.processing"
            data-test="login-button"
        >
            <AppSpinner v-if="codeForm.processing" />
            {{ $t('common.logIn') }}
        </Button>

        <div class="text-center text-sm text-muted-foreground">
            <button
                type="button"
                class="underline underline-offset-4"
                @click="useDifferentEmail"
                :tabindex="4"
            >
                {{ $t('auth.login.useDifferentEmail') }}
            </button>
        </div>
    </form>
</template>
