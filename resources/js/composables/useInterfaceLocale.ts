import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { setLocale } from '@/i18n';
import type { InterfaceLocale } from '@/i18n';
import { cachePage, clearPageCache } from '@/lib/pageCache';
import { update as updateSignedIn } from '@/routes/interface-locale';
import { update as updateGuest } from '@/routes/interface-locale/guest';

export function useInterfaceLocale() {
    const page = usePage();

    const current = computed(() => page.props.interfaceLocale);
    const supported = computed(() => page.props.supportedLocales);

    function change(locale: InterfaceLocale): void {
        if (locale === current.value) {
            return;
        }

        const route = page.props.auth.user ? updateSignedIn() : updateGuest();

        router.patch(
            route.url,
            { interface_locale: locale },
            {
                preserveScroll: true,
                onSuccess: async () => {
                    setLocale(locale);
                    await clearPageCache();
                    await cachePage(
                        window.location.pathname + window.location.search,
                    );
                },
            },
        );
    }

    return { current, supported, change };
}
