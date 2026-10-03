import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { ref } from 'vue';
import InstallAppButton from './InstallAppButton.vue';

const canInstall = ref(false);
const hint = ref<'ios' | 'android' | null>(null);
const install = vi.fn();

vi.mock('@/composables/useInstallPrompt', () => ({
    useInstallPrompt: () => ({ canInstall, hint, install }),
}));

vi.mock('@/components/ui/dropdown-menu', () => ({
    DropdownMenuItem: {
        emits: ['select'],
        template: '<div @click="$emit(\'select\')"><slot /></div>',
    },
}));

beforeEach(() => {
    canInstall.value = false;
    hint.value = null;
    install.mockClear();
});

describe('InstallAppButton', () => {
    it('renders nothing when not installable and no hint applies', () => {
        const wrapper = mount(InstallAppButton);

        expect(wrapper.find('[data-test="install-app"]').exists()).toBe(false);
        expect(wrapper.find('[data-test="install-hint"]').exists()).toBe(false);
    });

    it('shows an install button that triggers the prompt', async () => {
        canInstall.value = true;
        const wrapper = mount(InstallAppButton);

        expect(wrapper.text()).toContain('Install app');

        await wrapper.get('[data-test="install-app"]').trigger('click');

        expect(install).toHaveBeenCalledOnce();
    });

    it('shows the install item in the menu layout', async () => {
        canInstall.value = true;
        const wrapper = mount(InstallAppButton, { props: { layout: 'menu' } });

        await wrapper.get('[data-test="install-app"]').trigger('click');

        expect(wrapper.text()).toContain('Install app');
        expect(install).toHaveBeenCalledOnce();
    });

    it('shows the iOS hint', () => {
        hint.value = 'ios';
        const wrapper = mount(InstallAppButton);

        expect(wrapper.get('[data-test="install-hint"]').text()).toContain(
            'Add to Home Screen',
        );
    });

    it('shows the Android menu hint', () => {
        hint.value = 'android';
        const wrapper = mount(InstallAppButton, { props: { layout: 'menu' } });

        expect(wrapper.get('[data-test="install-hint"]').text()).toContain(
            'Install app',
        );
    });
});
