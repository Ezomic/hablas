// The server counts the cooldown in UTC days, like streaks and the review
// forecast, so the date is labelled in UTC too.
const formatter = new Intl.DateTimeFormat('en-GB', {
    day: 'numeric',
    month: 'long',
    timeZone: 'UTC',
});

export function retakeDate(date: string): string {
    return formatter.format(new Date(`${date}T00:00:00Z`));
}
