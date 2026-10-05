<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('i vestiti', cue: 'clothes'),
            new WordData('la camicia', cue: 'shirt', forms: ['camicie']),
            new WordData('i pantaloni', cue: 'trousers (always plural in Italian)'),
            new WordData('il prezzo', cue: 'price', forms: ['prezzi']),
            new WordData('la taglia', cue: 'size (of clothes)'),
            new WordData('il colore', cue: 'color'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara', 'cari', 'care']),
            new WordData('economico', cue: 'cheap, inexpensive (masculine)', forms: ['economica', 'economici', 'economiche']),
            new WordData('provare', cue: 'to try on (clothes)', forms: ['provo']),
            new WordData('lo sconto', cue: 'discount'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'La camicia è cara.', 'english' => 'The shirt is expensive.'],
            ['text' => 'I pantaloni sono cari.', 'english' => 'The trousers are expensive.'],
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
            Kit::gap($stage, 'sentences.choose_gap.camicia-cara', 'La camicia è ___.', ['cara', 'caro', 'cari'], 'cara', Kit::form('cara'), 'Camicia is feminine singular, so the adjective ends in -a: cara.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.prezzo-economico', 'Il prezzo è ___.', ['economico', 'economica', 'economici'], 'economico', Kit::form('economico'), 'Prezzo is masculine singular, so the adjective ends in -o: economico.', 'choose', 'The price is cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.pantaloni-cari', 'I pantaloni sono ___.', ['cari', 'caro', 'care'], 'cari', Kit::form('cari', true), 'Pantaloni is masculine and plural, so the adjective ends in -i: cari, not the singular caro.', 'choose', 'The trousers are expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.sconto', 'La camicia è cara. ___ lo sconto, è economica.', ['Con', 'Senza'], 'Con', Kit::word('lo sconto', 'sconto'), 'Con means with and senza means without: a discount is what makes the shirt cheap, so con fits.', 'choose', 'The shirt is expensive. With the discount, it is cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.vestiti-economici', 'I vestiti sono ___.', ['economici', 'economico', 'economiche'], 'economici', Kit::form('economici', true), 'Vestiti is masculine plural, so the adjective ends in -i: economici.', 'choose', 'The clothes are cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.colore', 'Di che ___ è la camicia?', ['colore', 'prezzo', 'sconto'], 'colore', Kit::word('il colore', 'colore'), 'Asking about the color of something uses di che colore.', 'choose', 'What color is the shirt?'),

            Kit::typeGap($stage, 'sentences.type_gap.camicia-rossa', 'La camicia è ___. (rosso)', 'The shirt is red.', 'rossa', Kit::form('rossa'), 'Camicia is feminine, so rosso becomes rossa.', glosses: ['rosso' => 'red']),
            Kit::typeGap($stage, 'sentences.type_gap.camicie', 'Le camicie sono ___.', 'The shirts are expensive.', 'care', Kit::form('care', true), 'Camicie is feminine plural, so the adjective ends in -e: care.'),
            Kit::typeGap($stage, 'sentences.type_gap.prezzo', 'Il ___ è qui.', 'The price is here.', 'prezzo', Kit::word('il prezzo', 'prezzo')),
            Kit::typeGap($stage, 'sentences.type_gap.provo', '___ la camicia.', 'I try on the shirt.', 'Provo', Kit::word('provare', 'provo')),
            Kit::typeGap($stage, 'sentences.type_gap.pantaloni', 'I pantaloni sono ___.', 'The trousers are cheap.', 'economici', Kit::form('economici', true), 'Pantaloni is masculine and plural, so the adjective ends in -i: economici.'),
            Kit::translate($stage, 'sentences.translate.camicia', 'The shirt is cheap.', ['La camicia è economica.'], [Kit::word('la camicia', 'camicia'), Kit::word('economico', 'economica'), Kit::form('economica')]),
            Kit::translate($stage, 'sentences.translate.pantaloni', 'The trousers are expensive.', ['I pantaloni sono cari.'], [Kit::word('i pantaloni'), Kit::word('caro', 'cari'), Kit::form('cari', true)]),
            Kit::translate($stage, 'sentences.translate.provo', 'I try on the trousers.', ['Provo i pantaloni.'], [Kit::word('provare', 'provo'), Kit::word('i pantaloni')]),
            Kit::build($stage, 'sentences.build.camicia', 'The shirt is not expensive.', 'La camicia non è cara.', ['caro'], [Kit::word('la camicia'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::build($stage, 'sentences.build.pantaloni', 'The trousers are not cheap.', 'I pantaloni non sono economici.', ['economico'], [Kit::word('i pantaloni'), Kit::word('economico', 'economici'), Kit::form('economici', true)]),
            Kit::build($stage, 'sentences.build.sconto', 'I have a discount.', 'Ho uno sconto.', ['un'], [Kit::word('lo sconto', 'sconto')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.camicia', 'La camicia è cara.', ['The shirt is expensive.', 'The shirt is cheap.', 'The trousers are expensive.', 'The clothes are expensive.'], 'The shirt is expensive.', [Kit::word('la camicia'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.prezzo', 'Il prezzo è qui.', ['The price is here.', 'The size is here.', 'The color is here.', 'The discount is here.'], 'The price is here.', [Kit::word('il prezzo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pantaloni', 'I pantaloni sono economici.', ['The trousers are cheap.', 'The trousers are expensive.', 'The shirt is cheap.', 'The clothes are cheap.'], 'The trousers are cheap.', [Kit::word('i pantaloni'), Kit::word('economico', 'economici'), Kit::form('economici', true)]),
            Kit::listenType($stage, 'sentences.listen_type.taglia', 'Che taglia hai?', 'What size are you? (to a friend)', [Kit::word('la taglia', 'taglia')], homophoneNote: 'Hai (you have) and ai (to the) sound the same: the h is only written, and it shows the verb.'),
            Kit::listenType($stage, 'sentences.listen_type.provo', 'Provo la camicia.', 'I try on the shirt.', [Kit::word('provare', 'provo'), Kit::word('la camicia')]),
            Kit::listenType($stage, 'sentences.listen_type.vestiti', 'I vestiti sono molto economici.', 'The clothes are very cheap.', [Kit::word('i vestiti'), Kit::word('economico', 'economici'), Kit::form('economici', true)]),
            Kit::listenType($stage, 'sentences.listen_type.colore', 'Di che colore è la camicia?', 'What color is the shirt?', [Kit::word('il colore', 'colore'), Kit::word('la camicia')], homophoneNote: 'È (is) has an accent and e (and) does not. They sound close, and the sentence tells you which is which.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.pantaloni', 'I pantaloni sono cari.', 'The trousers are expensive.', [Kit::word('i pantaloni'), Kit::word('caro', 'cari'), Kit::form('cari', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.taglia', 'Che taglia ha?', 'What size are you? (formal)', [Kit::word('la taglia', 'taglia')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ecco', 'Ecco la camicia.', 'Here is the shirt.', [Kit::word('la camicia')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.provo', 'Provo i pantaloni.', 'I try on the trousers.', [Kit::word('provare', 'provo'), Kit::word('i pantaloni')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.camicia', 'La camicia è cara?', 'Is the shirt expensive?', [['sì', 'no', 'è'], ['cara', 'economica']], 'Sì, la camicia è cara.', [Kit::word('la camicia'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sconto', 'Hai uno sconto?', 'Do you have a discount?', [['sì', 'no', 'ho'], ['sconto']], 'Sì, ho uno sconto.', [Kit::word('lo sconto', 'sconto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vestiti', 'I vestiti sono economici?', 'Are the clothes cheap?', [['sì', 'no', 'sono'], ['economici', 'cari']], 'Sì, i vestiti sono economici.', [Kit::word('i vestiti'), Kit::word('economico', 'economici'), Kit::form('economici', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.negozio', 'Read the conversation in the shop.', [
                Kit::line('Anna', 'Buongiorno. Il prezzo è qui?'),
                Kit::line('Luca', 'Sì. La camicia è cara, ma i pantaloni sono economici.'),
                Kit::line('Anna', 'Di che colore è la camicia?'),
                Kit::line('Luca', 'Il colore è blu.'),
                Kit::line('Anna', "C'è uno sconto sui vestiti?"),
                Kit::line('Luca', "Sì, c'è lo sconto. Che taglia ha?"),
                Kit::line('Anna', 'La mia taglia è piccola. Provo la camicia.'),
            ], [
                Kit::question('Which clothes are expensive?', ['The shirt', 'The trousers', 'All the clothes'], 'The shirt'),
                Kit::question('Is there a discount?', ['Yes, on the clothes.', 'No, there is not.', 'The text does not say.'], 'Yes, on the clothes.'),
                Kit::question('What color is the shirt?', ['Green', 'Blue', 'Red'], 'Blue'),
            ], [Kit::word('il prezzo'), Kit::word('la camicia'), Kit::word('caro'), Kit::word('i pantaloni'), Kit::word('economico'), Kit::word('provare'), Kit::word('lo sconto'), Kit::word('i vestiti'), Kit::word('la taglia'), Kit::word('il colore')], 'read', glosses: ['piccola' => 'small', 'blu' => 'blue', 'sui' => 'on the']),
            Kit::gap($stage, 'task.choose_gap.camicia-grande', 'La camicia è ___.', ['grande', 'grandi'], 'grande', Kit::form('grande', true), 'Adjectives in -e, like grande, have one form for masculine and feminine singular, so la camicia è grande. The plural is grandi.', 'read', 'The shirt is big.', ['grande' => 'big', 'grandi' => 'big']),
            Kit::gap($stage, 'task.choose_gap.pantaloni-grandi', 'I pantaloni sono ___.', ['grandi', 'grande'], 'grandi', Kit::form('grandi', true), 'Grande ends in -e and its plural is grandi, for masculine and feminine alike: i pantaloni sono grandi.', 'read', 'The trousers are big.', ['grande' => 'big', 'grandi' => 'big']),

            Kit::transform($stage, 'task.transform.camicie', 'Make it plural.', 'La camicia è cara.', ['Le camicie sono care.'], [Kit::word('la camicia', 'camicie'), Kit::word('caro', 'care'), Kit::form('care', true)]),
            Kit::transform($stage, 'task.transform.prezzi', 'Make it plural.', 'Il prezzo è economico.', ['I prezzi sono economici.'], [Kit::word('il prezzo', 'prezzi'), Kit::word('economico', 'economici'), Kit::form('economici', true)]),
            Kit::transform($stage, 'task.transform.nera', 'Talk about the shirt instead.', 'I pantaloni sono neri.', ['La camicia è nera.'], [Kit::word('la camicia'), Kit::form('nera')], ['neri' => 'black', 'nera' => 'black']),
            Kit::writeGuided($stage, 'task.write_guided.camicia', 'Say that the shirt is expensive and ask whether there is a discount.', ['camicia', 'cara', 'sconto'], "La camicia è cara. C'è uno sconto?", [
                ['forms' => ['camicia'], 'term' => 'la camicia'],
                ['forms' => ['cara'], 'term' => 'caro'],
                ['forms' => ['sconto'], 'term' => 'lo sconto'],
            ], [Kit::word('la camicia'), Kit::word('caro', 'cara'), Kit::word('lo sconto')]),
            Kit::writeGuided($stage, 'task.write_guided.colore', 'Ask what color the trousers are and say that your size is small.', ['colore', 'pantaloni', 'taglia'], 'Di che colore sono i pantaloni? La mia taglia è piccola.', [
                ['forms' => ['colore'], 'term' => 'il colore'],
                ['forms' => ['pantaloni'], 'term' => 'i pantaloni'],
                ['forms' => ['taglia'], 'term' => 'la taglia'],
            ], [Kit::word('il colore'), Kit::word('i pantaloni'), Kit::word('la taglia')], ['piccola' => 'small']),
            Kit::build($stage, 'task.build.vestiti', 'The clothes are not expensive.', 'I vestiti non sono cari.', ['caro', 'care'], [Kit::word('i vestiti'), Kit::word('caro', 'cari'), Kit::form('cari', true)]),
            Kit::build($stage, 'task.build.provo', 'I try on the shirt and the trousers.', 'Provo la camicia e i pantaloni.', ['le', 'è'], [Kit::word('provare', 'provo'), Kit::word('la camicia'), Kit::word('i pantaloni')]),
            Kit::build($stage, 'task.build.prezzo', 'The price and the discount are here.', 'Il prezzo e lo sconto sono qui.', ['sei', 'la'], [Kit::word('il prezzo'), Kit::word('lo sconto')]),
            Kit::translate($stage, 'task.translate.prezzo-taglia', 'The price and the size are here.', ['Il prezzo e la taglia sono qui.'], [Kit::word('il prezzo'), Kit::word('la taglia')]),
            Kit::translate($stage, 'task.translate.sconto', 'Is there a discount on the clothes?', ["C'è uno sconto sui vestiti?", "C'è lo sconto sui vestiti?", "C'è uno sconto per i vestiti?", "C'è lo sconto per i vestiti?"], [Kit::word('lo sconto', 'sconto'), Kit::word('i vestiti', 'vestiti')], glosses: ['sui' => 'on the']),

            Kit::listenPassage($stage, 'task.listen_passage.negozio', [
                Kit::line('Paolo', 'Buongiorno. La camicia è cara?'),
                Kit::line('Luca', "No, è economica. E c'è uno sconto."),
                Kit::line('Paolo', 'Molto bene. E i pantaloni?'),
                Kit::line('Luca', 'I pantaloni sono cari.'),
                Kit::line('Paolo', 'Provo la camicia. Grazie.'),
            ], [
                Kit::question('Is the shirt expensive?', ['Yes', 'No, it is cheap.', 'The conversation does not say.'], 'No, it is cheap.'),
                Kit::question('What does Paolo ask about after the shirt?', ['The trousers', 'The size', 'The color'], 'The trousers'),
                Kit::question('What does Paolo try on?', ['The trousers', 'The shirt', 'Nothing'], 'The shirt'),
            ], [
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Are the trousers expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la camicia'), Kit::word('caro'), Kit::word('economico'), Kit::word('lo sconto'), Kit::word('i pantaloni'), Kit::word('provare')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.sconto', 'Con lo sconto, i pantaloni sono economici.', 'With the discount, the trousers are cheap.', [Kit::word('lo sconto', 'sconto'), Kit::word('i pantaloni'), Kit::word('economico', 'economici'), Kit::form('economici', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.prezzo', 'Ecco il prezzo dei vestiti.', 'Here is the price of the clothes.', [Kit::word('il prezzo', 'prezzo'), Kit::word('i vestiti', 'vestiti')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.colore', 'Di che colore sono i pantaloni?', 'What color are the trousers?', [Kit::word('il colore', 'colore'), Kit::word('i pantaloni')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.pantaloni', 'I pantaloni sono cari o economici?', 'Are the trousers expensive or cheap?', [['pantaloni', 'sono'], ['economici', 'cari']], 'I pantaloni sono economici.', [Kit::word('i pantaloni'), Kit::word('economico', 'economici'), Kit::form('economici', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sconto', "C'è uno sconto per i vestiti?", 'Is there a discount on the clothes?', [['sì', 'no', "c'è"], ['sconto', 'vestiti']], "Sì, c'è uno sconto per i vestiti.", [Kit::word('lo sconto', 'sconto'), Kit::word('i vestiti')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.camicia', 'La camicia è cara o economica?', 'Is the shirt expensive or cheap?', [['camicia', 'è'], ['cara', 'economica']], 'La camicia è economica.', [Kit::word('la camicia'), Kit::word('economico', 'economica'), Kit::form('economica')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.prezzo', 'Il prezzo è qui o lì?', 'Is the price here or there?', [['prezzo', 'è'], ['qui', 'lì']], 'Il prezzo è qui.', [Kit::word('il prezzo', 'prezzo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.colore', 'Il colore e la taglia sono qui.', 'The color and the size are here.', [Kit::word('il colore'), Kit::word('la taglia')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.provo', 'Provo la camicia e i pantaloni.', 'I try on the shirt and the trousers.', [Kit::word('provare', 'provo'), Kit::word('la camicia'), Kit::word('i pantaloni')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $accent = 'È (is) has an accent and e (and) does not. They sound close, and the sentence tells you which is which.';

        return [
            Kit::translate($stage, 'check.a.translate.camicia', 'The shirt is expensive, but the trousers are very cheap.', ['La camicia è cara, ma i pantaloni sono molto economici.'], [Kit::word('la camicia'), Kit::word('caro', 'cara'), Kit::word('i pantaloni'), Kit::word('economico', 'economici'), Kit::form('economici', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.provo', 'I try on the clothes, but the price is not here.', ['Provo i vestiti, ma il prezzo non è qui.'], [Kit::word('provare', 'provo'), Kit::word('i vestiti'), Kit::word('il prezzo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.taglia', 'The price, the size and the discount are here.', ['Il prezzo, la taglia e lo sconto sono qui.'], [Kit::word('il prezzo'), Kit::word('la taglia'), Kit::word('lo sconto')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.colore', 'What color are the cheap clothes?', ['Di che colore sono i vestiti economici?'], [Kit::word('il colore', 'colore'), Kit::word('i vestiti'), Kit::word('economico', 'economici'), Kit::form('economici')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pantaloni', 'I pantaloni sono molto ___.', 'The trousers are very expensive.', 'cari', Kit::form('cari', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.camicie', 'Le camicie non sono ___.', 'The shirts are not cheap.', 'economiche', Kit::form('economiche'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.camicie', 'Le camicie sono molto care.', 'The shirts are very expensive.', [Kit::word('la camicia', 'camicie'), Kit::word('caro', 'care'), Kit::form('care')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.sconto', 'Con lo sconto, la camicia è economica.', 'With the discount, the shirt is cheap.', [Kit::word('lo sconto', 'sconto'), Kit::word('la camicia'), Kit::word('economico', 'economica'), Kit::form('economica')], 'dictation', $set, homophoneNote: $accent),
            Kit::listenType($stage, 'check.a.listen_type.provo', 'Provo i pantaloni. Ecco la taglia e il colore.', 'I try on the trousers. Here are the size and the color.', [Kit::word('provare', 'provo'), Kit::word('i pantaloni'), Kit::word('la taglia'), Kit::word('il colore')], 'dictation', $set, homophoneNote: 'E (and) joins the size and the color. It has no accent, unlike è (is), which sounds close.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.taglia', [
                Kit::line('Marta', 'Buongiorno. Ha la camicia in questa taglia?'),
                Kit::line('Luca', "Sì, ecco la camicia. È cara, ma c'è uno sconto."),
                Kit::line('Marta', 'Molto bene. Grazie.'),
            ], [
                Kit::question('What does Marta ask for?', ['A cheaper shirt', 'A shirt in this size', 'The price'], 'A shirt in this size'),
                Kit::question('Is the shirt expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who says thank you?', ['Marta', 'The shop assistant', 'Both of them'], 'Marta'),
                Kit::question('Who speaks first?', ['Marta', 'The shop assistant', 'Nobody'], 'Marta'),
                Kit::question('How does the conversation end?', ['Marta says thank you.', 'Marta asks the price.', 'Marta tries on trousers.'], 'Marta says thank you.'),
            ], [Kit::word('la camicia'), Kit::word('la taglia'), Kit::word('caro'), Kit::word('lo sconto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.vestiti', 'Read the conversation.', [
                Kit::line('Paolo', 'Buonasera. Ci sono vestiti economici qui?'),
                Kit::line('Luca', "Sì, i vestiti sono economici. C'è uno sconto per i pantaloni."),
                Kit::line('Paolo', 'Molto bene. E il prezzo?'),
            ], [
                Kit::question('Are the clothes cheap?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Which clothes have a discount?', ['The shirts', 'The trousers', 'All the clothes'], 'The trousers'),
            ], [Kit::word('i vestiti'), Kit::word('economico'), Kit::word('i pantaloni'), Kit::word('lo sconto'), Kit::word('il prezzo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sconto', "C'è uno sconto?", 'Is there a discount?', [['sì', 'no', "c'è"], ['sconto']], "Sì, c'è lo sconto.", [Kit::word('lo sconto', 'sconto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vestiti', 'I vestiti sono qui o lì?', 'Are the clothes here or there?', [['vestiti', 'sono'], ['qui', 'lì']], 'I vestiti sono qui.', [Kit::word('i vestiti')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pantaloni', 'I pantaloni qui sono cari?', 'Are the trousers here expensive?', [['sì', 'no', 'sono'], ['economici', 'cari']], 'No, i pantaloni sono economici.', [Kit::word('i pantaloni'), Kit::word('economico', 'economici')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $accent = 'È (is) has an accent and e (and) does not. They sound close, and the sentence tells you which is which.';

        return [
            Kit::translate($stage, 'check.b.translate.camicia', 'The shirt is very cheap, but the trousers are expensive.', ['La camicia è molto economica, ma i pantaloni sono cari.'], [Kit::word('la camicia'), Kit::word('economico', 'economica'), Kit::word('i pantaloni'), Kit::word('caro', 'cari'), Kit::form('economica')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.provo', 'I try on the shirt, but the size is not here.', ['Provo la camicia, ma la taglia non è qui.'], [Kit::word('provare', 'provo'), Kit::word('la camicia'), Kit::word('la taglia')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.prezzo', 'The price and the size of the clothes are not here.', ['Il prezzo e la taglia dei vestiti non sono qui.'], [Kit::word('il prezzo'), Kit::word('la taglia'), Kit::word('i vestiti', 'vestiti')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.colore', 'What color are the cheap shirts?', ['Di che colore sono le camicie economiche?'], [Kit::word('il colore', 'colore'), Kit::word('la camicia', 'camicie'), Kit::form('economiche')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.camicie', 'Le camicie qui sono ___.', 'The shirts here are expensive.', 'care', Kit::form('care', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vestiti', 'I vestiti qui sono ___.', 'The clothes here are cheap.', 'economici', Kit::form('economici'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vestiti', "I vestiti sono cari, ma c'è lo sconto.", 'The clothes are expensive, but there is a discount.', [Kit::word('i vestiti'), Kit::word('caro', 'cari'), Kit::word('lo sconto', 'sconto'), Kit::form('cari', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.prezzo', 'Con lo sconto, il prezzo è economico.', 'With the discount, the price is cheap.', [Kit::word('lo sconto', 'sconto'), Kit::word('il prezzo', 'prezzo'), Kit::word('economico'), Kit::form('economico')], 'dictation', $set, homophoneNote: $accent),
            Kit::listenType($stage, 'check.b.listen_type.provo', 'Provo i pantaloni. Il colore è qui.', 'I try on the trousers. The color is here.', [Kit::word('provare', 'provo'), Kit::word('i pantaloni'), Kit::word('il colore', 'colore')], 'dictation', $set, homophoneNote: $accent),
        ];
    }
}
