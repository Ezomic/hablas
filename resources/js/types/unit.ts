export type UnitAvailability =
    'completed' | 'available' | 'held_back' | 'locked';

export interface LibraryUnit {
    id: number;
    title: string;
    taskDescription: string;
    cefrLevel: string;
    primarySkill: string;
    availability: UnitAvailability;
}
