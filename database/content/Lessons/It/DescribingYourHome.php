<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingYourHome implements UnitContent
{
    private const NOTE_IS = 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.';

    private const NOTE_AND = 'E (and) and è (is) sound very close, and the accent is only a written difference. Here it means and, so it has no accent.';

    private const NOTE_HA = 'Ha (has) sounds the same as a (to); only the h on paper tells them apart. Here it is the verb has.';

    private const NOTE_CE = 'C\'è (there is) is written with an apostrophe and an accent: c\'è.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'describing-your-home';
    }

    public function words(): array
    {
        return [
            new WordData('la casa', cue: 'house (or home)', forms: ['case']),
            new WordData('l\'appartamento', cue: 'flat (apartment)', forms: ['appartamento', 'appartamenti']),
            new WordData('la cucina', cue: 'kitchen', forms: ['cucine']),
            new WordData('il salotto', cue: 'living room', accepted: ['il soggiorno'], forms: ['salotti']),
            new WordData('la camera', cue: 'bedroom', accepted: ['la camera da letto'], forms: ['camere']),
            new WordData('il giardino', cue: 'garden', forms: ['giardini']),
            new WordData('il tavolo', cue: 'table', forms: ['tavoli']),
            new WordData('la sedia', cue: 'chair', forms: ['sedie']),
            new WordData('il letto', cue: 'bed', forms: ['letti']),
            new WordData('la finestra', cue: 'window', forms: ['finestre']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'C\'è un tavolo in cucina.', 'english' => 'There is a table in the kitchen.'],
            ['text' => 'Il tavolo è in cucina.', 'english' => 'The table is in the kitchen.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /**
     * @param  list<string>  $options
     * @param  list<TargetSpec>  $targets
     * @param  array<string, string>  $glosses
     */
    private function choose(Stage $stage, string $key, string $prompt, string $english, array $options, string $answer, array $targets, string $why, string $block = 'choose', array $glosses = []): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::ChooseGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'options' => $options, 'answer' => $answer, 'why' => $why, 'glosses' => $glosses]), [$answer], $targets, block: $block);
    }

    /**
     * @param  list<string>  $answers
     * @param  list<TargetSpec>  $targets
     */
    private function gap(Stage $stage, string $key, string $prompt, string $english, array $answers, array $targets, ?string $why = null, ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TypeGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'why' => $why]), $answers, $targets, $set, $set === null ? 'write' : 'sentences');
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $s = Stage::Sentences;
        $casa = Kit::word('la casa', 'casa');
        $appartamento = Kit::word('l\'appartamento', 'appartamento', ['l\'appartamento']);
        $cucina = Kit::word('la cucina', 'cucina');
        $salotto = Kit::word('il salotto', 'salotto');
        $camera = Kit::word('la camera', 'camera');
        $giardino = Kit::word('il giardino', 'giardino');
        $tavolo = Kit::word('il tavolo', 'tavolo');
        $sedia = Kit::word('la sedia', 'sedia');
        $letto = Kit::word('il letto', 'letto');
        $finestra = Kit::word('la finestra', 'finestra');
        $ce = Kit::form('c\'è', true);
        $siSono = Kit::form('ci sono', true);
        $e = Kit::form('è', true);
        $sono = Kit::form('sono', true);

        return [
            $this->choose($s, 'sentences.choose_gap.tavolo-e', 'Il tavolo ___ in cucina.', 'The table is in the kitchen.', ['è', 'c\'è'], 'è', [$e], 'Il tavolo is a table you both know, so we say è. C\'è is for introducing something new.'),
            $this->choose($s, 'sentences.choose_gap.letto-ce', '___ un letto in camera.', 'There is a bed in the bedroom.', ['C\'è', 'È'], 'C\'è', [$ce], 'Un letto is something new, so we say c\'è: there is a bed.'),
            $this->choose($s, 'sentences.choose_gap.sedia-sotto', 'La sedia è ___ il tavolo.', 'The chair is under the table.', ['sotto', 'sopra', 'vicino'], 'sotto', [Kit::form('sotto')], 'Sotto means under. Sopra means on top of, and vicino needs a after it and means near.'),
            $this->choose($s, 'sentences.choose_gap.finestra-in', 'C\'è una finestra ___ salotto.', 'There is a window in the living room.', ['in', 'sopra', 'sotto'], 'in', [Kit::form('in')], 'In means in. Sopra and sotto mean on top of and under, and they are not used for a room.'),
            $this->choose($s, 'sentences.choose_gap.giardino', 'Il tavolo è accanto al ___.', 'The table is next to the garden.', ['giardino', 'cucina', 'sedia'], 'giardino', [$giardino], 'Giardino is masculine, so it goes with al: al giardino. Cucina and sedia are feminine and go with alla.', 'choose', ['accanto' => 'next to']),
            $this->choose($s, 'sentences.choose_gap.camere', 'Il mio appartamento ha due ___.', 'My flat has two bedrooms.', ['camere', 'camera'], 'camere', [Kit::word('la camera', 'camere')], 'After due we need the plural, so camera becomes camere.'),

            $this->gap($s, 'sentences.type_gap.sedia-in', 'C\'è una sedia ___ giardino.', 'There is a chair in the garden.', ['in'], [Kit::form('in')], 'In means in a place, and the garden needs no article after it here.'),
            $this->gap($s, 'sentences.type_gap.letto-e', 'Il letto ___ in camera.', 'The bed is in the bedroom.', ['è'], [$e], 'Il letto is a bed you both know, so we say è, not c\'è.'),
            $this->gap($s, 'sentences.type_gap.sedie-ci-sono', '___ due sedie in cucina.', 'There are two chairs in the kitchen.', ['Ci sono'], [$siSono], 'Two chairs are something new and more than one, so we say ci sono. C\'è is for one thing.'),
            $this->gap($s, 'sentences.type_gap.sedie-sono', 'Le sedie ___ in salotto.', 'The chairs are in the living room.', ['sono'], [$sono], 'Le sedie is plural and known, so we say sono.'),
            $this->gap($s, 'sentences.type_gap.finestra-accanto', 'La finestra è ___ al letto.', 'The window is next to the bed.', ['accanto', 'vicino'], [Kit::form('accanto', false, ['vicino'])], 'Accanto a means next to, and vicino a means near. Both fit with al.'),

            Kit::translate($s, 'sentences.translate.tavolo-cucina', 'There is a table in the kitchen.', ['C\'è un tavolo in cucina.', 'In cucina c\'è un tavolo.'], [$tavolo, $cucina, $ce]),
            Kit::translate($s, 'sentences.translate.sedia-vicino', 'The chair is near the table.', ['La sedia è vicino al tavolo.', 'La sedia è vicina al tavolo.', 'La sedia è accanto al tavolo.'], [$sedia, $tavolo, Kit::form('vicino', false, ['vicina', 'accanto'])]),
            Kit::translate($s, 'sentences.translate.casa-giardino', 'We have a house with a garden.', ['Abbiamo una casa con giardino.', 'Abbiamo una casa con un giardino.', 'Noi abbiamo una casa con giardino.', 'Noi abbiamo una casa con un giardino.'], [$casa, $giardino]),

            Kit::build($s, 'sentences.build.giardino-accanto', 'The flat is next to the garden.', 'L\'appartamento è accanto al giardino.', ['c\'è'], [$appartamento, $giardino, Kit::form('accanto')]),
            Kit::build($s, 'sentences.build.finestra-salotto', 'The window is in the living room.', 'La finestra è in salotto.', ['c\'è'], [$finestra, $salotto, $e]),
            Kit::build($s, 'sentences.build.casa-giardino', 'The house has a garden.', 'La casa ha un giardino.', ['sono'], [$casa, $giardino]),

            Kit::listenChoose($s, 'sentences.listen_choose.tavolo-salotto', 'C\'è un tavolo in salotto.', ['There is a table in the living room.', 'There is a chair in the living room.', 'There is a table in the kitchen.', 'The table is in the living room.'], 'There is a table in the living room.', [$tavolo, $salotto, $ce]),
            Kit::listenChoose($s, 'sentences.listen_choose.letto-sotto', 'Il letto è sotto la finestra.', ['The bed is under the window.', 'The bed is next to the window.', 'The chair is under the window.', 'The window is under the bed.'], 'The bed is under the window.', [$letto, $finestra, Kit::form('sotto')]),
            Kit::listenChoose($s, 'sentences.listen_choose.camere', 'La mia casa ha due camere.', ['My house has two bedrooms.', 'My house has two kitchens.', 'My flat has two bedrooms.', 'My house has three bedrooms.'], 'My house has two bedrooms.', [$casa, Kit::word('la camera', 'camere')]),
            Kit::listenType($s, 'sentences.listen_type.sedia-cucina', 'C\'è una sedia in cucina.', 'There is a chair in the kitchen.', [$sedia, $cucina, $ce], homophoneNote: self::NOTE_CE),
            Kit::listenType($s, 'sentences.listen_type.tavolo-giardino', 'Il tavolo è in giardino.', 'The table is in the garden.', [$tavolo, $giardino, $e], homophoneNote: self::NOTE_IS),
            Kit::listenType($s, 'sentences.listen_type.appartamento-vicino', 'Il mio appartamento è vicino alla casa.', 'My flat is near the house.', [$appartamento, $casa, Kit::form('vicino')], homophoneNote: self::NOTE_IS),
            Kit::listenType($s, 'sentences.listen_type.sedie-sotto', 'Le sedie sono sotto il tavolo.', 'The chairs are under the table.', [Kit::word('la sedia', 'sedie'), $tavolo, Kit::form('sotto')]),

            Kit::speakRepeat($s, 'sentences.speak_repeat.cucina-salotto', 'La cucina è accanto al salotto.', 'The kitchen is next to the living room.', [$cucina, $salotto, Kit::form('accanto')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.letto-camera', 'C\'è un letto in camera.', 'There is a bed in the bedroom.', [$letto, $camera, $ce]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.casa-giardino', 'La mia casa ha un giardino.', 'My house has a garden.', [$casa, $giardino]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.appartamento-salotto', 'Il mio appartamento ha un salotto.', 'My flat has a living room.', [$appartamento, $salotto]),
            Kit::speakAnswer($s, 'sentences.speak_answer.tavolo-cucina', 'C\'è un tavolo in cucina?', 'Is there a table in the kitchen?', [['sì', 'no', 'c\'è'], ['tavolo', 'cucina']], 'Sì, c\'è un tavolo in cucina.', [$tavolo, $cucina, $ce]),
            Kit::speakAnswer($s, 'sentences.speak_answer.letto-camera', 'Il letto è in camera?', 'Is the bed in the bedroom?', [['sì', 'no', 'è'], ['letto', 'camera']], 'Sì, il letto è in camera.', [$letto, $camera, $e]),
            Kit::speakAnswer($s, 'sentences.speak_answer.giardino', 'Quante sedie ci sono in giardino?', 'How many chairs are there in the garden?', [['una', 'due', 'tre', 'quattro', 'cinque'], ['sedia', 'sedie']], 'In giardino ci sono due sedie.', [$giardino, $sedia, $siSono]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $casa = Kit::word('la casa', 'casa');
        $appartamento = Kit::word('l\'appartamento', 'appartamento', ['l\'appartamento']);
        $cucina = Kit::word('la cucina', 'cucina');
        $salotto = Kit::word('il salotto', 'salotto');
        $camera = Kit::word('la camera', 'camera');
        $giardino = Kit::word('il giardino', 'giardino');
        $tavolo = Kit::word('il tavolo', 'tavolo');
        $sedia = Kit::word('la sedia', 'sedia');
        $letto = Kit::word('il letto', 'letto');
        $finestra = Kit::word('la finestra', 'finestra');
        $sedie = Kit::word('la sedia', 'sedie');
        $camere = Kit::word('la camera', 'camere');
        $ce = Kit::form('c\'è', true);
        $siSono = Kit::form('ci sono', true);
        $e = Kit::form('è', true);
        $sono = Kit::form('sono', true);

        return [
            Kit::readPassage($s, 'task.read_passage.appartamento', 'Read the conversation about Marta\'s flat.', [
                Kit::line('Anna', 'Ciao, Marta. Hai un appartamento?'),
                Kit::line('Marta', 'Sì. Il mio appartamento ha un salotto e due camere. In salotto c\'è un tavolo con quattro sedie.'),
                Kit::line('Anna', 'E la cucina?'),
                Kit::line('Marta', 'La cucina è accanto al salotto. C\'è una finestra.'),
            ], [
                Kit::question('How many bedrooms does the flat have?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What is in the living room?', ['A table and four chairs', 'A bed and a table', 'A garden'], 'A table and four chairs'),
                Kit::question('Where is the kitchen?', ['Next to the living room', 'Under the living room', 'In the garden'], 'Next to the living room'),
            ], [$appartamento, $salotto, $camere, $tavolo, $sedie, $cucina, $finestra], 'read'),
            $this->choose($s, 'task.choose_gap.dove-e', 'Dove ___ il tavolo?', 'Where is the table?', ['è', 'c\'è'], 'è', [$tavolo, $e], 'Il tavolo is a table you both know, so we ask where it is with è. In everyday Italian this is usually written dov\'è.', 'read'),
            $this->choose($s, 'task.choose_gap.qualcosa-ce', '___ qualcosa in salotto?', 'Is there something in the living room?', ['C\'è', 'È'], 'C\'è', [$salotto, $ce], 'We do not know yet what is there, so we ask with c\'è.', 'read'),

            Kit::transform($s, 'task.transform.sedie', 'Say it about "the chairs" (plural).', 'La sedia è in giardino.', ['Le sedie sono in giardino.'], [$sedie, $giardino, $sono]),
            Kit::transform($s, 'task.transform.tavolo', 'Change it to "the table" (not "a table").', 'C\'è un tavolo in cucina.', ['Il tavolo è in cucina.'], [$tavolo, $cucina, $e]),
            Kit::transform($s, 'task.transform.finestra', 'Change it to "there is a window".', 'La finestra è in salotto.', ['C\'è una finestra in salotto.', 'In salotto c\'è una finestra.'], [$finestra, $salotto, $ce]),
            Kit::writeGuided($s, 'task.write_guided.appartamento', 'Say that your flat has a living room and two bedrooms.', ['appartamento', 'ha', 'salotto', 'camere'], 'Il mio appartamento ha un salotto e due camere.', [
                ['forms' => ['appartamento'], 'term' => 'l\'appartamento'],
                ['forms' => ['salotto'], 'term' => 'il salotto'],
                ['forms' => ['camere'], 'term' => 'la camera'],
            ], [$appartamento, $salotto, $camere]),
            Kit::writeGuided($s, 'task.write_guided.giardino', 'Say that there is a table in the garden.', ['c\'è', 'tavolo', 'giardino'], 'C\'è un tavolo in giardino.', [
                ['forms' => ['c\'è'], 'term' => null],
                ['forms' => ['tavolo'], 'term' => 'il tavolo'],
                ['forms' => ['giardino'], 'term' => 'il giardino'],
            ], [$tavolo, $giardino]),
            Kit::build($s, 'task.build.cucina-camera', 'The kitchen is next to the bedroom.', 'La cucina è accanto alla camera.', ['c\'è', 'sopra'], [$cucina, $camera, Kit::form('accanto')], 'write'),
            Kit::build($s, 'task.build.sedia-tavolo', 'The chair is under the table.', 'La sedia è sotto il tavolo.', ['c\'è', 'sopra'], [$sedia, $tavolo, Kit::form('sotto')], 'write'),
            Kit::build($s, 'task.build.giardino-casa', 'The garden is near the house.', 'Il giardino è vicino alla casa.', ['c\'è', 'sotto'], [$giardino, $casa, Kit::form('vicino')], 'write'),
            Kit::translate($s, 'task.translate.letto', 'Is there a bed in the bedroom?', ['C\'è un letto in camera?', 'In camera c\'è un letto?'], [$letto, $camera, $ce], 'write'),
            Kit::translate($s, 'task.translate.sedie', 'Where are the chairs?', ['Dove sono le sedie?'], [$sedie, $sono], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.giardino', [
                Kit::line('Luca', 'Ho una casa con giardino.'),
                Kit::line('Anna', 'C\'è qualcosa in giardino?'),
                Kit::line('Luca', 'Sì, ci sono due sedie e un tavolo. Il tavolo è accanto alla casa.'),
            ], [
                Kit::question('What does Luca have?', ['A house with a garden', 'A flat with a garden', 'A house without a garden'], 'A house with a garden'),
                Kit::question('What is in the garden?', ['A table and two chairs', 'A bed and a chair', 'Two tables'], 'A table and two chairs'),
                Kit::question('Where is the table?', ['Next to the house', 'Under the window', 'In the kitchen'], 'Next to the house'),
            ], [
                Kit::question('Who has a garden?', ['Luca', 'Anna', 'Nobody'], 'Luca'),
                Kit::question('How many chairs are there?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Anna ask about?', ['The garden', 'The kitchen', 'The bed'], 'The garden'),
            ], [$casa, $giardino, $tavolo, $sedie], 'listen'),
            Kit::listenType($s, 'task.listen_type.letto-finestra', 'C\'è un letto e una finestra in camera.', 'There is a bed and a window in the bedroom.', [$letto, $finestra, $camera, $ce], 'listen', homophoneNote: self::NOTE_AND.' '.self::NOTE_CE),
            Kit::listenType($s, 'task.listen_type.casa-cucina', 'La mia casa ha una cucina e un salotto.', 'My house has a kitchen and a living room.', [$casa, $cucina, $salotto], 'listen', homophoneNote: self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenType($s, 'task.listen_type.sedia-sopra', 'C\'è una sedia sopra il letto.', 'There is a chair on top of the bed.', [$sedia, $letto, Kit::form('sopra')], 'listen', homophoneNote: self::NOTE_CE),

            Kit::speakAnswer($s, 'task.speak_answer.camera', 'C\'è una finestra in camera tua?', 'Is there a window in your bedroom?', [['sì', 'no', 'c\'è'], ['finestra', 'camera']], 'Sì, c\'è una finestra in camera mia.', [$camera, $finestra, $ce], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.casa-giardino', 'La tua casa ha un giardino?', 'Does your house have a garden?', [['sì', 'no', 'ha', 'ho'], ['giardino', 'casa']], 'Sì, la mia casa ha un giardino.', [$casa, $giardino], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.letto', 'Il tuo letto è in camera o in salotto?', 'Is your bed in the bedroom or in the living room?', [['camera', 'salotto'], ['letto', 'è']], 'Il mio letto è in camera.', [$letto, $salotto, $e], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.sedie-casa', 'Quante sedie ci sono in casa tua?', 'How many chairs are there in your house?', [['una', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci'], ['sedia', 'sedie']], 'In casa mia ci sono quattro sedie.', [$sedie, $siSono], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.appartamento-casa', 'Il mio appartamento è vicino alla casa di Anna.', 'My flat is near Anna\'s house.', [$appartamento, $casa, Kit::form('vicino')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.camera', 'In camera c\'è un letto e una finestra.', 'In the bedroom there is a bed and a window.', [$camera, $letto, $finestra, $ce], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $casa = Kit::word('la casa', 'casa');
        $appartamento = Kit::word('l\'appartamento', 'appartamento', ['l\'appartamento']);
        $cucina = Kit::word('la cucina', 'cucina');
        $salotto = Kit::word('il salotto', 'salotto');
        $camera = Kit::word('la camera', 'camera');
        $giardino = Kit::word('il giardino', 'giardino');
        $tavolo = Kit::word('il tavolo', 'tavolo');
        $sedia = Kit::word('la sedia', 'sedia');
        $letto = Kit::word('il letto', 'letto');
        $finestra = Kit::word('la finestra', 'finestra');
        $camere = Kit::word('la camera', 'camere');
        $ce = Kit::form('c\'è', true);
        $e = Kit::form('è', true);

        return [
            Kit::translate($s, 'check.a.translate.camera', 'The bedroom is next to the kitchen.', ['La camera è accanto alla cucina.', 'La camera è vicino alla cucina.', 'La camera è vicina alla cucina.'], [$camera, $cucina, Kit::form('accanto', false, ['vicino', 'vicina'])], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.finestra', 'There is a window in the bedroom.', ['C\'è una finestra in camera.', 'In camera c\'è una finestra.'], [$finestra, $camera, $ce], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.appartamento', 'My flat has a garden.', ['Il mio appartamento ha un giardino.'], [$appartamento, $giardino], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.sedia', 'The chair is under the bed.', ['La sedia è sotto il letto.'], [$sedia, $letto, Kit::form('sotto')], 'sentences', $a),
            $this->gap($s, 'check.a.type_gap.tavolo', 'Il tavolo ___ in casa.', 'The table is in the house.', ['è'], [$e], null, $a),
            $this->gap($s, 'check.a.type_gap.letti', '___ due letti in camera.', 'There are two beds in the bedroom.', ['Ci sono'], [Kit::form('ci sono', true)], null, $a),
            Kit::listenType($s, 'check.a.listen_type.casa', 'La mia casa ha tre camere.', 'My house has three bedrooms.', [$casa, $camere], 'dictation', $a, homophoneNote: self::NOTE_HA),
            Kit::listenType($s, 'check.a.listen_type.tavolo', 'Il tavolo è in salotto.', 'The table is in the living room.', [$tavolo, $salotto, $e], 'dictation', $a, homophoneNote: self::NOTE_IS),
            Kit::listenType($s, 'check.a.listen_type.appartamento', 'Il mio appartamento ha un salotto e una cucina.', 'My flat has a living room and a kitchen.', [$appartamento, $salotto, $cucina], 'dictation', $a, homophoneNote: self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenPassage($s, 'check.a.listen_passage.casa', [
                Kit::line('Marta', 'La mia casa ha una cucina e un giardino.'),
                Kit::line('Luca', 'E ha un salotto?'),
                Kit::line('Marta', 'Sì. In salotto c\'è un tavolo e ci sono due sedie.'),
            ], [
                Kit::question('What does Marta\'s house have?', ['A kitchen and a garden', 'A kitchen and a bedroom', 'A living room and a bedroom'], 'A kitchen and a garden'),
                Kit::question('What does Luca ask about?', ['The living room', 'The kitchen', 'The garden'], 'The living room'),
                Kit::question('What is in the living room?', ['A table and two chairs', 'A bed and a table', 'Two tables'], 'A table and two chairs'),
            ], [
                Kit::question('Who has a house with a garden?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('How many chairs does Marta have in the living room?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Luca ask?', ['Whether the house has a living room', 'Whether the house has a garden', 'Whether there is a bed'], 'Whether the house has a living room'),
            ], [$casa, $cucina, $giardino, $salotto, $tavolo, Kit::word('la sedia', 'sedie')], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.appartamento', 'Read the conversation.', [
                Kit::line('Anna', 'Ciao, Luca. Il mio appartamento ha una camera.'),
                Kit::line('Luca', 'In camera c\'è un letto?'),
                Kit::line('Anna', 'Sì, c\'è un letto e c\'è una finestra.'),
            ], [
                Kit::question('What does Anna\'s flat have?', ['A bedroom', 'A garden', 'A kitchen'], 'A bedroom'),
                Kit::question('What is in the bedroom?', ['A bed and a window', 'A table and a chair', 'A garden'], 'A bed and a window'),
            ], [$appartamento, $camera, $letto, $finestra], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.finestra', 'C\'è una finestra in cucina?', 'Is there a window in the kitchen?', [['sì', 'no', 'c\'è'], ['finestra', 'cucina']], 'Sì, c\'è una finestra in cucina.', [$finestra, $cucina], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.appartamento', 'Il tuo appartamento ha un giardino?', 'Does your flat have a garden?', [['sì', 'no', 'ha', 'ho'], ['appartamento', 'giardino']], 'No, il mio appartamento non ha un giardino.', [$appartamento, $giardino], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.sedia', 'La sedia è in cucina?', 'Is the chair in the kitchen?', [['sì', 'no', 'è'], ['sedia', 'cucina']], 'Sì, la sedia è in cucina.', [$sedia, $cucina], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $casa = Kit::word('la casa', 'casa');
        $appartamento = Kit::word('l\'appartamento', 'appartamento', ['l\'appartamento']);
        $cucina = Kit::word('la cucina', 'cucina');
        $salotto = Kit::word('il salotto', 'salotto');
        $camera = Kit::word('la camera', 'camera');
        $giardino = Kit::word('il giardino', 'giardino');
        $tavolo = Kit::word('il tavolo', 'tavolo');
        $sedia = Kit::word('la sedia', 'sedia');
        $letto = Kit::word('il letto', 'letto');
        $finestra = Kit::word('la finestra', 'finestra');
        $sedie = Kit::word('la sedia', 'sedie');
        $camere = Kit::word('la camera', 'camere');
        $sono = Kit::form('sono', true);

        return [
            Kit::translate($s, 'check.b.translate.letto', 'The bed is next to the window.', ['Il letto è accanto alla finestra.', 'Il letto è vicino alla finestra.'], [$letto, $finestra, Kit::form('accanto', false, ['vicino'])], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.sedie', 'There are two chairs in the garden.', ['Ci sono due sedie in giardino.', 'In giardino ci sono due sedie.'], [$sedie, $giardino, Kit::form('ci sono', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.appartamento', 'My flat has a living room and a garden.', ['Il mio appartamento ha un salotto e un giardino.'], [$appartamento, $salotto, $giardino], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.tavolo', 'The table is near the window.', ['Il tavolo è vicino alla finestra.', 'Il tavolo è accanto alla finestra.'], [$tavolo, $finestra, Kit::form('vicino', false, ['accanto'])], 'sentences', $b),
            $this->gap($s, 'check.b.type_gap.sedie', 'Le sedie ___ in cucina.', 'The chairs are in the kitchen.', ['sono'], [$sono], null, $b),
            $this->gap($s, 'check.b.type_gap.tavolo', '___ un tavolo in casa.', 'There is a table in the house.', ['C\'è'], [Kit::form('c\'è', true)], null, $b),
            Kit::listenType($s, 'check.b.listen_type.sedia-tavolo', 'La sedia e il tavolo sono in salotto.', 'The chair and the table are in the living room.', [$sedia, $tavolo, $salotto, $sono], 'dictation', $b, homophoneNote: self::NOTE_AND),
            Kit::listenType($s, 'check.b.listen_type.casa', 'La mia casa ha una cucina e due camere.', 'My house has a kitchen and two bedrooms.', [$casa, $cucina, $camere], 'dictation', $b, homophoneNote: self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenType($s, 'check.b.listen_type.camera', 'Il mio appartamento ha una camera con un letto.', 'My flat has a bedroom with a bed.', [$appartamento, $camera, $letto], 'dictation', $b, homophoneNote: self::NOTE_HA),
        ];
    }
}
