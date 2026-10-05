<?php

declare(strict_types=1);

namespace App\Lessons;

final class TileSplitter
{
    /**
     * Splits a sentence into tiles on whitespace, keeping the punctuation that
     * French sets off with a space (Ça va ?) on the tile it belongs to.
     *
     * @return list<string>
     */
    public static function split(string $text): array
    {
        $tiles = [];

        foreach (preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [] as $token) {
            if ($tiles !== [] && preg_match('/^[?!:;]+$/u', $token) === 1) {
                $tiles[count($tiles) - 1] .= ' '.$token;

                continue;
            }

            $tiles[] = $token;
        }

        return $tiles;
    }
}
