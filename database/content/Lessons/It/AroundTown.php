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

final class AroundTown implements UnitContent
{
    private const PLACES = ['banca', 'posta', 'supermercato', 'parco', 'negozio', 'chiesa', 'museo', 'biblioteca', 'città', 'paese'];

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'around-town';
    }

    public function words(): array
    {
        return [
            new WordData('la banca', cue: 'bank'),
            new WordData('la posta', cue: 'post office', accepted: ['l\'ufficio postale'], note: 'La posta is the post office, and also the mail. L\'ufficio postale means the same and is also accepted.'),
            new WordData('il supermercato', cue: 'supermarket'),
            new WordData('il parco', cue: 'park'),
            new WordData('il negozio', cue: 'shop (store)'),
            new WordData('la chiesa', cue: 'church'),
            new WordData('il museo', cue: 'museum'),
            new WordData('la biblioteca', cue: 'library', note: 'La biblioteca is a library, where you borrow books. A bookshop is a libreria.'),
            new WordData('la città', cue: 'city', note: 'Città ends in an accent. The word stays the same in the plural.'),
            new WordData('il paese', cue: 'village (small town)', note: 'Paese means a village or small town, and also a country. In paese means in town / in the village.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Vado al parco.', 'english' => 'I am going to the park.'],
            ['text' => 'Vengo dalla chiesa.', 'english' => 'I come from the church.'],
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

    /** @return list<string> */
    private function placesAnd(string ...$extra): array
    {
        return array_values([...$extra, ...self::PLACES]);
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $vengo = ['vengo' => 'I come'];
        $oNote = 'O (or) sounds like ho (I have), but ho is a verb that starts with a silent h. Here o means or.';
        $eNote = 'È (is) sounds close to e (and), but the sentence tells you it is the verb. The verb has an accent.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.banca', 'Vado ___ banca.', ['alla', 'al'], 'alla', Kit::form('alla', true), 'Banca is feminine, so a + la joins into alla. Al goes with a masculine word.', 'choose', 'I am going to the bank.'),
            Kit::gap($stage, 'sentences.choose_gap.negozio', 'Vado ___ negozio.', ['al', 'alla'], 'al', Kit::form('al', true), 'Negozio is masculine, so a + il joins into al. Alla is only for a feminine word.', 'choose', 'I am going to the shop.'),
            Kit::gap($stage, 'sentences.choose_gap.chiesa', 'Vengo ___ chiesa.', ['dalla', 'dal'], 'dalla', Kit::form('dalla', true), 'Chiesa is feminine, so da + la joins into dalla. Dal is only for a masculine word.', 'choose', 'I come from the church.', $vengo),
            Kit::gap($stage, 'sentences.choose_gap.museo', 'Vengo ___ museo.', ['dal', 'dalla'], 'dal', Kit::form('dal', true), 'Museo is masculine, so da + il joins into dal. Dalla goes with a feminine word.', 'choose', 'I come from the museum.', $vengo),
            Kit::gap($stage, 'sentences.choose_gap.biblioteca', 'Vado alla ___.', ['biblioteca', 'museo', 'parco'], 'biblioteca', Kit::word('la biblioteca', 'biblioteca'), 'Alla goes with biblioteca, which is feminine. Museo and parco are masculine and take al.', 'choose', 'I am going to the library.'),
            Kit::gap($stage, 'sentences.choose_gap.supermercato', 'Sono al ___.', ['supermercato', 'chiesa', 'città'], 'supermercato', Kit::word('il supermercato', 'supermercato'), 'Al goes with supermercato, which is masculine. Chiesa and città are feminine and take alla.', 'choose', 'I am at the supermarket.'),

            Kit::typeGap($stage, 'sentences.type_gap.parco', 'Vado ___ parco.', 'I am going to the park.', 'al', Kit::form('al'), 'Parco is masculine, so a + il becomes al.'),
            Kit::typeGap($stage, 'sentences.type_gap.chiesa', 'Sono in ___.', 'I am in the church.', 'chiesa', Kit::word('la chiesa', 'chiesa')),
            Kit::typeGap($stage, 'sentences.type_gap.paese', 'Vengo ___ paese.', 'I come from the village.', 'dal', Kit::form('dal'), 'Paese is masculine, so da + il becomes dal.', glosses: $vengo),
            Kit::typeGap($stage, 'sentences.type_gap.posta', 'Vado alla ___.', 'I am going to the post office.', 'posta', Kit::word('la posta', 'posta')),
            Kit::typeGap($stage, 'sentences.type_gap.citta', 'Vengo ___ città.', 'I come from the city.', 'dalla', Kit::form('dalla'), 'Città is feminine, so da + la becomes dalla.', glosses: $vengo),

            Kit::translate($stage, 'sentences.translate.museo', 'I am going to the museum.', ['Vado al museo.', 'Io vado al museo.'], [Kit::word('il museo', 'museo'), Kit::form('al')]),
            Kit::translate($stage, 'sentences.translate.banca', 'Are you going to the bank? (informal you)', ['Vai alla banca?', 'Tu vai alla banca?', 'Vai in banca?', 'Tu vai in banca?'], [Kit::word('la banca', 'banca'), Kit::form('alla', false, ['in'])]),
            Kit::translate($stage, 'sentences.translate.biblioteca', 'I am in the library.', ['Sono in biblioteca.', 'Io sono in biblioteca.', 'Sono alla biblioteca.', 'Io sono alla biblioteca.'], [Kit::word('la biblioteca', 'biblioteca')]),

            Kit::build($stage, 'sentences.build.supermercato', 'I am going to the supermarket.', 'Vado al supermercato.', ['alla'], [Kit::word('il supermercato', 'supermercato'), Kit::form('al')]),
            Kit::build($stage, 'sentences.build.parco', 'We are going to the park.', 'Andiamo al parco.', ['alla'], [Kit::word('il parco', 'parco'), Kit::form('al')]),
            Kit::build($stage, 'sentences.build.negozio', 'We are going to the shop.', 'Andiamo al negozio.', ['alla'], [Kit::word('il negozio', 'negozio'), Kit::form('al')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.banca', 'Vado alla banca.', ['I am going to the bank.', 'I am going to the park.', 'I am at the bank.', 'I come from the bank.'], 'I am going to the bank.', [Kit::word('la banca', 'banca'), Kit::form('alla')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.chiesa', 'Vai alla chiesa?', ['Are you going to the church?', 'Are you going to the museum?', 'Are you at the church?', 'Am I going to the church?'], 'Are you going to the church?', [Kit::word('la chiesa', 'chiesa'), Kit::form('alla')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.citta', 'Sono in città.', ['I am in the city.', 'I am going to the city.', 'I am in the village.', 'I am in the park.'], 'I am in the city.', [Kit::word('la città', 'città')]),
            Kit::listenType($stage, 'sentences.listen_type.posta', 'Vado alla posta.', 'I am going to the post office.', [Kit::word('la posta', 'posta')]),
            Kit::listenType($stage, 'sentences.listen_type.parco-paese', 'Sono al parco del paese.', 'I am at the village park.', [Kit::word('il parco', 'parco'), Kit::word('il paese', 'paese'), Kit::form('del')]),
            Kit::listenType($stage, 'sentences.listen_type.biblioteca', 'Marta è in biblioteca.', 'Marta is in the library.', [Kit::word('la biblioteca', 'biblioteca')], homophoneNote: $eNote),
            Kit::listenType($stage, 'sentences.listen_type.supermercato-negozio', 'Vai al supermercato o al negozio?', 'Are you going to the supermarket or to the shop? (informal you)', [Kit::word('il supermercato', 'supermercato'), Kit::word('il negozio', 'negozio'), Kit::form('al', true)], homophoneNote: $oNote),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.parco', 'Vado al parco.', 'I am going to the park.', [Kit::word('il parco', 'parco'), Kit::form('al')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.citta', 'Andiamo in città.', 'We are going to the city.', [Kit::word('la città', 'città')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paese', 'Sono in paese.', 'I am in the village.', [Kit::word('il paese', 'paese')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.posta-museo', 'Vado alla posta e al museo.', 'I am going to the post office and to the museum.', [Kit::word('la posta', 'posta'), Kit::word('il museo', 'museo'), Kit::form('al')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.dove', 'Dove sei?', 'Where are you?', [['sono', 'in', 'al', 'alla'], self::PLACES], 'Sono in chiesa.', [Kit::word('la chiesa', 'chiesa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.negozio', 'Vai al negozio?', 'Are you going to the shop?', [['sì', 'no'], $this->placesAnd('vado', 'andiamo')], 'Sì, vado al negozio.', [Kit::word('il negozio', 'negozio'), Kit::form('al')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.banca', 'Sei in banca?', 'Are you in the bank?', [['sì', 'no'], $this->placesAnd('sono')], 'Sì, sono in banca.', [Kit::word('la banca', 'banca')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $vengo = ['vengo' => 'I come'];
        $oNote = 'O (or) sounds like ho (I have), but ho is a verb that starts with a silent h. Here o means or.';
        $andNote = 'E (and) sounds close to è (is), but here it joins two places. The verb has an accent.';

        return [
            Kit::readPassage($stage, 'task.read_passage.dove-sei', 'Read the conversation about where Paolo and Anna are.', [
                Kit::line('Anna', 'Paolo, dove sei?'),
                Kit::line('Paolo', 'Sono in paese. Vado alla posta e in banca.'),
                Kit::line('Anna', 'Sono in città. Vengo dal museo e vado in biblioteca.'),
            ], [
                Kit::question('Where is Paolo?', ['In the village', 'In the city', 'In the library'], 'In the village'),
                Kit::question('Where does Paolo go?', ['To the post office and the bank', 'To the museum', 'To the park'], 'To the post office and the bank'),
                Kit::question('Where does Anna come from?', ['From the museum', 'From the bank', 'From the village'], 'From the museum'),
            ], [Kit::word('il paese', 'paese'), Kit::word('la posta', 'posta'), Kit::word('la banca', 'banca'), Kit::word('la città', 'città'), Kit::word('il museo', 'museo'), Kit::word('la biblioteca', 'biblioteca')], 'read', glosses: $vengo),
            Kit::gap($stage, 'task.choose_gap.chiesa-marta', 'Vado ___ chiesa con Marta.', ['alla', 'al'], 'alla', Kit::form('alla', true), 'Chiesa is feminine, so a + la joins into alla: alla chiesa.', 'read', 'I am going to the church with Marta.'),
            Kit::gap($stage, 'task.choose_gap.paese-luca', 'Vado al ___ con Luca.', ['paese', 'città', 'chiesa'], 'paese', Kit::word('il paese', 'paese'), 'Al goes with a masculine word, and paese is masculine. Città and chiesa are feminine and take alla.', 'read', 'I am going to the village with Luca.'),

            Kit::transform($stage, 'task.transform.parco', 'Say that you are in the park.', 'Vado al parco.', ['Sono al parco.', 'Io sono al parco.'], [Kit::word('il parco', 'parco')]),
            Kit::transform($stage, 'task.transform.museo', 'Say that you come from the museum.', 'Vado al museo.', ['Vengo dal museo.', 'Io vengo dal museo.'], [Kit::word('il museo', 'museo'), Kit::form('dal', true)], $vengo),
            Kit::transform($stage, 'task.transform.biblioteca', 'Ask a friend instead (informal you).', 'Vado alla biblioteca.', ['Vai alla biblioteca?', 'Tu vai alla biblioteca?', 'Vai in biblioteca?', 'Tu vai in biblioteca?'], [Kit::word('la biblioteca', 'biblioteca'), Kit::form('alla', false, ['in'])]),
            Kit::writeGuided($stage, 'task.write_guided.citta-parco', 'Say that you are in the city and that you are going to the park.', ['sono in', 'città', 'vado', 'parco'], 'Sono in città e vado al parco.', [
                ['forms' => ['città'], 'term' => 'la città'],
                ['forms' => ['al'], 'term' => null],
                ['forms' => ['parco'], 'term' => 'il parco'],
            ], [Kit::word('la città', 'città'), Kit::word('il parco', 'parco'), Kit::form('al')]),
            Kit::writeGuided($stage, 'task.write_guided.museo-supermercato', 'Say that you come from the museum and that you are going to the supermarket.', ['vengo', 'museo', 'vado', 'supermercato'], 'Vengo dal museo e vado al supermercato.', [
                ['forms' => ['dal'], 'term' => null],
                ['forms' => ['museo'], 'term' => 'il museo'],
                ['forms' => ['al'], 'term' => null],
                ['forms' => ['supermercato'], 'term' => 'il supermercato'],
            ], [Kit::word('il museo', 'museo'), Kit::word('il supermercato', 'supermercato'), Kit::form('dal', true)], $vengo),
            Kit::build($stage, 'task.build.parco-vengo', 'I come from the park.', 'Vengo dal parco.', ['dalla', 'al'], [Kit::word('il parco', 'parco'), Kit::form('dal', true)], 'write', $vengo),
            Kit::build($stage, 'task.build.parco-chiesa', 'I am going to the park and to the church.', 'Vado al parco e alla chiesa.', ['dal', 'dalla'], [Kit::word('il parco', 'parco'), Kit::word('la chiesa', 'chiesa'), Kit::form('al')]),
            Kit::build($stage, 'task.build.marta-parco', 'Marta is at the park of the village.', 'Marta è al parco del paese.', ['dal', 'alla'], [Kit::word('il parco', 'parco'), Kit::word('il paese', 'paese'), Kit::form('del', true)]),
            Kit::translate($stage, 'task.translate.banca-posta', 'I am going to the bank and to the post office.', ['Vado in banca e alla posta.', 'Io vado in banca e alla posta.', 'Vado alla posta e in banca.', 'Io vado alla posta e in banca.', 'Vado alla banca e alla posta.', 'Io vado alla banca e alla posta.', 'Vado alla posta e alla banca.', 'Io vado alla posta e alla banca.'], [Kit::word('la banca', 'banca'), Kit::word('la posta', 'posta'), Kit::form('alla')]),
            Kit::translate($stage, 'task.translate.paese-citta', 'I come from the village, not from the city.', ['Vengo dal paese, non dalla città.', 'Io vengo dal paese, non dalla città.'], [Kit::word('il paese', 'paese'), Kit::word('la città', 'città'), Kit::form('dal', true)], glosses: $vengo),

            Kit::listenPassage($stage, 'task.listen_passage.negozio-museo', [
                Kit::line('Marta', 'Luca, dove sei?'),
                Kit::line('Luca', 'Sono al negozio del museo.'),
                Kit::line('Marta', 'Vai alla chiesa?'),
                Kit::line('Luca', 'No, vado al supermercato. E tu, dove sei?'),
                Kit::line('Marta', 'Io sono in biblioteca.'),
            ], [
                Kit::question('Where is Luca?', ['In the museum shop', 'In the library', 'In the supermarket'], 'In the museum shop'),
                Kit::question('Where is Luca going?', ['To the supermarket', 'To the church', 'To the library'], 'To the supermarket'),
                Kit::question('Where is Marta?', ['In the library', 'In the museum', 'In the church'], 'In the library'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Is Luca going to the church?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il negozio', 'negozio'), Kit::word('il museo', 'museo'), Kit::word('la chiesa', 'chiesa'), Kit::word('il supermercato', 'supermercato'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('del')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.banca-posta', 'Andiamo in banca e alla posta.', 'We are going to the bank and to the post office.', [Kit::word('la banca', 'banca'), Kit::word('la posta', 'posta'), Kit::form('alla')], 'listen', homophoneNote: $andNote),
            Kit::listenType($stage, 'task.listen_type.parco-citta', 'Sei al parco o in città?', 'Are you at the park or in the city? (informal you)', [Kit::word('il parco', 'parco'), Kit::word('la città', 'città')], 'listen', homophoneNote: $oNote),
            Kit::listenType($stage, 'task.listen_type.chiesa-paese', 'Anna va alla chiesa del paese.', 'Anna goes to the church of the village.', [Kit::word('la chiesa', 'chiesa'), Kit::word('il paese', 'paese'), Kit::form('del')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.parco-negozio', 'Vai al parco o al negozio?', 'Are you going to the park or to the shop?', [['vado', 'andiamo'], self::PLACES], 'Vado al negozio.', [Kit::word('il negozio', 'negozio'), Kit::form('al')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marta', 'Marta è in banca o in biblioteca?', 'Is Marta at the bank or in the library?', [['è', 'in'], self::PLACES], 'Marta è in biblioteca.', [Kit::word('la biblioteca', 'biblioteca')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.supermercato', 'Sei al supermercato?', 'Are you at the supermarket?', [['sì', 'no'], $this->placesAnd('sono')], 'Sì, sono al supermercato.', [Kit::word('il supermercato', 'supermercato')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.citta-paese', 'Sei in città o in paese?', 'Are you in the city or in the village?', [['sono'], ['città', 'paese']], 'Sono in paese.', [Kit::word('la città', 'città'), Kit::word('il paese', 'paese')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.museo-biblioteca', 'Vado al museo e in biblioteca.', 'I am going to the museum and to the library.', [Kit::word('il museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('al')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.chiesa-paese', 'Andiamo alla biblioteca del paese.', 'We are going to the library of the village.', [Kit::word('la biblioteca', 'biblioteca'), Kit::word('il paese', 'paese'), Kit::form('del')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $eNote = 'È (is) sounds close to e (and), but the sentence tells you it is the verb. The verb has an accent.';
        $andNote = 'E (and) sounds close to è (is), but here it joins two places. The verb has an accent.';

        return [
            Kit::translate($stage, 'check.a.translate.banca-negozio-parco', 'I am going to the bank, to the shop and to the park.', ['Vado in banca, al negozio e al parco.', 'Io vado in banca, al negozio e al parco.', 'Vado alla banca, al negozio e al parco.', 'Io vado alla banca, al negozio e al parco.'], [Kit::word('la banca', 'banca'), Kit::word('il negozio', 'negozio'), Kit::word('il parco', 'parco'), Kit::form('al')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.anna-museo', 'Anna is going to the museum, to the library and to the supermarket.', ['Anna va al museo, in biblioteca e al supermercato.', 'Anna va al museo, alla biblioteca e al supermercato.'], [Kit::word('il museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::word('il supermercato', 'supermercato'), Kit::form('in', true, ['alla'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.chiesa-paese', 'The church of the village is at the park.', ['La chiesa del paese è al parco.'], [Kit::word('la chiesa', 'chiesa'), Kit::word('il paese', 'paese'), Kit::word('il parco', 'parco'), Kit::form('al', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.posta-banca', 'I am going to the post office, to the bank and to the supermarket.', ['Vado alla posta, in banca e al supermercato.', 'Io vado alla posta, in banca e al supermercato.', 'Vado alla posta, alla banca e al supermercato.', 'Io vado alla posta, alla banca e al supermercato.'], [Kit::word('la posta', 'posta'), Kit::word('la banca', 'banca'), Kit::word('il supermercato', 'supermercato'), Kit::form('alla')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.posta', 'Andiamo alla ___.', 'We are going to the post office.', 'posta', Kit::word('la posta', 'posta'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-citta', 'Marta è in ___.', 'Marta is in the city.', 'città', Kit::word('la città', 'città'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.negozio-museo', 'Marta è al negozio del museo.', 'Marta is at the shop of the museum.', [Kit::word('il negozio', 'negozio'), Kit::word('il museo', 'museo'), Kit::form('al', true)], 'dictation', $set, homophoneNote: $eNote),
            Kit::listenType($stage, 'check.a.listen_type.parco-biblioteca', 'Luca va al parco e in biblioteca.', 'Luca is going to the park and to the library.', [Kit::word('il parco', 'parco'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('in', true)], 'dictation', $set, homophoneNote: $andNote),
            Kit::listenType($stage, 'check.a.listen_type.chiesa-paese', 'Marta va alla biblioteca del paese.', 'Marta goes to the library of the village.', [Kit::word('la biblioteca', 'biblioteca'), Kit::word('il paese', 'paese')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.citta', [
                Kit::line('Marta', 'Anna, sei in città?'),
                Kit::line('Anna', 'Sì, sono in città e vado al negozio.'),
                Kit::line('Marta', 'E Paolo? È al parco?'),
                Kit::line('Anna', 'No, Paolo è in biblioteca.'),
            ], [
                Kit::question('Where is Anna?', ['In the city', 'In the park', 'In the library'], 'In the city'),
                Kit::question('Where does Anna go?', ['To the shop', 'To the park', 'To the library'], 'To the shop'),
                Kit::question('Where is Paolo?', ['In the library', 'In the park', 'In the city'], 'In the library'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Anna', 'Paolo'], 'Marta'),
                Kit::question('Is Paolo at the park?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la città', 'città'), Kit::word('il negozio', 'negozio'), Kit::word('il parco', 'parco'), Kit::word('la biblioteca', 'biblioteca')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.museo', 'Read the conversation.', [
                Kit::line('Paolo', 'Luca, vai al museo?'),
                Kit::line('Luca', 'No, vado in chiesa. E tu?'),
                Kit::line('Paolo', 'Vado in biblioteca. È in città.'),
            ], [
                Kit::question('Where is Luca going?', ['To the church', 'To the museum', 'To the library'], 'To the church'),
                Kit::question('Where is Paolo going?', ['To the library', 'To the museum', 'To the park'], 'To the library'),
            ], [Kit::word('il museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::word('la città', 'città')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.posta', 'Vai alla posta?', 'Are you going to the post office?', [['sì', 'no'], $this->placesAnd('vado', 'andiamo')], 'Sì, vado alla posta.', [Kit::word('la posta', 'posta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.supermercato-negozio', 'Sei al supermercato o al negozio?', 'Are you at the supermarket or at the shop?', [['sono'], ['supermercato', 'negozio']], 'Sono al negozio.', [Kit::word('il supermercato', 'supermercato'), Kit::word('il negozio', 'negozio')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.citta', 'Sei in città?', 'Are you in the city?', [['sì', 'no'], $this->placesAnd('sono')], 'Sì, sono in città.', [Kit::word('la città', 'città')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $eNote = 'È (is) sounds close to e (and), but the sentence tells you it is the verb. The verb has an accent.';
        $andNote = 'E (and) sounds close to è (is), but here it joins two words. The verb has an accent.';

        return [
            Kit::translate($stage, 'check.b.translate.posta-chiesa-museo', 'I am going to the post office, to the church and to the museum.', ['Vado alla posta, in chiesa e al museo.', 'Io vado alla posta, in chiesa e al museo.', 'Vado alla posta, alla chiesa e al museo.', 'Io vado alla posta, alla chiesa e al museo.'], [Kit::word('la posta', 'posta'), Kit::word('la chiesa', 'chiesa'), Kit::word('il museo', 'museo'), Kit::form('al')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luca-biblioteca', 'Luca is going to the library, to the park and to the supermarket.', ['Luca va alla biblioteca, al parco e al supermercato.', 'Luca va in biblioteca, al parco e al supermercato.'], [Kit::word('la biblioteca', 'biblioteca'), Kit::word('il parco', 'parco'), Kit::word('il supermercato', 'supermercato'), Kit::form('alla', false, ['in'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.negozio-paese', 'I am going to the shop of the village and to the bank.', ['Vado al negozio del paese e in banca.', 'Io vado al negozio del paese e in banca.', 'Vado al negozio del paese e alla banca.', 'Io vado al negozio del paese e alla banca.'], [Kit::word('il negozio', 'negozio'), Kit::word('il paese', 'paese'), Kit::word('la banca', 'banca'), Kit::form('al', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.citta-chiesa', 'I am in the city, not in the church.', ['Sono in città, non in chiesa.', 'Io sono in città, non in chiesa.', 'Sono in città, non alla chiesa.', 'Io sono in città, non alla chiesa.'], [Kit::word('la città', 'città'), Kit::word('la chiesa', 'chiesa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luca-biblioteca', 'Luca è in ___.', 'Luca is in the library.', 'biblioteca', Kit::word('la biblioteca', 'biblioteca'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-parco', 'Marta va ___ parco.', 'Marta is going to the park.', 'al', Kit::form('al', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.negozio-paese', 'Sono al negozio del paese.', 'I am at the shop of the village.', [Kit::word('il negozio', 'negozio'), Kit::word('il paese', 'paese'), Kit::form('al')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.parco-supermercato', 'Il parco è in città.', 'The park is in the city.', [Kit::word('il parco', 'parco'), Kit::word('la città', 'città')], 'dictation', $set, homophoneNote: $eNote),
            Kit::listenType($stage, 'check.b.listen_type.banca-posta-museo', 'Andiamo in banca, alla posta e al museo.', 'We are going to the bank, to the post office and to the museum.', [Kit::word('la banca', 'banca'), Kit::word('la posta', 'posta'), Kit::word('il museo', 'museo'), Kit::form('in', true)], 'dictation', $set, homophoneNote: $andNote),
        ];
    }
}
