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

final class MakingPlans implements UnitContent
{
    private const A_NOTE = 'The word a (to) sounds the same as ha (has). Here it is a, as in voy a or invitar a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'making-plans';
    }

    public function words(): array
    {
        return [
            new WordData('el plan', cue: 'plan (something you arrange to do)'),
            new WordData('la fiesta', cue: 'party'),
            new WordData('el cine', cue: 'cinema'),
            new WordData('la cena', cue: 'dinner (the evening meal)', forms: ['cenar']),
            new WordData('el café', cue: 'café (the place where you meet)'),
            new WordData('invitar', cue: 'to invite'),
            new WordData('quedar', cue: 'to meet up (to arrange to meet)', forms: ['quedamos']),
            new WordData('¿quieres venir?', cue: 'do you want to come? (informal you)', forms: ['quieres', 'venir']),
            new WordData('lo siento', cue: 'I am sorry'),
            new WordData('claro', cue: 'of course', accepted: ['claro que sí', 'por supuesto']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Vamos a invitar a Ana.', 'english' => 'We are going to invite Ana.'],
            ['text' => '¿Puedes venir? No puedo.', 'english' => 'Can you come? I cannot.'],
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
            Kit::gap($stage, 'sentences.choose_gap.voy-invitar', 'Yo ___ a invitar a Ana.', ['voy', 'vas', 'va'], 'voy', Kit::form('voy', true), 'Yo goes with voy. Vas is for tú and va is for él or ella.', 'choose', glosses: ['voy' => 'I go', 'vas' => 'you go', 'va' => 'he or she goes']),
            Kit::gap($stage, 'sentences.choose_gap.lo-siento', '¿Puedes venir? No, ___, no puedo.', ['lo siento', 'el plan', 'la fiesta'], 'lo siento', Kit::word('lo siento'), 'Lo siento says you are sorry. El plan and la fiesta are things, so they do not fit here.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.cine', 'Vamos al ___ con Marta.', ['cine', 'cena', 'fiesta'], 'cine', Kit::word('el cine', 'cine'), 'Al means a + el, so it needs a masculine word: el cine. Cena and fiesta are feminine.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.claro', '¿Puedes venir? ___, voy a venir.', ['Claro', 'La cena', 'El plan'], 'Claro', Kit::word('claro'), 'Claro means of course, so it fits a yes. La cena and el plan are things, not answers.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.invitar', 'Voy a ___ a Pablo a la fiesta.', ['invitar', 'quedar', 'cenar'], 'invitar', Kit::word('invitar'), 'You invite a person: invitar a Pablo. Quedar means to meet up and cenar means to have dinner.', 'choose', glosses: ['quedar' => 'to meet up', 'cenar' => 'to have dinner']),
            Kit::gap($stage, 'sentences.choose_gap.puedes', 'Pablo, ¿tú ___ venir a la cena?', ['puedes', 'puedo', 'podemos'], 'puedes', Kit::form('puedes', true), 'Tú goes with puedes. Puedo is for yo and podemos is for nosotros.', 'choose', glosses: ['puedes' => 'you can', 'puedo' => 'I can', 'podemos' => 'we can']),

            Kit::typeGap($stage, 'sentences.type_gap.vamos-cenar', '___ a cenar en el café.', 'We are going to have dinner at the café.', 'Vamos', Kit::form('vamos'), 'We is vamos. After vamos come a and the verb: vamos a cenar.', glosses: ['cenar' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.voy-invitar', 'Yo ___ a invitar a Marta.', 'I am going to invite Marta.', 'voy', Kit::form('voy', true), 'I is voy. After voy come a and the verb: voy a invitar.'),
            Kit::typeGap($stage, 'sentences.type_gap.puedes-cine', 'Pablo, ¿___ venir al cine?', 'Pablo, can you come to the cinema? (informal you)', 'puedes', Kit::form('puedes'), 'Pablo is the person you ask, so use puedes. The verb after it stays venir.', glosses: ['venir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.puedo-fiesta', 'Yo no ___ venir a la fiesta.', 'I cannot come to the party.', 'puedo', Kit::form('puedo'), 'I is puedo, and no goes before it. There is no a before venir.', glosses: ['venir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.donde-quedamos', '¿Dónde ___?', 'Where shall we meet?', 'quedamos', Kit::word('quedar', 'quedamos'), null, glosses: ['quedamos' => 'we meet up']),

            Kit::translate($stage, 'sentences.translate.vamos-invitar', 'We are going to invite Pablo.', ['Vamos a invitar a Pablo.', 'Nosotros vamos a invitar a Pablo.'], [Kit::word('invitar'), Kit::form('vamos a')], glosses: ['invitar' => 'to invite']),
            Kit::translate($stage, 'sentences.translate.puedes-fiesta', 'Can you come to the party? (informal you)', ['¿Puedes venir a la fiesta?', '¿Tú puedes venir a la fiesta?'], [Kit::word('la fiesta', 'fiesta'), Kit::form('puedes')], glosses: ['venir' => 'to come']),
            Kit::translate($stage, 'sentences.translate.voy-cenar', 'I am going to have dinner with Luis.', ['Voy a cenar con Luis.', 'Yo voy a cenar con Luis.'], [Kit::word('la cena', 'cenar'), Kit::form('voy a')], glosses: ['cenar' => 'to have dinner']),

            Kit::build($stage, 'sentences.build.voy-invitar', 'I am going to invite Ana.', 'Voy a invitar a Ana.', ['vamos'], [Kit::word('invitar'), Kit::form('voy a')], glosses: ['invitar' => 'to invite']),
            Kit::build($stage, 'sentences.build.puedes-cafe', 'Can you come to the café? (informal you)', '¿Puedes venir al café?', ['puedo'], [Kit::word('el café', 'café'), Kit::form('puedes')], glosses: ['venir' => 'to come']),
            Kit::build($stage, 'sentences.build.quedamos-cine', 'We are meeting at the cinema.', 'Quedamos en el cine.', ['quedar'], [Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine')], glosses: ['quedar' => 'to meet up', 'quedamos' => 'we meet up']),

            Kit::listenChoose($stage, 'sentences.listen_choose.plan-venir', '¿Tienes un plan? ¿Quieres venir?', ['Do you have a plan? Do you want to come?', 'Do you have a plan? Can you come?', 'Do you have a party? Do you want to come?', 'Do I have a plan? Do you want to come?'], 'Do you have a plan? Do you want to come?', [Kit::word('el plan', 'plan'), Kit::word('¿quieres venir?', 'quieres venir')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.lo-siento', 'Lo siento, no puedo venir.', ['I am sorry, I cannot come.', 'Of course, I can come.', 'I am sorry, we cannot come.', 'I am sorry, you cannot come.'], 'I am sorry, I cannot come.', [Kit::word('lo siento'), Kit::form('puedo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vamos-cenar', 'Vamos a cenar en el café.', ['We are going to have dinner at the café.', 'I am going to have dinner at the café.', 'We are going to have dinner at the cinema.', 'They are going to have dinner at the café.'], 'We are going to have dinner at the café.', [Kit::word('la cena', 'cenar'), Kit::word('el café', 'café'), Kit::form('vamos a')]),
            Kit::listenType($stage, 'sentences.listen_type.claro-voy', 'Claro, voy a venir.', 'Of course, I am going to come.', [Kit::word('claro'), Kit::form('voy a')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.plan-cenar', 'El plan es cenar en el café.', 'The plan is to have dinner at the café.', [Kit::word('el plan', 'plan'), Kit::word('el café', 'café'), Kit::word('la cena', 'cenar')]),
            Kit::listenType($stage, 'sentences.listen_type.fiesta', 'Ana tiene una fiesta. ¿Quieres venir?', 'Ana has a party. Do you want to come?', [Kit::word('la fiesta', 'fiesta'), Kit::word('¿quieres venir?', 'quieres venir')]),
            Kit::listenType($stage, 'sentences.listen_type.puedes-cenar', 'Ana, ¿puedes cenar con Luis?', 'Ana, can you have dinner with Luis?', [Kit::form('puedes'), Kit::word('la cena', 'cenar')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.quieres-cine', '¿Quieres venir al cine?', 'Do you want to come to the cinema?', [Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('el cine', 'cine')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.lo-siento', 'Lo siento, no puedo.', 'I am sorry, I cannot.', [Kit::word('lo siento'), Kit::form('puedo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.vamos-invitar', 'Vamos a invitar a Pablo.', 'We are going to invite Pablo.', [Kit::word('invitar'), Kit::form('vamos a')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.donde-quedamos', '¿Dónde quedamos? ¿En el café?', 'Where shall we meet? At the café?', [Kit::word('quedar', 'quedamos'), Kit::word('el café', 'café')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cine', '¿Quieres venir al cine?', 'Do you want to come to the cinema?', [['claro', 'sí', 'no', 'siento'], ['voy', 'puedo']], 'Claro, voy a venir.', [Kit::word('claro'), Kit::word('el cine', 'cine'), Kit::form('voy')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cena', '¿Puedes venir a la cena?', 'Can you come to the dinner? (informal you)', [['sí', 'no', 'claro', 'siento'], ['puedo', 'voy']], 'Sí, puedo venir.', [Kit::word('la cena'), Kit::form('puedo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.donde', '¿Dónde quedamos?', 'Where shall we meet?', [['quedamos', 'en'], ['cine', 'café']], 'Quedamos en el cine.', [Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fiesta', 'Read the conversation.', [
                Kit::line('Ana', 'Hola, Pablo. Hay una fiesta en el café. ¿Quieres venir?'),
                Kit::line('Pablo', 'Lo siento, Ana. No puedo venir.'),
                Kit::line('Pablo', 'Voy a cenar con Marta.'),
                Kit::line('Ana', 'Claro, tienes un plan. ¡Adiós!'),
            ], [
                Kit::question('Where is the party?', ['In the café', 'In the cinema', 'In a restaurant'], 'In the café'),
                Kit::question('Can Pablo come?', ['No', 'Yes', 'The text does not say.'], 'No'),
                Kit::question('What is Pablo going to do?', ['Have dinner with Marta', 'Invite Ana', 'Go to the cinema'], 'Have dinner with Marta'),
            ], [Kit::word('la fiesta', 'fiesta'), Kit::word('el café', 'café'), Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('lo siento'), Kit::word('la cena', 'cenar'), Kit::word('claro')], 'read'),
            Kit::gap($stage, 'task.choose_gap.va-invitar', 'Ana va ___ invitar a Pablo.', ['a', 'de', 'en'], 'a', Kit::form('a', true), 'In a plan, a comes between ir and the verb: va a invitar. De and en do not link ir to a verb.', 'read'),
            Kit::gap($stage, 'task.choose_gap.plan', 'Tengo un ___: quedar en el cine.', ['plan', 'cena', 'fiesta'], 'plan', Kit::word('el plan', 'plan'), 'Un goes with a masculine word. Cena and fiesta are feminine, so they need una.', 'read'),

            Kit::transform($stage, 'task.transform.vamos-cenar', 'Change it to nosotros.', 'Voy a cenar en el café.', ['Vamos a cenar en el café.', 'Nosotros vamos a cenar en el café.'], [Kit::word('la cena', 'cenar'), Kit::form('vamos a')]),
            Kit::transform($stage, 'task.transform.ana-puede', 'Say it about Ana.', 'Puedo venir a la fiesta.', ['Ana puede venir a la fiesta.'], [Kit::word('la fiesta', 'fiesta'), Kit::form('puede')]),
            Kit::transform($stage, 'task.transform.vas-invitar', 'Change it to tú.', 'Voy a invitar a Marta.', ['Vas a invitar a Marta.', 'Tú vas a invitar a Marta.'], [Kit::word('invitar'), Kit::form('vas a')]),
            Kit::writeGuided($stage, 'task.write_guided.invitar', 'Say that you are going to invite Pablo to the party.', ['voy a', 'invitar', 'fiesta'], 'Voy a invitar a Pablo a la fiesta.', [
                ['forms' => ['voy'], 'term' => null],
                ['forms' => ['invitar'], 'term' => 'invitar'],
                ['forms' => ['fiesta'], 'term' => 'la fiesta'],
            ], [Kit::word('invitar'), Kit::word('la fiesta', 'fiesta')]),
            Kit::writeGuided($stage, 'task.write_guided.lo-siento', 'Say that you are sorry and that you cannot come to the cinema.', ['lo siento', 'no puedo', 'venir', 'cine'], 'Lo siento, no puedo venir al cine.', [
                ['forms' => ['siento'], 'term' => 'lo siento'],
                ['forms' => ['puedo'], 'term' => null],
                ['forms' => ['cine'], 'term' => 'el cine'],
            ], [Kit::word('lo siento'), Kit::word('el cine', 'cine')], ['venir' => 'to come']),
            Kit::build($stage, 'task.build.vamos-cenar', 'We are going to have dinner at the café.', 'Vamos a cenar en el café.', ['voy', 'con'], [Kit::word('la cena', 'cenar'), Kit::word('el café', 'café'), Kit::form('vamos a')], 'write', ['cenar' => 'to have dinner']),
            Kit::build($stage, 'task.build.puedes-invitar', 'Can you invite Marta to the party? (informal you)', '¿Puedes invitar a Marta a la fiesta?', ['puedo', 'en'], [Kit::word('invitar'), Kit::word('la fiesta', 'fiesta'), Kit::form('puedes')], 'write', ['invitar' => 'to invite']),
            Kit::build($stage, 'task.build.lo-siento-cena', 'I am sorry, I cannot come to the dinner.', 'Lo siento, no puedo venir a la cena.', ['puedes', 'claro'], [Kit::word('lo siento'), Kit::word('la cena', 'cena'), Kit::form('puedo')], 'write', ['venir' => 'to come']),
            Kit::translate($stage, 'task.translate.vas-cine', 'Are you going to come to the cinema? (informal you)', ['¿Vas a venir al cine?', '¿Tú vas a venir al cine?'], [Kit::word('el cine', 'cine'), Kit::form('vas a')], 'write', glosses: ['venir' => 'to come']),
            Kit::translate($stage, 'task.translate.claro-quedar', 'Of course, we are going to meet at the café.', ['Claro, vamos a quedar en el café.', 'Claro, nosotros vamos a quedar en el café.'], [Kit::word('claro'), Kit::word('quedar'), Kit::form('vamos a')], 'write', glosses: ['quedar' => 'to meet up']),

            Kit::listenPassage($stage, 'task.listen_passage.cine', [
                Kit::line('Marta', 'Hola, Luis. ¿Tienes un plan?'),
                Kit::line('Luis', 'No, ¿por qué?'),
                Kit::line('Marta', 'Ana y yo vamos al cine. ¿Quieres venir?'),
                Kit::line('Luis', 'Claro. ¿Dónde quedamos?'),
                Kit::line('Marta', 'Quedamos en el cine.'),
                Kit::line('Luis', 'Muy bien. Adiós.'),
            ], [
                Kit::question('What are Marta and Ana going to do?', ['Go to the cinema', 'Go to a party', 'Have dinner'], 'Go to the cinema'),
                Kit::question('What does Luis answer to the invitation?', ['Of course', 'Sorry, no', 'He does not know'], 'Of course'),
                Kit::question('Where do they meet?', ['At the cinema', 'At the café', 'At the party'], 'At the cinema'),
            ], [
                Kit::question('Does Luis have a plan?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('How does the conversation end?', ['Luis says goodbye', 'Marta says sorry', 'Luis says no'], 'Luis says goodbye'),
            ], [Kit::word('el plan', 'plan'), Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('claro'), Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.lo-siento-cena', 'Lo siento, Ana. No puedo venir a la cena.', 'I am sorry, Ana. I cannot come to the dinner.', [Kit::word('lo siento'), Kit::word('la cena', 'cena'), Kit::form('puedo')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.va-invitar', 'Marta va a invitar a Luis a la fiesta.', 'Marta is going to invite Luis to the party.', [Kit::word('invitar'), Kit::word('la fiesta', 'fiesta'), Kit::form('va a')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.claro-quedamos', 'Claro, ¿quedamos en el cine o en el café?', 'Of course, shall we meet at the cinema or at the café?', [Kit::word('claro'), Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine'), Kit::word('el café', 'café')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.fiesta', '¿Quieres venir a la fiesta?', 'Do you want to come to the party?', [['claro', 'sí', 'no', 'siento'], ['voy', 'puedo']], 'Lo siento, no puedo venir.', [Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('la fiesta', 'fiesta'), Kit::word('lo siento')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.invitar', '¿Vas a invitar a Ana?', 'Are you going to invite Ana?', [['sí', 'no', 'claro'], ['voy']], 'Sí, voy a invitar a Ana.', [Kit::word('invitar'), Kit::form('voy')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.plan', '¿Tienes un plan?', 'Do you have a plan?', [['sí', 'no'], ['tengo', 'voy', 'vamos']], 'Sí, tengo un plan.', [Kit::word('el plan', 'plan')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quedamos', '¿Dónde quedamos, en el cine o en el café?', 'Where shall we meet, at the cinema or at the café?', [['quedamos', 'en'], ['cine', 'café']], 'Quedamos en el café.', [Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine'), Kit::word('el café', 'café')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.lo-siento-cena', 'Lo siento, no puedo venir a la cena.', 'I am sorry, I cannot come to the dinner.', [Kit::word('lo siento'), Kit::word('la cena', 'cena'), Kit::form('puedo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.claro-quedar', 'Claro, vamos a quedar en el cine.', 'Of course, we are going to meet at the cinema.', [Kit::word('claro'), Kit::word('quedar'), Kit::word('el cine', 'cine'), Kit::form('vamos a')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.quieres-cafe', 'Do you want to come to the café with Ana? (informal you)', ['¿Quieres venir al café con Ana?'], [Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('el café', 'café')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vamos-cena', 'We are going to invite Marta to the dinner.', ['Vamos a invitar a Marta a la cena.', 'Nosotros vamos a invitar a Marta a la cena.'], [Kit::word('invitar'), Kit::word('la cena', 'cena'), Kit::form('vamos a')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.lo-siento', 'I am sorry, I cannot come to the party.', ['Lo siento, no puedo venir a la fiesta.'], [Kit::word('lo siento'), Kit::word('la fiesta', 'fiesta'), Kit::form('puedo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.puedes-cine', 'Can you come to the cinema? (informal you)', ['¿Puedes venir al cine?', '¿Tú puedes venir al cine?'], [Kit::word('el cine', 'cine'), Kit::form('puedes', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.va-cenar', 'Ana ___ a cenar en el café.', 'Ana is going to have dinner at the café.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.puede-cena', 'Marta no ___ venir a la cena.', 'Marta cannot come to the dinner.', 'puede', Kit::form('puede', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.claro-voy', 'Claro, voy a cenar con Pablo.', 'Of course, I am going to have dinner with Pablo.', [Kit::word('claro'), Kit::word('la cena', 'cenar'), Kit::form('voy a')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.plan', 'Tengo un plan: cenar en el café.', 'I have a plan: to have dinner at the café.', [Kit::word('el plan', 'plan'), Kit::word('la cena', 'cenar'), Kit::word('el café', 'café')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.donde', '¿Dónde quedamos? ¿En el cine?', 'Where shall we meet? At the cinema?', [Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.fiesta', [
                Kit::line('Pablo', 'Hola, Ana. Hay una fiesta. ¿Quieres venir?'),
                Kit::line('Ana', 'Claro. ¿Dónde quedamos?'),
                Kit::line('Pablo', 'Quedamos aquí, en el café.'),
            ], [
                Kit::question('What does Pablo ask Ana?', ['If she wants to come to the party', 'If she can have dinner', 'If she has a plan'], 'If she wants to come to the party'),
                Kit::question('What does Ana answer?', ['Of course', 'Sorry, no', 'The conversation does not say.'], 'Of course'),
                Kit::question('Where do they meet?', ['At the café', 'At the party', 'At the cinema'], 'At the café'),
            ], [
                Kit::question('Who speaks first?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Ana say yes?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la fiesta', 'fiesta'), Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('claro'), Kit::word('quedar', 'quedamos'), Kit::word('el café', 'café')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.plan', 'Read the conversation.', [
                Kit::line('Luis', 'Hola, Marta. ¿Qué plan tienes?'),
                Kit::line('Marta', 'Voy a invitar a Ana al cine. ¿Quieres venir?'),
                Kit::line('Luis', 'Lo siento, Marta. No puedo. Voy a cenar con Pablo.'),
                Kit::line('Marta', 'Claro, Luis. Adiós.'),
            ], [
                Kit::question('Where is Marta going to invite Ana?', ['To the cinema', 'To a party', 'To a café'], 'To the cinema'),
                Kit::question('Can Luis come?', ['No', 'Yes', 'The text does not say.'], 'No'),
            ], [Kit::word('el plan', 'plan'), Kit::word('invitar'), Kit::word('el cine', 'cine'), Kit::word('¿quieres venir?', 'quieres venir'), Kit::word('lo siento'), Kit::word('la cena', 'cenar'), Kit::word('claro')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fiesta-ana', '¿Quieres venir a la fiesta de Ana?', 'Do you want to come to Ana\'s party?', [['claro', 'sí', 'no', 'siento'], ['voy', 'puedo']], 'Claro, voy a venir.', [Kit::word('claro'), Kit::word('la fiesta', 'fiesta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.plan-fiesta', '¿Tienes un plan para la fiesta?', 'Do you have a plan for the party?', [['sí', 'no'], ['tengo', 'voy', 'vamos']], 'Sí, tengo un plan.', [Kit::word('el plan', 'plan'), Kit::word('la fiesta', 'fiesta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quedamos-cine', '¿Quedamos en el cine, Pablo?', 'Shall we meet at the cinema, Pablo?', [['sí', 'no', 'claro', 'siento'], ['quedamos', 'puedo', 'café']], 'Claro, quedamos en el cine.', [Kit::word('quedar', 'quedamos'), Kit::word('el cine', 'cine'), Kit::word('claro')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vamos-invitar', 'We are going to invite Ana to the party.', ['Vamos a invitar a Ana a la fiesta.', 'Nosotros vamos a invitar a Ana a la fiesta.'], [Kit::word('invitar'), Kit::word('la fiesta', 'fiesta'), Kit::form('vamos a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.puedes-fiesta', 'Can you come to the party at the café? (informal you)', ['¿Puedes venir a la fiesta en el café?', '¿Tú puedes venir a la fiesta en el café?'], [Kit::word('la fiesta', 'fiesta'), Kit::word('el café', 'café'), Kit::form('puedes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.voy-cenar', 'I am sorry, I am going to have dinner with Luis.', ['Lo siento, voy a cenar con Luis.', 'Lo siento, yo voy a cenar con Luis.'], [Kit::word('lo siento'), Kit::word('la cena', 'cenar'), Kit::form('voy a')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.plan-cine', 'Is the plan to meet at the cinema? Of course.', ['¿El plan es quedar en el cine? Claro.', '¿Es el plan quedar en el cine? Claro.'], [Kit::word('el plan', 'plan'), Kit::word('quedar'), Kit::word('el cine', 'cine'), Kit::word('claro')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.va-invitar', 'Marta ___ a invitar a Pablo.', 'Marta is going to invite Pablo.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.venir', '¿Quieres ___ con nosotros?', 'Do you want to come with us?', 'venir', Kit::word('¿quieres venir?', 'venir'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.plan-cenar', 'El plan es cenar. ¿Quieres venir?', 'The plan is to have dinner. Do you want to come?', [Kit::word('el plan', 'plan'), Kit::word('la cena', 'cenar'), Kit::word('¿quieres venir?', 'quieres venir')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.podemos', 'Claro, ¿podemos invitar a Pablo al cine?', 'Of course, can we invite Pablo to the cinema?', [Kit::word('claro'), Kit::form('podemos'), Kit::word('invitar'), Kit::word('el cine', 'cine')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.puede-quedar', 'Lo siento, Ana no puede quedar en el café.', 'I am sorry, Ana cannot meet at the café.', [Kit::word('lo siento'), Kit::form('puede'), Kit::word('quedar'), Kit::word('el café', 'café')], 'dictation', $set),
        ];
    }
}
