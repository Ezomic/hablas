<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TellingAStory implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'telling-a-story';
    }

    public function words(): array
    {
        return [
            new WordData('mientras', cue: 'while'),
            new WordData('de repente', cue: 'suddenly', note: 'De repente introduces something that happens all at once, so the verb after it is in the indefinido: de repente sonó el teléfono.'),
            new WordData('cuando', cue: 'when (joining two parts of a sentence)', note: 'In a statement, cuando has no accent. In a question, ¿cuándo? has an accent.'),
            new WordData('entonces', cue: 'then (next, in a story)', note: 'Entonces also means so.'),
            new WordData('caminar', cue: 'to walk', forms: ['caminaba', 'caminó']),
            new WordData('llover', cue: 'to rain', forms: ['llovía', 'llovió'], note: 'Llover is only used in the he/she/it form: llueve, llovía, llovió.'),
            new WordData('sonar', cue: 'to ring (a phone)', forms: ['sonó', 'sonaba'], note: 'Sonar also means to sound: suena bien, it sounds good.'),
            new WordData('perder', cue: 'to lose', forms: ['perdió', 'perdía', 'perdí']),
            new WordData('abrir', cue: 'to open', forms: ['abrió', 'abría', 'abrí']),
            new WordData('llegar', cue: 'to arrive', forms: ['llegó', 'llegaba', 'llegué']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ana caminaba cuando sonó el teléfono.', 'english' => 'Ana was walking when the phone rang.'],
            ['text' => 'Mientras llovía, Marta abrió el paraguas.', 'english' => 'While it was raining, Marta opened the umbrella.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-08', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-08', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-08', 'Released on the owner\'s instruction on 2026-10-08, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.caminaba', 'Ana ___ cuando sonó el teléfono.', ['caminaba', 'caminó'], 'caminaba', Kit::form('caminaba', true), 'Ana was in the middle of walking when the phone interrupted her. An action in progress is the scene, so the imperfecto: caminaba. Caminó would mean the walk was one finished event.', 'choose', 'Ana was walking when the phone rang.'),
            Kit::gap($stage, 'sentences.choose_gap.sono', 'De repente ___ el teléfono.', ['sonó', 'sonaba'], 'sonó', Kit::form('sonó', true), 'De repente brings in one sudden event, so the indefinido: sonó. Sonaba is for a sound that goes on in the background.', 'choose', 'Suddenly the phone rang.'),
            Kit::gap($stage, 'sentences.choose_gap.mientras', '___ Ana caminaba, llovía.', ['Mientras', 'De repente', 'Entonces'], 'Mientras', Kit::word('mientras'), 'Two things happen at the same time, so mientras (while). De repente is suddenly and entonces is then.', 'choose', 'While Ana was walking, it was raining.'),
            Kit::gap($stage, 'sentences.choose_gap.entonces', 'Llovía, ___ Ana abrió el paraguas.', ['entonces', 'mientras'], 'entonces', Kit::word('entonces'), 'It rained, so then Ana did something: entonces. Mientras needs two things happening at the same time.', 'choose', 'It was raining, so Ana opened the umbrella.'),
            Kit::gap($stage, 'sentences.choose_gap.abrio', 'Ana ___ la puerta.', ['abrió', 'llovió', 'llegó'], 'abrió', Kit::word('abrir', 'abrió'), 'You open a door, so abrió. Llovió is rained and llegó is arrived.', 'choose', 'Ana opened the door.'),
            Kit::gap($stage, 'sentences.choose_gap.perdio', 'De repente Ana ___ la llave.', ['perdió', 'perdía'], 'perdió', Kit::form('perdió', true), 'De repente brings in one sudden event, so the indefinido: perdió. Perdía would describe losing it over a period or as a habit, not one sudden moment.', 'choose', 'Suddenly Ana lost the key.'),

            Kit::typeGap($stage, 'sentences.type_gap.calle', 'Ana ___ por la calle.', 'Ana was walking along the street.', 'caminaba', Kit::word('caminar', 'caminaba'), 'Walking along is an action in progress, the scene: caminaba.'),
            Kit::typeGap($stage, 'sentences.type_gap.casa', 'Luis ___ a casa.', 'Luis arrived home.', 'llegó', Kit::word('llegar', 'llegó'), 'Arriving is one finished event: llegó.'),
            Kit::typeGap($stage, 'sentences.type_gap.llave', 'Pablo ___ la llave.', 'Pablo lost the key.', 'perdió', Kit::word('perder', 'perdió'), 'Losing the key is one finished event: perdió.'),
            Kit::typeGap($stage, 'sentences.type_gap.cuando', 'Ana abrió la puerta ___ llegó Luis.', 'Ana opened the door when Luis arrived.', 'cuando', Kit::word('cuando')),
            Kit::typeGap($stage, 'sentences.type_gap.frio', 'Hacía frío y ___ mucho.', 'It was cold and it was raining a lot.', 'llovía', Kit::word('llover', 'llovía'), 'Weather is part of the scene, so the imperfecto: llovía.'),

            Kit::translate($stage, 'sentences.translate.pablo-caminaba', 'Pablo was walking when the phone rang.', ['Pablo caminaba cuando sonó el teléfono.', 'Pablo caminaba cuando el teléfono sonó.', 'Cuando sonó el teléfono, Pablo caminaba.', 'Cuando el teléfono sonó, Pablo caminaba.'], [Kit::word('caminar', 'caminaba'), Kit::word('cuando'), Kit::word('sonar', 'sonó'), Kit::form('sonó', true)]),
            Kit::translate($stage, 'sentences.translate.luis-llego', 'Suddenly Luis arrived home.', ['De repente Luis llegó a casa.', 'De repente llegó Luis a casa.'], [Kit::word('de repente'), Kit::word('llegar', 'llegó'), Kit::form('llegó')]),
            Kit::translate($stage, 'sentences.translate.llovia', 'While it was raining, I was at home.', ['Mientras llovía, estaba en casa.', 'Mientras llovía, yo estaba en casa.', 'Estaba en casa mientras llovía.', 'Yo estaba en casa mientras llovía.'], [Kit::word('mientras'), Kit::word('llover', 'llovía'), Kit::form('estaba')]),

            Kit::build($stage, 'sentences.build.entonces', 'Then Ana opened the door.', 'Entonces Ana abrió la puerta.', ['abría'], [Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::form('abrió', true)]),
            Kit::build($stage, 'sentences.build.marta-casa', 'Marta was at home when I arrived.', 'Marta estaba en casa cuando llegué.', ['llegaba'], [Kit::word('cuando'), Kit::word('llegar', 'llegué'), Kit::form('llegué', true)]),
            Kit::build($stage, 'sentences.build.frio', 'It was cold and it was raining.', 'Hacía frío y llovía.', ['llovió'], [Kit::word('llover', 'llovía'), Kit::form('hacía')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.perdio', 'Ana perdió la llave.', ['Ana lost the key.', 'Ana opened the door.', 'Ana arrived home.', 'Pablo lost the key.'], 'Ana lost the key.', [Kit::word('perder', 'perdió'), Kit::form('perdió')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.mientras', 'Mientras llovía, Luis llegó a casa.', ['While it was raining, Luis arrived home.', 'While it was raining, Luis opened the door.', 'It did not rain when Luis arrived home.', 'While Luis was walking, it was raining.'], 'While it was raining, Luis arrived home.', [Kit::word('mientras'), Kit::word('llover', 'llovía'), Kit::word('llegar', 'llegó'), Kit::form('llovía')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.repente', 'De repente sonó el teléfono.', ['Suddenly the phone rang.', 'The phone was ringing.', 'Suddenly the door opened.', 'Then the phone rang.'], 'Suddenly the phone rang.', [Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::form('sonó')]),
            Kit::listenType($stage, 'sentences.listen_type.abrio-llego', 'Ana abrió la puerta y llegó Pablo.', 'Ana opened the door and Pablo arrived.', [Kit::word('abrir', 'abrió'), Kit::word('llegar', 'llegó'), Kit::form('abrió')]),
            Kit::listenType($stage, 'sentences.listen_type.cuando-llegó', 'Cuando llegó Marta, llovía.', 'When Marta arrived, it was raining.', [Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::word('llover', 'llovía'), Kit::form('llovía', true)]),
            Kit::listenType($stage, 'sentences.listen_type.llovio', 'Pablo caminaba, y de repente llovió.', 'Pablo was walking, and suddenly it rained.', [Kit::word('caminar', 'caminaba'), Kit::word('de repente'), Kit::word('llover', 'llovió'), Kit::form('llovió', true)]),
            Kit::listenType($stage, 'sentences.listen_type.mientras-sono', 'Mientras Pablo caminaba, sonó el teléfono.', 'While Pablo was walking, the phone rang.', [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('sonar', 'sonó'), Kit::form('caminaba')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.mientras', 'Mientras Ana caminaba, llovía.', 'While Ana was walking, it was raining.', [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('llover', 'llovía'), Kit::form('caminaba')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.repente', 'De repente Ana abrió la puerta.', 'Suddenly Ana opened the door.', [Kit::word('de repente'), Kit::word('abrir', 'abrió'), Kit::form('abrió')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.entonces', 'Llovía, entonces Marta abrió el paraguas.', 'It was raining, so Marta opened the umbrella.', [Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::form('llovía')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cuando', 'Cuando llegó Luis, perdió la llave.', 'When Luis arrived, he lost the key.', [Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::word('perder', 'perdió'), Kit::form('llegó')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.caminaba', '¿Caminaba Ana o abría la puerta?', 'Was Ana walking or opening the door?', [['caminaba', 'abría']], 'Ana caminaba por la calle.', [Kit::word('caminar', 'caminaba'), Kit::form('caminaba')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.llego', '¿Llegó Pablo a casa?', 'Did Pablo arrive home?', [['sí', 'no'], ['llegó']], 'Sí, Pablo llegó a casa.', [Kit::word('llegar', 'llegó'), Kit::form('llegó')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.llovia', '¿Llovía cuando llegó Ana?', 'Was it raining when Ana arrived?', [['sí', 'no'], ['llovía']], 'Sí, llovía cuando llegó Ana.', [Kit::word('llover', 'llovía'), Kit::word('cuando'), Kit::form('llovía')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $note = self::A_NOTE;

        return [
            Kit::readPassage($stage, 'task.read_passage.pablo-llave', 'Read the conversation about what happened to Pablo.', [
                Kit::line('Ana', 'Pablo, ¿estás bien?'),
                Kit::line('Pablo', 'No, perdí la llave.'),
                Kit::line('Ana', '¿Cómo?'),
                Kit::line('Pablo', 'Mientras caminaba por la calle, llovía. Entonces abrí el paraguas.'),
                Kit::line('Ana', '¿Y la llave?'),
                Kit::line('Pablo', 'De repente sonó el teléfono, y perdí la llave.'),
                Kit::line('Ana', '¿Y la puerta?'),
                Kit::line('Pablo', 'Cuando llegué a casa, Marta abrió la puerta.'),
            ], [
                Kit::question('What did Pablo lose?', ['The key', 'The umbrella', 'The phone'], 'The key'),
                Kit::question('What did Pablo do when it was raining?', ['He opened the umbrella', 'He arrived home', 'He lost the key'], 'He opened the umbrella'),
                Kit::question('Who opened the door?', ['Marta', 'Pablo', 'Ana'], 'Marta'),
            ], [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::word('perder', 'perdí'), Kit::word('cuando'), Kit::word('llegar', 'llegué')], 'read', glosses: ['abrí' => 'I opened', 'perdí' => 'I lost', 'llegué' => 'I arrived']),
            Kit::gap($stage, 'task.choose_gap.abria', 'Pablo ___ la puerta cuando sonó el teléfono.', ['abría', 'abrió'], 'abría', Kit::form('abría', true), 'Pablo was in the middle of opening the door when the phone interrupted him. An action in progress is the scene, so the imperfecto: abría.', 'read', 'Pablo was opening the door when the phone rang.'),
            Kit::gap($stage, 'task.choose_gap.llego', 'Luis ___ a casa y abrió la puerta.', ['llegó', 'perdió', 'llovió'], 'llegó', Kit::word('llegar', 'llegó'), 'Luis got home and then opened the door: llegó. Perdió is lost and llovió is rained.', 'read', 'Luis arrived home and opened the door.'),

            Kit::transform($stage, 'task.transform.repente', 'Say that it happened suddenly.', 'Sonó el teléfono.', ['De repente sonó el teléfono.', 'De repente el teléfono sonó.'], [Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::form('sonó', true)]),
            Kit::transform($stage, 'task.transform.mientras', 'Add: while it was raining.', 'Ana caminaba por la calle.', ['Mientras llovía, Ana caminaba por la calle.', 'Ana caminaba por la calle mientras llovía.'], [Kit::word('mientras'), Kit::word('llover', 'llovía'), Kit::word('caminar', 'caminaba'), Kit::form('llovía')]),
            Kit::transform($stage, 'task.transform.cuando', 'Keep the verb as it is and add: when Luis arrived.', 'Ana abría la puerta.', ['Ana abría la puerta cuando llegó Luis.', 'Ana abría la puerta cuando Luis llegó.', 'Cuando llegó Luis, Ana abría la puerta.', 'Cuando Luis llegó, Ana abría la puerta.'], [Kit::word('cuando'), Kit::word('abrir', 'abría'), Kit::word('llegar', 'llegó'), Kit::form('abría', true)]),
            Kit::writeGuided($stage, 'task.write_guided.caminaba-sono', 'Say that Pablo was walking and suddenly the phone rang.', ['caminaba', 'de repente', 'sonó'], 'Pablo caminaba y de repente sonó el teléfono.', [
                ['forms' => ['caminaba'], 'term' => 'caminar'],
                ['forms' => ['repente'], 'term' => 'de repente'],
                ['forms' => ['sonó'], 'term' => 'sonar'],
            ], [Kit::word('caminar', 'caminaba'), Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::form('sonó', true)]),
            Kit::writeGuided($stage, 'task.write_guided.llovia-abrio', 'Say that it was raining, so Ana opened the umbrella.', ['llovía', 'entonces', 'abrió'], 'Llovía, entonces Ana abrió el paraguas.', [
                ['forms' => ['llovía'], 'term' => 'llover'],
                ['forms' => ['entonces'], 'term' => 'entonces'],
                ['forms' => ['abrió'], 'term' => 'abrir'],
            ], [Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::form('abrió')]),
            Kit::build($stage, 'task.build.mientras', 'It was raining while Pablo was walking.', 'Llovía mientras Pablo caminaba.', ['caminó', 'llovió'], [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('llover', 'llovía'), Kit::form('caminaba', true)]),
            Kit::build($stage, 'task.build.repente', 'Suddenly Pablo lost the bag.', 'De repente Pablo perdió el bolso.', ['perdía', 'entonces'], [Kit::word('de repente'), Kit::word('perder', 'perdió'), Kit::form('perdió', true)]),
            Kit::build($stage, 'task.build.marta-abria', 'Marta was opening the door when I arrived.', 'Marta abría la puerta cuando llegué.', ['abrió', 'llegaba'], [Kit::word('abrir', 'abría'), Kit::word('cuando'), Kit::word('llegar', 'llegué'), Kit::form('abría')]),
            Kit::translate($stage, 'task.translate.entonces', 'Luis arrived home, and then he opened the door.', ['Luis llegó a casa y entonces abrió la puerta.', 'Luis llegó a casa. Entonces abrió la puerta.'], [Kit::word('llegar', 'llegó'), Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::form('llegó')]),
            Kit::translate($stage, 'task.translate.llegue', 'When I arrived, it was raining.', ['Cuando llegué, llovía.', 'Cuando llegué llovía.'], [Kit::word('cuando'), Kit::word('llegar', 'llegué'), Kit::word('llover', 'llovía'), Kit::form('llovía', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.luis-casa', [
                Kit::line('Marta', 'Luis, ¿estás en casa?'),
                Kit::line('Luis', 'Sí. Mientras caminaba, llovía mucho.'),
                Kit::line('Marta', '¿Y entonces?'),
                Kit::line('Luis', 'De repente sonó el teléfono y perdí la llave.'),
                Kit::line('Marta', '¿Y la puerta?'),
                Kit::line('Luis', 'Cuando llegué, Ana abrió la puerta.'),
            ], [
                Kit::question('Where is Luis?', ['At home', 'In the street', 'In the park'], 'At home'),
                Kit::question('What did Luis lose?', ['The key', 'The umbrella', 'The phone'], 'The key'),
                Kit::question('Who opened the door?', ['Ana', 'Marta', 'Luis'], 'Ana'),
            ], [
                Kit::question('What was the weather like?', ['It was raining', 'It was sunny', 'It was cold'], 'It was raining'),
                Kit::question('Who asks the questions?', ['Marta', 'Luis', 'Ana'], 'Marta'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::word('perder', 'perdí'), Kit::word('cuando'), Kit::word('llegar', 'llegué'), Kit::word('abrir', 'abrió')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mientras-sono', 'Mientras Ana caminaba por la calle, sonó el teléfono.', 'While Ana was walking along the street, the phone rang.', [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('sonar', 'sonó'), Kit::form('caminaba', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.llovia-abrio', 'Llovía, entonces Pablo abrió el paraguas y llegó a casa.', 'It was raining, so Pablo opened the umbrella and arrived home.', [Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::word('llegar', 'llegó'), Kit::form('abrió')], 'listen', homophoneNote: $note),
            Kit::listenType($stage, 'task.listen_type.repente-perdio', 'De repente Marta perdió la llave cuando llegó a casa.', 'Suddenly Marta lost the key when she arrived home.', [Kit::word('de repente'), Kit::word('perder', 'perdió'), Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::form('perdió', true)], 'listen', homophoneNote: $note),

            Kit::speakAnswer($stage, 'task.speak_answer.perdio', '¿Qué perdió Ana?', 'What did Ana lose?', [['perdió'], ['llave', 'paraguas', 'bolso', 'mochila', 'teléfono', 'libro']], 'Ana perdió la llave.', [Kit::word('perder', 'perdió'), Kit::form('perdió')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.abrio', '¿Quién abrió la puerta?', 'Who opened the door?', [['abrió', 'abrí'], ['ana', 'pablo', 'marta', 'luis', 'yo']], 'Marta abrió la puerta.', [Kit::word('abrir', 'abrió'), Kit::form('abrió')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.llovia', '¿Llovía o hacía sol cuando llegó Luis?', 'Was it raining or sunny when Luis arrived?', [['llovía', 'hacía']], 'Llovía cuando llegó Luis.', [Kit::word('llover', 'llovía'), Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::form('llovía')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.entonces', '¿Y entonces, abrió Ana la puerta?', 'And then, did Ana open the door?', [['sí', 'no'], ['abrió']], 'Sí, entonces Ana abrió la puerta.', [Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::form('abrió')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.mientras-repente', 'Mientras Luis caminaba, llovía. De repente sonó el teléfono.', 'While Luis was walking, it was raining. Suddenly the phone rang.', [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('llover', 'llovía'), Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::form('sonó')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cuando-abrio', 'Cuando llegó a casa, Marta abrió la puerta.', 'When she arrived home, Marta opened the door.', [Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::word('abrir', 'abrió'), Kit::form('abrió')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.luis-abria', 'Luis was opening the door when the phone rang.', ['Luis abría la puerta cuando sonó el teléfono.', 'Luis abría la puerta cuando el teléfono sonó.', 'Cuando sonó el teléfono, Luis abría la puerta.', 'Cuando el teléfono sonó, Luis abría la puerta.'], [Kit::word('abrir', 'abría'), Kit::word('cuando'), Kit::word('sonar', 'sonó'), Kit::form('abría', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-estaba', 'While Ana was at home, it was raining.', ['Mientras Ana estaba en casa, llovía.', 'Ana estaba en casa mientras llovía.'], [Kit::word('mientras'), Kit::word('llover', 'llovía'), Kit::form('estaba')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-perdio', 'Suddenly Marta lost the key.', ['De repente Marta perdió la llave.'], [Kit::word('de repente'), Kit::word('perder', 'perdió'), Kit::form('perdió', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luis-parque', 'Luis arrived home, and then he walked to the park.', ['Luis llegó a casa y entonces caminó al parque.', 'Luis llegó a casa. Entonces caminó al parque.'], [Kit::word('llegar', 'llegó'), Kit::word('entonces'), Kit::word('caminar', 'caminó'), Kit::form('llegó')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cuando', 'Marta abría la puerta ___ llegó Pablo.', 'Marta was opening the door when Pablo arrived.', 'cuando', Kit::word('cuando'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luis-llave', 'Luis ___ la llave en la calle.', 'Luis lost the key in the street.', 'perdió', Kit::word('perder', 'perdió'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.mientras-sono', 'Mientras Marta caminaba, sonó su teléfono.', 'While Marta was walking, her phone rang.', [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('sonar', 'sonó'), Kit::form('sonó')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.llovia-luis', 'Llovía mucho, entonces Luis llegó a casa.', 'It was raining a lot, so Luis arrived home.', [Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('llegar', 'llegó'), Kit::form('llovía', true)], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.repente-llego', 'De repente llegó Pablo, y Ana abrió la puerta.', 'Suddenly Pablo arrived, and Ana opened the door.', [Kit::word('de repente'), Kit::word('llegar', 'llegó'), Kit::word('abrir', 'abrió')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.llave', [
                Kit::line('Ana', 'Marta, perdí la llave.'),
                Kit::line('Marta', '¿Cuándo?'),
                Kit::line('Ana', 'Mientras caminaba por el parque, sonó el teléfono.'),
                Kit::line('Marta', '¿Y entonces?'),
                Kit::line('Ana', 'Cuando llegué a casa, llovía mucho.'),
            ], [
                Kit::question('What did Ana lose?', ['The key', 'The umbrella', 'The phone'], 'The key'),
                Kit::question('Where was Ana walking?', ['In the park', 'In the street', 'At home'], 'In the park'),
                Kit::question('What was the weather like at home?', ['It was raining', 'It was sunny', 'It was cold'], 'It was raining'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Ana', 'Pablo'], 'Marta'),
                Kit::question('Who lost something?', ['Ana', 'Marta', 'Nobody'], 'Ana'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('perder', 'perdí'), Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::word('sonar', 'sonó'), Kit::word('entonces'), Kit::word('cuando'), Kit::word('llegar', 'llegué'), Kit::word('llover', 'llovía')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.paraguas', 'Read the conversation.', [
                Kit::line('Pablo', 'Luis, ¿dónde estás?'),
                Kit::line('Luis', 'No estoy en casa. Llegué tarde al trabajo.'),
                Kit::line('Pablo', '¿Por qué?'),
                Kit::line('Luis', 'Caminaba por la calle y de repente llovió. Entonces abrí mi paraguas.'),
            ], [
                Kit::question('What did Luis do when it rained?', ['He opened the umbrella', 'He lost the key', 'He arrived home'], 'He opened the umbrella'),
                Kit::question('Is Luis at home?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('llegar', 'llegué'), Kit::word('caminar', 'caminaba'), Kit::word('de repente'), Kit::word('llover', 'llovió'), Kit::word('entonces'), Kit::word('abrir', 'abrí')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.perdio', '¿Perdió Luis la llave?', 'Did Luis lose the key?', [['sí', 'no'], ['perdió']], 'Sí, Luis perdió la llave.', [Kit::word('perder', 'perdió')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.llego', '¿Llegó Marta a casa?', 'Did Marta arrive home?', [['sí', 'no'], ['llegó']], 'Sí, Marta llegó a casa.', [Kit::word('llegar', 'llegó')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.abrio', '¿Abrió Pablo la puerta?', 'Did Pablo open the door?', [['sí', 'no'], ['abrió']], 'Sí, Pablo abrió la puerta.', [Kit::word('abrir', 'abrió')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.marta-caminaba', 'Marta was walking while Luis was at home.', ['Marta caminaba mientras Luis estaba en casa.', 'Mientras Luis estaba en casa, Marta caminaba.'], [Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::form('caminaba', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-perdio', 'Ana lost the key when she arrived home.', ['Ana perdió la llave cuando llegó a casa.', 'Cuando llegó a casa, Ana perdió la llave.'], [Kit::word('perder', 'perdió'), Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::form('perdió')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-paraguas', 'It was raining, so Ana walked home.', ['Llovía, entonces Ana caminó a casa.', 'Llovía y entonces Ana caminó a casa.'], [Kit::word('llover', 'llovía'), Kit::word('entonces'), Kit::word('caminar', 'caminó'), Kit::form('caminó')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sono-ana', 'Suddenly the phone rang while Ana was walking.', ['De repente sonó el teléfono mientras Ana caminaba.', 'Mientras Ana caminaba, de repente sonó el teléfono.', 'De repente el teléfono sonó mientras Ana caminaba.', 'Mientras Ana caminaba, de repente el teléfono sonó.'], [Kit::word('de repente'), Kit::word('sonar', 'sonó'), Kit::word('mientras'), Kit::word('caminar', 'caminaba'), Kit::form('sonó')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.entonces', 'Llovía y ___ Marta abrió el paraguas.', 'It was raining and then Marta opened the umbrella.', 'entonces', Kit::word('entonces'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-paraguas', 'Luis ___ el paraguas.', 'Luis lost the umbrella.', 'perdió', Kit::word('perder', 'perdió'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.cuando-llego', 'Cuando llegó Pablo, Ana perdió el paraguas.', 'When Pablo arrived, Ana lost the umbrella.', [Kit::word('cuando'), Kit::word('llegar', 'llegó'), Kit::word('perder', 'perdió'), Kit::form('llegó', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.entonces-sono', 'Entonces Pablo abrió la puerta. De repente sonó el teléfono.', 'Then Pablo opened the door. Suddenly the phone rang.', [Kit::word('entonces'), Kit::word('abrir', 'abrió'), Kit::word('de repente'), Kit::word('sonar', 'sonó')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.mientras-llovia', 'Mientras llovía, Marta caminaba por la calle.', 'While it was raining, Marta was walking along the street.', [Kit::word('mientras'), Kit::word('llover', 'llovía'), Kit::word('caminar', 'caminaba'), Kit::form('caminaba')], 'dictation', $set),
        ];
    }
}
