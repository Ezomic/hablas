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

final class DescribingYourHome implements UnitContent
{
    private const HA_NOTE = 'Há (there is) sounds the same as à (to the), and is easy to mix up in writing with a (the). Here it means there is or there are.';

    private const A_NOTE = 'The a here is the article a (the), not à (to the) and not há (there is).';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'describing-your-home';
    }

    public function words(): array
    {
        return [
            new WordData('a casa', cue: 'house (or home)', forms: ['casas']),
            new WordData('o apartamento', cue: 'flat (apartment)', forms: ['apartamentos'], portunolSlips: ['el piso']),
            new WordData('a cozinha', cue: 'kitchen', forms: ['cozinhas'], portunolSlips: ['la cocina']),
            new WordData('a sala', cue: 'living room', forms: ['salas'], portunolSlips: ['el salón'], note: 'A sala is feminine in Portuguese, but el salón is masculine in Spanish: a sala é pequena, not o sala.'),
            new WordData('o quarto', cue: 'bedroom', forms: ['quartos'], portunolSlips: ['el dormitorio']),
            new WordData('o jardim', cue: 'garden', forms: ['jardins'], portunolSlips: ['el jardín']),
            new WordData('a mesa', cue: 'table', forms: ['mesas']),
            new WordData('a cadeira', cue: 'chair', forms: ['cadeiras'], portunolSlips: ['la silla']),
            new WordData('a cama', cue: 'bed', forms: ['camas']),
            new WordData('a janela', cue: 'window', forms: ['janelas'], portunolSlips: ['la ventana']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Há uma mesa na cozinha.', 'english' => 'There is a table in the kitchen.'],
            ['text' => 'A mesa está na cozinha.', 'english' => 'The table is in the kitchen.'],
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
        $s = Stage::Sentences;
        $casa = Kit::word('a casa', 'casa');
        $apartamento = Kit::word('o apartamento', 'apartamento');
        $cozinha = Kit::word('a cozinha', 'cozinha');
        $sala = Kit::word('a sala', 'sala');
        $quarto = Kit::word('o quarto', 'quarto');
        $jardim = Kit::word('o jardim', 'jardim');
        $mesa = Kit::word('a mesa', 'mesa');
        $cadeira = Kit::word('a cadeira', 'cadeira');
        $cama = Kit::word('a cama', 'cama');
        $janela = Kit::word('a janela', 'janela');

        return [
            Kit::gap($s, 'sentences.choose_gap.mesa-esta', 'A mesa ___ na cozinha.', ['está', 'há'], 'está', Kit::form('está', true), 'A mesa is a table you both know, so we say está. Há is for introducing something new.', 'choose', 'The table is in the kitchen.'),
            Kit::gap($s, 'sentences.choose_gap.cama-ha', '___ uma cama no quarto.', ['Há', 'Está'], 'Há', Kit::form('há', true), 'Uma cama is something new, so at this level we say há: there is a bed.', 'choose', 'There is a bed in the bedroom.'),
            Kit::gap($s, 'sentences.choose_gap.cadeira-debaixo', 'A cadeira está ___ da mesa.', ['debaixo', 'no', 'ao'], 'debaixo', Kit::form('debaixo'), 'Debaixo de means under, and it needs de after it. No and ao do not fit before da mesa.', 'choose', 'The chair is under the table.'),
            Kit::gap($s, 'sentences.choose_gap.janela-no', 'Há uma janela ___ quarto.', ['no', 'em cima', 'debaixo'], 'no', Kit::form('no'), 'Em plus o becomes no: no quarto means in the bedroom. Em cima and debaixo need de after them and mean on top of and under.', 'choose', 'There is a window in the bedroom.'),
            Kit::gap($s, 'sentences.choose_gap.jardim', 'Há uma mesa no ___.', ['jardim', 'cozinha', 'cadeira'], 'jardim', $jardim, 'Jardim is masculine, so it goes with no (em + o). Cozinha and cadeira are feminine and go with na.', 'choose', 'There is a table in the garden.'),
            Kit::gap($s, 'sentences.choose_gap.quartos', 'O meu apartamento tem dois ___.', ['quartos', 'quarto'], 'quartos', Kit::word('o quarto', 'quartos'), 'After dois we need the plural, so the word ends in -s.', 'choose', 'My flat has two bedrooms.'),

            Kit::typeGap($s, 'sentences.type_gap.cadeira-no', 'Há uma cadeira ___ jardim.', 'There is a chair in the garden.', 'no', Kit::form('no'), 'Em plus o becomes no: no jardim means in the garden.'),
            Kit::typeGap($s, 'sentences.type_gap.cama-esta', 'A cama ___ no quarto.', 'The bed is in the bedroom.', 'está', Kit::form('está', true), 'A cama is a bed you both know, so we say está, not há.'),
            Kit::typeGap($s, 'sentences.type_gap.cadeiras-ha', '___ duas cadeiras na cozinha.', 'There are two chairs in the kitchen.', 'Há', Kit::form('há', true), 'Há stays the same for one thing or for many things.'),
            Kit::typeGap($s, 'sentences.type_gap.cadeiras-estao', 'As cadeiras ___ na sala.', 'The chairs are in the living room.', 'estão', Kit::form('estão', true), 'As cadeiras is plural and known, so we say estão.'),
            Kit::typeGap($s, 'sentences.type_gap.janela-lado', 'A janela está ao ___ da cama.', 'The window is next to the bed.', 'lado', Kit::form('lado'), 'Ao lado de means next to.'),

            Kit::translate($s, 'sentences.translate.mesa-cozinha', 'There is a table in the kitchen.', ['Há uma mesa na cozinha.', 'Na cozinha há uma mesa.'], [$mesa, $cozinha, Kit::form('há', true)]),
            Kit::translate($s, 'sentences.translate.cadeira-perto', 'The chair is near the table.', ['A cadeira está perto da mesa.'], [$cadeira, $mesa, Kit::form('perto')]),
            Kit::translate($s, 'sentences.translate.casa-jardim', 'We have a house with a garden.', ['Temos uma casa com jardim.', 'Temos uma casa com um jardim.', 'Nós temos uma casa com jardim.', 'Nós temos uma casa com um jardim.'], [$casa, $jardim]),

            Kit::build($s, 'sentences.build.jardim-lado', 'The flat is next to the garden.', 'O apartamento está ao lado do jardim.', ['há'], [$apartamento, $jardim, Kit::form('lado')]),
            Kit::build($s, 'sentences.build.janela-sala', 'The window is in the living room.', 'A janela está na sala.', ['há'], [$janela, $sala, Kit::form('está', true)]),
            Kit::build($s, 'sentences.build.casa-jardim', 'The house has a garden.', 'A casa tem um jardim.', ['há'], [$casa, $jardim]),

            Kit::listenChoose($s, 'sentences.listen_choose.mesa-sala', 'Há uma mesa na sala.', ['There is a table in the living room.', 'There is a chair in the living room.', 'There is a table in the kitchen.', 'The table is in the living room.'], 'There is a table in the living room.', [$mesa, $sala, Kit::form('há', true)]),
            Kit::listenChoose($s, 'sentences.listen_choose.cama-debaixo', 'A cama está debaixo da janela.', ['The bed is under the window.', 'The bed is next to the window.', 'The chair is under the window.', 'The window is under the bed.'], 'The bed is under the window.', [$cama, $janela, Kit::form('debaixo')]),
            Kit::listenChoose($s, 'sentences.listen_choose.quartos', 'A minha casa tem dois quartos.', ['My house has two bedrooms.', 'My house has two kitchens.', 'My flat has two bedrooms.', 'My house has three bedrooms.'], 'My house has two bedrooms.', [$casa, Kit::word('o quarto', 'quartos')]),
            Kit::listenType($s, 'sentences.listen_type.cadeira-cozinha', 'Há uma cadeira na cozinha.', 'There is a chair in the kitchen.', [$cadeira, $cozinha, Kit::form('há', true)], homophoneNote: self::HA_NOTE),
            Kit::listenType($s, 'sentences.listen_type.mesa-jardim', 'A mesa está no jardim.', 'The table is in the garden.', [$mesa, $jardim, Kit::form('está', true)], homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'sentences.listen_type.apartamento-perto', 'O meu apartamento está perto da casa.', 'My flat is near the house.', [$apartamento, $casa, Kit::form('perto')]),
            Kit::listenType($s, 'sentences.listen_type.cadeiras-debaixo', 'As cadeiras estão debaixo da mesa.', 'The chairs are under the table.', [Kit::word('a cadeira', 'cadeiras'), $mesa, Kit::form('debaixo')]),

            Kit::speakRepeat($s, 'sentences.speak_repeat.cozinha-sala', 'A cozinha está ao lado da sala.', 'The kitchen is next to the living room.', [$cozinha, $sala, Kit::form('lado')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.cama-quarto', 'Há uma cama no meu quarto.', 'There is a bed in my bedroom.', [$cama, $quarto, Kit::form('há', true)]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.casa-jardim', 'A minha casa tem um jardim.', 'My house has a garden.', [$casa, $jardim]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.apartamento-sala', 'O meu apartamento tem uma sala.', 'My flat has a living room.', [$apartamento, $sala]),
            Kit::speakAnswer($s, 'sentences.speak_answer.mesa-cozinha', 'Há uma mesa na cozinha?', 'Is there a table in the kitchen?', [['sim', 'não', 'há'], ['mesa', 'cozinha']], 'Sim, há uma mesa na cozinha.', [$mesa, $cozinha, Kit::form('há', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.cama-quarto', 'A cama está no quarto?', 'Is the bed in the bedroom?', [['sim', 'não', 'está'], ['cama', 'quarto']], 'Sim, a cama está no quarto.', [$cama, $quarto, Kit::form('está', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.jardim', 'O que há no jardim?', 'What is there in the garden?', [['há', 'uma', 'um', 'duas', 'dois'], ['mesa', 'cadeira', 'cadeiras', 'casa']], 'Há uma mesa e duas cadeiras.', [$jardim, $cadeira, Kit::form('há', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $casa = Kit::word('a casa', 'casa');
        $apartamento = Kit::word('o apartamento', 'apartamento');
        $cozinha = Kit::word('a cozinha', 'cozinha');
        $sala = Kit::word('a sala', 'sala');
        $quarto = Kit::word('o quarto', 'quarto');
        $jardim = Kit::word('o jardim', 'jardim');
        $mesa = Kit::word('a mesa', 'mesa');
        $cadeira = Kit::word('a cadeira', 'cadeira');
        $cama = Kit::word('a cama', 'cama');
        $janela = Kit::word('a janela', 'janela');
        $cadeiras = Kit::word('a cadeira', 'cadeiras');

        return [
            Kit::readPassage($s, 'task.read_passage.apartamento', 'Read the conversation about Marta\'s flat.', [
                Kit::line('Ana', 'Olá, Marta. Como é o teu apartamento?'),
                Kit::line('Marta', 'O meu apartamento tem uma sala e dois quartos. Na sala há uma mesa e quatro cadeiras.'),
                Kit::line('Ana', 'E a cozinha?'),
                Kit::line('Marta', 'A cozinha está ao lado da sala. Há uma janela.'),
            ], [
                Kit::question('How many bedrooms does the flat have?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What is in the living room?', ['A table and four chairs', 'A bed and a table', 'A garden'], 'A table and four chairs'),
                Kit::question('Where is the kitchen?', ['Next to the living room', 'Under the living room', 'In the garden'], 'Next to the living room'),
            ], [$apartamento, $sala, Kit::word('o quarto', 'quartos'), $mesa, $cadeiras, $cozinha, $janela], 'read'),
            Kit::gap($s, 'task.choose_gap.onde-esta', 'Onde ___ a mesa?', ['está', 'há'], 'está', Kit::form('está', true), 'A mesa is a table you both know, so we ask where it is with está.', 'read', 'Where is the table?'),
            Kit::gap($s, 'task.choose_gap.ha-janela', '___ uma janela na sala?', ['Há', 'Está'], 'Há', Kit::form('há', true), 'Uma janela is something we do not know about yet, so at this level we ask with há.', 'read', 'Is there a window in the living room?'),

            Kit::transform($s, 'task.transform.cadeiras', 'Say it about "the chairs" (plural).', 'A cadeira está no jardim.', ['As cadeiras estão no jardim.'], [$cadeiras, $jardim, Kit::form('estão', true)]),
            Kit::transform($s, 'task.transform.mesa', 'Change it to "the table" (not "a table").', 'Há uma mesa na cozinha.', ['A mesa está na cozinha.', 'Na cozinha está a mesa.'], [$mesa, $cozinha, Kit::form('está', true)]),
            Kit::transform($s, 'task.transform.janela', 'Change it to "there is a window".', 'A janela está na sala.', ['Há uma janela na sala.', 'Na sala há uma janela.'], [$janela, $sala, Kit::form('há', true)]),
            Kit::writeGuided($s, 'task.write_guided.apartamento', 'Say that your flat has a living room and two bedrooms.', ['apartamento', 'sala', 'quartos'], 'O meu apartamento tem uma sala e dois quartos.', [
                ['forms' => ['apartamento'], 'term' => 'o apartamento'],
                ['forms' => ['sala'], 'term' => 'a sala'],
                ['forms' => ['quartos'], 'term' => 'o quarto'],
            ], [$apartamento, $sala, Kit::word('o quarto', 'quartos')]),
            Kit::writeGuided($s, 'task.write_guided.jardim', 'Say that there is a table and a chair in the garden.', ['há', 'mesa', 'cadeira', 'jardim'], 'Há uma mesa e uma cadeira no jardim.', [
                ['forms' => ['há'], 'term' => null],
                ['forms' => ['mesa'], 'term' => 'a mesa'],
                ['forms' => ['cadeira'], 'term' => 'a cadeira'],
                ['forms' => ['jardim'], 'term' => 'o jardim'],
            ], [$mesa, $cadeira, $jardim]),
            Kit::build($s, 'task.build.cozinha-quarto', 'The kitchen is next to the bedroom.', 'A cozinha está ao lado do quarto.', ['há', 'em'], [$cozinha, $quarto, Kit::form('lado')], 'write'),
            Kit::build($s, 'task.build.cadeira-mesa', 'There is a chair under the table.', 'Há uma cadeira debaixo da mesa.', ['está', 'no'], [$cadeira, $mesa, Kit::form('há', true)], 'write'),
            Kit::build($s, 'task.build.jardim-casa', 'The garden is near the house.', 'O jardim está perto da casa.', ['há', 'ao'], [$jardim, $casa, Kit::form('perto')], 'write'),
            Kit::translate($s, 'task.translate.cama', 'Is there a bed in the bedroom?', ['Há uma cama no quarto?'], [$cama, $quarto, Kit::form('há', true)], 'write'),
            Kit::translate($s, 'task.translate.cadeiras', 'Where are the chairs?', ['Onde estão as cadeiras?'], [$cadeiras, Kit::form('estão', true)], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.jardim', [
                Kit::line('João', 'Tenho uma casa com jardim.'),
                Kit::line('Ana', 'O que há no jardim?'),
                Kit::line('João', 'Há uma mesa e duas cadeiras. A mesa está ao lado da casa.'),
            ], [
                Kit::question('What does João have?', ['A house with a garden', 'A flat with a garden', 'A house without a garden'], 'A house with a garden'),
                Kit::question('What is in the garden?', ['A table and two chairs', 'A bed and a chair', 'Two tables'], 'A table and two chairs'),
                Kit::question('Where is the table?', ['Next to the house', 'Under the window', 'In the kitchen'], 'Next to the house'),
            ], [
                Kit::question('Who has a garden?', ['João', 'Ana', 'Nobody'], 'João'),
                Kit::question('How many chairs are there?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Ana ask about?', ['The garden', 'The kitchen', 'The bed'], 'The garden'),
            ], [$casa, $jardim, $mesa, $cadeiras], 'listen'),
            Kit::listenType($s, 'task.listen_type.cama-janela', 'Há uma cama e uma janela no quarto.', 'There is a bed and a window in the bedroom.', [$cama, $janela, $quarto, Kit::form('há', true)], 'listen', homophoneNote: self::HA_NOTE),
            Kit::listenType($s, 'task.listen_type.casa-cozinha', 'A minha casa tem uma cozinha e uma sala.', 'My house has a kitchen and a living room.', [$casa, $cozinha, $sala], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'task.listen_type.cadeira-cima', 'Há uma cadeira em cima da cama.', 'There is a chair on the bed.', [$cadeira, $cama, Kit::form('cima')], 'listen', homophoneNote: self::HA_NOTE),

            Kit::speakAnswer($s, 'task.speak_answer.quarto', 'O que há no teu quarto?', 'What is there in your bedroom?', [['há', 'uma', 'um', 'duas'], ['cama', 'mesa', 'cadeira', 'janela']], 'Há uma cama e uma janela.', [$quarto, $cama, Kit::form('há', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.casa-jardim', 'A tua casa tem jardim?', 'Does your house have a garden?', [['sim', 'não', 'tem', 'tenho'], ['jardim', 'casa']], 'Sim, a minha casa tem jardim.', [$casa, $jardim], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.cama', 'Onde está a tua cama?', 'Where is your bed?', [['cama', 'está'], ['quarto', 'apartamento', 'casa', 'sala']], 'A minha cama está no meu quarto.', [$cama, $quarto, Kit::form('está', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.cadeira-sala', 'Há uma cadeira na tua sala?', 'Is there a chair in your living room?', [['sim', 'não', 'há'], ['cadeira', 'sala']], 'Sim, há uma cadeira na minha sala.', [$cadeira, $sala, Kit::form('há', true)], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.apartamento-casa', 'O meu apartamento está perto da casa da Ana.', 'My flat is near Ana\'s house.', [$apartamento, $casa, Kit::form('perto')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.quarto', 'No quarto há uma cama e uma janela.', 'In the bedroom there is a bed and a window.', [$quarto, $cama, $janela, Kit::form('há', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $casa = Kit::word('a casa', 'casa');
        $apartamento = Kit::word('o apartamento', 'apartamento');
        $cozinha = Kit::word('a cozinha', 'cozinha');
        $sala = Kit::word('a sala', 'sala');
        $quarto = Kit::word('o quarto', 'quarto');
        $jardim = Kit::word('o jardim', 'jardim');
        $mesa = Kit::word('a mesa', 'mesa');
        $cadeira = Kit::word('a cadeira', 'cadeira');
        $cama = Kit::word('a cama', 'cama');
        $janela = Kit::word('a janela', 'janela');

        return [
            Kit::translate($s, 'check.a.translate.quarto', 'The bedroom is next to the kitchen.', ['O quarto está ao lado da cozinha.'], [$quarto, $cozinha, Kit::form('lado')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.janela', 'There is a window in my bedroom.', ['Há uma janela no meu quarto.', 'No meu quarto há uma janela.'], [$janela, $quarto, Kit::form('há', true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.apartamento', 'My flat has a garden.', ['O meu apartamento tem um jardim.', 'O meu apartamento tem jardim.'], [$apartamento, $jardim], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.cadeira', 'The chair is under the bed.', ['A cadeira está debaixo da cama.'], [$cadeira, $cama, Kit::form('debaixo')], 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.mesa', 'A mesa ___ na casa.', 'The table is in the house.', 'está', Kit::form('está', true), null, 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.camas', '___ duas camas no quarto.', 'There are two beds in the bedroom.', 'Há', Kit::form('há', true), null, 'sentences', $a),
            Kit::listenType($s, 'check.a.listen_type.casa', 'A minha casa tem três quartos.', 'My house has three bedrooms.', [$casa, Kit::word('o quarto', 'quartos')], 'dictation', $a, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.a.listen_type.mesa', 'A mesa está na sala.', 'The table is in the living room.', [$mesa, $sala, Kit::form('está', true)], 'dictation', $a, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.a.listen_type.apartamento', 'O meu apartamento tem uma sala e uma cozinha.', 'My flat has a living room and a kitchen.', [$apartamento, $sala, $cozinha], 'dictation', $a),
            Kit::listenPassage($s, 'check.a.listen_passage.casa', [
                Kit::line('Marta', 'A minha casa tem uma cozinha e um jardim.'),
                Kit::line('João', 'E tem uma sala?'),
                Kit::line('Marta', 'Sim. Na sala tenho uma mesa e duas cadeiras.'),
            ], [
                Kit::question('What does Marta\'s house have?', ['A kitchen and a garden', 'A kitchen and a bedroom', 'A living room and a bedroom'], 'A kitchen and a garden'),
                Kit::question('What does João ask about?', ['The living room', 'The kitchen', 'The garden'], 'The living room'),
                Kit::question('What is in the living room?', ['A table and two chairs', 'A bed and a table', 'Two tables'], 'A table and two chairs'),
            ], [
                Kit::question('Who has a house with a garden?', ['Marta', 'João', 'Nobody'], 'Marta'),
                Kit::question('How many chairs does Marta have in the living room?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does João ask?', ['Whether the house has a living room', 'Whether the house has a garden', 'Whether there is a bed'], 'Whether the house has a living room'),
            ], [$casa, $cozinha, $jardim, $sala, $mesa, Kit::word('a cadeira', 'cadeiras')], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.apartamento', 'Read the conversation.', [
                Kit::line('Ana', 'Olá, João. O meu apartamento tem um quarto.'),
                Kit::line('João', 'Há uma cama e uma janela?'),
                Kit::line('Ana', 'Sim, há uma cama e uma janela.'),
            ], [
                Kit::question('What does Ana\'s flat have?', ['A bedroom', 'A garden', 'A kitchen'], 'A bedroom'),
                Kit::question('What does João ask about?', ['A bed and a window', 'A table and a chair', 'A garden'], 'A bed and a window'),
            ], [$apartamento, $quarto, $cama, $janela], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.janela', 'Há uma janela na cozinha?', 'Is there a window in the kitchen?', [['sim', 'não', 'há'], ['janela', 'cozinha']], 'Sim, há uma janela na cozinha.', [$janela, $cozinha], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.apartamento', 'O teu apartamento tem jardim?', 'Does your flat have a garden?', [['sim', 'não', 'tem', 'tenho'], ['apartamento', 'jardim']], 'Não, o meu apartamento não tem jardim.', [$apartamento, $jardim], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.cadeira', 'A cadeira está na cozinha?', 'Is the chair in the kitchen?', [['sim', 'não', 'está'], ['cadeira', 'cozinha']], 'Sim, a cadeira está na cozinha.', [$cadeira, $cozinha], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $casa = Kit::word('a casa', 'casa');
        $apartamento = Kit::word('o apartamento', 'apartamento');
        $cozinha = Kit::word('a cozinha', 'cozinha');
        $sala = Kit::word('a sala', 'sala');
        $quarto = Kit::word('o quarto', 'quarto');
        $jardim = Kit::word('o jardim', 'jardim');
        $mesa = Kit::word('a mesa', 'mesa');
        $cadeira = Kit::word('a cadeira', 'cadeira');
        $cama = Kit::word('a cama', 'cama');
        $janela = Kit::word('a janela', 'janela');

        return [
            Kit::translate($s, 'check.b.translate.cama', 'The bed is next to the window.', ['A cama está ao lado da janela.'], [$cama, $janela, Kit::form('lado')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.cadeiras', 'There are two chairs in the garden.', ['Há duas cadeiras no jardim.', 'No jardim há duas cadeiras.'], [Kit::word('a cadeira', 'cadeiras'), $jardim, Kit::form('há', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.apartamento', 'The flat has a living room, a kitchen and a garden.', ['O apartamento tem uma sala, uma cozinha e um jardim.', 'O apartamento tem sala, cozinha e jardim.'], [$apartamento, $sala, $cozinha, $jardim], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.mesa', 'The table is near the house.', ['A mesa está perto da casa.'], [$mesa, $casa, Kit::form('perto')], 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.camas', 'As camas ___ no quarto.', 'The beds are in the bedroom.', 'estão', Kit::form('estão', true), null, 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.mesa-cadeiras', '___ uma mesa e duas cadeiras no jardim.', 'There are a table and two chairs in the garden.', 'Há', Kit::form('há', true), null, 'sentences', $b),
            Kit::listenType($s, 'check.b.listen_type.cadeira-mesa', 'A cadeira e a mesa estão na sala.', 'The chair and the table are in the living room.', [$cadeira, $mesa, $sala, Kit::form('estão', true)], 'dictation', $b, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.b.listen_type.casa', 'A minha casa tem uma cozinha e dois quartos.', 'My house has a kitchen and two bedrooms.', [$casa, $cozinha, Kit::word('o quarto', 'quartos')], 'dictation', $b, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.b.listen_type.quarto', 'O meu apartamento tem um quarto com uma cama.', 'My flat has a bedroom with a bed.', [$apartamento, $quarto, $cama], 'dictation', $b),
        ];
    }
}
