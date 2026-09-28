<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store as login } from '@/routes/login';
import { store as requestCode } from '@/routes/login/code';
import {
    login as passkeyLogin,
    loginOptions as passkeyLoginOptions,
} from '@/routes/passkey';
import { redirect as ssoRedirect } from '@/routes/sso';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description:
            'Sign in with Thijssensoftware, a passkey, or an emailed code',
    },
});

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
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Button class="w-full" as-child>
        <a :href="ssoRedirect().url" data-test="sso-button">
            Sign in with Thijssensoftware
        </a>
    </Button>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <Separator class="w-full" />
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-background px-2 text-muted-foreground">
                Or continue with
            </span>
        </div>
    </div>

    <div class="mb-6 flex items-center">
        <Label for="remember" class="flex items-center space-x-3">
            <Checkbox id="remember" v-model="codeForm.remember" :tabindex="3" />
            <span>Remember me</span>
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
            <Label for="email">Email address</Label>
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
            <Spinner v-if="emailForm.processing" />
            Email me a code
        </Button>

        <div class="text-center text-sm text-muted-foreground">
            Don't have an account?
            <TextLink :href="register()" :tabindex="4">Sign up</TextLink>
        </div>
    </form>

    <form v-else @submit.prevent="submitCode" class="flex flex-col gap-6">
        <div class="grid gap-2">
            <Label for="code">Sign-in code</Label>
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
                We sent a code to {{ codeForm.email }}. It expires in 10
                minutes.
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
            <Spinner v-if="codeForm.processing" />
            Log in
        </Button>

        <div class="text-center text-sm text-muted-foreground">
            <button
                type="button"
                class="underline underline-offset-4"
                @click="useDifferentEmail"
                :tabindex="4"
            >
                Use a different email
            </button>
        </div>
    </form>
</template>
