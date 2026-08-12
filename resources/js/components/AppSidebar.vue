<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    AudioLines,
    BookOpenText,
    Layers,
    LayoutGrid,
    MessagesSquare,
    Mic,
    NotebookPen,
    PenLine,
    TriangleAlert,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as pronunciationDrillsIndex } from '@/routes/pronunciation-drills';
import { index as readingIndex } from '@/routes/reading';
import { index as reflectionsIndex } from '@/routes/reflections';
import { index as reviewIndex } from '@/routes/review';
import { index as weakSpotsIndex } from '@/routes/review/weak-spots';
import { index as scriptedPromptsIndex } from '@/routes/scripted-prompts';
import { index as shadowingIndex } from '@/routes/shadowing';
import { index as writingIndex } from '@/routes/writing';
import type { NavItem } from '@/types';

const studyNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Review', href: reviewIndex(), icon: Layers },
    { title: 'Weak spots', href: weakSpotsIndex(), icon: TriangleAlert },
];

const practiceNavItems: NavItem[] = [
    { title: 'Reading', href: readingIndex(), icon: BookOpenText },
    { title: 'Shadowing', href: shadowingIndex(), icon: Mic },
    { title: 'Writing', href: writingIndex(), icon: PenLine },
    {
        title: 'Scripted prompts',
        href: scriptedPromptsIndex(),
        icon: MessagesSquare,
    },
    {
        title: 'Pronunciation drills',
        href: pronunciationDrillsIndex(),
        icon: AudioLines,
    },
    {
        title: 'Weekly reflection',
        href: reflectionsIndex(),
        icon: NotebookPen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain label="Study" :items="studyNavItems" />
            <NavMain label="Practice" :items="practiceNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
