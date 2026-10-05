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

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la rue', cue: 'street'),
            new WordData('le coin', cue: 'corner (of a street)'),
            new WordData('à droite', cue: 'to the right'),
            new WordData('à gauche', cue: 'to the left'),
            new WordData('tout droit', cue: 'straight ahead'),
            new WordData('près', cue: 'near, close', accepted: ['près de']),
            new WordData('loin', cue: 'far', accepted: ['loin de']),
            new WordData('le plan', cue: 'map of a town (le plan also means plan, a false friend of English plan)'),
            new WordData('où est… ?', cue: 'where is…? (asking for a place)', accepted: ['où se trouve… ?']),
            new WordData('la place', cue: 'square, plaza (in a town)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je vais au restaurant.', 'english' => 'I go to the restaurant.'],
            ['text' => 'Nous allons à la gare.', 'english' => 'We go to the station.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;
        $est = 'Où (where) has an accent and ou (or) does not. Est (is) and et (and) sound very close, and context decides.';
        $est2 = 'Est (is) and et (and) sound very close, and context decides: here est is the verb.';
        $a = 'À (to, at) has an accent; a (has) does not.';

        return [
            Kit::gap($stage, 'sentences.choose_gap.restaurant', 'Je vais ___ restaurant.', ['au', 'à le', 'à la'], 'au', Kit::form('au', true), 'À and le merge into au: à le is never said.', 'choose', 'I go to the restaurant.', ['restaurant' => 'restaurant']),
            Kit::gap($stage, 'sentences.choose_gap.gare', 'Nous allons ___ gare.', ['à la', 'au', 'aux'], 'à la', Kit::form('à la', true), 'La gare is feminine, so à and la stay apart. Au is only for le.', 'choose', 'We go to the station.', ['gare' => 'station']),
            Kit::gap($stage, 'sentences.choose_gap.vous-allez', 'Vous ___ au coin ?', ['allez', 'avez', 'sommes'], 'allez', Kit::form('allez'), 'Vous goes with allez. Avez belongs to avoir, to have.', 'choose', 'Do you go to the corner?'),
            Kit::gap($stage, 'sentences.choose_gap.ils-musees', 'Ils ___ aux musées.', ['vont', 'va', 'allez'], 'vont', Kit::form('vont'), 'Ils (they) takes vont.', 'choose', 'They go to the museums.', ['musées' => 'museums']),
            Kit::gap($stage, 'sentences.choose_gap.tu-vas', 'Anne, tu ___ à la place ?', ['vas', 'va', 'vais'], 'vas', Kit::form('vas', true), 'Tu takes vas. Va is for il and elle, and vais is for je.', 'choose', 'Anne, do you go to the square?'),
            Kit::gap($stage, 'sentences.choose_gap.droite', 'La place est à ___.', ['droite', 'près', 'loin'], 'droite', Kit::word('à droite', 'droite'), 'To the right is the fixed phrase à droite.', 'choose', 'The square is on the right.'),

            Kit::typeGap($stage, 'sentences.type_gap.allons', 'Nous ___ au coin.', 'We go to the corner.', 'allons', Kit::form('allons'), 'Nous (we) takes allons.'),
            Kit::typeGap($stage, 'sentences.type_gap.au', 'Il va ___ coin.', 'He goes to the corner.', 'au', Kit::form('au', true), 'À and le merge into au: à le coin is never said.'),
            Kit::typeGap($stage, 'sentences.type_gap.vont', 'Ils ___ à gauche.', 'They go to the left.', 'vont', Kit::form('vont'), 'Ils (they) takes vont.'),
            Kit::typeGap($stage, 'sentences.type_gap.a-l', 'Nous allons ___ hôtel.', 'We go to the hotel.', "à l'", Kit::form("à l'", true), "Before a vowel or a silent h, à la and à le both become à l': à l'hôtel.", glosses: ['hôtel' => 'hotel']),
            Kit::typeGap($stage, 'sentences.type_gap.loin', 'La rue est ___ de la place.', 'The street is far from the square.', 'loin', Kit::word('loin')),
            Kit::translate($stage, 'sentences.translate.tout-droit', 'We go straight ahead to the square.', ['Nous allons tout droit à la place.', 'Nous allons à la place, tout droit.'], [Kit::word('tout droit'), Kit::word('la place'), Kit::form('allons')]),
            Kit::translate($stage, 'sentences.translate.ou-rue', 'Where is the street? It is far.', ['Où est la rue ? Elle est loin.', "Où est la rue ? C'est loin."], [Kit::word('où est… ?', 'où est'), Kit::word('la rue'), Kit::word('loin')]),
            Kit::translate($stage, 'sentences.translate.musee', 'I go to the museum, near the corner.', ['Je vais au musée, près du coin.'], [Kit::word('près'), Kit::word('le coin', 'coin'), Kit::form('au')], glosses: ['musée' => 'museum']),
            Kit::build($stage, 'sentences.build.ou-plan', 'Where is the map?', 'Où est le plan ?', ['sont'], [Kit::word('où est… ?', 'où est'), Kit::word('le plan')]),
            Kit::build($stage, 'sentences.build.rue-droite', 'The street is on the right.', 'La rue est à droite.', ['gauche'], [Kit::word('la rue'), Kit::word('à droite')]),
            Kit::build($stage, 'sentences.build.coin', 'We go to the corner.', 'Nous allons au coin.', ['aux'], [Kit::word('le coin', 'coin'), Kit::form('au', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.rue-loin', 'La rue est loin du coin.', ['The street is far from the corner.', 'The street is near the corner.', 'The square is far from the corner.', 'The street is to the left of the corner.'], 'The street is far from the corner.', [Kit::word('la rue'), Kit::word('loin'), Kit::word('le coin', 'coin')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vous-place', 'Vous allez à la place, à droite.', ['You go to the square, to the right.', 'You go to the square, to the left.', 'You go to the street, to the right.', 'You are in the square, on the right.'], 'You go to the square, to the right.', [Kit::word('la place'), Kit::form('allez')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.plan-gauche', 'Le plan est à gauche.', ['The map is on the left.', 'The map is on the right.', 'The street is on the left.', 'The map is near.'], 'The map is on the left.', [Kit::word('le plan'), Kit::word('à gauche')]),
            Kit::listenType($stage, 'sentences.listen_type.ou-place', 'Où est la place ?', 'Where is the square?', [Kit::word('où est… ?', 'où est'), Kit::word('la place')], homophoneNote: $est),
            Kit::listenType($stage, 'sentences.listen_type.place-droit', 'La place, c\'est tout droit.', 'The square is straight ahead.', [Kit::word('la place'), Kit::word('tout droit')], homophoneNote: $est2),
            Kit::listenType($stage, 'sentences.listen_type.vous-droite', 'Vous allez à droite.', 'You go to the right.', [Kit::word('à droite'), Kit::form('allez')], homophoneNote: $a),
            Kit::listenType($stage, 'sentences.listen_type.coin-pres', 'Le coin est près de la rue.', 'The corner is near the street.', [Kit::word('le coin', 'coin'), Kit::word('près'), Kit::word('la rue')], homophoneNote: $est2),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ou-rue', 'Où est la rue ? Elle est loin.', 'Where is the street? It is far.', [Kit::word('où est… ?', 'où est'), Kit::word('la rue'), Kit::word('loin')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.coin-gauche', 'Je vais au coin, à gauche.', 'I go to the corner, to the left.', [Kit::word('le coin', 'coin'), Kit::word('à gauche'), Kit::form('au')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.place-pres', 'Nous allons à la place. Elle est près du coin.', 'We go to the square. It is near the corner.', [Kit::word('la place'), Kit::word('près'), Kit::form('allons')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.plan-droite', 'Vous allez tout droit. La rue est à droite.', 'You go straight ahead. The street is on the right.', [Kit::word('tout droit'), Kit::word('la rue'), Kit::word('à droite'), Kit::form('allez')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ou-place', 'Où est la place ?', 'Where is the square?', [['place', 'est', "c'est", 'elle', 'tout', 'à', 'droite', 'gauche', 'près', 'loin', 'au', 'ici', 'là'], ['droite', 'gauche', 'droit', 'loin', 'près', 'coin', 'rue', 'ici', 'là']], 'La place est à droite.', [Kit::word('où est… ?', 'où est'), Kit::word('la place')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pres-loin', "C'est près ou loin ?", 'Is it near or far?', [['près', 'loin']], "C'est près.", [Kit::word('près'), Kit::word('loin')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vous-ou', 'Vous allez où ?', 'Where are you going?', [['vais', 'allons', 'va', 'au', 'à', 'je', 'tout', 'droit', 'coin', 'place', 'rue', 'droite', 'gauche', 'plan']], 'Je vais au coin.', [Kit::word('le coin', 'coin'), Kit::form('vais')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $est = 'Où (where) has an accent and ou (or) does not. Est (is) and et (and) sound very close, and context decides.';
        $est2 = 'Est (is) and et (and) sound very close, and context decides: here est is the verb.';
        $a = 'À (to, at) has an accent; a (has) does not.';

        return [
            Kit::readPassage($stage, 'task.read_passage.place', 'Read the conversation in the street.', [
                Kit::line('Anne', 'Bonjour. Voici le plan. Où est la place ?'),
                Kit::line('Paul', 'Bonjour. Vous allez tout droit, dans la rue.'),
                Kit::line('Anne', "C'est loin ?"),
                Kit::line('Paul', 'Non, c\'est près. Au coin, la place est à droite.'),
                Kit::line('Anne', 'Merci beaucoup. Au revoir.'),
            ], [
                Kit::question('What does Anne show Paul?', ['The map', 'The square', 'The street'], 'The map'),
                Kit::question('Is the square far?', ['Yes, it is far.', 'No, it is near.', 'The text does not say.'], 'No, it is near.'),
                Kit::question('Which way is the square at the corner?', ['To the left', 'To the right', 'The text does not say.'], 'To the right'),
            ], [Kit::word('le plan'), Kit::word('où est… ?', 'où est'), Kit::word('la place'), Kit::word('tout droit'), Kit::word('la rue'), Kit::word('loin'), Kit::word('près'), Kit::word('le coin', 'coin'), Kit::word('à droite')], 'read'),
            Kit::gap($stage, 'task.choose_gap.restaurants', 'Elles vont ___ restaurants.', ['aux', 'au', 'à les'], 'aux', Kit::form('aux', true), 'À and les merge into aux: à les is never said.', 'read', 'They go to the restaurants.', ['restaurants' => 'restaurants']),
            Kit::gap($stage, 'task.choose_gap.droit', 'La rue va tout ___.', ['droit', 'droite'], 'droit', Kit::word('tout droit', 'droit'), 'Tout droit is a fixed phrase, so droit never changes.', 'read', 'The street goes straight ahead.'),

            Kit::transform($stage, 'task.transform.nous', 'Change the subject to nous.', 'Je vais au coin.', ['Nous allons au coin.'], [Kit::word('le coin', 'coin'), Kit::form('allons')]),
            Kit::transform($stage, 'task.transform.aux', 'Make it plural: they.', 'Il va au musée.', ['Ils vont aux musées.'], [Kit::form('aux', true)], ['musée' => 'museum', 'musées' => 'museums']),
            Kit::transform($stage, 'task.transform.vous', 'Change the subject to vous.', 'Tu vas à gauche.', ['Vous allez à gauche.'], [Kit::word('à gauche'), Kit::form('allez')]),
            Kit::writeGuided($stage, 'task.write_guided.ou', 'Ask where the square is and say it is on the left.', ['où', 'place', 'gauche'], 'Où est la place ? Elle est à gauche.', [
                ['forms' => ['où'], 'term' => 'où est… ?'],
                ['forms' => ['place'], 'term' => 'la place'],
                ['forms' => ['gauche'], 'term' => 'à gauche'],
            ], [Kit::word('où est… ?', 'où'), Kit::word('la place'), Kit::word('à gauche')]),
            Kit::writeGuided($stage, 'task.write_guided.coin', 'Say that you go straight ahead to the corner and that it is far.', ['droit', 'coin', 'loin'], "Je vais tout droit au coin. C'est loin.", [
                ['forms' => ['droit'], 'term' => 'tout droit'],
                ['forms' => ['coin'], 'term' => 'le coin'],
                ['forms' => ['loin'], 'term' => 'loin'],
            ], [Kit::word('tout droit'), Kit::word('le coin'), Kit::word('loin')]),
            Kit::build($stage, 'task.build.place-droite', 'We go to the square, to the right.', 'Nous allons à la place, à droite.', ['au', 'vont'], [Kit::word('la place'), Kit::word('à droite'), Kit::form('allons')], 'write'),
            Kit::build($stage, 'task.build.plan-pres', 'Where is the map? It is near.', 'Où est le plan ? Il est près.', ['loin', 'elle'], [Kit::word('où est… ?', 'où est'), Kit::word('le plan'), Kit::word('près')], 'write'),
            Kit::build($stage, 'task.build.tout-droit', 'They go straight ahead to the corner.', 'Ils vont tout droit au coin.', ['aux', 'allez'], [Kit::word('tout droit'), Kit::word('le coin', 'coin'), Kit::form('au', true)], 'write'),
            Kit::translate($stage, 'task.translate.gare', 'Do you (formal) go to the station? It is far.', ["Vous allez à la gare ? C'est loin.", 'Vous allez à la gare ? Elle est loin.'], [Kit::word('loin'), Kit::form('allez')], 'write', glosses: ['gare' => 'station']),
            Kit::translate($stage, 'task.translate.rue', 'The street is straight ahead, near the square.', ["La rue, c'est tout droit, près de la place.", 'La rue est tout droit, près de la place.'], [Kit::word('la rue'), Kit::word('tout droit'), Kit::word('près'), Kit::word('la place')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.place', [
                Kit::line('Luc', 'Bonjour. Où est la place ?'),
                Kit::line('Marie', 'Elle est près. Vous allez tout droit, au coin, à gauche.'),
                Kit::line('Luc', "C'est loin ?"),
                Kit::line('Marie', 'Non. Voici le plan.'),
                Kit::line('Luc', 'Merci beaucoup. Au revoir.'),
            ], [
                Kit::question('What does Luc ask about?', ['The square', 'The map', 'The street'], 'The square'),
                Kit::question('Which way does Marie say at the corner?', ['To the left', 'To the right', 'Marie does not say.'], 'To the left'),
                Kit::question('Is it far?', ['Yes', 'No', 'Marie does not say.'], 'No'),
            ], [
                Kit::question('Who asks the first question?', ['Luc', 'Marie', 'Anne'], 'Luc'),
                Kit::question('What does Marie give Luc?', ['The map', 'The street', 'The square'], 'The map'),
                Kit::question('How does the conversation end?', ['Luc says thank you.', 'Marie says thank you.', 'Nobody says goodbye.'], 'Luc says thank you.'),
            ], [Kit::word('où est… ?', 'où est'), Kit::word('la place'), Kit::word('près'), Kit::word('tout droit'), Kit::word('le coin', 'coin'), Kit::word('à gauche'), Kit::word('loin'), Kit::word('le plan')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.rue-gauche', 'La rue est à gauche, près du coin.', 'The street is on the left, near the corner.', [Kit::word('la rue'), Kit::word('à gauche'), Kit::word('près'), Kit::word('le coin', 'coin')], 'listen', null, [], $a.' '.$est2),
            Kit::listenType($stage, 'task.listen_type.plan-ici', 'Où est le plan ? Il est ici.', 'Where is the map? It is here.', [Kit::word('où est… ?', 'où est'), Kit::word('le plan')], 'listen', null, [], $est),
            Kit::listenType($stage, 'task.listen_type.coin-place', 'Nous allons au coin de la place.', 'We go to the corner of the square.', [Kit::word('le coin', 'coin'), Kit::word('la place'), Kit::form('au')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.coin', 'Où est le coin ?', 'Where is the corner?', [['coin', 'est', "c'est", 'elle', 'il', 'tout', 'à', 'droite', 'gauche', 'près', 'loin', 'au', 'ici', 'là'], ['droite', 'gauche', 'droit', 'loin', 'près', 'rue', 'ici', 'là']], 'Le coin est près.', [Kit::word('où est… ?', 'où est'), Kit::word('le coin', 'coin')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.droite-gauche', 'Vous allez à droite ou à gauche ?', 'Do you go right or left?', [['droite', 'gauche']], 'Je vais à droite.', [Kit::word('à droite'), Kit::word('à gauche'), Kit::form('vais')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.place-loin', 'La place est loin ?', 'Is the square far?', [['loin', 'près']], 'Non, elle est près.', [Kit::word('la place'), Kit::word('loin'), Kit::word('près')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vous-coin', 'Vous allez au coin ?', 'Do you go to the corner?', [['oui', 'non'], ['vais', 'allons', 'coin']], 'Oui, je vais au coin.', [Kit::word('le coin', 'coin'), Kit::form('vais')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tout-droit', 'Vous allez tout droit. La place est à droite.', 'You go straight ahead. The square is on the right.', [Kit::word('tout droit'), Kit::word('la place'), Kit::word('à droite'), Kit::form('allez')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.coin-pres', 'Le coin est près de la rue, et la place est loin.', 'The corner is near the street, and the square is far.', [Kit::word('le coin', 'coin'), Kit::word('près'), Kit::word('la rue'), Kit::word('la place'), Kit::word('loin')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $est2 = 'Est (is) and et (and) sound very close, and context decides: here est is the verb.';
        $a = 'À (to, at) has an accent; a (has) does not.';

        return [
            Kit::translate($stage, 'check.a.translate.rue-loin', 'The corner is far from the street.', ['Le coin est loin de la rue.'], [Kit::word('la rue'), Kit::word('loin'), Kit::word('le coin', 'coin')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.place-pres', 'We go to the square, near the street.', ['Nous allons à la place, près de la rue.'], [Kit::word('la place'), Kit::word('près'), Kit::word('la rue'), Kit::form('allons')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.plan-gauche', 'Where is the map? It is to the left.', ['Où est le plan ? Il est à gauche.'], [Kit::word('où est… ?', 'où est'), Kit::word('le plan'), Kit::word('à gauche')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.coin-droit', 'I go to the corner, straight ahead.', ['Je vais au coin, tout droit.', 'Je vais tout droit au coin.'], [Kit::word('le coin', 'coin'), Kit::word('tout droit'), Kit::form('au', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.va', 'Elle ___ à la place.', 'She goes to the square.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vont', 'Ils ___ au coin.', 'They go to the corner.', 'vont', Kit::form('vont'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.droite-coin', 'Vous allez à droite, au coin.', 'You go to the right, at the corner.', [Kit::word('à droite'), Kit::word('le coin', 'coin'), Kit::form('allez')], 'dictation', $set, [], $a),
            Kit::listenType($stage, 'check.a.listen_type.place-pres', 'La place est près du coin.', 'The square is near the corner.', [Kit::word('la place'), Kit::word('près'), Kit::word('le coin', 'coin')], 'dictation', $set, [], $est2),
            Kit::listenType($stage, 'check.a.listen_type.tu-vas', 'Tu vas au coin, à gauche.', 'You go to the corner, to the left.', [Kit::word('le coin', 'coin'), Kit::word('à gauche'), Kit::form('vas', true)], 'dictation', $set, [], $a),
            Kit::listenPassage($stage, 'check.a.listen_passage.rue', [
                Kit::line('Anne', 'Bonjour. La rue est loin ?'),
                Kit::line('Paul', 'Non, elle est près. Vous allez tout droit.'),
                Kit::line('Anne', 'Et la place ? Elle est à droite ?'),
                Kit::line('Paul', 'Non, elle est à gauche, au coin.'),
                Kit::line('Anne', 'Merci beaucoup. Au revoir.'),
            ], [
                Kit::question('Is the street far?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Where is the square?', ['To the right', 'To the left', 'Straight ahead'], 'To the left'),
                Kit::question('Who says thank you?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
            ], [
                Kit::question('Who asks the first question?', ['Anne', 'Paul', 'Marie'], 'Anne'),
                Kit::question('What does Paul say Anne should do?', ['Go straight ahead', 'Go to the corner on the right', 'Go far'], 'Go straight ahead'),
                Kit::question('How does the conversation end?', ['Anne says thank you and goodbye.', 'Paul says goodbye first.', 'Anne asks a new question.'], 'Anne says thank you and goodbye.'),
            ], [Kit::word('la rue'), Kit::word('loin'), Kit::word('près'), Kit::word('tout droit'), Kit::word('la place'), Kit::word('à droite'), Kit::word('à gauche'), Kit::word('le coin', 'coin')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.coin', 'Read the conversation.', [
                Kit::line('Marie', 'Bonjour. Vous allez au coin de la place ?'),
                Kit::line('Luc', 'Non, je vais au coin de la rue.'),
                Kit::line('Marie', "C'est loin ?"),
                Kit::line('Luc', 'Non, le coin est près, là.'),
            ], [
                Kit::question('Where does Luc go?', ['To the corner of the street', 'To the corner of the square', 'To the map'], 'To the corner of the street'),
                Kit::question('Is it far?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('la place'), Kit::word('le coin', 'coin'), Kit::word('la rue'), Kit::word('loin'), Kit::word('près')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.rue', 'Où est la rue ?', 'Where is the street?', [['rue', 'est', "c'est", 'elle', 'tout', 'à', 'droite', 'gauche', 'près', 'loin', 'au', 'ici', 'là'], ['droite', 'gauche', 'droit', 'loin', 'près', 'coin', 'ici', 'là']], 'La rue est à droite.', [Kit::word('où est… ?', 'où est'), Kit::word('la rue')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.place', "La place, c'est près ou loin ?", 'Is the square near or far?', [['près', 'loin']], "C'est près.", [Kit::word('la place'), Kit::word('près'), Kit::word('loin')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.plan', 'Le plan est à droite ou à gauche ?', 'Is the map on the right or on the left?', [['droite', 'gauche']], 'Le plan est à gauche.', [Kit::word('le plan'), Kit::word('à droite'), Kit::word('à gauche')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';
        $est = 'Où (where) has an accent and ou (or) does not. Est (is) and et (and) sound very close, and context decides.';
        $a = 'À (to, at) has an accent; a (has) does not.';

        return [
            Kit::translate($stage, 'check.b.translate.tout-droit', 'We go straight ahead to the corner.', ['Nous allons tout droit au coin.'], [Kit::word('tout droit'), Kit::word('le coin', 'coin'), Kit::form('allons')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.rue-gauche', 'Where is the street? It is on the left.', ['Où est la rue ? Elle est à gauche.'], [Kit::word('où est… ?', 'où est'), Kit::word('la rue'), Kit::word('à gauche')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.place-droite', 'She goes to the square, to the right.', ['Elle va à la place, à droite.'], [Kit::word('la place'), Kit::word('à droite'), Kit::form('à la', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.plan-coin', 'The map is near the corner, on the left.', ['Le plan est près du coin, à gauche.'], [Kit::word('le plan'), Kit::word('près'), Kit::word('le coin', 'coin'), Kit::word('à gauche')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vais', 'Je ___ à gauche.', 'I go to the left.', 'vais', Kit::form('vais'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.au', 'Marie va ___ coin.', 'Marie goes to the corner.', 'au', Kit::form('au', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.place-loin', 'Où est la place ? Elle est loin.', 'Where is the square? It is far.', [Kit::word('où est… ?', 'où est'), Kit::word('la place'), Kit::word('loin')], 'dictation', $set, [], $est),
            Kit::listenType($stage, 'check.b.listen_type.tout-droit', 'Vous allez tout droit, loin de la rue.', 'You go straight ahead, far from the street.', [Kit::word('tout droit'), Kit::word('loin'), Kit::word('la rue'), Kit::form('allez')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.plan-droite', 'Voici le plan. Ils vont à droite, près du coin.', 'Here is the map. They go to the right, near the corner.', [Kit::word('le plan'), Kit::word('près'), Kit::word('le coin', 'coin'), Kit::word('à droite'), Kit::form('vont')], 'dictation', $set, [], $a),
        ];
    }
}
