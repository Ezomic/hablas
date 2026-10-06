<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Badge } from '@/components/ui/badge';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { index, show } from '@/routes/reading';

interface Story {
    id: number;
    title: string;
    cefrLevel: string;
    questions: number;
    best: number | null;
}

const props = defineProps<{
    stories: Story[];
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('reading.title'), href: index() }]);
</script>

<template>
    <Head :title="t('reading.title')" />

    <div class="mx-auto flex max-w-2xl flex-col gap-4 p-4">
        <h1 class="text-2xl font-semibold">{{ t('reading.title') }}</h1>

        <ul v-if="props.stories.length > 0" class="flex flex-col gap-3">
            <li v-for="story in props.stories" :key="story.id">
                <Link
                    :href="show(story.id)"
                    class="flex items-center justify-between gap-3 rounded-lg border p-4 hover:bg-accent"
                    data-testid="story"
                >
                    <span class="flex flex-col gap-1">
                        <span class="font-medium">{{ story.title }}</span>
                        <span class="text-sm text-muted-foreground">
                            {{ t('reading.questions', { n: story.questions }) }}
                        </span>
                    </span>
                    <span class="flex flex-col items-end gap-1">
                        <Badge variant="secondary">{{ story.cefrLevel }}</Badge>
                        <span
                            v-if="story.best !== null"
                            class="text-xs text-muted-foreground"
                        >
                            {{ t('reading.best', { score: story.best }) }}
                        </span>
                    </span>
                </Link>
            </li>
        </ul>

        <p v-else class="text-muted-foreground">
            {{ t('reading.empty') }}
        </p>
    </div>
</template>
