<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Download } from '@lucide/vue';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

// Temporary: listening page for comparing TTS voices, removed in HAB-109 PR 1.
type TtsSample = {
    language: string;
    engine: string;
    voice: string;
    speed: string;
    kind: string;
    text: string;
    file: string;
    duration: number;
    bytes: number;
    licence: string;
};

type VoiceRow = {
    key: string;
    label: string;
    licence: string;
    normal: TtsSample | null;
    slow: TtsSample | null;
};

type TextGroup = {
    key: string;
    kind: string;
    text: string;
    voices: VoiceRow[];
};

type LanguageGroup = { code: string; name: string; texts: TextGroup[] };

const props = defineProps<{ samples: TtsSample[] }>();

const languageNames: Record<string, string> = {
    es: 'Spanish (es-ES)',
    pt: 'Portuguese (pt-PT)',
    fr: 'French',
    it: 'Italian',
    nl: 'Dutch',
};

const groups = computed<LanguageGroup[]>(() =>
    Object.keys(languageNames).map((code) => {
        const texts = new Map<string, TextGroup>();

        for (const sample of props.samples.filter((s) => s.language === code)) {
            const textKey = `${sample.kind}:${sample.text}`;
            const group = texts.get(textKey) ?? {
                key: textKey,
                kind: sample.kind,
                text: sample.text,
                voices: [],
            };
            const voiceKey = `${sample.engine}:${sample.voice}`;
            let row = group.voices.find((v) => v.key === voiceKey);

            if (!row) {
                row = {
                    key: voiceKey,
                    label: `${sample.engine} ${sample.voice}`,
                    licence: sample.licence,
                    normal: null,
                    slow: null,
                };
                group.voices.push(row);
            }

            if (sample.speed === 'slow') {
                row.slow = sample;
            } else {
                row.normal = sample;
            }

            texts.set(textKey, group);
        }

        return {
            code,
            name: languageNames[code],
            texts: [...texts.values()],
        };
    }),
);

const src = (sample: TtsSample) => `/tts-preview/${sample.file}`;
</script>

<template>
    <Head title="TTS preview" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            title="TTS preview"
            description="Temporary page for comparing text-to-speech voices. Each voice has a normal and a slow take. Removed in HAB-109 PR 1."
        />

        <nav class="flex flex-wrap gap-2">
            <a
                v-for="group in groups"
                :key="group.code"
                :href="`#${group.code}`"
            >
                <Badge variant="outline">{{ group.name }}</Badge>
            </a>
        </nav>

        <section
            v-for="group in groups"
            :id="group.code"
            :key="group.code"
            class="flex scroll-mt-4 flex-col gap-4"
        >
            <h2 class="text-lg font-semibold text-primary">{{ group.name }}</h2>

            <Card v-for="text in group.texts" :key="text.key">
                <CardHeader>
                    <CardTitle class="text-base">{{ text.text }}</CardTitle>
                    <CardDescription>{{ text.kind }}</CardDescription>
                </CardHeader>
                <CardContent class="flex flex-col gap-4">
                    <div
                        v-for="voice in text.voices"
                        :key="voice.key"
                        class="flex min-w-0 flex-col gap-1"
                    >
                        <p class="text-sm font-medium" :title="voice.licence">
                            {{ voice.label }}
                        </p>
                        <div class="grid grid-cols-2 gap-2 [&>*]:min-w-0">
                            <div
                                v-if="voice.normal"
                                class="flex min-w-0 flex-col gap-1"
                            >
                                <span class="text-xs text-muted-foreground"
                                    >normal</span
                                >
                                <audio
                                    controls
                                    preload="none"
                                    class="w-full"
                                    :src="src(voice.normal)"
                                />
                                <Button
                                    as="a"
                                    variant="outline"
                                    size="sm"
                                    :href="src(voice.normal)"
                                    :download="voice.normal.file"
                                    :aria-label="`Download ${voice.label} normal`"
                                >
                                    <Download />
                                    Download
                                </Button>
                            </div>
                            <div
                                v-if="voice.slow"
                                class="flex min-w-0 flex-col gap-1"
                            >
                                <span class="text-xs text-muted-foreground"
                                    >slow</span
                                >
                                <audio
                                    controls
                                    preload="none"
                                    class="w-full"
                                    :src="src(voice.slow)"
                                />
                                <Button
                                    as="a"
                                    variant="outline"
                                    size="sm"
                                    :href="src(voice.slow)"
                                    :download="voice.slow.file"
                                    :aria-label="`Download ${voice.label} slow`"
                                >
                                    <Download />
                                    Download
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </section>
    </div>
</template>
