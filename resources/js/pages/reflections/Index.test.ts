import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import { i18n, setLocale } from '@/i18n';
import Index from './Index.vue';

const { forms } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    useForm: (data: Record<string, unknown>) => {
        const form = reactive({ ...data, processing: false, post: vi.fn() });
        forms.push(form);

        return form;
    },
}));

vi.mock('@/routes/reflections', () => ({
    index: () => ({ url: '/reflections', method: 'get' }),
    store: () => ({ url: '/reflections' }),
}));

function mountPage() {
    forms.length = 0;

    const wrapper = mount(Index, {
        props: {
            statements: [
                {
                    id: 1,
                    skill: 'reading',
                    statement_text: 'I can read a menu.',
                },
            ],
            submittedThisWeek: false,
        },
    });

    return { wrapper, form: forms[0] as { can_do_ids: number[] } };
}

describe('reflections/Index checkbox binding', () => {
    it('toggles the statement id into form state when the checkbox is clicked', async () => {
        const { wrapper, form } = mountPage();

        const checkbox = wrapper.get('[role="checkbox"]');
        await checkbox.trigger('click');

        expect(form.can_do_ids).toContain(1);

        await checkbox.trigger('click');

        expect(form.can_do_ids).not.toContain(1);
    });
});

describe('reflections/Index text', () => {
    it('keeps the English text and groups statements by skill', () => {
        const { wrapper } = mountPage();

        expect(wrapper.get('h1').text()).toBe('Weekly reflection');
        expect(wrapper.text()).toContain(
            'Check off what you feel confident doing this week.',
        );
        expect(wrapper.findAll('h2').map((h) => h.text())).toEqual([
            'Reading',
            'Listening',
            'Speaking',
            'Writing',
        ]);
        expect(wrapper.get('button[type="submit"]').text()).toBe(
            'Submit reflection',
        );
    });

    it('shows the Dutch headings and leaves the statement text alone', () => {
        setLocale('nl');

        const { wrapper } = mountPage();

        expect(wrapper.get('h1').text()).toBe(
            i18n.global.t('nav.weeklyReflection'),
        );
        expect(wrapper.findAll('h2')[0].text()).toBe(
            i18n.global.t('skills.reading'),
        );
        expect(wrapper.text()).toContain('I can read a menu.');
        expect(wrapper.get('button[type="submit"]').text()).toBe(
            i18n.global.t('reflections.submit'),
        );
    });
});
