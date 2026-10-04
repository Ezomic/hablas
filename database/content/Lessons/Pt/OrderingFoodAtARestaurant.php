<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
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
            new WordData('queria', cue: 'I would like (polite)', accepted: ['eu queria', 'gostaria', 'eu gostaria'], portunolSlips: ['quisiera'], questions: ['Is "Queria a conta, por favor" a natural polite request in Portugal, and does "Queria algo para comer?" sound natural as a question from the waiter, or would a waiter say something else?']),
            new WordData('o copo', cue: 'glass (a plain drinking glass)', portunolSlips: ['la copa'], note: 'O copo is the plain glass you drink from. It is not Spanish la copa, and a trophy is a taça in Portugal.'),
            new WordData('para comer', cue: 'to eat (as in something to eat)', accepted: ['algo para comer', 'de comer', 'algo de comer', 'alguma coisa para comer'], questions: ['Are "algo para comer", "alguma coisa para comer" and "de comer" all natural answers for "something to eat", and is "Tomo algo para comer" natural for having something to eat, or should another verb or phrase be used?']),
            new WordData('o empregado', cue: 'waiter (or waitress)', accepted: ['a empregada'], portunolSlips: ['el camarero', 'la camarera'], questions: ['Is plain "o empregado" natural for a waiter in Portugal (versus "empregado de mesa"), and is "a empregada" a safe accepted answer for a waitress, or does it risk reading as a maid?']),
            new WordData('delicioso', cue: 'delicious (masculine)', forms: ['deliciosa']),
            new WordData('a gorjeta', cue: 'tip (money for the waiter)', portunolSlips: ['la propina'], questions: ['Are "Pago a gorjeta" and "A gorjeta é para o senhor" natural ways to talk about leaving a tip in Portugal, or would "deixar a gorjeta" be the normal verb?']),
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
        return [];
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
            Kit::typeGap($stage, 'sentences.type_gap.pagamos', '___ a gorjeta.', 'We pay the tip.', 'Pagamos', Kit::form('pagamos'), 'We pay is pagamos: the ending -amos means we.', glosses: ['pagamos' => 'we pay']),
            Kit::typeGap($stage, 'sentences.type_gap.falam', 'Eles ___ com o empregado.', 'They speak with the waiter.', 'falam', Kit::form('falam'), 'Eles goes with -am, said with a nasal sound like the ão of são. Spanish hablan ends in -n, Portuguese falam in -m.', glosses: ['falam' => 'they speak']),
            Kit::typeGap($stage, 'sentences.type_gap.tomo', 'Eu ___ um copo de água.', 'I am having a glass of water.', 'tomo', Kit::form('tomo'), 'Eu goes with the ending -o. Tomar also means to have a drink or a meal.', glosses: ['tomo' => 'I am having (food or drink)', 'água' => 'water']),

            Kit::translate($stage, 'sentences.translate.falamos', 'We speak with the waiter.', ['Falamos com o empregado.', 'Nós falamos com o empregado.'], [Kit::word('o empregado', 'empregado'), Kit::form('falamos')]),
            Kit::translate($stage, 'sentences.translate.paga', 'Ana pays the tip.', ['A Ana paga a gorjeta.', 'Ana paga a gorjeta.'], [Kit::word('a gorjeta', 'gorjeta'), Kit::form('paga')]),
            Kit::translate($stage, 'sentences.translate.ementa', 'The menu is delicious.', ['A ementa é deliciosa.', 'A ementa está deliciosa.'], [Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')]),

            Kit::build($stage, 'sentences.build.empregado-paga', 'The waiter pays the bill.', 'O empregado paga a conta.', ['pago'], [Kit::word('o empregado', 'empregado'), Kit::word('a conta', 'conta'), Kit::form('paga')]),
            Kit::build($stage, 'sentences.build.trabalhamos', 'We work in the restaurant.', 'Trabalhamos no restaurante.', ['trabalham'], [Kit::word('o restaurante', 'restaurante'), Kit::form('trabalhamos')]),
            Kit::build($stage, 'sentences.build.ementa-vegetariana', 'I would like a vegetarian menu.', 'Queria uma ementa vegetariana.', ['vegetariano'], [Kit::word('queria'), Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.empregado-fala', 'O empregado fala com a Ana.', ['The waiter speaks with Ana.', 'The waiter pays Ana.', 'The waiter works with Ana.', 'The waiter eats with Ana.'], 'The waiter speaks with Ana.', [Kit::word('o empregado', 'empregado'), Kit::form('fala')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.falam', 'Eles falam com a Marta.', ['They speak with Marta.', 'He speaks with Marta.', 'We speak with Marta.', 'They pay Marta.'], 'They speak with Marta.', [Kit::form('falam')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pagamos', 'Pagamos a conta.', ['We pay the bill.', 'I pay the bill.', 'They pay the bill.', 'We pay the tip.'], 'We pay the bill.', [Kit::word('a conta', 'conta'), Kit::form('pagamos')]),
            Kit::listenType($stage, 'sentences.listen_type.comer', 'Queria algo para comer.', 'I would like something to eat.', [Kit::word('queria'), Kit::word('para comer')], alsoAccepted: ['Eu queria algo para comer.']),
            Kit::listenType($stage, 'sentences.listen_type.gorjeta', 'A gorjeta é para o empregado.', 'The tip is for the waiter.', [Kit::word('a gorjeta', 'gorjeta'), Kit::word('o empregado', 'empregado'), Kit::form('é', true)], homophoneNote: 'The a at the start is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.ementa', 'A ementa vegetariana é deliciosa.', 'The vegetarian menu is delicious.', [Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana'), Kit::word('delicioso', 'deliciosa'), Kit::form('é', true)], homophoneNote: 'The a at the start is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.trabalho', 'Trabalho no restaurante.', 'I work in the restaurant.', [Kit::word('o restaurante', 'restaurante'), Kit::form('trabalho')], alsoAccepted: ['Eu trabalho no restaurante.']),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.conta', 'Queria a conta, por favor.', 'I would like the bill, please.', [Kit::word('queria'), Kit::word('a conta', 'conta')], alsoAccepted: ['Eu queria a conta, por favor.']),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.gorjeta', 'Pago a conta e a gorjeta.', 'I pay the bill and the tip.', [Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta'), Kit::form('pago')], alsoAccepted: ['Eu pago a conta e a gorjeta.']),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.restaurante', 'O restaurante é vegetariano.', 'The restaurant is vegetarian.', [Kit::word('o restaurante', 'restaurante'), Kit::word('vegetariano')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.copo', 'Queria um copo e algo para comer.', 'I would like a glass and something to eat.', [Kit::word('o copo', 'copo'), Kit::word('para comer')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.empregado', 'Falas com o empregado?', 'Do you speak with the waiter?', [['sim', 'não'], ['falo', 'falamos', 'empregado']], 'Sim, falo com o empregado.', [Kit::word('o empregado', 'empregado'), Kit::form('falo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paga', 'Quem paga a conta?', 'Who pays the bill?', [['eu', 'tu', 'nós', 'ele', 'ela', 'eles', 'elas', 'empregado', 'ana', 'rui', 'marta', 'joão'], ['paga', 'pago', 'pagamos', 'pagam', 'pagas']], 'A Ana paga a conta.', [Kit::word('a conta', 'conta'), Kit::form('paga')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vegetariana', 'Há uma ementa vegetariana?', 'Is there a vegetarian menu?', [['sim', 'não', 'há'], ['ementa', 'menu', 'vegetariana', 'vegetariano', 'há', 'tem']], 'Sim, há uma ementa vegetariana.', [Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')]),
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
                Kit::line('Rui', 'Eu pago. A gorjeta é para o senhor.'),
                Kit::line('Empregado', 'Muito obrigado.'),
            ], [
                Kit::question('What does Rui ask for?', ['The menu', 'The bill', 'Something to eat'], 'The bill'),
                Kit::question('Who pays?', ['Rui', 'The waiter', 'The text does not say.'], 'Rui'),
                Kit::question('Who is the tip for?', ['The waiter', 'Rui', 'The restaurant'], 'The waiter'),
            ], [Kit::word('queria'), Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta')], 'read'),
            Kit::gap($stage, 'task.choose_gap.algo-delicioso', 'Queria algo ___ para comer.', ['delicioso', 'deliciosa'], 'delicioso', Kit::word('delicioso'), 'Algo is masculine, so the adjective ends in -o.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pagam', 'Vocês ___ a conta.', ['pagam', 'paga', 'pagamos'], 'pagam', Kit::form('pagam'), 'Vocês, you in the plural, takes -am, with the nasal sound of são. Vós is not used.', 'read', glosses: ['pagam' => 'you or they pay', 'paga' => 'he or she pays', 'pagamos' => 'we pay']),

            Kit::transform($stage, 'task.transform.pagamos', 'Change it to nós.', 'Eu pago a conta.', ['Pagamos a conta.', 'Nós pagamos a conta.'], [Kit::word('a conta', 'conta'), Kit::form('pagamos')]),
            Kit::transform($stage, 'task.transform.estamos', 'Change it to nós.', 'Estou no restaurante.', ['Estamos no restaurante.', 'Nós estamos no restaurante.'], [Kit::word('o restaurante', 'restaurante'), Kit::form('estamos', true)]),
            Kit::transform($stage, 'task.transform.fala', 'Say it about the waiter.', 'Falo com a Ana.', ['O empregado fala com a Ana.'], [Kit::word('o empregado', 'empregado'), Kit::form('fala')]),
            Kit::writeGuided($stage, 'task.write_guided.trabalho', 'Say that you work in a restaurant and that the menu is delicious.', ['trabalho', 'restaurante', 'ementa', 'deliciosa'], 'Trabalho num restaurante. A ementa é deliciosa.', [
                ['forms' => ['trabalho'], 'term' => null],
                ['forms' => ['restaurante'], 'term' => 'o restaurante'],
                ['forms' => ['ementa', 'menu'], 'term' => 'a ementa'],
                ['forms' => ['delicioso', 'deliciosa'], 'term' => 'delicioso'],
            ], [Kit::word('o restaurante', 'restaurante'), Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')]),
            Kit::writeGuided($stage, 'task.write_guided.copo', 'Say that you would like a glass and that you pay the bill.', ['queria', 'copo', 'pago', 'conta'], 'Queria um copo. Pago a conta.', [
                ['forms' => ['queria', 'gostaria'], 'term' => 'queria'],
                ['forms' => ['copo'], 'term' => 'o copo'],
                ['forms' => ['pago'], 'term' => null],
                ['forms' => ['conta'], 'term' => 'a conta'],
            ], [Kit::word('queria'), Kit::word('o copo', 'copo'), Kit::word('a conta', 'conta')]),
            Kit::build($stage, 'task.build.empregado-trabalha', 'The waiter works in the restaurant.', 'O empregado trabalha no restaurante.', ['trabalho', 'trabalham'], [Kit::word('o empregado', 'empregado'), Kit::word('o restaurante', 'restaurante'), Kit::form('trabalha')], 'write'),
            Kit::build($stage, 'task.build.comer', 'I would like something to eat, please.', 'Queria algo para comer, por favor.', ['o', 'uma'], [Kit::word('queria'), Kit::word('para comer')], 'write'),
            Kit::build($stage, 'task.build.conta-gorjeta', 'We pay the bill and the tip.', 'Pagamos a conta e a gorjeta.', ['pago', 'pagam'], [Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta'), Kit::form('pagamos')], 'write', ['pagam' => 'they pay']),
            Kit::translate($stage, 'task.translate.falas', 'Marta, do you speak with the waiter?', ['Marta, falas com o empregado?', 'Falas com o empregado, Marta?', 'Marta, falas tu com o empregado?', 'Marta, tu falas com o empregado?', 'Tu falas com o empregado, Marta?', 'Falas tu com o empregado, Marta?'], [Kit::word('o empregado', 'empregado'), Kit::form('falas')], 'write'),
            Kit::translate($stage, 'task.translate.ementa', 'I would like the menu, please.', ['Queria a ementa, por favor.', 'Eu queria a ementa, por favor.', 'Queria o menu, por favor.', 'Eu queria o menu, por favor.'], [Kit::word('queria')], 'write'),

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
            Kit::listenType($stage, 'task.listen_type.toma', 'A Ana toma algo para comer.', 'Ana is having something to eat.', [Kit::word('para comer'), Kit::form('toma')], 'listen', alsoAccepted: ['Ana toma algo para comer.'], homophoneNote: 'The a before Ana is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.empregados', 'Os empregados trabalham aqui.', 'The waiters work here.', [Kit::word('o empregado', 'empregados'), Kit::form('trabalham')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paga', 'O Rui paga a conta e a gorjeta.', 'Rui pays the bill and the tip.', [Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta'), Kit::form('paga')], 'listen', alsoAccepted: ['Rui paga a conta e a gorjeta.'], homophoneNote: 'Both a words are the article a (the), not à (to the) and not há (there is).'),

            Kit::speakAnswer($stage, 'task.speak_answer.trabalha', 'Onde trabalha o empregado?', 'Where does the waiter work?', [['empregado', 'trabalha'], ['restaurante', 'aqui']], 'O empregado trabalha no restaurante.', [Kit::word('o empregado', 'empregado'), Kit::word('o restaurante', 'restaurante'), Kit::form('trabalha')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.gorjeta', 'Quem paga a gorjeta?', 'Who pays the tip?', [['eu', 'tu', 'nós', 'ele', 'ela', 'eles', 'elas', 'empregado', 'ana', 'rui', 'marta', 'joão'], ['paga', 'pago', 'pagamos', 'pagam', 'pagas']], 'O Rui paga a gorjeta.', [Kit::word('a gorjeta', 'gorjeta'), Kit::form('paga')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.deliciosa', 'A ementa é deliciosa?', 'Is the menu delicious?', [['sim', 'não', 'é'], ['deliciosa', 'delicioso']], 'Sim, a ementa é deliciosa.', [Kit::word('a ementa', 'ementa'), Kit::word('delicioso', 'deliciosa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.comer', 'Queria algo para comer?', 'Would you like something to eat?', [['sim', 'não', 'queria'], ['comer', 'algo', 'obrigado', 'obrigada']], 'Sim, queria algo para comer.', [Kit::word('queria'), Kit::word('para comer')], 'speak'),
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
            Kit::translate($stage, 'check.a.translate.ementa', 'I would like the vegetarian menu.', ['Queria a ementa vegetariana.', 'Eu queria a ementa vegetariana.'], [Kit::word('queria'), Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.gorjeta', 'Rui, do you pay the tip?', ['Rui, pagas a gorjeta?', 'Pagas a gorjeta, Rui?', 'Rui, pagas tu a gorjeta?', 'Rui, tu pagas a gorjeta?', 'Tu pagas a gorjeta, Rui?', 'Pagas tu a gorjeta, Rui?'], [Kit::word('a gorjeta', 'gorjeta'), Kit::form('pagas')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.conta', 'The bill is here.', ['A conta está aqui.', 'Aqui está a conta.'], [Kit::word('a conta', 'conta'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paga', 'A Marta ___ a conta.', 'Marta pays the bill.', 'paga', Kit::form('paga'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vegetariana', 'A Ana ___ vegetariana.', 'Ana is vegetarian.', 'é', Kit::form('é', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.copo', 'Queria um copo, por favor.', 'I would like a glass, please.', [Kit::word('queria'), Kit::word('o copo', 'copo')], 'dictation', $set, ['Eu queria um copo, por favor.']),
            Kit::listenType($stage, 'check.a.listen_type.toma', 'A Marta toma algo para comer.', 'Marta is having something to eat.', [Kit::word('para comer'), Kit::form('toma')], 'dictation', $set, ['Marta toma algo para comer.'], homophoneNote: 'The a before Marta is the article a (the), not à (to the) and not há (there is).'),
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
            Kit::speakAnswer($stage, 'check.a.speak_answer.comer', 'Há algo para comer?', 'Is there something to eat?', [['sim', 'não', 'há'], ['algo', 'comer', 'há', 'tem']], 'Sim, há algo para comer.', [Kit::word('para comer')], 'speaking', $set),
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
            Kit::translate($stage, 'check.b.translate.queria', 'I would like the bill and the tip.', ['Queria a conta e a gorjeta.', 'Eu queria a conta e a gorjeta.'], [Kit::word('queria'), Kit::word('a conta', 'conta'), Kit::word('a gorjeta', 'gorjeta')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tem', 'The restaurant has a vegetarian menu.', ['O restaurante tem uma ementa vegetariana.'], [Kit::word('o restaurante', 'restaurante'), Kit::word('a ementa', 'ementa'), Kit::word('vegetariano', 'vegetariana'), Kit::form('tem', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta', 'Is Marta vegetarian?', ['A Marta é vegetariana?', 'Marta é vegetariana?', 'É a Marta vegetariana?', 'É vegetariana a Marta?'], [Kit::word('vegetariano', 'vegetariana'), Kit::form('é', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pagamos', 'Nós ___ a conta.', 'We pay the bill.', 'pagamos', Kit::form('pagamos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estamos', 'A Ana e eu ___ no restaurante.', 'Ana and I are in the restaurant.', 'estamos', Kit::form('estamos', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tomo', 'Tomo algo para comer, obrigado.', 'I am having something to eat, thank you.', [Kit::word('para comer'), Kit::form('tomo')], 'dictation', $set, alsoAccepted: ['Tomo algo para comer, obrigada.', 'Eu tomo algo para comer, obrigado.', 'Eu tomo algo para comer, obrigada.']),
            Kit::listenType($stage, 'check.b.listen_type.pago', 'Pago a conta, obrigado.', 'I pay the bill, thank you.', [Kit::word('a conta', 'conta')], 'dictation', $set, alsoAccepted: ['Pago a conta, obrigada.', 'Eu pago a conta, obrigado.', 'Eu pago a conta, obrigada.'], homophoneNote: 'The a before conta is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.delicioso', 'Queria um copo e algo delicioso.', 'I would like a glass and something delicious.', [Kit::word('o copo', 'copo'), Kit::word('delicioso')], 'dictation', $set, ['Eu queria um copo e algo delicioso.']),
        ];
    }
}
