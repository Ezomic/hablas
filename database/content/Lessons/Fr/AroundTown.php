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

final class AroundTown implements UnitContent
{
    private const PLACES = ['banque', 'poste', 'supermarché', 'parc', 'magasin', "l'église", 'église', 'musée', 'bibliothèque', 'ville', 'village'];

    private const A_NOTE = 'À (to, at, with an accent) sounds the same as a (a form of avoir, without an accent), and the sentence tells you which is which: here it is à.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins two things.';

    private const EST_A_NOTE = 'Est (is, the verb) and et (and) sound very close, and à (at, with an accent) sounds the same as a (a form of avoir, without an accent). The sentence tells you which is which: here it is est and à.';

    private const ET_A_NOTE = 'Et (and) and est (is, the verb) sound very close, and à (to, with an accent) sounds the same as a (a form of avoir, without an accent). The sentence tells you which is which: here it is et and à.';

    private const OU_NOTE = 'Ou (or, without an accent) and où (where, with an accent) sound the same, and the sentence tells you which is which: here it is ou, meaning or.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'around-town';
    }

    public function words(): array
    {
        return [
            new WordData('la banque', cue: 'bank'),
            new WordData('la poste', cue: 'post office', accepted: ['le bureau de poste'], note: 'La poste is the post office, and you say à la poste. Le bureau de poste means the same and is also accepted.'),
            new WordData('le supermarché', cue: 'supermarket'),
            new WordData('le parc', cue: 'park'),
            new WordData('le magasin', cue: 'shop (store)'),
            new WordData("l'église", cue: 'church (feminine: une église; the l\' hides la)', forms: ['église']),
            new WordData('le musée', cue: 'museum'),
            new WordData('la bibliothèque', cue: 'library', note: 'La bibliothèque is a library, where you borrow books. A bookshop is a librairie.'),
            new WordData('la ville', cue: 'city (also town; en ville means in town)'),
            new WordData('le village', cue: 'village'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je vais au parc.', 'english' => 'I am going to the park.'],
            ['text' => 'Je viens du musée.', 'english' => 'I come from the museum.'],
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

    /** @return list<string> */
    private function placesAnd(string ...$extra): array
    {
        return array_values([...$extra, ...self::PLACES]);
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $viens = ['viens' => 'I come'];

        return [
            Kit::gap($stage, 'sentences.choose_gap.parc', 'Je vais ___ parc.', ['au', 'à la'], 'au', Kit::form('au', true), 'Parc is masculine, so à + le joins into au. À la goes with a feminine word.', 'choose', 'I am going to the park.'),
            Kit::gap($stage, 'sentences.choose_gap.banque', 'Je vais ___ banque.', ['à la', 'au'], 'à la', Kit::form('à la', true), 'Banque is feminine, so à stays apart from la: à la banque. Au is only for a masculine word.', 'choose', 'I am going to the bank.'),
            Kit::gap($stage, 'sentences.choose_gap.bibliotheque', 'Je viens ___ bibliothèque.', ['de la', 'du'], 'de la', Kit::form('de la', true), 'Bibliothèque is feminine, so de stays apart from la: de la bibliothèque. Du is only for a masculine word.', 'choose', 'I come from the library.', $viens),
            Kit::gap($stage, 'sentences.choose_gap.musee', 'Je viens ___ musée.', ['du', 'de la'], 'du', Kit::form('du', true), 'Musée is masculine, so de + le joins into du. De la goes with a feminine word.', 'choose', 'I come from the museum.', $viens),
            Kit::gap($stage, 'sentences.choose_gap.bibliotheque-noun', 'Je vais à la ___.', ['bibliothèque', 'musée', 'parc'], 'bibliothèque', Kit::word('la bibliothèque', 'bibliothèque'), 'La goes with bibliothèque. Musée and parc take le.', 'choose', 'I am going to the library.'),
            Kit::gap($stage, 'sentences.choose_gap.supermarche', 'Je suis au ___.', ['supermarché', 'église', 'ville'], 'supermarché', Kit::word('le supermarché', 'supermarché'), 'Au goes with a masculine word, and supermarché is masculine. Église and ville are feminine.', 'choose', 'I am at the supermarket.'),

            Kit::typeGap($stage, 'sentences.type_gap.supermarche', 'Je vais ___ supermarché.', 'I am going to the supermarket.', 'au', Kit::form('au'), 'Supermarché is masculine, so à + le becomes au.'),
            Kit::typeGap($stage, 'sentences.type_gap.eglise', 'Je suis à ___.', 'I am at the church.', "l'église", Kit::word("l'église", "l'église"), 'Église starts with a vowel, so la shortens to l\' and à stays apart: à l\'église.'),
            Kit::typeGap($stage, 'sentences.type_gap.village', 'Je viens ___ village.', 'I come from the village.', 'du', Kit::form('du'), 'Village is masculine, so de + le becomes du.', glosses: $viens),
            Kit::typeGap($stage, 'sentences.type_gap.poste', 'Je vais à la ___.', 'I am going to the post office.', 'poste', Kit::word('la poste', 'poste')),
            Kit::typeGap($stage, 'sentences.type_gap.ville', 'Je viens ___ ville.', 'I come from the city.', 'de la', Kit::form('de la'), 'Ville is feminine, so de and la stay apart: de la ville.', glosses: $viens),

            Kit::translate($stage, 'sentences.translate.musee', 'I am going to the museum.', ['Je vais au musée.'], [Kit::word('le musée', 'musée'), Kit::form('au')]),
            Kit::translate($stage, 'sentences.translate.banque', 'Are you going to the bank? (informal you)', ['Tu vas à la banque ?', 'Vas-tu à la banque ?'], [Kit::word('la banque', 'banque'), Kit::form('à la')]),
            Kit::translate($stage, 'sentences.translate.bibliotheque', 'I am at the library.', ['Je suis à la bibliothèque.'], [Kit::word('la bibliothèque', 'bibliothèque')]),

            Kit::build($stage, 'sentences.build.supermarche', 'I am going to the supermarket.', 'Je vais au supermarché.', ['la'], [Kit::word('le supermarché', 'supermarché'), Kit::form('au')]),
            Kit::build($stage, 'sentences.build.parc', 'We are going to the park.', 'Nous allons au parc.', ['la'], [Kit::word('le parc', 'parc'), Kit::form('au')]),
            Kit::build($stage, 'sentences.build.ville', 'I come from the city.', 'Je viens de la ville.', ['du'], [Kit::word('la ville', 'ville'), Kit::form('de la')], glosses: $viens),

            Kit::listenChoose($stage, 'sentences.listen_choose.magasin', 'Je vais au magasin.', ['I am going to the shop.', 'I am going to the park.', 'I am at the shop.', 'I come from the shop.'], 'I am going to the shop.', [Kit::word('le magasin', 'magasin'), Kit::form('au')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.eglise', "Tu vas à l'église ?", ['Are you going to the church?', 'Are you going to the museum?', 'Are you at the church?', 'Am I going to the church?'], 'Are you going to the church?', [Kit::word("l'église"), Kit::form("à l'")]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ville', 'Je suis en ville.', ['I am in town.', 'I am going to town.', 'I am in the village.', 'I am in the park.'], 'I am in town.', [Kit::word('la ville', 'ville')]),
            Kit::listenType($stage, 'sentences.listen_type.poste', 'Je vais à la poste.', 'I am going to the post office.', [Kit::word('la poste', 'poste'), Kit::form('à la')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.parc-village', 'Je suis au parc du village.', 'I am at the village park.', [Kit::word('le parc', 'parc'), Kit::word('le village', 'village'), Kit::form('du')]),
            Kit::listenType($stage, 'sentences.listen_type.bibliotheque', 'Marie est à la bibliothèque.', 'Marie is at the library.', [Kit::word('la bibliothèque', 'bibliothèque')], homophoneNote: self::EST_A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.supermarche-magasin', 'Tu vas au supermarché ou au magasin ?', 'Are you going to the supermarket or to the shop? (informal you)', [Kit::word('le supermarché', 'supermarché'), Kit::word('le magasin', 'magasin'), Kit::form('au', true)], homophoneNote: self::OU_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.parc', 'Je vais au parc.', 'I am going to the park.', [Kit::word('le parc', 'parc'), Kit::form('au')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ville', 'Nous allons en ville.', 'We are going into town.', [Kit::word('la ville', 'ville')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.village', 'Je suis au village.', 'I am in the village.', [Kit::word('le village', 'village'), Kit::form('au')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.poste-musee', 'À la poste et au musée.', 'To the post office and to the museum.', [Kit::word('la poste', 'poste'), Kit::word('le musée', 'musée'), Kit::form('au')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ou', 'Tu es où ?', 'Where are you?', [['suis', 'à', 'au'], self::PLACES], "Je suis à l'église.", [Kit::word("l'église")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.magasin', 'Tu vas au magasin ?', 'Are you going to the shop?', [['oui', 'non'], $this->placesAnd('vais', 'allons')], 'Oui, je vais au magasin.', [Kit::word('le magasin', 'magasin'), Kit::form('au')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.banque', 'Tu es à la banque ?', 'Are you at the bank?', [['oui', 'non'], $this->placesAnd('suis')], 'Oui, je suis à la banque.', [Kit::word('la banque', 'banque')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $viens = ['viens' => 'I come'];

        return [
            Kit::readPassage($stage, 'task.read_passage.ou-es-tu', 'Read the conversation about where Paul and Anne are.', [
                Kit::line('Anne', 'Paul, tu es où ?'),
                Kit::line('Paul', 'Je suis au village. Je vais à la poste et à la banque.'),
                Kit::line('Anne', 'Je suis en ville. Je viens du musée et je vais à la bibliothèque.'),
            ], [
                Kit::question('Where is Paul?', ['In the village', 'In the city', 'In the library'], 'In the village'),
                Kit::question('Where does Paul go?', ['To the post office and the bank', 'To the museum', 'To the park'], 'To the post office and the bank'),
                Kit::question('Where does Anne come from?', ['From the museum', 'From the bank', 'From the village'], 'From the museum'),
            ], [Kit::word('le village', 'village'), Kit::word('la poste', 'poste'), Kit::word('la banque', 'banque'), Kit::word('la ville', 'ville'), Kit::word('le musée', 'musée'), Kit::word('la bibliothèque', 'bibliothèque')], 'read', glosses: $viens),
            Kit::gap($stage, 'task.choose_gap.bibliotheque-marie', 'Je vais ___ bibliothèque avec Marie.', ['à la', 'au'], 'à la', Kit::form('à la', true), 'Bibliothèque is feminine, so à stays apart from la: à la bibliothèque.', 'read', 'I am going to the library with Marie.'),
            Kit::gap($stage, 'task.choose_gap.village-luc', 'Je vais au ___ avec Luc.', ['village', 'ville', 'banque'], 'village', Kit::word('le village', 'village'), 'Au goes with a masculine word, and village is masculine. Ville and banque take la.', 'read', 'I am going to the village with Luc.'),

            Kit::transform($stage, 'task.transform.parc', 'Say that you are at the park.', 'Je vais au parc.', ['Je suis au parc.'], [Kit::word('le parc', 'parc'), Kit::form('au')]),
            Kit::transform($stage, 'task.transform.musee', 'Say that you come from the museum.', 'Je vais au musée.', ['Je viens du musée.'], [Kit::word('le musée', 'musée'), Kit::form('du', true)], $viens),
            Kit::transform($stage, 'task.transform.bibliotheque', 'Ask a friend instead (informal you).', 'Je vais à la bibliothèque.', ['Tu vas à la bibliothèque ?', 'Vas-tu à la bibliothèque ?'], [Kit::word('la bibliothèque', 'bibliothèque'), Kit::form('à la')]),
            Kit::writeGuided($stage, 'task.write_guided.ville-parc', 'Say that you are in town and that you are going to the park.', ['je suis', 'en ville', 'je vais', 'parc'], 'Je suis en ville et je vais au parc.', [
                ['forms' => ['en', 'dans'], 'term' => null],
                ['forms' => ['ville'], 'term' => 'la ville'],
                ['forms' => ['au'], 'term' => null],
                ['forms' => ['parc'], 'term' => 'le parc'],
            ], [Kit::word('la ville', 'ville'), Kit::word('le parc', 'parc'), Kit::form('au')]),
            Kit::writeGuided($stage, 'task.write_guided.musee-supermarche', 'Say that you come from the museum and that you are going to the supermarket.', ['je viens', 'musée', 'je vais', 'supermarché'], 'Je viens du musée et je vais au supermarché.', [
                ['forms' => ['du'], 'term' => null],
                ['forms' => ['musée'], 'term' => 'le musée'],
                ['forms' => ['au'], 'term' => null],
                ['forms' => ['supermarché'], 'term' => 'le supermarché'],
            ], [Kit::word('le musée', 'musée'), Kit::word('le supermarché', 'supermarché'), Kit::form('du', true)], $viens),
            Kit::build($stage, 'task.build.parc-viens', 'I come from the park.', 'Je viens du parc.', ['de', 'la'], [Kit::word('le parc', 'parc'), Kit::form('du', true)], 'write', $viens),
            Kit::build($stage, 'task.build.parc-eglise', 'I am going to the park and to the church.', "Je vais au parc et à l'église.", ['du', 'la'], [Kit::word('le parc', 'parc'), Kit::word("l'église"), Kit::form('au')]),
            Kit::build($stage, 'task.build.marie-parc', 'Marie is at the park of the village.', 'Marie est au parc du village.', ['de', 'la'], [Kit::word('le parc', 'parc'), Kit::word('le village', 'village'), Kit::form('du')]),
            Kit::translate($stage, 'task.translate.banque-poste', 'I am going to the bank and to the post office.', ['Je vais à la banque et à la poste.', 'Je vais à la poste et à la banque.'], [Kit::word('la banque', 'banque'), Kit::word('la poste', 'poste'), Kit::form('à la')]),
            Kit::translate($stage, 'task.translate.village-ville', 'I come from the village, not from the city.', ['Je viens du village, pas de la ville.', 'Je viens du village et pas de la ville.'], [Kit::word('le village', 'village'), Kit::word('la ville', 'ville'), Kit::form('du', true)], glosses: $viens),

            Kit::listenPassage($stage, 'task.listen_passage.magasin-musee', [
                Kit::line('Marie', 'Luc, tu es où ?'),
                Kit::line('Luc', 'Je suis au magasin du musée.'),
                Kit::line('Marie', "Tu vas à l'église ?"),
                Kit::line('Luc', 'Non, je vais au supermarché. Tu es où ?'),
                Kit::line('Marie', 'Je suis à la bibliothèque.'),
            ], [
                Kit::question('Where is Luc?', ['In the museum shop', 'In the library', 'In the supermarket'], 'In the museum shop'),
                Kit::question('Where is Luc going?', ['To the supermarket', 'To the church', 'To the library'], 'To the supermarket'),
                Kit::question('Where is Marie?', ['In the library', 'In the museum', 'In the church'], 'In the library'),
            ], [
                Kit::question('Who speaks first?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Is Luc going to the church?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le magasin', 'magasin'), Kit::word('le musée', 'musée'), Kit::word("l'église"), Kit::word('le supermarché', 'supermarché'), Kit::word('la bibliothèque', 'bibliothèque'), Kit::form('du')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.banque-poste', 'Nous allons à la banque et à la poste.', 'We are going to the bank and to the post office.', [Kit::word('la banque', 'banque'), Kit::word('la poste', 'poste'), Kit::form('à la')], 'listen', homophoneNote: self::ET_A_NOTE),
            Kit::listenType($stage, 'task.listen_type.parc-ville', 'Tu es au parc ou en ville ?', 'Are you at the park or in town? (informal you)', [Kit::word('le parc', 'parc'), Kit::word('la ville', 'ville')], 'listen', homophoneNote: self::OU_NOTE),
            Kit::listenType($stage, 'task.listen_type.eglise-village', "Marie est à l'église du village.", 'Marie is at the village church.', [Kit::word("l'église"), Kit::word('le village', 'village'), Kit::form('du')], 'listen', homophoneNote: self::EST_A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.parc-magasin', 'Tu vas au parc ou au magasin ?', 'Are you going to the park or to the shop?', [['vais', 'allons'], self::PLACES], 'Je vais au magasin.', [Kit::word('le magasin', 'magasin'), Kit::form('au')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marie', 'Où est Marie ?', 'Where is Marie?', [['est', 'à', 'au'], self::PLACES], 'Marie est à la bibliothèque.', [Kit::word('la bibliothèque', 'bibliothèque')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.supermarche', 'Tu es au supermarché ?', 'Are you at the supermarket?', [['oui', 'non'], $this->placesAnd('suis')], 'Oui, je suis au supermarché.', [Kit::word('le supermarché', 'supermarché')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ville-village', 'Tu es en ville ou au village ?', 'Are you in town or in the village?', [['suis'], ['ville', 'village']], 'Je suis au village.', [Kit::word('la ville', 'ville'), Kit::word('le village', 'village')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.musee-bibliotheque', 'Je vais au musée et à la bibliothèque.', 'I am going to the museum and to the library.', [Kit::word('le musée', 'musée'), Kit::word('la bibliothèque', 'bibliothèque'), Kit::form('au')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.eglise-village', "Nous allons à l'église du village.", 'We are going to the church of the village.', [Kit::word("l'église"), Kit::word('le village', 'village'), Kit::form('du')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.banque-magasin-parc', 'I am going to the bank, to the shop and to the park.', ['Je vais à la banque, au magasin et au parc.'], [Kit::word('la banque', 'banque'), Kit::word('le magasin', 'magasin'), Kit::word('le parc', 'parc'), Kit::form('au')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.anne-musee', 'Anne is going to the museum, to the library and to the supermarket.', ['Anne va au musée, à la bibliothèque et au supermarché.'], [Kit::word('le musée', 'musée'), Kit::word('la bibliothèque', 'bibliothèque'), Kit::word('le supermarché', 'supermarché'), Kit::form('à la')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.eglise-village', 'The church of the village is in the park.', ["L'église du village est dans le parc."], [Kit::word("l'église"), Kit::word('le village', 'village'), Kit::word('le parc', 'parc'), Kit::form('du', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.poste-banque', 'Anne is going to the post office and to the bank.', ['Anne va à la poste et à la banque.'], [Kit::word('la poste', 'poste'), Kit::word('la banque', 'banque'), Kit::form('à la')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.poste', 'Nous allons à la ___.', 'We are going to the post office.', 'poste', Kit::word('la poste', 'poste'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marie-ville', 'Marie est en ___.', 'Marie is in town.', 'ville', Kit::word('la ville', 'ville'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.magasin-musee', 'Marie est au magasin du musée.', 'Marie is at the shop of the museum.', [Kit::word('le magasin', 'magasin'), Kit::word('le musée', 'musée'), Kit::form('du', true)], 'dictation', $set, homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.parc-bibliotheque', 'Luc va au parc et à la bibliothèque.', 'Luc is going to the park and to the library.', [Kit::word('le parc', 'parc'), Kit::word('la bibliothèque', 'bibliothèque'), Kit::form('au')], 'dictation', $set, homophoneNote: self::ET_A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.eglise-village', "Je suis à l'église du village.", 'I am at the church of the village.', [Kit::word("l'église"), Kit::word('le village', 'village')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.ville', [
                Kit::line('Marie', 'Anne, tu es en ville ?'),
                Kit::line('Anne', 'Oui, je suis en ville et je vais au magasin.'),
                Kit::line('Marie', 'Et Paul ? Il est au parc ?'),
                Kit::line('Anne', 'Non, Paul est à la bibliothèque.'),
            ], [
                Kit::question('Where is Anne?', ['In the city', 'In the park', 'In the library'], 'In the city'),
                Kit::question('Where does Anne go?', ['To the shop', 'To the park', 'To the library'], 'To the shop'),
                Kit::question('Where is Paul?', ['In the library', 'In the park', 'In the city'], 'In the library'),
            ], [
                Kit::question('Who asks the questions?', ['Marie', 'Anne', 'Paul'], 'Marie'),
                Kit::question('Is Paul in the park?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la ville', 'ville'), Kit::word('le magasin', 'magasin'), Kit::word('le parc', 'parc'), Kit::word('la bibliothèque', 'bibliothèque')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.musee', 'Read the conversation.', [
                Kit::line('Paul', 'Luc, tu vas au musée ?'),
                Kit::line('Luc', "Non, je vais à l'église. Tu vas où ?"),
                Kit::line('Paul', 'Je vais à la bibliothèque de la ville.'),
            ], [
                Kit::question('Where is Luc going?', ['To the church', 'To the museum', 'To the library'], 'To the church'),
                Kit::question('Where is Paul going?', ['To the library', 'To the museum', 'To the park'], 'To the library'),
            ], [Kit::word('le musée', 'musée'), Kit::word("l'église"), Kit::word('la bibliothèque', 'bibliothèque'), Kit::word('la ville', 'ville')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.poste', 'Tu vas à la poste ?', 'Are you going to the post office?', [['oui', 'non'], $this->placesAnd('vais', 'allons')], 'Oui, je vais à la poste.', [Kit::word('la poste', 'poste')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.supermarche-magasin', 'Tu es au supermarché ou au magasin ?', 'Are you at the supermarket or at the shop?', [['suis'], ['supermarché', 'magasin']], 'Je suis au magasin.', [Kit::word('le supermarché', 'supermarché'), Kit::word('le magasin', 'magasin')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ville', 'Tu es en ville ?', 'Are you in town?', [['oui', 'non'], $this->placesAnd('suis', 'en')], 'Oui, je suis en ville.', [Kit::word('la ville', 'ville')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.poste-eglise-musee', 'I am going to the post office, to the church and to the museum.', ["Je vais à la poste, à l'église et au musée."], [Kit::word('la poste', 'poste'), Kit::word("l'église"), Kit::word('le musée', 'musée'), Kit::form('au')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luc-bibliotheque', 'Luc is going to the library, to the park and to the supermarket.', ['Luc va à la bibliothèque, au parc et au supermarché.'], [Kit::word('la bibliothèque', 'bibliothèque'), Kit::word('le parc', 'parc'), Kit::word('le supermarché', 'supermarché'), Kit::form('à la')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.magasin-village', 'I am going to the shop of the village and to the bank.', ['Je vais au magasin du village et à la banque.'], [Kit::word('le magasin', 'magasin'), Kit::word('le village', 'village'), Kit::word('la banque', 'banque'), Kit::form('du', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ville-eglise', 'I am in the city, not at the church.', ["Je suis dans la ville, pas à l'église.", "Je suis en ville, pas à l'église."], [Kit::word('la ville', 'ville'), Kit::word("l'église")], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luc-bibliotheque', 'Luc est à la ___.', 'Luc is at the library.', 'bibliothèque', Kit::word('la bibliothèque', 'bibliothèque'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marie-parc', 'Marie va ___ parc.', 'Marie is going to the park.', 'au', Kit::form('au', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.magasin-village', 'Je suis au magasin du village.', 'I am at the shop of the village.', [Kit::word('le magasin', 'magasin'), Kit::word('le village', 'village'), Kit::form('du')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.parc-supermarche', 'Le parc est en ville et le supermarché au village.', 'The park is in town and the supermarket is in the village.', [Kit::word('le parc', 'parc'), Kit::word('le supermarché', 'supermarché'), Kit::word('la ville', 'ville'), Kit::word('le village', 'village')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.bibliotheque-musee', 'Luc va à la bibliothèque et au musée.', 'Luc is going to the library and to the museum.', [Kit::word('la bibliothèque', 'bibliothèque'), Kit::word('le musée', 'musée'), Kit::form('à la')], 'dictation', $set, homophoneNote: self::ET_A_NOTE),
        ];
    }
}
