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
