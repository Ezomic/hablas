import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import Index from './Index.vue';

const { forms } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    useForm: (data: Record<string, unknown>) => {
        const form = reactive({ ...data, processing: false, post: vi.fn() });
        forms.push(form);

        return form;
    },
}));

vi.mock('@/routes/reflections', () => ({
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
