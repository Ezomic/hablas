import { describe, expect, it } from 'vitest';
import { dueLabel } from './dueLabel';

const now = new Date(2026, 8, 30, 14, 0);

function at(day: number, hour: number): string {
    return new Date(2026, 8, day, hour, 0).toISOString();
}

describe('due label', () => {
    it('calls anything due before today overdue', () => {
        expect(dueLabel(at(29, 23), now)).toBe('Overdue');
        expect(dueLabel(at(1, 9), now)).toBe('Overdue');
    });

    it('counts any time today as due today, earlier or later', () => {
        expect(dueLabel(at(30, 0), now)).toBe('Due today');
        expect(dueLabel(at(30, 23), now)).toBe('Due today');
    });

    it('names tomorrow, then counts calendar days', () => {
        expect(dueLabel(new Date(2026, 9, 1, 0, 30).toISOString(), now)).toBe(
            'Due tomorrow',
        );
        expect(dueLabel(new Date(2026, 9, 3, 8, 0).toISOString(), now)).toBe(
            'Due in 3 days',
        );
    });
});
