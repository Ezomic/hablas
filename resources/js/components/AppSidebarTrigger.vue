<script setup lang="ts">
import { PanelLeftClose, PanelLeftOpen } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';
import { useSidebar } from '@/components/ui/sidebar';

const { t } = useI18n();
const { isMobile, state, toggleSidebar } = useSidebar();
</script>

<template>
    <Button
        data-sidebar="trigger"
        data-slot="sidebar-trigger"
        variant="ghost"
        size="icon"
        class="-ml-1 h-7 w-7"
        @click="toggleSidebar"
    >
        <PanelLeftOpen v-if="isMobile || state === 'collapsed'" />
        <PanelLeftClose v-else />
        <span class="sr-only">{{ t('nav.toggleSidebar') }}</span>
    </Button>
</template>
