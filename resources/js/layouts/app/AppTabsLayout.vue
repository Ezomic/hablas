<script setup lang="ts">
import AppToaster from '@/components/AppToaster.vue';
import BottomTabs from '@/components/BottomTabs.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import OfflineSyncBanner from '@/components/OfflineSyncBanner.vue';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <div class="mx-auto flex min-h-dvh w-full max-w-2xl flex-col">
        <header
            class="sticky top-0 z-30 flex h-14 shrink-0 items-center justify-between gap-2 border-b bg-background/95 px-4 pt-[env(safe-area-inset-top)] backdrop-blur"
        >
            <div class="min-w-0 flex-1">
                <Breadcrumbs
                    v-if="breadcrumbs.length > 0"
                    :breadcrumbs="breadcrumbs"
                />
            </div>
            <LanguageSwitcher />
        </header>
        <OfflineSyncBanner />
        <main
            class="flex flex-1 flex-col pb-[calc(5rem+env(safe-area-inset-bottom))]"
        >
            <slot />
        </main>
        <BottomTabs />
        <AppToaster />
    </div>
</template>
