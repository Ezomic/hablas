<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ProgressSnapshotSummary from '@/components/ProgressSnapshotSummary.vue';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';

defineProps<{
    snapshot: ProgressSnapshot;
    ownerName: string;
}>();

const { t } = useI18n();
</script>

<template>
    <Head :title="t('progress.public.title', { name: ownerName })" />

    <div class="mx-auto flex max-w-lg flex-col gap-6 p-6">
        <div>
            <h1 class="text-2xl font-semibold">
                {{ t('progress.public.title', { name: ownerName }) }}
            </h1>
        </div>

        <ProgressSnapshotSummary :snapshot="snapshot" />

        <p class="text-center text-sm text-muted-foreground">
            {{ t('progress.public.sharedFrom') }}
        </p>
    </div>
</template>
