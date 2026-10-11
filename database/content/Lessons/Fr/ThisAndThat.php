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

final class ThisAndThat implements UnitContent
{
    private const THINGS = ['verre', 'sac', 'voiture', 'photo', 'cadeau', 'parapluie', 'stylo', 'chapeau'];

    private const NAMES = ['anne', 'paul', 'marie', 'luc'];

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins two things.';

    private const OU_NOTE = 'Ou (or) and où (where) sound the same, and the sentence tells you which is which: here ou means or.';

    private const A_NOTE = 'À (to, at) and a (has, from avoir) sound the same, and the sentence tells you which is which: the à after est or sont shows who owns something.';

    private const CES_NOTE = 'Ces (these) and ses (his, her) sound the same, and the sentence tells you which is which: here ces points at things.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'this-and-that';
    }

    public function words(): array
    {
        return [
            new WordData('le verre', cue: 'glass (for drinking)', forms: ['verres']),
            new WordData('le sac', cue: 'bag, handbag', forms: ['sacs']),
            new WordData('la voiture', cue: 'car', forms: ['voitures']),
            new WordData('le sac à dos', cue: 'backpack', forms: ['sacs à dos']),
            new WordData('la photo', cue: 'photo', accepted: ['la photographie'], forms: ['photos']),
            new WordData('le cadeau', cue: 'gift, present', forms: ['cadeaux']),
            new WordData('le parapluie', cue: 'umbrella', forms: ['parapluies']),
            new WordData('le stylo', cue: 'pen', forms: ['stylos']),
            new WordData('le chapeau', cue: 'hat', forms: ['chapeaux']),
            new WordData('préférer', cue: 'to prefer', forms: ['préfère', 'préfères', 'préférez'], note: 'The accent changes: je préfère, tu préfères, il préfère, but vous préférez.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Cette photo est à Anne.', 'english' => 'This photo is Anne\'s.'],
            ['text' => 'Tu préfères ce sac ou ce chapeau ?', 'english' => 'Do you prefer this bag or this hat?'],
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
            Kit::gap($stage, 'sentences.choose_gap.verre', '___ verre est à Anne.', ['Ce', 'Cette', 'Ces'], 'Ce', Kit::form('ce', true), 'Verre is masculine and singular, so this is ce. Cette is for feminine words and ces is for several things.', 'choose', "This glass is Anne's."),
            Kit::gap($stage, 'sentences.choose_gap.voiture', '___ voiture est à Paul.', ['Cette', 'Ce', 'Ces'], 'Cette', Kit::form('cette', true), 'Voiture is feminine and singular, so this is cette. Ce is for masculine words and ces is for several things.', 'choose', "This car is Paul's."),
            Kit::gap($stage, 'sentences.choose_gap.sacs-a-dos', '___ sacs à dos sont à Luc.', ['Ces', 'Ce', 'Cette'], 'Ces', Kit::form('ces', true), 'Sacs is plural, so these is ces. Ce and cette are for one thing.', 'choose', "These backpacks are Luc's."),
            Kit::gap($stage, 'sentences.choose_gap.photo', 'À qui est ___ photo ?', ['cette', 'ce', 'ces'], 'cette', Kit::form('cette', true), 'Photo is feminine and singular, so this is cette. Ce is for masculine words and ces is for several things.', 'choose', 'Whose photo is this?'),
            Kit::gap($stage, 'sentences.choose_gap.photo-luc', '___ photo est à Paul.', ['Cette', 'Ce', 'Ces'], 'Cette', Kit::form('cette', true), 'Photo is feminine and singular, so this is cette. Ce is for masculine words and ces is for several things.', 'choose', "This photo is Paul's."),
            Kit::gap($stage, 'sentences.choose_gap.prefere', 'Je ___ ce verre.', ['préfère', 'préfères', 'préférez'], 'préfère', Kit::word('préférer', 'préfère'), 'Je goes with préfère. Préfères is for tu and préférez is for vous.', 'choose', 'I prefer this glass.'),

            Kit::typeGap($stage, 'sentences.type_gap.sac', '___ sac est à Marie.', "This bag is Marie's.", 'Ce', Kit::form('ce'), 'Sac is masculine and singular, so this is ce.'),
            Kit::typeGap($stage, 'sentences.type_gap.preferes', 'Tu ___ ce sac ou ce stylo ?', 'Do you prefer this bag or this pen? (informal you)', 'préfères', Kit::word('préférer', 'préfères')),
            Kit::typeGap($stage, 'sentences.type_gap.stylos', 'Ces ___ sont à Anne.', "These pens are Anne's.", 'stylos', Kit::word('le stylo', 'stylos')),
            Kit::typeGap($stage, 'sentences.type_gap.cadeaux', '___ cadeaux sont pour Luc.', 'These gifts are for Luc.', 'Ces', Kit::form('ces'), 'Cadeaux is plural, so these is ces, for masculine and feminine words alike.'),
            Kit::typeGap($stage, 'sentences.type_gap.ca', "C'est quoi, ___ ? C'est un parapluie.", 'What is this? It is an umbrella.', 'ça', Kit::form('ça'), 'The neutral ça has no gender, so it is right when you have not named the thing.'),

            Kit::translate($stage, 'sentences.translate.stylo', "This pen is Luc's.", ['Ce stylo est à Luc.'], [Kit::word('le stylo', 'stylo'), Kit::form('ce')]),
            Kit::translate($stage, 'sentences.translate.chapeau', 'I prefer this hat.', ['Je préfère ce chapeau.'], [Kit::word('le chapeau', 'chapeau'), Kit::word('préférer', 'préfère'), Kit::form('ce')]),
            Kit::translate($stage, 'sentences.translate.ca', 'What is this? It is a gift.', ["C'est quoi, ça ? C'est un cadeau."], [Kit::word('le cadeau', 'cadeau'), Kit::form('ça')]),

            Kit::build($stage, 'sentences.build.photo', "This photo is Marie's.", 'Cette photo est à Marie.', ['ce'], [Kit::word('la photo', 'photo'), Kit::form('cette')]),
            Kit::build($stage, 'sentences.build.sac-a-dos', 'I prefer this backpack.', 'Je préfère ce sac à dos.', ['cette'], [Kit::word('le sac à dos', 'sac à dos'), Kit::word('préférer', 'préfère'), Kit::form('ce')]),
            Kit::build($stage, 'sentences.build.sac', 'Whose bag is this?', 'À qui est ce sac ?', ['cette'], [Kit::word('le sac', 'sac'), Kit::form('ce')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.voiture', 'Cette voiture est à Marie.', ["This car is Marie's.", "These cars are Marie's.", "This car is Anne's.", "This bag is Marie's."], "This car is Marie's.", [Kit::word('la voiture', 'voiture'), Kit::form('cette')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ca', "C'est quoi, ça ?", ['What is this?', 'Who is this?', 'Where is this?', 'Whose is this?'], 'What is this?', [Kit::form('ça')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.verre', 'Je préfère ce verre.', ['I prefer this glass.', 'You prefer this glass.', 'I prefer these glasses.', 'I prefer this bag.'], 'I prefer this glass.', [Kit::word('le verre', 'verre'), Kit::word('préférer', 'préfère'), Kit::form('ce')]),
            Kit::listenType($stage, 'sentences.listen_type.sacs-a-dos', 'Ces sacs à dos sont à Paul.', "These backpacks are Paul's.", [Kit::word('le sac à dos', 'sacs à dos'), Kit::form('ces')], homophoneNote: self::CES_NOTE.' '.self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.sac-chapeau', 'Tu préfères le sac ou le chapeau ?', 'Do you prefer the bag or the hat? (informal you)', [Kit::word('le sac', 'sac'), Kit::word('le chapeau', 'chapeau'), Kit::word('préférer', 'préfères')], homophoneNote: self::OU_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.photo-cadeau', 'Cette photo est un cadeau.', 'This photo is a gift.', [Kit::word('la photo', 'photo'), Kit::word('le cadeau', 'cadeau'), Kit::form('cette')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.parapluie', 'Ce parapluie est à Marie.', "This umbrella is Marie's.", [Kit::word('le parapluie', 'parapluie'), Kit::form('ce')], homophoneNote: self::EST_NOTE.' '.self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.verre', 'Ce verre est à Paul.', "This glass is Paul's.", [Kit::word('le verre', 'verre'), Kit::form('ce')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sac', 'Je préfère ce sac.', 'I prefer this bag.', [Kit::word('le sac', 'sac'), Kit::word('préférer', 'préfère'), Kit::form('ce')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.voiture', 'Cette voiture est à Anne.', "This car is Anne's.", [Kit::word('la voiture', 'voiture'), Kit::form('cette')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.parapluie', "C'est quoi, ça ? C'est un parapluie.", 'What is this? It is an umbrella.', [Kit::word('le parapluie', 'parapluie'), Kit::form('ça')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ca', "C'est quoi, ça ?", 'What is this?', [["c'est", 'un', 'une'], self::THINGS], "C'est un cadeau.", [Kit::word('le cadeau', 'cadeau'), Kit::form('ça')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sac-voiture', 'Tu préfères le sac ou la voiture ?', 'Do you prefer the bag or the car?', [['préfère', 'ce', 'cette'], ['sac', 'voiture']], 'Je préfère le sac.', [Kit::word('le sac', 'sac'), Kit::word('préférer', 'préfère')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.voiture', 'À qui est la voiture ?', 'Whose car is it?', [['est', 'à'], self::NAMES], 'La voiture est à Luc.', [Kit::word('la voiture', 'voiture')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.cadeau', 'Read the conversation.', [
                Kit::line('Anne', 'Paul, tu préfères ce sac ou ce chapeau ?'),
                Kit::line('Paul', "Je préfère ce chapeau. C'est un cadeau ?"),
                Kit::line('Anne', "Oui, c'est un cadeau pour Marie. Ces stylos sont à Luc."),
            ], [
                Kit::question('What does Paul prefer?', ['The hat', 'The bag', 'The pen'], 'The hat'),
                Kit::question('Who is the gift for?', ['Marie', 'Luc', 'Paul'], 'Marie'),
                Kit::question('Whose are the pens?', ["Luc's", "Marie's", "Paul's"], "Luc's"),
            ], [Kit::word('le sac', 'sac'), Kit::word('le chapeau', 'chapeau'), Kit::word('préférer', 'préfères'), Kit::word('le cadeau', 'cadeau'), Kit::word('le stylo', 'stylos')], 'read'),
            Kit::gap($stage, 'task.choose_gap.photos', '___ photos sont à Anne.', ['Ces', 'Ce', 'Cette'], 'Ces', Kit::form('ces', true), 'Photos is plural, so these is ces. Ce and cette are for one thing.', 'read', "These photos are Anne's."),
            Kit::gap($stage, 'task.choose_gap.voitures', '___ voitures sont à Paul.', ['Ces', 'Cette', 'Ce'], 'Ces', Kit::form('ces', true), 'Voitures is plural, so these is ces, even though voiture is feminine. Cette is for one thing.', 'read', "These cars are Paul's."),

            Kit::transform($stage, 'task.transform.sacs', 'Change it to several things: these.', 'Ce sac est à Anne.', ['Ces sacs sont à Anne.'], [Kit::word('le sac', 'sacs'), Kit::form('ces', true)]),
            Kit::transform($stage, 'task.transform.photos', 'Change it to several things: these.', 'Cette photo est à Luc.', ['Ces photos sont à Luc.'], [Kit::word('la photo', 'photos'), Kit::form('ces', true)]),
            Kit::transform($stage, 'task.transform.voiture', 'Say it about a car instead of a bag.', 'Ce sac est à Marie.', ['Cette voiture est à Marie.'], [Kit::word('la voiture', 'voiture'), Kit::form('cette', true)]),
            Kit::writeGuided($stage, 'task.write_guided.chapeau-sac', 'Say that you prefer this hat and this bag.', ['préfère', 'ce', 'chapeau', 'sac'], 'Je préfère ce chapeau et ce sac.', [
                ['forms' => ['préfère'], 'term' => 'préférer'],
                ['forms' => ['ce'], 'term' => null],
                ['forms' => ['chapeau'], 'term' => 'le chapeau'],
                ['forms' => ['sac'], 'term' => 'le sac'],
            ], [Kit::word('préférer', 'préfère'), Kit::word('le chapeau', 'chapeau'), Kit::word('le sac', 'sac'), Kit::form('ce')]),
            Kit::writeGuided($stage, 'task.write_guided.stylos-cadeau', "Say that these pens are Luc's and that this gift is for Anne.", ['ces', 'stylos', 'ce', 'cadeau', 'pour'], 'Ces stylos sont à Luc et ce cadeau est pour Anne.', [
                ['forms' => ['ces'], 'term' => null],
                ['forms' => ['stylos'], 'term' => 'le stylo'],
                ['forms' => ['ce'], 'term' => null],
                ['forms' => ['cadeau'], 'term' => 'le cadeau'],
            ], [Kit::word('le stylo', 'stylos'), Kit::word('le cadeau', 'cadeau'), Kit::form('ces')]),
            Kit::build($stage, 'task.build.stylos', 'Whose pens are these?', 'À qui sont ces stylos ?', ['ce', 'est'], [Kit::word('le stylo', 'stylos'), Kit::form('ces')], 'write'),
            Kit::build($stage, 'task.build.photo-cadeau', 'This photo is a gift for Luc.', 'Cette photo est un cadeau pour Luc.', ['ce', 'ces'], [Kit::word('la photo', 'photo'), Kit::word('le cadeau', 'cadeau'), Kit::form('cette')], 'write'),
            Kit::build($stage, 'task.build.paul-voiture', 'Paul prefers this car.', 'Paul préfère cette voiture.', ['ce', 'préfères'], [Kit::word('préférer', 'préfère'), Kit::word('la voiture', 'voiture'), Kit::form('cette')], 'write'),
            Kit::translate($stage, 'task.translate.verre', 'I prefer this glass, but Paul prefers this bag.', ['Je préfère ce verre, mais Paul préfère ce sac.'], [Kit::word('le verre', 'verre'), Kit::word('préférer', 'préfère'), Kit::form('ce')], 'write'),
            Kit::translate($stage, 'task.translate.voiture', "Whose car is this? It is Marie's.", ['À qui est cette voiture ? Elle est à Marie.', "À qui est cette voiture ? C'est à Marie.", 'À qui est cette voiture ? Cette voiture est à Marie.'], [Kit::word('la voiture', 'voiture'), Kit::form('cette')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.sac-a-dos', [
                Kit::line('Luc', 'Marie, à qui est ce sac à dos ?'),
                Kit::line('Marie', 'Il est à Anne. Ces stylos sont aussi à Anne.'),
                Kit::line('Luc', 'Et ce parapluie, il est à Paul ?'),
                Kit::line('Marie', 'Oui, il est à Paul.'),
            ], [
                Kit::question('Whose is the backpack?', ["Anne's", "Marie's", "Paul's"], "Anne's"),
                Kit::question('Whose are the pens?', ["Anne's", "Luc's", "Paul's"], "Anne's"),
                Kit::question('Whose is the umbrella?', ["Paul's", "Anne's", "Marie's"], "Paul's"),
            ], [
                Kit::question('Who asks about the backpack?', ['Luc', 'Marie', 'Paul'], 'Luc'),
                Kit::question("Is the umbrella Paul's?", ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le sac à dos', 'sac à dos'), Kit::word('le stylo', 'stylos'), Kit::word('le parapluie', 'parapluie')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.photos-cadeaux', 'Ces photos sont des cadeaux pour Marie.', 'These photos are gifts for Marie.', [Kit::word('la photo', 'photos'), Kit::word('le cadeau', 'cadeaux'), Kit::form('ces')], 'listen', homophoneNote: self::CES_NOTE),
            Kit::listenType($stage, 'task.listen_type.voiture-sac', 'Tu préfères cette voiture ou ce sac ?', 'Do you prefer this car or this bag? (informal you)', [Kit::word('la voiture', 'voiture'), Kit::word('préférer', 'préfères'), Kit::form('cette')], 'listen', homophoneNote: self::OU_NOTE),
            Kit::listenType($stage, 'task.listen_type.chapeau-sac', 'Je préfère ce chapeau, pas ce sac.', 'I prefer this hat, not this bag.', [Kit::word('le chapeau', 'chapeau'), Kit::word('le sac', 'sac'), Kit::word('préférer', 'préfère'), Kit::form('ce')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.ca', "C'est quoi, ça ?", 'What is this?', [["c'est", 'un', 'une'], self::THINGS], "C'est un parapluie.", [Kit::word('le parapluie', 'parapluie'), Kit::form('ça')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chapeau-parapluie', 'Tu préfères le chapeau ou le parapluie ?', 'Do you prefer the hat or the umbrella?', [['préfère', 'ce', 'cette'], ['chapeau', 'parapluie']], 'Je préfère le chapeau.', [Kit::word('le chapeau', 'chapeau'), Kit::word('préférer', 'préfère')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sac-a-dos', 'À qui est le sac à dos ?', 'Whose backpack is it?', [['est', 'à'], self::NAMES], 'Il est à Marie.', [Kit::word('le sac à dos', 'sac à dos')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.stylo', 'À qui est le stylo ?', 'Whose pen is it?', [['est', 'à'], self::NAMES], 'Il est à Paul.', [Kit::word('le stylo', 'stylo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.sacs-a-dos', 'Ces sacs à dos sont à Anne.', "These backpacks are Anne's.", [Kit::word('le sac à dos', 'sacs à dos'), Kit::form('ces')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.verre', 'Paul préfère ce verre.', 'Paul prefers this glass.', [Kit::word('le verre', 'verre'), Kit::word('préférer', 'préfère'), Kit::form('ce')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sac-a-dos-cadeau', 'This backpack is a gift for Anne.', ['Ce sac à dos est un cadeau pour Anne.'], [Kit::word('le sac à dos', 'sac à dos'), Kit::word('le cadeau', 'cadeau'), Kit::form('ce')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.chapeau-parapluie', 'Do you prefer this hat or this umbrella? (informal you)', ['Tu préfères ce chapeau ou ce parapluie ?'], [Kit::word('le chapeau', 'chapeau'), Kit::word('le parapluie', 'parapluie'), Kit::word('préférer', 'préfères'), Kit::form('ce', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.stylos', "These pens are not Paul's.", ['Ces stylos ne sont pas à Paul.'], [Kit::word('le stylo', 'stylos'), Kit::form('ces')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.voiture', 'Whose car is this?', ['À qui est cette voiture ?'], [Kit::word('la voiture', 'voiture'), Kit::form('cette')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.verre', '___ verre est à Luc.', "This glass is Luc's.", 'Ce', Kit::form('ce', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.chapeaux', '___ chapeaux sont à Paul.', "These hats are Paul's.", 'Ces', Kit::form('ces'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sac-voiture', 'Je préfère le sac, pas la voiture.', 'I prefer the bag, not the car.', [Kit::word('préférer', 'préfère'), Kit::word('le sac', 'sac'), Kit::word('la voiture', 'voiture')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.verre-chapeau', 'Le verre et le chapeau sont des cadeaux.', 'The glass and the hat are gifts.', [Kit::word('le verre', 'verre'), Kit::word('le chapeau', 'chapeau'), Kit::word('le cadeau', 'cadeaux')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.photo-sac-a-dos', 'La photo et le sac à dos sont à Marie.', "The photo and the backpack are Marie's.", [Kit::word('la photo', 'photo'), Kit::word('le sac à dos', 'sac à dos')], 'dictation', $set, homophoneNote: self::ET_NOTE.' '.self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.cadeau', [
                Kit::line('Luc', "Marie, c'est quoi, ça ?"),
                Kit::line('Marie', "C'est un cadeau pour Paul."),
                Kit::line('Luc', "C'est un stylo ou un chapeau ?"),
                Kit::line('Marie', "C'est un chapeau."),
            ], [
                Kit::question('Who is the gift for?', ['Paul', 'Luc', 'Anne'], 'Paul'),
                Kit::question('What is the gift?', ['A hat', 'A pen', 'A bag'], 'A hat'),
                Kit::question('Who asks what it is?', ['Luc', 'Marie', 'Paul'], 'Luc'),
            ], [
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Is the gift for Paul?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is the gift a pen?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('le cadeau', 'cadeau'), Kit::word('le stylo', 'stylo'), Kit::word('le chapeau', 'chapeau')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.parapluie', 'Read the conversation.', [
                Kit::line('Paul', 'Anne, à qui est ce parapluie ?'),
                Kit::line('Anne', 'Il est à Luc. Cette photo est aussi à Luc.'),
                Kit::line('Paul', 'Voici un cadeau pour Marie : un sac et un verre.'),
            ], [
                Kit::question('Whose umbrella is it?', ["Luc's", "Anne's", "Marie's"], "Luc's"),
                Kit::question('What is the gift for Marie?', ['A bag and a glass', 'An umbrella and a photo', 'A hat and a pen'], 'A bag and a glass'),
            ], [Kit::word('le parapluie', 'parapluie'), Kit::word('la photo', 'photo'), Kit::word('le cadeau', 'cadeau'), Kit::word('le sac', 'sac'), Kit::word('le verre', 'verre')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.voiture-chapeau', 'Tu préfères la voiture ou le chapeau ?', 'Do you prefer the car or the hat?', [['préfère', 'ce', 'cette'], ['voiture', 'chapeau']], 'Je préfère la voiture.', [Kit::word('la voiture', 'voiture'), Kit::word('le chapeau', 'chapeau'), Kit::word('préférer', 'préfère')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sac', 'À qui est le sac ?', 'Whose bag is it?', [['est', 'à'], self::NAMES], 'Il est à Anne.', [Kit::word('le sac', 'sac')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.photo', 'À qui est la photo ?', 'Whose photo is it?', [['est', 'à'], self::NAMES], 'Elle est à Marie.', [Kit::word('la photo', 'photo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.chapeaux-cadeaux', 'These hats are gifts for Marie.', ['Ces chapeaux sont des cadeaux pour Marie.'], [Kit::word('le chapeau', 'chapeaux'), Kit::word('le cadeau', 'cadeaux'), Kit::form('ces')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.chapeau-photo', 'I prefer this hat, not this photo.', ['Je préfère ce chapeau, pas cette photo.'], [Kit::word('le chapeau', 'chapeau'), Kit::word('la photo', 'photo'), Kit::word('préférer', 'préfère'), Kit::form('ce', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sacs-parapluies', 'Whose bags and umbrellas are these?', ['À qui sont ces sacs et ces parapluies ?', 'À qui sont ces sacs et parapluies ?'], [Kit::word('le sac', 'sacs'), Kit::word('le parapluie', 'parapluies'), Kit::form('ces')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.stylo-verre', "This pen and this glass are Luc's.", ['Ce stylo et ce verre sont à Luc.'], [Kit::word('le stylo', 'stylo'), Kit::word('le verre', 'verre'), Kit::form('ce')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.voiture', 'Cette ___ est à Luc.', "This car is Luc's.", 'voiture', Kit::word('la voiture', 'voiture'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.sacs', 'Ces ___ sont à Paul.', "These bags are Paul's.", 'sacs', Kit::word('le sac', 'sacs'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.photo-chapeau', 'Tu préfères cette photo ou ce chapeau ?', 'Do you prefer this photo or this hat? (informal you)', [Kit::word('préférer', 'préfères'), Kit::word('la photo', 'photo'), Kit::word('le chapeau', 'chapeau'), Kit::form('cette', true)], 'dictation', $set, homophoneNote: self::OU_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.parapluies-sacs-a-dos', 'Ces parapluies et ces sacs à dos sont à Anne.', "These umbrellas and backpacks are Anne's.", [Kit::word('le parapluie', 'parapluies'), Kit::word('le sac à dos', 'sacs à dos'), Kit::form('ces')], 'dictation', $set, homophoneNote: self::ET_NOTE.' '.self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sac-stylo-verre', 'Le sac, le stylo et le verre sont des cadeaux.', 'The bag, the pen and the glass are gifts.', [Kit::word('le sac', 'sac'), Kit::word('le stylo', 'stylo'), Kit::word('le verre', 'verre'), Kit::word('le cadeau', 'cadeaux')], 'dictation', $set, homophoneNote: self::ET_NOTE),
        ];
    }
}
