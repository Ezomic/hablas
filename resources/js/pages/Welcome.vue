<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import HablasLogoIcon from '@/components/HablasLogoIcon.vue';
import InterfaceLocaleSwitcher from '@/components/InterfaceLocaleSwitcher.vue';
import { Button } from '@/components/ui/button';
import { dashboard, login, register } from '@/routes';

/**
 * Deliberately restrained copy. The planning docs' premise is that this app is
 * built on established SLA research "rather than gamified guesswork", and that
 * pacing is presented with real FSI hour estimates, "not app-marketing
 * numbers" — so this page makes the honest case rather than a hype one.
 *
 * It also only claims what is actually built: Spanish first, with Portuguese
 * introduced later via the staggered-parallel model. The AI conversation
 * partner and free-form writing grading are phase 2 and are deliberately absent.
 */
const pillars = ['cefr', 'input', 'spaced', 'trap'];

// The FSI Category I estimates from the pedagogical plan, at a sustainable
// ~6 hrs/week. Shown because honesty about the timeline is the point.
const pacing = [
    { level: 'A1', key: 'a1' },
    { level: 'A2', key: 'a2' },
    { level: 'B1', key: 'b1' },
    { level: 'B2', key: 'b2' },
];
</script>

<template>
    <Head :title="$t('welcome.head.title')">
        <meta name="description" :content="$t('welcome.head.description')" />
    </Head>

    <div class="min-h-screen bg-background text-foreground">
        <header
            class="mx-auto flex w-full max-w-5xl items-center justify-between px-6 py-6"
        >
            <div class="flex items-center gap-2">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md bg-primary text-primary-foreground"
                >
                    <HablasLogoIcon class="size-5" />
                </div>
                <span class="text-base font-semibold tracking-tight"
                    >Hablas</span
                >
            </div>

            <nav class="flex items-center gap-2">
                <InterfaceLocaleSwitcher />
                <Button
                    v-if="$page.props.auth.user"
                    as-child
                    variant="outline"
                    size="sm"
                >
                    <Link :href="dashboard()">{{ $t('nav.dashboard') }}</Link>
                </Button>
                <template v-else>
                    <Button as-child variant="ghost" size="sm">
                        <Link :href="login()">{{ $t('common.logIn') }}</Link>
                    </Button>
                    <Button as-child size="sm">
                        <Link :href="register()">{{
                            $t('common.createAccount')
                        }}</Link>
                    </Button>
                </template>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-5xl px-6">
            <section class="border-b border-border py-16 sm:py-24">
                <p
                    class="mb-4 text-sm font-medium tracking-wide text-muted-foreground uppercase"
                >
                    {{ $t('welcome.hero.eyebrow') }}
                </p>
                <h1
                    class="max-w-3xl text-4xl font-semibold tracking-tight text-balance sm:text-5xl"
                >
                    {{ $t('welcome.hero.title') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg text-muted-foreground">
                    {{ $t('welcome.hero.body') }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <Button v-if="$page.props.auth.user" as-child size="lg">
                        <Link :href="dashboard()">{{
                            $t('welcome.hero.goToDashboard')
                        }}</Link>
                    </Button>
                    <template v-else>
                        <Button as-child size="lg">
                            <Link :href="register()">{{
                                $t('welcome.hero.startPlacement')
                            }}</Link>
                        </Button>
                        <Button as-child variant="ghost" size="lg">
                            <Link :href="login()">{{
                                $t('welcome.hero.haveAccount')
                            }}</Link>
                        </Button>
                    </template>
                </div>

                <p class="mt-4 text-sm text-muted-foreground">
                    {{ $t('welcome.hero.passwordless') }}
                </p>
            </section>

            <section class="border-b border-border py-16">
                <h2 class="text-2xl font-semibold tracking-tight">
                    {{ $t('welcome.builtOn.title') }}
                </h2>
                <div class="mt-8 grid gap-x-10 gap-y-8 sm:grid-cols-2">
                    <div v-for="pillar in pillars" :key="pillar">
                        <h3 class="font-medium">
                            {{ $t(`welcome.builtOn.pillars.${pillar}.title`) }}
                        </h3>
                        <p
                            class="mt-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ $t(`welcome.builtOn.pillars.${pillar}.body`) }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="border-b border-border py-16">
                <h2 class="text-2xl font-semibold tracking-tight">
                    {{ $t('welcome.pacing.title') }}
                </h2>
                <p class="mt-3 max-w-2xl text-sm text-muted-foreground">
                    {{ $t('welcome.pacing.intro') }}
                </p>

                <div class="mt-8 overflow-x-auto">
                    <table
                        class="w-full min-w-md border-collapse text-left text-sm"
                    >
                        <thead>
                            <tr
                                class="border-b border-border text-muted-foreground"
                            >
                                <th class="py-2 pr-4 font-medium">
                                    {{ $t('welcome.pacing.levelColumn') }}
                                </th>
                                <th class="py-2 pr-4 font-medium">
                                    {{ $t('welcome.pacing.studyColumn') }}
                                </th>
                                <th class="py-2 font-medium">
                                    {{ $t('welcome.pacing.paceColumn') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="row in pacing"
                                :key="row.level"
                                class="border-b border-border/60"
                            >
                                <td class="py-3 pr-4">
                                    <span class="font-medium">{{
                                        row.level
                                    }}</span>
                                    <span class="ml-2 text-muted-foreground">{{
                                        $t(
                                            `welcome.pacing.levels.${row.key}.blurb`,
                                        )
                                    }}</span>
                                </td>
                                <td class="py-3 pr-4 text-muted-foreground">
                                    {{
                                        $t(
                                            `welcome.pacing.levels.${row.key}.hours`,
                                        )
                                    }}
                                </td>
                                <td class="py-3 text-muted-foreground">
                                    {{
                                        $t(
                                            `welcome.pacing.levels.${row.key}.time`,
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="py-16">
                <h2 class="text-2xl font-semibold tracking-tight text-balance">
                    {{ $t('welcome.cta.title') }}
                </h2>
                <p class="mt-3 max-w-2xl text-muted-foreground">
                    {{ $t('welcome.cta.body') }}
                </p>
                <div class="mt-8">
                    <Button v-if="$page.props.auth.user" as-child size="lg">
                        <Link :href="dashboard()">{{
                            $t('welcome.hero.goToDashboard')
                        }}</Link>
                    </Button>
                    <Button v-else as-child size="lg">
                        <Link :href="register()">{{
                            $t('welcome.cta.createYourAccount')
                        }}</Link>
                    </Button>
                </div>
            </section>
        </main>

        <footer class="border-t border-border">
            <div
                class="mx-auto flex w-full max-w-5xl flex-col gap-2 px-6 py-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between"
            >
                <span>Hablas</span>
                <i18n-t keypath="welcome.footer.builtOn" tag="span">
                    <template #cefr>
                        <a
                            href="https://www.coe.int/en/web/common-european-framework-reference-languages/level-descriptions"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="underline underline-offset-4 hover:text-foreground"
                            >CEFR</a
                        >
                    </template>
                    <template #fsi>
                        <a
                            href="https://www.fsi-language-courses.org/blog/fsi-language-difficulty/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="underline underline-offset-4 hover:text-foreground"
                            >FSI</a
                        >
                    </template>
                </i18n-t>
            </div>
        </footer>
    </div>
</template>
