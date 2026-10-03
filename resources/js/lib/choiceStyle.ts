export function choiceState(
    option: string,
    selected: string | null,
    answer: string | null | undefined,
): string {
    if (answer === undefined || answer === null || answer === '') {
        return option === selected
            ? 'border-primary bg-primary/10'
            : 'hover:bg-accent';
    }

    if (option === answer) {
        return 'border-green-600 bg-green-50 text-green-900 dark:bg-green-950 dark:text-green-100';
    }

    return option === selected
        ? 'border-red-600 bg-red-50 text-red-900 dark:bg-red-950 dark:text-red-100'
        : 'opacity-60';
}

export const choiceButtonClass =
    'flex min-h-12 w-full items-center gap-3 rounded-lg border bg-background px-4 py-3 text-left text-base transition-colors outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50';
