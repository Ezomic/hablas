<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Search } from '@lucide/vue';
import { watchDebounced } from '@vueuse/core';
import { ref } from 'vue';
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
import { dueLabel } from '@/lib/dueLabel';
import { pluralize } from '@/lib/pluralize';
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

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Vocabulary', href: index() }],
    },
});

const sortLabels: Record<VocabularySort, string> = {
    recent: 'Recently added',
    due: 'Due date',
    alphabetical: 'A to Z',
};

const stateLabels: Record<SrsCardState, string> = {
    new: 'New',
    learning: 'Learning',
    review: 'Review',
    relearning: 'Relearning',
};

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
    <Head title="Vocabulary" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Vocabulary</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Every word and grammar point from the units you've completed.
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
                    placeholder="Search words or translations"
                    aria-label="Search vocabulary"
                />
            </div>
            <Select
                :model-value="props.filters.sort"
                @update:model-value="changeSort"
            >
                <SelectTrigger class="w-full sm:w-44" aria-label="Sort by">
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="(label, value) in sortLabels"
                        :key="value"
                        :value="value"
                    >
                        {{ label }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <template v-if="props.items.length > 0">
            <p class="text-sm text-muted-foreground">
                {{ props.pagination.total }}
                {{ pluralize('card', props.pagination.total) }}
            </p>

            <ul class="divide-y rounded-xl border">
                <li
                    v-for="item in props.items"
                    :key="item.id"
                    class="flex flex-col gap-2 p-4 sm:flex-row sm:items-start sm:justify-between sm:gap-4"
                >
                    <div class="flex min-w-0 flex-col gap-1">
                        <div class="flex items-center gap-1">
                            <span class="font-medium break-words">{{
                                item.term
                            }}</span>
                            <SpeakButton
                                v-if="item.kind === 'vocabulary'"
                                :text="item.term"
                                :locale="props.speechLocale"
                            />
                            <Badge v-else variant="outline" class="ml-1"
                                >Grammar</Badge
                            >
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
                            stateLabels[item.state]
                        }}</Badge>
                        <Badge v-if="item.isWeakSpot" variant="destructive"
                            >Weak spot</Badge
                        >
                        <span class="text-xs text-muted-foreground">{{
                            item.state === 'new'
                                ? 'Not studied yet'
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
            {{
                props.filters.q === ''
                    ? 'Complete a unit to start your deck.'
                    : 'Nothing matches that search.'
            }}
        </p>

        <nav
            v-if="props.pagination.lastPage > 1"
            class="flex items-center justify-between gap-2"
            aria-label="Pages"
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
                    >Previous</Link
                >
            </Button>
            <Button v-else variant="outline" size="sm" disabled
                >Previous</Button
            >

            <span class="text-sm text-muted-foreground">
                Page {{ props.pagination.currentPage }} of
                {{ props.pagination.lastPage }}
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
                    >Next</Link
                >
            </Button>
            <Button v-else variant="outline" size="sm" disabled>Next</Button>
        </nav>
    </div>
</template>
