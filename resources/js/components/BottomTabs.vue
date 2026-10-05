<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Dumbbell, GraduationCap, Layers, UserRound } from '@lucide/vue';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { continueMethod, dashboard, practice } from '@/routes';
import { index as reviewIndex } from '@/routes/review';

const { t } = useI18n();
const page = usePage();

const dueCount = computed(() => Number(page.props.dueReviewCount ?? 0));

const tabs = computed(() => [
    {
        key: 'learn',
        title: t('nav.learn'),
        href: continueMethod(),
        icon: GraduationCap,
        prefixes: ['/continue', '/units', '/lesson-runs', '/placement'],
        badge: 0,
    },
    {
        key: 'review',
        title: t('nav.review'),
        href: reviewIndex(),
        icon: Layers,
        prefixes: ['/review'],
        badge: dueCount.value,
    },
    {
        key: 'practise',
        title: t('nav.practise'),
        href: practice(),
        icon: Dumbbell,
        prefixes: [
            '/practice',
            '/reading',
            '/listening',
            '/shadowing',
            '/writing',
            '/scripted-prompts',
            '/pronunciation-drills',
            '/reflections',
            '/vocabulary',
        ],
        badge: 0,
    },
    {
        key: 'me',
        title: t('nav.me'),
        href: dashboard(),
        icon: UserRound,
        prefixes: ['/dashboard', '/settings', '/progress'],
        badge: 0,
    },
]);

function isActive(prefixes: string[]): boolean {
    const path = page.url.split('?')[0];

    return prefixes.some(
        (prefix) => path === prefix || path.startsWith(prefix + '/'),
    );
}
</script>

<template>
    <nav
        class="fixed inset-x-0 bottom-0 z-40 border-t bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/80"
        :aria-label="t('nav.navigationMenu')"
        data-testid="bottom-tabs"
    >
        <ul
            class="mx-auto grid max-w-2xl grid-cols-4 pb-[env(safe-area-inset-bottom)]"
        >
            <li v-for="tab in tabs" :key="tab.key">
                <Link
                    :href="tab.href"
                    class="relative flex h-16 flex-col items-center justify-center gap-1 text-xs font-medium transition-colors"
                    :class="
                        isActive(tab.prefixes)
                            ? 'text-primary'
                            : 'text-muted-foreground'
                    "
                    :aria-current="isActive(tab.prefixes) ? 'page' : undefined"
                    :data-testid="`tab-${tab.key}`"
                >
                    <span class="relative">
                        <component :is="tab.icon" class="size-6" />
                        <span
                            v-if="tab.badge > 0"
                            class="absolute -top-1.5 -right-2.5 flex min-w-4 items-center justify-center rounded-full bg-primary px-1 text-[10px] leading-4 font-semibold text-primary-foreground"
                            data-testid="tab-badge"
                        >
                            {{ tab.badge > 99 ? '99+' : tab.badge }}
                        </span>
                    </span>
                    {{ tab.title }}
                </Link>
            </li>
        </ul>
    </nav>
</template>
