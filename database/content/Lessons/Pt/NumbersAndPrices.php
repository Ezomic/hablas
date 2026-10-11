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

final class NumbersAndPrices implements UnitContent
{
    private const NUMBERS = ['um', 'uma', 'dois', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez', 'onze', 'doze', 'treze', 'catorze', 'quinze', 'dezasseis', 'dezassete', 'dezoito', 'dezanove', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'cem'];

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'numbers-and-prices';
    }

    public function words(): array
    {
        return [
            new WordData('trinta', cue: 'thirty'),
            new WordData('quarenta', cue: 'forty'),
            new WordData('cinquenta', cue: 'fifty', portunolSlips: ['cincuenta']),
            new WordData('cem', cue: 'one hundred', note: 'Cem is exactly 100: cem euros.', portunolSlips: ['cien']),
            new WordData('o euro', cue: 'euro', forms: ['euros']),
            new WordData('custar', cue: 'to cost', forms: ['custa', 'custam'], note: 'Use custa for one thing and custam for several things.', portunolSlips: ['costar', 'cuesta']),
            new WordData('pagar', cue: 'to pay', forms: ['pago', 'pagas']),
            new WordData('o cartão', cue: 'card (bank card)', portunolSlips: ['la tarjeta']),
            new WordData('o saco', cue: 'bag', forms: ['sacos'], note: 'A bolsa is not the bag you get in a shop in Portugal. That is o saco.', portunolSlips: ['la bolsa']),
            new WordData('o computador', cue: 'computer', forms: ['computadores'], portunolSlips: ['el ordenador']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quanto custa o saco?', 'english' => 'How much does the bag cost?'],
            ['text' => 'Os computadores custam cem euros.', 'english' => 'The computers cost 100 euros.'],
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
            Kit::gap($stage, 'sentences.choose_gap.saco-dez', 'O saco ___ dez euros.', ['custa', 'custam'], 'custa', Kit::form('custa', true), 'O saco is one thing, so you need custa. Custam is for several things.', 'choose', 'The bag costs 10 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.computadores-cem', 'Os computadores ___ cem euros.', ['custam', 'custa'], 'custam', Kit::form('custam', true), 'Os computadores is plural, so you need custam. Custa is for one thing.', 'choose', 'The computers cost 100 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.quanto-saco', 'Quanto ___ o saco?', ['custa', 'custam'], 'custa', Kit::form('custa'), 'You ask about one bag, so you need custa. Custam would be for several things.', 'choose', 'How much does the bag cost?'),
            Kit::gap($stage, 'sentences.choose_gap.contar', 'Trinta, quarenta, ___, sessenta.', ['cinquenta', 'cem', 'dez'], 'cinquenta', Kit::word('cinquenta'), 'The tens go trinta, quarenta, cinquenta, sessenta. Cem is 100 and dez is 10, so neither fits between 40 and 60.', 'choose', 'Thirty, forty, fifty, sixty.', ['sessenta' => 'sixty']),
            Kit::gap($stage, 'sentences.choose_gap.soma-cem', 'Cinquenta e cinquenta são ___.', ['cem', 'quarenta', 'trinta'], 'cem', Kit::word('cem'), '50 and 50 make 100, which is cem. Quarenta is 40 and trinta is 30.', 'choose', '50 and 50 make 100.'),
            Kit::gap($stage, 'sentences.choose_gap.cartao', 'Pago com o ___.', ['cartão', 'cem', 'trinta'], 'cartão', Kit::word('o cartão', 'cartão'), 'You pay with a card, o cartão. Cem and trinta are numbers, not things you pay with.', 'choose', 'I pay with the card.'),

            Kit::typeGap($stage, 'sentences.type_gap.computadores-quarenta', 'Os computadores ___ quarenta euros.', 'The computers cost 40 euros.', 'custam', Kit::form('custam'), 'Os computadores is plural, so the verb is custam.'),
            Kit::typeGap($stage, 'sentences.type_gap.quanto-sacos', 'Quanto ___ os sacos?', 'How much do the bags cost?', 'custam', Kit::form('custam'), 'Os sacos is plural, so the verb is custam.'),
            Kit::typeGap($stage, 'sentences.type_gap.quinze-euros', 'Custa quinze ___.', 'It costs 15 euros.', 'euros', Kit::word('o euro', 'euros')),
            Kit::typeGap($stage, 'sentences.type_gap.vinte-cinquenta', 'Vinte e cinco mais vinte e cinco são ___.', '25 and 25 make 50.', 'cinquenta', Kit::word('cinquenta'), null, 'write', null, ['mais' => 'plus']),
            Kit::typeGap($stage, 'sentences.type_gap.pagas', '___ com cartão?', 'Do you pay by card? (informal you, one person)', 'Pagas', Kit::word('pagar', 'pagas')),

            Kit::translate($stage, 'sentences.translate.saco-vinte', 'The bag costs 20 euros.', ['O saco custa vinte euros.'], [Kit::word('o saco', 'saco'), Kit::word('custar', 'custa'), Kit::word('o euro', 'euros'), Kit::form('custa')]),
            Kit::translate($stage, 'sentences.translate.quanto-computadores', 'How much do the computers cost?', ['Quanto custam os computadores?'], [Kit::word('o computador', 'computadores'), Kit::word('custar', 'custam'), Kit::form('custam', true)]),
            Kit::translate($stage, 'sentences.translate.trinta-e-cinco', 'It costs 35 euros.', ['Custa trinta e cinco euros.', 'São trinta e cinco euros.'], [Kit::word('trinta'), Kit::word('o euro', 'euros'), Kit::form('trinta e cinco')]),

            Kit::build($stage, 'sentences.build.saco-quarenta', 'The bag costs 40 euros.', 'O saco custa quarenta euros.', ['custam'], [Kit::word('o saco', 'saco'), Kit::word('quarenta'), Kit::form('custa', true)]),
            Kit::build($stage, 'sentences.build.pagas', 'Do you pay by card? (informal you)', 'Pagas com cartão?', ['em'], [Kit::word('pagar', 'pagas'), Kit::word('o cartão', 'cartão')]),
            Kit::build($stage, 'sentences.build.computadores-cem', 'The computers cost 100 euros.', 'Os computadores custam cem euros.', ['custa'], [Kit::word('o computador', 'computadores'), Kit::word('cem'), Kit::form('custam', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.saco-dez', 'O saco custa dez euros.', ['The bag costs 10 euros.', 'The bag costs 20 euros.', 'The bags cost 10 euros.', 'The computer costs 10 euros.'], 'The bag costs 10 euros.', [Kit::word('o saco', 'saco'), Kit::word('o euro', 'euros'), Kit::form('custa')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.computadores-quarenta', 'Os computadores custam quarenta euros.', ['The computers cost 40 euros.', 'The computers cost 14 euros.', 'The computers cost 50 euros.', 'The computer costs 40 euros.'], 'The computers cost 40 euros.', [Kit::word('o computador', 'computadores'), Kit::word('quarenta'), Kit::form('custam')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cinquenta', 'Custa cinquenta euros.', ['It costs 50 euros.', 'It costs 15 euros.', 'It costs 100 euros.', 'It costs 30 euros.'], 'It costs 50 euros.', [Kit::word('cinquenta'), Kit::word('custar', 'custa')]),
            Kit::listenType($stage, 'sentences.listen_type.pago', 'Pago com cartão.', 'I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')]),
            Kit::listenType($stage, 'sentences.listen_type.quanto-saco', 'Quanto custa o saco?', 'How much does the bag cost?', [Kit::word('o saco', 'saco'), Kit::form('custa')]),
            Kit::listenType($stage, 'sentences.listen_type.trinta-e-cinco', 'São trinta e cinco euros.', 'That is 35 euros.', [Kit::word('trinta'), Kit::word('o euro', 'euros'), Kit::form('trinta e cinco')]),
            Kit::listenType($stage, 'sentences.listen_type.sacos-trinta', 'Os sacos custam trinta euros.', 'The bags cost 30 euros.', [Kit::word('o saco', 'sacos'), Kit::word('custar', 'custam'), Kit::form('custam')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.quarenta', 'Custa quarenta euros.', 'It costs 40 euros.', [Kit::word('quarenta'), Kit::word('o euro', 'euros'), Kit::word('custar', 'custa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.computadores-cem', 'Os computadores custam cem euros.', 'The computers cost 100 euros.', [Kit::word('o computador', 'computadores'), Kit::word('cem'), Kit::form('custam')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pagas', 'Pagas com cartão?', 'Do you pay by card? (informal you)', [Kit::word('pagar', 'pagas'), Kit::word('o cartão', 'cartão')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sacos-cinquenta', 'Os sacos custam cinquenta euros.', 'The bags cost 50 euros.', [Kit::word('o saco', 'sacos'), Kit::word('cinquenta'), Kit::form('custam')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quanto-saco', 'Quanto custa o saco?', 'How much does the bag cost?', [self::NUMBERS, ['euro', 'euros']], 'Custa quinze euros.', [Kit::word('o saco', 'saco'), Kit::word('o euro', 'euros'), Kit::word('custar', 'custa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quanto-computadores', 'Quanto custam os computadores?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Custam cem euros.', [Kit::word('o computador', 'computadores'), Kit::word('cem'), Kit::form('custam')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pagas', 'Pagas com cartão?', 'Do you pay by card? (informal you)', [['sim', 'não'], ['pago', 'cartão']], 'Sim, pago com cartão.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.loja', 'Read the conversation in the shop.', [
                Kit::line('Ana', 'Olá, João. Quanto custam os computadores?'),
                Kit::line('João', 'Custam cem euros.'),
                Kit::line('Ana', 'E o saco?'),
                Kit::line('João', 'Custa trinta euros. Pagas com cartão?'),
                Kit::line('Ana', 'Sim, pago com cartão.'),
            ], [
                Kit::question('How much do the computers cost?', ['100 euros', '30 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['30 euros', '13 euros', '40 euros'], '30 euros'),
                Kit::question('How does Ana pay?', ['By card', 'In cash', 'She does not pay.'], 'By card'),
            ], [Kit::word('o computador', 'computadores'), Kit::word('custar', 'custam'), Kit::word('o euro', 'euros'), Kit::word('o saco', 'saco'), Kit::word('trinta'), Kit::word('cem'), Kit::word('pagar', 'pagas'), Kit::word('o cartão', 'cartão')], 'read'),
            Kit::gap($stage, 'task.choose_gap.saco-computadores', 'O saco custa um euro e os computadores ___ cem euros.', ['custam', 'custa'], 'custam', Kit::form('custam', true), 'Os computadores is plural, so you need custam. O saco is one thing, which is why it has custa.', 'read', 'The bag costs 1 euro and the computers cost 100 euros.'),
            Kit::gap($stage, 'task.choose_gap.um-euro', 'O saco custa um ___.', ['euro', 'euros'], 'euro', Kit::word('o euro', 'euro'), 'Um means one, so the word stays euro. Euros is for two or more.', 'read', 'The bag costs 1 euro.'),

            Kit::transform($stage, 'task.transform.sacos', 'Now say it for several things.', 'O saco custa vinte euros.', ['Os sacos custam vinte euros.'], [Kit::word('o saco', 'sacos'), Kit::word('custar', 'custam'), Kit::form('custam', true)]),
            Kit::transform($stage, 'task.transform.quanto-computador', 'Now ask how much it costs.', 'O computador custa cem euros.', ['Quanto custa o computador?'], [Kit::word('o computador'), Kit::word('custar', 'custa'), Kit::form('custa')]),
            Kit::transform($stage, 'task.transform.cinquenta', 'Now make it 50 euros.', 'O saco custa quarenta euros.', ['O saco custa cinquenta euros.'], [Kit::word('cinquenta'), Kit::word('o saco', 'saco')]),
            Kit::writeGuided($stage, 'task.write_guided.saco-cartao', 'Say that the bag costs 30 euros and that you pay by card.', ['saco', 'custa', 'trinta', 'pago', 'cartão'], 'O saco custa trinta euros. Pago com cartão.', [
                ['forms' => ['saco'], 'term' => 'o saco'],
                ['forms' => ['custa'], 'term' => 'custar'],
                ['forms' => ['trinta'], 'term' => 'trinta'],
                ['forms' => ['pago'], 'term' => 'pagar'],
                ['forms' => ['cartão'], 'term' => 'o cartão'],
            ], [Kit::word('o saco', 'saco'), Kit::word('custar', 'custa'), Kit::word('trinta'), Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')]),
            Kit::writeGuided($stage, 'task.write_guided.computadores-sacos', 'Say that the computers cost 100 euros and the bags cost 40 euros.', ['computadores', 'custam', 'cem', 'sacos', 'quarenta'], 'Os computadores custam cem euros e os sacos custam quarenta euros.', [
                ['forms' => ['computadores'], 'term' => 'o computador'],
                ['forms' => ['custam'], 'term' => 'custar'],
                ['forms' => ['cem'], 'term' => 'cem'],
                ['forms' => ['sacos'], 'term' => 'o saco'],
                ['forms' => ['quarenta'], 'term' => 'quarenta'],
            ], [Kit::word('o computador', 'computadores'), Kit::word('custar', 'custam'), Kit::word('cem'), Kit::word('o saco', 'sacos'), Kit::word('quarenta')]),
            Kit::build($stage, 'task.build.sacos-cinquenta', 'The bags cost 50 euros.', 'Os sacos custam cinquenta euros.', ['custa', 'com'], [Kit::word('o saco', 'sacos'), Kit::word('cinquenta'), Kit::form('custam', true)], 'write'),
            Kit::build($stage, 'task.build.quarenta-e-cinco', 'It costs 45 euros.', 'Custa quarenta e cinco euros.', ['custam', 'de'], [Kit::word('quarenta'), Kit::word('o euro', 'euros'), Kit::form('quarenta e cinco')], 'write'),
            Kit::build($stage, 'task.build.computador-cem', 'The computer costs 100 euros.', 'O computador custa cem euros.', ['custam', 'os'], [Kit::word('o computador'), Kit::word('cem'), Kit::form('custa', true)], 'write'),
            Kit::translate($stage, 'task.translate.sacos-computador', 'The bags cost 40 euros and the computer costs 100 euros.', ['Os sacos custam quarenta euros e o computador custa cem euros.', 'Os sacos custam quarenta euros e o computador cem euros.'], [Kit::word('o saco', 'sacos'), Kit::word('o computador'), Kit::word('quarenta'), Kit::word('cem'), Kit::form('custam', true)], 'write'),
            Kit::translate($stage, 'task.translate.pago-trinta-e-cinco', 'I pay 35 euros by card.', ['Pago trinta e cinco euros com cartão.', 'Eu pago trinta e cinco euros com cartão.', 'Pago trinta e cinco euros com o cartão.', 'Eu pago trinta e cinco euros com o cartão.'], [Kit::word('pagar', 'pago'), Kit::word('trinta'), Kit::word('o cartão', 'cartão'), Kit::word('o euro', 'euros')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.loja', [
                Kit::line('Marta', 'Olá, Rui. Quanto custa o computador?'),
                Kit::line('Rui', 'O computador custa quarenta euros.'),
                Kit::line('Marta', 'E os sacos?'),
                Kit::line('Rui', 'Os sacos custam quinze euros.'),
                Kit::line('Marta', 'Pago com cartão, por favor.'),
            ], [
                Kit::question('How much does the computer cost?', ['40 euros', '14 euros', '50 euros'], '40 euros'),
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Marta pay?', ['By card', 'In cash', 'She does not say.'], 'By card'),
            ], [
                Kit::question('Who asks the price of the computer?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Does Marta ask about the bags?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o computador'), Kit::word('quarenta'), Kit::word('o saco', 'sacos'), Kit::word('custar', 'custa'), Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.computador-quarenta-e-cinco', 'O computador custa quarenta e cinco euros.', 'The computer costs 45 euros.', [Kit::word('o computador'), Kit::word('quarenta'), Kit::form('quarenta e cinco')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.saco-cinquenta', 'O saco custa cinquenta euros.', 'The bag costs 50 euros.', [Kit::word('o saco', 'saco'), Kit::word('cinquenta'), Kit::form('custa')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pago-cem', 'Pago cem euros com cartão.', 'I pay 100 euros by card.', [Kit::word('pagar', 'pago'), Kit::word('cem'), Kit::word('o cartão', 'cartão'), Kit::word('o euro', 'euros')], 'listen', homophoneNote: 'Cem (one hundred) sounds like sem (without), but here it is the number: cem euros.'),

            Kit::speakAnswer($stage, 'task.speak_answer.quanto-computador', 'Quanto custa o computador?', 'How much does the computer cost?', [self::NUMBERS, ['euro', 'euros']], 'Custa cem euros.', [Kit::word('o computador'), Kit::word('cem'), Kit::word('custar', 'custa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quanto-computadores', 'Quanto custam os computadores?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Custam quarenta euros.', [Kit::word('o computador', 'computadores'), Kit::word('quarenta'), Kit::word('custar', 'custam')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.como-pagas', 'Como pagas?', 'How do you pay? (informal you; say: by card)', [['pago', 'com'], ['cartão']], 'Pago com cartão.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quanto-e', 'Quanto é, por favor?', 'How much is it, please?', [self::NUMBERS, ['euro', 'euros']], 'São trinta euros.', [Kit::word('trinta'), Kit::word('o euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.sacos-quinze', 'Os sacos custam quinze euros.', 'The bags cost 15 euros.', [Kit::word('o saco', 'sacos'), Kit::word('custar', 'custam'), Kit::word('o euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.saco-quarenta-e-cinco', 'O saco custa quarenta e cinco euros.', 'The bag costs 45 euros.', [Kit::word('o saco', 'saco'), Kit::word('quarenta'), Kit::word('custar', 'custa')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.computador-saco', 'The computer costs 100 euros and the bag costs 30.', ['O computador custa cem euros e o saco custa trinta.', 'O computador custa cem euros e o saco trinta.', 'O computador custa cem euros e o saco trinta euros.'], [Kit::word('o computador'), Kit::word('o saco', 'saco'), Kit::word('cem'), Kit::word('trinta'), Kit::word('custar', 'custa'), Kit::form('custa')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.quanto-sacos', 'How much does it cost?', ['Quanto custa?'], [Kit::word('custar', 'custa'), Kit::form('custa', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pago-quarenta', 'I pay 40 euros by card.', ['Pago quarenta euros com cartão.', 'Eu pago quarenta euros com cartão.', 'Pago quarenta euros com o cartão.', 'Eu pago quarenta euros com o cartão.'], [Kit::word('pagar', 'pago'), Kit::word('quarenta'), Kit::word('o cartão', 'cartão'), Kit::word('o euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.computadores-quarenta-e-cinco', 'The computers cost 45 euros.', ['Os computadores custam quarenta e cinco euros.'], [Kit::word('o computador', 'computadores'), Kit::word('quarenta'), Kit::form('quarenta e cinco')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.computador-quinze', 'O computador ___ quinze euros.', 'The computer costs 15 euros.', 'custa', Kit::form('custa', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sacos-dez', 'Os sacos ___ dez euros.', 'The bags cost 10 euros.', 'custam', Kit::form('custam'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.quanto-saco', 'Ana, quanto custa o saco?', 'Ana, how much does the bag cost?', [Kit::word('o saco', 'saco'), Kit::form('custa')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.sao-trinta', 'São trinta euros. Pagas com cartão?', 'That is 30 euros. Do you pay by card? (informal you)', [Kit::word('trinta'), Kit::word('pagar', 'pagas'), Kit::word('o cartão', 'cartão'), Kit::word('o euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cartao-cinquenta', 'São cinquenta euros. Pago com cartão.', 'That is 50 euros. I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão'), Kit::word('cinquenta'), Kit::word('o euro', 'euros')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.loja', [
                Kit::line('Marta', 'Ana, quanto custa o computador?'),
                Kit::line('Ana', 'Custa cem euros.'),
                Kit::line('Marta', 'E o saco, Ana?'),
                Kit::line('Ana', 'Custa vinte euros.'),
            ], [
                Kit::question('How much does the computer cost?', ['100 euros', '10 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['20 euros', '12 euros', '30 euros'], '20 euros'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Ana', 'Nobody'], 'Marta'),
                Kit::question('Does Ana ask about the bag?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o computador'), Kit::word('cem'), Kit::word('o saco', 'saco'), Kit::word('custar', 'custa')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.cartao', 'Read the conversation.', [
                Kit::line('Rui', 'Marta, quanto custam os sacos?'),
                Kit::line('Marta', 'Custam quinze euros.'),
                Kit::line('Rui', 'Pago com cartão, obrigado.'),
            ], [
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Rui pay?', ['By card', 'In cash'], 'By card'),
            ], [Kit::word('o saco', 'sacos'), Kit::word('custar', 'custam'), Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quanto-computador', 'Quanto custa o computador, Rui?', 'How much does the computer cost, Rui?', [self::NUMBERS, ['euro', 'euros']], 'Custa quarenta euros.', [Kit::word('o computador'), Kit::word('quarenta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quanto-saco', 'Quanto custa o saco, Marta?', 'How much does the bag cost, Marta?', [self::NUMBERS, ['euro', 'euros']], 'Custa trinta euros.', [Kit::word('o saco', 'saco'), Kit::word('trinta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pagas', 'Pagas com cartão, Ana?', 'Do you pay by card, Ana?', [['sim', 'não'], ['pago', 'cartão']], 'Sim, pago com cartão.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.computadores-cinquenta', 'The computers cost 50 euros.', ['Os computadores custam cinquenta euros.'], [Kit::word('o computador', 'computadores'), Kit::word('cinquenta'), Kit::word('o euro', 'euros'), Kit::word('custar', 'custam'), Kit::form('custam')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.computador-saco', 'The computer costs 100 euros and the bag costs 40.', ['O computador custa cem euros e o saco custa quarenta.', 'O computador custa cem euros e o saco quarenta.', 'O computador custa cem euros e o saco quarenta euros.'], [Kit::word('o computador'), Kit::word('o saco', 'saco'), Kit::word('cem'), Kit::word('quarenta'), Kit::word('custar', 'custa'), Kit::form('custa')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pago-cinquenta', 'I pay 50 euros by card.', ['Pago cinquenta euros com cartão.', 'Eu pago cinquenta euros com cartão.', 'Pago cinquenta euros com o cartão.', 'Eu pago cinquenta euros com o cartão.'], [Kit::word('pagar', 'pago'), Kit::word('cinquenta'), Kit::word('o cartão', 'cartão'), Kit::word('o euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.saco-trinta-e-cinco', 'The bag costs 35 euros.', ['O saco custa trinta e cinco euros.'], [Kit::word('o saco', 'saco'), Kit::word('trinta'), Kit::word('o euro', 'euros'), Kit::form('trinta e cinco')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.sacos-doze', 'Os sacos ___ doze euros.', 'The bags cost 12 euros.', 'custam', Kit::form('custam', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.computador-trinta', 'O computador ___ trinta euros.', 'The computer costs 30 euros.', 'custa', Kit::form('custa', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.sacos-quarenta', 'Os sacos custam quarenta euros.', 'The bags cost 40 euros.', [Kit::word('o saco', 'sacos'), Kit::word('quarenta'), Kit::form('custam')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.sao-trinta', 'São trinta euros, por favor.', 'That is 30 euros, please.', [Kit::word('trinta'), Kit::word('o euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pago-cem', 'São cem euros. Pago com cartão.', 'That is 100 euros. I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('o cartão', 'cartão'), Kit::word('cem'), Kit::word('o euro', 'euros')], 'dictation', $set, homophoneNote: 'Cem (one hundred) sounds like sem (without), but here it is the number: cem euros.'),
        ];
    }
}
