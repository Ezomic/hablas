<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import Heading from '@/components/Heading.vue';
import ProgressSnapshotSummary from '@/components/ProgressSnapshotSummary.vue';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';
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

const { t } = useI18n();

useBreadcrumbs(() => [
    { title: t('progress.share.title'), href: '/progress/share' },
]);

const copied = ref(false);
const image = ref<{
    file: File;
    url: string;
    text: ReturnType<typeof progressCardText>;
} | null>(null);
const canShareImage = ref(false);
const imageError = ref<'create' | 'share' | null>(null);

const imageDescription = computed(() =>
    image.value
        ? t('progress.share.imageDescription', {
              language: image.value.text.language,
              level: image.value.text.level,
              streak: image.value.text.streak,
              completion: image.value.text.completion,
          })
        : '',
);

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
            text,
        };
        canShareImage.value =
            typeof navigator.canShare === 'function' &&
            navigator.canShare({ files: [file] });
    } catch {
        imageError.value = 'create';
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
            imageError.value = 'share';
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
    <Head :title="t('progress.share.title')" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            variant="small"
            :title="t('progress.share.title')"
            :description="t('progress.share.description')"
        />

        <p v-if="!props.snapshot" class="text-muted-foreground">
            {{ t('progress.share.needPlacement') }}
        </p>

        <template v-else>
            <div class="flex flex-col gap-2">
                <div class="flex gap-2">
                    <Input :model-value="props.shareUrl ?? ''" readonly />
                    <Button @click="copyLink">{{
                        copied
                            ? t('progress.share.copied')
                            : t('progress.share.copy')
                    }}</Button>
                </div>
                <Button
                    variant="outline"
                    class="w-fit"
                    @click="regenerateLink"
                    >{{ t('progress.share.regenerate') }}</Button
                >
                <p class="text-sm text-muted-foreground">
                    {{ t('progress.share.regenerateNote') }}
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <Heading
                    variant="small"
                    :title="t('progress.share.imageTitle')"
                    :description="t('progress.share.imageNote')"
                />
                <img
                    v-if="image"
                    :src="image.url"
                    :alt="imageDescription"
                    class="aspect-[1200/630] w-full max-w-md rounded-lg border"
                />
                <div v-if="image" class="flex flex-wrap gap-2">
                    <Button as-child variant="outline">
                        <a :href="image.url" :download="image.file.name">{{
                            t('progress.share.download')
                        }}</a>
                    </Button>
                    <Button
                        v-if="canShareImage"
                        variant="outline"
                        @click="shareImage"
                        >{{ t('progress.share.shareImage') }}</Button
                    >
                </div>
                <p v-if="imageError" class="text-sm text-destructive">
                    {{
                        imageError === 'create'
                            ? t('progress.share.createFailed')
                            : t('progress.share.shareFailed')
                    }}
                </p>
            </div>

            <ProgressSnapshotSummary :snapshot="props.snapshot" />
        </template>
    </div>
</template>
