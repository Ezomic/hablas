import { i18n } from '@/i18n';
import type { InterfaceLocale } from '@/i18n';

const tags: Record<InterfaceLocale, string> = {
    en: 'en-GB',
    nl: 'nl-NL',
};

export function intlTag(): string {
    return tags[i18n.global.locale.value as InterfaceLocale] ?? tags.en;
}

export function dateFormatter(
    options: Intl.DateTimeFormatOptions,
): Intl.DateTimeFormat {
    return new Intl.DateTimeFormat(intlTag(), options);
}

export function joinList(items: string[]): string {
    return new Intl.ListFormat(intlTag(), {
        style: 'long',
        type: 'conjunction',
    }).format(items);
}
