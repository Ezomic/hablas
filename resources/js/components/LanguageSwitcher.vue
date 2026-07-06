<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { update } from '@/routes/language';

const page = usePage();

function switchLanguage(languageId: string) {
    router.patch(
        update().url,
        { language_id: Number(languageId) },
        { preserveScroll: true },
    );
}
</script>

<template>
    <Select
        v-if="page.props.availableLanguages.length > 1"
        :model-value="String(page.props.currentLanguage?.id ?? '')"
        @update:model-value="(value) => switchLanguage(String(value))"
    >
        <SelectTrigger class="w-36">
            <SelectValue />
        </SelectTrigger>
        <SelectContent>
            <SelectItem
                v-for="language in page.props.availableLanguages"
                :key="language.id"
                :value="String(language.id)"
            >
                {{ language.name }}
            </SelectItem>
        </SelectContent>
    </Select>
</template>
