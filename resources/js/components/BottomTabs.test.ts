import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import BottomTabs from './BottomTabs.vue';

const page = vi.hoisted(() => ({
    props: { dueReviewCount: 0 },
    url: '/units/3',
}));

vi.mock('@inertiajs/vue3', () => ({
    usePage: () => page,
    Link: {
        props: ['href'],
        template:
            '<a :href="typeof href === \'string\' ? href : href.url"><slot /></a>',
    },
}));

const { route } = vi.hoisted(() => ({
    route: (path: string) => () => ({ url: path, method: 'get' }),
}));

vi.mock('@/routes', () => ({
    continueMethod: route('/continue'),
    dashboard: route('/dashboard'),
    practice: route('/practice'),
}));
vi.mock('@/routes/review', () => ({ index: route('/review') }));

function tabs(url: string, due = 0) {
    page.url = url;
    page.props.dueReviewCount = due;

    return mount(BottomTabs);
}

describe('BottomTabs', () => {
    it('offers four tabs and marks the one of the current page', () => {
        const wrapper = tabs('/units/3');

        expect(wrapper.findAll('a')).toHaveLength(4);
        expect(
            wrapper
                .find('[data-testid="tab-learn"]')
                .attributes('aria-current'),
        ).toBe('page');
        expect(
            wrapper
                .find('[data-testid="tab-review"]')
                .attributes('aria-current'),
        ).toBeUndefined();
    });

    it('keeps the practice modes under the practise tab and settings under me', () => {
        expect(
            tabs('/reading')
                .find('[data-testid="tab-practise"]')
                .attributes('aria-current'),
        ).toBe('page');
        expect(
            tabs('/settings/profile')
                .find('[data-testid="tab-me"]')
                .attributes('aria-current'),
        ).toBe('page');
    });

    it('shows how many reviews are due on the review tab', () => {
        expect(
            tabs('/continue', 12).find('[data-testid="tab-badge"]').text(),
        ).toBe('12');
        expect(
            tabs('/continue', 250).find('[data-testid="tab-badge"]').text(),
        ).toBe('99+');
        expect(
            tabs('/continue', 0).find('[data-testid="tab-badge"]').exists(),
        ).toBe(false);
    });
});
