import { getCsrfToken } from '@/lib/csrf';

/**
 * fetch() with the JSON headers and manual CSRF token every non-Inertia
 * request in this app needs (Inertia's own router handles this
 * automatically, but plain fetch calls — offline-queued attempts, push
 * subscription management — don't go through it).
 */
export function fetchJson(
    url: string,
    method: string,
    body?: string,
): Promise<Response> {
    return fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-XSRF-TOKEN': getCsrfToken(),
        },
        body,
    });
}
