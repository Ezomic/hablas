<?php

declare(strict_types=1);

use App\Speech\CharacterVoices;

it('gives each character the same voice every time, and different characters different ones', function () {
    expect(CharacterVoices::voiceFor('Ana'))->toBe('F1')
        ->and(CharacterVoices::voiceFor('Pablo'))->toBe('M1')
        ->and(CharacterVoices::voiceFor('Luis'))->toBe('M2')
        ->and(CharacterVoices::voiceFor('Marta'))->toBe('F2')
        ->and(CharacterVoices::voiceFor('Alguien'))->toBe(CharacterVoices::voiceFor('Alguien'))
        ->and(CharacterVoices::voiceFor('Alguien'))->toBeIn(['F1', 'F2', 'M1', 'M2']);
});
