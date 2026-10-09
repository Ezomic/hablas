<?php

declare(strict_types=1);

use App\Enums\ContextTag;
use App\Enums\Skill;

return [
    'slug' => 'polite-requests',
    'title' => 'Polite requests',
    'context_tag' => ContextTag::Professional,
    'primary_skill' => Skill::Speaking,
    'secondary_skill' => Skill::Writing,
    'task_description' => 'Ask politely and say what not to do.',
    'interest_tags' => [],
    'vocabulary' => [
        ['term' => 'el formulario', 'translation_en' => 'form', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'el documento', 'translation_en' => 'document', 'is_cognate' => true, 'part_of_speech' => 'noun'],
        ['term' => 'la factura', 'translation_en' => 'invoice', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'el correo electrónico', 'translation_en' => 'email', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'la ventanilla', 'translation_en' => 'counter / service window', 'is_cognate' => false, 'part_of_speech' => 'noun'],
        ['term' => 'enviar', 'translation_en' => 'to send', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'firmar', 'translation_en' => 'to sign', 'is_cognate' => true, 'part_of_speech' => 'verb'],
        ['term' => 'rellenar', 'translation_en' => 'to fill in', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'escribir', 'translation_en' => 'to write', 'is_cognate' => false, 'part_of_speech' => 'verb'],
        ['term' => 'pasar', 'translation_en' => 'to go through / to come in', 'is_cognate' => false, 'part_of_speech' => 'verb'],
    ],
    'grammar' => [
        [
            'title' => 'Polite requests: the usted command, no + command and podría + infinitive',
            'explanation' => "To ask a stranger or a client to do something, use the usted command. Take the yo form, drop the -o and add the opposite vowel: -ar verbs get -e, -er and -ir verbs get -a. So firmo gives 'Firme aquí' (sign here), relleno gives 'Rellene el formulario', escribo gives 'Escriba su número' and pongo gives 'Ponga'. Enviar needs an accent: 'Envíe la factura'. Dutch uses a question here ('Wilt u hier tekenen?'), but Spanish happily uses the command with por favor. To say what not to do, put no before the same usted form: 'No firme el documento'. For a friend (tú) the negative command swaps the vowel the other way: -ar verbs get -es and -er and -ir verbs get -as, as in 'No hables aquí' and 'No pongas el documento en la mesa'. Never use the plain tú form after no. The politest request is podría + infinitive, like Dutch 'Zou u ... kunnen ...?': '¿Podría enviar la factura?' (Could you send the invoice?). After podría the verb stays in the infinitive. '¿Puede enviar la factura?' is also correct, a little more direct.",
            'error_tag_category' => null,
        ],
    ],
];
