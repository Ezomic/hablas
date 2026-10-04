import { i18n } from '@/i18n';

export const skillKeys = ['reading', 'listening', 'speaking', 'writing'];

export function skillLabel(skill: string): string {
    return skillKeys.includes(skill) ? i18n.global.t(`skills.${skill}`) : skill;
}
