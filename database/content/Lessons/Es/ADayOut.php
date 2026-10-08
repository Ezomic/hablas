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

final class ADayOut implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'a-day-out';
    }

    public function words(): array
    {
        return [
            new WordData('comer', cue: 'to eat', forms: ['comí', 'comiste', 'comió', 'comimos', 'comieron']),
            new WordData('beber', cue: 'to drink', forms: ['bebí', 'bebiste', 'bebió', 'bebimos', 'bebieron']),
            new WordData('volver', cue: 'to come back (to return)', forms: ['volví', 'volviste', 'volvió', 'volvimos', 'volvieron'], note: 'Volver is to come back: volver a casa is to go back home. In the past tense it is regular: volví, volviste, volvió.'),
            new WordData('subir', cue: 'to go up (to climb)', forms: ['subí', 'subiste', 'subió', 'subimos', 'subieron'], note: 'Subir is to go up or to climb: subir a la montaña.'),
            new WordData('decidir', cue: 'to decide', forms: ['decidí', 'decidiste', 'decidió', 'decidimos', 'decidieron']),
            new WordData('la playa', cue: 'beach'),
            new WordData('el castillo', cue: 'castle'),
            new WordData('el mercado', cue: 'market'),
            new WordData('la montaña', cue: 'mountain'),
            new WordData('el lago', cue: 'lake'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Comí en el mercado.', 'english' => 'I ate in the market.'],
            ['text' => 'Ana subió a la montaña.', 'english' => 'Ana went up the mountain.'],
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
            Kit::gap($stage, 'sentences.choose_gap.ana-comio', 'Ana ___ en el mercado.', ['comió', 'comí', 'comimos'], 'comió', Kit::form('comió', true), 'Ana is she, so the verb ends in -ió: comió. Comí is only for yo and comimos for nosotros.', 'choose', 'Ana ate in the market.'),
            Kit::gap($stage, 'sentences.choose_gap.yo-bebi', 'Yo ___ agua.', ['bebí', 'bebiste', 'bebió'], 'bebí', Kit::form('bebí', true), 'Yo goes with -í, and it carries an accent: bebí. Bebiste is for tú and bebió for él or ella.', 'choose', 'I drank water.'),
            Kit::gap($stage, 'sentences.choose_gap.tu-volviste', 'Tú ___ a casa.', ['volviste', 'volví', 'volvió'], 'volviste', Kit::form('volviste', true), 'Tú goes with -iste: volviste. Volví is for yo and volvió for él or ella.', 'choose', 'You came back home. (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.subimos-montana', 'Subimos a la ___.', ['montaña', 'mercado', 'castillo'], 'montaña', Kit::word('la montaña', 'montaña'), 'La goes with montaña, a feminine word. Mercado and castillo take el.', 'choose', 'We went up the mountain.'),
            Kit::gap($stage, 'sentences.choose_gap.comimos-mercado', 'Comimos en el ___.', ['mercado', 'playa', 'montaña'], 'mercado', Kit::word('el mercado', 'mercado'), 'El goes with mercado. Playa and montaña take la.', 'choose', 'We ate in the market.'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-decidio', 'Pablo ___ ir al lago.', ['decidió', 'decidí', 'decidimos'], 'decidió', Kit::word('decidir', 'decidió'), 'Pablo is he, so the verb is decidió. Decidí is only for yo and decidimos for nosotros.', 'choose', 'Pablo decided to go to the lake.'),

            Kit::typeGap($stage, 'sentences.type_gap.yo-comi', 'Yo ___ en la playa.', 'I ate on the beach.', 'comí', Kit::word('comer', 'comí'), 'Yo goes with comí, with an accent on the í.'),
            Kit::typeGap($stage, 'sentences.type_gap.luis-volvio', 'Luis ___ del lago.', 'Luis came back from the lake.', 'volvió', Kit::word('volver', 'volvió')),
            Kit::typeGap($stage, 'sentences.type_gap.nosotros-subimos', 'Nosotros ___ al castillo.', 'We went up to the castle.', 'subimos', Kit::word('subir', 'subimos')),
            Kit::typeGap($stage, 'sentences.type_gap.tu-bebiste', 'Tú ___ vino.', 'You drank wine. (informal you)', 'bebiste', Kit::form('bebiste'), 'Tú goes with -iste: bebiste. Bebí is only for yo.'),
            Kit::typeGap($stage, 'sentences.type_gap.ellos-decidieron', 'Ellos ___ ir al mercado.', 'They decided to go to the market.', 'decidieron', Kit::word('decidir', 'decidieron')),

            Kit::translate($stage, 'sentences.translate.bebi-lago', 'I drank water at the lake.', ['Bebí agua en el lago.', 'Yo bebí agua en el lago.'], [Kit::word('beber', 'bebí'), Kit::word('el lago', 'lago'), Kit::form('bebí')]),
            Kit::translate($stage, 'sentences.translate.comimos-pescado', 'We ate fish on the beach.', ['Comimos pescado en la playa.', 'Nosotros comimos pescado en la playa.'], [Kit::word('comer', 'comimos'), Kit::word('la playa', 'playa'), Kit::form('comimos')]),
            Kit::translate($stage, 'sentences.translate.volviste-mercado', 'Did you come back from the market? (informal you)', ['¿Volviste del mercado?', '¿Tú volviste del mercado?'], [Kit::word('volver', 'volviste'), Kit::word('el mercado', 'mercado'), Kit::form('volviste', true)]),

            Kit::build($stage, 'sentences.build.luis-subio', 'Luis went up to the mountain.', 'Luis subió a la montaña.', ['subí'], [Kit::word('subir', 'subió'), Kit::word('la montaña', 'montaña'), Kit::form('subió')]),
            Kit::build($stage, 'sentences.build.decidimos-castillo', 'We decided to go to the castle.', 'Decidimos ir al castillo.', ['decidió'], [Kit::word('decidir', 'decidimos'), Kit::word('el castillo', 'castillo'), Kit::form('decidimos')]),
            Kit::build($stage, 'sentences.build.ana-bebio', 'Ana drank coffee in the market.', 'Ana bebió café en el mercado.', ['bebí'], [Kit::word('beber', 'bebió'), Kit::form('bebió', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.comimos', 'Comimos en el mercado.', ['We ate in the market.', 'We eat in the market.', 'I ate in the market.', 'They ate in the market.'], 'We ate in the market.', [Kit::word('comer', 'comimos'), Kit::word('el mercado', 'mercado'), Kit::form('comimos', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pablo-volvio', 'Pablo volvió del lago.', ['Pablo came back from the lake.', 'Pablo goes to the lake.', 'I came back from the lake.', 'Pablo came back from the beach.'], 'Pablo came back from the lake.', [Kit::word('volver', 'volvió'), Kit::word('el lago', 'lago'), Kit::form('volvió')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.subi', 'Subí a la montaña.', ['I went up the mountain.', 'She went up the mountain.', 'You went up the mountain.', 'I went up to the castle.'], 'I went up the mountain.', [Kit::word('subir', 'subí'), Kit::word('la montaña', 'montaña'), Kit::form('subí')]),
            Kit::listenType($stage, 'sentences.listen_type.bebi-playa', 'Bebí agua en la playa.', 'I drank water on the beach.', [Kit::word('beber', 'bebí'), Kit::word('la playa', 'playa'), Kit::form('bebí')]),
            Kit::listenType($stage, 'sentences.listen_type.decidimos-lago', 'Decidimos ir al lago.', 'We decided to go to the lake.', [Kit::word('decidir', 'decidimos'), Kit::word('el lago', 'lago'), Kit::form('decidimos')]),
            Kit::listenType($stage, 'sentences.listen_type.luis-castillo', 'Luis subió al castillo.', 'Luis went up to the castle.', [Kit::word('subir', 'subió'), Kit::word('el castillo', 'castillo'), Kit::form('subió')]),
            Kit::listenType($stage, 'sentences.listen_type.marta-comio', 'Marta comió en el mercado.', 'Marta ate in the market.', [Kit::word('comer', 'comió'), Kit::word('el mercado', 'mercado'), Kit::form('comió')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.comi-playa', 'Comí en la playa.', 'I ate on the beach.', [Kit::word('comer', 'comí'), Kit::word('la playa', 'playa'), Kit::form('comí')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.volvimos-castillo', 'Volvimos del castillo.', 'We came back from the castle.', [Kit::word('volver', 'volvimos'), Kit::word('el castillo', 'castillo'), Kit::form('volvimos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bebieron-lago', 'Bebieron agua en el lago.', 'They drank water at the lake.', [Kit::word('beber', 'bebieron'), Kit::word('el lago', 'lago'), Kit::form('bebieron')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.marta-decidio', 'Marta decidió volver a la playa.', 'Marta decided to go back to the beach.', [Kit::word('decidir', 'decidió'), Kit::word('volver', 'volver'), Kit::word('la playa', 'playa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.comiste-mercado', '¿Comiste en el mercado?', 'Did you eat in the market? (informal you)', [['sí', 'no'], ['comí']], 'Sí, comí en el mercado.', [Kit::word('comer', 'comí'), Kit::word('el mercado', 'mercado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.volviste-casa', '¿Volviste a casa?', 'Did you come back home? (informal you)', [['sí', 'no'], ['volví']], 'Sí, volví a casa.', [Kit::word('volver', 'volví')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-bebiste', '¿Qué bebiste?', 'What did you drink? (informal you)', [['bebí']], 'Bebí agua.', [Kit::word('beber', 'bebí')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.lago', 'Read the conversation about a day out.', [
                Kit::line('Ana', 'Pablo, ¿volviste del lago?'),
                Kit::line('Pablo', 'Sí, volvimos el lunes. El domingo subimos a la montaña.'),
                Kit::line('Ana', '¿Qué comiste allí?'),
                Kit::line('Pablo', 'Comí pan y queso. Marta bebió vino y yo bebí agua.'),
                Kit::line('Ana', '¿Y Luis?'),
                Kit::line('Pablo', 'Luis no subió. Decidió comer en el pueblo.'),
            ], [
                Kit::question('When did Pablo come back?', ['On Monday', 'On Sunday', 'Today'], 'On Monday'),
                Kit::question('Where did Pablo go on Sunday?', ['Up the mountain', 'To the castle', 'To the beach'], 'Up the mountain'),
                Kit::question('What did Luis decide?', ['To eat in the village', 'To climb the mountain', 'To go to the lake'], 'To eat in the village'),
            ], [Kit::word('el lago', 'lago'), Kit::word('volver', 'volviste'), Kit::word('la montaña', 'montaña'), Kit::word('subir', 'subimos'), Kit::word('comer', 'comí'), Kit::word('beber', 'bebió'), Kit::word('decidir', 'decidió')], 'read'),
            Kit::gap($stage, 'task.choose_gap.comieron-playa', 'Luis y Marta ___ en la playa.', ['comieron', 'comió', 'comí'], 'comieron', Kit::form('comieron', true), 'Luis y Marta is they, so the verb ends in -ieron: comieron. Comió is for one person.', 'read', 'Luis and Marta ate on the beach.'),
            Kit::gap($stage, 'task.choose_gap.subimos-castillo', 'Subimos al ___ y comimos allí.', ['castillo', 'supermercado', 'cine'], 'castillo', Kit::word('el castillo', 'castillo'), 'You go up to a castle, because it stands on a hill. You do not go up to a supermarket or a cinema.', 'read', 'We went up and ate there.'),

            Kit::transform($stage, 'task.transform.ana-mercado', 'Say that Ana ate in the market.', 'Comí en el mercado.', ['Ana comió en el mercado.'], [Kit::word('comer', 'comió'), Kit::word('el mercado', 'mercado'), Kit::form('comió', true)]),
            Kit::transform($stage, 'task.transform.pablo-ana-bebieron', 'Say that Pablo and Ana drank water.', 'Bebimos agua.', ['Pablo y Ana bebieron agua.', 'Ana y Pablo bebieron agua.'], [Kit::word('beber', 'bebieron'), Kit::form('bebieron', true)]),
            Kit::transform($stage, 'task.transform.subiste', 'Ask a friend instead (informal you).', 'Subí a la montaña.', ['¿Subiste a la montaña?', '¿Tú subiste a la montaña?'], [Kit::word('subir', 'subiste'), Kit::word('la montaña', 'montaña'), Kit::form('subiste')]),
            Kit::writeGuided($stage, 'task.write_guided.mercado-castillo', 'Say that you ate in the market and went up to the castle.', ['comí en', 'el mercado', 'subí', 'castillo'], 'Comí en el mercado y subí al castillo.', [
                ['forms' => ['comí'], 'term' => 'comer'],
                ['forms' => ['mercado'], 'term' => 'el mercado'],
                ['forms' => ['subí'], 'term' => 'subir'],
                ['forms' => ['castillo'], 'term' => 'el castillo'],
            ], [Kit::word('comer', 'comí'), Kit::word('el mercado', 'mercado'), Kit::word('subir', 'subí'), Kit::word('el castillo', 'castillo'), Kit::form('comí')]),
            Kit::writeGuided($stage, 'task.write_guided.playa-agua', 'Say that you came back from the beach and drank water.', ['volví de', 'la playa', 'bebí'], 'Volví de la playa y bebí agua.', [
                ['forms' => ['volví'], 'term' => 'volver'],
                ['forms' => ['playa'], 'term' => 'la playa'],
                ['forms' => ['bebí'], 'term' => 'beber'],
            ], [Kit::word('volver', 'volví'), Kit::word('la playa', 'playa'), Kit::word('beber', 'bebí'), Kit::form('volví', true)]),
            Kit::build($stage, 'task.build.marta-y-yo', 'Marta and I ate fish in the market.', 'Marta y yo comimos pescado en el mercado.', ['comí', 'comió'], [Kit::word('comer', 'comimos'), Kit::form('comimos')]),
            Kit::build($stage, 'task.build.luis-domingo', 'Luis went up to the castle on Sunday.', 'Luis subió al castillo el domingo.', ['subí', 'subimos'], [Kit::word('subir', 'subió'), Kit::word('el castillo', 'castillo'), Kit::form('subió')]),
            Kit::build($stage, 'task.build.decidieron-volver', 'They decided to come back from the lake.', 'Decidieron volver del lago.', ['decidimos', 'volvieron'], [Kit::word('decidir', 'decidieron'), Kit::word('el lago', 'lago'), Kit::form('decidieron')]),
            Kit::translate($stage, 'task.translate.domingo-mercado', 'On Sunday I ate in the market and drank water.', ['El domingo comí en el mercado y bebí agua.', 'El domingo yo comí en el mercado y bebí agua.', 'Comí en el mercado y bebí agua el domingo.', 'Yo comí en el mercado y bebí agua el domingo.'], [Kit::word('beber', 'bebí'), Kit::word('el mercado', 'mercado'), Kit::form('comí')]),
            Kit::translate($stage, 'task.translate.subimos-volvimos', 'We went up the mountain and came back to the village.', ['Subimos a la montaña y volvimos al pueblo.', 'Nosotros subimos a la montaña y volvimos al pueblo.'], [Kit::word('subir', 'subimos'), Kit::word('la montaña', 'montaña'), Kit::word('volver', 'volvimos'), Kit::form('volvimos')]),

            Kit::listenPassage($stage, 'task.listen_passage.playa-castillo', [
                Kit::line('Luis', 'Marta, ¿dónde comiste el domingo?'),
                Kit::line('Marta', 'Comí en la playa con Ana.'),
                Kit::line('Luis', '¿Y qué bebiste?'),
                Kit::line('Marta', 'Bebí agua, y Ana bebió vino.'),
                Kit::line('Luis', '¿Y Pablo? ¿Volvió con Ana?'),
                Kit::line('Marta', 'No, Pablo decidió subir al castillo.'),
            ], [
                Kit::question('Where did Marta eat?', ['On the beach', 'In the market', 'In the village'], 'On the beach'),
                Kit::question('What did Ana drink?', ['Wine', 'Water', 'Coffee'], 'Wine'),
                Kit::question('What did Pablo decide?', ['To go up to the castle', 'To come back with Ana', 'To eat on the beach'], 'To go up to the castle'),
            ], [
                Kit::question('Who went up to the castle?', ['Pablo', 'Marta', 'Luis'], 'Pablo'),
                Kit::question('Did Marta eat with Ana?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who drank wine?', ['Ana', 'Marta', 'Pablo'], 'Ana'),
            ], [Kit::word('comer', 'comiste'), Kit::word('la playa', 'playa'), Kit::word('beber', 'bebiste'), Kit::word('volver', 'volvió'), Kit::word('decidir', 'decidió'), Kit::word('subir', 'subir'), Kit::word('el castillo', 'castillo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.volvi-bebi', 'Volví a casa y bebí agua.', 'I came back home and drank water.', [Kit::word('volver', 'volví'), Kit::word('beber', 'bebí')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.subieron-montana', 'Pablo y Marta subieron a la montaña.', 'Pablo and Marta went up the mountain.', [Kit::word('subir', 'subieron'), Kit::word('la montaña', 'montaña'), Kit::form('subieron')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.decidimos-comer', 'Decidimos comer en el mercado.', 'We decided to eat in the market.', [Kit::word('decidir', 'decidimos'), Kit::word('comer', 'comer'), Kit::word('el mercado', 'mercado')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.que-comiste', '¿Qué comiste en el mercado?', 'What did you eat in the market? (informal you)', [['comí']], 'Comí fruta y queso.', [Kit::word('comer', 'comí'), Kit::word('el mercado', 'mercado')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.subiste-montana', '¿Subiste a la montaña?', 'Did you go up the mountain? (informal you)', [['sí', 'no'], ['subí']], 'Sí, subí a la montaña.', [Kit::word('subir', 'subí'), Kit::word('la montaña', 'montaña')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.bebiste-playa', '¿Bebiste agua en la playa?', 'Did you drink water on the beach? (informal you)', [['sí', 'no'], ['bebí']], 'Sí, bebí agua en la playa.', [Kit::word('beber', 'bebí'), Kit::word('la playa', 'playa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.volviste-lago', '¿Volviste del lago el domingo?', 'Did you come back from the lake on Sunday? (informal you)', [['sí', 'no'], ['volví']], 'Sí, volví del lago el domingo.', [Kit::word('volver', 'volví'), Kit::word('el lago', 'lago')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.decidimos-subir', 'Decidimos subir al castillo.', 'We decided to go up to the castle.', [Kit::word('decidir', 'decidimos'), Kit::word('el castillo', 'castillo'), Kit::form('decidimos')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.comimos-volvimos', 'Comimos en el mercado y volvimos al pueblo.', 'We ate in the market and came back to the village.', [Kit::word('comer', 'comimos'), Kit::word('el mercado', 'mercado'), Kit::word('volver', 'volvimos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.pablo-vino-fruta', 'Pablo drank wine and ate fruit in the market.', ['Pablo bebió vino y comió fruta en el mercado.'], [Kit::word('beber', 'bebió'), Kit::word('comer', 'comió'), Kit::word('el mercado', 'mercado'), Kit::form('bebió')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.castillo-playa', 'We went up to the castle and came back to the beach.', ['Subimos al castillo y volvimos a la playa.', 'Nosotros subimos al castillo y volvimos a la playa.'], [Kit::word('subir', 'subimos'), Kit::word('el castillo', 'castillo'), Kit::word('volver', 'volvimos'), Kit::word('la playa', 'playa')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.decidi-montana', 'I decided to go up the mountain.', ['Decidí subir a la montaña.', 'Yo decidí subir a la montaña.'], [Kit::word('decidir', 'decidí'), Kit::word('subir', 'subir'), Kit::word('la montaña', 'montaña'), Kit::form('decidí')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.volviste-lunes', 'Did you come back from the lake on Monday? (informal you)', ['¿Volviste del lago el lunes?', '¿Tú volviste del lago el lunes?', '¿El lunes volviste del lago?'], [Kit::word('volver', 'volviste'), Kit::word('el lago', 'lago'), Kit::form('volviste', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-luis-comieron', 'Ana y Luis ___ en el mercado.', 'Ana and Luis ate in the market.', 'comieron', Kit::form('comieron', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-bebio', 'Marta ___ agua en la playa.', 'Marta drank water on the beach.', 'bebió', Kit::word('beber', 'bebió'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-montana', 'Marta subió a la montaña.', 'Marta went up the mountain.', [Kit::word('subir', 'subió'), Kit::word('la montaña', 'montaña'), Kit::form('subió', true)], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.decidimos-castillo', 'Decidimos comer en el castillo.', 'We decided to eat in the castle.', [Kit::word('decidir', 'decidimos'), Kit::word('comer', 'comer'), Kit::word('el castillo', 'castillo')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.bebi-vino-lago', 'Bebí vino en el lago.', 'I drank wine at the lake.', [Kit::word('beber', 'bebí'), Kit::word('el lago', 'lago'), Kit::form('bebí')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.playa-mercado', [
                Kit::line('Pablo', 'Luis, ¿volviste de la playa?'),
                Kit::line('Luis', 'Sí, volví el lunes. Comí en el mercado con Ana.'),
                Kit::line('Pablo', '¿Y tú, qué bebiste?'),
                Kit::line('Luis', 'Bebí agua. Ana bebió vino.'),
            ], [
                Kit::question('When did Luis come back?', ['On Monday', 'On Sunday', 'Today'], 'On Monday'),
                Kit::question('Where did Luis eat?', ['In the market', 'On the beach', 'At the castle'], 'In the market'),
                Kit::question('What did Ana drink?', ['Wine', 'Water', 'Coffee'], 'Wine'),
            ], [
                Kit::question('Who ate in the market with Ana?', ['Luis', 'Pablo', 'Marta'], 'Luis'),
                Kit::question('Did Luis drink water?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who drank wine?', ['Ana', 'Luis', 'Pablo'], 'Ana'),
            ], [Kit::word('volver', 'volviste'), Kit::word('la playa', 'playa'), Kit::word('comer', 'comí'), Kit::word('el mercado', 'mercado'), Kit::word('beber', 'bebiste')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.castillo', 'Read the conversation.', [
                Kit::line('Ana', 'Marta, ¿subiste al castillo?'),
                Kit::line('Marta', 'No, decidí comer en el pueblo.'),
                Kit::line('Ana', '¿Y Pablo?'),
                Kit::line('Marta', 'Pablo subió a la montaña con Luis.'),
            ], [
                Kit::question('Where did Marta decide to eat?', ['In the village', 'At the castle', 'On the mountain'], 'In the village'),
                Kit::question('Where did Pablo go?', ['Up the mountain', 'To the castle', 'To the village'], 'Up the mountain'),
            ], [Kit::word('subir', 'subiste'), Kit::word('el castillo', 'castillo'), Kit::word('decidir', 'decidí'), Kit::word('la montaña', 'montaña')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.que-bebiste-domingo', '¿Qué bebiste el domingo?', 'What did you drink on Sunday? (informal you)', [['bebí']], 'Bebí agua.', [Kit::word('beber', 'bebí')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.decidiste-playa', '¿Decidiste ir a la playa?', 'Did you decide to go to the beach? (informal you)', [['sí', 'no'], ['decidí']], 'Sí, decidí ir a la playa.', [Kit::word('decidir', 'decidí'), Kit::word('la playa', 'playa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.subiste-castillo', '¿Subiste al castillo con Pablo?', 'Did you go up to the castle with Pablo? (informal you)', [['sí', 'no'], ['subí']], 'Sí, subí al castillo con Pablo.', [Kit::word('subir', 'subí'), Kit::word('el castillo', 'castillo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luis-pescado-agua', 'Luis ate fish and drank water on the beach.', ['Luis comió pescado y bebió agua en la playa.'], [Kit::word('comer', 'comió'), Kit::word('beber', 'bebió'), Kit::word('la playa', 'playa'), Kit::form('comió', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.decidimos-volver', 'We decided to come back from the mountain.', ['Decidimos volver de la montaña.', 'Nosotros decidimos volver de la montaña.'], [Kit::word('decidir', 'decidimos'), Kit::word('volver', 'volver'), Kit::word('la montaña', 'montaña'), Kit::form('decidimos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-castillo-mercado', 'Marta went up to the castle and ate in the market.', ['Marta subió al castillo y comió en el mercado.'], [Kit::word('subir', 'subió'), Kit::word('el castillo', 'castillo'), Kit::word('el mercado', 'mercado'), Kit::form('subió', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.volvi-lago-vino', 'I came back from the lake and drank wine.', ['Volví del lago y bebí vino.', 'Yo volví del lago y bebí vino.'], [Kit::word('volver', 'volví'), Kit::word('el lago', 'lago'), Kit::word('beber', 'bebí'), Kit::form('volví', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.nosotros-volvimos', 'Nosotros ___ de la playa.', 'We came back from the beach.', 'volvimos', Kit::form('volvimos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.subi-montana', 'Subí al ___ con Pablo.', 'I went up to the castle with Pablo.', 'castillo', Kit::word('el castillo', 'castillo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.bebieron-lago', 'Luis y Ana bebieron agua en el lago.', 'Luis and Ana drank water at the lake.', [Kit::word('beber', 'bebieron'), Kit::word('el lago', 'lago'), Kit::form('bebieron')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.decidi-castillo', 'Decidí subir al castillo con Marta.', 'I decided to go up to the castle with Marta.', [Kit::word('decidir', 'decidí'), Kit::word('subir', 'subir'), Kit::word('el castillo', 'castillo')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.comimos-volvimos', 'Comimos en el mercado y volvimos a la playa.', 'We ate in the market and came back to the beach.', [Kit::word('comer', 'comimos'), Kit::word('el mercado', 'mercado'), Kit::word('volver', 'volvimos'), Kit::word('la playa', 'playa')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
