import { createI18n } from 'vue-i18n';
import en from '@/lang/en.json';
import nl from '@/lang/nl.json';

export type InterfaceLocale = 'en' | 'nl';

export const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: 'en',
    fallbackLocale: 'en',
    messages: { en, nl },
});

export function setLocale(locale: InterfaceLocale): void {
    i18n.global.locale.value = locale;

    if (typeof document !== 'undefined') {
        document.documentElement.lang = locale;
    }
}
