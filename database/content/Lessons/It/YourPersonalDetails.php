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

final class YourPersonalDetails implements UnitContent
{
    private const NUMBERS = ['uno', 'due', 'tre', 'quattro', 'cinque', 'sei', 'sette', 'otto', 'nove', 'dieci', 'undici', 'dodici', 'tredici', 'quattordici', 'quindici', 'sedici', 'diciassette', 'diciotto', 'diciannove', 'venti'];

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'your-personal-details';
    }

    public function words(): array
    {
        return [
            new WordData('l\'anno', cue: 'year', forms: ['anni'], note: 'You give your age in anni: ho venti anni.'),
            new WordData('il paese', cue: 'country', note: 'Paese can also mean village or small town. To ask where someone is from, Di dove sei? is the safest question.'),
            new WordData('l\'Olanda', cue: 'Holland (the Netherlands)'),
            new WordData('l\'Italia', cue: 'Italy'),
            new WordData('olandese', cue: 'Dutch (nationality; the same for a man and a woman)'),
            new WordData('il telefono', cue: 'telephone, phone'),
            new WordData('il numero', cue: 'number'),
            new WordData('l\'indirizzo', cue: 'address'),
            new WordData('il cognome', cue: 'surname, last name'),
            new WordData('sillabare', cue: 'to spell out (say the letters one by one; literally: split into syllables)', forms: ['sillaba'], note: 'Sillaba is the informal command: sillaba il tuo cognome, per favore. The usual everyday question is Come si scrive il tuo cognome?'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ho venti anni.', 'english' => 'I am twenty years old.'],
            ['text' => 'Sono olandese.', 'english' => 'I am Dutch.'],
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
            Kit::gap($stage, 'sentences.choose_gap.anni', 'Ho dodici ___.', ['anni', 'paese'], 'anni', Kit::word('l\'anno', 'anni'), 'You count your age in anni (years): ho dodici anni. Paese means country.', 'choose', 'I am twelve years old.'),
            Kit::gap($stage, 'sentences.choose_gap.ho', '___ dieci anni.', ['Ho', 'Sono'], 'Ho', Kit::form('ho', true), 'In Italian you have your age: ho dieci anni. Sono dieci anni would be like saying I am ten years, which Italian does not say.', 'choose', 'I am ten years old.'),
            Kit::gap($stage, 'sentences.choose_gap.hai', 'Anna, quanti anni ___?', ['hai', 'ho'], 'hai', Kit::form('hai', true), 'You are asking Anna, so you use hai (you have). Ho is for talking about yourself.', 'choose', 'Anna, how old are you? (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.sono-olandese', '___ olandese.', ['Sono', 'Ho'], 'Sono', Kit::form('sono', true), 'Nationality takes essere: sono olandese. Ho olandese would mean I have Dutch, which makes no sense.', 'choose', 'I am Dutch.'),
            Kit::gap($stage, 'sentences.choose_gap.italia', 'Il paese di Paolo è ___.', ['l\'Italia', 'l\'indirizzo', 'l\'anno'], 'l\'Italia', Kit::word('l\'Italia'), 'L\'Italia is a country, so it fits after il paese di Paolo è. L\'indirizzo is address and l\'anno is year.', 'choose', 'Paolo\'s country is Italy.'),
            Kit::gap($stage, 'sentences.choose_gap.olandese', 'Anna è ___.', ['olandese', 'l\'Olanda'], 'olandese', Kit::word('olandese'), 'Olandese is the nationality, and it is the same for a man and a woman. L\'Olanda is the country, not a person.', 'choose', 'Anna is Dutch.'),

            Kit::typeGap($stage, 'sentences.type_gap.anni', 'Ho quindici ___.', 'I am fifteen years old.', 'anni', Kit::word('l\'anno', 'anni')),
            Kit::typeGap($stage, 'sentences.type_gap.paolo-hai', 'Paolo, quanti anni ___?', 'Paolo, how old are you? (informal you)', 'hai', Kit::form('hai'), 'You are talking to Paolo, so you use hai. Age takes avere in Italian.'),
            Kit::typeGap($stage, 'sentences.type_gap.luca-sei', 'Luca, di dove ___?', 'Luca, where are you from? (informal you)', 'sei', Kit::form('sei'), 'Sei is the tu form of essere, the verb you use to ask where someone is from.'),
            Kit::typeGap($stage, 'sentences.type_gap.sono', '___ olandese.', 'I am Dutch.', 'Sono', Kit::form('sono'), 'Nationality takes essere: sono olandese. Ho would mean I have.'),
            Kit::typeGap($stage, 'sentences.type_gap.numero', 'Il tuo ___ di telefono, per favore.', 'Your phone number, please. (informal you)', 'numero', Kit::word('il numero', 'numero')),

            Kit::translate($stage, 'sentences.translate.venti', 'I am twenty years old.', ['Ho venti anni.', 'Io ho venti anni.'], [Kit::word('l\'anno', 'anni'), Kit::form('ho')]),
            Kit::translate($stage, 'sentences.translate.di-dove', 'Where are you from? (informal you)', ['Di dove sei?', 'Di dove sei tu?', 'Tu di dove sei?'], [Kit::form('sei')]),
            Kit::translate($stage, 'sentences.translate.cognome', 'Spell your surname, please. (informal you)', ['Sillaba il tuo cognome, per favore.', 'Per favore, sillaba il tuo cognome.'], [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')]),

            Kit::build($stage, 'sentences.build.sono-olandese', 'I am Dutch.', 'Sono olandese.', ['ho'], [Kit::word('olandese'), Kit::form('sono')]),
            Kit::build($stage, 'sentences.build.quanti', 'How old are you? (informal you)', 'Quanti anni hai?', ['ho'], [Kit::word('l\'anno', 'anni'), Kit::form('hai')]),
            Kit::build($stage, 'sentences.build.indirizzo', 'Your address, please. (informal you; start with the address)', 'Il tuo indirizzo, per favore.', ['sei'], [Kit::word('l\'indirizzo', 'indirizzo')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.venti', 'Ho tredici anni.', ['I am thirteen years old.', 'I am twelve years old.', 'You are thirteen years old.', 'I am Dutch.'], 'I am thirteen years old.', [Kit::word('l\'anno', 'anni'), Kit::form('ho')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.di-dove', 'Di dove sei?', ['Where are you from?', 'Where are you?', 'How old are you?', 'What is your surname?'], 'Where are you from?', [Kit::form('sei')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.telefono', 'Il mio telefono è quattro, otto, uno.', ['My phone number is four, eight, one.', 'My phone number is four, eight, two.', 'My phone number is five, eight, one.', 'My address is number four.'], 'My phone number is four, eight, one.', [Kit::word('il telefono', 'telefono')]),
            Kit::listenType($stage, 'sentences.listen_type.numero', 'Il mio numero è cinque, sette, tre.', 'My number is five, seven, three.', [Kit::word('il numero', 'numero')], homophoneNote: 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.'),
            Kit::listenType($stage, 'sentences.listen_type.olanda', 'Il paese di Anna è l\'Olanda.', 'Anna\'s country is Holland.', [Kit::word('il paese', 'paese'), Kit::word('l\'Olanda')], homophoneNote: 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.'),
            Kit::listenType($stage, 'sentences.listen_type.cognome', 'Per favore, sillaba il tuo cognome.', 'Please spell your surname. (informal you)', [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')]),
            Kit::listenType($stage, 'sentences.listen_type.anna-ha', 'Anna ha quindici anni.', 'Anna is fifteen years old.', [Kit::word('l\'anno', 'anni'), Kit::form('ha')], homophoneNote: 'Ha (has, with an h) sounds the same as a (to, at); here ha is the verb has, which is how you give someone\'s age.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ho', 'Ho venti anni.', 'I am twenty years old.', [Kit::word('l\'anno', 'anni'), Kit::form('ho')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.anna', 'Anna è olandese.', 'Anna is Dutch.', [Kit::word('olandese'), Kit::form('è')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.indirizzo', 'Il tuo indirizzo e telefono, per favore.', 'Your address and phone number, please. (informal you)', [Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il telefono', 'telefono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paese', 'Il mio paese è l\'Italia.', 'My country is Italy.', [Kit::word('il paese', 'paese'), Kit::word('l\'Italia')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.anni', 'Quanti anni hai?', 'How old are you?', [['ho', 'anni'], self::NUMBERS], 'Ho venti anni.', [Kit::word('l\'anno', 'anni')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.di-dove', 'Di dove sei?', 'Where are you from?', [['sono'], ['olandese']], 'Sono olandese.', [Kit::word('olandese')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.paese', 'Di che paese sei?', 'What country are you from?', [['il', 'mio', 'paese', 'è', 'sono'], ['l\'olanda', 'l\'italia', 'olandese']], 'Il mio paese è l\'Olanda.', [Kit::word('il paese', 'paese'), Kit::word('l\'Olanda')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.dati', 'Read the conversation.', [
                Kit::line('Marta', 'Ciao, Luca. Di dove sei?'),
                Kit::line('Luca', 'Il mio paese è l\'Olanda. Sono olandese.'),
                Kit::line('Marta', 'Il mio paese è l\'Italia. Quanti anni hai?'),
                Kit::line('Luca', 'Ho venti anni. E tu?'),
                Kit::line('Marta', 'Ho diciotto anni. Il tuo indirizzo?'),
                Kit::line('Luca', 'Il mio indirizzo è il numero dodici. E il tuo telefono?'),
                Kit::line('Marta', 'Il mio telefono è cinque, sette, tre.'),
            ], [
                Kit::question('Where is Luca from?', ['Holland', 'Italy', 'The text does not say.'], 'Holland'),
                Kit::question('How old is Marta?', ['Eighteen', 'Twenty', 'Twelve'], 'Eighteen'),
                Kit::question('What is Marta\'s phone number?', ['Five, seven, three', 'Five, seven, two', 'Twelve'], 'Five, seven, three'),
            ], [Kit::word('il paese', 'paese'), Kit::word('l\'Olanda'), Kit::word('olandese'), Kit::word('l\'Italia'), Kit::word('l\'anno', 'anni'), Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono')], 'read'),
            Kit::gap($stage, 'task.choose_gap.marta-ha', 'Anna ___ quindici anni.', ['ha', 'è'], 'ha', Kit::form('ha', true), 'Age takes avere, so Anna ha quindici anni. È quindici anni would be like saying she is fifteen years, which Italian does not say.', 'read', 'Anna is fifteen years old.'),
            Kit::gap($stage, 'task.choose_gap.indirizzo', 'Il mio ___ è il numero dodici.', ['indirizzo', 'paese', 'cognome'], 'indirizzo', Kit::word('l\'indirizzo', 'indirizzo'), 'An address has a number, so indirizzo fits. Paese is country and cognome is surname.', 'read', 'My address is number twelve.'),

            Kit::transform($stage, 'task.transform.domanda', 'Now ask a friend how old they are (informal you).', 'Ho venti anni.', ['Quanti anni hai?', 'Quanti anni hai tu?', 'Tu quanti anni hai?'], [Kit::word('l\'anno', 'anni'), Kit::form('hai')]),
            Kit::transform($stage, 'task.transform.anna', 'Now say it about Anna.', 'Sono olandese.', ['Anna è olandese.', 'È olandese.', 'Lei è olandese.'], [Kit::word('olandese'), Kit::form('è', true)]),
            Kit::transform($stage, 'task.transform.paolo', 'Now say it about Paolo.', 'Ho quindici anni.', ['Paolo ha quindici anni.', 'Ha quindici anni.', 'Lui ha quindici anni.'], [Kit::word('l\'anno', 'anni'), Kit::form('ha', true)]),
            Kit::writeGuided($stage, 'task.write_guided.eta', 'Say that you are twenty years old and that you are Dutch.', ['ho', 'anni', 'sono', 'olandese'], 'Ho venti anni. Sono olandese.', [
                ['forms' => ['ho'], 'term' => null],
                ['forms' => ['anni'], 'term' => 'l\'anno'],
                ['forms' => ['olandese'], 'term' => 'olandese'],
            ], [Kit::word('l\'anno', 'anni'), Kit::word('olandese'), Kit::form('ho')]),
            Kit::writeGuided($stage, 'task.write_guided.cognome', 'Ask Paolo to spell his surname, please (informal you).', ['sillaba', 'il tuo cognome', 'per favore'], 'Paolo, sillaba il tuo cognome, per favore.', [
                ['forms' => ['sillaba'], 'term' => 'sillabare'],
                ['forms' => ['cognome'], 'term' => 'il cognome'],
            ], [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')]),
            Kit::build($stage, 'task.build.telefono', 'Which phone number do you have? (informal you)', 'Che numero di telefono hai?', ['sei', 'ho'], [Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono')], 'write'),
            Kit::build($stage, 'task.build.anna-olandese', 'Anna is Dutch.', 'Anna è olandese.', ['ha', 'anni'], [Kit::word('olandese'), Kit::form('è')], 'write'),
            Kit::build($stage, 'task.build.paolo', 'How old is Paolo? (start with the question)', 'Quanti anni ha Paolo?', ['sono', 'di'], [Kit::word('l\'anno', 'anni'), Kit::form('ha')], 'write'),
            Kit::translate($stage, 'task.translate.olandese-italia', 'I am Dutch, but Marta\'s country is Italy.', ['Sono olandese, ma il paese di Marta è l\'Italia.', 'Io sono olandese, ma il paese di Marta è l\'Italia.'], [Kit::word('olandese'), Kit::word('il paese', 'paese'), Kit::word('l\'Italia'), Kit::form('sono')], 'write'),
            Kit::translate($stage, 'task.translate.telefono', 'My phone number is eight, nine, two.', ['Il mio numero di telefono è otto, nove, due.', 'Il mio telefono è otto, nove, due.'], [Kit::word('il telefono', 'telefono')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.dati', [
                Kit::line('Paolo', 'Ciao, Anna. Di dove sei?'),
                Kit::line('Anna', 'Sono olandese. Il mio paese è l\'Olanda.'),
                Kit::line('Paolo', 'Il mio paese è l\'Italia. Quanti anni hai?'),
                Kit::line('Anna', 'Ho quindici anni.'),
                Kit::line('Paolo', 'Che numero di telefono hai?'),
                Kit::line('Anna', 'Il mio numero è sei, due, quattro.'),
            ], [
                Kit::question('Where is Anna from?', ['Holland', 'Italy', 'The conversation does not say.'], 'Holland'),
                Kit::question('How old is Anna?', ['Fifteen', 'Twelve', 'Twenty'], 'Fifteen'),
                Kit::question('What does Paolo ask for last?', ['Anna\'s phone number', 'Anna\'s address', 'Anna\'s surname'], 'Anna\'s phone number'),
            ], [
                Kit::question('Where is Paolo from?', ['Italy', 'Holland', 'The conversation does not say.'], 'Italy'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Is Anna Dutch?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('il paese', 'paese'), Kit::word('l\'Olanda'), Kit::word('olandese'), Kit::word('l\'Italia'), Kit::word('l\'anno', 'anni'), Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta', 'Paolo ha diciassette anni.', 'Paolo is seventeen years old.', [Kit::word('l\'anno', 'anni'), Kit::form('ha', true)], 'listen', homophoneNote: 'Ha (has, with an h) sounds the same as a (to, at); here ha is the verb has, which is how you give someone\'s age.'),
            Kit::listenType($stage, 'task.listen_type.cognome', 'Paolo, il tuo cognome? Sillaba, per favore.', 'Paolo, your surname? Spell it, please. (informal you)', [Kit::word('il cognome', 'cognome'), Kit::word('sillabare', 'sillaba')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.paese-indirizzo', 'Il mio paese è l\'Italia. Il mio indirizzo è il numero otto.', 'My country is Italy. My address is number eight.', [Kit::word('il paese', 'paese'), Kit::word('l\'Italia'), Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il numero', 'numero')], 'listen', homophoneNote: 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.'),

            Kit::speakAnswer($stage, 'task.speak_answer.telefono', 'Che numero di telefono hai?', 'What is your phone number?', [['il', 'mio', 'telefono', 'è', 'numero', 'di'], self::NUMBERS], 'Il mio numero di telefono è cinque, sette, tre.', [Kit::word('il telefono', 'telefono')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.indirizzo', 'Il tuo indirizzo, per favore.', 'Your address, please.', [['il', 'mio', 'indirizzo', 'è'], ['numero', ...self::NUMBERS]], 'Il mio indirizzo è il numero otto.', [Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il numero', 'numero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.olandese', 'Sei olandese?', 'Are you Dutch?', [['sì', 'no'], ['sono', 'olandese']], 'Sì, sono olandese.', [Kit::word('olandese')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.italia', 'Il tuo paese è l\'Italia?', 'Is your country Italy?', [['sì', 'no'], ['sono', 'il', 'mio', 'paese', 'l\'olanda', 'l\'italia']], 'No, il mio paese è l\'Olanda.', [Kit::word('l\'Italia'), Kit::word('l\'Olanda')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cognome', 'Paolo, sillaba il tuo cognome, per favore.', 'Paolo, spell your surname, please.', [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.anni-olanda', 'Ho venti anni e il mio paese è l\'Olanda.', 'I am twenty years old and my country is Holland.', [Kit::word('l\'anno', 'anni'), Kit::word('l\'Olanda')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.diciannove', 'I am nineteen years old.', ['Ho diciannove anni.', 'Io ho diciannove anni.'], [Kit::word('l\'anno', 'anni'), Kit::form('ho', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-hai', 'Marta, how old are you? (informal you)', ['Marta, quanti anni hai?', 'Quanti anni hai, Marta?'], [Kit::word('l\'anno', 'anni'), Kit::form('hai')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paolo-olandese', 'My country is Italy, but Paolo is Dutch.', ['Il mio paese è l\'Italia, ma Paolo è olandese.'], [Kit::word('l\'Italia'), Kit::word('olandese'), Kit::form('è')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luca-cognome', 'Luca, spell your surname, please. (informal you)', ['Luca, sillaba il tuo cognome, per favore.', 'Per favore, Luca, sillaba il tuo cognome.', 'Sillaba il tuo cognome, per favore, Luca.'], [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.indirizzo', 'Il mio ___ è il numero nove.', 'My address is number nine.', 'indirizzo', Kit::word('l\'indirizzo', 'indirizzo'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luca-ha', 'Luca ___ diciassette anni.', 'Luca is seventeen years old.', 'ha', Kit::form('ha', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.olandese', 'Sono olandese e ho venti anni.', 'I am Dutch and I am twenty years old.', [Kit::word('olandese'), Kit::word('l\'anno', 'anni'), Kit::form('ho')], 'dictation', $set, homophoneNote: 'E (and) has no accent and sounds close to è (is); ho (I have) starts with a silent h and sounds like o (or). Here e joins the two parts and ho is the verb have.'),
            Kit::listenType($stage, 'check.a.listen_type.paese', 'Il mio paese è l\'Italia, non l\'Olanda.', 'My country is Italy, not Holland.', [Kit::word('il paese', 'paese'), Kit::word('l\'Italia'), Kit::word('l\'Olanda')], 'dictation', $set, homophoneNote: 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.'),
            Kit::listenType($stage, 'check.a.listen_type.telefono', 'Sono olandese. Il mio numero di telefono è sei.', 'I am Dutch. My phone number is six.', [Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono'), Kit::form('sono')], 'dictation', $set, homophoneNote: 'È (is, with an accent) sounds close to e (and); here è is the verb is, so it is written with an accent.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.olandese', [
                Kit::line('Luca', 'Ciao, Marta. Sei olandese?'),
                Kit::line('Marta', 'No, il mio paese è l\'Italia. E tu?'),
                Kit::line('Luca', 'Io sono olandese. E il tuo telefono, Marta?'),
                Kit::line('Marta', 'Il mio numero di telefono è nove, due, tre.'),
            ], [
                Kit::question('Is Marta Dutch?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Where is Marta from?', ['Italy', 'Holland', 'The conversation does not say.'], 'Italy'),
                Kit::question('What does Luca ask for?', ['A phone number', 'An address', 'A surname'], 'A phone number'),
            ], [
                Kit::question('Who is Dutch?', ['Luca', 'Marta', 'Both'], 'Luca'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Does Marta say her phone number?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('olandese'), Kit::word('il telefono', 'telefono'), Kit::word('il numero', 'numero'), Kit::word('l\'Italia')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.indirizzo', 'Read the conversation.', [
                Kit::line('Paolo', 'Ciao, Anna. E il tuo indirizzo?'),
                Kit::line('Anna', 'Il mio indirizzo è il numero otto.'),
                Kit::line('Paolo', 'Anna, sillaba il tuo cognome, per favore.'),
                Kit::line('Anna', 'Sì, Paolo.'),
            ], [
                Kit::question('What does Paolo ask first?', ['Her address', 'Her age', 'Her phone number'], 'Her address'),
                Kit::question('What number is Anna\'s address?', ['Eight', 'Six', 'Twelve'], 'Eight'),
            ], [Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il numero', 'numero'), Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.anna-anni', 'Quanti anni hai, Anna?', 'How old are you, Anna?', [['ho', 'anni'], self::NUMBERS], 'Ho diciotto anni.', [Kit::word('l\'anno', 'anni')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.paolo-di-dove', 'Paolo, di dove sei?', 'Paolo, where are you from?', [['sono'], ['olandese']], 'Sono olandese.', [Kit::word('olandese'), Kit::form('sono')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.e-paese', 'E tu, di che paese sei?', 'And you, what country are you from?', [['il', 'mio', 'paese', 'è', 'sono'], ['l\'olanda', 'l\'italia']], 'Il mio paese è l\'Olanda.', [Kit::word('il paese', 'paese'), Kit::word('l\'Olanda')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.undici', 'I am eleven years old and my country is Italy.', ['Ho undici anni e il mio paese è l\'Italia.', 'Io ho undici anni e il mio paese è l\'Italia.'], [Kit::word('l\'anno', 'anni'), Kit::word('l\'Italia'), Kit::form('ho', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.indirizzo-telefono', 'My address is number six. My phone number is five.', ['Il mio indirizzo è il numero sei. Il mio telefono è cinque.', 'Il mio indirizzo è il numero sei. Il mio numero di telefono è cinque.'], [Kit::word('l\'indirizzo', 'indirizzo'), Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anna-olandese', 'Anna is Dutch and her country is Holland.', ['Anna è olandese e il suo paese è l\'Olanda.'], [Kit::word('olandese'), Kit::word('il paese', 'paese'), Kit::word('l\'Olanda'), Kit::form('è')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-cognome', 'Marta, spell your surname, please. (informal you)', ['Marta, sillaba il tuo cognome, per favore.', 'Per favore, Marta, sillaba il tuo cognome.', 'Sillaba il tuo cognome, per favore, Marta.'], [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luca-hai', 'Luca, quanti anni ___?', 'Luca, how old are you? (informal you)', 'hai', Kit::form('hai', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-e', 'Marta ___ olandese.', 'Marta is Dutch.', 'è', Kit::form('è'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.paese', 'Sono olandese e il mio paese è l\'Olanda. Il paese di Luca è l\'Italia.', 'I am Dutch and my country is Holland. Luca\'s country is Italy.', [Kit::word('olandese'), Kit::word('il paese', 'paese'), Kit::word('l\'Olanda'), Kit::word('l\'Italia'), Kit::form('è')], 'dictation', $set, homophoneNote: 'E (and) has no accent and sounds close to è (is); here e joins two parts and è is the verb is.'),
            Kit::listenType($stage, 'check.b.listen_type.cognome', 'Luca, sillaba il tuo cognome e indirizzo, per favore.', 'Luca, spell your surname and your address, please. (informal you)', [Kit::word('sillabare', 'sillaba'), Kit::word('il cognome', 'cognome'), Kit::word('l\'indirizzo', 'indirizzo')], 'dictation', $set, homophoneNote: 'E (and) has no accent and sounds close to è (is); here it joins the two things you spell.'),
            Kit::listenType($stage, 'check.b.listen_type.dodici', 'Ho dodici anni. Il mio numero di telefono è cinque.', 'I am twelve years old. My phone number is five.', [Kit::word('l\'anno', 'anni'), Kit::word('il numero', 'numero'), Kit::word('il telefono', 'telefono'), Kit::form('ho')], 'dictation', $set, homophoneNote: 'Ho (I have) starts with a silent h and sounds like o (or); here it is the verb have. È (is, with an accent) differs from e (and).'),
        ];
    }
}
