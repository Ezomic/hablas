<script setup lang="ts">
import InterfaceLocaleSwitcher from '@/components/InterfaceLocaleSwitcher.vue';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

const { title = '', description = '' } = defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative">
        <div class="absolute top-4 right-4 z-10 md:top-6 md:right-6">
            <InterfaceLocaleSwitcher />
        </div>
        <AuthLayout :title="title" :description="description">
            <slot />
        </AuthLayout>
    </div>
</template>
