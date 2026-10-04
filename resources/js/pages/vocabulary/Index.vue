<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { watchDebounced } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import SpeakButton from '@/components/SpeakButton.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { dueLabel } from '@/lib/dueLabel';
import { index } from '@/routes/vocabulary';
import type {
    SrsCardState,
    VocabularyEntry,
    VocabularyFilters,
    VocabularyPagination,
    VocabularySort,
} from '@/types/vocabulary';

const props = defineProps<{
    items: VocabularyEntry[];
    pagination: VocabularyPagination;
    filters: VocabularyFilters;
    speechLocale: string | null;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('nav.vocabulary'), href: index() }]);

const sortOptions: VocabularySort[] = ['recent', 'due', 'alphabetical'];

const stateKeys: Record<SrsCardState, string> = {
    new: 'vocabulary.state.new',
    learning: 'vocabulary.state.learning',
    review: 'vocabulary.state.review',
    relearning: 'vocabulary.state.relearning',
};

const emptyMessage = computed(() =>
    props.filters.q === '' ? t('vocabulary.empty') : t('vocabulary.noMatch'),
);

const search = ref(props.filters.q);

function pageUrl(q: string, sort: VocabularySort, page = 1): string {
    return index({
        query: {
            q: q === '' ? undefined : q,
            sort: sort === 'recent' ? undefined : sort,
            page: page === 1 ? undefined : page,
        },
    }).url;
}

function reload(q: string, sort: VocabularySort) {
    router.get(
        pageUrl(q, sort),
        {},
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

watchDebounced(search, (value) => reload(value.trim(), props.filters.sort), {
    debounce: 300,
});

function changeSort(value: unknown) {
    reload(search.value.trim(), value as VocabularySort);
}
</script>

<template>
    <Head :title="t('nav.vocabulary')" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ t('nav.vocabulary') }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ t('vocabulary.intro') }}
            </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
            <div class="relative flex-1">
                <Search
                    class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
                />
                <Input
                    v-model="search"
                    type="search"
                    class="pl-9"
                    :placeholder="t('vocabulary.searchPlaceholder')"
                    :aria-label="t('vocabulary.searchLabel')"
                />
            </div>
            <Select
                :model-value="props.filters.sort"
                @update:model-value="changeSort"
            >
                <SelectTrigger
                    class="w-full sm:w-44"
                    :aria-label="t('vocabulary.sortBy')"
                >
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="value in sortOptions"
                        :key="value"
                        :value="value"
                    >
                        {{ t(`vocabulary.sort.${value}`) }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <template v-if="props.items.length > 0">
            <p class="text-sm text-muted-foreground">
                {{ t('vocabulary.count', props.pagination.total) }}
            </p>

            <ul class="divide-y rounded-xl border">
                <li
                    v-for="item in props.items"
                    :key="item.id"
                    class="flex flex-col gap-2 p-4 sm:flex-row sm:items-start sm:justify-between sm:gap-4"
                >
                    <div class="flex min-w-0 flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <span
                                class="font-medium break-words"
                                :lang="
                                    item.kind === 'vocabulary'
                                        ? (props.speechLocale ?? undefined)
                                        : undefined
                                "
                                >{{ item.term }}</span
                            >
                            <SpeakButton
                                v-if="item.kind === 'vocabulary'"
                                :text="item.term"
                                :locale="props.speechLocale"
                                :audio-url="item.audioUrl"
                                :audio-slow-url="item.audioSlowUrl"
                            />
                            <Badge v-else variant="outline" class="ml-1">{{
                                t('units.grammar')
                            }}</Badge>
                        </div>
                        <p
                            class="text-sm text-muted-foreground"
                            :class="{ 'line-clamp-2': item.kind === 'grammar' }"
                        >
                            {{ item.translation }}
                        </p>
                    </div>
                    <div
                        class="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end"
                    >
                        <Badge variant="secondary">{{
                            t(stateKeys[item.state])
                        }}</Badge>
                        <Badge v-if="item.isWeakSpot" variant="destructive">{{
                            t('vocabulary.weakSpot')
                        }}</Badge>
                        <span class="text-xs text-muted-foreground">{{
                            item.state === 'new'
                                ? t('vocabulary.notStudied')
                                : dueLabel(item.dueAt)
                        }}</span>
                    </div>
                </li>
            </ul>
        </template>

        <p
            v-else
            class="rounded-xl border border-dashed p-8 text-center text-sm text-muted-foreground"
        >
            {{ emptyMessage }}
        </p>

        <nav
            v-if="props.pagination.lastPage > 1"
            class="flex items-center justify-between gap-2"
            :aria-label="t('vocabulary.pages')"
        >
            <Button
                v-if="props.pagination.currentPage > 1"
                variant="outline"
                size="sm"
                as-child
            >
                <Link
                    :href="
                        pageUrl(
                            props.filters.q,
                            props.filters.sort,
                            props.pagination.currentPage - 1,
                        )
                    "
                    >{{ t('common.previous') }}</Link
                >
            </Button>
            <Button v-else variant="outline" size="sm" disabled>{{
                t('common.previous')
            }}</Button>

            <span class="text-sm text-muted-foreground">
                {{
                    t('vocabulary.page', {
                        current: props.pagination.currentPage,
                        last: props.pagination.lastPage,
                    })
                }}
            </span>

            <Button
                v-if="props.pagination.currentPage < props.pagination.lastPage"
                variant="outline"
                size="sm"
                as-child
            >
                <Link
                    :href="
                        pageUrl(
                            props.filters.q,
                            props.filters.sort,
                            props.pagination.currentPage + 1,
                        )
                    "
                    >{{ t('common.next') }}</Link
                >
            </Button>
            <Button v-else variant="outline" size="sm" disabled>{{
                t('common.next')
            }}</Button>
        </nav>
    </div>
</template>
