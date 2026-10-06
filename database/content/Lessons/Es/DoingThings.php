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

final class DoingThings implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    private const SE_NOTE = 'Sé with an accent means I know. Se without an accent is a different little word, but here it is sé.';

    private const TU_NOTE = 'Tú with an accent means you. Tu without an accent means your, but here it is tú.';

    private const HOLA_NOTE = 'Hola is written with a silent h. It sounds the same as ola (a wave), but here it is hola.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'doing-things';
    }

    public function words(): array
    {
        return [
            new WordData('hacer', cue: 'to do / to make', forms: ['hago', 'haces', 'hace', 'hacemos']),
            new WordData('ver', cue: 'to see', forms: ['veo', 'ves', 've', 'vemos']),
            new WordData('venir', cue: 'to come', forms: ['vengo', 'vienes', 'viene', 'venimos']),
            new WordData('saber', cue: 'to know (a fact)', forms: ['sé', 'sabes', 'sabe', 'sabemos'], note: 'Saber is to know a fact: No sé dónde está Ana.'),
            new WordData('salir', cue: 'to leave / to go out', forms: ['salgo', 'sales', 'sale', 'salimos']),
            new WordData('poner', cue: 'to put', forms: ['pongo', 'pones', 'pone', 'ponemos']),
            new WordData('decir', cue: 'to say / to tell', forms: ['digo', 'dices', 'dice', 'decimos']),
            new WordData('dar', cue: 'to give', forms: ['doy', 'das', 'da', 'damos'], note: 'Dar las gracias is the set phrase for to thank: Doy las gracias a Ana.'),
            new WordData('traer', cue: 'to bring', forms: ['traigo', 'traes', 'trae', 'traemos']),
            new WordData('oír', cue: 'to hear', forms: ['oigo', 'oyes', 'oye', 'oímos']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Salgo a las ocho.', 'english' => 'I leave at eight.'],
            ['text' => 'No sé dónde está Ana.', 'english' => 'I do not know where Ana is.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.veo', 'Yo ___ a Ana.', ['veo', 'ves', 've'], 'veo', Kit::form('veo', true), 'Yo goes with veo, which is irregular. Ves is for tú and ve is for él or ella.', 'choose', 'I see Ana.'),
            Kit::gap($stage, 'sentences.choose_gap.vengo', 'Yo ___ con Pablo.', ['vengo', 'viene'], 'vengo', Kit::form('vengo', true), 'Yo goes with vengo, which is irregular. Viene is for él or ella.', 'choose', 'I come with Pablo.'),
            Kit::gap($stage, 'sentences.choose_gap.sabes', '¿Tú ___ dónde está Ana?', ['sabes', 'sé'], 'sabes', Kit::form('sabes', true), 'Tú goes with sabes, which is regular. Sé is only for yo.', 'choose', 'Do you know where Ana is?'),
            Kit::gap($stage, 'sentences.choose_gap.sale', 'Ana ___ a las siete.', ['sale', 'salgo'], 'sale', Kit::word('salir', 'sale'), 'Ana is she, so the verb is sale. Salgo is only for yo.', 'choose', 'Ana leaves at seven.'),
            Kit::gap($stage, 'sentences.choose_gap.doy', 'Yo ___ el libro a Ana.', ['doy', 'da'], 'doy', Kit::word('dar', 'doy'), 'Yo goes with doy. Da is for él or ella.', 'choose', 'I give the book to Ana.', ['libro' => 'book']),
            Kit::gap($stage, 'sentences.choose_gap.oigo', 'Yo no ___ bien.', ['oigo', 'oye'], 'oigo', Kit::word('oír', 'oigo'), 'Yo goes with oigo, the irregular form of oír. Oye is for él or ella.', 'choose', 'I do not hear well.'),

            Kit::typeGap($stage, 'sentences.type_gap.salgo', 'Yo ___ a las ocho.', 'I leave at eight.', 'salgo', Kit::form('salgo'), 'Yo goes with salgo, which is irregular. Sales is for tú.'),
            Kit::typeGap($stage, 'sentences.type_gap.traigo', 'Yo ___ a Marta aquí.', 'I bring Marta here.', 'traigo', Kit::word('traer', 'traigo'), 'Yo goes with traigo, the irregular form of traer.'),
            Kit::typeGap($stage, 'sentences.type_gap.pongo', 'Yo ___ dos aquí.', 'I put two here.', 'pongo', Kit::form('pongo'), 'Yo goes with pongo, which is irregular. Pones is for tú.'),
            Kit::typeGap($stage, 'sentences.type_gap.digo', 'Yo ___ hola a Luis.', 'I say hello to Luis.', 'digo', Kit::word('decir', 'digo'), 'Yo goes with digo, the irregular form of decir.'),
            Kit::typeGap($stage, 'sentences.type_gap.hace', '¿Qué ___ Ana?', 'What does Ana do?', 'hace', Kit::form('hace'), 'Ana is she, so the verb is hace. Hago is only for yo.'),

            Kit::translate($stage, 'sentences.translate.no-se', 'I do not know.', ['No sé.', 'Yo no sé.'], [Kit::word('saber', 'sé'), Kit::form('sé')]),
            Kit::translate($stage, 'sentences.translate.veo-ana', 'I see Ana here.', ['Veo a Ana aquí.', 'Yo veo a Ana aquí.', 'Veo aquí a Ana.', 'Aquí veo a Ana.'], [Kit::word('ver', 'veo'), Kit::form('veo')]),
            Kit::translate($stage, 'sentences.translate.haces', 'What do you do here? (informal you)', ['¿Qué haces aquí?', '¿Qué haces tú aquí?', '¿Tú qué haces aquí?'], [Kit::word('hacer', 'haces')]),

            Kit::build($stage, 'sentences.build.vengo', 'I come with Ana.', 'Vengo con Ana.', ['vienes'], [Kit::word('venir', 'vengo'), Kit::form('vengo')]),
            Kit::build($stage, 'sentences.build.sales', 'Do you leave at seven? (informal you)', '¿Sales a las siete?', ['salgo'], [Kit::word('salir', 'sales')]),
            Kit::build($stage, 'sentences.build.pone', 'She puts two here.', 'Ella pone dos aquí.', ['pongo'], [Kit::word('poner', 'pone')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.veo', 'Veo a Ana y a Pablo.', ['I see Ana and Pablo.', 'I hear Ana and Pablo.', 'You see Ana and Pablo.', 'I bring Ana and Pablo.'], 'I see Ana and Pablo.', [Kit::word('ver', 'veo'), Kit::form('veo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.sabes', '¿Sabes dónde está Luis?', ['Do you know where Luis is?', 'Do I know where Luis is?', 'Do you see Luis here?', 'Is Luis here?'], 'Do you know where Luis is?', [Kit::word('saber', 'sabes')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.viene', 'Ana viene con Pablo.', ['Ana comes with Pablo.', 'Ana leaves with Pablo.', 'Ana brings Pablo.', 'I come with Pablo.'], 'Ana comes with Pablo.', [Kit::word('venir', 'viene')]),
            Kit::listenType($stage, 'sentences.listen_type.no-se-donde', 'No sé dónde está Marta.', 'I do not know where Marta is.', [Kit::word('saber', 'sé'), Kit::form('sé')], homophoneNote: self::SE_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.salgo', 'Salgo a las ocho con Luis.', 'I leave at eight with Luis.', [Kit::word('salir', 'salgo'), Kit::form('salgo')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.hago', 'Yo hago uno y tú haces dos.', 'I do one and you do two. (informal you)', [Kit::word('hacer', 'hago'), Kit::form('hago')], homophoneNote: self::TU_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.digo', 'Digo hola y adiós.', 'I say hello and goodbye.', [Kit::word('decir', 'digo')], homophoneNote: self::HOLA_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.haces', '¿Qué haces aquí?', 'What are you doing here? (informal you)', [Kit::word('hacer', 'haces')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.traigo', 'Traigo a Marta aquí.', 'I bring Marta here.', [Kit::word('traer', 'traigo'), Kit::form('traigo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pongo', 'Pongo uno aquí y dos allí.', 'I put one here and two there.', [Kit::word('poner', 'pongo'), Kit::form('pongo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.doy', 'Doy las gracias a Ana.', 'I thank Ana.', [Kit::word('dar', 'doy')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ves', '¿Ves a Ana?', 'Do you see Ana? (informal you)', [['sí', 'no'], ['veo']], 'Sí, veo a Ana.', [Kit::word('ver', 'veo'), Kit::form('veo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vienes', '¿Vienes con Pablo?', 'Are you coming with Pablo? (informal you)', [['sí', 'no'], ['vengo']], 'Sí, vengo con Pablo.', [Kit::word('venir', 'vengo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.oyes', '¿Oyes bien?', 'Do you hear well? (informal you)', [['sí', 'no'], ['oigo']], 'Sí, oigo bien.', [Kit::word('oír', 'oigo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.cena', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿qué haces aquí?'),
                Kit::line('Pablo', 'Vengo con Luis y traigo la cena.'),
                Kit::line('Ana', 'Muy bien. Yo pongo la mesa. ¿Sabes dónde está Marta?'),
                Kit::line('Pablo', 'No sé. Aquí no veo a Marta.'),
                Kit::line('Ana', 'Yo oigo a Marta. Está allí.'),
            ], [
                Kit::question('What does Pablo bring?', ['The dinner', 'The table', 'Marta'], 'The dinner'),
                Kit::question('What does Ana do for the dinner?', ['She sets the table.', 'She brings the dinner.', 'She sees Marta.'], 'She sets the table.'),
                Kit::question('Where is Marta?', ['There', 'Here', 'The text does not say.'], 'There'),
            ], [Kit::word('hacer', 'haces'), Kit::word('venir', 'vengo'), Kit::word('traer', 'traigo'), Kit::word('poner', 'pongo'), Kit::word('saber', 'sabes'), Kit::word('ver', 'veo'), Kit::word('oír', 'oigo')], 'read', glosses: ['cena' => 'dinner', 'mesa' => 'table']),
            Kit::gap($stage, 'task.choose_gap.veo-marta', 'Yo no ___ a Marta aquí.', ['veo', 'ves'], 'veo', Kit::form('veo', true), 'Yo goes with veo, which is irregular. Ves is for tú.', 'read', 'I do not see Marta here.'),
            Kit::gap($stage, 'task.choose_gap.da', 'Luis ___ las gracias a Ana.', ['da', 'doy'], 'da', Kit::word('dar', 'da'), 'Luis is he, so the verb is da. Doy is only for yo.', 'read', 'Luis thanks Ana.'),

            Kit::transform($stage, 'task.transform.ella-ve', 'Change the subject to she.', 'Veo a Marta.', ['Ella ve a Marta.', 'Ve a Marta.'], [Kit::word('ver', 've'), Kit::form('ve')]),
            Kit::transform($stage, 'task.transform.salimos', 'Change the subject to we.', 'Salgo a las ocho.', ['Salimos a las ocho.', 'Nosotros salimos a las ocho.'], [Kit::word('salir', 'salimos'), Kit::form('salimos')]),
            Kit::transform($stage, 'task.transform.pongo', 'Change the subject to I.', 'Ana pone dos aquí.', ['Pongo dos aquí.', 'Yo pongo dos aquí.'], [Kit::word('poner', 'pongo'), Kit::form('pongo', true)]),
            Kit::writeGuided($stage, 'task.write_guided.vengo-traigo', 'Say that you come with Luis and that you bring Ana.', ['vengo con', 'traigo a'], 'Vengo con Luis y traigo a Ana.', [
                ['forms' => ['vengo', 'venimos'], 'term' => 'venir'],
                ['forms' => ['traigo', 'traemos'], 'term' => 'traer'],
            ], [Kit::word('venir', 'vengo'), Kit::word('traer', 'traigo')]),
            Kit::writeGuided($stage, 'task.write_guided.no-se-no-veo', 'Say that you do not know where Marta is and that you do not see Luis here.', ['no sé', 'no veo', 'dónde'], 'No sé dónde está Marta y no veo a Luis aquí.', [
                ['forms' => ['sé', 'sabemos'], 'term' => 'saber'],
                ['forms' => ['veo', 'vemos'], 'term' => 'ver'],
            ], [Kit::word('saber', 'sé'), Kit::word('ver', 'veo')]),
            Kit::build($stage, 'task.build.digo', 'I say hello to Ana.', 'Digo hola a Ana.', ['dices', 'dice'], [Kit::word('decir', 'digo'), Kit::form('digo')], 'write'),
            Kit::build($stage, 'task.build.venimos', 'We come with Luis.', 'Venimos con Luis.', ['vengo', 'viene'], [Kit::word('venir', 'venimos')], 'write'),
            Kit::build($stage, 'task.build.oyes', 'Do you hear Marta? (informal you)', '¿Oyes a Marta?', ['oigo', 'oye'], [Kit::word('oír', 'oyes')], 'write'),
            Kit::translate($stage, 'task.translate.veo-oigo', 'I see Ana, but I do not hear Marta.', ['Veo a Ana, pero no oigo a Marta.', 'Yo veo a Ana, pero no oigo a Marta.'], [Kit::word('ver', 'veo'), Kit::word('oír', 'oigo'), Kit::form('veo')], 'write'),
            Kit::translate($stage, 'task.translate.haces-sales', 'What do you do and when do you leave? (informal you)', ['¿Qué haces y cuándo sales?'], [Kit::word('hacer', 'haces'), Kit::word('salir', 'sales'), Kit::form('haces', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.salir', [
                Kit::line('Marta', '¿Cuándo sales, Luis?'),
                Kit::line('Luis', 'Salgo a las ocho. Digo adiós a Ana y a Pablo.'),
                Kit::line('Marta', 'Yo también salgo a las ocho. Doy las gracias a Ana.'),
            ], [
                Kit::question('When does Luis leave?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who does Luis say goodbye to?', ['Ana and Pablo', 'Marta', 'Nobody'], 'Ana and Pablo'),
                Kit::question('Who thanks Ana?', ['Marta', 'Luis', 'Pablo'], 'Marta'),
            ], [
                Kit::question('Who asks when Luis leaves?', ['Marta', 'Luis', 'Ana'], 'Marta'),
                Kit::question('Does Marta leave too?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('salir', 'salgo'), Kit::word('decir', 'digo'), Kit::word('dar', 'doy')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vengo-traigo', 'Vengo con Luis y traigo a Marta.', 'I come with Luis and I bring Marta.', [Kit::word('venir', 'vengo'), Kit::word('traer', 'traigo'), Kit::form('vengo')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.dice-digo', 'Ana dice hola y yo digo adiós.', 'Ana says hello and I say goodbye.', [Kit::word('decir', 'dice'), Kit::form('digo')], 'listen', homophoneNote: self::HOLA_NOTE),
            Kit::listenType($stage, 'task.listen_type.no-oigo', 'No oigo bien, pero veo a Pablo allí.', 'I do not hear well, but I see Pablo there.', [Kit::word('oír', 'oigo'), Kit::word('ver', 'veo')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.sales', '¿Sales a las ocho?', 'Do you leave at eight? (informal you)', [['sí', 'no'], ['salgo']], 'Sí, salgo a las ocho.', [Kit::word('salir', 'salgo'), Kit::form('salgo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.dices', '¿Dices hola a Pablo?', 'Do you say hello to Pablo? (informal you)', [['sí', 'no'], ['digo']], 'Sí, digo hola a Pablo.', [Kit::word('decir', 'digo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.traes', '¿Traes a Luis?', 'Are you bringing Luis? (informal you)', [['sí', 'no'], ['traigo']], 'Sí, traigo a Luis.', [Kit::word('traer', 'traigo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pones', '¿Pones dos aquí?', 'Do you put two here? (informal you)', [['sí', 'no'], ['pongo']], 'Sí, pongo dos aquí.', [Kit::word('poner', 'pongo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.doy', 'Doy las gracias a Ana y a Pablo.', 'I thank Ana and Pablo.', [Kit::word('dar', 'doy')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.no-se-hago', 'No sé qué hago aquí.', 'I do not know what I am doing here.', [Kit::word('saber', 'sé'), Kit::word('hacer', 'hago'), Kit::form('hago')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.pablo-haces', 'Marta, what do you do here? (informal you)', ['Marta, ¿qué haces aquí?', '¿Qué haces aquí, Marta?'], [Kit::word('hacer', 'haces'), Kit::form('haces', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vengo-traigo', 'I come with Marta and I bring two.', ['Vengo con Marta y traigo dos.', 'Yo vengo con Marta y traigo dos.'], [Kit::word('venir', 'vengo'), Kit::word('traer', 'traigo'), Kit::form('vengo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.salgo-digo', 'I leave at nine and I say goodbye.', ['Salgo a las nueve y digo adiós.', 'Yo salgo a las nueve y digo adiós.'], [Kit::word('salir', 'salgo'), Kit::word('decir', 'digo'), Kit::form('salgo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.no-se-veo', 'I do not know, but I see Luis here.', ['No sé, pero veo a Luis aquí.', 'Yo no sé, pero veo a Luis aquí.', 'No sé, pero aquí veo a Luis.'], [Kit::word('saber', 'sé'), Kit::word('ver', 'veo'), Kit::form('sé')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.doy', 'Yo ___ las gracias a Marta.', 'I thank Marta.', 'doy', Kit::form('doy', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pongo', 'Yo ___ dos allí.', 'I put two there.', 'pongo', Kit::form('pongo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.oigo', 'No oigo bien a Ana.', 'I do not hear Ana well.', [Kit::word('oír', 'oigo')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.digo-pongo', 'Digo hola a Luis y pongo dos aquí.', 'I say hello to Luis and I put two here.', [Kit::word('decir', 'digo'), Kit::word('poner', 'pongo')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::HOLA_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.da', 'Marta da las gracias a Pablo y a Luis.', 'Marta thanks Pablo and Luis.', [Kit::word('dar', 'da')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.traes', [
                Kit::line('Ana', '¿Qué traes, Luis?'),
                Kit::line('Luis', 'Traigo dos. ¿Dónde los pongo?'),
                Kit::line('Ana', 'Allí, por favor.'),
            ], [
                Kit::question('What does Luis bring?', ['Two', 'Three', 'Nothing'], 'Two'),
                Kit::question('Where does Ana say to put them?', ['There', 'Here', 'The conversation does not say.'], 'There'),
                Kit::question('Who asks the first question?', ['Ana', 'Luis', 'Nobody'], 'Ana'),
            ], [
                Kit::question('Who brings something?', ['Luis', 'Ana', 'Nobody'], 'Luis'),
                Kit::question('Does Luis know where to put them?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('traer', 'traigo'), Kit::word('poner', 'pongo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sabes', 'Read the conversation.', [
                Kit::line('Marta', 'Pablo, ¿sabes dónde está Ana?'),
                Kit::line('Pablo', 'No sé. Aquí no veo a Ana, pero oigo a Luis.'),
                Kit::line('Marta', 'Yo también oigo a Luis. Viene de allí.'),
            ], [
                Kit::question('What does Marta ask Pablo?', ['Where Ana is', 'Where Luis is', 'Who is coming'], 'Where Ana is'),
                Kit::question('Does Pablo see Ana?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('saber', 'sabes'), Kit::word('ver', 'veo'), Kit::word('oír', 'oigo'), Kit::word('venir', 'viene')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.haces', '¿Qué haces?', 'What do you do? (informal you)', [['hago', 'vengo', 'salgo', 'pongo', 'traigo', 'veo', 'digo'], ['aquí', 'allí', 'con', 'a', 'uno', 'dos', 'tres', 'hola', 'adiós']], 'Veo a Ana aquí.', [Kit::word('hacer', 'haces')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.das', '¿Das las gracias a Ana?', 'Do you thank Ana? (informal you)', [['sí', 'no'], ['doy']], 'Sí, doy las gracias a Ana.', [Kit::word('dar', 'das')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sales', '¿Sales a las siete o a las ocho?', 'Do you leave at seven or at eight? (informal you)', [['salgo'], ['siete', 'ocho']], 'Salgo a las ocho.', [Kit::word('salir', 'sales')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vengo-traigo-digo', 'I come with Luis, I bring Marta and I say hello.', ['Vengo con Luis, traigo a Marta y digo hola.', 'Yo vengo con Luis, traigo a Marta y digo hola.'], [Kit::word('venir', 'vengo'), Kit::word('traer', 'traigo'), Kit::word('decir', 'digo'), Kit::form('vengo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.salgo-doy', 'I leave with Luis and I thank Ana.', ['Salgo con Luis y doy las gracias a Ana.', 'Yo salgo con Luis y doy las gracias a Ana.'], [Kit::word('salir', 'salgo'), Kit::word('dar', 'doy'), Kit::form('salgo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.no-se-haces', 'I do not know what you do and I do not see Ana. (informal you)', ['No sé qué haces y no veo a Ana.', 'Yo no sé qué haces y no veo a Ana.'], [Kit::word('saber', 'sé'), Kit::word('hacer', 'haces'), Kit::word('ver', 'veo'), Kit::form('sé', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pongo-oigo', 'I put two here and I do not hear Ana.', ['Pongo dos aquí y no oigo a Ana.', 'Yo pongo dos aquí y no oigo a Ana.'], [Kit::word('poner', 'pongo'), Kit::word('oír', 'oigo'), Kit::form('pongo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.da', 'Ana ___ las gracias a Pablo.', 'Ana thanks Pablo.', 'da', Kit::word('dar', 'da'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pone', 'Ana ___ dos allí.', 'Ana puts two there.', 'pone', Kit::word('poner', 'pone'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.veo-oigo-digo', 'Veo a Ana, oigo a Pablo y digo hola.', 'I see Ana, I hear Pablo and I say hello.', [Kit::word('ver', 'veo'), Kit::word('oír', 'oigo'), Kit::word('decir', 'digo'), Kit::form('veo')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::HOLA_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sabe-hace-sale', 'Ana sabe qué hace y sale con Luis.', 'Ana knows what she does and she leaves with Luis.', [Kit::word('saber', 'sabe'), Kit::word('hacer', 'hace'), Kit::word('salir', 'sale'), Kit::form('hace')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.viene-trae', 'Marta viene con Luis y trae dos.', 'Marta comes with Luis and brings two.', [Kit::word('venir', 'viene'), Kit::word('traer', 'trae')], 'dictation', $set),
        ];
    }
}
