<script setup lang="ts">
import InterfaceLocaleSwitcher from '@/components/InterfaceLocaleSwitcher.vue';
import { useInterfaceLocale } from '@/composables/useInterfaceLocale';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

const { title = '', description = '' } = defineProps<{
    title?: string;
    description?: string;
}>();

const { supported } = useInterfaceLocale();
</script>

<template>
    <div class="relative">
        <div
            v-if="supported.length > 1"
            class="absolute top-4 right-4 z-10 md:top-6 md:right-6"
        >
            <InterfaceLocaleSwitcher />
        </div>
        <AuthLayout
            :title="title"
            :description="description"
            :reserve-top="supported.length > 1"
        >
            <slot />
        </AuthLayout>
    </div>
</template>
