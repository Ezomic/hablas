import { mount } from '@vue/test-utils';
import { afterAll, beforeEach, describe, expect, it, vi } from 'vitest';
import RetakeSkillButton from './RetakeSkillButton.vue';

// West of UTC, midnight UTC is still the day before, so a date labelled in
// local time would name the wrong day. Set before the formatter is built.
vi.hoisted(() => vi.stubEnv('TZ', 'America/Los_Angeles'));

const form = vi.hoisted(() => ({
    processing: false,
    errors: {} as Record<string, string>,
    post: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    useForm: () => form,
}));

vi.mock('@/routes/placement/skills', () => ({
    store: (skill: string) => ({
        url: `/placement/skills/${skill}`,
        method: 'post',
    }),
}));

function mountButton(availableOn: string | null, skill = 'reading') {
    return mount(RetakeSkillButton, { props: { skill, availableOn } });
}

describe('re-take skill button', () => {
    beforeEach(() => {
        form.processing = false;
        form.errors = {};
        form.post.mockReset();
    });

    afterAll(() => {
        vi.unstubAllEnvs();
    });

    it('starts a re-take of its skill', async () => {
        const wrapper = mountButton(null, 'listening');

        expect(wrapper.text()).toContain('Re-take listening');

        await wrapper.get('button').trigger('click');

        expect(form.post).toHaveBeenCalledWith('/placement/skills/listening', {
            preserveScroll: true,
        });
    });

    it('is disabled during the cooldown and names the day it ends', () => {
        const wrapper = mountButton('2026-10-06');

        expect(wrapper.get('button').attributes('disabled')).toBeDefined();
        expect(wrapper.text()).toContain('Available on 6 October');
    });

    it('labels the date in UTC, as the server counts it', () => {
        expect(mountButton('2026-11-01').text()).toContain(
            'Available on 1 November',
        );
    });

    it('shows why the server refused', () => {
        form.errors = { skill: 'You can re-take reading on 6 October.' };

        expect(mountButton(null).text()).toContain(
            'You can re-take reading on 6 October.',
        );
    });
});
