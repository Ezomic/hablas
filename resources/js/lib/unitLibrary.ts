import type { LibraryUnit } from '@/types/unit';

const CEFR_LEVELS = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];

export type CompletionFilter = 'all' | 'completed' | 'not_completed';

export interface UnitFilters {
    skill: string;
    completion: CompletionFilter;
}

export interface LevelGroup {
    level: string;
    units: LibraryUnit[];
}

export function filterUnits(
    units: LibraryUnit[],
    filters: UnitFilters,
): LibraryUnit[] {
    return units.filter((unit) => {
        if (filters.skill !== 'all' && unit.primarySkill !== filters.skill) {
            return false;
        }

        if (filters.completion === 'all') {
            return true;
        }

        return (
            (unit.availability === 'completed') ===
            (filters.completion === 'completed')
        );
    });
}

export function groupByLevel(units: LibraryUnit[]): LevelGroup[] {
    return CEFR_LEVELS.map((level) => ({
        level,
        units: units.filter((unit) => unit.cefrLevel === level),
    })).filter((group) => group.units.length > 0);
}

export interface Pace {
    left: number;
    days: number;
    perDay: number;
    status: 'done' | 'late' | 'comfortable' | 'steady' | 'behind';
}

const MS_PER_DAY = 86_400_000;

/**
 * The lessons of a level still to do against the days to a deadline. The day
 * of the deadline counts, so on the last day the whole remainder is for today.
 */
export function paceToDeadline(
    units: LibraryUnit[],
    level: string,
    deadline: string,
    today: Date,
): Pace {
    const own = units.filter((unit) => unit.cefrLevel === level);
    const left = own.reduce(
        (sum, unit) => sum + (unit.lessonCount - unit.lessonsCompleted),
        0,
    );
    const end = new Date(`${deadline}T00:00:00`);
    const start = new Date(
        today.getFullYear(),
        today.getMonth(),
        today.getDate(),
    );
    const days = Math.round((end.getTime() - start.getTime()) / MS_PER_DAY) + 1;
    const perDay = days > 0 ? Math.round((left / days) * 10) / 10 : left;

    if (left === 0) {
        return { left, days: Math.max(days, 0), perDay: 0, status: 'done' };
    }

    if (days <= 0) {
        return { left, days: 0, perDay, status: 'late' };
    }

    return {
        left,
        days,
        perDay,
        status: perDay <= 2 ? 'comfortable' : perDay <= 3 ? 'steady' : 'behind',
    };
}
