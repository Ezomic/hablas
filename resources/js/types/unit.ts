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
}
