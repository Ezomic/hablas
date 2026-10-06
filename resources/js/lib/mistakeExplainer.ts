export type Verb = 'ser' | 'estar' | 'tener' | 'haber';

export type ContrastId =
    | 'serForEstar'
    | 'estarForSer'
    | 'tenerForBe'
    | 'beForTener'
    | 'hayForEstar'
    | 'estarForHay';

export interface Explanation {
    id: ContrastId;
    given: string;
    expected: string;
    givenVerb: Verb;
    expectedVerb: Verb;
}

// The forms of the verbs that A1 learners mix up most. Accents are left out of
// the keys, because a dropped accent is a different matter from a different verb.
const FORMS: Record<string, Record<string, Verb>> = {
    es: {
        soy: 'ser',
        eres: 'ser',
        es: 'ser',
        somos: 'ser',
        sois: 'ser',
        son: 'ser',
        estoy: 'estar',
        estas: 'estar',
        esta: 'estar',
        estamos: 'estar',
        estais: 'estar',
        estan: 'estar',
        tengo: 'tener',
        tienes: 'tener',
        tiene: 'tener',
        tenemos: 'tener',
        teneis: 'tener',
        tienen: 'tener',
        hay: 'haber',
    },
    pt: {
        sou: 'ser',
        es: 'ser',
        e: 'ser',
        somos: 'ser',
        sao: 'ser',
        estou: 'estar',
        estas: 'estar',
        esta: 'estar',
        estamos: 'estar',
        estao: 'estar',
        tenho: 'tener',
        tens: 'tener',
        tem: 'tener',
        temos: 'tener',
        ha: 'haber',
    },
};

function plain(word: string): string {
    return word
        .toLowerCase()
        .normalize('NFD')
        .replace(/\p{M}/gu, '')
        .replace(/[^\p{L}\p{N}]/gu, '');
}

function wordsOf(text: string): string[] {
    return text
        .split(/\s+/)
        .map((word) => word.replace(/[^\p{L}\p{N}]/gu, ''))
        .filter((word) => word !== '');
}

function contrast(given: Verb, expected: Verb): ContrastId | null {
    switch (`${given}>${expected}`) {
        case 'estar>ser':
            return 'serForEstar';
        case 'ser>estar':
            return 'estarForSer';
        case 'ser>tener':
        case 'estar>tener':
            return 'tenerForBe';
        case 'tener>ser':
        case 'tener>estar':
            return 'beForTener';
        case 'estar>haber':
        case 'ser>haber':
            return 'hayForEstar';
        case 'haber>estar':
        case 'haber>ser':
            return 'estarForHay';
        default:
            return null;
    }
}

/**
 * When the wrong word is a form of one verb and the right one a form of
 * another that learners confuse with it, says which two verbs they are. Only
 * the first such pair is explained, so the feedback stays one short rule.
 */
export function explainMistake(
    given: string,
    expected: string,
    locale: string | null,
): Explanation | null {
    const forms = FORMS[(locale ?? '').slice(0, 2).toLowerCase()];

    if (forms === undefined) {
        return null;
    }

    const typed = wordsOf(given);
    const right = wordsOf(expected);
    const rightKeys = new Set(right.map(plain));
    const typedKeys = new Set(typed.map(plain));

    const wrong = typed.filter((word) => !rightKeys.has(plain(word)));
    const missing = right.filter((word) => !typedKeys.has(plain(word)));

    for (const wrongWord of wrong) {
        const givenVerb = forms[plain(wrongWord)];

        if (givenVerb === undefined) {
            continue;
        }

        for (const missingWord of missing) {
            const expectedVerb = forms[plain(missingWord)];
            const id =
                expectedVerb === undefined || expectedVerb === givenVerb
                    ? null
                    : contrast(givenVerb, expectedVerb);

            if (id !== null && expectedVerb !== undefined) {
                return {
                    id,
                    given: wrongWord,
                    expected: missingWord,
                    givenVerb,
                    expectedVerb,
                };
            }
        }
    }

    return null;
}
