<script setup lang="ts">
import { Download } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { useInstallPrompt } from '@/composables/useInstallPrompt';

type Props = {
    layout?: 'inline' | 'menu';
};

withDefaults(defineProps<Props>(), { layout: 'inline' });

const { canInstall, hint, install } = useInstallPrompt();
</script>

<template>
    <template v-if="canInstall">
        <DropdownMenuItem
            v-if="layout === 'menu'"
            class="cursor-pointer"
            data-test="install-app"
            @select="install"
        >
            <Download class="mr-2 h-4 w-4" />
            {{ $t('install.button') }}
        </DropdownMenuItem>
        <Button
            v-else
            variant="outline"
            size="lg"
            data-test="install-app"
            @click="install"
        >
            <Download class="h-4 w-4" />
            {{ $t('install.button') }}
        </Button>
    </template>
    <p
        v-else-if="hint"
        class="text-sm text-muted-foreground"
        :class="{ 'px-2 py-1.5': layout === 'menu' }"
        data-test="install-hint"
    >
        {{ hint === 'ios' ? $t('install.iosHint') : $t('install.androidHint') }}
    </p>
</template>
