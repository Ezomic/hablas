import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import type { LibraryUnit } from '@/types/unit';
import Index from './Index.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    Link: {
        props: ['href'],
        template:
            '<a :href="typeof href === \'string\' ? href : href.url"><slot /></a>',
    },
}));

vi.mock('@/routes/units', () => ({
    index: () => ({ url: '/units', method: 'get' }),
    show: (id: number) => ({ url: `/units/${id}`, method: 'get' }),
}));

vi.mock('@/routes/review', () => ({
    index: () => ({ url: '/review', method: 'get' }),
}));

function unit(
    id: number,
    cefrLevel: string,
    availability: LibraryUnit['availability'],
    primarySkill = 'reading',
): LibraryUnit {
    return {
        id,
        title: `Unit ${id}`,
        taskDescription: `Task ${id}`,
        cefrLevel,
        primarySkill,
        availability,
        lessonCount: 0,
        lessonsCompleted: 0,
        masteredCount: 0,
        percent: 0,
        stars: 0,
    };
}

function mountPage(
    units: LibraryUnit[],
    language: { name: string } | null = { name: 'Spanish' },
) {
    return mount(Index, { props: { language, units } });
}

function hrefs(wrapper: ReturnType<typeof mountPage>): string[] {
    return wrapper
        .findAll('a')
        .map((anchor) => anchor.attributes('href') ?? '');
}

describe('unit library page', () => {
    it('groups the units under their level headings', () => {
        const wrapper = mountPage([
            unit(1, 'A1', 'completed'),
            unit(2, 'A2', 'available'),
        ]);

        expect(wrapper.findAll('h2').map((heading) => heading.text())).toEqual([
            'A1',
            'A2',
        ]);
        expect(wrapper.text()).toContain('Unit 1');
        expect(wrapper.text()).toContain('Task 2');
    });

    it('shows the skill and the availability of each unit', () => {
        const text = mountPage([
            unit(1, 'A1', 'completed', 'speaking'),
            unit(2, 'A1', 'available'),
        ]).text();

        expect(text).toContain('Speaking');
        expect(text).toContain('Completed');
        expect(text).toContain('Available');
    });

    it('links completed and available units, and nothing else', () => {
        const links = hrefs(
            mountPage([
                unit(1, 'A1', 'completed'),
                unit(2, 'A1', 'available'),
                unit(3, 'A1', 'held_back'),
                unit(4, 'B1', 'locked'),
            ]),
        );

        expect(links).toContain('/units/1');
        expect(links).toContain('/units/2');
        expect(links).not.toContain('/units/3');
        expect(links).not.toContain('/units/4');
    });

    it('says what level unlocks a locked unit', () => {
        const text = mountPage([unit(4, 'B1', 'locked')]).text();

        expect(text).toContain('Locked');
        expect(text).toContain('Unlocks when your overall level reaches B1');
    });

    it('sends the learner to review while new units are held back', () => {
        const wrapper = mountPage([
            unit(1, 'A1', 'completed'),
            unit(3, 'A1', 'held_back'),
        ]);

        expect(wrapper.text()).toContain("Reinforce what's tricky first");
        expect(wrapper.text()).toContain('Opens once your recent reviews');
        expect(hrefs(wrapper)).toContain('/review');
        expect(hrefs(wrapper)).toContain('/units/1');
    });

    it('does not mention remediation when nothing is held back', () => {
        expect(mountPage([unit(1, 'A1', 'available')]).text()).not.toContain(
            "Reinforce what's tricky first",
        );
    });

    it('offers skill and progress filters when there are units', () => {
        const text = mountPage([unit(1, 'A1', 'available')]).text();

        expect(text).toContain('Skill');
        expect(text).toContain('Progress');
    });

    it('explains an empty library', () => {
        const text = mountPage([]).text();

        expect(text).toContain('No Spanish units yet.');
        expect(text).not.toContain('Progress');
    });

    it('explains a missing language', () => {
        expect(mountPage([], null).text()).toContain('No active language yet.');
    });

    it('opens a unit in progress and says which lesson is next', () => {
        const wrapper = mountPage([
            {
                ...unit(1, 'A1', 'in_progress'),
                lessonCount: 5,
                lessonsCompleted: 2,
                masteredCount: 0,
            },
        ]);

        expect(hrefs(wrapper)).toContain('/units/1');
        expect(wrapper.text()).toContain('In progress');
        expect(wrapper.text()).toContain('Lesson 3 of 5');
    });

    it('treats a unit in progress as not completed in the filter', () => {
        const wrapper = mountPage([unit(1, 'A1', 'in_progress')]);

        expect(wrapper.text()).toContain('Unit 1');
    });
});
