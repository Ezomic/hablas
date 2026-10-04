<script setup lang="ts">
import AppToaster from '@/components/AppToaster.vue';
import OfflineSyncBanner from '@/components/OfflineSyncBanner.vue';
</script>

<template>
    <div class="flex min-h-dvh w-full flex-col bg-background">
        <OfflineSyncBanner />
        <slot />
        <AppToaster />
    </div>
</template>
