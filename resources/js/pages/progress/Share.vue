<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import ProgressSnapshotSummary from '@/components/ProgressSnapshotSummary.vue';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { regenerate } from '@/routes/progress/share';

const props = defineProps<{
    snapshot: ProgressSnapshot | null;
    shareUrl: string | null;
    languageId: number | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Share your progress', href: '/progress/share' },
        ],
    },
});

const copied = ref(false);

async function copyLink() {
    if (!props.shareUrl) {
        return;
    }

    await navigator.clipboard.writeText(props.shareUrl);
    copied.value = true;
}

function regenerateLink() {
    if (props.languageId === null) {
        return;
    }

    router.post(
        regenerate().url,
        { language_id: props.languageId },
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head title="Share your progress" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            variant="small"
            title="Share your progress"
            description="Anyone with this link can view a read-only snapshot of your progress — no login required."
        />

        <p v-if="!props.snapshot" class="text-muted-foreground">
            Complete placement first to build a shareable snapshot.
        </p>

        <template v-else>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <Input :model-value="props.shareUrl ?? ''" readonly />
                    <Button @click="copyLink">{{
                        copied ? 'Copied!' : 'Copy link'
                    }}</Button>
                </div>
                <Button variant="outline" class="w-fit" @click="regenerateLink"
                    >Regenerate link</Button
                >
                <p class="text-sm text-muted-foreground">
                    Regenerating replaces this link — the old one stops working.
                </p>
            </div>

            <ProgressSnapshotSummary :snapshot="props.snapshot" />
        </template>
    </div>
</template>
