<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingPeople implements UnitContent
{
    private const A_NOTE = 'Written a is the article "the"; à (to the) and há (there is) are different words with a more open vowel.';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'describing-people';
    }

    public function words(): array
    {
        return [
            new WordData('alto', cue: 'tall (masculine)', forms: ['alta', 'altos', 'altas']),
            new WordData('baixo', cue: 'short, not tall (masculine)', forms: ['baixa', 'baixos', 'baixas'], portunolSlips: ['bajo', 'baja']),
            new WordData('simpático', cue: 'nice, friendly (masculine)', forms: ['simpática', 'simpáticos', 'simpáticas']),
            new WordData('jovem', cue: 'young', forms: ['jovens'], portunolSlips: ['joven', 'jóvenes'], note: 'Jovem has the same form for men and women. The plural is jovens.'),
            new WordData('louro', cue: 'blond (masculine)', forms: ['loura', 'louros', 'louras', 'loiro', 'loira', 'loiros', 'loiras'], portunolSlips: ['rubio', 'rubia'], note: 'Louro and loiro are both correct in Portugal.'),
            new WordData('moreno', cue: 'dark-haired (masculine)', forms: ['morena', 'morenos', 'morenas'], note: 'Moreno covers dark brown or black hair, and also a dark complexion.'),
            new WordData('bonito', cue: 'good-looking (masculine)', forms: ['bonita', 'bonitos', 'bonitas'], portunolSlips: ['guapo', 'guapa'], note: 'In Portugal giro (feminine gira) is also very common.'),
            new WordData('o cabelo', cue: 'hair', portunolSlips: ['el pelo'], note: 'O cabelo is masculine and singular: o cabelo louro.'),
            new WordData('o olho', cue: 'eye', forms: ['olhos'], portunolSlips: ['el ojo', 'ojos']),
            new WordData('a pessoa', cue: 'person', forms: ['pessoas'], portunolSlips: ['persona'], note: 'A pessoa is always feminine, also for a man: O Rui é uma pessoa simpática.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'A Ana é alta e tem o cabelo louro.', 'english' => 'Ana is tall and has blond hair.'],
            ['text' => 'O João é simpático e está bem.', 'english' => 'João is nice and he is fine.'],
        ];
    }

    public function exercises(): array
    {
        return [
            ...$this->sentences(),
            ...$this->task(),
            ...$this->checkA(),
            ...$this->checkB(),
        ];
    }

    public function reviews(): array
    {
        return [
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.ana-alta', 'A Ana é ___.', ['alta', 'alto', 'altas'], 'alta', Kit::word('alto', 'alta'), 'Ana is one woman, so the adjective ends in -a and stays singular: alta.', 'choose', 'Ana is tall.'),
            Kit::gap($stage, 'sentences.choose_gap.rui-baixo', 'O Rui é ___.', ['baixo', 'baixa', 'baixos'], 'baixo', Kit::word('baixo'), 'Rui is one man, so the adjective ends in -o and stays singular: baixo.', 'choose', 'Rui is short.'),
            Kit::gap($stage, 'sentences.choose_gap.joao-cabelo', 'O João ___ o cabelo preto.', ['tem', 'é'], 'tem', Kit::form('tem', true), 'You have hair and eyes, so Portuguese uses ter: tem. É says what someone is, not what they have.', 'choose', 'João has black hair.', ['preto' => 'black']),
            Kit::gap($stage, 'sentences.choose_gap.ana-olhos', 'A Ana tem os ___ azuis.', ['olhos', 'olho'], 'olhos', Kit::word('o olho', 'olhos'), 'Os is plural, so the noun is plural too: olhos.', 'choose', 'Ana has blue eyes.', ['azuis' => 'blue']),
            Kit::gap($stage, 'sentences.choose_gap.marta-pessoa', 'A Marta é uma ___ simpática.', ['pessoa', 'cabelo', 'olhos'], 'pessoa', Kit::word('a pessoa', 'pessoa'), 'Uma goes with a feminine singular noun, and a person is pessoa. Cabelo is masculine and olhos is plural.', 'choose', 'Marta is a nice person.'),
            Kit::gap($stage, 'sentences.choose_gap.joao-bem', 'O João ___ bem.', ['está', 'é'], 'está', Kit::form('está', true), 'Está says how someone is at the moment. É bem is not how Portuguese says it.', 'choose', 'João is fine.'),

            Kit::typeGap($stage, 'sentences.type_gap.rui-alto', 'O Rui ___ alto.', 'Rui is a tall man.', 'é', Kit::form('é'), 'Height is a lasting trait, so you use é.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-cabelo', 'A Marta ___ o cabelo louro.', 'Marta has blond hair.', 'tem', Kit::form('tem'), 'Hair is something you have, so you use tem.'),
            Kit::typeGap($stage, 'sentences.type_gap.rui-louro', 'O Rui tem o cabelo ___.', 'Rui has dark hair.', 'moreno', Kit::word('moreno')),
            Kit::typeGap($stage, 'sentences.type_gap.joao-rui-altos', 'O João e o Rui ___ altos.', 'João and Rui are tall men.', 'são', Kit::form('são'), 'Two people need the plural of ser: são. Altos is plural as well.'),
            Kit::typeGap($stage, 'sentences.type_gap.ana-bonita', 'A Ana é muito ___.', 'Ana is very good-looking.', 'bonita', Kit::word('bonito', 'bonita')),

            Kit::translate($stage, 'sentences.translate.ana-alta-loura', 'Ana is tall and blond.', ['A Ana é alta e loura.', 'A Ana é loura e alta.', 'Ana é alta e loura.', 'Ana é loura e alta.', 'A Ana é alta e loira.', 'A Ana é loira e alta.', 'Ana é alta e loira.', 'Ana é loira e alta.'], [Kit::word('alto', 'alta'), Kit::word('louro', 'loura', ['loira']), Kit::form('é')]),
            Kit::translate($stage, 'sentences.translate.rui-moreno', 'Rui is dark-haired.', ['O Rui é moreno.', 'Rui é moreno.', 'O Rui tem o cabelo moreno.', 'O Rui tem cabelo moreno.', 'Rui tem o cabelo moreno.', 'Rui tem cabelo moreno.'], [Kit::word('moreno'), Kit::form('é', alternates: ['tem'])]),
            Kit::translate($stage, 'sentences.translate.marta-jovem', 'Marta is young and nice.', ['A Marta é jovem e simpática.', 'A Marta é simpática e jovem.', 'Marta é jovem e simpática.', 'Marta é simpática e jovem.'], [Kit::word('jovem'), Kit::word('simpático', 'simpática'), Kit::form('é')]),

            Kit::build($stage, 'sentences.build.ana-olhos', 'Ana has blue eyes.', 'A Ana tem os olhos azuis.', ['é'], [Kit::word('o olho', 'olhos'), Kit::form('tem')], 'write', ['azuis' => 'blue']),
            Kit::build($stage, 'sentences.build.ana-muito-alta', 'Ana is very tall.', 'A Ana é muito alta.', ['alto'], [Kit::word('alto', 'alta'), Kit::form('é')]),
            Kit::build($stage, 'sentences.build.rui-cabelo', 'Ana has dark hair.', 'A Ana tem o cabelo moreno.', ['olhos'], [Kit::word('o cabelo', 'cabelo'), Kit::word('moreno'), Kit::form('tem')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.ana-alta-morena', 'A Ana é alta e morena.', ['Ana is tall and dark-haired.', 'Ana is short and dark-haired.', 'Ana is tall and blond.', 'Ana is young and dark-haired.'], 'Ana is tall and dark-haired.', [Kit::word('alto', 'alta'), Kit::word('moreno', 'morena'), Kit::form('é')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.rui-cabelo', 'A Marta tem o cabelo louro.', ['Marta has blond hair.', 'Marta has dark hair.', 'Ana has blond hair.', 'Marta has blond eyes.'], 'Marta has blond hair.', [Kit::word('o cabelo', 'cabelo'), Kit::word('louro'), Kit::form('tem')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.joao-jovem', 'O João é jovem e bonito.', ['João is young and good-looking.', 'João is old and good-looking.', 'João is young and tall.', 'João is nice and good-looking.'], 'João is young and good-looking.', [Kit::word('jovem'), Kit::word('bonito'), Kit::form('é')]),
            Kit::listenType($stage, 'sentences.listen_type.rui-olhos', 'O Rui tem os olhos da Ana.', 'Rui has Ana\'s eyes.', [Kit::word('o olho', 'olhos'), Kit::form('tem')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-pessoa', 'Ana é uma pessoa muito simpática.', 'Ana is a very nice person.', [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática'), Kit::form('é')]),
            Kit::listenType($stage, 'sentences.listen_type.joao-baixo', 'O João não é baixo, é alto.', 'João is not short, he is tall.', [Kit::word('baixo'), Kit::word('alto'), Kit::form('é')]),
            Kit::listenType($stage, 'sentences.listen_type.marta-ana-jovens', 'Marta e Ana são jovens.', 'Marta and Ana are young.', [Kit::word('jovem', 'jovens'), Kit::form('são')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.marta-alta-bonita', 'A Marta é alta e bonita.', 'Marta is tall and good-looking.', [Kit::word('alto', 'alta'), Kit::word('bonito', 'bonita'), Kit::form('é')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.joao-cabelo', 'O João tem o cabelo moreno.', 'João has dark hair.', [Kit::word('o cabelo', 'cabelo'), Kit::word('moreno'), Kit::form('tem')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.marta-olhos', 'A Marta tem os olhos do João.', 'Marta has João\'s eyes.', [Kit::word('o olho', 'olhos'), Kit::form('tem')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.joao-pessoa', 'O João é uma pessoa simpática.', 'João is a nice person.', [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática'), Kit::form('é')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.es-alto', 'És alto?', 'Are you tall?', [['sim', 'não'], ['alto', 'alta', 'baixo', 'baixa']], 'Sim, sou alto.', [Kit::word('alto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-e-ana', 'Como é a Ana?', 'What is Ana like?', [['é', 'tem'], ['alta', 'baixa', 'loura', 'morena', 'bonita', 'jovem', 'simpática', 'cabelo']], 'É alta e loura.', [Kit::word('louro', 'loura'), Kit::form('é')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cabelo-louro', 'Tens o cabelo louro?', 'Do you have blond hair?', [['sim', 'não'], ['louro', 'loura', 'loiro', 'loira', 'moreno', 'morena']], 'Sim, tenho o cabelo louro.', [Kit::word('o cabelo', 'cabelo'), Kit::word('louro')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.como-e-o-joao', 'Read the conversation about João and Rui.', [
                Kit::line('Marta', 'Ana, como é o João?'),
                Kit::line('Ana', 'É alto e moreno. Tem os olhos azuis.'),
                Kit::line('Marta', 'É simpático?'),
                Kit::line('Ana', 'Sim, é uma pessoa muito simpática.'),
                Kit::line('Marta', 'E o Rui?'),
                Kit::line('Ana', 'O Rui é baixo e louro. É jovem e muito bonito.'),
            ], [
                Kit::question('What does João look like?', ['Tall and dark-haired', 'Short and blond', 'Tall and blond'], 'Tall and dark-haired'),
                Kit::question('Is João nice?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Who is blond?', ['Rui', 'João', 'Both of them'], 'Rui'),
            ], [Kit::word('alto'), Kit::word('moreno'), Kit::word('o olho', 'olhos'), Kit::word('simpático'), Kit::word('a pessoa', 'pessoa'), Kit::word('baixo'), Kit::word('louro'), Kit::word('jovem'), Kit::word('bonito')], 'read', null, ['azuis' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.como-e-o-joao', 'O João ___ uma pessoa simpática.', ['é', 'está'], 'é', Kit::form('é', true), 'A nice person is what he is like, a lasting trait, so you use é. Está does not go with a noun like pessoa.', 'read', 'João is a nice person.'),
            Kit::gap($stage, 'task.choose_gap.ana-louro', 'A Ana tem o cabelo ___ e é muito bonita.', ['louro', 'loura'], 'louro', Kit::word('louro'), 'O cabelo is masculine, so the colour is louro. Loura would describe Ana herself: A Ana é loura.', 'read', 'Ana has blond hair and is very good-looking.'),

            Kit::transform($stage, 'task.transform.marta-baixa-morena', 'Change it to talk about Marta.', 'O João é baixo e moreno.', ['A Marta é baixa e morena.', 'A Marta é morena e baixa.', 'Marta é baixa e morena.', 'Marta é morena e baixa.'], [Kit::word('baixo', 'baixa'), Kit::word('moreno', 'morena'), Kit::form('é')]),
            Kit::transform($stage, 'task.transform.jovens-simpaticos', 'Change it to talk about Rui and João.', 'A Ana é jovem e simpática.', ['O Rui e o João são jovens e simpáticos.', 'O João e o Rui são jovens e simpáticos.', 'O Rui e o João são simpáticos e jovens.', 'O João e o Rui são simpáticos e jovens.', 'Rui e João são jovens e simpáticos.', 'João e Rui são jovens e simpáticos.', 'Rui e João são simpáticos e jovens.', 'João e Rui são simpáticos e jovens.'], [Kit::word('jovem', 'jovens'), Kit::word('simpático', 'simpáticos'), Kit::form('são')]),
            Kit::transform($stage, 'task.transform.ana-cabelo', 'Say that Ana has blond hair.', 'A Ana é loura.', ['A Ana tem o cabelo louro.', 'A Ana tem cabelo louro.', 'Ana tem o cabelo louro.', 'Ana tem cabelo louro.', 'A Ana tem o cabelo loiro.', 'A Ana tem cabelo loiro.', 'Ana tem o cabelo loiro.', 'Ana tem cabelo loiro.'], [Kit::word('o cabelo', 'cabelo'), Kit::word('louro', null, ['loiro']), Kit::form('tem', true)]),
            Kit::writeGuided($stage, 'task.write_guided.marta-alta', 'Say that Marta is tall and has blond hair.', ['é', 'alta', 'tem', 'cabelo', 'louro'], 'A Marta é alta e tem o cabelo louro.', [
                ['forms' => ['é'], 'term' => null],
                ['forms' => ['alta'], 'term' => 'alto'],
                ['forms' => ['cabelo'], 'term' => 'o cabelo'],
                ['forms' => ['louro'], 'term' => 'louro'],
            ], [Kit::word('alto', 'alta'), Kit::word('o cabelo', 'cabelo'), Kit::word('louro')]),
            Kit::writeGuided($stage, 'task.write_guided.joao-olhos', 'Say that João is a nice person and has blue eyes.', ['é', 'pessoa', 'simpática', 'tem', 'olhos', 'azuis'], 'O João é uma pessoa simpática e tem os olhos azuis.', [
                ['forms' => ['é'], 'term' => null],
                ['forms' => ['pessoa'], 'term' => 'a pessoa'],
                ['forms' => ['simpática'], 'term' => 'simpático'],
                ['forms' => ['olhos'], 'term' => 'o olho'],
            ], [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática'), Kit::word('o olho', 'olhos')], ['azuis' => 'blue']),
            Kit::build($stage, 'task.build.joao-nao-alto', 'João is not tall.', 'O João não é alto.', ['tem', 'alta'], [Kit::word('alto'), Kit::form('é')], 'write'),
            Kit::build($stage, 'task.build.rui-baixo-ana-alta', 'Rui is short, but Ana is tall.', 'O Rui é baixo, mas a Ana é alta.', ['tem', 'olhos'], [Kit::word('baixo'), Kit::word('alto', 'alta'), Kit::form('é')], 'write'),
            Kit::build($stage, 'task.build.rui-moreno', 'Rui has dark hair.', 'O Rui tem o cabelo moreno.', ['morena', 'olhos'], [Kit::word('moreno'), Kit::form('tem')], 'write'),
            Kit::translate($stage, 'task.translate.ana-jovem-nao-loura', 'Ana is young, but she is not blond.', ['A Ana é jovem, mas não é loura.', 'Ana é jovem, mas não é loura.', 'A Ana é jovem, mas não é loira.', 'Ana é jovem, mas não é loira.'], [Kit::word('jovem'), Kit::word('louro', 'loura', ['loira']), Kit::form('é')], 'write'),
            Kit::translate($stage, 'task.translate.joao-alto-baixo', 'Is João tall or short?', ['O João é alto ou baixo?', 'João é alto ou baixo?', 'É alto ou baixo, o João?'], [Kit::word('alto'), Kit::word('baixo'), Kit::form('é')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.como-e-ana', [
                Kit::line('Rui', 'Marta, como é a Ana?'),
                Kit::line('Marta', 'É jovem e muito bonita. Tem o cabelo louro.'),
                Kit::line('Rui', 'É alta?'),
                Kit::line('Marta', 'Não, é baixa. Mas é uma pessoa muito simpática.'),
            ], [
                Kit::question('What does Ana look like?', ['Young, good-looking and blond', 'Tall and dark-haired', 'Short and dark-haired'], 'Young, good-looking and blond'),
                Kit::question('Is Ana tall?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What is Ana like as a person?', ['Nice', 'Not nice', 'The conversation does not say.'], 'Nice'),
            ], [
                Kit::question('Who asks about Ana?', ['Rui', 'Marta', 'Nobody'], 'Rui'),
                Kit::question('Does Ana have blond hair?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('jovem'), Kit::word('bonito', 'bonita'), Kit::word('o cabelo', 'cabelo'), Kit::word('louro'), Kit::word('alto', 'alta'), Kit::word('baixo', 'baixa'), Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-cabelo-olhos', 'A Ana tem o cabelo louro e os olhos do Rui.', 'Ana has blond hair and Rui\'s eyes.', [Kit::word('o cabelo', 'cabelo'), Kit::word('louro'), Kit::word('o olho', 'olhos'), Kit::form('tem')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.rui-jovem-bonito', 'O Rui é jovem, moreno e muito bonito.', 'Rui is young, dark-haired and very good-looking.', [Kit::word('jovem'), Kit::word('moreno'), Kit::word('bonito'), Kit::form('é')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-esta-bem', 'A Ana está bem, mas é muito baixa.', 'Ana is fine, but she is very short.', [Kit::word('baixo', 'baixa'), Kit::form('está', true)], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.como-es', 'Como és?', 'What are you like?', [['sou', 'tenho'], ['alto', 'alta', 'baixo', 'baixa', 'jovem', 'louro', 'loura', 'loiro', 'loira', 'moreno', 'morena', 'simpático', 'simpática', 'bonito', 'bonita', 'cabelo', 'olhos']], 'Sou alto e simpático.', [Kit::word('alto'), Kit::word('simpático')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cabelo-moreno', 'Tens o cabelo moreno?', 'Do you have dark hair?', [['sim', 'não'], ['moreno', 'morena', 'louro', 'loura']], 'Sim, tenho o cabelo moreno.', [Kit::word('o cabelo', 'cabelo'), Kit::word('moreno')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marta-alta', 'A Marta é alta?', 'Is Marta tall?', [['sim', 'não'], ['alta', 'baixa']], 'Não, é baixa.', [Kit::word('baixo', 'baixa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.es-jovem', 'És jovem?', 'Are you young?', [['sim', 'não'], ['jovem']], 'Sim, sou jovem.', [Kit::word('jovem')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.rui-baixo-cabelo', 'O Rui é baixo e tem o cabelo moreno.', 'Rui is short and has dark hair.', [Kit::word('baixo'), Kit::word('moreno'), Kit::form('tem')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.marta-jovem-bonita', 'A Marta é jovem e muito bonita.', 'Marta is young and very good-looking.', [Kit::word('jovem'), Kit::word('bonito', 'bonita'), Kit::form('é')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.ana-alta-bonita', 'Ana is tall and good-looking.', ['A Ana é alta e bonita.', 'A Ana é bonita e alta.', 'Ana é alta e bonita.', 'Ana é bonita e alta.'], [Kit::word('alto', 'alta'), Kit::word('bonito', 'bonita'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.joao-cabelo', 'João is tall and has blond hair.', ['O João é alto e tem o cabelo louro.', 'O João é alto e tem cabelo louro.', 'João é alto e tem o cabelo louro.', 'João é alto e tem cabelo louro.', 'O João é alto e tem o cabelo loiro.', 'O João é alto e tem cabelo loiro.', 'João é alto e tem o cabelo loiro.', 'João é alto e tem cabelo loiro.'], [Kit::word('alto'), Kit::word('o cabelo', 'cabelo'), Kit::word('louro', null, ['loiro']), Kit::form('tem')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.rui-pessoa', 'Rui is a young and nice person.', ['O Rui é uma pessoa jovem e simpática.', 'O Rui é uma pessoa simpática e jovem.', 'Rui é uma pessoa jovem e simpática.', 'Rui é uma pessoa simpática e jovem.'], [Kit::word('a pessoa', 'pessoa'), Kit::word('jovem'), Kit::word('simpático', 'simpática'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-bem-baixa', 'Marta is fine, but she is short.', ['A Marta está bem, mas é baixa.', 'Marta está bem, mas é baixa.'], [Kit::word('baixo', 'baixa'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.joao-rui-baixos', 'O João e o Rui são ___.', 'João and Rui are short.', 'baixos', Kit::word('baixo', 'baixos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-olhos', 'A Ana tem os ___ do Rui.', 'Ana has Rui\'s eyes.', 'olhos', Kit::word('o olho', 'olhos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-alta-simpatica', 'A Marta é alta e muito simpática.', 'Marta is tall and very nice.', [Kit::word('alto', 'alta'), Kit::word('simpático', 'simpática'), Kit::form('é')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.joao-jovem-cabelo', 'O João é jovem e tem o cabelo moreno.', 'João is young and has dark hair.', [Kit::word('jovem'), Kit::word('moreno'), Kit::word('o cabelo', 'cabelo'), Kit::form('tem', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ana-baixa-bonita', 'A Ana é baixa e muito bonita.', 'Ana is short and very good-looking.', [Kit::word('baixo', 'baixa'), Kit::word('bonito', 'bonita')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.como-e-marta', [
                Kit::line('Ana', 'João, como é a Marta?'),
                Kit::line('João', 'É jovem e bonita. Tem os olhos do Rui.'),
                Kit::line('Ana', 'É loura?'),
                Kit::line('João', 'Não, é morena.'),
            ], [
                Kit::question('What is Marta like?', ['Young and good-looking', 'Short and nice', 'Tall and blond'], 'Young and good-looking'),
                Kit::question('Whose eyes does Marta have?', ['Rui\'s', 'João\'s', 'Ana\'s'], 'Rui\'s'),
                Kit::question('Is Marta blond?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('Who asks about Marta?', ['Ana', 'João', 'Nobody'], 'Ana'),
                Kit::question('Is Marta young?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('jovem'), Kit::word('bonito', 'bonita'), Kit::word('o olho', 'olhos'), Kit::word('louro', 'loura'), Kit::word('moreno', 'morena')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.como-e-o-rui', 'Read the conversation.', [
                Kit::line('Marta', 'Ana, como é o Rui?'),
                Kit::line('Ana', 'É baixo, mas é uma pessoa muito simpática. Tem os olhos do João.'),
                Kit::line('Marta', 'E o João?'),
                Kit::line('Ana', 'O João é alto e louro.'),
            ], [
                Kit::question('What is Rui like?', ['Short and nice', 'Tall and nice', 'Short and blond'], 'Short and nice'),
                Kit::question('Who is blond?', ['João', 'Rui', 'Nobody'], 'João'),
            ], [Kit::word('baixo'), Kit::word('a pessoa', 'pessoa'), Kit::word('simpático'), Kit::word('o olho', 'olhos'), Kit::word('alto'), Kit::word('louro')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.es-louro', 'És louro?', 'Are you blond?', [['sim', 'não'], ['louro', 'loura', 'loiro', 'loira', 'moreno', 'morena']], 'Não, sou moreno.', [Kit::word('louro'), Kit::word('moreno')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cabelo-louro-moreno', 'Tens o cabelo louro ou moreno?', 'Do you have blond or dark hair?', [['tenho', 'sou'], ['louro', 'loura', 'loiro', 'loira', 'moreno', 'morena']], 'Tenho o cabelo moreno.', [Kit::word('o cabelo', 'cabelo'), Kit::word('louro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pessoa-simpatica', 'És uma pessoa simpática?', 'Are you a nice person?', [['sim', 'não'], ['sou', 'simpática']], 'Sim, sou uma pessoa simpática.', [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.rui-alto-moreno', 'Rui is tall and dark-haired.', ['O Rui é alto e moreno.', 'O Rui é moreno e alto.', 'Rui é alto e moreno.', 'Rui é moreno e alto.'], [Kit::word('alto'), Kit::word('moreno'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-loura-bonita', 'Marta is blond and good-looking.', ['A Marta é loura e bonita.', 'A Marta é bonita e loura.', 'Marta é loura e bonita.', 'Marta é bonita e loura.', 'A Marta é loira e bonita.', 'A Marta é bonita e loira.', 'Marta é loira e bonita.', 'Marta é bonita e loira.'], [Kit::word('louro', 'loura', ['loira']), Kit::word('bonito', 'bonita'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-jovem-olhos', 'Ana is young and has João\'s eyes.', ['A Ana é jovem e tem os olhos do João.', 'Ana é jovem e tem os olhos do João.'], [Kit::word('jovem'), Kit::word('o olho', 'olhos'), Kit::form('tem')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.rui-bem-pessoa', 'Rui is fine, but he is not a nice person.', ['O Rui está bem, mas não é uma pessoa simpática.', 'Rui está bem, mas não é uma pessoa simpática.'], [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-marta-baixas', 'A Ana e a Marta são ___.', 'Ana and Marta are short.', 'baixas', Kit::word('baixo', 'baixas'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.joao-olhos', 'O João tem os ___ da Marta.', 'João has Marta\'s eyes.', 'olhos', Kit::word('o olho', 'olhos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-pessoa', 'A Marta é uma pessoa simpática e bonita.', 'Marta is a nice and good-looking person.', [Kit::word('a pessoa', 'pessoa'), Kit::word('simpático', 'simpática'), Kit::word('bonito', 'bonita')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.ana-baixa-cabelo', 'A Ana é baixa e tem o cabelo louro.', 'Ana is short and has blond hair.', [Kit::word('baixo', 'baixa'), Kit::word('o cabelo', 'cabelo'), Kit::word('louro'), Kit::form('é')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.rui-jovem-alto', 'O Rui é jovem, alto e tem o cabelo moreno.', 'Rui is young, tall and has dark hair.', [Kit::word('jovem'), Kit::word('alto'), Kit::word('o cabelo', 'cabelo'), Kit::word('moreno'), Kit::form('tem', true)], 'dictation', $set),
        ];
    }
}
