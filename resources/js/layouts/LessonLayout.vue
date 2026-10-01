<script setup lang="ts">
import OfflineSyncBanner from '@/components/OfflineSyncBanner.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div class="flex min-h-dvh w-full flex-col bg-background">
        <OfflineSyncBanner />
        <slot />
        <Toaster />
    </div>
</template>
