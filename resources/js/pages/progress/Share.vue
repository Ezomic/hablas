<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import ProgressSnapshotSummary from '@/components/ProgressSnapshotSummary.vue';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    progressCardFileName,
    progressCardText,
    renderProgressCard,
} from '@/lib/progressCard';
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
const image = ref<{ file: File; url: string; description: string } | null>(
    null,
);
const canShareImage = ref(false);
const imageError = ref<string | null>(null);

// Drawn on page load rather than on click: Safari only opens the share sheet
// while the tap that asked for it is still fresh.
onMounted(async () => {
    if (!props.snapshot) {
        return;
    }

    try {
        const blob = await renderProgressCard(props.snapshot);
        const file = new File([blob], progressCardFileName(props.snapshot), {
            type: 'image/png',
        });
        const text = progressCardText(props.snapshot);

        image.value = {
            file,
            url: URL.createObjectURL(file),
            description: `Hablas progress in ${text.language}. CEFR level: ${text.level}. Streak: ${text.streak}. Units completed: ${text.completion}.`,
        };
        canShareImage.value =
            typeof navigator.canShare === 'function' &&
            navigator.canShare({ files: [file] });
    } catch {
        imageError.value = "Couldn't create the image.";
    }
});

onBeforeUnmount(() => {
    if (image.value) {
        URL.revokeObjectURL(image.value.url);
    }
});

async function copyLink() {
    if (!props.shareUrl) {
        return;
    }

    await navigator.clipboard.writeText(props.shareUrl);
    copied.value = true;
}

async function shareImage() {
    if (!image.value) {
        return;
    }

    imageError.value = null;

    try {
        await navigator.share({ files: [image.value.file] });
    } catch (error) {
        if (!(error instanceof DOMException && error.name === 'AbortError')) {
            imageError.value = "Couldn't share the image.";
        }
    }
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

            <div class="flex flex-col gap-3">
                <Heading
                    variant="small"
                    title="Share an image"
                    description="A picture of your level, streak and units completed. Your name isn't on it."
                />
                <img
                    v-if="image"
                    :src="image.url"
                    :alt="image.description"
                    class="aspect-[1200/630] w-full max-w-md rounded-lg border"
                />
                <div v-if="image" class="flex flex-wrap gap-2">
                    <Button as-child variant="outline">
                        <a :href="image.url" :download="image.file.name"
                            >Download image</a
                        >
                    </Button>
                    <Button
                        v-if="canShareImage"
                        variant="outline"
                        @click="shareImage"
                        >Share image</Button
                    >
                </div>
                <p v-if="imageError" class="text-sm text-destructive">
                    {{ imageError }}
                </p>
            </div>

            <ProgressSnapshotSummary :snapshot="props.snapshot" />
        </template>
    </div>
</template>
