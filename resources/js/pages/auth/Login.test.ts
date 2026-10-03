import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import Login from './Login.vue';

const { forms, visit } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
    visit: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    router: { visit },
    setLayoutProps: vi.fn(),
    // TextLink renders an Inertia Link; stub it so it doesn't need a router.
    Link: { template: '<a><slot /></a>' },
    useForm: (data: Record<string, unknown>) => {
        const form = reactive({
            ...data,
            processing: false,
            errors: {},
            // Drive the success path so the component advances to step two.
            post: vi.fn((_url: string, options?: { onSuccess?: () => void }) =>
                options?.onSuccess?.(),
            ),
            reset: vi.fn(),
            clearErrors: vi.fn(),
        });
        forms.push(form);

        return form;
    },
}));

vi.mock('@/routes', () => ({ register: () => ({ url: '/register' }) }));
vi.mock('@/routes/login', () => ({ store: () => ({ url: '/login' }) }));
vi.mock('@/routes/login/code', () => ({
    store: () => ({ url: '/login/code' }),
}));
vi.mock('@/routes/sso', () => ({
    redirect: () => ({ url: '/auth/sso/redirect' }),
}));
vi.mock('@/routes/passkey', () => ({
    loginOptions: () => ({ url: '/passkeys/login/options' }),
    login: (options: { query: { remember: boolean } }) => ({
        url: `/passkeys/login?remember=${Number(options.query.remember)}`,
    }),
}));

function mountPage() {
    forms.length = 0;

    const wrapper = mount(Login, { props: {} });

    return {
        wrapper,
        emailForm: forms[0] as {
            email: string;
            post: ReturnType<typeof vi.fn>;
        },
        codeForm: forms[1] as {
            email: string;
            code: string;
            remember: boolean;
            post: ReturnType<typeof vi.fn>;
        },
    };
}

/**
 * A browser with a passkey: @laravel/passkeys runs for real, with WebAuthn
 * answering straight away and fetch standing in for the server.
 */
function stubPasskeyBrowser() {
    vi.stubGlobal('PublicKeyCredential', class {});
    vi.spyOn(navigator, 'credentials', 'get').mockReturnValue({
        get: async () => ({
            id: 'credential',
            rawId: new ArrayBuffer(8),
            type: 'public-key',
            response: {
                clientDataJSON: new ArrayBuffer(8),
                authenticatorData: new ArrayBuffer(8),
                signature: new ArrayBuffer(8),
                userHandle: null,
            },
            authenticatorAttachment: null,
            getClientExtensionResults: () => ({}),
        }),
    } as unknown as CredentialsContainer);

    const fetch = vi.fn(async (url: string) => ({
        ok: true,
        json: async () =>
            url === '/passkeys/login/options'
                ? { options: { challenge: 'Y2hhbGxlbmdl' } }
                : { redirect: '/dashboard' },
    }));
    vi.stubGlobal('fetch', fetch);

    return fetch;
}

afterEach(() => {
    vi.unstubAllGlobals();
    vi.restoreAllMocks();
    visit.mockClear();
});

describe('auth/Login email-code flow', () => {
    it('asks for an email first, and no password field exists', () => {
        const { wrapper } = mountPage();

        expect(wrapper.find('[data-test="request-code-button"]').exists()).toBe(
            true,
        );
        expect(wrapper.find('[data-test="code-input"]').exists()).toBe(false);
        expect(wrapper.find('input[type="password"]').exists()).toBe(false);
    });

    it('advances to the code step and carries the email over', async () => {
        const { wrapper, emailForm, codeForm } = mountPage();

        emailForm.email = 'someone@example.com';
        await wrapper.get('form').trigger('submit');

        expect(emailForm.post).toHaveBeenCalledWith(
            '/login/code',
            expect.anything(),
        );

        expect(wrapper.find('[data-test="code-input"]').exists()).toBe(true);
        expect(wrapper.find('[data-test="request-code-button"]').exists()).toBe(
            false,
        );
        expect(codeForm.email).toBe('someone@example.com');
        expect(wrapper.text()).toContain('someone@example.com');
    });
});

describe('auth/Login Thijssensoftware ID sign-in', () => {
    it('links to the ID sign-in next to the email-code form', () => {
        const { wrapper } = mountPage();

        const sso = wrapper.get('[data-test="sso-button"]');

        expect(sso.attributes('href')).toBe('/auth/sso/redirect');
        expect(sso.text()).toBe('Sign in with Thijssensoftware');
        expect(wrapper.find('[data-test="request-code-button"]').exists()).toBe(
            true,
        );
    });
});

describe('auth/Login Remember me', () => {
    it('offers one Remember me, unticked, on both steps', async () => {
        const { wrapper, emailForm } = mountPage();

        expect(wrapper.findAll('#remember')).toHaveLength(1);
        expect(wrapper.get('#remember').attributes('aria-checked')).toBe(
            'false',
        );

        emailForm.email = 'someone@example.com';
        await wrapper.get('form').trigger('submit');

        expect(wrapper.findAll('#remember')).toHaveLength(1);
    });

    it('gives every tab stop its own position on both steps', async () => {
        const { wrapper, emailForm } = mountPage();
        const positions = () =>
            wrapper
                .findAll('[tabindex]')
                .map((element) => element.attributes('tabindex'))
                .filter((position) => Number(position) > 0);

        expect(positions()).toEqual([...new Set(positions())]);

        emailForm.email = 'someone@example.com';
        await wrapper.get('form').trigger('submit');

        expect(positions()).toEqual([...new Set(positions())]);
    });

    it.each([
        ['ticked', true, '/passkeys/login?remember=1'],
        ['left unticked', false, '/passkeys/login?remember=0'],
    ])(
        'sends Remember me %s with a passkey sign-in',
        async (_label, tick, submitUrl) => {
            const fetch = stubPasskeyBrowser();
            const { wrapper } = mountPage();
            await flushPromises();

            if (tick) {
                await wrapper.get('#remember').trigger('click');
            }

            const passkey = wrapper
                .findAll('button')
                .find((button) => button.text() === 'Sign in with a passkey');
            await passkey?.trigger('click');
            await flushPromises();

            expect(fetch).toHaveBeenLastCalledWith(
                submitUrl,
                expect.objectContaining({ method: 'POST' }),
            );
            expect(visit).toHaveBeenCalledWith('/dashboard');
        },
    );

    it('sends the same Remember me with a code sign-in', async () => {
        const { wrapper, emailForm, codeForm } = mountPage();

        await wrapper.get('#remember').trigger('click');
        emailForm.email = 'someone@example.com';
        await wrapper.get('form').trigger('submit');
        codeForm.code = '123456';
        await wrapper.get('form').trigger('submit');

        expect(codeForm.post).toHaveBeenCalledWith('/login', expect.anything());
        expect(codeForm.remember).toBe(true);
    });
});
