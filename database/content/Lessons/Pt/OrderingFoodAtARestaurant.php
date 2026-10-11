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

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('o restaurante', cue: 'restaurant'),
            new WordData('a ementa', cue: 'menu (the list of dishes)', accepted: ['o menu', 'a carta'], portunolSlips: ['el menú'], questions: ['Is "a ementa" the everyday word for the list of dishes in a restaurant in Portugal, with "o menu" accepted as well, or should "a carta" also be accepted?'], note: 'In Portugal the list of dishes is a ementa, which is feminine. O menu and a carta are also used and are accepted here. Spanish el menú is masculine and has an accent, the Portuguese menu has none.'),
            new WordData('a conta', cue: 'bill (to pay at the end)', portunolSlips: ['la cuenta']),
            new WordData('queria', cue: 'I would like (polite)', accepted: ['eu queria'], portunolSlips: ['quisiera'], questions: ['Is "Queria a conta, por favor" a natural polite request in Portugal?']),
            new WordData('o copo', cue: 'glass (a plain drinking glass)', portunolSlips: ['la copa'], note: 'O copo is the plain glass you drink from. It is not Spanish la copa, and a trophy is a taça in Portugal. The Spanish plain glass is el vaso, which is why copo and vaso match.'),
            new WordData('para comer', cue: 'to eat (as in something to eat)', accepted: ['algo para comer', 'de comer', 'algo de comer', 'alguma coisa para comer'], questions: ['Are "algo para comer", "alguma coisa para comer" and "de comer" all natural answers for "something to eat", and is "Tomo algo para comer" natural for having something to eat, or should another verb or phrase be used? (Review judged tomar algo para comer unnatural, so it is no longer used in exercises.)']),
            new WordData('o empregado', cue: 'waiter (or waitress)', accepted: ['a empregada'], portunolSlips: ['el camarero', 'la camarera'], note: 'Without de mesa, a empregada alone can mean a cleaner or maid, so use empregada de mesa if unclear.', questions: ['Is plain "o empregado" natural for a waiter in Portugal (versus "empregado de mesa"), and is "a empregada" a safe accepted answer for a waitress, or does it risk reading as a maid?']),
            new WordData('delicioso', cue: 'delicious (masculine)', forms: ['deliciosa']),
            new WordData('a gorjeta', cue: 'tip (money for the waiter)', portunolSlips: ['la propina'], questions: ['Deixar or dar gorjeta is the normal verb in Portugal, not pagar a gorjeta. Deixar is not yet taught in this unit, so the exercises only use a gorjeta with é para. Should deixar be added to the vocabulary?']),
            new WordData('vegetariano', cue: 'vegetarian (masculine)', forms: ['vegetariana']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'O empregado trabalha aqui.', 'english' => 'The waiter works here.'],
            ['text' => 'Falamos com o empregado.', 'english' => 'We speak with the waiter.'],
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
            Kit::gap($stage, 'sentences.choose_gap.falo-empregado', 'Eu ___ com o empregado.', ['falo', 'fala', 'falam'], 'falo', Kit::form('falo'), 'The ending shows who speaks: eu goes with -o.', 'choose', glosses: ['falo' => 'I speak', 'fala' => 'he or she speaks', 'falam' => 'they speak']),
            Kit::gap($stage, 'sentences.choose_gap.empregado-fala', 'O empregado ___ com a Ana.', ['fala', 'falas', 'falamos'], 'fala', Kit::form('fala'), 'O empregado is one person, so the verb ends in -a.', 'choose', glosses: ['fala' => 'he or she speaks', 'falas' => 'you speak', 'falamos' => 'we speak']),
            Kit::gap($stage, 'sentences.choose_gap.trabalhamos', 'Nós ___ no restaurante.', ['trabalhamos', 'trabalham', 'trabalho'], 'trabalhamos', Kit::form('trabalhamos'), 'Nós goes with the ending -amos.', 'choose', glosses: ['trabalhamos' => 'we work', 'trabalham' => 'they work', 'trabalho' => 'I work']),
            Kit::gap($stage, 'sentences.choose_gap.ementa-e', 'A ementa ___ deliciosa.', ['é', 'fala', 'toma'], 'é', Kit::form('é', true), 'É says what the menu is like. The verbs fala and toma say what someone does.', 'choose', glosses: ['fala' => 'he or she speaks', 'toma' => 'he or she is having (food or drink)']),
            Kit::gap($stage, 'sentences.choose_gap.ementa-vegetariana', 'Queria uma ementa ___, por favor.', ['vegetariana', 'vegetariano'], 'vegetariana', Kit::word('vegetariano', 'vegetariana'), 'Ementa is feminine, so the adjective ends in -a. Spanish menú is masculine, but ementa is not.', 'choose', 'I would like a vegetarian menu, please.'),
            Kit::gap($stage, 'sentences.choose_gap.copo', 'Queria um ___ de água.', ['copo', 'conta', 'gorjeta'], 'copo', Kit::word('o copo', 'copo'), 'O copo is the plain glass you drink from. Spanish copa is a different word, and a trophy is a taça.', 'choose', 'I would like a glass of water.', ['água' => 'water']),

            Kit::typeGap($stage, 'sentences.type_gap.pago', 'Eu ___ a conta.', 'I pay the bill.', 'pago', Kit::form('pago'), 'Eu goes with the ending -o.', glosses: ['pago' => 'I pay']),
            Kit::typeGap($stage, 'sentences.type_gap.trabalha', 'O empregado ___ aqui.', 'The waiter works here.', 'trabalha', Kit::form('trabalha'), 'O empregado is one person, so the verb ends in -a.', glosses: ['trabalha' => 'he or she works']),
            Kit::typeGap($stage, 'sentences.type_gap.pagamos', '___ a conta, por favor.', 'We pay the bill, please.', 'Pagamos', Kit::form('pagamos'), 'We pay is pagamos: the ending -amos means we.', glosses: ['pagamos' => 'we pay']),
            Kit::typeGap($stage, 'sentences.type_gap.falam', 'Eles ___ com o empregado.', 'They speak with the waiter.', 'falam', Kit::form('falam'), 'Eles goes with -am, said with a nasal sound like the ão of são. Spanish hablan ends in -n, Portuguese falam in -m.', glosses: ['falam' => 'they speak']),
            Kit::typeGap($stage, 'sentences.type_gap.tomo', 'Eu ___ um copo de água.', 'I take a glass of water.', 'tomo', Kit::form('tomo'), 'Eu goes with the ending -o. Tomar also means to have a drink or a meal.', glosses: ['tomo' => 'I take, I am having (food or drink)', 'água' => 'water']),

            Kit::translate($stage, 'sentences.translate.falamos', 'We speak with the waiter.', ['Falamos com o empregado.', 'Nós falamos com o empregado.'], [Kit::word('o empregado', 'empregado'), Kit::form('falamos')]),
            Kit::translate($stage, 'sentences.translate.paga', 'Ana pays the bill.', ['A Ana paga a conta.', 'Ana paga a conta.'], [Kit::word('a conta', 'conta'), Kit::form('paga')]),
            Kit::translate($stage, 'sentences.translate.ementa', 'The menu is delicious.', ['A ementa é deliciosa.', 'A ementa está deliciosa.', 'O menu é delicioso.', 'O menu está delicioso.', 'A carta é deliciosa.', 'A carta está deliciosa.'], [Kit::word('a ementa', 'ementa', ['menu', 'carta']), Kit::word('delicioso', 'deliciosa', ['delicioso'])]),

            Kit::build($stage, 'sentences.build.empregado-paga', 'The waiter pays the bill.', 'O empregado paga a conta.', ['pago'], [Kit::word('o empregado', 'empregado'), Kit::word('a conta', 'conta'), Kit::form('paga')]),
            Kit::build($stage, 'sentences.build.trabalhamos', 'We work in the restaurant.', 'Trabalhamos no restaurante.', ['trabalham'], [Kit::word('o restaurante', 'restaurante'), Kit::form('trabalhamos')]),
            Kit::build($stage, 'sentences.build.ementa-vegetariana', 'I would like a vegetarian menu.', 'Queria uma ementa vegetariana.', ['vegetariano'], [Kit::word('queria'), Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.empregado-fala', 'O empregado fala com a Ana.', ['The waiter speaks with Ana.', 'The waiter pays Ana.', 'The waiter works with Ana.', 'The waiter eats with Ana.'], 'The waiter speaks with Ana.', [Kit::word('o empregado', 'empregado'), Kit::form('fala')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.falam', 'Eles falam com a Marta.', ['They speak with Marta.', 'He speaks with Marta.', 'We speak with Marta.', 'They pay Marta.'], 'They speak with Marta.', [Kit::form('falam')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pagamos', 'Pagamos a conta.', ['We pay the bill.', 'I pay the bill.', 'They pay the bill.', 'We would like the bill.'], 'We pay the bill.', [Kit::word('a conta', 'conta'), Kit::form('pagamos')]),
            Kit::listenType($stage, 'sentences.listen_type.comer', 'Queria algo para comer.', 'I would like something to eat.', [Kit::word('queria'), Kit::word('para comer')], alsoAccepted: ['Eu queria algo para comer.']),
            Kit::listenType($stage, 'sentences.listen_type.gorjeta', 'A gorjeta é para o empregado.', 'The tip is for the waiter.', [Kit::word('a gorjeta', 'gorjeta'), Kit::word('o empregado', 'empregado'), Kit::form('é', true)], homophoneNote: 'Do not confuse the article a (the) at the start with à (to the) or há (there is) in writing.'),
            Kit::listenType($stage, 'sentences.listen_type.ementa', 'A ementa vegetariana é deliciosa.', 'The vegetarian menu is delicious.', [Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana'), Kit::word('delicioso', 'deliciosa'), Kit::form('é', true)], homophoneNote: 'Do not confuse the article a (the) at the start with à (to the) or há (there is) in writing.'),
            Kit::listenType($stage, 'sentences.listen_type.trabalho', 'Trabalho no restaurante.', 'I work in the restaurant.', [Kit::word('o restaurante', 'restaurante'), Kit::form('trabalho')], alsoAccepted: ['Eu trabalho no restaurante.']),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.conta', 'Queria a conta, por favor.', 'I would like the bill, please.', [Kit::word('queria'), Kit::word('a conta', 'conta')], alsoAccepted: ['Eu queria a conta, por favor.']),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.gorjeta', 'A gorjeta é para a empregada.', 'The tip is for the waitress.', [Kit::word('a gorjeta', 'gorjeta'), Kit::word('o empregado', 'empregada'), Kit::form('é', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.restaurante', 'O restaurante é vegetariano.', 'The restaurant is vegetarian.', [Kit::word('o restaurante', 'restaurante'), Kit::word('vegetariano')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.copo', 'Queria um copo e algo para comer.', 'I would like a glass and something to eat.', [Kit::word('o copo', 'copo'), Kit::word('para comer')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.empregado', 'Falas com o empregado?', 'Do you speak with the waiter?', [['sim', 'não'], ['falo', 'falamos', 'empregado']], 'Sim, falo com o empregado.', [Kit::word('o empregado', 'empregado'), Kit::form('falo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paga', 'Quem paga a conta?', 'Who pays the bill?', [['eu', 'tu', 'nós', 'ele', 'ela', 'eles', 'elas', 'empregado', 'ana', 'rui', 'marta', 'joão'], ['paga', 'pago', 'pagamos', 'pagam']], 'A Ana paga a conta.', [Kit::word('a conta', 'conta'), Kit::form('paga')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vegetariana', 'Há uma ementa vegetariana?', 'Is there a vegetarian menu?', [['sim', 'não', 'há'], ['ementa', 'menu', 'carta', 'vegetariana', 'vegetariano', 'há', 'tem']], 'Sim, há uma ementa vegetariana.', [Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.conta', 'Read the conversation at the end of the meal.', [
                Kit::line('Rui', 'Boa tarde. Queria a conta, por favor.'),
                Kit::line('Empregado', 'Aqui tem a conta.'),
                Kit::line('Rui', 'Eu pago. A gorjeta é para si.'),
                Kit::line('Empregado', 'Muito obrigado.'),
            ], [
                Kit::question('What does Rui ask for?', ['The menu', 'The bill', 'Something to eat'], 'The bill'),
                Kit::question('Who pays?', ['Rui', 'The waiter', 'The text does not say.'], 'Rui'),
                Kit::question('Who is the tip for?', ['The waiter', 'Rui', 'The restaurant'], 'The waiter'),
            ], [Kit::word('queria'), Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta')], 'read', glosses: ['si' => 'you (polite)']),
            Kit::gap($stage, 'task.choose_gap.algo-delicioso', 'Queria algo ___ para comer.', ['delicioso', 'deliciosa'], 'delicioso', Kit::word('delicioso'), 'Algo is masculine, so the adjective ends in -o.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pagam', 'Vocês ___ a conta.', ['pagam', 'paga', 'pagamos'], 'pagam', Kit::form('pagam'), 'Vocês, you in the plural, takes -am, with the nasal sound of são. Vós is not used.', 'read', glosses: ['pagam' => 'you or they pay', 'paga' => 'he or she pays', 'pagamos' => 'we pay']),

            Kit::transform($stage, 'task.transform.pagamos', 'Change it to nós.', 'Eu pago a conta.', ['Pagamos a conta.', 'Nós pagamos a conta.'], [Kit::word('a conta', 'conta'), Kit::form('pagamos')]),
            Kit::transform($stage, 'task.transform.estamos', 'Change it to nós.', 'Tomo um copo.', ['Tomamos um copo.', 'Nós tomamos um copo.'], [Kit::word('o copo', 'copo'), Kit::form('tomamos')]),
            Kit::transform($stage, 'task.transform.fala', 'Say it about the waiter.', 'Falo com a Ana.', ['O empregado fala com a Ana.', 'O empregado fala com Ana.', 'A empregada fala com a Ana.', 'A empregada fala com Ana.'], [Kit::word('o empregado', 'empregado', ['empregada']), Kit::form('fala')]),
            Kit::writeGuided($stage, 'task.write_guided.trabalho', 'Say that you work in a restaurant and that the menu is delicious.', ['trabalho', 'restaurante', 'ementa', 'deliciosa'], 'Trabalho num restaurante. A ementa é deliciosa.', [
                ['forms' => ['trabalho'], 'term' => null],
                ['forms' => ['restaurante'], 'term' => 'o restaurante'],
                ['forms' => ['ementa', 'menu', 'carta'], 'term' => 'a ementa'],
                ['forms' => ['delicioso', 'deliciosa'], 'term' => 'delicioso'],
            ], [Kit::word('o restaurante', 'restaurante'), Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')]),
            Kit::writeGuided($stage, 'task.write_guided.copo', 'Say that you would like a glass and that you pay the bill.', ['queria', 'copo', 'pago', 'conta'], 'Queria um copo. Pago a conta.', [
                ['forms' => ['queria'], 'term' => 'queria'],
                ['forms' => ['copo'], 'term' => 'o copo'],
                ['forms' => ['pago'], 'term' => null],
                ['forms' => ['conta'], 'term' => 'a conta'],
            ], [Kit::word('queria'), Kit::word('o copo', 'copo'), Kit::word('a conta', 'conta')]),
            Kit::build($stage, 'task.build.empregado-trabalha', 'Ana and Rui work in the restaurant.', 'A Ana e o Rui trabalham no restaurante.', ['trabalho', 'trabalha'], [Kit::word('o restaurante', 'restaurante'), Kit::form('trabalham')], 'write'),
            Kit::build($stage, 'task.build.comer', 'I would like something to eat, please.', 'Queria algo para comer, por favor.', ['o', 'uma'], [Kit::word('queria'), Kit::word('para comer')], 'write'),
            Kit::build($stage, 'task.build.conta-gorjeta', 'The tip is for Ana.', 'A gorjeta é para a Ana.', ['pago', 'pagam'], [Kit::word('a gorjeta', 'gorjeta'), Kit::form('é', true)], 'write', ['pagam' => 'they pay']),
            Kit::translate($stage, 'task.translate.falas', 'Marta, do you speak with the waiter?', ['Marta, falas com o empregado?', 'Falas com o empregado, Marta?', 'Marta, falas tu com o empregado?', 'Marta, tu falas com o empregado?', 'Tu falas com o empregado, Marta?', 'Falas tu com o empregado, Marta?'], [Kit::word('o empregado', 'empregado'), Kit::form('falas')], 'write'),
            Kit::translate($stage, 'task.translate.ementa', 'I would like the menu, please.', ['Queria a ementa, por favor.', 'Eu queria a ementa, por favor.', 'Queria o menu, por favor.', 'Eu queria o menu, por favor.', 'Queria a carta, por favor.', 'Eu queria a carta, por favor.'], [Kit::word('queria')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.vegetariana', [
                Kit::line('Ana', 'Boa tarde. Queria uma ementa vegetariana.'),
                Kit::line('Empregado', 'Sim, há uma ementa vegetariana. É deliciosa.'),
                Kit::line('Ana', 'E queria um copo, por favor.'),
                Kit::line('Empregado', 'Muito bem.'),
                Kit::line('Ana', 'Obrigada.'),
            ], [
                Kit::question('What kind of menu does Ana want?', ['A vegetarian one', 'A big one', 'A cheap one'], 'A vegetarian one'),
                Kit::question('What does the waiter say about the menu?', ['It is delicious.', 'It is expensive.', 'It is not ready.'], 'It is delicious.'),
                Kit::question('What else does Ana want?', ['A glass', 'The bill', 'Something to eat'], 'A glass'),
            ], [
                Kit::question('Is there a vegetarian menu?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Ana say at the end?', ['Thank you', 'Goodbye', 'The bill, please'], 'Thank you'),
            ], [Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana'), Kit::word('delicioso', 'deliciosa'), Kit::word('queria'), Kit::word('o copo', 'copo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.toma', 'A Ana toma um copo.', 'Ana is having a glass.', [Kit::word('o copo', 'copo'), Kit::form('toma')], 'listen', alsoAccepted: ['Ana toma um copo.'], homophoneNote: 'Do not confuse the article a (the) before Ana with à (to the) or há (there is) in writing.'),
            Kit::listenType($stage, 'task.listen_type.empregados', 'Os empregados trabalham aqui.', 'The waiters work here.', [Kit::word('o empregado', 'empregados'), Kit::form('trabalham')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paga', 'O Rui paga a conta e toma um copo.', 'Rui pays the bill and has a glass.', [Kit::word('a conta', 'conta'), Kit::word('o copo', 'copo'), Kit::form('paga')], 'listen', alsoAccepted: ['Rui paga a conta e toma um copo.'], homophoneNote: 'Do not confuse the article a (the), used twice here, with à (to the) or há (there is) in writing.'),

            Kit::speakAnswer($stage, 'task.speak_answer.trabalha', 'Onde trabalha o empregado?', 'Where does the waiter work?', [['empregado', 'trabalha'], ['restaurante', 'aqui']], 'O empregado trabalha no restaurante.', [Kit::word('o empregado', 'empregado'), Kit::word('o restaurante', 'restaurante'), Kit::form('trabalha')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.gorjeta', 'Para quem é a gorjeta?', 'Who is the tip for?', [['gorjeta', 'é', 'para'], ['empregado', 'empregada', 'ana', 'rui', 'marta', 'joão']], 'A gorjeta é para o empregado.', [Kit::word('a gorjeta', 'gorjeta'), Kit::form('é', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.deliciosa', 'A ementa é deliciosa?', 'Is the menu delicious?', [['sim', 'não', 'é'], ['deliciosa', 'delicioso']], 'Sim, a ementa é deliciosa.', [Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.comer', 'Queria algo para comer?', 'Would you like something to eat?', [['sim', 'não', 'queria'], ['comer', 'algo', 'coisa', 'obrigado', 'obrigada']], 'Sim, queria algo para comer.', [Kit::word('queria'), Kit::word('para comer')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ementa', 'Boa tarde, queria uma ementa vegetariana.', 'Good afternoon, I would like a vegetarian menu.', [Kit::word('queria'), Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')], 'speak', ['Boa tarde, eu queria uma ementa vegetariana.']),
            Kit::speakRepeat($stage, 'task.speak_repeat.gorjeta', 'A conta e a gorjeta, por favor.', 'The bill and the tip, please.', [Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.empregado', 'The waiter works in a restaurant.', ['O empregado trabalha num restaurante.', 'O empregado trabalha em um restaurante.'], [Kit::word('o empregado', 'empregado'), Kit::word('o restaurante', 'restaurante'), Kit::form('trabalha')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ementa', 'I would like the vegetarian menu.', ['Queria a ementa vegetariana.', 'Eu queria a ementa vegetariana.', 'Queria o menu vegetariano.', 'Eu queria o menu vegetariano.', 'Queria a carta vegetariana.', 'Eu queria a carta vegetariana.'], [Kit::word('queria'), Kit::word('a ementa', 'ementa', ['menu', 'carta']), Kit::word('vegetariano', 'vegetariana', ['vegetariano'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.gorjeta', 'Is the tip for Rui?', ['A gorjeta é para o Rui?', 'A gorjeta é para Rui?', 'É para o Rui a gorjeta?', 'É a gorjeta para o Rui?'], [Kit::word('a gorjeta', 'gorjeta'), Kit::form('é', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.conta', 'The bill is here.', ['A conta está aqui.', 'Aqui está a conta.'], [Kit::word('a conta', 'conta'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paga', 'A Marta ___ a conta.', 'Marta pays the bill.', 'paga', Kit::form('paga'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vegetariana', 'A Ana ___ vegetariana.', 'Ana is vegetarian.', 'é', Kit::form('é', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.copo', 'Queria um copo, por favor.', 'I would like a glass, please.', [Kit::word('queria'), Kit::word('o copo', 'copo')], 'dictation', $set, ['Eu queria um copo, por favor.']),
            Kit::listenType($stage, 'check.a.listen_type.toma', 'A Marta toma um copo e há algo para comer.', 'Marta is having a glass and there is something to eat.', [Kit::word('o copo', 'copo'), Kit::word('para comer'), Kit::form('toma')], 'dictation', $set, ['Marta toma um copo e há algo para comer.'], homophoneNote: 'Do not confuse the article a (the) before Marta with à (to the) or há (there is) in writing.'),
            Kit::listenType($stage, 'check.a.listen_type.restaurante', 'O restaurante tem uma ementa deliciosa.', 'The restaurant has a delicious menu.', [Kit::word('o restaurante', 'restaurante'), Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.ementa', [
                Kit::line('Empregado', 'Boa tarde. Aqui tem a ementa.'),
                Kit::line('João', 'Para comer, queria algo vegetariano.'),
                Kit::line('Empregado', 'Muito bem. E um copo?'),
            ], [
                Kit::question('What does the waiter give João?', ['The menu', 'The bill', 'The key'], 'The menu'),
                Kit::question('What does João want?', ['Something to drink', 'Something to eat', 'The bill'], 'Something to eat'),
                Kit::question('What does the waiter ask about at the end?', ['A glass', 'A vegetarian menu', 'The tip'], 'A glass'),
            ], [
                Kit::question('Who speaks first?', ['The waiter', 'João', 'Nobody'], 'The waiter'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does João ask for something vegetarian?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('a ementa', 'ementa'), Kit::word('queria'), Kit::word('para comer'), Kit::word('vegetariano'), Kit::word('o copo', 'copo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.restaurante', 'Read the conversation.', [
                Kit::line('Marta', 'Olá, Rui. Trabalho num restaurante vegetariano.'),
                Kit::line('Rui', 'Muito bem. Tem uma ementa vegetariana?'),
                Kit::line('Marta', 'Sim, tem. É deliciosa.'),
            ], [
                Kit::question('Where does Marta work?', ['In a vegetarian restaurant', 'In a hotel', 'In a shop'], 'In a vegetarian restaurant'),
                Kit::question('What does Rui ask about?', ['A vegetarian menu', 'The bill', 'The tip'], 'A vegetarian menu'),
            ], [Kit::word('o restaurante', 'restaurante'), Kit::word('vegetariano'), Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.comer', 'Há algo para comer?', 'Is there something to eat?', [['sim', 'não', 'há'], ['algo', 'coisa', 'comer', 'há', 'tem']], 'Sim, há algo para comer.', [Kit::word('para comer')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.restaurante', 'Há um restaurante vegetariano aqui?', 'Is there a vegetarian restaurant here?', [['sim', 'não', 'há'], ['restaurante', 'vegetariano', 'há', 'tem']], 'Sim, há um restaurante vegetariano aqui.', [Kit::word('o restaurante', 'restaurante'), Kit::word('vegetariano')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.conta', 'Onde está a conta?', 'Where is the bill?', [['conta', 'está'], ['aqui', 'ali']], 'A conta está aqui.', [Kit::word('a conta', 'conta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.fala', 'The waiter speaks with Marta.', ['O empregado fala com a Marta.', 'O empregado fala com Marta.'], [Kit::word('o empregado', 'empregado'), Kit::form('fala')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.queria', 'I would like the bill and a glass.', ['Queria a conta e um copo.', 'Eu queria a conta e um copo.'], [Kit::word('queria'), Kit::word('a conta', 'conta'), Kit::word('o copo', 'copo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tem', 'The restaurant has a vegetarian menu.', ['O restaurante tem uma ementa vegetariana.', 'O restaurante tem um menu vegetariano.', 'O restaurante tem uma carta vegetariana.'], [Kit::word('o restaurante', 'restaurante'), Kit::word('a ementa', 'ementa', ['menu', 'carta']), Kit::word('vegetariano', 'vegetariana', ['vegetariano']), Kit::form('tem', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta', 'Is Marta vegetarian?', ['A Marta é vegetariana?', 'Marta é vegetariana?', 'É a Marta vegetariana?', 'É vegetariana a Marta?'], [Kit::word('vegetariano', 'vegetariana'), Kit::form('é', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pagamos', 'Nós ___ a conta aqui.', 'We pay the bill here.', 'pagamos', Kit::form('pagamos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estamos', 'A Ana e eu ___ com o empregado.', 'Ana and I speak with the waiter.', 'falamos', Kit::form('falamos', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tomo', 'Tomo um copo, obrigado. Queria algo para comer.', 'I am having a glass, thank you. I would like something to eat.', [Kit::word('o copo', 'copo'), Kit::word('para comer'), Kit::form('tomo')], 'dictation', $set, alsoAccepted: ['Tomo um copo, obrigada. Queria algo para comer.', 'Eu tomo um copo, obrigado. Queria algo para comer.', 'Eu tomo um copo, obrigada. Queria algo para comer.']),
            Kit::listenType($stage, 'check.b.listen_type.pago', 'A gorjeta é para o Rui, obrigado.', 'The tip is for Rui, thank you.', [Kit::word('a gorjeta', 'gorjeta')], 'dictation', $set, alsoAccepted: ['A gorjeta é para o Rui, obrigada.', 'A gorjeta é para Rui, obrigado.', 'A gorjeta é para Rui, obrigada.'], homophoneNote: 'Do not confuse the article a (the) at the start with à (to the) or há (there is) in writing.'),
            Kit::listenType($stage, 'check.b.listen_type.delicioso', 'Queria um copo e algo delicioso.', 'I would like a glass and something delicious.', [Kit::word('o copo', 'copo'), Kit::word('delicioso')], 'dictation', $set, ['Eu queria um copo e algo delicioso.']),
        ];
    }
}
