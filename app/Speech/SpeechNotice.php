<?php

declare(strict_types=1);

namespace App\Speech;

final class SpeechNotice
{
    public function __construct(private readonly SpeechCredits $credits) {}

    public function handle(): string
    {
        $lines = [
            'Hablas',
            '',
            'Spoken audio in Hablas is machine generated. It is produced offline with the text-to-speech',
            'models listed below and served as pre-rendered audio clips.',
        ];

        foreach ($this->credits->handle() as $credit) {
            $lines[] = '';
            $lines[] = $credit['attribution'];
            $lines[] = 'Engine: '.$credit['engine'];
            $lines[] = 'Voices: '.$this->voiceList($credit['voices']);
            $lines[] = 'Source: '.$credit['sourceUrl'];
            $lines[] = 'Licence: '.$credit['license'].' ('.$credit['licenseUrl'].')';

            if ($credit['restrictions'] !== []) {
                $lines[] = '';
                $lines[] = 'Use-based restrictions of this licence, which apply to anyone using the model or its output:';

                foreach ($credit['restrictions'] as $restriction) {
                    $lines[] = '- '.$restriction;
                }
            }
        }

        return implode("\n", $lines)."\n";
    }

    /** @param array<string, list<string>> $voices */
    private function voiceList(array $voices): string
    {
        $parts = [];

        foreach ($voices as $language => $names) {
            $parts[] = $language.' ('.implode(', ', $names).')';
        }

        return implode(', ', $parts);
    }
}
