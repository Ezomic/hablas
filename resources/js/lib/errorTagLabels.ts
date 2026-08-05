import type { ErrorTag } from '@/types/review';

export const errorTagLabels: Record<ErrorTag, string> = {
    wrong_gender: 'Wrong gender',
    ser_estar_confusion: 'Ser vs estar',
    false_friend: 'False friend',
    wrong_tense: 'Wrong tense',
    portunol_slip: 'Portuñol slip',
    other: 'Something else',
};
