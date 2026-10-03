<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import HablasLogoIcon from '@/components/HablasLogoIcon.vue';
import { home } from '@/routes';

type Credit = {
    key: string;
    engine: string;
    license: string;
    licenseUrl: string;
    attribution: string;
    sourceUrl: string;
    restrictions: Record<string, string>;
    voices: Record<string, string[]>;
};

defineProps<{ credits: Credit[] }>();

const voiceList = (voices: Record<string, string[]>): string =>
    Object.entries(voices)
        .map(([language, names]) => `${language}: ${names.join(', ')}`)
        .join(' · ');
</script>

<template>
    <Head :title="$t('credits.title')" />

    <div class="min-h-screen bg-background text-foreground">
        <header
            class="mx-auto flex w-full max-w-3xl items-center justify-between px-6 py-6"
        >
            <Link :href="home()" class="flex items-center gap-2">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md bg-primary text-primary-foreground"
                >
                    <HablasLogoIcon class="size-5" />
                </div>
                <span class="text-base font-semibold tracking-tight"
                    >Hablas</span
                >
            </Link>
        </header>

        <main class="mx-auto w-full max-w-3xl px-6 pb-16">
            <h1 class="text-3xl font-semibold tracking-tight">
                {{ $t('credits.title') }}
            </h1>
            <p class="mt-4 text-muted-foreground">{{ $t('credits.intro') }}</p>

            <section
                v-for="credit in credits"
                :key="credit.key"
                class="mt-10 border-t border-border pt-8"
                data-test="credit"
            >
                <h2 class="text-xl font-semibold tracking-tight">
                    {{ credit.attribution }}
                </h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            {{ $t('credits.engine') }}
                        </dt>
                        <dd>{{ credit.engine }}</dd>
                    </div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            {{ $t('credits.voices') }}
                        </dt>
                        <dd>{{ voiceList(credit.voices) }}</dd>
                    </div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            {{ $t('credits.licence') }}
                        </dt>
                        <dd>
                            <a
                                :href="credit.licenseUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="underline underline-offset-4 hover:text-foreground"
                                >{{ credit.license }}</a
                            >
                        </dd>
                    </div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
                        <dt class="w-28 shrink-0 text-muted-foreground">
                            {{ $t('credits.source') }}
                        </dt>
                        <dd>
                            <a
                                :href="credit.sourceUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="underline underline-offset-4 hover:text-foreground"
                                >{{ credit.sourceUrl }}</a
                            >
                        </dd>
                    </div>
                </dl>

                <template v-if="Object.keys(credit.restrictions).length > 0">
                    <h3 class="mt-6 font-medium">
                        {{ $t('credits.restrictionsHeading') }}
                    </h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ $t('credits.restrictionsIntro') }}
                    </p>
                    <ul
                        class="mt-3 list-disc space-y-1 pl-5 text-sm text-muted-foreground"
                    >
                        <li v-for="(_, id) in credit.restrictions" :key="id">
                            {{ $t(`credits.restrictions.${id}`) }}
                        </li>
                    </ul>
                </template>
            </section>
        </main>
    </div>
</template>
