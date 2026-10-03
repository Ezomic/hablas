<?php

declare(strict_types=1);

namespace App\Speech;

interface AudioEncoder
{
    /**
     * @param  list<string>  $wavs
     * @return list<EncodedAudio|null> the encoded clip per WAV in order, null where encoding failed
     */
    public function encode(array $wavs): array;
}
