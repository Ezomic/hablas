import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import type * as ProgressCard from '@/lib/progressCard';
import Share from './Share.vue';

const { renderProgressCard } = vi.hoisted(() => ({
    renderProgressCard: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    router: { post: vi.fn() },
}));

vi.mock('@/routes/progress/share', () => ({
    regenerate: () => ({ url: '/progress/share/regenerate' }),
}));

vi.mock('@/lib/progressCard', async (importOriginal) => ({
    ...(await importOriginal<typeof ProgressCard>()),
    renderProgressCard,
}));

const snapshot: ProgressSnapshot = {
    language: { code: 'es', name: 'Spanish' },
    blendedLevel: 'A2',
    skillLevels: {
        reading: 'A2',
        listening: 'A2',
        speaking: 'A2',
        writing: 'B1',
    },
    streak: { currentLength: 12, longestLength: 20 },
    unitCompletionPercentage: 25,
    topErrorTags: [],
};

const card = new Blob(['png'], { type: 'image/png' });

async function mountPage(
    props: { snapshot: ProgressSnapshot | null } = { snapshot },
) {
    const wrapper = mount(Share, {
        props: {
            snapshot: props.snapshot,
            shareUrl: props.snapshot ? 'https://hablas.test/shared/abc' : null,
            languageId: props.snapshot ? 1 : null,
        },
    });

    await flushPromises();

    return wrapper;
}

function stubWebShare(
    canShare: (data: ShareData) => boolean,
    share: (data: ShareData) => Promise<void> = vi.fn(),
) {
    Object.defineProperty(navigator, 'canShare', {
        configurable: true,
        value: canShare,
    });
    Object.defineProperty(navigator, 'share', {
        configurable: true,
        value: share,
    });
}

function buttonLabelled(
    wrapper: Awaited<ReturnType<typeof mountPage>>,
    label: string,
) {
    return wrapper.findAll('button').find((button) => button.text() === label);
}

beforeEach(() => {
    vi.useFakeTimers({ toFake: ['Date'] });
    vi.setSystemTime(new Date(2026, 8, 30, 10, 0));
    renderProgressCard.mockReset().mockResolvedValue(card);
    vi.spyOn(URL, 'createObjectURL').mockReturnValue('blob:progress-card');
    vi.spyOn(URL, 'revokeObjectURL').mockImplementation(() => undefined);
});

afterEach(() => {
    vi.useRealTimers();
    vi.restoreAllMocks();
    Reflect.deleteProperty(navigator, 'canShare');
    Reflect.deleteProperty(navigator, 'share');
});

describe('progress/Share image', () => {
    it('offers the image as a download named after the language and date', async () => {
        const wrapper = await mountPage();

        expect(renderProgressCard).toHaveBeenCalledWith(snapshot);

        const link = wrapper.get('a[download]');

        expect(link.text()).toBe('Download image');
        expect(link.attributes('download')).toBe(
            'hablas-progress-es-2026-09-30.png',
        );
        expect(link.attributes('href')).toBe('blob:progress-card');
    });

    it('previews the image it will download', async () => {
        const wrapper = await mountPage();

        const preview = wrapper.get('img');

        expect(preview.attributes('src')).toBe('blob:progress-card');
        expect(preview.attributes('alt')).toContain('A2');
    });

    it('hides Share image when the browser cannot share files', async () => {
        const wrapper = await mountPage();

        expect(buttonLabelled(wrapper, 'Share image')).toBeUndefined();
    });

    it('hides Share image when the browser refuses this file', async () => {
        stubWebShare(() => false);

        const wrapper = await mountPage();

        expect(buttonLabelled(wrapper, 'Share image')).toBeUndefined();
    });

    it('shares the image file when the browser can share files', async () => {
        const share = vi.fn().mockResolvedValue(undefined);
        stubWebShare((data) => (data.files?.length ?? 0) > 0, share);

        const wrapper = await mountPage();
        await buttonLabelled(wrapper, 'Share image')!.trigger('click');
        await flushPromises();

        expect(share).toHaveBeenCalledTimes(1);

        const [file] = share.mock.calls[0][0].files as File[];

        expect(file.name).toBe('hablas-progress-es-2026-09-30.png');
        expect(file.type).toBe('image/png');
        expect(wrapper.text()).not.toContain("Couldn't");
    });

    it('stays quiet when the learner closes the share sheet', async () => {
        stubWebShare(
            () => true,
            vi
                .fn()
                .mockRejectedValue(new DOMException('Cancelled', 'AbortError')),
        );

        const wrapper = await mountPage();
        await buttonLabelled(wrapper, 'Share image')!.trigger('click');
        await flushPromises();

        expect(wrapper.text()).not.toContain("Couldn't share the image.");
    });

    it('says so when sharing fails', async () => {
        stubWebShare(
            () => true,
            vi
                .fn()
                .mockRejectedValue(new DOMException('No', 'NotAllowedError')),
        );

        const wrapper = await mountPage();
        await buttonLabelled(wrapper, 'Share image')!.trigger('click');
        await flushPromises();

        expect(wrapper.text()).toContain("Couldn't share the image.");
    });

    it('says so when the image cannot be created', async () => {
        renderProgressCard.mockRejectedValue(new Error('no canvas'));

        const wrapper = await mountPage();

        expect(wrapper.text()).toContain("Couldn't create the image.");
        expect(wrapper.find('a[download]').exists()).toBe(false);
        expect(wrapper.find('img').exists()).toBe(false);
    });

    it('does not draw an image before placement', async () => {
        const wrapper = await mountPage({ snapshot: null });

        expect(renderProgressCard).not.toHaveBeenCalled();
        expect(wrapper.text()).not.toContain('Download image');
    });

    it('releases the image when the page goes away', async () => {
        const wrapper = await mountPage();

        wrapper.unmount();

        expect(URL.revokeObjectURL).toHaveBeenCalledWith('blob:progress-card');
    });
});
