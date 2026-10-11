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

final class MakingPlans implements UnitContent
{
    private const A_NOTE = 'Both a words are the article a (the), not à (to the) and not há (there is).';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'making-plans';
    }

    public function words(): array
    {
        return [
            new WordData('o plano', cue: 'plan (something you arrange to do)', portunolSlips: ['plan']),
            new WordData('a festa', cue: 'party', portunolSlips: ['fiesta']),
            new WordData('o cinema', cue: 'cinema', portunolSlips: ['cine']),
            new WordData('o jantar', cue: 'dinner (the evening meal)', portunolSlips: ['cena', 'cenar']),
            new WordData('o café', cue: 'café (the place where you meet)'),
            new WordData('convidar', cue: 'to invite', portunolSlips: ['invitar']),
            new WordData('combinar', cue: 'to arrange (to meet)', forms: ['combinamos'], portunolSlips: ['quedar', 'quedamos']),
            new WordData('queres vir?', cue: 'do you want to come? (informal you)', forms: ['queres', 'vir'], portunolSlips: ['quieres venir']),
            new WordData('desculpa', cue: 'I am sorry (informal you)', portunolSlips: ['lo siento']),
            new WordData('claro', cue: 'of course', accepted: ['claro que sim', 'com certeza']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Vamos convidar a Ana.', 'english' => 'We are going to invite Ana.'],
            ['text' => 'Podes vir? Não posso.', 'english' => 'Can you come? I cannot.'],
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
            Kit::gap($stage, 'sentences.choose_gap.vou-convidar', 'Eu ___ convidar a Ana.', ['vou', 'vais', 'vai'], 'vou', Kit::form('vou', true), 'Eu goes with vou, and the infinitive follows with no a. Vais is for tu and vai is for ele or ela.', 'choose', glosses: ['vou' => 'I go', 'vais' => 'you go', 'vai' => 'he or she goes']),
            Kit::gap($stage, 'sentences.choose_gap.desculpa', 'Queres vir? Não, ___, não posso.', ['desculpa', 'o plano', 'a festa'], 'desculpa', Kit::word('desculpa'), 'Desculpa says you are sorry. O plano and a festa are things, so they do not fit here.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.cinema', 'Vamos ao ___ com a Marta.', ['cinema', 'festa', 'plano'], 'cinema', Kit::word('o cinema', 'cinema'), 'Ao means a + o, so it needs a masculine place: o cinema. Festa is feminine (à festa). O plano is masculine, but it is not a place you go to.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.claro', 'Queres vir? ___, vou vir.', ['Claro', 'O jantar', 'O plano'], 'Claro', Kit::word('claro'), 'Claro means of course, so it fits a yes. O jantar and o plano are things, not answers.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.convidar', 'Vou ___ o João para a festa.', ['convidar', 'combinar', 'jantar'], 'convidar', Kit::word('convidar'), 'You invite a person: convidar o João. Combinar means to arrange and jantar means to have dinner.', 'choose', glosses: ['combinar' => 'to arrange', 'jantar' => 'to have dinner']),
            Kit::gap($stage, 'sentences.choose_gap.podes', 'João, ___ vir ao jantar?', ['podes', 'posso', 'podemos'], 'podes', Kit::form('podes', true), 'João is the person you are asking, so use podes (informal you). Posso is for eu and podemos is for nós.', 'choose', glosses: ['podes' => 'you can', 'posso' => 'I can', 'podemos' => 'we can']),

            Kit::typeGap($stage, 'sentences.type_gap.vamos-jantar', '___ jantar no café.', 'We are going to have dinner at the café.', 'Vamos', Kit::form('vamos'), 'We is vamos. The infinitive follows directly: vamos jantar, with no a.', glosses: ['jantar' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.vou-convidar', 'Eu ___ jantar com a Marta.', 'I am going to have dinner with Marta.', 'vou', Kit::form('vou', true), 'I is vou. Unlike Spanish voy a, Portuguese has no a: vou jantar.', glosses: ['jantar' => 'to have dinner']),
            Kit::typeGap($stage, 'sentences.type_gap.podes-cinema', 'João, ___ vir ao cinema?', 'João, can you come to the cinema? (informal you)', 'podes', Kit::form('podes'), 'João is the person you ask, so use podes. The verb after it stays vir.', glosses: ['vir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.posso-festa', 'Eu não ___ vir à festa.', 'I cannot come to the party.', 'posso', Kit::form('posso'), 'I is posso, and não goes before it. The infinitive vir follows with no a.', glosses: ['vir' => 'to come']),
            Kit::typeGap($stage, 'sentences.type_gap.onde-combinamos', 'Onde ___?', 'Where shall we meet?', 'combinamos', Kit::word('combinar', 'combinamos'), 'Combinamos is the we form of combinar: onde combinamos?', glosses: ['combinamos' => 'we arrange']),

            Kit::translate($stage, 'sentences.translate.vamos-convidar', 'We are going to invite João.', ['Vamos convidar o João.', 'Nós vamos convidar o João.'], [Kit::word('convidar'), Kit::form('vamos')], glosses: ['convidar' => 'to invite']),
            Kit::translate($stage, 'sentences.translate.podes-festa', 'Can you come to the party? (informal you)', ['Podes vir à festa?', 'Tu podes vir à festa?', 'Podes vir para a festa?'], [Kit::word('a festa', 'festa'), Kit::form('podes')], glosses: ['vir' => 'to come']),
            Kit::translate($stage, 'sentences.translate.vou-jantar', 'I am going to have dinner with Rui.', ['Vou jantar com o Rui.', 'Eu vou jantar com o Rui.'], [Kit::word('o jantar', 'jantar'), Kit::form('vou')], glosses: ['jantar' => 'to have dinner']),

            Kit::build($stage, 'sentences.build.vou-convidar', 'I am going to invite Ana.', 'Vou convidar a Ana.', ['vamos'], [Kit::word('convidar'), Kit::form('vou')], glosses: ['convidar' => 'to invite']),
            Kit::build($stage, 'sentences.build.podes-cafe', 'Can you come to the café? (informal you)', 'Podes vir ao café?', ['posso'], [Kit::word('o café', 'café'), Kit::form('podes')], glosses: ['vir' => 'to come']),
            Kit::build($stage, 'sentences.build.combinamos-cinema', 'Let\'s meet at the cinema.', 'Combinamos no cinema.', ['combinar'], [Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')], glosses: ['combinar' => 'to arrange', 'combinamos' => 'we arrange']),

            Kit::listenChoose($stage, 'sentences.listen_choose.plano-vir', 'Tens um plano? Queres vir?', ['Do you have a plan? Do you want to come?', 'Do you have a plan? Can you come?', 'Do you have a party? Do you want to come?', 'Do I have a plan? Do you want to come?'], 'Do you have a plan? Do you want to come?', [Kit::word('o plano', 'plano'), Kit::word('queres vir?', 'queres vir')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.desculpa', 'Desculpa, não posso vir.', ['I am sorry, I cannot come.', 'Of course, I can come.', 'I am sorry, we cannot come.', 'I am sorry, you cannot come.'], 'I am sorry, I cannot come.', [Kit::word('desculpa'), Kit::form('posso')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vamos-jantar', 'Vamos jantar no café.', ['We are going to have dinner at the café.', 'I am going to have dinner at the café.', 'We are going to have dinner at the cinema.', 'They are going to have dinner at the café.'], 'We are going to have dinner at the café.', [Kit::word('o jantar', 'jantar'), Kit::word('o café', 'café'), Kit::form('vamos')]),
            Kit::listenType($stage, 'sentences.listen_type.claro-vou', 'Claro, vou vir jantar.', 'Of course, I am going to come for dinner.', [Kit::word('claro'), Kit::word('o jantar', 'jantar'), Kit::form('vou')]),
            Kit::listenType($stage, 'sentences.listen_type.plano-jantar', 'O plano é jantar no café.', 'The plan is to have dinner at the café.', [Kit::word('o plano', 'plano'), Kit::word('o café', 'café'), Kit::word('o jantar', 'jantar')]),
            Kit::listenType($stage, 'sentences.listen_type.festa', 'O Rui tem uma festa. Queres vir?', 'Rui has a party. Do you want to come?', [Kit::word('a festa', 'festa'), Kit::word('queres vir?', 'queres vir')]),
            Kit::listenType($stage, 'sentences.listen_type.podes-jantar', 'Ana, porque é que não podes jantar?', 'Ana, why can you not have dinner?', [Kit::form('podes'), Kit::word('o jantar', 'jantar')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.queres-cinema', 'Queres vir ao cinema?', 'Do you want to come to the cinema?', [Kit::word('queres vir?', 'queres vir'), Kit::word('o cinema', 'cinema')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.porque', 'Porque não podes vir ao café?', 'Why can you not come to the café? (informal you)', [Kit::form('podes'), Kit::word('o café', 'café')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.vamos-convidar', 'Vamos convidar o João.', 'We are going to invite João.', [Kit::word('convidar'), Kit::form('vamos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.onde-combinamos', 'Onde combinamos? No café?', 'Where shall we meet? At the café?', [Kit::word('combinar', 'combinamos'), Kit::word('o café', 'café')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cinema', 'Queres vir ao cinema?', 'Do you want to come to the cinema?', [['claro', 'sim', 'não', 'desculpa'], ['vou', 'posso']], 'Claro, vou vir.', [Kit::word('claro'), Kit::word('o cinema', 'cinema'), Kit::form('vou')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.jantar', 'Podes vir ao jantar?', 'Can you come to dinner? (informal you)', [['sim', 'não', 'claro', 'desculpa'], ['posso', 'vou']], 'Sim, posso vir.', [Kit::word('o jantar'), Kit::form('posso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.onde', 'Onde combinamos?', 'Where shall we meet?', [['combinamos', 'no'], ['cinema', 'café']], 'Combinamos no cinema.', [Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.festa', 'Read the conversation.', [
                Kit::line('Ana', 'Olá, João. Há uma festa no café. Queres vir?'),
                Kit::line('João', 'Desculpa, Ana. Não posso vir.'),
                Kit::line('João', 'Vou jantar com a Marta.'),
                Kit::line('Ana', 'Claro, tens um plano! Adeus!'),
            ], [
                Kit::question('Where is the party?', ['In the café', 'In the cinema', 'In a restaurant'], 'In the café'),
                Kit::question('Can João come?', ['No', 'Yes', 'The text does not say.'], 'No'),
                Kit::question('What is João going to do?', ['Have dinner with Marta', 'Invite Ana', 'Go to the cinema'], 'Have dinner with Marta'),
            ], [Kit::word('a festa', 'festa'), Kit::word('o café', 'café'), Kit::word('queres vir?', 'queres vir'), Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::word('claro')], 'read'),
            Kit::gap($stage, 'task.choose_gap.vai-convidar', 'A Ana ___ convidar o João.', ['vai', 'vais', 'vou'], 'vai', Kit::form('vai', true), 'A Ana is ela, so vai. Vais is for tu and vou is for eu. The infinitive follows with no a.', 'read'),
            Kit::gap($stage, 'task.choose_gap.plano', 'Tenho um ___: combinar no cinema.', ['plano', 'festa', 'noite'], 'plano', Kit::word('o plano', 'plano'), 'Um goes with a masculine word. Festa and noite are feminine, so they need uma.', 'read'),

            Kit::transform($stage, 'task.transform.vamos-jantar', 'Change it to nós.', 'Vou jantar no café.', ['Vamos jantar no café.', 'Nós vamos jantar no café.'], [Kit::word('o jantar', 'jantar'), Kit::form('vamos')]),
            Kit::transform($stage, 'task.transform.ana-pode', 'Say it about Ana.', 'Posso vir à festa.', ['A Ana pode vir à festa.', 'Ana pode vir à festa.'], [Kit::word('a festa', 'festa'), Kit::form('pode')]),
            Kit::transform($stage, 'task.transform.vais-convidar', 'Change it to tu.', 'Vou convidar a Marta.', ['Vais convidar a Marta.', 'Tu vais convidar a Marta.'], [Kit::word('convidar'), Kit::form('vais')]),
            Kit::writeGuided($stage, 'task.write_guided.convidar', 'Say that you are going to invite João to the party.', ['vou', 'convidar', 'festa'], 'Vou convidar o João para a festa.', [
                ['forms' => ['vou'], 'term' => null],
                ['forms' => ['convidar'], 'term' => 'convidar'],
                ['forms' => ['festa'], 'term' => 'a festa'],
            ], [Kit::word('convidar'), Kit::word('a festa', 'festa')]),
            Kit::writeGuided($stage, 'task.write_guided.desculpa', 'Say that you are sorry and that you cannot come to the cinema.', ['desculpa', 'não posso', 'vir', 'cinema'], 'Desculpa, não posso vir ao cinema.', [
                ['forms' => ['desculpa'], 'term' => 'desculpa'],
                ['forms' => ['posso'], 'term' => null],
                ['forms' => ['cinema'], 'term' => 'o cinema'],
            ], [Kit::word('desculpa'), Kit::word('o cinema', 'cinema')], ['vir' => 'to come']),
            Kit::build($stage, 'task.build.vamos-jantar', 'We are going to have dinner at the café.', 'Vamos jantar no café.', ['a', 'vou'], [Kit::word('o jantar', 'jantar'), Kit::word('o café', 'café'), Kit::form('vamos', true)], 'write', ['jantar' => 'to have dinner']),
            Kit::build($stage, 'task.build.podes-convidar', 'Can you invite Marta to the party? (informal you)', 'Podes convidar a Marta para a festa?', ['posso', 'em'], [Kit::word('convidar'), Kit::word('a festa', 'festa'), Kit::form('podes')], 'write', ['convidar' => 'to invite']),
            Kit::build($stage, 'task.build.desculpa-jantar', 'I am sorry, I cannot come to dinner.', 'Desculpa, não posso vir ao jantar.', ['podes', 'claro'], [Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::form('posso')], 'write', ['vir' => 'to come']),
            Kit::translate($stage, 'task.translate.vais-cinema', 'Are you going to come to the cinema? (informal you)', ['Vais vir ao cinema?', 'Tu vais vir ao cinema?'], [Kit::word('o cinema', 'cinema'), Kit::form('vais')], 'write', glosses: ['vir' => 'to come']),
            Kit::translate($stage, 'task.translate.claro-combinar', 'Of course, let\'s meet at the café.', ['Claro, combinamos no café.', 'Claro, nós combinamos no café.'], [Kit::word('claro'), Kit::word('combinar', 'combinamos')], 'write', glosses: ['combinar' => 'to arrange', 'combinamos' => 'we arrange']),

            Kit::listenPassage($stage, 'task.listen_passage.cinema', [
                Kit::line('Marta', 'Olá, Rui. Tens um plano?'),
                Kit::line('Rui', 'Não, porquê?'),
                Kit::line('Marta', 'A Ana e eu vamos ao cinema. Queres vir?'),
                Kit::line('Rui', 'Claro. Onde combinamos?'),
                Kit::line('Marta', 'Combinamos no cinema.'),
                Kit::line('Rui', 'Claro. Adeus.'),
            ], [
                Kit::question('What are Marta and Ana going to do?', ['Go to the cinema', 'Go to a party', 'Have dinner'], 'Go to the cinema'),
                Kit::question('What does Rui answer to the invitation?', ['Of course', 'Sorry, no', 'He does not know'], 'Of course'),
                Kit::question('Where do they meet?', ['At the cinema', 'At the café', 'At the party'], 'At the cinema'),
            ], [
                Kit::question('Does Rui have a plan?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('How does the conversation end?', ['Rui says goodbye', 'Marta says sorry', 'Rui says no'], 'Rui says goodbye'),
            ], [Kit::word('o plano', 'plano'), Kit::word('queres vir?', 'queres vir'), Kit::word('claro'), Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.desculpa-jantar', 'Desculpa, Ana. Não posso vir ao jantar.', 'I am sorry, Ana. I cannot come to dinner.', [Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::form('posso')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vai-convidar', 'A Marta vai convidar o Rui para a festa.', 'Marta is going to invite Rui to the party.', [Kit::word('convidar'), Kit::word('a festa', 'festa'), Kit::form('vai')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.claro-combinamos', 'Claro, combinamos no cinema ou no café?', 'Of course, shall we meet at the cinema or at the café?', [Kit::word('claro'), Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema'), Kit::word('o café', 'café')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.festa', 'Queres vir à festa?', 'Do you want to come to the party?', [['claro', 'sim', 'não', 'desculpa'], ['vou', 'posso']], 'Desculpa, não posso vir.', [Kit::word('queres vir?', 'queres vir'), Kit::word('a festa', 'festa'), Kit::word('desculpa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.convidar', 'Porque é que vais convidar a Ana?', 'Why are you going to invite Ana?', [['há', 'tem', 'tenho', 'é'], ['festa', 'plano', 'jantar', 'cinema', 'café']], 'Há uma festa.', [Kit::word('convidar'), Kit::form('vais')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.plano', 'Tens um plano?', 'Do you have a plan?', [['sim', 'não'], ['tenho', 'vou', 'vamos']], 'Sim, tenho um plano.', [Kit::word('o plano', 'plano')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.combinamos', 'Onde combinamos, no cinema ou no café?', 'Where shall we meet, at the cinema or at the café?', [['combinamos', 'no'], ['cinema', 'café']], 'Combinamos no café.', [Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema'), Kit::word('o café', 'café')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.desculpa-jantar', 'Desculpa, não posso vir ao jantar.', 'I am sorry, I cannot come to dinner.', [Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::form('posso')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.claro-combinar', 'Claro, combinamos no cinema.', 'Of course, let\'s meet at the cinema.', [Kit::word('claro'), Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.queres-cafe', 'Do you want to come to the café with Ana? (informal you)', ['Queres vir ao café com a Ana?', 'Tu queres vir ao café com a Ana?'], [Kit::word('queres vir?', 'queres vir'), Kit::word('o café', 'café')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vamos-jantar', 'We are going to invite Marta to dinner.', ['Vamos convidar a Marta para o jantar.', 'Nós vamos convidar a Marta para o jantar.'], [Kit::word('convidar'), Kit::word('o jantar', 'jantar'), Kit::form('vamos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.desculpa', 'I am sorry, I cannot come to the party.', ['Desculpa, não posso vir à festa.', 'Desculpa, eu não posso vir à festa.'], [Kit::word('desculpa'), Kit::word('a festa', 'festa'), Kit::form('posso')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.podes-cinema', 'Why can you not come to the cinema? (informal you)', ['Porque é que não podes vir ao cinema?', 'Porque não podes vir ao cinema?', 'Porque é que tu não podes vir ao cinema?', 'Porque tu não podes vir ao cinema?'], [Kit::word('o cinema', 'cinema'), Kit::form('podes', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vai-jantar', 'A Ana ___ jantar no café.', 'Ana is going to have dinner at the café.', 'vai', Kit::form('vai', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pode-jantar', 'A Marta não ___ vir ao jantar.', 'Marta cannot come to the dinner.', 'pode', Kit::form('pode', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.claro-vou', 'Claro, vou jantar com o Rui.', 'Of course, I am going to have dinner with Rui.', [Kit::word('claro'), Kit::word('o jantar', 'jantar'), Kit::form('vou')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.plano', 'Tenho um plano: jantar no café.', 'I have a plan: to have dinner at the café.', [Kit::word('o plano', 'plano'), Kit::word('o jantar', 'jantar'), Kit::word('o café', 'café')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.onde', 'Onde combinamos? No cinema?', 'Where shall we meet? At the cinema?', [Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.festa', [
                Kit::line('João', 'Olá, Ana. Há uma festa. Queres vir?'),
                Kit::line('Ana', 'Claro. Onde combinamos?'),
                Kit::line('João', 'Combinamos no café.'),
            ], [
                Kit::question('What does João ask Ana?', ['If she wants to come to the party', 'If she can have dinner', 'If she has a plan'], 'If she wants to come to the party'),
                Kit::question('What does Ana answer?', ['Of course', 'Sorry, no', 'The conversation does not say.'], 'Of course'),
                Kit::question('Where do they meet?', ['At the café', 'At the party', 'At the cinema'], 'At the café'),
            ], [
                Kit::question('Who speaks first?', ['João', 'Ana', 'Nobody'], 'João'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does Ana say yes?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('a festa', 'festa'), Kit::word('queres vir?', 'queres vir'), Kit::word('claro'), Kit::word('combinar', 'combinamos'), Kit::word('o café', 'café')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.plano', 'Read the conversation.', [
                Kit::line('Rui', 'Olá, Marta. Que plano tens?'),
                Kit::line('Marta', 'Vou convidar a Ana para o cinema. Queres vir?'),
                Kit::line('Rui', 'Desculpa, Marta. Não posso. Vou jantar com o João.'),
                Kit::line('Marta', 'Claro, Rui. Adeus.'),
            ], [
                Kit::question('Where is Marta going to invite Ana to?', ['To the cinema', 'To a party', 'To a café'], 'To the cinema'),
                Kit::question('Can Rui come?', ['No', 'Yes', 'The text does not say.'], 'No'),
            ], [Kit::word('o plano', 'plano'), Kit::word('convidar'), Kit::word('o cinema', 'cinema'), Kit::word('queres vir?', 'queres vir'), Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::word('claro')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.festa-ana', 'Queres vir à festa da Ana?', 'Do you want to come to Ana\'s party?', [['claro', 'sim', 'não', 'desculpa'], ['vou', 'posso']], 'Claro, vou vir.', [Kit::word('claro'), Kit::word('a festa', 'festa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.plano-festa', 'Tens um plano para a festa?', 'Do you have a plan for the party?', [['sim', 'não'], ['tenho', 'vou', 'vamos']], 'Sim, tenho um plano.', [Kit::word('o plano', 'plano'), Kit::word('a festa', 'festa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.combinamos-cinema', 'Combinamos no cinema, João?', 'Shall we meet at the cinema, João?', [['sim', 'não', 'claro', 'desculpa'], ['combinamos', 'posso', 'café']], 'Claro, combinamos no cinema.', [Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema'), Kit::word('claro')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vamos-convidar', 'We are going to invite Ana to the party.', ['Vamos convidar a Ana para a festa.', 'Nós vamos convidar a Ana para a festa.'], [Kit::word('convidar'), Kit::word('a festa', 'festa'), Kit::form('vamos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.podes-festa', 'Why can you not come to the party? (informal you)', ['Porque é que não podes vir à festa?', 'Porque não podes vir à festa?', 'Porque é que tu não podes vir à festa?', 'Porque tu não podes vir à festa?'], [Kit::word('a festa', 'festa'), Kit::form('podes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vou-jantar', 'I am sorry, I am going to have dinner with Rui.', ['Desculpa, vou jantar com o Rui.', 'Desculpa, eu vou jantar com o Rui.'], [Kit::word('desculpa'), Kit::word('o jantar', 'jantar'), Kit::form('vou')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.plano-cinema', 'Is the plan to have dinner at the café? Of course.', ['O plano é jantar no café? Claro.'], [Kit::word('o plano', 'plano'), Kit::word('o jantar', 'jantar'), Kit::word('o café', 'café'), Kit::word('claro')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vai-convidar', 'A Marta ___ convidar o João.', 'Marta is going to invite João.', 'vai', Kit::form('vai', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vir', 'Queres ___ com a Ana?', 'Do you want to come with Ana?', 'vir', Kit::word('queres vir?', 'vir'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.plano-jantar', 'O plano é jantar no café. Queres vir?', 'The plan is to have dinner at the café. Do you want to come?', [Kit::word('o plano', 'plano'), Kit::word('o jantar', 'jantar'), Kit::word('o café', 'café'), Kit::word('queres vir?', 'queres vir')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.podemos', 'Podemos convidar o João para o cinema?', 'Can we invite João to the cinema?', [Kit::form('podemos'), Kit::word('convidar'), Kit::word('o cinema', 'cinema')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pode-combinar', 'Desculpa, o Rui não pode vir ao café. Combinamos no cinema?', 'I am sorry, Rui cannot come to the café. Shall we meet at the cinema?', [Kit::word('desculpa'), Kit::form('pode'), Kit::word('o café', 'café'), Kit::word('combinar', 'combinamos'), Kit::word('o cinema', 'cinema')], 'dictation', $set),
        ];
    }
}
