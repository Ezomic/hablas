import { describe, expect, it } from 'vitest';
import { explainMistake } from './mistakeExplainer';

describe('explainMistake', () => {
    it('names the two verbs when estar is used for who someone is', () => {
        expect(
            explainMistake('hola estoy ana', 'Hola, soy Ana.', 'es-ES'),
        ).toEqual({
            id: 'serForEstar',
            given: 'estoy',
            expected: 'soy',
            givenVerb: 'estar',
            expectedVerb: 'ser',
        });
    });

    it.each([
        ['La habitación es lista', 'La habitación está lista', 'estarForSer'],
        ['Soy veinte años', 'Tengo veinte años', 'tenerForBe'],
        ['Estoy veinte años', 'Tengo veinte años', 'tenerForBe'],
        ['Tengo Ana', 'Soy Ana', 'beForTener'],
        [
            'La mesa está en la cocina',
            'Hay una mesa en la cocina',
            'hayForEstar',
        ],
        ['Hay la mesa aquí', 'La mesa está aquí', 'estarForHay'],
    ])('explains %s against %s', (given, expected, id) => {
        expect(explainMistake(given, expected, 'es-ES')?.id).toBe(id);
    });

    it('works for a single chosen word', () => {
        expect(explainMistake('estoy', 'soy', 'es-ES')?.id).toBe('serForEstar');
    });

    it('is not fooled by accents, case or punctuation', () => {
        expect(explainMistake('¿Cómo ESTAN?', '¿Cómo son?', 'es-ES')?.id).toBe(
            'serForEstar',
        );
    });

    it('says nothing when the mistake is not a mix-up of those verbs', () => {
        expect(
            explainMistake('hola soy luis', 'Hola, soy Ana.', 'es-ES'),
        ).toBeNull();
        expect(explainMistake('soy', 'es', 'es-ES')).toBeNull();
        expect(
            explainMistake('Tengo el libro', 'Tengo la libro', 'es-ES'),
        ).toBeNull();
    });

    it('only explains the verbs of the language being learned', () => {
        expect(explainMistake('estoy', 'soy', 'fr-FR')).toBeNull();
        expect(explainMistake('estoy', 'soy', null)).toBeNull();
    });

    it('knows the Portuguese forms', () => {
        expect(explainMistake('Estou Ana', 'Sou Ana', 'pt-PT')?.id).toBe(
            'serForEstar',
        );
        expect(explainMistake('Tenho sou', 'Sou', 'pt-PT')).toBeNull();
    });
});
