<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import AppSheetContent from '@/components/AppSheetContent.vue';
import { Sheet } from '@/components/ui/sheet';
import SheetDescription from '@/components/ui/sheet/SheetDescription.vue';
import SheetHeader from '@/components/ui/sheet/SheetHeader.vue';
import SheetTitle from '@/components/ui/sheet/SheetTitle.vue';
import type { SidebarProps } from '@/components/ui/sidebar';
import { Sidebar, useSidebar } from '@/components/ui/sidebar';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<SidebarProps>(), {
    side: 'left',
    variant: 'sidebar',
    collapsible: 'offcanvas',
});

const { t } = useI18n();
const { isMobile, openMobile, setOpenMobile } = useSidebar();
</script>

<template>
    <Sheet
        v-if="isMobile"
        :open="openMobile"
        v-bind="$attrs"
        @update:open="setOpenMobile"
    >
        <AppSheetContent
            data-sidebar="sidebar"
            data-slot="sidebar"
            data-mobile="true"
            :side="props.side"
            class="w-(--sidebar-width) bg-sidebar p-0 text-sidebar-foreground [&>button]:hidden"
            :style="{ '--sidebar-width': '18rem' }"
        >
            <SheetHeader class="sr-only">
                <SheetTitle>{{ t('nav.sidebar') }}</SheetTitle>
                <SheetDescription>{{
                    t('nav.sidebarDescription')
                }}</SheetDescription>
            </SheetHeader>
            <div class="flex h-full w-full flex-col">
                <slot />
            </div>
        </AppSheetContent>
    </Sheet>

    <Sidebar v-else v-bind="{ ...props, ...$attrs }">
        <slot />
    </Sidebar>
</template>
