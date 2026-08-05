import { showToast } from '@/lib/flashToast';
import type { FlashToast } from '@/types/ui';

/**
 * The practice endpoints answer with JSON over fetch, so a level-up rides
 * back in the response body rather than through Inertia's flash, which only
 * fires on an Inertia visit and would otherwise pop on some unrelated later
 * navigation.
 */
export function showMilestone(payload: unknown): void {
    const milestone = (payload as { milestone?: FlashToast | null } | null)
        ?.milestone;

    if (milestone) {
        showToast(milestone);
    }
}
