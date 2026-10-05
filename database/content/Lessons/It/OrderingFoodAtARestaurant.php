<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class OrderingFoodAtARestaurant implements UnitContent
{
    private const E_NOTE = 'È (is) has an accent and e (and) does not. They sound close, so the sentence tells you which one it is.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('il ristorante', cue: 'restaurant'),
            new WordData('il menù', cue: 'menu, the list of dishes', accepted: ['il menu']),
            new WordData('il conto', cue: 'the bill (in a restaurant)'),
            new WordData('vorrei', cue: 'I would like (polite request)'),
            new WordData('da bere', cue: 'something to drink (as in "qualcosa da bere")', accepted: ['qualcosa da bere']),
            new WordData('da mangiare', cue: 'something to eat (as in "qualcosa da mangiare")', accepted: ['qualcosa da mangiare']),
            new WordData('il cameriere', cue: 'waiter (a man)'),
            new WordData('delizioso', cue: 'delicious (masculine)', accepted: ['squisito'], forms: ['deliziosa', 'deliziosi', 'deliziose', 'squisita', 'squisiti', 'squisite']),
            new WordData('la mancia', cue: 'tip (money left for the waiter)'),
            new WordData('vegetariano', cue: 'vegetarian (masculine)', forms: ['vegetariana', 'vegetariani', 'vegetariane']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Il cameriere parla con Anna.', 'english' => 'The waiter speaks with Anna.'],
            ['text' => 'Paghiamo il conto.', 'english' => 'We pay the bill.'],
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
            Kit::gap($stage, 'sentences.choose_gap.pago-conto', 'Io ___ il conto.', ['pago', 'paga', 'paghi'], 'pago', Kit::form('pago', true), 'The ending shows who pays: io goes with -o.', 'choose', glosses: ['pago' => 'I pay', 'paga' => 'he or she pays', 'paghi' => 'you pay']),
            Kit::gap($stage, 'sentences.choose_gap.cameriere-parla', 'Il cameriere ___ con Anna.', ['parla', 'parli', 'parliamo'], 'parla', Kit::form('parla'), 'Il cameriere is one person, so the verb ends in -a.', 'choose', glosses: ['parla' => 'he or she speaks', 'parli' => 'you speak', 'parliamo' => 'we speak']),
            Kit::gap($stage, 'sentences.choose_gap.menu-delizioso', 'Il menù è ___.', ['delizioso', 'deliziosa', 'deliziosi'], 'delizioso', Kit::word('delizioso'), 'Il menù is masculine and singular, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.lavoriamo', 'Noi ___ in un ristorante.', ['lavoriamo', 'lavorano', 'lavoro'], 'lavoriamo', Kit::form('lavoriamo', true), 'Noi goes with the ending -iamo.', 'choose', glosses: ['lavoriamo' => 'we work', 'lavorano' => 'they work', 'lavoro' => 'I work']),
            Kit::gap($stage, 'sentences.choose_gap.menu-vegetariano', 'Vorrei un menù ___.', ['vegetariano', 'vegetariana'], 'vegetariano', Kit::word('vegetariano'), 'Il menù is masculine, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.vorrei', '___ il conto, per favore.', ['Vorrei', 'Parlo', 'Lavoro'], 'Vorrei', Kit::word('vorrei'), 'Vorrei is the polite way to ask for something. Verbs like parlo and lavoro do not ask for things.', 'choose', glosses: ['parlo' => 'I speak', 'lavoro' => 'I work']),

            Kit::typeGap($stage, 'sentences.type_gap.parla', 'Anna ___ con il cameriere.', 'Anna speaks with the waiter.', 'parla', Kit::form('parla'), 'Anna is one person, so the verb ends in -a.'),
            Kit::typeGap($stage, 'sentences.type_gap.paghiamo', '___ il conto.', 'We pay the bill.', 'Paghiamo', Kit::form('paghiamo', true), 'We pay is paghiamo: the ending -iamo means we, and pagare adds an h before i to keep the hard g (paghi, paghiamo).', glosses: ['paghiamo' => 'we pay']),
            Kit::typeGap($stage, 'sentences.type_gap.lavoro', 'Io ___ in un ristorante.', 'I work in a restaurant.', 'lavoro', Kit::form('lavoro'), 'Io goes with the ending -o.', glosses: ['lavoro' => 'I work']),
            Kit::typeGap($stage, 'sentences.type_gap.paga-mancia', 'Paolo ___ la mancia.', 'Paolo pays the tip.', 'paga', Kit::form('paga', true), 'Paolo is one person, so the verb ends in -a, not in -o.'),
            Kit::typeGap($stage, 'sentences.type_gap.ordino', 'Io ___ qualcosa da bere.', 'I order something to drink.', 'ordino', Kit::form('ordino'), 'Io goes with the ending -o.', glosses: ['ordino' => 'I order']),

            Kit::translate($stage, 'sentences.translate.parliamo', 'We speak with the waiter.', ['Parliamo con il cameriere.', 'Noi parliamo con il cameriere.'], [Kit::word('il cameriere', 'cameriere'), Kit::form('parliamo')]),
            Kit::translate($stage, 'sentences.translate.pago', 'I pay the bill.', ['Pago il conto.', 'Io pago il conto.'], [Kit::word('il conto', 'conto'), Kit::form('pago')]),
            Kit::translate($stage, 'sentences.translate.menu', 'The menu is delicious.', ['Il menù è delizioso.', 'Il menu è delizioso.', 'Il menù è squisito.', 'Il menu è squisito.'], [Kit::word('il menù', 'menù', ['menu']), Kit::word('delizioso', null, ['squisito'])]),

            Kit::build($stage, 'sentences.build.paga-mancia', 'Anna pays the tip.', 'Anna paga la mancia.', ['pago'], [Kit::word('la mancia', 'mancia'), Kit::form('paga')]),
            Kit::build($stage, 'sentences.build.lavoriamo', 'We work in a restaurant.', 'Lavoriamo in un ristorante.', ['lavorano'], [Kit::word('il ristorante', 'ristorante'), Kit::form('lavoriamo')]),
            Kit::build($stage, 'sentences.build.menu-vegetariano', 'I would like a vegetarian menu.', 'Vorrei un menù vegetariano.', ['vegetariana'], [Kit::word('vorrei'), Kit::word('il menù', 'menù'), Kit::word('vegetariano')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.cameriere-parla', 'Il cameriere parla con Anna.', ['The waiter speaks with Anna.', 'The waiter pays Anna.', 'The waiter works with Anna.', 'The waiter speaks with Paolo.'], 'The waiter speaks with Anna.', [Kit::word('il cameriere', 'cameriere'), Kit::form('parla')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.paghiamo', 'Paghiamo il conto.', ['We pay the bill.', 'I pay the bill.', 'They pay the bill.', 'We pay the tip.'], 'We pay the bill.', [Kit::word('il conto', 'conto'), Kit::form('paghiamo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.da-bere', 'Vorrei qualcosa da bere.', ['I would like something to eat.', 'I would like something to drink.', 'I would like the menu.', 'I would like the bill.'], 'I would like something to drink.', [Kit::word('vorrei'), Kit::word('da bere')]),
            Kit::listenType($stage, 'sentences.listen_type.mancia', 'La mancia è per il cameriere.', 'The tip is for the waiter.', [Kit::word('la mancia', 'mancia'), Kit::word('il cameriere', 'cameriere')], homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.da-mangiare', 'Vorrei qualcosa da mangiare.', 'I would like something to eat.', [Kit::word('vorrei'), Kit::word('da mangiare')]),
            Kit::listenType($stage, 'sentences.listen_type.menu', 'Il menù vegetariano è delizioso.', 'The vegetarian menu is delicious.', [Kit::word('il menù', 'menù'), Kit::word('vegetariano'), Kit::word('delizioso')], homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.lavoro', 'Lavoro in un ristorante.', 'I work in a restaurant.', [Kit::word('il ristorante', 'ristorante'), Kit::form('lavoro')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.conto', 'Vorrei il conto, per favore.', 'I would like the bill, please.', [Kit::word('vorrei'), Kit::word('il conto', 'conto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mancia', 'Pago il conto e la mancia.', 'I pay the bill and the tip.', [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::form('pago')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ristorante', 'Il ristorante è vegetariano.', 'The restaurant is vegetarian.', [Kit::word('il ristorante', 'ristorante'), Kit::word('vegetariano')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mangiare-bere', 'Qualcosa da mangiare e qualcosa da bere.', 'Something to eat and something to drink.', [Kit::word('da mangiare'), Kit::word('da bere')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cameriere', 'Parli con il cameriere?', 'Do you speak with the waiter?', [['sì', 'no', 'parlo', 'parliamo', 'cameriere']], 'Sì, parlo con il cameriere.', [Kit::word('il cameriere', 'cameriere'), Kit::form('parlo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paga', 'Chi paga il conto?', 'Who pays the bill?', [['io', 'tu', 'noi', 'lui', 'lei', 'voi', 'loro', 'cameriere', 'anna', 'paolo', 'marta', 'luca'], ['paga', 'pago', 'paghiamo', 'pagano', 'paghi']], 'Anna paga il conto.', [Kit::word('il conto', 'conto'), Kit::form('paga')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vegetariano', "C'è un menù vegetariano?", 'Is there a vegetarian menu?', [['sì', 'no', "c'è"]], "Sì, c'è un menù vegetariano.", [Kit::word('il menù', 'menù', ['menu']), Kit::word('vegetariano')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.conto', 'Read the conversation at the end of the meal.', [
                Kit::line('Paolo', 'Cameriere, il conto, per favore.'),
                Kit::line('Cameriere', 'Ecco il conto.'),
                Kit::line('Paolo', 'Pago io. Ecco la mancia.'),
                Kit::line('Cameriere', 'Grazie, arrivederci.'),
            ], [
                Kit::question('What does Paolo ask for?', ['The menu', 'The bill', 'Something to drink'], 'The bill'),
                Kit::question('Who pays?', ['Paolo', 'The waiter', 'The text does not say.'], 'Paolo'),
                Kit::question('What does the waiter say at the end?', ['Thank you, goodbye', 'Good evening', 'The bill, please'], 'Thank you, goodbye'),
            ], [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::word('il cameriere', 'cameriere')], 'read'),
            Kit::gap($stage, 'task.choose_gap.da-bere', 'Vorrei qualcosa ___ bere.', ['da', 'di', 'con'], 'da', Kit::word('da bere', 'da'), 'Something meant to be drunk is qualcosa da bere: da links the thing to what you do with it.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pagano', 'Loro ___ la mancia.', ['pagano', 'paga', 'pagate'], 'pagano', Kit::form('pagano', true), 'Loro (they) goes with the ending -ano.', 'read', glosses: ['pagano' => 'they pay', 'paga' => 'he or she pays', 'pagate' => 'you all pay']),

            Kit::transform($stage, 'task.transform.paghiamo', 'Change it to noi.', 'Io pago il conto.', ['Paghiamo il conto.', 'Noi paghiamo il conto.'], [Kit::word('il conto', 'conto'), Kit::form('paghiamo')]),
            Kit::transform($stage, 'task.transform.lavoriamo', 'Change it to noi.', 'Lavoro in un ristorante.', ['Lavoriamo in un ristorante.', 'Noi lavoriamo in un ristorante.'], [Kit::word('il ristorante', 'ristorante'), Kit::form('lavoriamo')]),
            Kit::transform($stage, 'task.transform.parla', 'Say it about Anna.', 'Parlo con il cameriere.', ['Anna parla con il cameriere.'], [Kit::word('il cameriere', 'cameriere'), Kit::form('parla')]),
            Kit::writeGuided($stage, 'task.write_guided.lavoro', 'Say that you work in a restaurant and that the menu is delicious.', ['lavoro', 'ristorante', 'menù', 'delizioso'], 'Lavoro in un ristorante. Il menù è delizioso.', [
                ['forms' => ['lavoro'], 'term' => null],
                ['forms' => ['ristorante'], 'term' => 'il ristorante'],
                ['forms' => ['menù', 'menu'], 'term' => 'il menù'],
                ['forms' => ['delizioso', 'deliziosa', 'deliziosi', 'squisito', 'squisita', 'squisiti'], 'term' => 'delizioso'],
            ], [Kit::word('il ristorante', 'ristorante'), Kit::word('il menù', 'menù'), Kit::word('delizioso')]),
            Kit::writeGuided($stage, 'task.write_guided.bere', 'Say that you would like something to drink and that you pay the bill.', ['vorrei', 'da bere', 'pago', 'conto'], 'Vorrei qualcosa da bere. Pago il conto.', [
                ['forms' => ['vorrei'], 'term' => 'vorrei'],
                ['forms' => ['bere'], 'term' => 'da bere'],
                ['forms' => ['pago'], 'term' => null],
                ['forms' => ['conto'], 'term' => 'il conto'],
            ], [Kit::word('vorrei'), Kit::word('da bere'), Kit::word('il conto', 'conto')]),
            Kit::build($stage, 'task.build.cameriere-lavora', 'The waiter works in a restaurant.', 'Il cameriere lavora in un ristorante.', ['lavoro', 'lavorano'], [Kit::word('il cameriere', 'cameriere'), Kit::word('il ristorante', 'ristorante'), Kit::form('lavora')], 'write'),
            Kit::build($stage, 'task.build.mangiare', 'I would like something to eat, please.', 'Vorrei qualcosa da mangiare, per favore.', ['il', 'una'], [Kit::word('vorrei'), Kit::word('da mangiare')], 'write'),
            Kit::build($stage, 'task.build.conto-mancia', 'We pay the bill and the tip.', 'Paghiamo il conto e la mancia.', ['pago', 'pagano'], [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::form('paghiamo')], 'write'),
            Kit::translate($stage, 'task.translate.parli', 'Paolo, do you speak with the waiter?', ['Paolo, parli con il cameriere?', 'Parli con il cameriere, Paolo?', 'Paolo, tu parli con il cameriere?', 'Tu parli con il cameriere, Paolo?', 'Paolo, parli tu con il cameriere?'], [Kit::word('il cameriere', 'cameriere'), Kit::form('parli')], 'write'),
            Kit::translate($stage, 'task.translate.menu', 'I would like the menu, please.', ['Vorrei il menù, per favore.', 'Vorrei il menu, per favore.', 'Per favore, vorrei il menù.', 'Per favore, vorrei il menu.'], [Kit::word('vorrei'), Kit::word('il menù', 'menù', ['menu'])], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.vegetariano', [
                Kit::line('Anna', 'Vorrei un menù vegetariano.'),
                Kit::line('Cameriere', "Sì, c'è un menù vegetariano. È delizioso."),
                Kit::line('Anna', 'E vorrei qualcosa da bere.'),
                Kit::line('Cameriere', 'Va bene.'),
                Kit::line('Anna', 'Grazie.'),
            ], [
                Kit::question('What kind of menu does Anna want?', ['A vegetarian one', 'A big one', 'A cheap one'], 'A vegetarian one'),
                Kit::question('What does the waiter say about the menu?', ['It is delicious.', 'It is expensive.', 'It is not ready.'], 'It is delicious.'),
                Kit::question('What else does Anna want?', ['Something to eat', 'Something to drink', 'The bill'], 'Something to drink'),
            ], [
                Kit::question('Is there a vegetarian menu?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Anna say at the end?', ['Thank you', 'Goodbye', 'The bill, please'], 'Thank you'),
            ], [Kit::word('il menù', 'menù'), Kit::word('vegetariano'), Kit::word('delizioso'), Kit::word('vorrei'), Kit::word('da bere')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ordina', 'Anna ordina qualcosa da bere.', 'Anna orders something to drink.', [Kit::word('da bere'), Kit::form('ordina')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.camerieri', 'I camerieri lavorano qui.', 'The waiters work here.', [Kit::word('il cameriere', 'camerieri'), Kit::form('lavorano')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paga', 'Paolo paga il conto e la mancia.', 'Paolo pays the bill and the tip.', [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::form('paga')], 'listen', homophoneNote: self::E_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.lavora', 'Dove lavora il cameriere?', 'Where does the waiter work?', [['ristorante', 'qui']], 'Il cameriere lavora in un ristorante.', [Kit::word('il cameriere', 'cameriere'), Kit::word('il ristorante', 'ristorante'), Kit::form('lavora')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mancia', 'Chi paga la mancia?', 'Who pays the tip?', [['io', 'tu', 'noi', 'lui', 'lei', 'voi', 'loro', 'cameriere', 'anna', 'paolo', 'marta', 'luca'], ['paga', 'pago', 'paghiamo', 'pagano', 'paghi']], 'Paolo paga la mancia.', [Kit::word('la mancia', 'mancia'), Kit::form('paga')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.delizioso', 'Il menù è delizioso?', 'Is the menu delicious?', [['sì', 'no', 'è', 'delizioso', 'deliziosa', 'squisito']], 'Sì, il menù è delizioso.', [Kit::word('il menù', 'menù'), Kit::word('delizioso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mangiare', "C'è qualcosa da mangiare?", 'Is there something to eat?', [['sì', 'no', "c'è"]], "Sì, c'è qualcosa da mangiare.", [Kit::word('da mangiare')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.menu', 'Buonasera, vorrei un menù vegetariano.', 'Good evening, I would like a vegetarian menu.', [Kit::word('vorrei'), Kit::word('il menù', 'menù'), Kit::word('vegetariano')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.mancia', 'Il conto e la mancia, per favore.', 'The bill and the tip, please.', [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.cameriere', 'The waiter works in a delicious vegetarian restaurant.', ['Il cameriere lavora in un ristorante vegetariano delizioso.', 'Il cameriere lavora in un ristorante vegetariano squisito.'], [Kit::word('il cameriere', 'cameriere'), Kit::word('il ristorante', 'ristorante'), Kit::word('vegetariano'), Kit::word('delizioso', null, ['squisito']), Kit::form('lavora')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.menu', 'I would like the menu and the bill, please.', ['Vorrei il menù e il conto, per favore.', 'Vorrei il menu e il conto, per favore.', 'Per favore, vorrei il menù e il conto.', 'Per favore, vorrei il menu e il conto.'], [Kit::word('vorrei'), Kit::word('il menù', 'menù', ['menu']), Kit::word('il conto', 'conto')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mancia', 'We pay the tip to the waiter.', ['Paghiamo la mancia al cameriere.', 'Noi paghiamo la mancia al cameriere.'], [Kit::word('la mancia', 'mancia'), Kit::word('il cameriere', 'cameriere'), Kit::form('paghiamo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paghi', 'Anna, do you pay the bill or the tip?', ['Anna, paghi tu il conto o la mancia?', 'Paghi tu il conto o la mancia, Anna?', 'Anna, tu paghi il conto o la mancia?', 'Anna, paghi il conto o la mancia?', 'Tu paghi il conto o la mancia, Anna?', 'Paghi il conto o la mancia, Anna?'], [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::form('paghi')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paga', 'Marta ___ il conto.', 'Marta pays the bill.', 'paga', Kit::form('paga', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.lavoriamo', 'Anna e io ___ qui.', 'Anna and I work here.', 'lavoriamo', Kit::form('lavoriamo', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.bere-mangiare', 'Vorrei qualcosa da bere e da mangiare.', 'I would like something to drink and something to eat.', [Kit::word('vorrei'), Kit::word('da bere'), Kit::word('da mangiare')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.ordina', 'Marta ordina qualcosa da bere e da mangiare.', 'Marta orders something to drink and to eat.', [Kit::word('da bere'), Kit::word('da mangiare'), Kit::form('ordina')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.menu', 'Il menù vegetariano del ristorante è delizioso.', 'The restaurant\'s vegetarian menu is delicious.', [Kit::word('il menù', 'menù'), Kit::word('vegetariano'), Kit::word('il ristorante', 'ristorante'), Kit::word('delizioso')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.menu', [
                Kit::line('Cameriere', 'Buonasera. Ecco il menù vegetariano.'),
                Kit::line('Luca', 'Grazie. Io vorrei qualcosa da mangiare.'),
                Kit::line('Cameriere', 'Molto bene. E da bere?'),
            ], [
                Kit::question('What does the waiter give Luca?', ['The vegetarian menu', 'The bill', 'The tip'], 'The vegetarian menu'),
                Kit::question('What does Luca ask for?', ['Something to eat', 'Something to drink', 'The bill'], 'Something to eat'),
                Kit::question('What does the waiter ask about at the end?', ['Something to drink', 'A vegetarian menu', 'The tip'], 'Something to drink'),
            ], [
                Kit::question('Who speaks first?', ['The waiter', 'Luca', 'Nobody'], 'The waiter'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Does the waiter bring a vegetarian menu?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('il menù', 'menù'), Kit::word('vorrei'), Kit::word('da mangiare'), Kit::word('da bere'), Kit::word('vegetariano')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ristorante', 'Read the conversation.', [
                Kit::line('Marta', 'Ciao, Paolo. Lavoro in un ristorante vegetariano.'),
                Kit::line('Paolo', 'Molto bene. Il menù è vegetariano?'),
                Kit::line('Marta', 'Sì, è vegetariano e delizioso.'),
            ], [
                Kit::question('Where does Marta work?', ['In a vegetarian restaurant', 'In a hotel', 'In a shop'], 'In a vegetarian restaurant'),
                Kit::question('What does Paolo ask about?', ['Whether the menu is vegetarian', 'The bill', 'The tip'], 'Whether the menu is vegetarian'),
            ], [Kit::word('il ristorante', 'ristorante'), Kit::word('vegetariano'), Kit::word('il menù', 'menù'), Kit::word('delizioso')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.bere', "C'è qualcosa da bere?", 'Is there something to drink?', [['sì', 'no', "c'è"]], "Sì, c'è qualcosa da bere.", [Kit::word('da bere')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ristorante', "C'è un ristorante vegetariano qui?", 'Is there a vegetarian restaurant here?', [['sì', 'no', "c'è"]], "Sì, c'è un ristorante vegetariano qui.", [Kit::word('il ristorante', 'ristorante'), Kit::word('vegetariano')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.conto', 'Paghi tu il conto?', 'Do you pay the bill?', [['sì', 'no'], ['pago', 'paga', 'paghiamo', 'pagano', 'conto']], 'Sì, pago io il conto.', [Kit::word('il conto', 'conto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.parliamo', 'We speak with the waiter of the vegetarian restaurant.', ['Parliamo con il cameriere del ristorante vegetariano.', 'Noi parliamo con il cameriere del ristorante vegetariano.'], [Kit::word('il cameriere', 'cameriere'), Kit::word('il ristorante', 'ristorante'), Kit::word('vegetariano'), Kit::form('parliamo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vorrei', 'The menu is delicious, I would like something to eat.', ['Il menù è delizioso, vorrei qualcosa da mangiare.', 'Il menu è delizioso, vorrei qualcosa da mangiare.', 'Il menù è squisito, vorrei qualcosa da mangiare.', 'Il menu è squisito, vorrei qualcosa da mangiare.'], [Kit::word('vorrei'), Kit::word('il menù', 'menù', ['menu']), Kit::word('delizioso', null, ['squisito']), Kit::word('da mangiare')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lavora', 'Paolo works in a restaurant with a delicious menu.', ['Paolo lavora in un ristorante con un menù delizioso.', 'Paolo lavora in un ristorante con un menu delizioso.', 'Paolo lavora in un ristorante con un menù squisito.', 'Paolo lavora in un ristorante con un menu squisito.'], [Kit::word('il ristorante', 'ristorante'), Kit::word('il menù', 'menù', ['menu']), Kit::word('delizioso', null, ['squisito']), Kit::form('lavora')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.paga', 'Marta pays the bill, Paolo pays the tip.', ['Marta paga il conto, Paolo paga la mancia.', 'Marta paga il conto e Paolo paga la mancia.'], [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::form('paga')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.parlate', 'Voi ___ con il cameriere.', 'You all speak with the waiter.', 'parlate', Kit::form('parlate', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pagano', 'Paolo e Marta ___ il conto.', 'Paolo and Marta pay the bill.', 'pagano', Kit::form('pagano', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.ordiniamo', 'Ordiniamo qualcosa da bere e da mangiare.', 'We order something to drink and to eat.', [Kit::word('da bere'), Kit::word('da mangiare'), Kit::form('ordiniamo')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.menu', 'Vorrei il menù vegetariano e qualcosa da bere.', 'I would like the vegetarian menu and something to drink.', [Kit::word('vorrei'), Kit::word('il menù', 'menù'), Kit::word('vegetariano'), Kit::word('da bere')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.conto', 'Ecco il conto e la mancia per il cameriere.', 'Here are the bill and the tip for the waiter.', [Kit::word('il conto', 'conto'), Kit::word('la mancia', 'mancia'), Kit::word('il cameriere', 'cameriere')], 'dictation', $set, homophoneNote: self::E_NOTE),
        ];
    }
}
