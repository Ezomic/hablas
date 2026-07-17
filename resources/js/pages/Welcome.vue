<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import HablasLogoIcon from '@/components/HablasLogoIcon.vue';
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
const pillars = [
    {
        title: 'CEFR, tracked per skill',
        body: 'Reading, listening, speaking and writing are scored separately against the Council of Europe’s six levels — so a lagging skill shows up instead of hiding behind an average.',
    },
    {
        title: 'Input first, then real tasks',
        body: 'Lessons are built around things you actually do — book a room, describe your weekend — with grammar introduced as the tool the task needs, not as a chapter to endure.',
    },
    {
        title: 'Spaced repetition that adapts',
        body: 'Vocabulary and grammar enter an FSRS queue that models your own forgetting curve. Miss something repeatedly and it escalates into a short remedial drill instead of cycling forever.',
    },
    {
        title: 'Built for the Spanish–Portuguese trap',
        body: 'Add Portuguese once Spanish is solid, and every lesson names where it diverges. Separate decks that never interleave, because Portuñol is the predictable failure of learning both at once.',
    },
];

// The FSI Category I estimates from the pedagogical plan, at a sustainable
// ~6 hrs/week. Shown because honesty about the timeline is the point.
const pacing = [
    {
        level: 'A1',
        blurb: 'Breakthrough',
        hours: '60–90 hrs',
        time: '~3 months',
    },
    { level: 'A2', blurb: 'Waystage', hours: '150–200 hrs', time: '~7 months' },
    {
        level: 'B1',
        blurb: 'Threshold',
        hours: '300–350 hrs',
        time: '~13 months',
    },
    {
        level: 'B2',
        blurb: 'Vantage',
        hours: '500–600 hrs',
        time: '~20–24 months',
    },
];
</script>

<template>
    <Head title="Learn Spanish, honestly">
        <meta
            name="description"
            content="A Spanish and Portuguese learning app built on CEFR levels, comprehensible input and spaced repetition — with honest timelines instead of streak-chasing."
        />
    </Head>

    <div class="min-h-screen bg-background text-foreground">
        <header
            class="mx-auto flex w-full max-w-5xl items-center justify-between px-6 py-6"
        >
            <div class="flex items-center gap-2">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md bg-foreground text-background"
                >
                    <HablasLogoIcon class="size-5" />
                </div>
                <span class="text-base font-semibold tracking-tight"
                    >Hablas</span
                >
            </div>

            <nav class="flex items-center gap-2">
                <Button
                    v-if="$page.props.auth.user"
                    as-child
                    variant="outline"
                    size="sm"
                >
                    <Link :href="dashboard()">Dashboard</Link>
                </Button>
                <template v-else>
                    <Button as-child variant="ghost" size="sm">
                        <Link :href="login()">Log in</Link>
                    </Button>
                    <Button as-child size="sm">
                        <Link :href="register()">Create account</Link>
                    </Button>
                </template>
            </nav>
        </header>

        <main class="mx-auto w-full max-w-5xl px-6">
            <section class="border-b border-border py-16 sm:py-24">
                <p
                    class="mb-4 text-sm font-medium tracking-wide text-muted-foreground uppercase"
                >
                    Spanish now · Portuguese when you’re ready
                </p>
                <h1
                    class="max-w-3xl text-4xl font-semibold tracking-tight text-balance sm:text-5xl"
                >
                    Learn Spanish the way the research says you actually will.
                </h1>
                <p class="mt-6 max-w-2xl text-lg text-muted-foreground">
                    Hablas is built on the CEFR framework, comprehensible input
                    and spaced repetition — what language-teaching institutions
                    actually use. No points, no leaderboards, no streak guilt.
                    Just an honest read on how good you’re getting.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <Button v-if="$page.props.auth.user" as-child size="lg">
                        <Link :href="dashboard()">Go to your dashboard</Link>
                    </Button>
                    <template v-else>
                        <Button as-child size="lg">
                            <Link :href="register()"
                                >Start with a placement test</Link
                            >
                        </Button>
                        <Button as-child variant="ghost" size="lg">
                            <Link :href="login()"
                                >I already have an account</Link
                            >
                        </Button>
                    </template>
                </div>

                <p class="mt-4 text-sm text-muted-foreground">
                    No password to remember — sign in with an emailed code or a
                    passkey.
                </p>
            </section>

            <section class="border-b border-border py-16">
                <h2 class="text-2xl font-semibold tracking-tight">
                    What it’s built on
                </h2>
                <div class="mt-8 grid gap-x-10 gap-y-8 sm:grid-cols-2">
                    <div v-for="pillar in pillars" :key="pillar.title">
                        <h3 class="font-medium">{{ pillar.title }}</h3>
                        <p
                            class="mt-2 text-sm leading-relaxed text-muted-foreground"
                        >
                            {{ pillar.body }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="border-b border-border py-16">
                <h2 class="text-2xl font-semibold tracking-tight">
                    How long this actually takes
                </h2>
                <p class="mt-3 max-w-2xl text-sm text-muted-foreground">
                    These are the U.S. Foreign Service Institute’s own estimates
                    for Spanish, at a sustainable six hours a week. Not
                    marketing numbers — you should know what you’re signing up
                    for.
                </p>

                <div class="mt-8 overflow-x-auto">
                    <table
                        class="w-full min-w-md border-collapse text-left text-sm"
                    >
                        <thead>
                            <tr
                                class="border-b border-border text-muted-foreground"
                            >
                                <th class="py-2 pr-4 font-medium">Level</th>
                                <th class="py-2 pr-4 font-medium">
                                    Cumulative study
                                </th>
                                <th class="py-2 font-medium">At ~6 hrs/week</th>
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
                                        row.blurb
                                    }}</span>
                                </td>
                                <td class="py-3 pr-4 text-muted-foreground">
                                    {{ row.hours }}
                                </td>
                                <td class="py-3 text-muted-foreground">
                                    {{ row.time }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="py-16">
                <h2 class="text-2xl font-semibold tracking-tight text-balance">
                    Find out where you actually stand.
                </h2>
                <p class="mt-3 max-w-2xl text-muted-foreground">
                    A placement test scores each skill separately and sets your
                    starting level. Everything after that is paced off what you
                    remember, not what you clicked.
                </p>
                <div class="mt-8">
                    <Button v-if="$page.props.auth.user" as-child size="lg">
                        <Link :href="dashboard()">Go to your dashboard</Link>
                    </Button>
                    <Button v-else as-child size="lg">
                        <Link :href="register()">Create your account</Link>
                    </Button>
                </div>
            </section>
        </main>

        <footer class="border-t border-border">
            <div
                class="mx-auto flex w-full max-w-5xl flex-col gap-2 px-6 py-8 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between"
            >
                <span>Hablas</span>
                <span>
                    Built on the
                    <a
                        href="https://www.coe.int/en/web/common-european-framework-reference-languages/level-descriptions"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline underline-offset-4 hover:text-foreground"
                        >CEFR</a
                    >
                    and
                    <a
                        href="https://www.fsi-language-courses.org/blog/fsi-language-difficulty/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline underline-offset-4 hover:text-foreground"
                        >FSI</a
                    >
                    pacing data.
                </span>
            </div>
        </footer>
    </div>
</template>
