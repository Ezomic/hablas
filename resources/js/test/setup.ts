import { config } from '@vue/test-utils';
import { afterEach } from 'vitest';
import { i18n, setLocale } from '@/i18n';

config.global.plugins = [i18n];

afterEach(() => {
    setLocale('en');
});
