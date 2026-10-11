<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';
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
import { Textarea } from '@/components/ui/textarea';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
import { index, store } from '@/routes/dutch-review';
import type {
    DutchReviewItem,
    DutchReviewLanguage,
    DutchReviewUnit,
} from '@/types/dutchReview';

const props = defineProps<{
    languages: DutchReviewLanguage[];
    language: string | null;
    units: DutchReviewUnit[];
    unit: string | null;
    previous: string | null;
    next: string | null;
    items: DutchReviewItem[];
    suggestionCount: number;
}>();

const { t } = useI18n();

useBreadcrumbs(() => [{ title: t('dutchReview.title'), href: index() }]);

const editing = ref<number | null>(null);
const draft = reactive({ suggestion: '', note: '' });
const saving = ref(false);

function go(language: string | null, unit: string | null) {
    editing.value = null;
    router.get(
        index({
            query: {
                language: language ?? undefined,
                unit: unit ?? undefined,
            },
        }).url,
        {},
        { preserveScroll: false, replace: true },
    );
}

function open(item: DutchReviewItem) {
    editing.value = item.id;
    draft.suggestion = item.suggestion ?? '';
    draft.note = item.note ?? '';
}

function save(item: DutchReviewItem) {
    saving.value = true;
    router.post(
        store(item.id).url,
        { suggestion: draft.suggestion, note: draft.note },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                editing.value = null;
            },
            onFinish: () => {
                saving.value = false;
            },
        },
    );
}
</script>

<template>
    <Head :title="t('dutchReview.title')" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-5 p-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ t('dutchReview.title') }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ t('dutchReview.intro') }}
            </p>
            <p
                v-if="props.suggestionCount > 0"
                class="mt-2 text-sm"
                data-test="suggestion-count"
            >
                {{ t('dutchReview.count', props.suggestionCount) }}
            </p>
        </div>

        <div
            v-if="props.languages.length > 0"
            class="flex flex-wrap gap-2"
            role="group"
            :aria-label="t('dutchReview.language')"
        >
            <Button
                v-for="language in props.languages"
                :key="language.code"
                size="sm"
                :variant="
                    language.code === props.language ? 'default' : 'outline'
                "
                :aria-pressed="language.code === props.language"
                :data-test="`language-${language.code}`"
                @click="go(language.code, null)"
            >
                {{ language.name }}
            </Button>
        </div>

        <div v-if="props.units.length > 0" class="flex items-center gap-2">
            <Button
                variant="outline"
                size="icon"
                :disabled="props.previous === null"
                :aria-label="t('dutchReview.previous')"
                data-test="previous-unit"
                @click="go(props.language, props.previous)"
            >
                <ChevronLeft class="size-4" />
            </Button>
            <Select
                :model-value="props.unit ?? undefined"
                @update:model-value="
                    (value) => go(props.language, String(value))
                "
            >
                <SelectTrigger
                    class="min-w-0 flex-1"
                    :aria-label="t('dutchReview.unit')"
                >
                    <SelectValue />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="unit in props.units"
                        :key="unit.slug"
                        :value="unit.slug"
                    >
                        {{ unit.level }} · {{ unit.title
                        }}<template v-if="unit.suggestions > 0">
                            ({{ unit.suggestions }})</template
                        >
                    </SelectItem>
                </SelectContent>
            </Select>
            <Button
                variant="outline"
                size="icon"
                :disabled="props.next === null"
                :aria-label="t('dutchReview.next')"
                data-test="next-unit"
                @click="go(props.language, props.next)"
            >
                <ChevronRight class="size-4" />
            </Button>
        </div>

        <p
            v-if="props.items.length === 0"
            class="text-sm text-muted-foreground"
        >
            {{ t('dutchReview.empty') }}
        </p>

        <ul v-else class="divide-y rounded-xl border">
            <li
                v-for="item in props.items"
                :key="item.id"
                class="flex flex-col gap-2 p-4"
                :data-test="`item-${item.id}`"
            >
                <div class="flex flex-wrap items-baseline gap-x-2">
                    <span class="font-medium break-words">{{ item.term }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        item.english
                    }}</span>
                </div>
                <p
                    class="text-lg break-words"
                    lang="nl"
                    :class="
                        item.dutch === null
                            ? 'text-muted-foreground italic'
                            : ''
                    "
                >
                    {{ item.dutch ?? t('dutchReview.missing') }}
                </p>

                <template v-if="editing === item.id">
                    <form
                        class="flex flex-col gap-2"
                        @submit.prevent="save(item)"
                    >
                        <Input
                            v-model="draft.suggestion"
                            lang="nl"
                            maxlength="255"
                            :placeholder="
                                t('dutchReview.suggestionPlaceholder')
                            "
                            :aria-label="t('dutchReview.suggestionLabel')"
                            data-test="suggestion-input"
                        />
                        <Textarea
                            v-model="draft.note"
                            maxlength="500"
                            rows="2"
                            :placeholder="t('dutchReview.notePlaceholder')"
                            :aria-label="t('dutchReview.noteLabel')"
                            data-test="note-input"
                        />
                        <div class="flex gap-2">
                            <Button
                                type="submit"
                                size="sm"
                                :disabled="saving"
                                data-test="save-suggestion"
                            >
                                {{ t('dutchReview.save') }}
                            </Button>
                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                @click="editing = null"
                            >
                                {{ t('dutchReview.cancel') }}
                            </Button>
                        </div>
                    </form>
                </template>
                <template v-else>
                    <div
                        v-if="item.suggestion !== null || item.note !== null"
                        class="flex flex-col gap-1 rounded-lg bg-muted p-3 text-sm"
                    >
                        <Badge variant="secondary" class="w-fit">{{
                            t('dutchReview.yourSuggestion')
                        }}</Badge>
                        <span v-if="item.suggestion !== null" lang="nl">{{
                            item.suggestion
                        }}</span>
                        <span
                            v-if="item.note !== null"
                            class="text-muted-foreground"
                            >{{ item.note }}</span
                        >
                    </div>
                    <Button
                        variant="outline"
                        size="sm"
                        class="w-fit"
                        data-test="suggest"
                        @click="open(item)"
                    >
                        {{
                            item.suggestion !== null || item.note !== null
                                ? t('dutchReview.edit')
                                : t('dutchReview.suggest')
                        }}
                    </Button>
                </template>
            </li>
        </ul>
    </div>
</template>
