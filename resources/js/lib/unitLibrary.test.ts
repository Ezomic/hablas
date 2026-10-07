import { describe, expect, it } from 'vitest';
import type { LibraryUnit } from '@/types/unit';
import { filterUnits, groupByLevel, paceToDeadline } from './unitLibrary';

function unit(
    id: number,
    cefrLevel: string,
    primarySkill: string,
    availability: LibraryUnit['availability'],
): LibraryUnit {
    return {
        id,
        title: `Unit ${id}`,
        taskDescription: 'Do the thing.',
        cefrLevel,
        primarySkill,
        availability,
        lessonCount: 0,
        lessonsCompleted: 0,
        masteredCount: 0,
        percent: 0,
        stars: 0,
        struggles: 0,
    };
}

const units: LibraryUnit[] = [
    unit(1, 'A1', 'reading', 'completed'),
    unit(2, 'A1', 'speaking', 'available'),
    unit(3, 'A2', 'reading', 'held_back'),
    unit(4, 'B1', 'reading', 'locked'),
];

function ids(list: LibraryUnit[]): number[] {
    return list.map((item) => item.id);
}

describe('filterUnits', () => {
    it('keeps everything when both filters are off', () => {
        expect(
            ids(filterUnits(units, { skill: 'all', completion: 'all' })),
        ).toEqual([1, 2, 3, 4]);
    });

    it('keeps only the chosen skill', () => {
        expect(
            ids(filterUnits(units, { skill: 'speaking', completion: 'all' })),
        ).toEqual([2]);
    });

    it('splits completed from everything not completed yet', () => {
        expect(
            ids(filterUnits(units, { skill: 'all', completion: 'completed' })),
        ).toEqual([1]);
        expect(
            ids(
                filterUnits(units, {
                    skill: 'all',
                    completion: 'not_completed',
                }),
            ),
        ).toEqual([2, 3, 4]);
    });

    it('combines the two filters', () => {
        expect(
            ids(
                filterUnits(units, {
                    skill: 'reading',
                    completion: 'not_completed',
                }),
            ),
        ).toEqual([3, 4]);
    });
});

describe('groupByLevel', () => {
    it('groups in CEFR order and skips levels without units', () => {
        const groups = groupByLevel([
            unit(5, 'B1', 'reading', 'locked'),
            unit(6, 'A1', 'reading', 'available'),
            unit(7, 'A1', 'writing', 'available'),
        ]);

        expect(groups.map((group) => group.level)).toEqual(['A1', 'B1']);
        expect(ids(groups[0].units)).toEqual([6, 7]);
        expect(ids(groups[1].units)).toEqual([5]);
    });

    it('returns no groups for no units', () => {
        expect(groupByLevel([])).toEqual([]);
    });
});

describe('paceToDeadline', () => {
    const withLessons = (count: number, done: number, level = 'A1') => ({
        ...unit(1, level, 'reading', 'available'),
        lessonCount: count,
        lessonsCompleted: done,
    });
    const today = new Date(2026, 9, 6);

    it('divides the lessons left over the days, counting both end days', () => {
        const pace = paceToDeadline(
            [withLessons(120, 7)],
            'A1',
            '2026-12-01',
            today,
        );

        expect(pace).toEqual({
            left: 113,
            days: 57,
            perDay: 2,
            status: 'comfortable',
        });
    });

    it('calls a heavier load steady, then behind', () => {
        expect(
            paceToDeadline([withLessons(120, 0)], 'A1', '2026-12-01', today)
                .status,
        ).toBe('steady');
        expect(
            paceToDeadline([withLessons(200, 0)], 'A1', '2026-12-01', today)
                .status,
        ).toBe('behind');
    });

    it('ignores other levels, and says done when nothing is left', () => {
        const pace = paceToDeadline(
            [withLessons(10, 10), withLessons(50, 0, 'A2')],
            'A1',
            '2026-12-01',
            today,
        );

        expect(pace.status).toBe('done');
    });

    it('is late once the date has passed', () => {
        const pace = paceToDeadline(
            [withLessons(10, 4)],
            'A1',
            '2026-10-01',
            today,
        );

        expect(pace).toMatchObject({ left: 6, days: 0, status: 'late' });
    });

    it('puts everything on today on the last day', () => {
        expect(
            paceToDeadline([withLessons(10, 4)], 'A1', '2026-10-06', today),
        ).toMatchObject({ days: 1, perDay: 6 });
    });
});
