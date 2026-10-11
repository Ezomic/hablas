<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

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
    /** @return list<string> */
    private static function numbers(): array
    {
        return ['un', 'uno', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici', 'tredici', 'quattordici', 'quindici', 'sedici', 'diciassette', 'diciotto', 'diciannove', 'cento', 'zero', ...self::forms(['venti', 'trenta', 'quaranta', 'cinquanta', 'sessanta', 'settanta', 'ottanta', 'novanta'])];
    }

    /**
     * @param  list<string>  $tens
     * @return list<string>
     */
    private static function forms(array $tens): array
    {
        $units = ['uno' => ['uno', 'un'], 'due' => ['due'], 'tre' => ['tre', 'tré'], 'quattro' => ['quattro'], 'cinque' => ['cinque'], 'sei' => ['sei'], 'sette' => ['sette'], 'otto' => ['otto'], 'nove' => ['nove']];
        $forms = [];

        foreach ($tens as $ten) {
            $forms[] = $ten;

            foreach ($units as $unit => $spellings) {
                $stem = in_array($unit, ['uno', 'otto'], true) ? substr($ten, 0, -1) : $ten;

                foreach ($spellings as $spelling) {
                    $forms[] = $stem.$spelling;
                }
            }
        }

        return $forms;
    }

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'numbers-and-prices';
    }

    public function words(): array
    {
        return [
            new WordData('trenta', cue: 'thirty', forms: self::forms(['venti', 'trenta'])),
            new WordData('quaranta', cue: 'forty', forms: self::forms(['quaranta'])),
            new WordData('cinquanta', cue: 'fifty', forms: self::forms(['cinquanta', 'sessanta'])),
            new WordData('cento', cue: 'one hundred', forms: [...self::forms(['settanta', 'ottanta', 'novanta']), 'zero'], note: 'Cento does not change: cento euro. 200 is duecento.'),
            new WordData("l'euro", cue: 'euro', accepted: ['euro'], note: 'Euro does not change in the plural: un euro, dieci euro.'),
            new WordData('costare', cue: 'to cost', forms: ['costa', 'costano'], note: 'Use costa for one thing and costano for several things.'),
            new WordData('pagare', cue: 'to pay', forms: ['pago', 'paghi']),
            new WordData('la carta', cue: 'card (bank card)', note: 'La carta also means paper. For payment it is short for la carta di credito or la carta (bancomat).'),
            new WordData('la borsa', cue: 'bag (handbag)', forms: ['borse']),
            new WordData('il computer', cue: 'computer', accepted: ['computer'], note: 'Computer does not change in the plural: il computer, i computer.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quanto costa la borsa?', 'english' => 'How much does the bag cost?'],
            ['text' => 'I computer costano cento euro.', 'english' => 'The computers cost 100 euros.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.borsa-dieci', 'La borsa ___ dieci euro.', ['costa', 'costano'], 'costa', Kit::form('costa', true), 'La borsa is one thing, so you need costa. Costano is for several things.', 'choose', 'The bag costs 10 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.computer-cento', 'I computer ___ cento euro.', ['costano', 'costa'], 'costano', Kit::form('costano', true), 'I computer is plural, so you need costano. Costa is for one thing.', 'choose', 'The computers cost 100 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.quanto-borsa', 'Quanto ___ la borsa?', ['costa', 'costano'], 'costa', Kit::form('costa'), 'You ask about one bag, so you need costa. Costano would be for several things.', 'choose', 'How much does the bag cost?'),
            Kit::gap($stage, 'sentences.choose_gap.contare', 'Trenta, quaranta, ___, sessanta.', ['cinquanta', 'cento', 'dieci'], 'cinquanta', Kit::word('cinquanta'), 'The tens go trenta, quaranta, cinquanta, sessanta. Cento is 100 and dieci is 10, so neither fits between 40 and 60.', 'choose', 'Count in tens.', ['sessanta' => 'sixty']),
            Kit::gap($stage, 'sentences.choose_gap.somma-cento', 'Cinquanta e cinquanta sono ___.', ['cento', 'quaranta', 'trenta'], 'cento', Kit::word('cento'), '50 and 50 make 100, which is cento. Quaranta is 40 and trenta is 30.', 'choose', 'Add 50 and 50.'),
            Kit::gap($stage, 'sentences.choose_gap.carta', 'Pago con la ___.', ['carta', 'cento', 'trenta'], 'carta', Kit::word('la carta', 'carta'), 'You pay with a card, la carta. Cento and trenta are numbers, not things you pay with.', 'choose', 'I pay with the card.'),

            Kit::typeGap($stage, 'sentences.type_gap.computer-quaranta', 'I computer ___ quaranta euro.', 'The computers cost 40 euros.', 'costano', Kit::form('costano'), 'I computer is plural, so the verb is costano.'),
            Kit::typeGap($stage, 'sentences.type_gap.quanto-computer', 'Quanto ___ i computer?', 'How much do the computers cost?', 'costano', Kit::form('costano'), 'I computer is plural, so the verb is costano.'),
            Kit::typeGap($stage, 'sentences.type_gap.quindici-euro', 'Costa quindici ___.', 'It costs 15 euros.', 'euro', Kit::word("l'euro", 'euro'), 'Euro does not change in the plural: quindici euro, not euros.'),
            Kit::typeGap($stage, 'sentences.type_gap.venticinque-cinquanta', 'Venticinque e venticinque sono ___.', '25 and 25 make 50.', 'cinquanta', Kit::word('cinquanta'), null, 'write', null, ['venticinque' => '25']),
            Kit::typeGap($stage, 'sentences.type_gap.paghi', '___ con la carta?', 'Do you pay by card? (informal you, one person)', 'Paghi', Kit::word('pagare', 'paghi')),

            Kit::translate($stage, 'sentences.translate.borsa-venti', 'The bag costs 20 euros.', ['La borsa costa venti euro.'], [Kit::word('la borsa', 'borsa'), Kit::word('costare', 'costa'), Kit::word("l'euro", 'euro'), Kit::form('costa')]),
            Kit::translate($stage, 'sentences.translate.quanto-computer', 'How much do the computers cost?', ['Quanto costano i computer?'], [Kit::word('il computer', 'computer'), Kit::word('costare', 'costano'), Kit::form('costano', true)]),
            Kit::translate($stage, 'sentences.translate.trentacinque', 'It costs 35 euros.', ['Costa trentacinque euro.'], [Kit::word('trenta', 'trentacinque'), Kit::word("l'euro", 'euro'), Kit::form('trentacinque')]),

            Kit::build($stage, 'sentences.build.borsa-quaranta', 'The bag costs 40 euros.', 'La borsa costa quaranta euro.', ['costano'], [Kit::word('la borsa', 'borsa'), Kit::word('quaranta'), Kit::form('costa', true)]),
            Kit::build($stage, 'sentences.build.paghi', 'Do you pay by card? (informal you)', 'Paghi con la carta?', ['di'], [Kit::word('pagare', 'paghi'), Kit::word('la carta', 'carta')]),
            Kit::build($stage, 'sentences.build.computer-cento', 'The computers cost 100 euros.', 'I computer costano cento euro.', ['costa'], [Kit::word('il computer', 'computer'), Kit::word('cento'), Kit::form('costano', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.borsa-dieci', 'La borsa costa dieci euro.', ['The bag costs 10 euros.', 'The bag costs 20 euros.', 'The bags cost 10 euros.', 'The computer costs 10 euros.'], 'The bag costs 10 euros.', [Kit::word('la borsa', 'borsa'), Kit::word("l'euro", 'euro'), Kit::form('costa')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.computer-quaranta', 'I computer costano quaranta euro.', ['The computers cost 40 euros.', 'The computers cost 14 euros.', 'The computers cost 50 euros.', 'The computer costs 40 euros.'], 'The computers cost 40 euros.', [Kit::word('il computer', 'computer'), Kit::word('quaranta'), Kit::form('costano')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cinquanta', 'Costa cinquanta euro.', ['It costs 50 euros.', 'It costs 15 euros.', 'It costs 100 euros.', 'It costs 30 euros.'], 'It costs 50 euros.', [Kit::word('cinquanta'), Kit::word('costare', 'costa')]),
            Kit::listenType($stage, 'sentences.listen_type.pago', 'Pago con la carta.', 'I pay by card.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')]),
            Kit::listenType($stage, 'sentences.listen_type.quanto-borsa', 'Quanto costa la borsa?', 'How much does the bag cost?', [Kit::word('la borsa', 'borsa'), Kit::form('costa')]),
            Kit::listenType($stage, 'sentences.listen_type.trentacinque', 'Sono trentacinque euro.', 'That is 35 euros.', [Kit::word('trenta', 'trentacinque'), Kit::word("l'euro", 'euro'), Kit::form('trentacinque')]),
            Kit::listenType($stage, 'sentences.listen_type.borse-trenta', 'Le borse costano trenta euro.', 'The bags cost 30 euros.', [Kit::word('la borsa', 'borse'), Kit::word('costare', 'costano'), Kit::form('costano')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.quaranta', 'Costa quaranta euro.', 'It costs 40 euros.', [Kit::word('quaranta'), Kit::word("l'euro", 'euro'), Kit::word('costare', 'costa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.computer-cento', 'I computer costano cento euro.', 'The computers cost 100 euros.', [Kit::word('il computer', 'computer'), Kit::word('cento'), Kit::form('costano')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paghi', 'Paghi con la carta?', 'Do you pay by card? (informal you)', [Kit::word('pagare', 'paghi'), Kit::word('la carta', 'carta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.borse-cinquanta', 'Le borse costano cinquanta euro.', 'The bags cost 50 euros.', [Kit::word('la borsa', 'borse'), Kit::word('cinquanta'), Kit::form('costano')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quanto-borsa', 'Quanto costa la borsa?', 'How much does the bag cost?', [self::numbers(), ['euro']], 'Costa quindici euro.', [Kit::word('la borsa', 'borsa'), Kit::word("l'euro", 'euro'), Kit::word('costare', 'costa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quanto-computer', 'Quanto costano i computer?', 'How much do the computers cost?', [self::numbers(), ['euro']], 'Costano cento euro.', [Kit::word('il computer', 'computer'), Kit::word('cento'), Kit::form('costano')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paghi', 'Paghi con la carta?', 'Do you pay by card?', [['sì', 'no'], ['pago', 'carta']], 'Sì, pago con la carta.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.negozio', 'Read the conversation in the shop.', [
                Kit::line('Anna', 'Ciao, Paolo. Quanto costano i computer?'),
                Kit::line('Paolo', 'Costano cento euro.'),
                Kit::line('Anna', 'E la borsa?'),
                Kit::line('Paolo', 'Costa trenta euro. Paghi con la carta?'),
                Kit::line('Anna', 'Sì, pago con la carta.'),
            ], [
                Kit::question('How much do the computers cost?', ['100 euros', '30 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['30 euros', '13 euros', '40 euros'], '30 euros'),
                Kit::question('How does Anna pay?', ['By card', 'In cash', 'She does not pay.'], 'By card'),
            ], [Kit::word('il computer', 'computer'), Kit::word('costare', 'costano'), Kit::word("l'euro", 'euro'), Kit::word('la borsa', 'borsa'), Kit::word('trenta'), Kit::word('cento'), Kit::word('pagare', 'paghi'), Kit::word('la carta', 'carta')], 'read'),
            Kit::gap($stage, 'task.choose_gap.borsa-computer', 'La borsa costa un euro e i computer ___ cento euro.', ['costano', 'costa'], 'costano', Kit::form('costano', true), 'I computer is plural, so you need costano. La borsa is one thing, which is why it has costa.', 'read', 'The bag costs 1 euro and the computers cost 100 euros.'),
            Kit::gap($stage, 'task.choose_gap.un-euro', 'La borsa costa ___ euro.', ['un', 'uno'], 'un', Kit::word("l'euro", 'euro'), 'Euro starts with a vowel, so the masculine article is un, without an apostrophe. Uno is only for words that start with s plus a consonant, z, gn or ps.', 'read', 'The bag costs 1 euro.'),

            Kit::transform($stage, 'task.transform.borse', 'Now say it for several things.', 'La borsa costa venti euro.', ['Le borse costano venti euro.'], [Kit::word('la borsa', 'borse'), Kit::word('costare', 'costano'), Kit::form('costano', true)]),
            Kit::transform($stage, 'task.transform.quanto-computer', 'Now ask how much it costs.', 'Il computer costa cento euro.', ['Quanto costa il computer?', 'Il computer quanto costa?'], [Kit::word('il computer'), Kit::word('costare', 'costa'), Kit::form('costa')]),
            Kit::transform($stage, 'task.transform.cinquanta', 'Now make it 50 euros.', 'La borsa costa quaranta euro.', ['La borsa costa cinquanta euro.'], [Kit::word('cinquanta'), Kit::word('la borsa', 'borsa')]),
            Kit::writeGuided($stage, 'task.write_guided.borsa-carta', 'Say that the bag costs 30 euros and that you pay by card.', ['borsa', 'costa', 'trenta', 'pago', 'carta'], 'La borsa costa trenta euro. Pago con la carta.', [
                ['forms' => ['borsa'], 'term' => 'la borsa'],
                ['forms' => ['costa'], 'term' => 'costare'],
                ['forms' => ['trenta'], 'term' => 'trenta'],
                ['forms' => ['pago'], 'term' => 'pagare'],
                ['forms' => ['carta'], 'term' => 'la carta'],
            ], [Kit::word('la borsa', 'borsa'), Kit::word('costare', 'costa'), Kit::word('trenta'), Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')]),
            Kit::writeGuided($stage, 'task.write_guided.computer-borse', 'Say that the computers cost 100 euros and the bags cost 40 euros.', ['computer', 'costano', 'cento', 'borse', 'quaranta'], 'I computer costano cento euro e le borse costano quaranta euro.', [
                ['forms' => ['computer'], 'term' => 'il computer'],
                ['forms' => ['costano'], 'term' => 'costare'],
                ['forms' => ['cento'], 'term' => 'cento'],
                ['forms' => ['borse'], 'term' => 'la borsa'],
                ['forms' => ['quaranta'], 'term' => 'quaranta'],
            ], [Kit::word('il computer', 'computer'), Kit::word('costare', 'costano'), Kit::word('cento'), Kit::word('la borsa', 'borse'), Kit::word('quaranta')]),
            Kit::build($stage, 'task.build.borse-cinquanta', 'The bags cost 50 euros.', 'Le borse costano cinquanta euro.', ['costa', 'con'], [Kit::word('la borsa', 'borse'), Kit::word('cinquanta'), Kit::form('costano', true)], 'write'),
            Kit::build($stage, 'task.build.quarantacinque', 'It costs 45 euros.', 'Costa quarantacinque euro.', ['costano', 'di'], [Kit::word('quaranta', 'quarantacinque'), Kit::word("l'euro", 'euro'), Kit::form('quarantacinque')], 'write'),
            Kit::build($stage, 'task.build.computer-cento', 'The computer costs 100 euros.', 'Il computer costa cento euro.', ['costano', 'i'], [Kit::word('il computer'), Kit::word('cento'), Kit::form('costa', true)], 'write'),
            Kit::translate($stage, 'task.translate.borse-computer', 'The bags cost 40 euros and the computer costs 100 euros.', ['Le borse costano quaranta euro e il computer costa cento euro.'], [Kit::word('la borsa', 'borse'), Kit::word('il computer'), Kit::word('quaranta'), Kit::word('cento'), Kit::form('costano', true)], 'write'),
            Kit::translate($stage, 'task.translate.pago-trentacinque', 'I pay 35 euros by card.', ['Pago trentacinque euro con la carta.', 'Io pago trentacinque euro con la carta.', 'Pago trentacinque euro con carta.', 'Io pago trentacinque euro con carta.'], [Kit::word('pagare', 'pago'), Kit::word('trenta', 'trentacinque'), Kit::word('la carta', 'carta'), Kit::word("l'euro", 'euro')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.negozio', [
                Kit::line('Marta', 'Ciao, Luca. Quanto costa il computer?'),
                Kit::line('Luca', 'Il computer costa quaranta euro.'),
                Kit::line('Marta', 'E le borse?'),
                Kit::line('Luca', 'Le borse costano quindici euro.'),
                Kit::line('Marta', 'Pago con la carta, per favore.'),
            ], [
                Kit::question('How much does the computer cost?', ['40 euros', '14 euros', '50 euros'], '40 euros'),
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Marta pay?', ['By card', 'In cash', 'She does not say.'], 'By card'),
            ], [
                Kit::question('Who asks the price of the computer?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Does Marta ask about the bags?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il computer'), Kit::word('quaranta'), Kit::word('la borsa', 'borse'), Kit::word('costare', 'costa'), Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.computer-quarantacinque', 'Il computer costa quarantacinque euro.', 'The computer costs 45 euros.', [Kit::word('il computer'), Kit::word('quaranta', 'quarantacinque'), Kit::form('quarantacinque')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.borsa-cinquanta', 'La borsa costa cinquanta euro.', 'The bag costs 50 euros.', [Kit::word('la borsa', 'borsa'), Kit::word('cinquanta'), Kit::form('costa')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pago-cento', 'Pago cento euro con la carta.', 'I pay 100 euros by card.', [Kit::word('pagare', 'pago'), Kit::word('cento'), Kit::word('la carta', 'carta'), Kit::word("l'euro", 'euro')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.quanto-computer', 'Quanto costa il computer?', 'How much does the computer cost?', [self::numbers(), ['euro']], 'Costa cento euro.', [Kit::word('il computer'), Kit::word('cento'), Kit::word('costare', 'costa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quanto-computer-plurale', 'Quanto costano i computer?', 'How much do the computers cost?', [self::numbers(), ['euro']], 'Costano quaranta euro.', [Kit::word('il computer', 'computer'), Kit::word('quaranta'), Kit::word('costare', 'costano')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.come-paghi', 'Come paghi?', 'How do you pay? (informal you)', [['pago', 'con'], ['carta']], 'Pago con la carta.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quanto-costa', 'Quanto costa, per favore?', 'How much does it cost, please?', [self::numbers(), ['euro']], 'Sono trenta euro.', [Kit::word('trenta'), Kit::word("l'euro", 'euro')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.borse-quindici', 'Le borse costano quindici euro.', 'The bags cost 15 euros.', [Kit::word('la borsa', 'borse'), Kit::word('costare', 'costano'), Kit::word("l'euro", 'euro')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.borsa-quarantacinque', 'La borsa costa quarantacinque euro.', 'The bag costs 45 euros.', [Kit::word('la borsa', 'borsa'), Kit::word('quaranta', 'quarantacinque'), Kit::word('costare', 'costa')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.computer-borsa', 'The computer costs 100 euros and the bag costs 30.', ['Il computer costa cento euro e la borsa costa trenta.', 'Il computer costa cento euro e la borsa trenta.', 'Il computer costa cento euro e la borsa trenta euro.'], [Kit::word('il computer'), Kit::word('la borsa', 'borsa'), Kit::word('cento'), Kit::word('trenta'), Kit::word('costare', 'costa'), Kit::form('costa')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.quanto-borse', 'How much do the bags cost?', ['Quanto costano le borse?'], [Kit::word('la borsa', 'borse'), Kit::word('costare', 'costano'), Kit::form('costano', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pago-quaranta', 'I pay 40 euros by card.', ['Pago quaranta euro con la carta.', 'Io pago quaranta euro con la carta.', 'Pago quaranta euro con carta.', 'Io pago quaranta euro con carta.'], [Kit::word('pagare', 'pago'), Kit::word('quaranta'), Kit::word('la carta', 'carta'), Kit::word("l'euro", 'euro')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.computer-quarantacinque', 'The computers cost 45 euros.', ['I computer costano quarantacinque euro.'], [Kit::word('il computer', 'computer'), Kit::word('quaranta', 'quarantacinque'), Kit::form('quarantacinque')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.computer-quindici', 'Il computer ___ quindici euro.', 'The computer costs 15 euros.', 'costa', Kit::form('costa', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.borse-dieci', 'Le borse ___ dieci euro.', 'The bags cost 10 euros.', 'costano', Kit::form('costano'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.quanto-borsa', 'Anna, quanto costa la borsa?', 'Anna, how much does the bag cost?', [Kit::word('la borsa', 'borsa'), Kit::form('costa')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.sono-trenta', 'Anna, sono trenta euro. Paghi con la carta?', 'Anna, that is 30 euros. Do you pay by card? (informal you)', [Kit::word('trenta'), Kit::word('pagare', 'paghi'), Kit::word('la carta', 'carta'), Kit::word("l'euro", 'euro')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.carta-cinquanta', 'Sono cinquanta euro. Pago con la carta.', 'That is 50 euros. I pay by card.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta'), Kit::word('cinquanta'), Kit::word("l'euro", 'euro')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.negozio', [
                Kit::line('Anna', 'Paolo, quanto costa il computer?'),
                Kit::line('Paolo', 'Costa cento euro.'),
                Kit::line('Anna', 'E la borsa, Paolo?'),
                Kit::line('Paolo', 'Costa venti euro.'),
            ], [
                Kit::question('How much does the computer cost?', ['100 euros', '10 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['20 euros', '12 euros', '30 euros'], '20 euros'),
            ], [
                Kit::question('Who asks the questions?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('Does Anna ask about the bag?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il computer'), Kit::word('cento'), Kit::word('la borsa', 'borse'), Kit::word('costare', 'costa')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.carta', 'Read the conversation.', [
                Kit::line('Luca', 'Marta, quanto costano le borse?'),
                Kit::line('Marta', 'Costano quindici euro.'),
                Kit::line('Luca', 'Pago con la carta, grazie.'),
            ], [
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Luca pay?', ['By card', 'In cash'], 'By card'),
            ], [Kit::word('la borsa', 'borse'), Kit::word('costare', 'costano'), Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quanto-computer', 'Quanto costa il computer, Luca?', 'How much does the computer cost, Luca?', [self::numbers(), ['euro']], 'Costa cento euro.', [Kit::word('il computer'), Kit::word('cento')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quanto-borsa', 'Quanto costa la borsa, Marta?', 'How much does the bag cost, Marta?', [self::numbers(), ['euro']], 'Costa trenta euro.', [Kit::word('la borsa', 'borsa'), Kit::word('trenta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.paghi', 'Paghi con la carta, Anna?', 'Do you pay by card, Anna?', [['sì', 'no'], ['pago', 'carta']], 'Sì, pago con la carta.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.computer-cinquanta', 'The computers cost 50 euros.', ['I computer costano cinquanta euro.'], [Kit::word('il computer', 'computer'), Kit::word('cinquanta'), Kit::word("l'euro", 'euro'), Kit::word('costare', 'costano'), Kit::form('costano')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.computer-borsa', 'The computer costs 100 euros and the bag costs 40.', ['Il computer costa cento euro e la borsa costa quaranta.', 'Il computer costa cento euro e la borsa quaranta.', 'Il computer costa cento euro e la borsa quaranta euro.'], [Kit::word('il computer'), Kit::word('la borsa', 'borsa'), Kit::word('cento'), Kit::word('quaranta'), Kit::word('costare', 'costa'), Kit::form('costa')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pago-cinquanta', 'I pay 50 euros by card.', ['Pago cinquanta euro con la carta.', 'Io pago cinquanta euro con la carta.', 'Pago cinquanta euro con carta.', 'Io pago cinquanta euro con carta.'], [Kit::word('pagare', 'pago'), Kit::word('cinquanta'), Kit::word('la carta', 'carta'), Kit::word("l'euro", 'euro')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.borsa-trentacinque', 'The bag costs 35 euros.', ['La borsa costa trentacinque euro.'], [Kit::word('la borsa', 'borsa'), Kit::word('trenta', 'trentacinque'), Kit::word("l'euro", 'euro'), Kit::form('trentacinque')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.borse-dodici', 'Le borse ___ dodici euro.', 'The bags cost 12 euros.', 'costano', Kit::form('costano', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.computer-trenta', 'Il computer ___ trenta euro.', 'The computer costs 30 euros.', 'costa', Kit::form('costa', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.borse-quaranta', 'Le borse costano quaranta euro.', 'The bags cost 40 euros.', [Kit::word('la borsa', 'borse'), Kit::word('quaranta'), Kit::form('costano')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.sono-trenta', 'Sono trenta euro, grazie.', 'That is 30 euros, thank you.', [Kit::word('trenta'), Kit::word("l'euro", 'euro')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pago-cento', 'Sono cento euro. Pago con la carta.', 'That is 100 euros. I pay by card.', [Kit::word('pagare', 'pago'), Kit::word('la carta', 'carta'), Kit::word('cento'), Kit::word("l'euro", 'euro')], 'dictation', $set),
        ];
    }
}
