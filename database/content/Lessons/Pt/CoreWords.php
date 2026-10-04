<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

/**
 * The A1 function words and forms every unit may use without teaching them:
 * articles, pronouns (tu and o senhor, never the singular você), the common
 * forms of ser, estar, ter, haver and ir, numbers to twenty, question words,
 * small prepositions and their contractions, the set phrases of politeness
 * and four first names. Any other word in a unit's text has to be one of the
 * unit's own words, a form its content declares, or glossed where it appears.
 */
final class CoreWords
{
    /** @return list<string> */
    public static function words(): array
    {
        return [
            'o', 'a', 'os', 'as', 'um', 'uma', 'uns', 'umas',
            'ao', 'à', 'aos', 'às', 'do', 'da', 'dos', 'das', 'no', 'na', 'nos', 'nas', 'num', 'numa',
            'eu', 'tu', 'ele', 'ela', 'nós', 'eles', 'elas', 'vocês', 'senhor', 'senhora',
            'meu', 'minha', 'meus', 'minhas', 'teu', 'tua', 'teus', 'tuas', 'seu', 'sua', 'seus', 'suas',
            'sou', 'és', 'é', 'somos', 'são',
            'estou', 'estás', 'está', 'estamos', 'estão',
            'tenho', 'tens', 'tem', 'temos', 'têm',
            'há',
            'vou', 'vais', 'vai', 'vamos', 'vão',
            'dois', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez',
            'onze', 'doze', 'treze', 'catorze', 'quinze', 'dezasseis', 'dezassete', 'dezoito', 'dezanove', 'vinte',
            'que', 'quê', 'quem', 'onde', 'quando', 'como', 'quanto', 'quanta', 'quantos', 'quantas', 'qual', 'porque', 'porquê',
            'de', 'em', 'com', 'sem', 'para', 'por', 'e', 'ou', 'mas',
            'não', 'sim', 'aqui', 'ali', 'lá', 'muito', 'bem', 'também',
            'olá', 'bom', 'boa', 'dia', 'dias', 'tarde', 'tardes', 'noite', 'obrigado', 'obrigada', 'favor', 'adeus',
            'ana', 'joão', 'marta', 'rui',
        ];
    }
}
