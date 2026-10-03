<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    AudioLines,
    BookA,
    BookOpenText,
    Headphones,
    Layers,
    LayoutGrid,
    Library,
    MessagesSquare,
    Mic,
    NotebookPen,
    PenLine,
    TriangleAlert,
} from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
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
import { index as listeningIndex } from '@/routes/listening';
import { index as pronunciationDrillsIndex } from '@/routes/pronunciation-drills';
import { index as readingIndex } from '@/routes/reading';
import { index as reflectionsIndex } from '@/routes/reflections';
import { index as reviewIndex } from '@/routes/review';
import { index as weakSpotsIndex } from '@/routes/review/weak-spots';
import { index as scriptedPromptsIndex } from '@/routes/scripted-prompts';
import { index as shadowingIndex } from '@/routes/shadowing';
import { index as unitsIndex } from '@/routes/units';
import { index as vocabularyIndex } from '@/routes/vocabulary';
import { index as writingIndex } from '@/routes/writing';
import type { NavItem } from '@/types';

const { t } = useI18n();

const studyNavItems = computed<NavItem[]>(() => [
    { title: t('nav.dashboard'), href: dashboard(), icon: LayoutGrid },
    { title: t('nav.units'), href: unitsIndex(), icon: Library },
    { title: t('nav.review'), href: reviewIndex(), icon: Layers },
    { title: t('nav.weakSpots'), href: weakSpotsIndex(), icon: TriangleAlert },
    { title: t('nav.vocabulary'), href: vocabularyIndex(), icon: BookA },
]);

const practiceNavItems = computed<NavItem[]>(() => [
    { title: t('nav.reading'), href: readingIndex(), icon: BookOpenText },
    { title: t('nav.listening'), href: listeningIndex(), icon: Headphones },
    { title: t('nav.shadowing'), href: shadowingIndex(), icon: Mic },
    { title: t('nav.writing'), href: writingIndex(), icon: PenLine },
    {
        title: t('nav.scriptedPrompts'),
        href: scriptedPromptsIndex(),
        icon: MessagesSquare,
    },
    {
        title: t('nav.pronunciationDrills'),
        href: pronunciationDrillsIndex(),
        icon: AudioLines,
    },
    {
        title: t('nav.weeklyReflection'),
        href: reflectionsIndex(),
        icon: NotebookPen,
    },
]);
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
            <NavMain :label="t('nav.study')" :items="studyNavItems" />
            <NavMain :label="t('nav.practice')" :items="practiceNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
