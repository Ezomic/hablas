import { dateFormatter } from '@/lib/intlLocale';

// The server counts the cooldown in UTC days, like streaks and the review
// forecast, so the date is labelled in UTC too.
export function retakeDate(date: string): string {
    return dateFormatter({
        day: 'numeric',
        month: 'long',
        timeZone: 'UTC',
    }).format(new Date(`${date}T00:00:00Z`));
}
