import { describe, expect, it } from 'vitest';
import { setLocale } from '@/i18n';
import {
    englishLine,
    glossesOf,
    hintFor,
    instructionFor,
    languageName,
    isPassageFormat,
    isTypedFormat,
    passageAnswers,
    passageLines,
    passageQuestions,
    sameLetters,
} from './lessonPayload';

describe('englishLine', () => {
    it('is the English text unless it only repeats the prompt', () => {
        expect(englishLine({ prompt: 'La llave', english: 'The key' })).toBe(
            'The key',
        );
        expect(englishLine({ prompt: 'key', english: 'key' })).toBe('');
        expect(englishLine({ prompt: 'key', english: '' })).toBe('');
        expect(englishLine({ prompt: 'key' })).toBe('');
    });
});

describe('glossesOf', () => {
    it('lists the word and meaning pairs, ignoring anything malformed', () => {
        expect(glossesOf({ glosses: { llave: 'key', x: 3, y: '' } })).toEqual([
            ['llave', 'key'],
        ]);
        expect(glossesOf({})).toEqual([]);
        expect(glossesOf({ glosses: null })).toEqual([]);
    });
});

describe('passages', () => {
    const questions = [
        { prompt: 'Who?', options: ['Ana', 'Luis'], answer: 'Ana' },
        { prompt: 'Where?', options: ['hotel', 'bar'] },
    ];

    it('reads the dialogue of a read passage and the lines of a listen passage', () => {
        expect(
            passageLines({ dialogue: [{ speaker: 'Ana', text: 'Hola.' }] }),
        ).toEqual([
            {
                speaker: 'Ana',
                text: 'Hola.',
                audioUrl: null,
                audioSlowUrl: null,
            },
        ]);
        expect(
            passageLines({
                lines: [
                    { speaker: 'Ana', audioUrl: '/a.mp3', audioSlowUrl: '' },
                ],
            }),
        ).toEqual([
            {
                speaker: 'Ana',
                text: '',
                audioUrl: '/a.mp3',
                audioSlowUrl: null,
            },
        ]);
    });

    it('reads the questions, with no answer where the check strips it', () => {
        expect(passageQuestions({ questions })).toEqual([
            { prompt: 'Who?', options: ['Ana', 'Luis'], answer: 'Ana' },
            { prompt: 'Where?', options: ['hotel', 'bar'], answer: null },
        ]);
    });

    it('knows the answers only when every question has one', () => {
        expect(passageAnswers({ questions })).toBeNull();
        expect(
            passageAnswers({
                questions: questions.map((question) => ({
                    ...question,
                    answer: 'Ana',
                })),
            }),
        ).toEqual(['Ana', 'Ana']);
        expect(passageAnswers({ questions: [] })).toBeNull();
    });

    it('recognises the formats', () => {
        expect(isPassageFormat('read_passage')).toBe(true);
        expect(isPassageFormat('listen_passage')).toBe(true);
        expect(isPassageFormat('listen_type')).toBe(false);
        expect(isTypedFormat('transform_sentence')).toBe(true);
        expect(isTypedFormat('write_guided')).toBe(true);
        expect(isTypedFormat('build_sentence')).toBe(false);
    });
});

describe('the instruction and hint text', () => {
    const exercise = (accepted: string[]) => ({
        id: 1,
        key: 'k',
        block: 'b',
        format: 'type_word',
        payload: { accepted },
    });

    it('names the language and each instruction in English', () => {
        expect(languageName('es-ES')).toBe('Spanish');
        expect(languageName('pt-PT')).toBe('Portuguese');
        expect(languageName('fr-FR')).toBe('French');
        expect(languageName('it-IT')).toBe('Italian');
        expect(languageName(null)).toBe('Spanish');
        expect(instructionFor('choose_meaning', 'Spanish')).toBe(
            'Choose the meaning',
        );
        expect(instructionFor('choose_word', 'Spanish')).toBe(
            'Choose the Spanish word',
        );
        expect(instructionFor('choose_gap', 'Spanish')).toBe(
            'Choose the word that fits',
        );
        expect(instructionFor('match_pairs', 'Spanish')).toBe(
            'Match each word with its meaning',
        );
        expect(instructionFor('type_word', 'Portuguese')).toBe(
            'Type it in Portuguese',
        );
        expect(instructionFor('type_gap', 'Spanish')).toBe(
            'Type the missing word',
        );
        expect(instructionFor('translate_sentence', 'Spanish')).toBe(
            'Translate it into Spanish',
        );
        expect(instructionFor('other', 'Spanish')).toBe('');
    });

    it('gives the hint in English, with the plural kept as it was for one letter', () => {
        expect(hintFor(exercise(['la llave']))).toBe(
            'Starts with "l", 7 letters',
        );
        expect(hintFor(exercise(['a']))).toBe('Starts with "a", 1 letters');
        expect(hintFor(exercise([]))).toBe('');
    });

    it('gives each instruction and the hint in Dutch', () => {
        setLocale('nl');

        expect(languageName('es-ES')).toBe('Spaans');
        expect(languageName('pt-PT')).toBe('Portugees');
        expect(instructionFor('choose_meaning', 'Spaans')).toBe(
            'Kies de betekenis',
        );
        expect(instructionFor('choose_word', 'Spaans')).toBe(
            'Kies het woord in het Spaans',
        );
        expect(instructionFor('choose_gap', 'Spaans')).toBe(
            'Kies het woord dat past',
        );
        expect(instructionFor('match_pairs', 'Spaans')).toBe(
            'Koppel elk woord aan zijn betekenis',
        );
        expect(instructionFor('type_word', 'Portugees')).toBe(
            'Typ het in het Portugees',
        );
        expect(instructionFor('type_gap', 'Spaans')).toBe(
            'Typ het ontbrekende woord',
        );
        expect(instructionFor('translate_sentence', 'Spaans')).toBe(
            'Vertaal het naar het Spaans',
        );
        expect(hintFor(exercise(['la llave']))).toBe(
            'Begint met "l", 7 letters',
        );
        expect(hintFor(exercise(['a']))).toBe('Begint met "a", 1 letter');
    });
});

describe('sameLetters', () => {
    it('forgives accents, case, spaces and punctuation when a word is copied', () => {
        expect(sameLetters('como estas?', '¿cómo estás?')).toBe(true);
        expect(sameLetters('  Como   estas ', '¿cómo estás?')).toBe(true);
        expect(sameLetters('¿Cómo estás?', '¿cómo estás?')).toBe(true);
        expect(sameLetters('como esta', '¿cómo estás?')).toBe(false);
    });
});
