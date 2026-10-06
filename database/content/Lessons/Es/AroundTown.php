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

final class AroundTown implements UnitContent
{
    private const PLACES = ['banco', 'correos', 'supermercado', 'parque', 'tienda', 'iglesia', 'museo', 'biblioteca', 'ciudad', 'pueblo'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'around-town';
    }

    public function words(): array
    {
        return [
            new WordData('el banco', cue: 'bank'),
            new WordData('correos', cue: 'post office', accepted: ['la oficina de correos'], note: 'Correos is the post office. It takes no article: voy a correos. La oficina de correos means the same and is also accepted.'),
            new WordData('el supermercado', cue: 'supermarket'),
            new WordData('el parque', cue: 'park'),
            new WordData('la tienda', cue: 'shop (store)'),
            new WordData('la iglesia', cue: 'church'),
            new WordData('el museo', cue: 'museum'),
            new WordData('la biblioteca', cue: 'library', note: 'La biblioteca is a library, where you borrow books. A bookshop is a librería.'),
            new WordData('la ciudad', cue: 'city'),
            new WordData('el pueblo', cue: 'village (small town)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Voy al banco.', 'english' => 'I am going to the bank.'],
            ['text' => 'Vengo del parque.', 'english' => 'I come from the park.'],
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

        return [
            Kit::gap($stage, 'sentences.choose_gap.banco', 'Voy ___ banco.', ['al', 'a la'], 'al', Kit::form('al', true), 'Banco is a masculine word, so a + el joins into al. A la goes with a feminine word.', 'choose', 'I am going to the bank.'),
            Kit::gap($stage, 'sentences.choose_gap.tienda', 'Voy ___ tienda.', ['a la', 'al'], 'a la', Kit::form('a la', true), 'Tienda is feminine, so a stays apart from la: a la tienda. Al is only for a masculine word.', 'choose', 'I am going to the shop.'),
            Kit::gap($stage, 'sentences.choose_gap.iglesia', 'Vengo ___ iglesia.', ['de la', 'del'], 'de la', Kit::form('de la', true), 'Iglesia is feminine, so de stays apart from la: de la iglesia. Del is only for a masculine word.', 'choose', 'I come from the church.', $vengo),
            Kit::gap($stage, 'sentences.choose_gap.museo', 'Vengo ___ museo.', ['del', 'de la'], 'del', Kit::form('del', true), 'Museo is masculine, so de + el joins into del. De la goes with a feminine word.', 'choose', 'I come from the museum.', $vengo),
            Kit::gap($stage, 'sentences.choose_gap.biblioteca', 'Voy a la ___.', ['biblioteca', 'museo', 'banco'], 'biblioteca', Kit::word('la biblioteca', 'biblioteca'), 'La goes with biblioteca. Museo and banco take el.', 'choose', 'I am going to the library.'),
            Kit::gap($stage, 'sentences.choose_gap.supermercado', 'Estoy en el ___.', ['supermercado', 'iglesia', 'ciudad'], 'supermercado', Kit::word('el supermercado', 'supermercado'), 'El goes with supermercado. Iglesia and ciudad take la.', 'choose', 'I am in the supermarket.'),

            Kit::typeGap($stage, 'sentences.type_gap.parque', 'Voy ___ parque.', 'I am going to the park.', 'al', Kit::form('al'), 'Parque is masculine, so a + el becomes al.'),
            Kit::typeGap($stage, 'sentences.type_gap.iglesia', 'Estoy en la ___.', 'I am in the church.', 'iglesia', Kit::word('la iglesia', 'iglesia')),
            Kit::typeGap($stage, 'sentences.type_gap.pueblo', 'Vengo ___ pueblo.', 'I come from the village.', 'del', Kit::form('del'), 'Pueblo is masculine, so de + el becomes del.', glosses: $vengo),
            Kit::typeGap($stage, 'sentences.type_gap.correos', 'Voy a ___.', 'I am going to the post office.', 'correos', Kit::word('correos')),
            Kit::typeGap($stage, 'sentences.type_gap.ciudad', 'Vengo ___ ciudad.', 'I come from the city.', 'de la', Kit::form('de la'), 'Ciudad is feminine, so de and la stay apart: de la ciudad.', glosses: $vengo),

            Kit::translate($stage, 'sentences.translate.museo', 'I am going to the museum.', ['Voy al museo.', 'Yo voy al museo.'], [Kit::word('el museo', 'museo'), Kit::form('al')]),
            Kit::translate($stage, 'sentences.translate.banco', 'Are you going to the bank? (informal you)', ['¿Vas al banco?', '¿Tú vas al banco?'], [Kit::word('el banco', 'banco'), Kit::form('al')]),
            Kit::translate($stage, 'sentences.translate.biblioteca', 'I am in the library.', ['Estoy en la biblioteca.', 'Yo estoy en la biblioteca.'], [Kit::word('la biblioteca', 'biblioteca')]),

            Kit::build($stage, 'sentences.build.supermercado', 'I am going to the supermarket.', 'Voy al supermercado.', ['la'], [Kit::word('el supermercado', 'supermercado'), Kit::form('al')]),
            Kit::build($stage, 'sentences.build.parque', 'We are going to the park.', 'Vamos al parque.', ['la'], [Kit::word('el parque', 'parque'), Kit::form('al')]),
            Kit::build($stage, 'sentences.build.tienda', 'I am going to the shop.', 'Voy a la tienda.', ['al'], [Kit::word('la tienda', 'tienda'), Kit::form('a la')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.banco', 'Voy al banco.', ['I am going to the bank.', 'I am going to the park.', 'I am in the bank.', 'I come from the bank.'], 'I am going to the bank.', [Kit::word('el banco', 'banco'), Kit::form('al')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.iglesia', '¿Vas a la iglesia?', ['Are you going to the church?', 'Are you going to the museum?', 'Are you in the church?', 'Am I going to the church?'], 'Are you going to the church?', [Kit::word('la iglesia', 'iglesia'), Kit::form('a la')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ciudad', 'Estoy en la ciudad.', ['I am in the city.', 'I am going to the city.', 'I am in the village.', 'I am in the park.'], 'I am in the city.', [Kit::word('la ciudad', 'ciudad')]),
            Kit::listenType($stage, 'sentences.listen_type.correos', 'Voy a correos.', 'I am going to the post office.', [Kit::word('correos')], homophoneNote: 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.'),
            Kit::listenType($stage, 'sentences.listen_type.parque-pueblo', 'Estoy en el parque del pueblo.', 'I am in the park of the village.', [Kit::word('el parque', 'parque'), Kit::word('el pueblo', 'pueblo'), Kit::form('del')]),
            Kit::listenType($stage, 'sentences.listen_type.biblioteca', 'Marta está en la biblioteca.', 'Marta is in the library.', [Kit::word('la biblioteca', 'biblioteca')]),
            Kit::listenType($stage, 'sentences.listen_type.supermercado-tienda', '¿Vas al supermercado o a la tienda?', 'Are you going to the supermarket or to the shop? (informal you)', [Kit::word('el supermercado', 'supermercado'), Kit::word('la tienda', 'tienda'), Kit::form('al', true)], homophoneNote: 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.parque', 'Voy al parque.', 'I am going to the park.', [Kit::word('el parque', 'parque'), Kit::form('al')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ciudad', 'Vamos a la ciudad.', 'We are going to the city.', [Kit::word('la ciudad', 'ciudad'), Kit::form('a la')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pueblo', 'Estoy en el pueblo.', 'I am in the village.', [Kit::word('el pueblo', 'pueblo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.correos-museo', 'Voy a correos y al museo.', 'I am going to the post office and to the museum.', [Kit::word('correos'), Kit::word('el museo', 'museo'), Kit::form('al')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.donde', '¿Dónde estás?', 'Where are you?', [['estoy', 'en'], self::PLACES], 'Estoy en la iglesia.', [Kit::word('la iglesia', 'iglesia')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tienda', '¿Vas a la tienda?', 'Are you going to the shop?', [['sí', 'no'], $this->placesAnd('voy', 'vamos')], 'Sí, voy a la tienda.', [Kit::word('la tienda', 'tienda'), Kit::form('a la')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.banco', '¿Estás en el banco?', 'Are you in the bank?', [['sí', 'no'], $this->placesAnd('estoy')], 'Sí, estoy en el banco.', [Kit::word('el banco', 'banco')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $vengo = ['vengo' => 'I come'];

        return [
            Kit::readPassage($stage, 'task.read_passage.donde-estas', 'Read the conversation about where Pablo and Ana are.', [
                Kit::line('Ana', 'Pablo, ¿dónde estás?'),
                Kit::line('Pablo', 'Estoy en el pueblo. Voy a correos y al banco.'),
                Kit::line('Ana', 'Estoy en la ciudad. Vengo del museo y voy a la biblioteca.'),
            ], [
                Kit::question('Where is Pablo?', ['In the village', 'In the city', 'In the library'], 'In the village'),
                Kit::question('Where does Pablo go?', ['To the post office and the bank', 'To the museum', 'To the park'], 'To the post office and the bank'),
                Kit::question('Where does Ana come from?', ['From the museum', 'From the bank', 'From the village'], 'From the museum'),
            ], [Kit::word('el pueblo', 'pueblo'), Kit::word('correos'), Kit::word('el banco', 'banco'), Kit::word('la ciudad', 'ciudad'), Kit::word('el museo', 'museo'), Kit::word('la biblioteca', 'biblioteca')], 'read', glosses: $vengo),
            Kit::gap($stage, 'task.choose_gap.iglesia-marta', 'Voy ___ iglesia con Marta.', ['a la', 'al'], 'a la', Kit::form('a la', true), 'Iglesia is feminine, so a stays apart from la: a la iglesia.', 'read', 'I am going to the church with Marta.'),
            Kit::gap($stage, 'task.choose_gap.pueblo-luis', 'Voy al ___ con Luis.', ['pueblo', 'ciudad', 'tienda'], 'pueblo', Kit::word('el pueblo', 'pueblo'), 'Al goes with a masculine word, and pueblo is masculine. Ciudad and tienda take la.', 'read', 'I am going to the village with Luis.'),

            Kit::transform($stage, 'task.transform.parque', 'Say that you are in the park.', 'Voy al parque.', ['Estoy en el parque.', 'Yo estoy en el parque.'], [Kit::word('el parque', 'parque')]),
            Kit::transform($stage, 'task.transform.museo', 'Say that you come from the museum.', 'Voy al museo.', ['Vengo del museo.', 'Yo vengo del museo.'], [Kit::word('el museo', 'museo'), Kit::form('del', true)], $vengo),
            Kit::transform($stage, 'task.transform.biblioteca', 'Ask a friend instead (informal you).', 'Voy a la biblioteca.', ['¿Vas a la biblioteca?', '¿Tú vas a la biblioteca?'], [Kit::word('la biblioteca', 'biblioteca'), Kit::form('a la')]),
            Kit::writeGuided($stage, 'task.write_guided.ciudad-parque', 'Say that you are in the city and that you are going to the park.', ['estoy en', 'la ciudad', 'voy', 'parque'], 'Estoy en la ciudad y voy al parque.', [
                ['forms' => ['ciudad'], 'term' => 'la ciudad'],
                ['forms' => ['al'], 'term' => null],
                ['forms' => ['parque'], 'term' => 'el parque'],
            ], [Kit::word('la ciudad', 'ciudad'), Kit::word('el parque', 'parque'), Kit::form('al')]),
            Kit::writeGuided($stage, 'task.write_guided.museo-supermercado', 'Say that you come from the museum and that you are going to the supermarket.', ['vengo', 'museo', 'voy', 'supermercado'], 'Vengo del museo y voy al supermercado.', [
                ['forms' => ['del'], 'term' => null],
                ['forms' => ['museo'], 'term' => 'el museo'],
                ['forms' => ['al'], 'term' => null],
                ['forms' => ['supermercado'], 'term' => 'el supermercado'],
            ], [Kit::word('el museo', 'museo'), Kit::word('el supermercado', 'supermercado'), Kit::form('del', true)], $vengo),
            Kit::build($stage, 'task.build.parque-vengo', 'I come from the park.', 'Vengo del parque.', ['de', 'la'], [Kit::word('el parque', 'parque'), Kit::form('del', true)], 'write', $vengo),
            Kit::build($stage, 'task.build.parque-iglesia', 'I am going to the park and to the church.', 'Voy al parque y a la iglesia.', ['de', 'el'], [Kit::word('el parque', 'parque'), Kit::word('la iglesia', 'iglesia'), Kit::form('al')]),
            Kit::build($stage, 'task.build.marta-parque', 'Marta is in the park of the village.', 'Marta está en el parque del pueblo.', ['de', 'la'], [Kit::word('el parque', 'parque'), Kit::word('el pueblo', 'pueblo'), Kit::form('del')]),
            Kit::translate($stage, 'task.translate.banco-correos', 'I am going to the bank and to the post office.', ['Voy al banco y a correos.', 'Yo voy al banco y a correos.', 'Voy a correos y al banco.', 'Yo voy a correos y al banco.'], [Kit::word('el banco', 'banco'), Kit::word('correos'), Kit::form('al')]),
            Kit::translate($stage, 'task.translate.pueblo-ciudad', 'I come from the village, not from the city.', ['Vengo del pueblo, no de la ciudad.', 'Yo vengo del pueblo, no de la ciudad.'], [Kit::word('el pueblo', 'pueblo'), Kit::word('la ciudad', 'ciudad'), Kit::form('del', true)], glosses: $vengo),

            Kit::listenPassage($stage, 'task.listen_passage.tienda-museo', [
                Kit::line('Marta', 'Luis, ¿dónde estás?'),
                Kit::line('Luis', 'Estoy en la tienda del museo.'),
                Kit::line('Marta', '¿Vas a la iglesia?'),
                Kit::line('Luis', 'No, voy al supermercado. ¿Y tú, dónde estás?'),
                Kit::line('Marta', 'Yo estoy en la biblioteca.'),
            ], [
                Kit::question('Where is Luis?', ['In the museum shop', 'In the library', 'In the supermarket'], 'In the museum shop'),
                Kit::question('Where is Luis going?', ['To the supermarket', 'To the church', 'To the library'], 'To the supermarket'),
                Kit::question('Where is Marta?', ['In the library', 'In the museum', 'In the church'], 'In the library'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is Luis going to the church?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la tienda', 'tienda'), Kit::word('el museo', 'museo'), Kit::word('la iglesia', 'iglesia'), Kit::word('el supermercado', 'supermercado'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('del')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.banco-correos', 'Vamos al banco y a correos.', 'We are going to the bank and to the post office.', [Kit::word('el banco', 'banco'), Kit::word('correos'), Kit::form('al')], 'listen', homophoneNote: 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.'),
            Kit::listenType($stage, 'task.listen_type.parque-ciudad', '¿Estás en el parque o en la ciudad?', 'Are you in the park or in the city? (informal you)', [Kit::word('el parque', 'parque'), Kit::word('la ciudad', 'ciudad')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.iglesia-pueblo', 'Ana está en la iglesia del pueblo.', 'Ana is in the church of the village.', [Kit::word('la iglesia', 'iglesia'), Kit::word('el pueblo', 'pueblo'), Kit::form('del')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.parque-tienda', '¿Vas al parque o a la tienda?', 'Are you going to the park or to the shop?', [['voy', 'vamos'], self::PLACES], 'Voy a la tienda.', [Kit::word('la tienda', 'tienda'), Kit::form('a la')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marta', '¿Dónde está Marta?', 'Where is Marta?', [['está', 'en'], self::PLACES], 'Marta está en la biblioteca.', [Kit::word('la biblioteca', 'biblioteca')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.supermercado', '¿Estás en el supermercado?', 'Are you in the supermarket?', [['sí', 'no'], $this->placesAnd('estoy')], 'Sí, estoy en el supermercado.', [Kit::word('el supermercado', 'supermercado')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ciudad-pueblo', '¿Estás en la ciudad o en el pueblo?', 'Are you in the city or in the village?', [['estoy'], ['ciudad', 'pueblo']], 'Estoy en el pueblo.', [Kit::word('la ciudad', 'ciudad'), Kit::word('el pueblo', 'pueblo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.museo-biblioteca', 'Voy al museo y a la biblioteca.', 'I am going to the museum and to the library.', [Kit::word('el museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('al')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.iglesia-pueblo', 'Vamos a la iglesia del pueblo.', 'We are going to the church of the village.', [Kit::word('la iglesia', 'iglesia'), Kit::word('el pueblo', 'pueblo'), Kit::form('del')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $note = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

        return [
            Kit::translate($stage, 'check.a.translate.banco-tienda-parque', 'I am going to the bank, to the shop and to the park.', ['Voy al banco, a la tienda y al parque.', 'Yo voy al banco, a la tienda y al parque.'], [Kit::word('el banco', 'banco'), Kit::word('la tienda', 'tienda'), Kit::word('el parque', 'parque'), Kit::form('al')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-museo', 'Ana is going to the museum, to the library and to the supermarket.', ['Ana va al museo, a la biblioteca y al supermercado.'], [Kit::word('el museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::word('el supermercado', 'supermercado'), Kit::form('a la')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.iglesia-pueblo', 'The church of the village is in the park.', ['La iglesia del pueblo está en el parque.'], [Kit::word('la iglesia', 'iglesia'), Kit::word('el pueblo', 'pueblo'), Kit::word('el parque', 'parque'), Kit::form('del', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.correos-banco', 'I am going to the post office, to the bank and to the supermarket.', ['Voy a correos, al banco y al supermercado.', 'Yo voy a correos, al banco y al supermercado.'], [Kit::word('correos'), Kit::word('el banco', 'banco'), Kit::word('el supermercado', 'supermercado'), Kit::form('al')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.correos', 'Vamos a ___.', 'We are going to the post office.', 'correos', Kit::word('correos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-ciudad', 'Marta está en la ___.', 'Marta is in the city.', 'ciudad', Kit::word('la ciudad', 'ciudad'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.tienda-museo', 'Marta está en la tienda del museo.', 'Marta is in the shop of the museum.', [Kit::word('la tienda', 'tienda'), Kit::word('el museo', 'museo'), Kit::form('del', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.parque-biblioteca', 'Luis va al parque y a la biblioteca.', 'Luis is going to the park and to the library.', [Kit::word('el parque', 'parque'), Kit::word('la biblioteca', 'biblioteca'), Kit::form('al')], 'dictation', $set, homophoneNote: $note),
            Kit::listenType($stage, 'check.a.listen_type.iglesia-pueblo', 'Estoy en la iglesia del pueblo.', 'I am in the church of the village.', [Kit::word('la iglesia', 'iglesia'), Kit::word('el pueblo', 'pueblo')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.ciudad', [
                Kit::line('Marta', 'Ana, ¿estás en la ciudad?'),
                Kit::line('Ana', 'Sí, estoy en la ciudad y voy a la tienda.'),
                Kit::line('Marta', '¿Y Pablo? ¿Está en el parque?'),
                Kit::line('Ana', 'No, Pablo está en la biblioteca.'),
            ], [
                Kit::question('Where is Ana?', ['In the city', 'In the park', 'In the library'], 'In the city'),
                Kit::question('Where does Ana go?', ['To the shop', 'To the park', 'To the library'], 'To the shop'),
                Kit::question('Where is Pablo?', ['In the library', 'In the park', 'In the city'], 'In the library'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Ana', 'Pablo'], 'Marta'),
                Kit::question('Is Pablo in the park?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la ciudad', 'ciudad'), Kit::word('la tienda', 'tienda'), Kit::word('el parque', 'parque'), Kit::word('la biblioteca', 'biblioteca')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.museo', 'Read the conversation.', [
                Kit::line('Pablo', 'Luis, ¿vas al museo?'),
                Kit::line('Luis', 'No, voy a la iglesia. ¿Y tú?'),
                Kit::line('Pablo', 'Voy a la biblioteca de la ciudad.'),
            ], [
                Kit::question('Where is Luis going?', ['To the church', 'To the museum', 'To the library'], 'To the church'),
                Kit::question('Where is Pablo going?', ['To the library', 'To the museum', 'To the park'], 'To the library'),
            ], [Kit::word('el museo', 'museo'), Kit::word('la biblioteca', 'biblioteca'), Kit::word('la ciudad', 'ciudad')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.correos', '¿Vas a correos?', 'Are you going to the post office?', [['sí', 'no'], $this->placesAnd('voy', 'vamos')], 'Sí, voy a correos.', [Kit::word('correos')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.supermercado-tienda', '¿Estás en el supermercado o en la tienda?', 'Are you in the supermarket or in the shop?', [['estoy'], ['supermercado', 'tienda']], 'Estoy en la tienda.', [Kit::word('el supermercado', 'supermercado'), Kit::word('la tienda', 'tienda')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ciudad', '¿Estás en la ciudad?', 'Are you in the city?', [['sí', 'no'], $this->placesAnd('estoy')], 'Sí, estoy en la ciudad.', [Kit::word('la ciudad', 'ciudad')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $note = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

        return [
            Kit::translate($stage, 'check.b.translate.correos-iglesia-museo', 'I am going to the post office, to the church and to the museum.', ['Voy a correos, a la iglesia y al museo.', 'Yo voy a correos, a la iglesia y al museo.'], [Kit::word('correos'), Kit::word('la iglesia', 'iglesia'), Kit::word('el museo', 'museo'), Kit::form('al')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luis-biblioteca', 'Luis is going to the library, to the park and to the supermarket.', ['Luis va a la biblioteca, al parque y al supermercado.'], [Kit::word('la biblioteca', 'biblioteca'), Kit::word('el parque', 'parque'), Kit::word('el supermercado', 'supermercado'), Kit::form('a la')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tienda-pueblo', 'I am going to the shop of the village and to the bank.', ['Voy a la tienda del pueblo y al banco.', 'Yo voy a la tienda del pueblo y al banco.'], [Kit::word('la tienda', 'tienda'), Kit::word('el pueblo', 'pueblo'), Kit::word('el banco', 'banco'), Kit::form('del', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ciudad-iglesia', 'I am in the city, not in the church.', ['Estoy en la ciudad, no en la iglesia.', 'Yo estoy en la ciudad, no en la iglesia.'], [Kit::word('la ciudad', 'ciudad'), Kit::word('la iglesia', 'iglesia')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-biblioteca', 'Luis está en la ___.', 'Luis is in the library.', 'biblioteca', Kit::word('la biblioteca', 'biblioteca'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-parque', 'Marta va ___ parque.', 'Marta is going to the park.', 'al', Kit::form('al', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tienda-pueblo', 'Estoy en la tienda del pueblo.', 'I am in the shop of the village.', [Kit::word('la tienda', 'tienda'), Kit::word('el pueblo', 'pueblo'), Kit::form('del')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.parque-supermercado', 'El parque y el supermercado están en la ciudad.', 'The park and the supermarket are in the city.', [Kit::word('el parque', 'parque'), Kit::word('el supermercado', 'supermercado'), Kit::word('la ciudad', 'ciudad')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.banco-correos-museo', 'Vamos al banco, a correos y al museo.', 'We are going to the bank, to the post office and to the museum.', [Kit::word('el banco', 'banco'), Kit::word('correos'), Kit::word('el museo', 'museo'), Kit::form('al')], 'dictation', $set, homophoneNote: $note),
        ];
    }
}
