<?php

declare(strict_types=1);

use App\Lessons\TileSplitter;

it('splits on whitespace', function () {
    expect(TileSplitter::split('Bonjour, je suis Anne.'))->toBe(['Bonjour,', 'je', 'suis', 'Anne.']);
});

it('keeps spaced French punctuation on the tile before it', function () {
    expect(TileSplitter::split('Bonjour. Ça va ?'))->toBe(['Bonjour.', 'Ça', 'va ?'])
        ->and(TileSplitter::split('Il dit : oui !'))->toBe(['Il', 'dit :', 'oui !']);
});

it('leaves unspaced and Spanish punctuation alone', function () {
    expect(TileSplitter::split('¿Cómo estás?'))->toBe(['¿Cómo', 'estás?'])
        ->and(TileSplitter::split('? Hola'))->toBe(['?', 'Hola']);
});
