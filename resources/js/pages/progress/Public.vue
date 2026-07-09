<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProgressSnapshotSummary from '@/components/ProgressSnapshotSummary.vue';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';

defineProps<{
    snapshot: ProgressSnapshot;
    ownerName: string;
}>();
</script>

<template>
    <Head :title="`${ownerName}'s progress`" />

    <div class="mx-auto flex max-w-lg flex-col gap-6 p-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ ownerName }}'s progress</h1>
        </div>

        <ProgressSnapshotSummary :snapshot="snapshot" />

        <p class="text-center text-sm text-muted-foreground">
            Shared from Hablas
        </p>
    </div>
</template>
