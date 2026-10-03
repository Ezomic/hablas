<script setup lang="ts">
import { X } from '@lucide/vue';
import { useI18n } from 'vue-i18n';
import { DialogClose, DialogContent } from '@/components/ui/dialog';

const { t } = useI18n();
</script>

<template>
    <DialogContent :show-close-button="false">
        <slot />

        <DialogClose
            class="absolute top-4 right-4 rounded-xs opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
        >
            <X />
            <span class="sr-only">{{ t('common.close') }}</span>
        </DialogClose>
    </DialogContent>
</template>
