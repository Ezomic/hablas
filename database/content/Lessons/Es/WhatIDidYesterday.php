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

final class WhatIDidYesterday implements UnitContent
{
    private const THINGS = ['pan', 'leche', 'fruta', 'carne', 'pescado', 'queso', 'vino', 'café', 'libro', 'ropa', 'camisa', 'regalo', 'mochila'];

    private const PEOPLE = ['ana', 'pablo', 'marta', 'luis', 'madre', 'padre', 'hermano', 'hermana', 'hijo', 'abuelos', 'familia', 'jefe', 'compañero', 'compañera'];

    private const HOMOPHONE = 'A without an h means to or at. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'what-i-did-yesterday';
    }

    public function words(): array
    {
        return [
            new WordData('ayer', cue: 'yesterday'),
            new WordData('anoche', cue: 'last night', note: 'Anoche is last night in one word, the evening or night before today.'),
            new WordData('el fin de semana', cue: 'weekend', note: 'Fin is masculine: el fin de semana. Last weekend is el fin de semana pasado.'),
            new WordData('pasado', cue: 'last (as in last week)', forms: ['pasada'], note: 'Pasado goes after the noun and agrees with it: el año pasado, la semana pasada, el fin de semana pasado.'),
            new WordData('comprar', cue: 'to buy', forms: ['compré', 'compraste', 'compró', 'compramos', 'compraron', 'compro']),
            new WordData('hablar', cue: 'to talk (to speak)', forms: ['hablé', 'hablaste', 'habló', 'hablamos', 'hablaron', 'hablo']),
            new WordData('estudiar', cue: 'to study', forms: ['estudié', 'estudiaste', 'estudió', 'estudiamos', 'estudiaron', 'estudio']),
            new WordData('cocinar', cue: 'to cook', forms: ['cociné', 'cocinaste', 'cocinó', 'cocinamos', 'cocinaron', 'cocino']),
            new WordData('llamar', cue: 'to call', forms: ['llamé', 'llamaste', 'llamó', 'llamamos', 'llamaron', 'llamo'], note: 'Llamar is to call someone, also by phone. A person takes a: llamé a Ana.'),
            new WordData('escuchar', cue: 'to listen (to)', forms: ['escuché', 'escuchaste', 'escuchó', 'escuchamos', 'escucharon', 'escucho'], note: 'Escuchar already means to listen to, so there is no extra word for to: escuché música.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ayer compré pan.', 'english' => 'Yesterday I bought bread.'],
            ['text' => 'Ana habló con Luis anoche.', 'english' => 'Ana talked with Luis last night.'],
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
            Kit::gap($stage, 'sentences.choose_gap.hable', 'Ayer yo ___ con Ana.', ['hablé', 'hablo', 'habló'], 'hablé', Kit::form('hablé', true), 'Ayer points to the past, and yo takes the ending -é. Hablo is the present and habló is for él or ella.', 'choose', 'Yesterday I talked with Ana.'),
            Kit::gap($stage, 'sentences.choose_gap.compraste', 'Ayer tú ___ pan.', ['compraste', 'compré', 'compró'], 'compraste', Kit::form('compraste'), 'Tú takes the ending -aste: compraste. Compré is for yo and compró is for él or ella.', 'choose', 'Yesterday you bought bread. (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.llamo-marta', 'Marta ___ a Luis anoche.', ['llamó', 'llamé', 'llamo'], 'llamó', Kit::form('llamó', true), 'Marta is she, so the ending is -ó, with an accent. Llamé is for yo and llamo is the present.', 'choose', 'Marta called Luis last night.'),
            Kit::gap($stage, 'sentences.choose_gap.escuchamos', 'Ayer nosotros ___ música.', ['escuchamos', 'escuché', 'escuchó'], 'escuchamos', Kit::form('escuchamos'), 'Nosotros takes -amos. The past and the present look the same for nosotros, and ayer shows that it is the past.', 'choose', 'Yesterday we listened to music.'),
            Kit::gap($stage, 'sentences.choose_gap.pasado', 'El año ___ estudié en Holanda.', ['pasado', 'pasada'], 'pasado', Kit::word('pasado'), 'Año is masculine, so the word is pasado. Pasada goes with a feminine word, such as la semana pasada.', 'choose', 'Last year I studied in Holland.'),
            Kit::gap($stage, 'sentences.choose_gap.fin-de-semana', 'El ___ pasado hablé con Ana.', ['fin de semana', 'semana', 'noche'], 'fin de semana', Kit::word('el fin de semana', 'fin de semana'), 'Last weekend is el fin de semana pasado. Semana and noche are feminine, so they cannot follow el.', 'choose', 'Last weekend I talked with Ana.'),

            Kit::typeGap($stage, 'sentences.type_gap.cocine', 'Anoche yo ___ la cena.', 'Last night I cooked dinner.', 'cociné', Kit::form('cociné'), 'Yo takes the ending -é, with an accent: cociné.'),
            Kit::typeGap($stage, 'sentences.type_gap.hablaste', 'Ayer tú ___ con Pablo.', 'Yesterday you talked with Pablo. (informal you)', 'hablaste', Kit::form('hablaste'), 'Tú takes the ending -aste: hablaste.'),
            Kit::typeGap($stage, 'sentences.type_gap.escucho-pablo', 'Pablo ___ música anoche.', 'Pablo listened to music last night.', 'escuchó', Kit::form('escuchó', true), 'Pablo is he, so the ending is -ó, with an accent. Without it, escucho means I listen.'),
            Kit::typeGap($stage, 'sentences.type_gap.compre', 'Anoche yo ___ pan y fruta.', 'Last night I bought bread and fruit.', 'compré', Kit::word('comprar', 'compré')),
            Kit::typeGap($stage, 'sentences.type_gap.pasada', 'La semana ___ llamé a Luis.', 'Last week I called Luis.', 'pasada', Kit::word('pasado', 'pasada'), 'Semana is feminine, so the word is pasada.'),

            Kit::translate($stage, 'sentences.translate.llame-ana', 'Yesterday I called Ana.', ['Ayer llamé a Ana.', 'Yo llamé a Ana ayer.', 'Llamé a Ana ayer.', 'Ayer yo llamé a Ana.'], [Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::form('llamé')]),
            Kit::translate($stage, 'sentences.translate.estudiaste', 'Last night you studied at home. (informal you)', ['Anoche estudiaste en casa.', 'Estudiaste en casa anoche.', 'Anoche tú estudiaste en casa.', 'Tú estudiaste en casa anoche.'], [Kit::word('anoche'), Kit::word('estudiar', 'estudiaste'), Kit::form('estudiaste')]),
            Kit::translate($stage, 'sentences.translate.compre-pan', 'Last week I bought bread.', ['La semana pasada compré pan.', 'Compré pan la semana pasada.', 'Yo compré pan la semana pasada.', 'La semana pasada yo compré pan.'], [Kit::word('pasado', 'pasada'), Kit::word('comprar', 'compré'), Kit::form('compré', true)]),

            Kit::build($stage, 'sentences.build.cocine-anoche', 'Last night I cooked.', 'Anoche cociné.', ['cocinó'], [Kit::word('cocinar', 'cociné'), Kit::word('anoche'), Kit::form('cociné')]),
            Kit::build($stage, 'sentences.build.hablamos-marta', 'Yesterday we talked with Marta.', 'Ayer hablamos con Marta.', ['hablaron'], [Kit::word('ayer'), Kit::word('hablar', 'hablamos'), Kit::form('hablamos')]),
            Kit::build($stage, 'sentences.build.escucho-pablo', 'Yesterday Pablo listened to music.', 'Ayer Pablo escuchó música.', ['escuché'], [Kit::word('escuchar', 'escuchó'), Kit::word('ayer'), Kit::form('escuchó', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.compre-pan', 'Ayer compré leche.', ['Yesterday I bought milk.', 'Yesterday she bought milk.', 'Yesterday I bought bread.', 'Today I buy milk.'], 'Yesterday I bought milk.', [Kit::word('ayer'), Kit::word('comprar', 'compré'), Kit::form('compré')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hablo-marta', 'Marta habló con Ana anoche.', ['Marta talked with Ana last night.', 'Marta talks with Ana every night.', 'Marta talked with Ana this morning.', 'I talked with Ana last night.'], 'Marta talked with Ana last night.', [Kit::word('hablar', 'habló'), Kit::word('anoche'), Kit::form('habló', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.estudiamos', 'Estudiamos el fin de semana pasado.', ['We studied last weekend.', 'We study every weekend.', 'We studied last week.', 'I studied last weekend.'], 'We studied last weekend.', [Kit::word('estudiar', 'estudiamos'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::form('estudiamos')]),
            Kit::listenType($stage, 'sentences.listen_type.llame-luis', 'Ayer llamé a Luis.', 'Yesterday I called Luis.', [Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::form('llamé')], homophoneNote: self::HOMOPHONE),
            Kit::listenType($stage, 'sentences.listen_type.cocinaste', 'Anoche cocinaste para Ana.', 'Last night you cooked for Ana. (informal you)', [Kit::word('anoche'), Kit::word('cocinar', 'cocinaste'), Kit::form('cocinaste')]),
            Kit::listenType($stage, 'sentences.listen_type.compro-cafe', 'Pablo compró café ayer.', 'Pablo bought coffee yesterday.', [Kit::word('comprar', 'compró'), Kit::word('ayer'), Kit::form('compró', true)]),
            Kit::listenType($stage, 'sentences.listen_type.hablamos-anoche', 'Hablamos con Marta anoche.', 'We talked with Marta last night.', [Kit::word('hablar', 'hablamos'), Kit::word('anoche'), Kit::form('hablamos')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.hable-marta', 'Ayer hablé con Marta.', 'Yesterday I talked with Marta.', [Kit::word('ayer'), Kit::word('hablar', 'hablé'), Kit::form('hablé')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cocine-pescado', 'Anoche cociné pescado.', 'Last night I cooked fish.', [Kit::word('anoche'), Kit::word('cocinar', 'cociné')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.estudie-holanda', 'El año pasado estudié en Holanda.', 'Last year I studied in Holland.', [Kit::word('pasado'), Kit::word('estudiar', 'estudié')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.escuchamos-fin', 'El fin de semana pasado escuchamos música.', 'Last weekend we listened to music.', [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('escuchar', 'escuchamos'), Kit::form('escuchamos')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.compraste', '¿Qué compraste ayer?', 'What did you buy yesterday?', [['compré'], self::THINGS], 'Ayer compré pan.', [Kit::word('comprar', 'compré'), Kit::word('ayer'), Kit::form('compré')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hablaste', '¿Con quién hablaste anoche?', 'Who did you talk with last night?', [['hablé'], self::PEOPLE], 'Anoche hablé con Ana.', [Kit::word('hablar', 'hablé'), Kit::word('anoche'), Kit::form('hablé')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.estudiaste', '¿Estudiaste ayer?', 'Did you study yesterday?', [['estudié']], 'Sí, estudié ayer.', [Kit::word('estudiar', 'estudié'), Kit::word('ayer')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.ayer', 'Read the conversation about yesterday.', [
                Kit::line('Ana', 'Pablo, ¿estudiaste ayer?'),
                Kit::line('Pablo', 'Sí, estudié en casa y llamé a Luis.'),
                Kit::line('Ana', 'Yo no estudié. Anoche cociné con Marta.'),
                Kit::line('Pablo', '¿Qué cocinaron?'),
                Kit::line('Ana', 'Cocinamos pescado. Marta compró el vino.'),
            ], [
                Kit::question('Who studied yesterday?', ['Pablo', 'Ana', 'Both of them'], 'Pablo'),
                Kit::question('Who did Pablo call?', ['Luis', 'Marta', 'Ana'], 'Luis'),
                Kit::question('What did Ana and Marta cook?', ['Fish', 'Meat', 'Bread'], 'Fish'),
            ], [Kit::word('estudiar', 'estudié'), Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::word('anoche'), Kit::word('cocinar', 'cociné'), Kit::word('comprar', 'compró')], 'read'),
            Kit::gap($stage, 'task.choose_gap.madre-hablo', 'Mi madre ___ con Pablo ayer.', ['habló', 'hablé', 'hablo'], 'habló', Kit::form('habló', true), 'Mi madre is she, so the ending is -ó, with an accent. Hablé is for yo and hablo is the present.', 'read', 'My mother talked with Pablo yesterday.'),
            Kit::gap($stage, 'task.choose_gap.pescado', 'Anoche yo ___ pescado con Marta.', ['cociné', 'escuché', 'llamé'], 'cociné', Kit::word('cocinar', 'cociné'), 'Pescado is something you cook, so cociné. You do not listen to fish or call fish.', 'read', 'Last night I cooked fish with Marta.'),

            Kit::transform($stage, 'task.transform.ana', 'Say that Ana did it.', 'Hablé con Luis ayer.', ['Ana habló con Luis ayer.', 'Habló con Luis ayer.', 'Ella habló con Luis ayer.'], [Kit::word('hablar', 'habló'), Kit::word('ayer'), Kit::form('habló', true)]),
            Kit::transform($stage, 'task.transform.tu', 'Say that you (tú) did it.', 'Compré pan ayer.', ['Compraste pan ayer.', 'Tú compraste pan ayer.'], [Kit::word('comprar', 'compraste'), Kit::form('compraste')]),
            Kit::transform($stage, 'task.transform.nosotros', 'Say that we did it.', 'Estudié anoche.', ['Estudiamos anoche.', 'Nosotros estudiamos anoche.'], [Kit::word('estudiar', 'estudiamos'), Kit::word('anoche'), Kit::form('estudiamos')]),
            Kit::writeGuided($stage, 'task.write_guided.llame-compre', 'Say what you did yesterday: you called Ana and you bought bread.', ['ayer', 'llamé', 'compré', 'pan'], 'Ayer llamé a Ana y compré pan.', [
                ['forms' => ['ayer'], 'term' => 'ayer'],
                ['forms' => ['llamé'], 'term' => 'llamar'],
                ['forms' => ['compré'], 'term' => 'comprar'],
            ], [Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::word('comprar', 'compré')]),
            Kit::writeGuided($stage, 'task.write_guided.estudie-cocine', 'Say that last night you studied and that last weekend you cooked.', ['anoche', 'estudié', 'el fin de semana pasado', 'cociné'], 'Anoche estudié y el fin de semana pasado cociné.', [
                ['forms' => ['anoche'], 'term' => 'anoche'],
                ['forms' => ['estudié'], 'term' => 'estudiar'],
                ['forms' => ['semana'], 'term' => 'el fin de semana'],
                ['forms' => ['pasado'], 'term' => 'pasado'],
                ['forms' => ['cociné'], 'term' => 'cocinar'],
            ], [Kit::word('anoche'), Kit::word('estudiar', 'estudié'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('cocinar', 'cociné')]),
            Kit::build($stage, 'task.build.llame-hable', 'Yesterday I called Marta and talked with Luis.', 'Ayer llamé a Marta y hablé con Luis.', ['llamó', 'habló'], [Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::word('hablar', 'hablé'), Kit::form('llamé')]),
            Kit::build($stage, 'task.build.estudio-pablo', 'Last year Pablo studied in Holland.', 'El año pasado Pablo estudió en Holanda.', ['estudié', 'estudiaste'], [Kit::word('pasado'), Kit::word('estudiar', 'estudió'), Kit::form('estudió', true)]),
            Kit::build($stage, 'task.build.escuchamos-llamamos', 'Last Sunday we listened to music and called Ana.', 'El domingo pasado escuchamos música y llamamos a Ana.', ['escuché', 'llamaron'], [Kit::word('pasado'), Kit::word('escuchar', 'escuchamos'), Kit::word('llamar', 'llamamos'), Kit::form('escuchamos')]),
            Kit::translate($stage, 'task.translate.compre-cocine', 'Yesterday I bought bread and last night I cooked fish.', ['Ayer compré pan y anoche cociné pescado.', 'Ayer yo compré pan y anoche yo cociné pescado.', 'Compré pan ayer y cociné pescado anoche.', 'Compré pan ayer y anoche cociné pescado.', 'Yo compré pan ayer y cociné pescado anoche.'], [Kit::word('ayer'), Kit::word('anoche'), Kit::word('comprar', 'compré'), Kit::word('cocinar', 'cociné'), Kit::form('compré', true)]),
            Kit::translate($stage, 'task.translate.estudiamos-escuchamos', 'Last weekend we studied and listened to music.', ['El fin de semana pasado estudiamos y escuchamos música.', 'Estudiamos y escuchamos música el fin de semana pasado.', 'El fin de semana pasado nosotros estudiamos y escuchamos música.', 'Nosotros estudiamos y escuchamos música el fin de semana pasado.'], [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('estudiar', 'estudiamos'), Kit::word('escuchar', 'escuchamos'), Kit::form('estudiamos')]),

            Kit::listenPassage($stage, 'task.listen_passage.ayer-anoche', [
                Kit::line('Luis', 'Marta, ¿hablaste con Ana ayer?'),
                Kit::line('Marta', 'Sí, hablé con ella y llamé a Pablo.'),
                Kit::line('Luis', '¿Y anoche? ¿Estudiaste?'),
                Kit::line('Marta', 'No, cociné con mi madre y escuchamos música.'),
            ], [
                Kit::question('Who did Marta talk with yesterday?', ['Ana', 'Pablo', 'Luis'], 'Ana'),
                Kit::question('Who did Marta call?', ['Ana', 'Pablo', 'Luis'], 'Pablo'),
                Kit::question('What did Marta do last night?', ['She studied and called Pablo', 'She cooked with her mother', 'She talked with Luis'], 'She cooked with her mother'),
            ], [
                Kit::question('Who asks the questions?', ['Luis', 'Marta', 'Pablo'], 'Luis'),
                Kit::question('Did Marta study last night?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('hablar', 'hablaste'), Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::word('anoche'), Kit::word('estudiar', 'estudiaste'), Kit::word('cocinar', 'cociné'), Kit::word('escuchar', 'escuchamos')]),
            Kit::listenType($stage, 'task.listen_type.escuchamos-cocinamos', 'Anoche escuchamos música y cocinamos con Ana.', 'Last night we listened to music and cooked with Ana.', [Kit::word('anoche'), Kit::word('escuchar', 'escuchamos'), Kit::word('cocinar', 'cocinamos'), Kit::form('cocinamos')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.compre-fin-de-semana', 'El fin de semana pasado compré pan y fruta.', 'Last weekend I bought bread and fruit.', [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('comprar', 'compré'), Kit::form('compré')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pablo-marta', 'Pablo estudió ayer, pero Marta no estudió.', 'Pablo studied yesterday, but Marta did not study.', [Kit::word('estudiar', 'estudió'), Kit::word('ayer'), Kit::form('estudió', true)], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.cocinaste', '¿Qué cocinaste anoche?', 'What did you cook last night?', [['cociné'], self::THINGS], 'Anoche cociné pescado.', [Kit::word('cocinar', 'cociné'), Kit::word('anoche'), Kit::form('cociné')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.llamaste', '¿A quién llamaste ayer?', 'Who did you call yesterday?', [['llamé'], self::PEOPLE], 'Ayer llamé a Ana.', [Kit::word('llamar', 'llamé'), Kit::word('ayer'), Kit::form('llamé')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.escuchaste', '¿Escuchaste música ayer?', 'Did you listen to music yesterday?', [['escuché']], 'Sí, escuché música ayer.', [Kit::word('escuchar', 'escuché'), Kit::word('ayer')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cuando-hablaste', '¿Cuándo hablaste con Ana?', 'When did you talk with Ana?', [['hablé'], ['ayer', 'anoche', 'semana', 'pasado', 'pasada', 'año', 'lunes', 'domingo']], 'Hablé con Ana ayer.', [Kit::word('hablar', 'hablé'), Kit::word('ayer')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ayer-anoche', 'Ayer estudié y anoche cociné.', 'Yesterday I studied and last night I cooked.', [Kit::word('ayer'), Kit::word('anoche'), Kit::word('estudiar', 'estudié'), Kit::word('cocinar', 'cociné'), Kit::form('estudié')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.llamamos-ana', 'El fin de semana pasado llamamos a Ana.', 'Last weekend we called Ana.', [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('llamar', 'llamamos'), Kit::form('llamamos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.cocine-escuche', 'Last night I cooked and listened to music.', ['Anoche cociné y escuché música.', 'Cociné y escuché música anoche.', 'Anoche yo cociné y escuché música.', 'Yo cociné y escuché música anoche.'], [Kit::word('anoche'), Kit::word('cocinar', 'cociné'), Kit::word('escuchar', 'escuché'), Kit::form('cociné')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-compro', 'Pablo bought bread and talked with Luis yesterday.', ['Pablo compró pan y habló con Luis ayer.', 'Ayer Pablo compró pan y habló con Luis.', 'Pablo compró pan ayer y habló con Luis.'], [Kit::word('comprar', 'compró'), Kit::word('hablar', 'habló'), Kit::word('ayer'), Kit::form('compró', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.llamamos-estudiamos', 'Last weekend we called Ana and studied.', ['El fin de semana pasado llamamos a Ana y estudiamos.', 'Llamamos a Ana y estudiamos el fin de semana pasado.'], [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('llamar', 'llamamos'), Kit::word('estudiar', 'estudiamos'), Kit::form('llamamos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.estudiaste-marta', 'Last year you studied with Marta. (informal you)', ['El año pasado estudiaste con Marta.', 'Estudiaste con Marta el año pasado.', 'El año pasado tú estudiaste con Marta.', 'Tú estudiaste con Marta el año pasado.'], [Kit::word('pasado'), Kit::word('estudiar', 'estudiaste'), Kit::form('estudiaste')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.llame-marta', 'Ayer yo ___ a Marta.', 'Yesterday I called Marta.', 'llamé', Kit::word('llamar', 'llamé'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pasada', 'La semana ___ cociné pescado.', 'Last week I cooked fish.', 'pasada', Kit::word('pasado', 'pasada'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-pablo', 'Ana habló con Pablo anoche.', 'Ana talked with Pablo last night.', [Kit::word('hablar', 'habló'), Kit::word('anoche'), Kit::form('habló', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-escucho', 'Luis escuchó música el fin de semana pasado.', 'Luis listened to music last weekend.', [Kit::word('escuchar', 'escuchó'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::form('escuchó')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.compre-fruta', 'Ayer compré fruta y leche.', 'Yesterday I bought fruit and milk.', [Kit::word('ayer'), Kit::word('comprar', 'compré')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.fin-de-semana', [
                Kit::line('Marta', 'Pablo, ¿qué compraste el fin de semana pasado?'),
                Kit::line('Pablo', 'Compré una mochila. Anoche cociné con mi hermano.'),
                Kit::line('Marta', 'Yo estudié en la biblioteca y llamé a Ana.'),
            ], [
                Kit::question('What did Pablo buy?', ['A backpack', 'A book', 'A shirt'], 'A backpack'),
                Kit::question('Who did Pablo cook with?', ['His brother', 'His mother', 'Marta'], 'His brother'),
                Kit::question('Where did Marta study?', ['In the library', 'At home', 'In the café'], 'In the library'),
            ], [
                Kit::question('Who asks the first question?', ['Marta', 'Pablo', 'Ana'], 'Marta'),
                Kit::question('Did Marta call Ana?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('comprar', 'compraste'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('anoche'), Kit::word('cocinar', 'cociné'), Kit::word('estudiar', 'estudié'), Kit::word('llamar', 'llamé')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ayer', 'Read the conversation.', [
                Kit::line('Luis', 'Ana, ¿hablaste con Marta ayer?'),
                Kit::line('Ana', 'No, hablé con Pablo. Marta no llamó a Pablo.'),
                Kit::line('Luis', 'Yo no hablé con Marta. Anoche cociné y escuché música.'),
            ], [
                Kit::question('Who did Ana talk with yesterday?', ['Marta', 'Pablo', 'Luis'], 'Pablo'),
                Kit::question('What did Luis do last night?', ['He cooked and listened to music', 'He studied', 'He called Ana'], 'He cooked and listened to music'),
            ], [Kit::word('hablar', 'hablaste'), Kit::word('ayer'), Kit::word('llamar', 'llamó'), Kit::word('anoche'), Kit::word('cocinar', 'cociné'), Kit::word('escuchar', 'escuché')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.compraste-anoche', '¿Qué compraste anoche?', 'What did you buy last night?', [['compré'], self::THINGS], 'Anoche compré fruta.', [Kit::word('comprar', 'compré'), Kit::word('anoche')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.estudiaste-fin', '¿Estudiaste el fin de semana pasado?', 'Did you study last weekend?', [['estudié']], 'Sí, estudié el fin de semana pasado.', [Kit::word('estudiar', 'estudié'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.llamaste-anoche', '¿A quién llamaste anoche?', 'Who did you call last night?', [['llamé'], self::PEOPLE], 'Anoche llamé a Luis.', [Kit::word('llamar', 'llamé'), Kit::word('anoche')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.llame-madre', 'Yesterday I called my mother and bought bread.', ['Ayer llamé a mi madre y compré pan.', 'Llamé a mi madre y compré pan ayer.', 'Ayer yo llamé a mi madre y compré pan.', 'Compré pan y llamé a mi madre ayer.'], [Kit::word('ayer'), Kit::word('llamar', 'llamé'), Kit::word('comprar', 'compré'), Kit::form('llamé')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-cocino', 'Last night Ana cooked and Pablo studied.', ['Anoche Ana cocinó y Pablo estudió.', 'Ana cocinó y Pablo estudió anoche.'], [Kit::word('anoche'), Kit::word('cocinar', 'cocinó'), Kit::word('estudiar', 'estudió'), Kit::form('cocinó', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hablaste-luis', 'Last weekend you talked with Luis. (informal you)', ['El fin de semana pasado hablaste con Luis.', 'Hablaste con Luis el fin de semana pasado.', 'El fin de semana pasado tú hablaste con Luis.', 'Tú hablaste con Luis el fin de semana pasado.'], [Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::word('hablar', 'hablaste'), Kit::form('hablaste')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.escucharon', 'Last week they listened to music.', ['La semana pasada escucharon música.', 'Escucharon música la semana pasada.', 'Ellos escucharon música la semana pasada.', 'La semana pasada ellos escucharon música.', 'Ellas escucharon música la semana pasada.', 'La semana pasada ellas escucharon música.'], [Kit::word('pasado', 'pasada'), Kit::word('escuchar', 'escucharon'), Kit::form('escucharon')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.compre-fruta', 'Ayer yo ___ fruta.', 'Yesterday I bought fruit.', 'compré', Kit::word('comprar', 'compré'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estudiaste-biblioteca', 'Anoche tú ___ en la biblioteca.', 'Last night you studied in the library. (informal you)', 'estudiaste', Kit::word('estudiar', 'estudiaste'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.hablamos-cafe', 'Anoche hablamos con Ana en el café.', 'Last night we talked with Ana in the café.', [Kit::word('anoche'), Kit::word('hablar', 'hablamos'), Kit::form('hablamos')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-escucho', 'Marta escuchó música el fin de semana pasado.', 'Marta listened to music last weekend.', [Kit::word('escuchar', 'escuchó'), Kit::word('el fin de semana', 'fin de semana'), Kit::word('pasado'), Kit::form('escuchó', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.luis-cocino', 'Luis cocinó y llamó a Ana ayer.', 'Luis cooked and called Ana yesterday.', [Kit::word('cocinar', 'cocinó'), Kit::word('llamar', 'llamó'), Kit::word('ayer')], 'dictation', $set, homophoneNote: self::HOMOPHONE),
        ];
    }
}
