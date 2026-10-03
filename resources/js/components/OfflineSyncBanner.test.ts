import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { ref } from 'vue';
import { setLocale } from '@/i18n';
import OfflineSyncBanner from './OfflineSyncBanner.vue';

const isOnline = ref(true);
const pendingCount = ref(0);
const rejectedCount = ref(0);

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ isOnline, pendingCount, rejectedCount }),
}));

beforeEach(() => {
    isOnline.value = true;
    pendingCount.value = 0;
    rejectedCount.value = 0;
});

describe('offline sync banner', () => {
    it('renders nothing when nothing is waiting or rejected', () => {
        expect(mount(OfflineSyncBanner).text()).toBe('');
    });

    it('says how many attempts are waiting while offline', () => {
        isOnline.value = false;
        pendingCount.value = 2;

        expect(mount(OfflineSyncBanner).text()).toContain(
            "You're offline. 2 attempts saved on this device will sync once you're back online.",
        );
    });

    it('says attempts are still waiting once back online', () => {
        pendingCount.value = 1;

        expect(mount(OfflineSyncBanner).text()).toContain(
            "1 attempt saved on this device hasn't synced yet and will be retried automatically.",
        );
    });

    it('reports discarded attempts until dismissed', async () => {
        rejectedCount.value = 1;
        const wrapper = mount(OfflineSyncBanner);

        expect(wrapper.text()).toContain(
            "1 attempt saved offline couldn't be synced and was discarded.",
        );

        await wrapper.get('button').trigger('click');

        expect(rejectedCount.value).toBe(0);
        expect(wrapper.text()).toBe('');
    });

    it('uses the Dutch plural forms', () => {
        setLocale('nl');
        pendingCount.value = 1;

        expect(mount(OfflineSyncBanner).text()).toContain(
            '1 poging die op dit apparaat is opgeslagen',
        );

        pendingCount.value = 3;

        expect(mount(OfflineSyncBanner).text()).toContain(
            '3 pogingen die op dit apparaat zijn opgeslagen',
        );
    });
});
