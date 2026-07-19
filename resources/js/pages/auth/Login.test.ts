import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import Login from './Login.vue';

const { forms } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
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

// PasskeyVerify talks to WebAuthn via @laravel/passkeys; not under test here.
vi.mock('@/components/PasskeyVerify.vue', () => ({
    default: { render: () => null },
}));

vi.mock('@/routes', () => ({ register: () => ({ url: '/register' }) }));
vi.mock('@/routes/login', () => ({ store: () => ({ url: '/login' }) }));
vi.mock('@/routes/login/code', () => ({
    store: () => ({ url: '/login/code' }),
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
        codeForm: forms[1] as { email: string; code: string },
    };
}

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
