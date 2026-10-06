<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

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
    private const HAY_NOTE = 'Hay (there is) sounds like ay (a cry of surprise or pain). Here it means there is or there are.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'describing-your-home';
    }

    public function words(): array
    {
        return [
            new WordData('la casa', cue: 'house (or home)', forms: ['casas']),
            new WordData('el piso', cue: 'flat (apartment)', accepted: ['el apartamento'], forms: ['pisos']),
            new WordData('la cocina', cue: 'kitchen', forms: ['cocinas']),
            new WordData('el salón', cue: 'living room', forms: ['salones']),
            new WordData('el dormitorio', cue: 'bedroom', accepted: ['la habitación'], forms: ['dormitorios']),
            new WordData('el jardín', cue: 'garden', forms: ['jardines']),
            new WordData('la mesa', cue: 'table', forms: ['mesas']),
            new WordData('la silla', cue: 'chair', forms: ['sillas']),
            new WordData('la cama', cue: 'bed', forms: ['camas']),
            new WordData('la ventana', cue: 'window', forms: ['ventanas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Hay una mesa en la cocina.', 'english' => 'There is a table in the kitchen.'],
            ['text' => 'La mesa está en la cocina.', 'english' => 'The table is in the kitchen.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-06', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
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

    /** @param  list<TargetSpec>  $targets */
    private function gap(Stage $stage, string $key, string $prompt, string $english, string $answer, array $targets, ?string $why = null, ?string $set = null): AuthoredExercise
    {
        return new AuthoredExercise($stage, Format::TypeGap, $key, array_filter(['prompt' => $prompt, 'english' => $english, 'why' => $why]), [$answer], $targets, $set, $set === null ? 'write' : 'sentences');
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $s = Stage::Sentences;
        $casa = Kit::word('la casa', 'casa');
        $piso = Kit::word('el piso', 'piso');
        $cocina = Kit::word('la cocina', 'cocina');
        $salon = Kit::word('el salón', 'salón');
        $dormitorio = Kit::word('el dormitorio', 'dormitorio');
        $jardin = Kit::word('el jardín', 'jardín');
        $mesa = Kit::word('la mesa', 'mesa');
        $silla = Kit::word('la silla', 'silla');
        $cama = Kit::word('la cama', 'cama');
        $ventana = Kit::word('la ventana', 'ventana');

        return [
            $this->choose($s, 'sentences.choose_gap.mesa-esta', 'La mesa ___ en la cocina.', 'The table is in the kitchen.', ['está', 'hay'], 'está', [Kit::form('está', true)], 'La mesa is a table you both know, so we say está. Hay is for introducing something new.'),
            $this->choose($s, 'sentences.choose_gap.cama-hay', '___ una cama en el dormitorio.', 'There is a bed in the bedroom.', ['Hay', 'Está'], 'Hay', [Kit::form('hay', true)], 'Una cama is something new, so we say hay: there is a bed.'),
            $this->choose($s, 'sentences.choose_gap.silla-debajo', 'La silla está ___ de la mesa.', 'The chair is under the table.', ['debajo', 'encima', 'cerca'], 'debajo', [Kit::form('debajo')], 'Debajo de means under. Encima de means on top of and cerca de means near.'),
            $this->choose($s, 'sentences.choose_gap.ventana-en', 'Hay una ventana ___ el salón.', 'There is a window in the living room.', ['en', 'encima', 'debajo'], 'en', [Kit::form('en')], 'En means in. Encima and debajo need de after them and mean on top of and under.'),
            $this->choose($s, 'sentences.choose_gap.jardin', 'Hay una mesa en el ___.', 'There is a table in the garden.', ['jardín', 'cocina', 'silla'], 'jardín', [$jardin], 'Jardín is masculine, so it goes with el. Cocina and silla are feminine and go with la.'),
            $this->choose($s, 'sentences.choose_gap.dormitorios', 'Mi piso tiene dos ___.', 'My flat has two bedrooms.', ['dormitorios', 'dormitorio'], 'dormitorios', [Kit::word('el dormitorio', 'dormitorios')], 'After dos we need the plural, so the word ends in -s.'),

            $this->gap($s, 'sentences.type_gap.silla-en', 'Hay una silla ___ el jardín.', 'There is a chair in the garden.', 'en', [Kit::form('en')], 'En means in or on a place.'),
            $this->gap($s, 'sentences.type_gap.cama-esta', 'La cama ___ en el dormitorio.', 'The bed is in the bedroom.', 'está', [Kit::form('está', true)], 'La cama is a bed you both know, so we say está, not hay.'),
            $this->gap($s, 'sentences.type_gap.sillas-hay', '___ dos sillas en la cocina.', 'There are two chairs in the kitchen.', 'Hay', [Kit::form('hay', true)], 'Hay stays the same for one thing or for many things.'),
            $this->gap($s, 'sentences.type_gap.sillas-estan', 'Las sillas ___ en el salón.', 'The chairs are in the living room.', 'están', [Kit::form('están', true)], 'Las sillas is plural and known, so we say están.'),
            $this->gap($s, 'sentences.type_gap.ventana-lado', 'La ventana está al ___ de la cama.', 'The window is next to the bed.', 'lado', [Kit::form('lado')], 'Al lado de means next to.'),

            Kit::translate($s, 'sentences.translate.mesa-cocina', 'There is a table in the kitchen.', ['Hay una mesa en la cocina.', 'En la cocina hay una mesa.'], [$mesa, $cocina, Kit::form('hay', true)]),
            Kit::translate($s, 'sentences.translate.silla-cerca', 'The chair is near the table.', ['La silla está cerca de la mesa.'], [$silla, $mesa, Kit::form('cerca')]),
            Kit::translate($s, 'sentences.translate.casa-jardin', 'We have a house with a garden.', ['Tenemos una casa con jardín.', 'Tenemos una casa con un jardín.', 'Nosotros tenemos una casa con jardín.', 'Nosotros tenemos una casa con un jardín.'], [$casa, $jardin]),

            Kit::build($s, 'sentences.build.jardin-lado', 'The flat is next to the garden.', 'El piso está al lado del jardín.', ['hay'], [$piso, $jardin, Kit::form('lado')]),
            Kit::build($s, 'sentences.build.ventana-salon', 'The window is in the living room.', 'La ventana está en el salón.', ['hay'], [$ventana, $salon, Kit::form('está', true)]),
            Kit::build($s, 'sentences.build.casa-jardin', 'The house has a garden.', 'La casa tiene un jardín.', ['hay'], [$casa, $jardin]),

            Kit::listenChoose($s, 'sentences.listen_choose.mesa-salon', 'Hay una mesa en el salón.', ['There is a table in the living room.', 'There is a chair in the living room.', 'There is a table in the kitchen.', 'The table is in the living room.'], 'There is a table in the living room.', [$mesa, $salon, Kit::form('hay', true)]),
            Kit::listenChoose($s, 'sentences.listen_choose.cama-debajo', 'La cama está debajo de la ventana.', ['The bed is under the window.', 'The bed is next to the window.', 'The chair is under the window.', 'The window is under the bed.'], 'The bed is under the window.', [$cama, $ventana, Kit::form('debajo')]),
            Kit::listenChoose($s, 'sentences.listen_choose.dormitorios', 'Mi casa tiene dos dormitorios.', ['My house has two bedrooms.', 'My house has two kitchens.', 'My flat has two bedrooms.', 'My house has three bedrooms.'], 'My house has two bedrooms.', [$casa, Kit::word('el dormitorio', 'dormitorios')]),
            Kit::listenType($s, 'sentences.listen_type.silla-cocina', 'Hay una silla en la cocina.', 'There is a chair in the kitchen.', [$silla, $cocina, Kit::form('hay', true)], homophoneNote: self::HAY_NOTE),
            Kit::listenType($s, 'sentences.listen_type.mesa-jardin', 'La mesa está en el jardín.', 'The table is in the garden.', [$mesa, $jardin, Kit::form('está', true)]),
            Kit::listenType($s, 'sentences.listen_type.piso-cerca', 'Mi piso está cerca de la casa.', 'My flat is near the house.', [$piso, $casa, Kit::form('cerca')]),
            Kit::listenType($s, 'sentences.listen_type.sillas-debajo', 'Las sillas están debajo de la mesa.', 'The chairs are under the table.', [Kit::word('la silla', 'sillas'), $mesa, Kit::form('debajo')]),

            Kit::speakRepeat($s, 'sentences.speak_repeat.cocina-salon', 'La cocina está al lado del salón.', 'The kitchen is next to the living room.', [$cocina, $salon, Kit::form('lado')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.cama-dormitorio', 'Hay una cama en mi dormitorio.', 'There is a bed in my bedroom.', [$cama, $dormitorio, Kit::form('hay', true)]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.casa-jardin', 'Mi casa tiene un jardín.', 'My house has a garden.', [$casa, $jardin]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.piso-salon', 'Mi piso tiene un salón.', 'My flat has a living room.', [$piso, $salon]),
            Kit::speakAnswer($s, 'sentences.speak_answer.mesa-cocina', '¿Hay una mesa en la cocina?', 'Is there a table in the kitchen?', [['sí', 'no', 'hay'], ['mesa', 'cocina']], 'Sí, hay una mesa en la cocina.', [$mesa, $cocina, Kit::form('hay', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.cama-dormitorio', '¿La cama está en el dormitorio?', 'Is the bed in the bedroom?', [['sí', 'no', 'está'], ['cama', 'dormitorio']], 'Sí, la cama está en el dormitorio.', [$cama, $dormitorio, Kit::form('está', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.jardin', '¿Qué hay en el jardín?', 'What is there in the garden?', [['hay', 'una', 'un', 'dos'], ['mesa', 'silla', 'sillas', 'casa']], 'Hay una mesa y dos sillas.', [$jardin, $silla, Kit::form('hay', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $casa = Kit::word('la casa', 'casa');
        $piso = Kit::word('el piso', 'piso');
        $cocina = Kit::word('la cocina', 'cocina');
        $salon = Kit::word('el salón', 'salón');
        $dormitorio = Kit::word('el dormitorio', 'dormitorio');
        $jardin = Kit::word('el jardín', 'jardín');
        $mesa = Kit::word('la mesa', 'mesa');
        $silla = Kit::word('la silla', 'silla');
        $cama = Kit::word('la cama', 'cama');
        $ventana = Kit::word('la ventana', 'ventana');
        $sillas = Kit::word('la silla', 'sillas');

        return [
            Kit::readPassage($s, 'task.read_passage.piso', 'Read the conversation about Marta\'s flat.', [
                Kit::line('Ana', 'Hola, Marta. ¿Cómo es tu piso?'),
                Kit::line('Marta', 'Mi piso tiene un salón y dos dormitorios. En el salón hay una mesa y cuatro sillas.'),
                Kit::line('Ana', '¿Y la cocina?'),
                Kit::line('Marta', 'La cocina está al lado del salón. Hay una ventana.'),
            ], [
                Kit::question('How many bedrooms does the flat have?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What is in the living room?', ['A table and four chairs', 'A bed and a table', 'A garden'], 'A table and four chairs'),
                Kit::question('Where is the kitchen?', ['Next to the living room', 'Under the living room', 'In the garden'], 'Next to the living room'),
            ], [$piso, $salon, Kit::word('el dormitorio', 'dormitorios'), $mesa, $sillas, $cocina, $ventana], 'read'),
            $this->choose($s, 'task.choose_gap.donde-esta', '¿Dónde ___ la mesa?', 'Where is the table?', ['está', 'hay'], 'está', [$mesa, Kit::form('está', true)], 'La mesa is a table you both know, so we ask where it is with está.', 'read'),
            $this->choose($s, 'task.choose_gap.que-hay', '¿Qué ___ en el salón?', 'What is there in the living room?', ['hay', 'está'], 'hay', [$salon, Kit::form('hay', true)], 'We do not know yet what is there, so we ask with hay.', 'read'),

            Kit::transform($s, 'task.transform.sillas', 'Say it about "the chairs" (plural).', 'La silla está en el jardín.', ['Las sillas están en el jardín.'], [Kit::word('la silla', 'sillas'), $jardin, Kit::form('están', true)]),
            Kit::transform($s, 'task.transform.mesa', 'Change it to "the table" (not "a table").', 'Hay una mesa en la cocina.', ['La mesa está en la cocina.', 'En la cocina está la mesa.'], [$mesa, $cocina, Kit::form('está', true)]),
            Kit::transform($s, 'task.transform.ventana', 'Change it to "there is a window".', 'La ventana está en el salón.', ['Hay una ventana en el salón.', 'En el salón hay una ventana.'], [$ventana, $salon, Kit::form('hay', true)]),
            Kit::writeGuided($s, 'task.write_guided.piso', 'Say that your flat has a living room and two bedrooms.', ['piso', 'tiene', 'salón', 'dormitorios'], 'Mi piso tiene un salón y dos dormitorios.', [
                ['forms' => ['piso'], 'term' => 'el piso'],
                ['forms' => ['salón'], 'term' => 'el salón'],
                ['forms' => ['dormitorios'], 'term' => 'el dormitorio'],
            ], [$piso, $salon, Kit::word('el dormitorio', 'dormitorios')]),
            Kit::writeGuided($s, 'task.write_guided.jardin', 'Say that there is a table and a chair in the garden.', ['hay', 'mesa', 'silla', 'jardín'], 'Hay una mesa y una silla en el jardín.', [
                ['forms' => ['hay'], 'term' => null],
                ['forms' => ['mesa'], 'term' => 'la mesa'],
                ['forms' => ['silla'], 'term' => 'la silla'],
                ['forms' => ['jardín'], 'term' => 'el jardín'],
            ], [$mesa, $silla, $jardin]),
            Kit::build($s, 'task.build.cocina-dormitorio', 'The kitchen is next to the bedroom.', 'La cocina está al lado del dormitorio.', ['hay', 'en'], [$cocina, $dormitorio, Kit::form('lado')], 'write'),
            Kit::build($s, 'task.build.silla-mesa', 'There is a chair under the table.', 'Hay una silla debajo de la mesa.', ['está', 'encima'], [$silla, $mesa, Kit::form('hay', true)], 'write'),
            Kit::build($s, 'task.build.jardin-casa', 'The garden is near the house.', 'El jardín está cerca de la casa.', ['hay', 'al'], [$jardin, $casa, Kit::form('cerca')], 'write'),
            Kit::translate($s, 'task.translate.cama', 'Is there a bed in the bedroom?', ['¿Hay una cama en el dormitorio?'], [$cama, $dormitorio, Kit::form('hay', true)], 'write'),
            Kit::translate($s, 'task.translate.sillas', 'Where are the chairs?', ['¿Dónde están las sillas?'], [Kit::word('la silla', 'sillas'), Kit::form('están', true)], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.jardin', [
                Kit::line('Luis', 'Tengo una casa con jardín.'),
                Kit::line('Ana', '¿Qué hay en el jardín?'),
                Kit::line('Luis', 'Hay una mesa y dos sillas. La mesa está al lado de la casa.'),
            ], [
                Kit::question('What does Luis have?', ['A house with a garden', 'A flat with a garden', 'A house without a garden'], 'A house with a garden'),
                Kit::question('What is in the garden?', ['A table and two chairs', 'A bed and a chair', 'Two tables'], 'A table and two chairs'),
                Kit::question('Where is the table?', ['Next to the house', 'Under the window', 'In the kitchen'], 'Next to the house'),
            ], [
                Kit::question('Who has a garden?', ['Luis', 'Ana', 'Nobody'], 'Luis'),
                Kit::question('How many chairs are there?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Ana ask about?', ['The garden', 'The kitchen', 'The bed'], 'The garden'),
            ], [$casa, $jardin, $mesa, $sillas], 'listen'),
            Kit::listenType($s, 'task.listen_type.cama-ventana', 'Hay una cama y una ventana en el dormitorio.', 'There is a bed and a window in the bedroom.', [$cama, $ventana, $dormitorio, Kit::form('hay', true)], 'listen', homophoneNote: self::HAY_NOTE),
            Kit::listenType($s, 'task.listen_type.casa-cocina', 'Mi casa tiene una cocina y un salón.', 'My house has a kitchen and a living room.', [$casa, $cocina, $salon], 'listen'),
            Kit::listenType($s, 'task.listen_type.silla-encima', 'Hay una silla encima de la cama.', 'There is a chair on the bed.', [$silla, $cama, Kit::form('encima')], 'listen', homophoneNote: self::HAY_NOTE),

            Kit::speakAnswer($s, 'task.speak_answer.dormitorio', '¿Qué hay en tu dormitorio?', 'What is there in your bedroom?', [['hay', 'una', 'un', 'dos'], ['cama', 'mesa', 'silla', 'ventana']], 'Hay una cama y una ventana.', [$dormitorio, $cama, Kit::form('hay', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.casa-jardin', '¿Tu casa tiene jardín?', 'Does your house have a garden?', [['sí', 'no', 'tiene', 'tengo'], ['jardín', 'casa']], 'Sí, mi casa tiene jardín.', [$casa, $jardin], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.cama', '¿Dónde está tu cama?', 'Where is your bed?', [['cama', 'está'], ['dormitorio', 'piso', 'casa', 'salón']], 'Mi cama está en mi dormitorio.', [$cama, $dormitorio, Kit::form('está', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.silla-salon', '¿Hay una silla en tu salón?', 'Is there a chair in your living room?', [['sí', 'no', 'hay'], ['silla', 'salón']], 'Sí, hay una silla en mi salón.', [$silla, $salon, Kit::form('hay', true)], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.piso-casa', 'Mi piso está cerca de la casa de Ana.', 'My flat is near Ana\'s house.', [$piso, $casa, Kit::form('cerca')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.dormitorio', 'En el dormitorio hay una cama y una ventana.', 'In the bedroom there is a bed and a window.', [$dormitorio, $cama, $ventana, Kit::form('hay', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $casa = Kit::word('la casa', 'casa');
        $piso = Kit::word('el piso', 'piso');
        $cocina = Kit::word('la cocina', 'cocina');
        $salon = Kit::word('el salón', 'salón');
        $dormitorio = Kit::word('el dormitorio', 'dormitorio');
        $jardin = Kit::word('el jardín', 'jardín');
        $mesa = Kit::word('la mesa', 'mesa');
        $silla = Kit::word('la silla', 'silla');
        $cama = Kit::word('la cama', 'cama');
        $ventana = Kit::word('la ventana', 'ventana');

        return [
            Kit::translate($s, 'check.a.translate.dormitorio', 'The bedroom is next to the kitchen.', ['El dormitorio está al lado de la cocina.'], [$dormitorio, $cocina, Kit::form('lado')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.ventana', 'There is a window in the bedroom.', ['Hay una ventana en el dormitorio.'], [$ventana, $dormitorio, Kit::form('hay', true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.piso', 'My flat has a garden.', ['Mi piso tiene un jardín.', 'Mi piso tiene jardín.'], [$piso, $jardin], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.silla', 'The chair is under the bed.', ['La silla está debajo de la cama.'], [$silla, $cama, Kit::form('debajo')], 'sentences', $a),
            $this->gap($s, 'check.a.type_gap.mesa', 'La mesa ___ en la casa.', 'The table is in the house.', 'está', [Kit::form('está', true)], null, $a),
            $this->gap($s, 'check.a.type_gap.camas', '___ dos camas en el dormitorio.', 'There are two beds in the bedroom.', 'Hay', [Kit::form('hay', true)], null, $a),
            Kit::listenType($s, 'check.a.listen_type.casa', 'Mi casa tiene tres dormitorios.', 'My house has three bedrooms.', [$casa, Kit::word('el dormitorio', 'dormitorios')], 'dictation', $a),
            Kit::listenType($s, 'check.a.listen_type.mesa', 'La mesa está en el salón.', 'The table is in the living room.', [$mesa, $salon, Kit::form('está', true)], 'dictation', $a),
            Kit::listenType($s, 'check.a.listen_type.piso', 'Mi piso tiene un salón y una cocina.', 'My flat has a living room and a kitchen.', [$piso, $salon, $cocina], 'dictation', $a),
            Kit::listenPassage($s, 'check.a.listen_passage.casa', [
                Kit::line('Marta', 'Mi casa tiene una cocina y un jardín.'),
                Kit::line('Luis', '¿Y tiene un salón?'),
                Kit::line('Marta', 'Sí. En el salón tengo una mesa y dos sillas.'),
            ], [
                Kit::question('What does Marta\'s house have?', ['A kitchen and a garden', 'A kitchen and a bedroom', 'A living room and a bedroom'], 'A kitchen and a garden'),
                Kit::question('What does Luis ask about?', ['The living room', 'The kitchen', 'The garden'], 'The living room'),
                Kit::question('What is in the living room?', ['A table and two chairs', 'A bed and a table', 'Two tables'], 'A table and two chairs'),
            ], [
                Kit::question('Who has a house with a garden?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('How many chairs does Marta have in the living room?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Luis ask?', ['Whether the house has a living room', 'Whether the house has a garden', 'Whether there is a bed'], 'Whether the house has a living room'),
            ], [$casa, $cocina, $jardin, $salon, $mesa, Kit::word('la silla', 'sillas')], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.piso', 'Read the conversation.', [
                Kit::line('Ana', 'Hola, Luis. Mi piso tiene un dormitorio.'),
                Kit::line('Luis', '¿Hay una cama y una ventana?'),
                Kit::line('Ana', 'Sí, hay una cama y una ventana.'),
            ], [
                Kit::question('What does Ana\'s flat have?', ['A bedroom', 'A garden', 'A kitchen'], 'A bedroom'),
                Kit::question('What does Luis ask about?', ['A bed and a window', 'A table and a chair', 'A garden'], 'A bed and a window'),
            ], [$piso, $dormitorio, $cama, $ventana], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.ventana', '¿Hay una ventana en la cocina?', 'Is there a window in the kitchen?', [['sí', 'no', 'hay'], ['ventana', 'cocina']], 'Sí, hay una ventana en la cocina.', [$ventana, $cocina], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.piso', '¿Tu piso tiene jardín?', 'Does your flat have a garden?', [['sí', 'no', 'tiene', 'tengo'], ['piso', 'jardín']], 'No, mi piso no tiene jardín.', [$piso, $jardin], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.silla', '¿La silla está en la cocina?', 'Is the chair in the kitchen?', [['sí', 'no', 'está'], ['silla', 'cocina']], 'Sí, la silla está en la cocina.', [$silla, $cocina], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $casa = Kit::word('la casa', 'casa');
        $piso = Kit::word('el piso', 'piso');
        $cocina = Kit::word('la cocina', 'cocina');
        $salon = Kit::word('el salón', 'salón');
        $dormitorio = Kit::word('el dormitorio', 'dormitorio');
        $jardin = Kit::word('el jardín', 'jardín');
        $mesa = Kit::word('la mesa', 'mesa');
        $silla = Kit::word('la silla', 'silla');
        $cama = Kit::word('la cama', 'cama');
        $ventana = Kit::word('la ventana', 'ventana');

        return [
            Kit::translate($s, 'check.b.translate.cama', 'The bed is next to the window.', ['La cama está al lado de la ventana.'], [$cama, $ventana, Kit::form('lado')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.sillas', 'There are two chairs in the garden.', ['Hay dos sillas en el jardín.'], [Kit::word('la silla', 'sillas'), $jardin, Kit::form('hay', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.piso', 'My flat has a living room, a kitchen and a garden.', ['Mi piso tiene un salón, una cocina y un jardín.', 'Mi piso tiene salón, cocina y jardín.'], [$piso, $salon, $cocina, $jardin], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.mesa', 'The table is near the house.', ['La mesa está cerca de la casa.'], [$mesa, $casa, Kit::form('cerca')], 'sentences', $b),
            $this->gap($s, 'check.b.type_gap.camas', 'Las camas ___ en el dormitorio.', 'The beds are in the bedroom.', 'están', [Kit::form('están', true)], null, $b),
            $this->gap($s, 'check.b.type_gap.mesa-sillas', '___ una mesa y dos sillas en el jardín.', 'There is a table and two chairs in the garden.', 'Hay', [Kit::form('hay', true)], null, $b),
            Kit::listenType($s, 'check.b.listen_type.sillas-mesa', 'La silla y la mesa están en el salón.', 'The chair and the table are in the living room.', [$silla, $mesa, $salon, Kit::form('están', true)], 'dictation', $b),
            Kit::listenType($s, 'check.b.listen_type.casa', 'Mi casa tiene una cocina y dos dormitorios.', 'My house has a kitchen and two bedrooms.', [$casa, $cocina, Kit::word('el dormitorio', 'dormitorios')], 'dictation', $b),
            Kit::listenType($s, 'check.b.listen_type.dormitorio', 'Mi piso tiene un dormitorio con una cama.', 'My flat has a bedroom with a bed.', [$piso, $dormitorio, $cama], 'dictation', $b),
        ];
    }
}
