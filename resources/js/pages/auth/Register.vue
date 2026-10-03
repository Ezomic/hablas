<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useLayoutText } from '@/composables/useLayoutText';
import { login } from '@/routes';
import { store } from '@/routes/register';

const { t } = useI18n();

useLayoutText(() => ({
    title: t('auth.register.title'),
    description: t('auth.register.description'),
}));
</script>

<template>
    <Head :title="$t('auth.register.headTitle')" />

    <Form
        v-bind="store.form()"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">{{ $t('auth.register.name') }}</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    :placeholder="$t('auth.register.namePlaceholder')"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">{{ $t('common.emailAddress') }}</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="2"
                    autocomplete="email"
                    name="email"
                    placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
                <p class="text-xs text-muted-foreground">
                    {{ $t('auth.register.noPassword') }}
                </p>
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="3"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                {{ $t('common.createAccount') }}
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            {{ $t('auth.register.haveAccount') }}
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="4"
                >{{ $t('common.logIn') }}</TextLink
            >
        </div>
    </Form>
</template>
