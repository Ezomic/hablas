import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import LetterBoxes from './LetterBoxes.vue';

const mask = ['h', null, 'l', null, ' ', 'a', null];

function boxes(wrapper: ReturnType<typeof mount>) {
    return wrapper.findAll('[data-testid="letter-box"]');
}

describe('LetterBoxes', () => {
    it('shows the given letters locked and a box for each missing one', () => {
        const wrapper = mount(LetterBoxes, {
            props: { modelValue: '', mask, locale: 'es' },
        });

        expect(
            wrapper
                .findAll('[data-testid="given-letter"]')
                .map((letter) => letter.text()),
        ).toEqual(['h', 'l', 'a']);
        expect(boxes(wrapper)).toHaveLength(3);
    });

    it('assembles the word from the given and the typed letters', async () => {
        const wrapper = mount(LetterBoxes, {
            props: { modelValue: '', mask, locale: 'es' },
        });

        await boxes(wrapper)[0].setValue('o');
        await boxes(wrapper)[1].setValue('a');
        await boxes(wrapper)[2].setValue('b');

        const values = wrapper.emitted('update:modelValue') ?? [];

        expect(values[values.length - 1]).toEqual(['hola ab']);
    });

    it('fills the next boxes from a longer entry', async () => {
        const wrapper = mount(LetterBoxes, {
            props: { modelValue: '', mask, locale: 'es' },
        });

        await boxes(wrapper)[0].setValue('oab');

        const values = wrapper.emitted('update:modelValue') ?? [];

        expect(values[values.length - 1]).toEqual(['hola ab']);
    });

    it('takes an accent key into the focused box', async () => {
        const wrapper = mount(LetterBoxes, {
            props: { modelValue: '', mask, locale: 'es' },
            attachTo: document.body,
        });

        (wrapper.vm as unknown as { insert: (c: string) => void }).insert('é');

        const values = wrapper.emitted('update:modelValue') ?? [];

        expect(values[values.length - 1]).toEqual(['hél a']);
        wrapper.unmount();
    });

    it('submits on Enter', async () => {
        const wrapper = mount(LetterBoxes, {
            props: { modelValue: '', mask, locale: 'es' },
        });

        await boxes(wrapper)[0].trigger('keydown', { key: 'Enter' });

        expect(wrapper.emitted('submit')).toHaveLength(1);
    });
});
