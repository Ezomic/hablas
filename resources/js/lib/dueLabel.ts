import { i18n } from '@/i18n';

const DAY_MS = 86_400_000;

function startOfDay(date: Date): number {
    return new Date(
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
    ).getTime();
}

export function dueLabel(dueAt: string, now: Date = new Date()): string {
    // Rounded, because a day that crosses a DST change is 23 or 25 hours long.
    const days = Math.round(
        (startOfDay(new Date(dueAt)) - startOfDay(now)) / DAY_MS,
    );

    const { t } = i18n.global;

    if (days < 0) {
        return t('review.due.overdue');
    }

    if (days === 0) {
        return t('review.due.today');
    }

    if (days === 1) {
        return t('review.due.tomorrow');
    }

    return t('review.due.inDays', days);
}
