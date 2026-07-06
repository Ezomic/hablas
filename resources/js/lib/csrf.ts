/**
 * For requests that intentionally bypass Inertia's router (e.g. an in-page
 * fetch() that expects a plain JSON response rather than a page visit),
 * Inertia/axios's automatic X-XSRF-TOKEN handling doesn't apply, so the
 * cookie has to be read and attached by hand.
 */
export function getCsrfToken(): string {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}
