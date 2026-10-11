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

final class TakingTheTrainOrBus implements UnitContent
{
    private const TIME = 'à quelle heure part-il ?';

    private const TIME_FORM = 'à quelle heure part';

    private const NUMBERS = ['un', 'une', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze'];

    private const A_NOTE = 'À (at, to) has a grave accent, unlike a (has). Here it means to or at.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and context decides: here est is the verb.';

    private const ET_NOTE = 'Et (and) sounds very close to est (is), and context decides: here et joins two things.';

    private const VEUT_NOTE = 'Veux and veut sound exactly the same, so je and tu veux and il and elle veut are heard alike. Veulent is different: you hear the l (veul). The subject decides: je and tu veux, il and elle veut, ils and elles veulent.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'taking-the-train-or-bus';
    }

    public function words(): array
    {
        return [
            new WordData('le train', cue: 'train', forms: ['trains']),
            new WordData('le bus', cue: 'bus'),
            new WordData('la gare', cue: 'station (train station)', forms: ['gares']),
            new WordData('l\'arrêt', cue: 'stop (where a bus or tram stops)', forms: ['arrêt', 'arrêts']),
            new WordData('le quai', cue: 'platform (at a train station)', forms: ['quais']),
            new WordData('l\'horaire', cue: 'timetable (masculine: un horaire)', forms: ['horaire', 'horaires']),
            new WordData('la place', cue: 'seat, also square or room', forms: ['places']),
            new WordData('un aller-retour', cue: 'return (as in a return ticket)', forms: ['aller-retour', 'billet', 'billets'], note: 'You say un aller-retour or un billet aller-retour for a return ticket. A single ticket is un aller simple.'),
            new WordData(self::TIME, cue: 'what time does it leave?', accepted: ['à quelle heure part il'], forms: ['part', 'heure', 'heures']),
            new WordData('la ligne', cue: 'line (of a bus or metro)', forms: ['lignes']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je veux un billet.', 'english' => 'I want a ticket.'],
            ['text' => 'Je veux aller à la gare.', 'english' => 'I want to go to the station.'],
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
        $time = Kit::word(self::TIME, self::TIME_FORM);

        return [
            Kit::gap($stage, 'sentences.choose_gap.veux-billet', 'Je ___ un billet.', ['veux', 'veut', 'voulons'], 'veux', Kit::form('veux'), 'Je goes with veux. Veut is for il or elle and voulons is for nous.', 'choose', 'I want a ticket.', ['veux' => 'I want', 'veut' => 'he or she wants', 'voulons' => 'we want']),
            Kit::gap($stage, 'sentences.choose_gap.marie-veut', 'Marie ___ aller à la gare.', ['veut', 'veux', 'veulent'], 'veut', Kit::form('veut'), 'Marie is one person, so the verb is veut. Veux is for je and tu, and veulent is for ils and elles.', 'choose', 'Marie wants to go to the station.', ['veut' => 'he or she wants', 'veux' => 'I want', 'veulent' => 'they want']),
            Kit::gap($stage, 'sentences.choose_gap.quai', 'Je veux aller au ___ trois.', ['quai', 'gare', 'ligne'], 'quai', Kit::word('le quai', 'quai'), 'Au is à + le, so it needs a masculine word. Quai is masculine, while gare and ligne are feminine.', 'choose', 'I want to go to platform three.', ['gare' => 'station', 'ligne' => 'line']),
            Kit::gap($stage, 'sentences.choose_gap.ligne', 'Le bus de la ___ cinq.', ['ligne', 'quai', 'horaire'], 'ligne', Kit::word('la ligne', 'ligne'), 'La goes with a feminine word, and ligne is feminine. Quai and horaire are masculine.', 'choose', 'The line five bus.', ['quai' => 'platform', 'horaire' => 'timetable']),
            Kit::gap($stage, 'sentences.choose_gap.aller-retour', 'Je veux ___ aller-retour.', ['un', 'une', 'le'], 'un', Kit::word('un aller-retour', 'aller-retour'), 'Aller-retour is masculine, so it takes un: un aller-retour. Une is for feminine words and le is the, not a.', 'choose', 'I want a return ticket.', ['une' => 'a (feminine)']),
            Kit::gap($stage, 'sentences.choose_gap.aller-arret', 'Je ___ aller à l\'arrêt.', ['veux', 'ai', 'suis'], 'veux', Kit::form('veux', true), 'Je veux + aller means I want to go. Ai and suis do not work straight before aller.', 'choose', 'I want to go to the stop.', ['ai' => 'I have', 'suis' => 'I am']),

            Kit::typeGap($stage, 'sentences.type_gap.voulons', 'Nous ___ aller à la gare.', 'We want to go to the station.', 'voulons', Kit::form('voulons'), 'Nous goes with voulons. The stem changes here: veux, veut, but voulons.'),
            Kit::typeGap($stage, 'sentences.type_gap.veulent', 'Ils ___ une place.', 'They want a seat.', 'veulent', Kit::form('veulent'), 'Ils means more than one person, so the verb ends in -ent: veulent. You hear the l, unlike veut.', glosses: ['veulent' => 'they want']),
            Kit::typeGap($stage, 'sentences.type_gap.veux', 'Tu ___ l\'horaire.', 'You want the timetable (informal you).', 'veux', Kit::form('veux', true), 'Tu goes with veux, the same form as for je. Veut is for il or elle.', glosses: ['veux' => 'you want']),
            Kit::typeGap($stage, 'sentences.type_gap.arret', 'Voici ___ de bus.', 'Here is the bus stop.', 'l\'arrêt', Kit::word('l\'arrêt'), 'A stop where buses stop is l\'arrêt. It is masculine, so un arrêt.'),
            Kit::typeGap($stage, 'sentences.type_gap.horaire', 'Je veux ___ du train.', 'I want the timetable of the train.', 'l\'horaire', Kit::word('l\'horaire'), 'The timetable is l\'horaire. Le or la shrinks to l\' before a vowel sound (horaire starts with a silent h).'),

            Kit::translate($stage, 'sentences.translate.aller-retour', 'I want a return ticket.', ['Je veux un aller-retour.', 'Je veux un billet aller-retour.'], [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veux')]),
            Kit::translate($stage, 'sentences.translate.place', 'We want a seat.', ['Nous voulons une place.'], [Kit::word('la place', 'place'), Kit::form('voulons')]),
            Kit::translate($stage, 'sentences.translate.gare', 'I want to go to the station.', ['Je veux aller à la gare.'], [Kit::word('la gare', 'gare'), Kit::form('veux')]),

            Kit::build($stage, 'sentences.build.horaire', 'Anne wants the timetable.', 'Anne veut l\'horaire.', ['veulent'], [Kit::word('l\'horaire'), Kit::form('veut')]),
            Kit::build($stage, 'sentences.build.ligne', 'Here is the line five bus.', 'Voici le bus de la ligne cinq.', ['une'], [Kit::word('le bus', 'bus'), Kit::word('la ligne', 'ligne')]),
            Kit::build($stage, 'sentences.build.train-quai', 'Marie is on the platform.', 'Marie est sur le quai.', ['la'], [Kit::word('le quai', 'quai')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.aller-retour', 'Je veux un aller-retour.', ['I want a return ticket.', 'I want a train ticket.', 'I want a seat.', 'I want the timetable.'], 'I want a return ticket.', [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veux')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.heure', 'À quelle heure part le train ?', ['What time does the train leave?', 'Where does the train leave from?', 'Which train is it?', 'Is the train here?'], 'What time does the train leave?', [$time, Kit::word('le train', 'train')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.arret', 'Je veux aller à l\'arrêt.', ['I want to go to the stop.', 'I want to go to the station.', 'I want to go to the platform.', 'I want to go home.'], 'I want to go to the stop.', [Kit::word('l\'arrêt'), Kit::form('veux')]),
            Kit::listenType($stage, 'sentences.listen_type.place', 'Nous voulons une place.', 'We want a seat.', [Kit::word('la place', 'place'), Kit::form('voulons')]),
            Kit::listenType($stage, 'sentences.listen_type.bus', 'Le bus part de l\'arrêt.', 'The bus leaves from the stop.', [Kit::word('le bus', 'bus'), Kit::word('l\'arrêt')]),
            Kit::listenType($stage, 'sentences.listen_type.horaire', 'L\'horaire est ici.', 'The timetable is here.', [Kit::word('l\'horaire')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.veulent', 'Ils veulent aller à la gare.', 'They want to go to the station.', [Kit::word('la gare', 'gare'), Kit::form('veulent')], homophoneNote: self::A_NOTE.' '.self::VEUT_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.aller-retour', 'Un aller-retour, s\'il vous plaît.', 'A return ticket, please.', [Kit::word('un aller-retour')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.heure', 'À quelle heure part le bus ?', 'What time does the bus leave?', [$time, Kit::word('le bus', 'bus')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.quai', 'Nous voulons aller au quai.', 'We want to go to the platform.', [Kit::word('le quai', 'quai'), Kit::form('voulons')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.horaire', 'L\'horaire est à la gare.', 'The timetable is at the station.', [Kit::word('l\'horaire'), Kit::word('la gare', 'gare')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.place', 'Tu veux une place ?', 'Do you want a seat? (informal you)', [['oui', 'non'], ['veux', 'place']], 'Oui, je veux une place.', [Kit::word('la place', 'place'), Kit::form('veux')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.arret', 'Où est l\'arrêt ?', 'Where is the stop?', [['arrêt', 'est'], ['ici', 'là']], 'L\'arrêt est ici.', [Kit::word('l\'arrêt')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quai', 'De quel quai part le train ?', 'Which platform does the train leave from?', [['part', 'quai', 'train'], self::NUMBERS], 'Le train part du quai trois.', [Kit::word('le quai', 'quai'), Kit::word('le train', 'train')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $time = Kit::word(self::TIME, self::TIME_FORM);

        return [
            Kit::readPassage($stage, 'task.read_passage.billet', 'Read the conversation at the ticket desk.', [
                Kit::line('Paul', 'Bonjour. Je veux un aller-retour.'),
                Kit::line('Employé', 'Très bien.'),
                Kit::line('Paul', 'À quelle heure part le train ?'),
                Kit::line('Employé', 'Il part à cinq heures, du quai trois.'),
                Kit::line('Paul', 'Je veux une place, s\'il vous plaît.'),
                Kit::line('Employé', 'Voici. Au revoir.'),
            ], [
                Kit::question('What does Paul ask for first?', ['A return ticket', 'A bus', 'The timetable'], 'A return ticket'),
                Kit::question('What time does the train leave?', ['At five', 'At three', 'At two'], 'At five'),
                Kit::question('Which platform does it leave from?', ['Platform three', 'Platform five', 'Platform two'], 'Platform three'),
            ], [Kit::word('un aller-retour', 'aller-retour'), Kit::word('le train', 'train'), $time, Kit::word('le quai', 'quai'), Kit::word('la place', 'place'), Kit::form('veux')], 'read', glosses: ['employé' => 'clerk']),
            Kit::gap($stage, 'task.choose_gap.veulent', 'Anne et Luc ___ aller en bus.', ['veulent', 'veut', 'veux'], 'veulent', Kit::form('veulent', true), 'Anne et Luc means two people, so the verb is veulent.', 'read', 'Anne and Luc want to go by bus.', ['veulent' => 'they want', 'veut' => 'he or she wants', 'veux' => 'I want']),
            Kit::gap($stage, 'task.choose_gap.horaire', 'Je veux ___ de la ligne cinq.', ['l\'horaire', 'la gare', 'la place'], 'l\'horaire', Kit::word('l\'horaire'), 'The timetable of a line is l\'horaire. La gare is a station and la place is a seat.', 'read', 'I want the timetable of line five.'),

            Kit::transform($stage, 'task.transform.nous', 'Change it to nous.', 'Je veux une place.', ['Nous voulons une place.'], [Kit::word('la place', 'place'), Kit::form('voulons')]),
            Kit::transform($stage, 'task.transform.ils', 'Change it to ils.', 'Il veut aller à la gare.', ['Ils veulent aller à la gare.'], [Kit::word('la gare', 'gare'), Kit::form('veulent')]),
            Kit::transform($stage, 'task.transform.anne', 'Say it about Anne.', 'Je veux l\'horaire du train.', ['Anne veut l\'horaire du train.'], [Kit::word('l\'horaire'), Kit::word('le train', 'train'), Kit::form('veut')]),
            Kit::writeGuided($stage, 'task.write_guided.billet', 'Say that you want a return ticket and a seat.', ['veux', 'aller-retour', 'place'], 'Je veux un aller-retour et une place.', [
                ['forms' => ['veux'], 'term' => null],
                ['forms' => ['aller-retour'], 'term' => 'un aller-retour'],
                ['forms' => ['place'], 'term' => 'la place'],
            ], [Kit::word('un aller-retour', 'aller-retour'), Kit::word('la place', 'place'), Kit::form('veux')]),
            Kit::writeGuided($stage, 'task.write_guided.horaire', 'Ask what time the bus leaves and say that you want the timetable.', ['veux', 'à quelle heure part', 'bus', 'horaire'], 'À quelle heure part le bus ? Je veux l\'horaire.', [
                ['forms' => ['part'], 'term' => self::TIME],
                ['forms' => ['bus'], 'term' => 'le bus'],
                ['forms' => ['l\'horaire'], 'term' => 'l\'horaire'],
                ['forms' => ['veux'], 'term' => null],
            ], [$time, Kit::word('le bus', 'bus'), Kit::word('l\'horaire'), Kit::form('veux')]),
            Kit::build($stage, 'task.build.arret', 'We want to go to the bus stop.', 'Nous voulons aller à l\'arrêt de bus.', ['veux', 'veulent'], [Kit::word('l\'arrêt'), Kit::word('le bus', 'bus'), Kit::form('voulons')], 'write', ['veux' => 'I want', 'veulent' => 'they want']),
            Kit::build($stage, 'task.build.ligne', 'The line five bus leaves from the stop.', 'Le bus de la ligne cinq part de l\'arrêt.', ['quai', 'au'], [Kit::word('le bus', 'bus'), Kit::word('la ligne', 'ligne'), Kit::word('l\'arrêt')], 'write', ['quai' => 'platform']),
            Kit::build($stage, 'task.build.place', 'I want a seat on the train, please.', 'Je veux une place dans le train, s\'il vous plaît.', ['veut', 'un'], [Kit::word('la place', 'place'), Kit::word('le train', 'train'), Kit::form('veux')], 'write', ['veut' => 'he or she wants']),
            Kit::translate($stage, 'task.translate.veux', 'Do you want a return ticket? (informal you)', ['Tu veux un aller-retour ?', 'Tu veux un billet aller-retour ?', 'Veux-tu un aller-retour ?'], [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veux', true)], 'write'),
            Kit::translate($stage, 'task.translate.heure', 'What time does the bus leave?', ['À quelle heure part le bus ?'], [$time, Kit::word('le bus', 'bus')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.arret', [
                Kit::line('Anne', 'Bonjour. Où est l\'arrêt de la ligne cinq ?'),
                Kit::line('Marie', 'Il est ici.'),
                Kit::line('Anne', 'À quelle heure part le bus ?'),
                Kit::line('Marie', 'Voici l\'horaire. Il part à deux heures.'),
                Kit::line('Anne', 'Merci beaucoup. Je veux aller à la gare.'),
            ], [
                Kit::question('What does Anne ask about first?', ['Where the bus stop is', 'The price of a ticket', 'A seat'], 'Where the bus stop is'),
                Kit::question('What does Marie show Anne?', ['The timetable', 'A ticket', 'A map'], 'The timetable'),
                Kit::question('Where does Anne want to go?', ['To the station', 'To the platform', 'Home'], 'To the station'),
            ], [
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the bus leave?', ['At two', 'At five', 'At three'], 'At two'),
                Kit::question('Does Anne say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('l\'arrêt'), Kit::word('la ligne', 'ligne'), $time, Kit::word('le bus', 'bus'), Kit::word('l\'horaire'), Kit::word('la gare', 'gare'), Kit::form('veux')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.train', 'Mon train part du quai deux.', 'My train leaves from platform two.', [Kit::word('le train', 'train'), Kit::word('le quai', 'quai')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.voulez', 'Vous voulez aller à la gare.', 'You want to go to the station (formal or plural you).', [Kit::word('la gare', 'gare'), Kit::form('voulez')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.veut', 'Anne veut un aller-retour.', 'Anne wants a return ticket.', [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veut')], 'listen', homophoneNote: self::VEUT_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.aller-retour', 'Tu veux un aller-retour ?', 'Do you want a return ticket? (informal you)', [['oui', 'non'], ['veux', 'billet', 'aller-retour']], 'Oui, je veux un aller-retour.', [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veux')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.heure', 'À quelle heure part le train ?', 'What time does the train leave?', [['part', 'train'], self::NUMBERS], 'Le train part à cinq heures.', [$time, Kit::word('le train', 'train')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ligne', 'Tu veux quelle ligne ?', 'Which line do you want? (informal you)', [['veux', 'ligne', 'bus'], self::NUMBERS], 'Je veux la ligne cinq.', [Kit::word('la ligne', 'ligne'), Kit::form('veux')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.gare', 'Où tu veux aller ? À la gare ?', 'Where do you want to go? To the station? (informal you)', [['veux', 'vais', 'oui', 'non'], ['gare', 'arrêt', 'quai']], 'Je veux aller à la gare.', [Kit::word('la gare', 'gare'), Kit::form('veux')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.place', 'Bonjour, je veux une place, s\'il vous plaît.', 'Hello, I want a seat, please.', [Kit::word('la place', 'place'), Kit::form('veux')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.train', 'Le train part de la gare à deux heures.', 'The train leaves from the station at two.', [Kit::word('le train', 'train'), Kit::word('la gare', 'gare')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.heure', 'What time does the train leave from the station?', ['À quelle heure part le train de la gare ?'], [Kit::word(self::TIME, self::TIME_FORM), Kit::word('le train', 'train'), Kit::word('la gare', 'gare')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.veux', 'Do you want a seat on the train? (informal you)', ['Tu veux une place dans le train ?', 'Veux-tu une place dans le train ?'], [Kit::word('la place', 'place'), Kit::word('le train', 'train'), Kit::form('veux', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.voulons', 'We want the timetable of line five.', ['Nous voulons l\'horaire de la ligne cinq.'], [Kit::word('l\'horaire'), Kit::word('la ligne', 'ligne'), Kit::form('voulons')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.veulent', 'They want a return ticket.', ['Ils veulent un aller-retour.', 'Ils veulent un billet aller-retour.', 'Elles veulent un aller-retour.', 'Elles veulent un billet aller-retour.'], [Kit::word('un aller-retour', 'aller-retour'), Kit::form('veulent')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.veut', 'Anne ___ aller au quai.', 'Anne wants to go to the platform.', 'veut', Kit::form('veut'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.voulons', 'Nous ___ l\'horaire.', 'We want the timetable.', 'voulons', Kit::form('voulons', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.horaire', 'Je veux l\'horaire de la gare, s\'il vous plaît.', 'I want the timetable of the station, please.', [Kit::word('l\'horaire'), Kit::word('la gare', 'gare'), Kit::form('veux')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.train', 'Le train part du quai quatre.', 'The train leaves from platform four.', [Kit::word('le train', 'train'), Kit::word('le quai', 'quai')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.bus', 'L\'arrêt de bus est à la gare.', 'The bus stop is at the station.', [Kit::word('l\'arrêt'), Kit::word('le bus', 'bus'), Kit::word('la gare', 'gare')], 'dictation', $set, homophoneNote: self::EST_NOTE.' '.self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.train', [
                Kit::line('Luc', 'Bonjour. À quelle heure part le train, s\'il vous plaît ?'),
                Kit::line('Employé', 'Il part à quatre heures, du quai deux.'),
                Kit::line('Luc', 'Et où est le quai ?'),
                Kit::line('Employé', 'Il est là, dans la gare.'),
            ], [
                Kit::question('What does Luc ask about?', ['The time of the train', 'A return ticket', 'The bus stop'], 'The time of the train'),
                Kit::question('Which platform does the train leave from?', ['Platform two', 'Platform four', 'Platform five'], 'Platform two'),
                Kit::question('Where is the platform?', ['Over there', 'Here', 'The conversation does not say.'], 'Over there'),
            ], [
                Kit::question('Who speaks first?', ['Luc', 'The clerk', 'Nobody'], 'Luc'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('What time does the train leave?', ['At four', 'At two', 'At five'], 'At four'),
            ], [Kit::word('le train', 'train'), Kit::word(self::TIME, self::TIME_FORM), Kit::word('le quai', 'quai'), Kit::word('la gare', 'gare')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.horaire', 'Read the conversation.', [
                Kit::line('Marie', 'Bonjour. Vous avez l\'horaire du bus ?'),
                Kit::line('Employé', 'Oui. Le bus de la ligne cinq part à deux heures.'),
                Kit::line('Marie', 'Je veux un aller-retour, s\'il vous plaît.'),
                Kit::line('Employé', 'Voici.'),
            ], [
                Kit::question('What does Marie ask for first?', ['The timetable', 'A ticket', 'A seat'], 'The timetable'),
                Kit::question('Which line leaves at two?', ['Line five', 'Line two', 'Line ten'], 'Line five'),
            ], [Kit::word('l\'horaire'), Kit::word('le bus', 'bus'), Kit::word('la ligne', 'ligne'), Kit::word('un aller-retour', 'aller-retour')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.place', 'Vous voulez une place ?', 'Do you want a seat? (formal you)', [['oui', 'non'], ['veux', 'place']], 'Oui, je veux une place.', [Kit::word('la place', 'place'), Kit::form('veux')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quai', 'Où est le quai ?', 'Where is the platform?', [['quai', 'est'], ['ici', 'là']], 'Le quai est là.', [Kit::word('le quai', 'quai')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.aller-retour', 'Vous voulez un aller-retour ?', 'Do you want a return ticket? (formal you)', [['oui', 'non'], ['veux', 'billet', 'aller-retour']], 'Oui, je veux un aller-retour.', [Kit::word('un aller-retour', 'aller-retour')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.arret', 'I want to go to the bus stop.', ['Je veux aller à l\'arrêt de bus.'], [Kit::word('l\'arrêt'), Kit::word('le bus', 'bus'), Kit::form('veux')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.place', 'The seat is on the line five bus.', ['La place est dans le bus de la ligne cinq.'], [Kit::word('la place', 'place'), Kit::word('le bus', 'bus'), Kit::word('la ligne', 'ligne')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.veulent', 'Do they want the timetable and a return ticket?', ['Ils veulent l\'horaire et un aller-retour ?', 'Ils veulent l\'horaire et un billet aller-retour ?', 'Elles veulent l\'horaire et un aller-retour ?', 'Elles veulent l\'horaire et un billet aller-retour ?'], [Kit::word('l\'horaire'), Kit::word('un aller-retour', 'aller-retour'), Kit::form('veulent', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.heure', 'What time does the train leave from platform three?', ['À quelle heure part le train du quai trois ?'], [Kit::word(self::TIME, self::TIME_FORM), Kit::word('le train', 'train'), Kit::word('le quai', 'quai')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.veut', 'Luc ___ un aller-retour.', 'Luc wants a return ticket.', 'veut', Kit::form('veut'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.voulez', 'Vous ___ aller à l\'arrêt.', 'You want to go to the stop (formal or plural you).', 'voulez', Kit::form('voulez', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.place', 'Je veux un aller-retour et une place.', 'I want a return ticket and a seat.', [Kit::word('un aller-retour', 'aller-retour'), Kit::word('la place', 'place'), Kit::form('veux')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.bus', 'Le bus de la gare part de l\'arrêt.', 'The station bus leaves from the stop.', [Kit::word('le bus', 'bus'), Kit::word('la gare', 'gare'), Kit::word('l\'arrêt')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.horaire', 'Nous voulons l\'horaire de la ligne deux.', 'We want the timetable of line two.', [Kit::word('l\'horaire'), Kit::word('la ligne', 'ligne'), Kit::form('voulons')], 'dictation', $set),
        ];
    }
}
