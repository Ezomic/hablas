export function pluralizeDays(count: number): string {
    return count === 1 ? 'day' : 'days';
}

export function pluralize(noun: string, count: number): string {
    return count === 1 ? noun : `${noun}s`;
}
