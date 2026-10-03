import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import { initializeInstallPrompt } from '@/composables/useInstallPrompt';
import { initializeOfflineSync } from '@/composables/useOfflineSync';
import { i18n, setLocale } from '@/i18n';
import type { InterfaceLocale } from '@/i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import LessonLayout from '@/layouts/LessonLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import { initializeLocaleSync } from '@/lib/localeSync';
import { initializeServiceWorker } from '@/lib/registerServiceWorker';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name === 'progress/Public':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('lessons/'):
                return LessonLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#be185d',
    },
    setup({ el, App, props, plugin }) {
        const initialLocale = props.initialPage.props.interfaceLocale as
            InterfaceLocale | undefined;

        if (initialLocale) {
            setLocale(initialLocale);
        }

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n);

        if (el) {
            app.mount(el);
        }
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will keep the Vue locale in step with the locale the server resolved...
initializeLocaleSync();

// This will listen for flash toast data from the server...
initializeFlashToast();

// This will tie the offline queue and page cache to the signed-in user...
initializeOfflineSync();

// This will register the service worker for offline support...
initializeServiceWorker();

// This will capture the browser's install prompt for the in-app install button...
initializeInstallPrompt();
