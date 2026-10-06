export type UnitAvailability =
    'completed' | 'in_progress' | 'available' | 'held_back' | 'locked';

export interface LibraryUnit {
    id: number;
    title: string;
    taskDescription: string;
    cefrLevel: string;
    primarySkill: string;
    availability: UnitAvailability;
    lessonCount: number;
    lessonsCompleted: number;
    masteredCount: number;
    percent: number;
    stars: number;
}

export interface UnitProgress {
    percent: number;
    known: number;
    total: number;
    stars: number;
    words: { id: number; state: string }[];
    level: { code: string; known: number; total: number; percent: number };
}

export interface DayProgress {
    words: number;
    goal: number;
    streak: number;
    due: number;
}
