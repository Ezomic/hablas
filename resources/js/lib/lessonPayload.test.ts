import { describe, expect, it } from 'vitest';
import {
    englishLine,
    glossesOf,
    isPassageFormat,
    isTypedFormat,
    passageAnswers,
    passageLines,
    passageQuestions,
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
