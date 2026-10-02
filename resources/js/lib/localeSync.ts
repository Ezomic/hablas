import { router } from '@inertiajs/vue3';
import { setLocale } from '@/i18n';
import type { InterfaceLocale } from '@/i18n';

export function initializeLocaleSync(): void {
    router.on('navigate', (event) => {
        const locale = event.detail.page.props.interfaceLocale as
            InterfaceLocale | undefined;

        if (locale) {
            setLocale(locale);
        }
    });
}
