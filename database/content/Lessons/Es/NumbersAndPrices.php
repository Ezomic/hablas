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

final class NumbersAndPrices implements UnitContent
{
    private const NUMBERS = ['un', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'cien'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'numbers-and-prices';
    }

    public function words(): array
    {
        return [
            new WordData('treinta', cue: 'thirty'),
            new WordData('cuarenta', cue: 'forty'),
            new WordData('cincuenta', cue: 'fifty'),
            new WordData('cien', cue: 'one hundred', note: 'Cien is exactly 100: cien euros.'),
            new WordData('el euro', cue: 'euro', forms: ['euros']),
            new WordData('costar', cue: 'to cost', forms: ['cuesta', 'cuestan'], note: 'Use cuesta for one thing and cuestan for several things.'),
            new WordData('pagar', cue: 'to pay', forms: ['pago', 'pagas']),
            new WordData('la tarjeta', cue: 'card (bank card)'),
            new WordData('la bolsa', cue: 'bag', forms: ['bolsas']),
            new WordData('el ordenador', cue: 'computer', forms: ['ordenadores']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => '¿Cuánto cuesta la bolsa?', 'english' => 'How much does the bag cost?'],
            ['text' => 'Los ordenadores cuestan cien euros.', 'english' => 'The computers cost 100 euros.'],
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
            Kit::gap($stage, 'sentences.choose_gap.bolsa-diez', 'La bolsa ___ diez euros.', ['cuesta', 'cuestan'], 'cuesta', Kit::form('cuesta', true), 'La bolsa is one thing, so you need cuesta. Cuestan is for several things.', 'choose', 'The bag costs 10 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.telefonos-cien', 'Los ordenadores ___ cien euros.', ['cuestan', 'cuesta'], 'cuestan', Kit::form('cuestan', true), 'Los ordenadores is plural, so you need cuestan. Cuesta is for one thing.', 'choose', 'The computers cost 100 euros.'),
            Kit::gap($stage, 'sentences.choose_gap.cuanto-bolsa', '¿Cuánto ___ la bolsa?', ['cuesta', 'cuestan'], 'cuesta', Kit::form('cuesta'), 'You ask about one bag, so you need cuesta. Cuestan would be for several things.', 'choose', 'How much does the bag cost?'),
            Kit::gap($stage, 'sentences.choose_gap.contar', 'Treinta, cuarenta, ___, sesenta.', ['cincuenta', 'cien', 'diez'], 'cincuenta', Kit::word('cincuenta'), 'The tens go treinta, cuarenta, cincuenta, sesenta. Cien is 100 and diez is 10, so neither fits between 40 and 60.', 'choose', 'Thirty, forty, fifty, sixty.', ['sesenta' => 'sixty']),
            Kit::gap($stage, 'sentences.choose_gap.suma-cien', 'Cincuenta y cincuenta son ___.', ['cien', 'cuarenta', 'treinta'], 'cien', Kit::word('cien'), '50 and 50 make 100, which is cien. Cuarenta is 40 and treinta is 30.', 'choose', '50 and 50 make 100.'),
            Kit::gap($stage, 'sentences.choose_gap.tarjeta', 'Pago con la ___.', ['tarjeta', 'cien', 'treinta'], 'tarjeta', Kit::word('la tarjeta', 'tarjeta'), 'You pay with a card, la tarjeta. Cien and treinta are numbers, not things you pay with.', 'choose', 'I pay with the card.'),

            Kit::typeGap($stage, 'sentences.type_gap.telefonos-cuarenta', 'Los ordenadores ___ cuarenta euros.', 'The computers cost 40 euros.', 'cuestan', Kit::form('cuestan'), 'Los ordenadores is plural, so the verb is cuestan.'),
            Kit::typeGap($stage, 'sentences.type_gap.cuanto-bolsa', '¿Cuánto ___ la bolsa?', 'How much does the bag cost?', 'cuesta', Kit::form('cuesta'), 'One bag, so the verb is cuesta.'),
            Kit::typeGap($stage, 'sentences.type_gap.quince-euros', 'Cuesta quince ___.', 'It costs 15 euros.', 'euros', Kit::word('el euro', 'euros')),
            Kit::typeGap($stage, 'sentences.type_gap.veinte-treinta', 'Veinticinco y veinticinco son ___.', '25 and 25 make 50.', 'cincuenta', Kit::word('cincuenta'), null, 'write', null, ['veinticinco' => '25']),
            Kit::typeGap($stage, 'sentences.type_gap.pagas', '¿___ con tarjeta?', 'Do you pay by card? (informal you, one person)', 'Pagas', Kit::word('pagar', 'pagas')),

            Kit::translate($stage, 'sentences.translate.bolsa-veinte', 'The bag costs 20 euros.', ['La bolsa cuesta veinte euros.'], [Kit::word('la bolsa', 'bolsa'), Kit::word('costar', 'cuesta'), Kit::word('el euro', 'euros'), Kit::form('cuesta')]),
            Kit::translate($stage, 'sentences.translate.cuanto-telefonos', 'How much do the computers cost?', ['¿Cuánto cuestan los ordenadores?'], [Kit::word('el ordenador', 'ordenadores'), Kit::word('costar', 'cuestan'), Kit::form('cuestan', true)]),
            Kit::translate($stage, 'sentences.translate.treinta-y-cinco', 'It costs 35 euros.', ['Cuesta treinta y cinco euros.'], [Kit::word('treinta'), Kit::word('el euro', 'euros'), Kit::form('treinta y cinco')]),

            Kit::build($stage, 'sentences.build.bolsa-cuarenta', 'The bag costs 40 euros.', 'La bolsa cuesta cuarenta euros.', ['cuestan'], [Kit::word('la bolsa', 'bolsa'), Kit::word('cuarenta'), Kit::form('cuesta', true)]),
            Kit::build($stage, 'sentences.build.pagas', 'Do you pay by card? (informal you)', '¿Pagas con tarjeta?', ['de'], [Kit::word('pagar', 'pagas'), Kit::word('la tarjeta', 'tarjeta')]),
            Kit::build($stage, 'sentences.build.telefonos-cien', 'The computers cost 100 euros.', 'Los ordenadores cuestan cien euros.', ['cuesta'], [Kit::word('el ordenador', 'ordenadores'), Kit::word('cien'), Kit::form('cuestan', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.bolsa-diez', 'La bolsa cuesta diez euros.', ['The bag costs 10 euros.', 'The bag costs 20 euros.', 'The bags cost 10 euros.', 'The computer costs 10 euros.'], 'The bag costs 10 euros.', [Kit::word('la bolsa', 'bolsa'), Kit::word('el euro', 'euros'), Kit::form('cuesta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.telefonos-cuarenta', 'Los ordenadores cuestan cuarenta euros.', ['The computers cost 40 euros.', 'The computers cost 14 euros.', 'The computers cost 50 euros.', 'The computer costs 40 euros.'], 'The computers cost 40 euros.', [Kit::word('el ordenador', 'ordenadores'), Kit::word('cuarenta'), Kit::form('cuestan')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cincuenta', 'Cuesta cincuenta euros.', ['It costs 50 euros.', 'It costs 15 euros.', 'It costs 100 euros.', 'It costs 30 euros.'], 'It costs 50 euros.', [Kit::word('cincuenta'), Kit::word('costar', 'cuesta')]),
            Kit::listenType($stage, 'sentences.listen_type.pago', 'Pago con tarjeta.', 'I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')]),
            Kit::listenType($stage, 'sentences.listen_type.cuanto-bolsa', '¿Cuánto cuesta la bolsa?', 'How much does the bag cost?', [Kit::word('la bolsa', 'bolsa'), Kit::form('cuesta')]),
            Kit::listenType($stage, 'sentences.listen_type.treinta-y-cinco', 'Son treinta y cinco euros.', 'That is 35 euros.', [Kit::word('treinta'), Kit::word('el euro', 'euros'), Kit::form('treinta y cinco')]),
            Kit::listenType($stage, 'sentences.listen_type.bolsas-treinta', 'Las bolsas cuestan treinta euros.', 'The bags cost 30 euros.', [Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuestan'), Kit::form('cuestan')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cuarenta', 'Cuesta cuarenta euros.', 'It costs 40 euros.', [Kit::word('cuarenta'), Kit::word('el euro', 'euros'), Kit::word('costar', 'cuesta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.telefonos-cien', 'Los ordenadores cuestan cien euros.', 'The computers cost 100 euros.', [Kit::word('el ordenador', 'ordenadores'), Kit::word('cien'), Kit::form('cuestan')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pagas', '¿Pagas con tarjeta?', 'Do you pay by card? (informal you)', [Kit::word('pagar', 'pagas'), Kit::word('la tarjeta', 'tarjeta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bolsas-cincuenta', 'Las bolsas cuestan cincuenta euros.', 'The bags cost 50 euros.', [Kit::word('la bolsa', 'bolsas'), Kit::word('cincuenta'), Kit::form('cuestan')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cuanto-bolsa', '¿Cuánto cuesta la bolsa?', 'How much does the bag cost?', [self::NUMBERS, ['euro', 'euros']], 'Cuesta quince euros.', [Kit::word('la bolsa', 'bolsa'), Kit::word('el euro', 'euros'), Kit::word('costar', 'cuesta')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cuanto-telefonos', '¿Cuánto cuestan los ordenadores?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Cuestan cien euros.', [Kit::word('el ordenador', 'ordenadores'), Kit::word('cien'), Kit::form('cuestan')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pagas', '¿Pagas con tarjeta?', 'Do you pay by card?', [['sí', 'no'], ['pago', 'tarjeta']], 'Sí, pago con tarjeta.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tienda', 'Read the conversation in the shop.', [
                Kit::line('Ana', 'Buenos días. ¿Cuánto cuestan los ordenadores?'),
                Kit::line('Pablo', 'Cuestan cien euros.'),
                Kit::line('Ana', '¿Y la bolsa?'),
                Kit::line('Pablo', 'Cuesta treinta euros. ¿Pagas con tarjeta?'),
                Kit::line('Ana', 'Sí, pago con tarjeta.'),
            ], [
                Kit::question('How much do the computers cost?', ['100 euros', '30 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['30 euros', '13 euros', '40 euros'], '30 euros'),
                Kit::question('How does Ana pay?', ['By card', 'In cash', 'She does not pay.'], 'By card'),
            ], [Kit::word('el ordenador', 'ordenadores'), Kit::word('costar', 'cuestan'), Kit::word('el euro', 'euros'), Kit::word('la bolsa', 'bolsa'), Kit::word('treinta'), Kit::word('cien'), Kit::word('pagar', 'pagas'), Kit::word('la tarjeta', 'tarjeta')], 'read'),
            Kit::gap($stage, 'task.choose_gap.bolsa-telefonos', 'La bolsa cuesta un euro y los ordenadores ___ cien euros.', ['cuestan', 'cuesta'], 'cuestan', Kit::form('cuestan', true), 'Los ordenadores is plural, so you need cuestan. La bolsa is one thing, which is why it has cuesta.', 'read', 'The bag costs 1 euro and the computers cost 100 euros.'),
            Kit::gap($stage, 'task.choose_gap.un-euro', 'La bolsa cuesta un ___.', ['euro', 'euros'], 'euro', Kit::word('el euro', 'euro'), 'Un means one, so the word stays euro. Euros is for two or more.', 'read', 'The bag costs 1 euro.'),

            Kit::transform($stage, 'task.transform.bolsas', 'Now say it for several things.', 'La bolsa cuesta veinte euros.', ['Las bolsas cuestan veinte euros.'], [Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuestan'), Kit::form('cuestan', true)]),
            Kit::transform($stage, 'task.transform.cuanto-telefono', 'Now ask how much it costs.', 'El ordenador cuesta cien euros.', ['¿Cuánto cuesta el ordenador?'], [Kit::word('el ordenador'), Kit::word('costar', 'cuesta'), Kit::form('cuesta')]),
            Kit::transform($stage, 'task.transform.cincuenta', 'Now make it 50 euros.', 'La bolsa cuesta cuarenta euros.', ['La bolsa cuesta cincuenta euros.'], [Kit::word('cincuenta'), Kit::word('la bolsa', 'bolsa')]),
            Kit::writeGuided($stage, 'task.write_guided.bolsa-tarjeta', 'Say that the bag costs 30 euros and that you pay by card.', ['bolsa', 'cuesta', 'treinta', 'pago', 'tarjeta'], 'La bolsa cuesta treinta euros. Pago con tarjeta.', [
                ['forms' => ['bolsa'], 'term' => 'la bolsa'],
                ['forms' => ['cuesta'], 'term' => 'costar'],
                ['forms' => ['treinta'], 'term' => 'treinta'],
                ['forms' => ['pago'], 'term' => 'pagar'],
                ['forms' => ['tarjeta'], 'term' => 'la tarjeta'],
            ], [Kit::word('la bolsa', 'bolsa'), Kit::word('costar', 'cuesta'), Kit::word('treinta'), Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')]),
            Kit::writeGuided($stage, 'task.write_guided.telefonos-bolsas', 'Say that the computers cost 100 euros and the bags cost 40 euros.', ['ordenadores', 'cuestan', 'cien', 'bolsas', 'cuarenta'], 'Los ordenadores cuestan cien euros y las bolsas cuestan cuarenta euros.', [
                ['forms' => ['ordenadores'], 'term' => 'el ordenador'],
                ['forms' => ['cuestan'], 'term' => 'costar'],
                ['forms' => ['cien'], 'term' => 'cien'],
                ['forms' => ['bolsas'], 'term' => 'la bolsa'],
                ['forms' => ['cuarenta'], 'term' => 'cuarenta'],
            ], [Kit::word('el ordenador', 'ordenadores'), Kit::word('costar', 'cuestan'), Kit::word('cien'), Kit::word('la bolsa', 'bolsas'), Kit::word('cuarenta')]),
            Kit::build($stage, 'task.build.bolsas-cincuenta', 'The bags cost 50 euros.', 'Las bolsas cuestan cincuenta euros.', ['cuesta', 'con'], [Kit::word('la bolsa', 'bolsas'), Kit::word('cincuenta'), Kit::form('cuestan', true)], 'write'),
            Kit::build($stage, 'task.build.cuarenta-y-cinco', 'It costs 45 euros.', 'Cuesta cuarenta y cinco euros.', ['cuestan', 'de'], [Kit::word('cuarenta'), Kit::word('el euro', 'euros'), Kit::form('cuarenta y cinco')], 'write'),
            Kit::build($stage, 'task.build.telefono-cien', 'The computer costs 100 euros.', 'El ordenador cuesta cien euros.', ['cuestan', 'los'], [Kit::word('el ordenador'), Kit::word('cien'), Kit::form('cuesta', true)], 'write'),
            Kit::translate($stage, 'task.translate.bolsas-telefono', 'The bags cost 40 euros and the computer costs 100 euros.', ['Las bolsas cuestan cuarenta euros y el ordenador cuesta cien euros.'], [Kit::word('la bolsa', 'bolsas'), Kit::word('el ordenador'), Kit::word('cuarenta'), Kit::word('cien'), Kit::form('cuestan', true)], 'write'),
            Kit::translate($stage, 'task.translate.pago-treinta-y-cinco', 'I pay 35 euros by card.', ['Pago treinta y cinco euros con tarjeta.', 'Yo pago treinta y cinco euros con tarjeta.', 'Pago treinta y cinco euros con la tarjeta.', 'Yo pago treinta y cinco euros con la tarjeta.'], [Kit::word('pagar', 'pago'), Kit::word('treinta'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('el euro', 'euros')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.tienda', [
                Kit::line('Marta', 'Hola, Luis. ¿Cuánto cuesta el ordenador?'),
                Kit::line('Luis', 'El ordenador cuesta cuarenta euros.'),
                Kit::line('Marta', '¿Y las bolsas?'),
                Kit::line('Luis', 'Las bolsas cuestan quince euros.'),
                Kit::line('Marta', 'Pago con tarjeta, por favor.'),
            ], [
                Kit::question('How much does the computer cost?', ['40 euros', '14 euros', '50 euros'], '40 euros'),
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Marta pay?', ['By card', 'In cash', 'She does not say.'], 'By card'),
            ], [
                Kit::question('Who asks the price of the computer?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Does Marta ask about the bags?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el ordenador'), Kit::word('cuarenta'), Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuesta'), Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.telefono-cuarenta-y-cinco', 'El ordenador cuesta cuarenta y cinco euros.', 'The computer costs 45 euros.', [Kit::word('el ordenador'), Kit::word('cuarenta'), Kit::form('cuarenta y cinco')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.bolsa-cincuenta', 'La bolsa cuesta cincuenta euros.', 'The bag costs 50 euros.', [Kit::word('la bolsa', 'bolsa'), Kit::word('cincuenta'), Kit::form('cuesta')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pago-cien', 'Pago cien euros con tarjeta.', 'I pay 100 euros by card.', [Kit::word('pagar', 'pago'), Kit::word('cien'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('el euro', 'euros')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.cuanto-telefono', '¿Cuánto cuesta el ordenador?', 'How much does the computer cost?', [self::NUMBERS, ['euro', 'euros']], 'Cuesta cien euros.', [Kit::word('el ordenador'), Kit::word('cien'), Kit::word('costar', 'cuesta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cuanto-telefonos', '¿Cuánto cuestan los ordenadores?', 'How much do the computers cost?', [self::NUMBERS, ['euro', 'euros']], 'Cuestan cuarenta euros.', [Kit::word('el ordenador', 'ordenadores'), Kit::word('cuarenta'), Kit::word('costar', 'cuestan')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.como-pagas', '¿Cómo pagas?', 'How do you pay? (informal you)', [['pago', 'con'], ['tarjeta']], 'Pago con tarjeta.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cuanto-es', '¿Cuánto es, por favor?', 'How much is it, please?', [self::NUMBERS, ['euro', 'euros']], 'Son treinta euros.', [Kit::word('treinta'), Kit::word('el euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bolsas-quince', 'Las bolsas cuestan quince euros.', 'The bags cost 15 euros.', [Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuestan'), Kit::word('el euro', 'euros')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bolsa-cuarenta-y-cinco', 'La bolsa cuesta cuarenta y cinco euros.', 'The bag costs 45 euros.', [Kit::word('la bolsa', 'bolsa'), Kit::word('cuarenta'), Kit::word('costar', 'cuesta')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.telefono-bolsa', 'The computer costs 100 euros and the bag costs 30.', ['El ordenador cuesta cien euros y la bolsa cuesta treinta.', 'El ordenador cuesta cien euros y la bolsa treinta.', 'El ordenador cuesta cien euros y la bolsa treinta euros.'], [Kit::word('el ordenador'), Kit::word('la bolsa', 'bolsa'), Kit::word('cien'), Kit::word('treinta'), Kit::word('costar', 'cuesta'), Kit::form('cuesta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.cuanto-bolsas', 'How much do the bags cost?', ['¿Cuánto cuestan las bolsas?'], [Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuestan'), Kit::form('cuestan', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pago-cuarenta', 'I pay 40 euros by card.', ['Pago cuarenta euros con tarjeta.', 'Yo pago cuarenta euros con tarjeta.', 'Pago cuarenta euros con la tarjeta.', 'Yo pago cuarenta euros con la tarjeta.'], [Kit::word('pagar', 'pago'), Kit::word('cuarenta'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('el euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.telefonos-cuarenta-y-cinco', 'The computers cost 45 euros.', ['Los ordenadores cuestan cuarenta y cinco euros.'], [Kit::word('el ordenador', 'ordenadores'), Kit::word('cuarenta'), Kit::form('cuarenta y cinco')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.telefono-cien', 'El ordenador ___ quince euros.', 'The computer costs 15 euros.', 'cuesta', Kit::form('cuesta', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.bolsas-diez', 'Las bolsas ___ diez euros.', 'The bags cost 10 euros.', 'cuestan', Kit::form('cuestan'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.cuanto-bolsa', 'Ana, ¿cuánto cuesta la bolsa?', 'Ana, how much does the bag cost?', [Kit::word('la bolsa', 'bolsa'), Kit::form('cuesta')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.son-treinta-o-cincuenta', 'Son treinta euros. ¿Pagas con tarjeta?', 'That is 30 euros. Do you pay by card? (informal you)', [Kit::word('treinta'), Kit::word('pagar', 'pagas'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('el euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.tarjeta-cincuenta-o-cien', 'Son cincuenta euros. Pago con tarjeta.', 'That is 50 euros. I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('cincuenta'), Kit::word('el euro', 'euros')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.tienda', [
                Kit::line('Ana', 'Pablo, ¿cuánto cuesta el ordenador?'),
                Kit::line('Pablo', 'Cuesta cien euros.'),
                Kit::line('Ana', '¿Y la bolsa, Pablo?'),
                Kit::line('Pablo', 'Cuesta veinte euros.'),
            ], [
                Kit::question('How much does the computer cost?', ['100 euros', '10 euros', '50 euros'], '100 euros'),
                Kit::question('How much does the bag cost?', ['20 euros', '12 euros', '30 euros'], '20 euros'),
            ], [
                Kit::question('Who asks the questions?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Does Ana ask about the bag?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el ordenador'), Kit::word('cien'), Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuesta')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.tarjeta', 'Read the conversation.', [
                Kit::line('Luis', 'Marta, ¿cuánto cuestan las bolsas?'),
                Kit::line('Marta', 'Cuestan quince euros.'),
                Kit::line('Luis', 'Pago con tarjeta, gracias.'),
            ], [
                Kit::question('How much do the bags cost?', ['15 euros', '50 euros', '5 euros'], '15 euros'),
                Kit::question('How does Luis pay?', ['By card', 'In cash'], 'By card'),
            ], [Kit::word('la bolsa', 'bolsas'), Kit::word('costar', 'cuestan'), Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cuanto-telefono', '¿Cuánto cuesta el ordenador, Luis?', 'How much does the computer cost, Luis?', [self::NUMBERS, ['euro', 'euros']], 'Cuesta cien euros.', [Kit::word('el ordenador'), Kit::word('cien')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cuanto-bolsa', '¿Cuánto cuesta la bolsa, Marta?', 'How much does the bag cost, Marta?', [self::NUMBERS, ['euro', 'euros']], 'Cuesta treinta euros.', [Kit::word('la bolsa', 'bolsa'), Kit::word('treinta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pagas', '¿Pagas con tarjeta, Ana?', 'Do you pay by card, Ana?', [['sí', 'no'], ['pago', 'tarjeta']], 'Sí, pago con tarjeta.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.telefonos-cincuenta', 'The computers cost 50 euros.', ['Los ordenadores cuestan cincuenta euros.'], [Kit::word('el ordenador', 'ordenadores'), Kit::word('cincuenta'), Kit::word('el euro', 'euros'), Kit::word('costar', 'cuestan'), Kit::form('cuestan')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.telefono-bolsa', 'The computer costs 100 euros and the bag costs 40.', ['El ordenador cuesta cien euros y la bolsa cuesta cuarenta.', 'El ordenador cuesta cien euros y la bolsa cuarenta.', 'El ordenador cuesta cien euros y la bolsa cuarenta euros.'], [Kit::word('el ordenador'), Kit::word('la bolsa', 'bolsa'), Kit::word('cien'), Kit::word('cuarenta'), Kit::word('costar', 'cuesta'), Kit::form('cuesta')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pago-cincuenta', 'I pay 50 euros by card.', ['Pago cincuenta euros con tarjeta.', 'Yo pago cincuenta euros con tarjeta.', 'Pago cincuenta euros con la tarjeta.', 'Yo pago cincuenta euros con la tarjeta.'], [Kit::word('pagar', 'pago'), Kit::word('cincuenta'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('el euro', 'euros')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.bolsa-treinta-y-cinco', 'The bag costs 35 euros.', ['La bolsa cuesta treinta y cinco euros.'], [Kit::word('la bolsa', 'bolsa'), Kit::word('treinta'), Kit::word('el euro', 'euros'), Kit::form('treinta y cinco')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.bolsas-veinte', 'Las bolsas ___ doce euros.', 'The bags cost 12 euros.', 'cuestan', Kit::form('cuestan', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.telefono-treinta', 'El ordenador ___ treinta euros.', 'The computer costs 30 euros.', 'cuesta', Kit::form('cuesta', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.bolsas-cuarenta', 'Las bolsas cuestan cuarenta euros.', 'The bags cost 40 euros.', [Kit::word('la bolsa', 'bolsas'), Kit::word('cuarenta'), Kit::form('cuestan')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.son-treinta', 'Son treinta euros, por favor.', 'That is 30 euros, please.', [Kit::word('treinta'), Kit::word('el euro', 'euros')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pago-cien', 'Son cien euros. Pago con tarjeta.', 'That is 100 euros. I pay by card.', [Kit::word('pagar', 'pago'), Kit::word('la tarjeta', 'tarjeta'), Kit::word('cien'), Kit::word('el euro', 'euros')], 'dictation', $set),
        ];
    }
}
