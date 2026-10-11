<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

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
    private const NUMBERS = ['un', 'une', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf', 'vingt', 'trente', 'quarante', 'cinquante', 'vingt-cinq', 'trente-cinq', 'quarante-cinq', 'cent'];

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'numbers-and-prices';
    }

    public function words(): array
    {
        return [
            new WordData('trente', cue: 'thirty'),
            new WordData('quarante', cue: 'forty'),
            new WordData('cinquante', cue: 'fifty'),
            new WordData('cent', cue: 'one hundred', note: 'Cent is exactly 100: cent euros. Careful: Dutch \'cent\' is a coin, but French cent is the number 100.'),
            new WordData('l\'euro', cue: 'euro', forms: ['euros']),
            new WordData('coûter', cue: 'to cost', forms: ['coûte', 'coûtent'], note: 'Use coûte for one thing and coûtent for several things. They sound the same, so listen for le or les.'),
            new WordData('payer', cue: 'to pay', forms: ['paie', 'paye', 'payez']),
            new WordData('la carte', cue: 'card (bank card)'),
            new WordData('le sac', cue: 'bag', forms: ['sacs']),
            new WordData('l\'ordinateur', cue: 'computer', forms: ['ordinateurs']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Combien coûte le sac ?', 'english' => 'How much does the bag cost?'],
            ['text' => 'Les ordinateurs coûtent cent euros.', 'english' => 'The computers cost 100 euros.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.sac-dix', 'Le sac ___ dix euros.', ['coûte', 'coûtent'], 'coûte', Kit::form('coûte', true), 'Le sac is one thing, so you need coûte. Coûtent is for several things. They sound the same, but you write them differently.', 'choose', 'The bag costs 10 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.ordinateurs-cent', 'Les ordinateurs ___ cent euros.', ['coûtent', 'coûte'], 'coûtent', Kit::form('coûtent', true), 'Les ordinateurs is plural, so you need coûtent. Coûte is for one thing.', 'choose', 'The computers cost 100 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.combien-sac', 'Combien ___ le sac ?', ['coûte', 'coûtent'], 'coûte', Kit::form('coûte'), 'You ask about one bag, so you need coûte. Coûtent would be for several things.', 'choose', 'How much does the bag cost?'),
            Kit::gap($stage, 'sentences.choose_gap.compter', 'Trente, quarante, ___, soixante.', ['cinquante', 'cent', 'dix'], 'cinquante', Kit::word('cinquante'), 'The tens go trente, quarante, cinquante, soixante. Cent is 100 and dix is 10, so neither fits between 40 and 60.', 'choose', 'Thirty, forty, fifty, sixty.', ['soixante' => 'sixty']),
            Kit::gap($stage, 'sentences.choose_gap.somme-cent', 'Cinquante et cinquante font ___.', ['cent', 'quarante', 'trente'], 'cent', Kit::word('cent'), '50 and 50 make 100, which is cent. Quarante is 40 and trente is 30.', 'choose', '50 and 50 make 100.', ['font' => 'make (in a sum)']),
            Kit::gap($stage, 'sentences.choose_gap.carte', 'Je paie par ___.', ['carte', 'cent', 'trente'], 'carte', Kit::word('la carte', 'carte'), 'You pay by card, la carte. Cent and trente are numbers, not things you pay with.', 'choose', 'I pay by card.'),

            Kit::typeGap($stage, 'sentences.type_gap.ordinateurs-quarante', 'Les ordinateurs ___ quarante euros.', 'The computers cost 40 euros.', 'coûtent', Kit::form('coûtent'), 'Les ordinateurs is plural, so the verb is coûtent.'),
            Kit::typeGap($stage, 'sentences.type_gap.combien-ordinateurs', 'Combien ___ les ordinateurs ?', 'How much do the computers cost?', 'coûtent', Kit::form('coûtent'), 'Les ordinateurs is plural, so the verb is coûtent.'),
            Kit::typeGap($stage, 'sentences.type_gap.quinze-euros', 'Il coûte quinze ___.', 'It costs 15 euros.', 'euros', Kit::word('l\'euro', 'euros')),
            Kit::typeGap($stage, 'sentences.type_gap.vingt-cinquante', 'Vingt-cinq et vingt-cinq font ___.', '25 and 25 make 50.', 'cinquante', Kit::word('cinquante'), null, 'write', null, ['vingt-cinq' => '25', 'font' => 'make (in a sum)']),
            Kit::typeGap($stage, 'sentences.type_gap.payez', 'Vous ___ par carte ?', 'Do you pay by card? (formal or plural you)', 'payez', Kit::word('payer', 'payez')),

            Kit::translate($stage, 'sentences.translate.sac-vingt', 'The bag costs 20 euros.', ['Le sac coûte vingt euros.'], [Kit::word('le sac', 'sac'), Kit::word('coûter', 'coûte'), Kit::word('l\'euro', 'euros'), Kit::form('coûte')]),
            Kit::translate($stage, 'sentences.translate.combien-ordinateurs', 'How much do the computers cost?', ['Combien coûtent les ordinateurs ?'], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('coûter', 'coûtent'), Kit::form('coûtent', true)]),
            Kit::translate($stage, 'sentences.translate.trente-cinq', 'It costs 35 euros.', ['Il coûte trente-cinq euros.', 'Elle coûte trente-cinq euros.'], [Kit::word('trente'), Kit::word('l\'euro', 'euros'), Kit::form('trente-cinq')]),

            Kit::build($stage, 'sentences.build.sac-quarante', 'The bag costs 40 euros.', 'Le sac coûte quarante euros.', ['coûtent'], [Kit::word('le sac', 'sac'), Kit::word('quarante'), Kit::form('coûte', true)]),
            Kit::build($stage, 'sentences.build.payez', 'Do you pay by card? (formal or plural you)', 'Vous payez par carte ?', ['de'], [Kit::word('payer', 'payez'), Kit::word('la carte', 'carte')]),
            Kit::build($stage, 'sentences.build.ordinateurs-cent', 'The computers cost 100 euros.', 'Les ordinateurs coûtent cent euros.', ['coûte'], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('cent'), Kit::form('coûtent', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.sac-dix', 'Le sac coûte dix euros.', ['The bag costs 10 euros.', 'The bag costs 20 euros.', 'The bag costs 11 euros.', 'The computer costs 10 euros.'], 'The bag costs 10 euros.', [Kit::word('le sac', 'sac'), Kit::word('l\'euro', 'euros'), Kit::form('coûte')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ordinateurs-quarante', 'Les ordinateurs coûtent quarante euros.', ['The computers cost 40 euros.', 'The computers cost 14 euros.', 'The computers cost 50 euros.', 'The computer costs 40 euros.'], 'The computers cost 40 euros.', [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('quarante'), Kit::form('coûtent')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cinquante', 'Il coûte cinquante euros.', ['It costs 50 euros.', 'It costs 15 euros.', 'It costs 100 euros.', 'It costs 30 euros.'], 'It costs 50 euros.', [Kit::word('cinquante'), Kit::word('coûter', 'coûte')]),
            Kit::listenType($stage, 'sentences.listen_type.paie', 'Je paie par carte.', 'I pay by card.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte')], alsoAccepted: ['Je paye par carte.']),
            Kit::listenType($stage, 'sentences.listen_type.combien-sac', 'Combien coûte le sac ?', 'How much does the bag cost?', [Kit::word('le sac', 'sac'), Kit::form('coûte')]),
            Kit::listenType($stage, 'sentences.listen_type.trente-cinq', 'Il coûte trente-cinq euros.', 'It costs 35 euros.', [Kit::word('trente'), Kit::word('l\'euro', 'euros'), Kit::form('trente-cinq')]),
            Kit::listenType($stage, 'sentences.listen_type.sacs-trente', 'Les sacs coûtent trente euros.', 'The bags cost 30 euros.', [Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûtent'), Kit::form('coûtent')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.quarante', 'Il coûte quarante euros.', 'It costs 40 euros.', [Kit::word('quarante'), Kit::word('l\'euro', 'euros'), Kit::word('coûter', 'coûte')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ordinateurs-cent', 'Les ordinateurs coûtent cent euros.', 'The computers cost 100 euros.', [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('cent'), Kit::form('coûtent')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.payez', 'Vous payez par carte ?', 'Do you pay by card? (formal or plural you)', [Kit::word('payer', 'payez'), Kit::word('la carte', 'carte')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sacs-cinquante', 'Les sacs coûtent cinquante euros.', 'The bags cost 50 euros.', [Kit::word('le sac', 'sacs'), Kit::word('cinquante'), Kit::form('coûtent')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.combien-sac', 'Combien coûte le sac ?', 'How much does the bag cost?', [self::NUMBERS, ['euro', 'euros']], 'Il coûte quinze euros.', [Kit::word('le sac', 'sac'), Kit::word('l\'euro', 'euros'), Kit::word('coûter', 'coûte')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.combien-ordinateurs', 'Combien coûtent les ordinateurs ?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Ils coûtent cent euros.', [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('cent'), Kit::form('coûtent')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.payez', 'Vous payez par carte ?', 'Do you pay by card? (formal or plural you)', [['oui', 'non'], ['paie', 'paye', 'carte']], 'Oui, je paie par carte.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.magasin', 'Read the conversation in the shop.', [
                Kit::line('Anne', 'Bonjour. Combien coûtent les ordinateurs ?'),
                Kit::line('Paul', 'Ils coûtent cent euros.'),
                Kit::line('Anne', 'Et le sac ?'),
                Kit::line('Paul', 'Il coûte trente euros. Vous payez par carte ?'),
                Kit::line('Anne', 'Oui, je paie par carte.'),
            ], [
                Kit::question('How much do the computers cost?', ['100 euros', '30 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['30 euros', '13 euros', '40 euros'], '30 euros'),
                Kit::question('How does Anne pay?', ['By card', 'In cash', 'She does not pay.'], 'By card'),
            ], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('coûter', 'coûtent'), Kit::word('l\'euro', 'euros'), Kit::word('le sac', 'sac'), Kit::word('trente'), Kit::word('cent'), Kit::word('payer', 'payez'), Kit::word('la carte', 'carte')], 'read'),
            Kit::gap($stage, 'task.choose_gap.sac-ordinateurs', 'Le sac coûte un euro et les ordinateurs ___ cent euros.', ['coûtent', 'coûte'], 'coûtent', Kit::form('coûtent', true), 'Les ordinateurs is plural, so you need coûtent. Le sac is one thing, which is why it has coûte.', 'read', 'The bag costs 1 euro and the computers cost 100 euros.'),
            Kit::gap($stage, 'task.choose_gap.un-euro', 'Le sac coûte un ___.', ['euro', 'euros'], 'euro', Kit::word('l\'euro', 'euro'), 'Un means one, so the word stays euro. Euros is for two or more.', 'read', 'The bag costs 1 euro.'),

            Kit::transform($stage, 'task.transform.sacs', 'Now say it for several things.', 'Le sac coûte vingt euros.', ['Les sacs coûtent vingt euros.'], [Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûtent'), Kit::form('coûtent', true)]),
            Kit::transform($stage, 'task.transform.combien-ordinateur', 'Now ask how much it costs.', 'L\'ordinateur coûte cent euros.', ['Combien coûte l\'ordinateur ?'], [Kit::word('l\'ordinateur'), Kit::word('coûter', 'coûte'), Kit::form('coûte')]),
            Kit::transform($stage, 'task.transform.cinquante', 'Now make it 50 euros.', 'Le sac coûte quarante euros.', ['Le sac coûte cinquante euros.'], [Kit::word('cinquante'), Kit::word('le sac', 'sac')]),
            Kit::writeGuided($stage, 'task.write_guided.sac-carte', 'Say that the bag costs 30 euros and that you pay by card.', ['sac', 'coûte', 'trente', 'paie', 'carte'], 'Le sac coûte trente euros. Je paie par carte.', [
                ['forms' => ['sac'], 'term' => 'le sac'],
                ['forms' => ['coûte'], 'term' => 'coûter'],
                ['forms' => ['trente'], 'term' => 'trente'],
                ['forms' => ['paie', 'paye'], 'term' => 'payer'],
                ['forms' => ['carte'], 'term' => 'la carte'],
            ], [Kit::word('le sac', 'sac'), Kit::word('coûter', 'coûte'), Kit::word('trente'), Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte')]),
            Kit::writeGuided($stage, 'task.write_guided.ordinateurs-sacs', 'Say that the computers cost 100 euros and the bags cost 40 euros.', ['ordinateurs', 'coûtent', 'cent', 'sacs', 'quarante'], 'Les ordinateurs coûtent cent euros et les sacs coûtent quarante euros.', [
                ['forms' => ['ordinateurs'], 'term' => 'l\'ordinateur'],
                ['forms' => ['coûtent'], 'term' => 'coûter'],
                ['forms' => ['cent'], 'term' => 'cent'],
                ['forms' => ['sacs'], 'term' => 'le sac'],
                ['forms' => ['quarante'], 'term' => 'quarante'],
            ], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('coûter', 'coûtent'), Kit::word('cent'), Kit::word('le sac', 'sacs'), Kit::word('quarante')]),
            Kit::build($stage, 'task.build.sacs-cinquante', 'The bags cost 50 euros.', 'Les sacs coûtent cinquante euros.', ['coûte', 'avec'], [Kit::word('le sac', 'sacs'), Kit::word('cinquante'), Kit::form('coûtent', true)], 'write'),
            Kit::build($stage, 'task.build.quarante-cinq', 'It costs 45 euros.', 'Il coûte quarante-cinq euros.', ['coûtent', 'de'], [Kit::word('quarante'), Kit::word('l\'euro', 'euros'), Kit::form('quarante-cinq')], 'write'),
            Kit::build($stage, 'task.build.ordinateur-cent', 'The computer costs 100 euros.', 'L\'ordinateur coûte cent euros.', ['coûtent', 'les'], [Kit::word('l\'ordinateur'), Kit::word('cent'), Kit::form('coûte', true)], 'write'),
            Kit::translate($stage, 'task.translate.sacs-ordinateur', 'The bags cost 40 euros and the computer costs 100 euros.', ['Les sacs coûtent quarante euros et l\'ordinateur coûte cent euros.'], [Kit::word('le sac', 'sacs'), Kit::word('l\'ordinateur'), Kit::word('quarante'), Kit::word('cent'), Kit::form('coûtent', true)], 'write'),
            Kit::translate($stage, 'task.translate.paie-trente-cinq', 'I pay 35 euros by card.', ['Je paie trente-cinq euros par carte.', 'Je paye trente-cinq euros par carte.', 'Je paie trente-cinq euros avec la carte.', 'Je paye trente-cinq euros avec la carte.'], [Kit::word('payer', 'paie', ['paye']), Kit::word('trente'), Kit::word('la carte', 'carte'), Kit::word('l\'euro', 'euros')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.magasin', [
                Kit::line('Marie', 'Bonjour, Luc. Combien coûte l\'ordinateur ?'),
                Kit::line('Luc', 'L\'ordinateur coûte quarante euros.'),
                Kit::line('Marie', 'Et les sacs ?'),
                Kit::line('Luc', 'Les sacs coûtent quinze euros.'),
                Kit::line('Marie', 'Je paie par carte, s\'il vous plaît.'),
            ], [
                Kit::question('How much does the computer cost?', ['40 euros', '14 euros', '50 euros'], '40 euros'),
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Marie pay?', ['By card', 'In cash', 'She does not say.'], 'By card'),
            ], [
                Kit::question('Who asks the price of the computer?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Does Marie ask about the bags?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('l\'ordinateur'), Kit::word('quarante'), Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûte'), Kit::word('payer', 'paie'), Kit::word('la carte', 'carte')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ordinateur-quarante-cinq', 'L\'ordinateur coûte quarante-cinq euros.', 'The computer costs 45 euros.', [Kit::word('l\'ordinateur'), Kit::word('quarante'), Kit::form('quarante-cinq')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.sac-cinquante', 'Le sac coûte cinquante euros.', 'The bag costs 50 euros.', [Kit::word('le sac', 'sac'), Kit::word('cinquante'), Kit::form('coûte')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paie-cent', 'Je paie cent euros par carte.', 'I pay 100 euros by card.', [Kit::word('payer', 'paie', ['paye']), Kit::word('cent'), Kit::word('la carte', 'carte'), Kit::word('l\'euro', 'euros')], 'listen', alsoAccepted: ['Je paye cent euros par carte.']),

            Kit::speakAnswer($stage, 'task.speak_answer.combien-ordinateur', 'Combien coûte l\'ordinateur ?', 'How much does the computer cost?', [self::NUMBERS, ['euro', 'euros']], 'Il coûte cent euros.', [Kit::word('l\'ordinateur'), Kit::word('cent'), Kit::word('coûter', 'coûte')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.combien-ordinateurs', 'Combien coûtent les ordinateurs ?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Ils coûtent quarante euros.', [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('quarante'), Kit::word('coûter', 'coûtent')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.comment-payez', 'Vous payez comment ?', 'How do you pay? (formal or plural you)', [['paie', 'paye', 'par'], ['carte']], 'Je paie par carte.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cest-combien', 'C\'est combien, s\'il vous plaît ?', 'How much is it, please?', [self::NUMBERS, ['euro', 'euros']], 'C\'est trente euros.', [Kit::word('trente'), Kit::word('l\'euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.sacs-quinze', 'Les sacs coûtent quinze euros.', 'The bags cost 15 euros.', [Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûtent'), Kit::word('l\'euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.sac-quarante-cinq', 'Le sac coûte quarante-cinq euros.', 'The bag costs 45 euros.', [Kit::word('le sac', 'sac'), Kit::word('quarante'), Kit::word('coûter', 'coûte')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.ordinateur-sac', 'The computer costs 100 euros and the bag costs 30.', ['L\'ordinateur coûte cent euros et le sac coûte trente.', 'L\'ordinateur coûte cent euros et le sac trente.', 'L\'ordinateur coûte cent euros et le sac trente euros.', 'L\'ordinateur coûte cent euros et le sac coûte trente euros.'], [Kit::word('l\'ordinateur'), Kit::word('le sac', 'sac'), Kit::word('cent'), Kit::word('trente'), Kit::word('coûter', 'coûte'), Kit::form('coûte')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.combien-sacs', 'How much do the bags cost?', ['Combien coûtent les sacs ?'], [Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûtent'), Kit::form('coûtent', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paie-quarante', 'I pay 40 euros by card.', ['Je paie quarante euros par carte.', 'Je paye quarante euros par carte.', 'Je paie quarante euros avec la carte.', 'Je paye quarante euros avec la carte.'], [Kit::word('payer', 'paie', ['paye']), Kit::word('quarante'), Kit::word('la carte', 'carte'), Kit::word('l\'euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ordinateurs-quarante-cinq', 'The computers cost 45 euros.', ['Les ordinateurs coûtent quarante-cinq euros.'], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('quarante'), Kit::form('quarante-cinq')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ordinateur-quinze', 'L\'ordinateur ___ quinze euros.', 'The computer costs 15 euros.', 'coûte', Kit::form('coûte', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sacs-dix', 'Les sacs ___ dix euros.', 'The bags cost 10 euros.', 'coûtent', Kit::form('coûtent'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.combien-sac', 'Anne, combien coûte le sac ?', 'Anne, how much does the bag cost?', [Kit::word('le sac', 'sac'), Kit::form('coûte')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.trente-payez', 'Il coûte trente euros. Vous payez par carte ?', 'It costs 30 euros. Do you pay by card? (formal or plural you)', [Kit::word('trente'), Kit::word('payer', 'payez'), Kit::word('la carte', 'carte'), Kit::word('l\'euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.carte-cinquante', 'Il coûte cinquante euros. Je paie par carte.', 'It costs 50 euros. I pay by card.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte'), Kit::word('cinquante'), Kit::word('l\'euro', 'euros')], 'dictation', $set, ['Il coûte cinquante euros. Je paye par carte.']),
            Kit::listenPassage($stage, 'check.a.listen_passage.magasin', [
                Kit::line('Anne', 'Paul, combien coûte l\'ordinateur ?'),
                Kit::line('Paul', 'Il coûte cent euros.'),
                Kit::line('Anne', 'Et le sac, Paul ?'),
                Kit::line('Paul', 'Il coûte vingt euros.'),
            ], [
                Kit::question('How much does the computer cost?', ['100 euros', '10 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['20 euros', '12 euros', '30 euros'], '20 euros'),
            ], [
                Kit::question('Who asks the questions?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('Does Anne ask about the bag?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('l\'ordinateur'), Kit::word('cent'), Kit::word('le sac', 'sac'), Kit::word('coûter', 'coûte')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.carte', 'Read the conversation.', [
                Kit::line('Luc', 'Marie, combien coûtent les sacs ?'),
                Kit::line('Marie', 'Ils coûtent quinze euros.'),
                Kit::line('Luc', 'Je paie par carte, merci.'),
            ], [
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Luc pay?', ['By card', 'In cash'], 'By card'),
            ], [Kit::word('le sac', 'sacs'), Kit::word('coûter', 'coûtent'), Kit::word('payer', 'paie'), Kit::word('la carte', 'carte')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.combien-ordinateur', 'Combien coûte l\'ordinateur, Luc ?', 'How much does the computer cost, Luc?', [self::NUMBERS, ['euro', 'euros']], 'Il coûte cent euros.', [Kit::word('l\'ordinateur'), Kit::word('cent')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.combien-sac', 'Combien coûte le sac, Marie ?', 'How much does the bag cost, Marie?', [self::NUMBERS, ['euro', 'euros']], 'Il coûte trente euros.', [Kit::word('le sac', 'sac'), Kit::word('trente')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.payez', 'Anne, vous payez par carte ?', 'Anne, do you pay by card?', [['oui', 'non'], ['paie', 'paye', 'carte']], 'Oui, je paie par carte.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ordinateurs-cinquante', 'The computers cost 50 euros.', ['Les ordinateurs coûtent cinquante euros.'], [Kit::word('l\'ordinateur', 'ordinateurs'), Kit::word('cinquante'), Kit::word('l\'euro', 'euros'), Kit::word('coûter', 'coûtent'), Kit::form('coûtent')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ordinateur-sac', 'The computer costs 100 euros and the bag costs 40.', ['L\'ordinateur coûte cent euros et le sac coûte quarante.', 'L\'ordinateur coûte cent euros et le sac quarante.', 'L\'ordinateur coûte cent euros et le sac quarante euros.', 'L\'ordinateur coûte cent euros et le sac coûte quarante euros.'], [Kit::word('l\'ordinateur'), Kit::word('le sac', 'sac'), Kit::word('cent'), Kit::word('quarante'), Kit::word('coûter', 'coûte'), Kit::form('coûte')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.paie-cinquante', 'I pay 50 euros by card.', ['Je paie cinquante euros par carte.', 'Je paye cinquante euros par carte.', 'Je paie cinquante euros avec la carte.', 'Je paye cinquante euros avec la carte.'], [Kit::word('payer', 'paie', ['paye']), Kit::word('cinquante'), Kit::word('la carte', 'carte'), Kit::word('l\'euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sac-trente-cinq', 'The bag costs 35 euros.', ['Le sac coûte trente-cinq euros.'], [Kit::word('le sac', 'sac'), Kit::word('trente'), Kit::word('l\'euro', 'euros'), Kit::form('trente-cinq')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.sacs-douze', 'Les sacs ___ douze euros.', 'The bags cost 12 euros.', 'coûtent', Kit::form('coûtent', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ordinateur-trente', 'L\'ordinateur ___ trente euros.', 'The computer costs 30 euros.', 'coûte', Kit::form('coûte', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.sacs-quarante', 'Les sacs coûtent quarante euros.', 'The bags cost 40 euros.', [Kit::word('le sac', 'sacs'), Kit::word('quarante'), Kit::form('coûtent')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.trente-svp', 'Trente euros, s\'il vous plaît.', '30 euros, please.', [Kit::word('trente'), Kit::word('l\'euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.paie-cent', 'Cent euros. Je paie par carte.', '100 euros. I pay by card.', [Kit::word('payer', 'paie', ['paye']), Kit::word('la carte', 'carte'), Kit::word('cent'), Kit::word('l\'euro', 'euros')], 'dictation', $set, ['Cent euros. Je paye par carte.']),
        ];
    }
}
