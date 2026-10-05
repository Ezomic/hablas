<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    AudioLines,
    BookA,
    BookOpenText,
    Headphones,
    MessagesSquare,
    Mic,
    NotebookPen,
    PenLine,
    TriangleAlert,
} from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { index as listeningIndex } from '@/routes/listening';
import { index as pronunciationDrillsIndex } from '@/routes/pronunciation-drills';
import { index as readingIndex } from '@/routes/reading';
import { index as reflectionsIndex } from '@/routes/reflections';
import { index as weakSpotsIndex } from '@/routes/review/weak-spots';
import { index as scriptedPromptsIndex } from '@/routes/scripted-prompts';
import { index as shadowingIndex } from '@/routes/shadowing';
import { index as vocabularyIndex } from '@/routes/vocabulary';
import { index as writingIndex } from '@/routes/writing';

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.practise'), href: '/practice' }]);

const modes = computed(() => [
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
    { title: t('nav.weakSpots'), href: weakSpotsIndex(), icon: TriangleAlert },
    { title: t('nav.vocabulary'), href: vocabularyIndex(), icon: BookA },
    {
        title: t('nav.weeklyReflection'),
        href: reflectionsIndex(),
        icon: NotebookPen,
    },
]);
</script>

<template>
    <Head :title="t('nav.practise')" />

    <div class="flex flex-col gap-4 p-4">
        <h1 class="text-2xl font-semibold">{{ t('nav.practise') }}</h1>
        <ul class="grid grid-cols-2 gap-3" data-testid="practice-modes">
            <li v-for="mode in modes" :key="mode.title">
                <Link
                    :href="mode.href"
                    class="flex h-28 flex-col justify-between rounded-xl border bg-card p-4 transition-colors active:bg-accent"
                >
                    <component
                        :is="mode.icon"
                        class="size-6 text-primary"
                        aria-hidden="true"
                    />
                    <span class="text-sm font-medium">{{ mode.title }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>
