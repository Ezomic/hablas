import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { ref } from 'vue';
import LessonLayout from './LessonLayout.vue';

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({
        isOnline: ref(false),
        pendingCount: ref(2),
        rejectedCount: ref(0),
    }),
}));

describe('LessonLayout', () => {
    it('has no sidebar, keeps the sync banner and the toaster, and renders the page', () => {
        const wrapper = mount(LessonLayout, {
            slots: { default: '<main>The exercise</main>' },
        });

        expect(wrapper.text()).toContain('The exercise');
        expect(wrapper.text()).toContain('saved on this device');
        expect(wrapper.html()).not.toContain('data-sidebar');
        expect(wrapper.findComponent({ name: 'Toaster' }).exists()).toBe(true);
    });
});
