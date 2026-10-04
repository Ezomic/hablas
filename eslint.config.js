import intlify from '@intlify/eslint-plugin-vue-i18n';
import stylistic from '@stylistic/eslint-plugin';
import {
    defineConfigWithVueTs,
    vueTsConfigs,
} from '@vue/eslint-config-typescript';
import prettier from 'eslint-config-prettier/flat';
import importPlugin from 'eslint-plugin-import';
import vue from 'eslint-plugin-vue';

const controlStatements = [
    'if',
    'return',
    'for',
    'while',
    'do',
    'switch',
    'try',
    'throw',
];
const paddingAroundControl = [
    ...controlStatements.flatMap((stmt) => [
        { blankLine: 'always', prev: '*', next: stmt },
        { blankLine: 'always', prev: stmt, next: '*' },
    ]),
];

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        plugins: {
            import: importPlugin,
        },
        settings: {
            'import/resolver': {
                typescript: {
                    alwaysTryTypes: true,
                    project: './tsconfig.json',
                },
                node: true,
            },
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'off',
            '@typescript-eslint/consistent-type-imports': [
                'error',
                {
                    prefer: 'type-imports',
                    fixStyle: 'separate-type-imports',
                },
            ],
            'import/order': [
                'error',
                {
                    groups: [
                        'builtin',
                        'external',
                        'internal',
                        'parent',
                        'sibling',
                        'index',
                    ],
                    alphabetize: { order: 'asc', caseInsensitive: true },
                },
            ],
            'import/consistent-type-specifier-style': [
                'error',
                'prefer-top-level',
            ],
        },
    },
    {
        plugins: {
            '@stylistic': stylistic,
        },
        rules: {
            '@stylistic/brace-style': [
                'error',
                '1tbs',
                { allowSingleLine: false },
            ],
            '@stylistic/padding-line-between-statements': [
                'error',
                ...paddingAroundControl,
            ],
        },
    },
    {
        files: ['resources/js/**/*.{ts,vue}'],
        plugins: {
            '@intlify/vue-i18n': intlify,
        },
        settings: {
            'vue-i18n': {
                localeDir: './resources/js/lang/*.json',
                messageSyntaxVersion: '^11.0.0',
            },
        },
        rules: {
            '@intlify/vue-i18n/no-missing-keys': 'error',
            '@intlify/vue-i18n/no-missing-keys-in-other-locales': 'error',
            '@intlify/vue-i18n/no-deprecated-i18n-component': 'error',
        },
    },
    {
        files: [
            'resources/js/pages/Credits.vue',
            'resources/js/pages/Dashboard.vue',
            'resources/js/pages/Welcome.vue',
            'resources/js/pages/auth/**',
            'resources/js/pages/lessons/**',
            'resources/js/pages/listening/**',
            'resources/js/pages/placement/**',
            'resources/js/pages/pronunciation-drills/**',
            'resources/js/pages/progress/**',
            'resources/js/pages/reading/**',
            'resources/js/pages/reflections/**',
            'resources/js/pages/review/**',
            'resources/js/pages/scripted-prompts/**',
            'resources/js/pages/settings/**',
            'resources/js/pages/shadowing/**',
            'resources/js/pages/units/**',
            'resources/js/pages/vocabulary/**',
            'resources/js/pages/writing/**',
            'resources/js/layouts/**',
            'resources/js/components/lesson/**',
            'resources/js/components/AlertError.vue',
            'resources/js/components/AppDialogContent.vue',
            'resources/js/components/AppHeader.vue',
            'resources/js/components/AppLogo.vue',
            'resources/js/components/AppSheetContent.vue',
            'resources/js/components/AppSidebar.vue',
            'resources/js/components/AppSidebarRoot.vue',
            'resources/js/components/AppSidebarHeader.vue',
            'resources/js/components/AppSidebarTrigger.vue',
            'resources/js/components/AppSpinner.vue',
            'resources/js/components/AppToaster.vue',
            'resources/js/components/AppearanceTabs.vue',
            'resources/js/components/Breadcrumbs.vue',
            'resources/js/components/DeleteUser.vue',
            'resources/js/components/Heading.vue',
            'resources/js/components/InstallAppButton.vue',
            'resources/js/components/InterfaceLocaleMenuItems.vue',
            'resources/js/components/InterfaceLocaleSwitcher.vue',
            'resources/js/components/LanguageSwitcher.vue',
            'resources/js/components/ManagePasskeys.vue',
            'resources/js/components/ManageTwoFactor.vue',
            'resources/js/components/NavMain.vue',
            'resources/js/components/NavUser.vue',
            'resources/js/components/OfflineSyncBanner.vue',
            'resources/js/components/PasskeyItem.vue',
            'resources/js/components/PasskeyRegister.vue',
            'resources/js/components/PasskeyVerify.vue',
            'resources/js/components/PortalSwitcher.vue',
            'resources/js/components/ProgressSnapshotSummary.vue',
            'resources/js/components/RemediationActions.vue',
            'resources/js/components/RetakeSkillButton.vue',
            'resources/js/components/ReviewDeck.vue',
            'resources/js/components/ReviewForecast.vue',
            'resources/js/components/SpeakButton.vue',
            'resources/js/components/TwoFactorRecoveryCodes.vue',
            'resources/js/components/TwoFactorSetupModal.vue',
            'resources/js/components/UnitLessonList.vue',
            'resources/js/components/UnitReference.vue',
            'resources/js/components/UserInfo.vue',
            'resources/js/components/UserMenuContent.vue',
            'resources/js/composables/useBreadcrumbs.ts',
            'resources/js/composables/useExercisePauses.ts',
            'resources/js/composables/useInterfaceLocale.ts',
            'resources/js/composables/useLessonRun.ts',
            'resources/js/composables/useLayoutText.ts',
            'resources/js/composables/useTwoFactorAuth.ts',
            'resources/js/composables/useWebPush.ts',
            'resources/js/i18n.ts',
            'resources/js/lib/lessonPayload.ts',
        ],
        ignores: ['**/*.test.ts'],
        rules: {
            '@intlify/vue-i18n/no-raw-text': [
                'error',
                {
                    attributes: {
                        '/.+/': [
                            'title',
                            'aria-label',
                            'aria-placeholder',
                            'placeholder',
                            'alt',
                            'description',
                            'label',
                            'empty-message',
                            'aria-description',
                        ],
                    },
                    ignoreText: [
                        'Hablas',
                        'CEFR',
                        'FSI',
                        'email@example.com',
                        '123456',
                        '/',
                        '%',
                        '×',
                        '↵',
                    ],
                },
            ],
        },
    },
    {
        files: ['resources/js/**/*.{ts,vue}'],
        ignores: [
            'resources/js/components/AppDialogContent.vue',
            'resources/js/components/AppSheetContent.vue',
            'resources/js/components/AppSidebarRoot.vue',
            'resources/js/components/AppSidebarTrigger.vue',
            'resources/js/components/AppSpinner.vue',
            'resources/js/components/AppToaster.vue',
        ],
        rules: {
            'no-restricted-imports': [
                'error',
                {
                    paths: [
                        {
                            name: '@/components/ui/spinner',
                            message:
                                'Use AppSpinner so the label is translated.',
                        },
                        {
                            name: '@/components/ui/sonner',
                            message:
                                'Use AppToaster so the labels are translated.',
                        },
                        {
                            name: '@/components/ui/dialog',
                            importNames: [
                                'DialogContent',
                                'DialogScrollContent',
                            ],
                            message:
                                'Use AppDialogContent so the close label is translated.',
                        },
                        {
                            name: '@/components/ui/sheet',
                            importNames: ['SheetContent'],
                            message:
                                'Use AppSheetContent so the close label is translated.',
                        },
                        {
                            name: '@/components/ui/sidebar',
                            importNames: [
                                'Sidebar',
                                'SidebarRail',
                                'SidebarTrigger',
                            ],
                            message:
                                'Use AppSidebarRoot or AppSidebarTrigger so the labels are translated.',
                        },
                    ],
                },
            ],
        },
    },
    {
        ignores: [
            'vendor',
            'node_modules',
            'public',
            'bootstrap/ssr',
            'tailwind.config.js',
            'vite.config.ts',
            'vitest.config.ts',
            'resources/js/actions/**',
            'resources/js/components/ui/*',
            'resources/js/routes/**',
            'resources/js/wayfinder/**',
        ],
    },
    prettier,
    {
        plugins: {
            '@stylistic': stylistic,
        },
        rules: {
            curly: ['error', 'all'],
            '@stylistic/brace-style': [
                'error',
                '1tbs',
                { allowSingleLine: false },
            ],
        },
    },
);
