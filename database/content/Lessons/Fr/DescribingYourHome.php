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

final class DescribingYourHome implements UnitContent
{
    private const A_NOTE = 'A (has, the verb) and à (to, at) sound identical. In il y a (there is) and in a (has), it is the verb a, with no accent.';

    private const IL_Y_A_NOTE = 'A (has, the verb) and à (to, at) sound identical. In il y a (there is), the a is the verb, so it has no accent.';

    private const ET_SONT_NOTE = 'Et (and) sounds very close to est (is): here et means and. Sont (are) sounds like son (his, her): here it is the verb.';

    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const A_ET_NOTE = 'A (has, the verb) and à (to, at) sound identical, and et (and) sounds very close to est (is): here a is the verb, with no accent, and et means and.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'describing-your-home';
    }

    public function words(): array
    {
        return [
            new WordData('la maison', cue: 'house (or home)', forms: ['maisons']),
            new WordData("l'appartement", cue: 'flat (apartment)', forms: ['appartement', 'appartements']),
            new WordData('la cuisine', cue: 'kitchen', forms: ['cuisines']),
            new WordData('le salon', cue: 'living room', forms: ['salons']),
            new WordData('la chambre', cue: 'bedroom', forms: ['chambres']),
            new WordData('le jardin', cue: 'garden', forms: ['jardins']),
            new WordData('la table', cue: 'table', forms: ['tables']),
            new WordData('la chaise', cue: 'chair', forms: ['chaises']),
            new WordData('le lit', cue: 'bed', forms: ['lits']),
            new WordData('la fenêtre', cue: 'window', forms: ['fenêtres']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Il y a une table dans la cuisine.', 'english' => 'There is a table in the kitchen.'],
            ['text' => 'La table est dans la cuisine.', 'english' => 'The table is in the kitchen.'],
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
        $s = Stage::Sentences;
        $maison = Kit::word('la maison', 'maison');
        $appart = Kit::word("l'appartement", 'appartement');
        $cuisine = Kit::word('la cuisine', 'cuisine');
        $salon = Kit::word('le salon', 'salon');
        $chambre = Kit::word('la chambre', 'chambre');
        $jardin = Kit::word('le jardin', 'jardin');
        $table = Kit::word('la table', 'table');
        $chaise = Kit::word('la chaise', 'chaise');
        $lit = Kit::word('le lit', 'lit');
        $fenetre = Kit::word('la fenêtre', 'fenêtre');

        return [
            Kit::gap($s, 'sentences.choose_gap.table-est', 'La table ___ dans la cuisine.', ['est', 'il y a'], 'est', Kit::form('est', true), 'La table is a table you both know, so we say est. Il y a is for introducing something new.', 'choose', 'The table is in the kitchen.'),
            Kit::gap($s, 'sentences.choose_gap.lit-il-y-a', '___ un lit dans la chambre.', ['Il y a', 'Il est'], 'Il y a', Kit::form('il y a', true), 'Un lit is something new, so we say il y a: there is a bed.', 'choose', 'There is a bed in the bedroom.'),
            Kit::gap($s, 'sentences.choose_gap.chaise-sous', 'La chaise est ___ la table.', ['sous', 'sur', 'dans'], 'sous', Kit::form('sous'), 'Sous means under. Sur means on and dans means in.', 'choose', 'The chair is under the table.'),
            Kit::gap($s, 'sentences.choose_gap.fenetre-dans', 'Il y a une fenêtre ___ le salon.', ['dans', 'sur', 'sous'], 'dans', Kit::form('dans'), 'Dans means in. Sur and sous mean on and under.', 'choose', 'There is a window in the living room.'),
            Kit::gap($s, 'sentences.choose_gap.jardin', 'Il y a une table dans le ___.', ['jardin', 'cuisine', 'chaise'], 'jardin', $jardin, 'Le means the word is masculine: le jardin. Cuisine and chaise are feminine, so they would need la, and they do not mean garden.', 'choose', 'There is a table in the garden.'),
            Kit::gap($s, 'sentences.choose_gap.chambres', 'Mon appartement a deux ___.', ['chambres', 'chambre'], 'chambres', Kit::word('la chambre', 'chambres'), 'After deux we need the plural, so the word ends in -s. You do not hear it, but you write it.', 'choose', 'My flat has two bedrooms.'),

            Kit::typeGap($s, 'sentences.type_gap.chaise-dans', 'Il y a une chaise ___ le jardin.', 'There is a chair in the garden.', 'dans', Kit::form('dans'), 'Dans means in.'),
            Kit::typeGap($s, 'sentences.type_gap.lit-est', 'Le lit ___ dans la chambre.', 'The bed is in the bedroom.', 'est', Kit::form('est', true), 'Le lit is a bed you both know, so we say est, not il y a.'),
            Kit::typeGap($s, 'sentences.type_gap.chaises-il-y-a', '___ deux chaises dans la cuisine.', 'There are two chairs in the kitchen.', 'Il y a', Kit::form('il y a', true), 'Il y a stays the same for one thing or for many things.'),
            Kit::typeGap($s, 'sentences.type_gap.chaises-sont', 'Les chaises ___ dans le salon.', 'The chairs are in the living room.', 'sont', Kit::form('sont', true), 'Les chaises is plural and known, so we say sont.'),
            Kit::typeGap($s, 'sentences.type_gap.fenetre-cote', 'La fenêtre est à ___ du lit.', 'The window is next to the bed.', 'côté', Kit::form('côté'), 'À côté de means next to. De joins le into du: à côté du lit.'),

            Kit::translate($s, 'sentences.translate.table-salon', 'There is a table in the living room.', ['Il y a une table au salon.', 'Au salon, il y a une table.'], [$table, $salon, Kit::form('il y a', true)]),
            Kit::translate($s, 'sentences.translate.chaise-pres', 'The chair is near the table.', ['La chaise est près de la table.'], [$chaise, $table, Kit::form('près')]),
            Kit::translate($s, 'sentences.translate.maison-jardin', 'We have a house with a garden.', ['Nous avons une maison avec un jardin.', 'Nous avons une maison avec jardin.', 'On a une maison avec un jardin.', 'On a une maison avec jardin.'], [$maison, $jardin]),

            Kit::build($s, 'sentences.build.jardin-cote', 'My flat is next to the garden.', 'Mon appartement est à côté du jardin.', ['sont'], [$appart, $jardin, Kit::form('côté')]),
            Kit::build($s, 'sentences.build.fenetre-salon', 'The window is in the living room.', 'La fenêtre est dans le salon.', ['sur'], [$fenetre, $salon, Kit::form('est', true)]),
            Kit::build($s, 'sentences.build.maison-jardin', 'The house has a garden.', 'La maison a un jardin.', ['est'], [$maison, $jardin]),

            Kit::listenChoose($s, 'sentences.listen_choose.table-salon', 'Il y a une table au salon.', ['There is a table in the living room.', 'There is a chair in the living room.', 'There is a table in the kitchen.', 'The table is in the living room.'], 'There is a table in the living room.', [$table, $salon, Kit::form('il y a', true)]),
            Kit::listenChoose($s, 'sentences.listen_choose.lit-sous', 'Le lit est sous la fenêtre.', ['The bed is under the window.', 'The bed is next to the window.', 'The chair is under the window.', 'The window is under the bed.'], 'The bed is under the window.', [$lit, $fenetre, Kit::form('sous')]),
            Kit::listenChoose($s, 'sentences.listen_choose.chambres', 'Ma maison a deux chambres.', ['My house has two bedrooms.', 'My house has two kitchens.', 'My flat has two bedrooms.', 'My house has three bedrooms.'], 'My house has two bedrooms.', [$maison, Kit::word('la chambre', 'chambres')]),
            Kit::listenType($s, 'sentences.listen_type.chaise-jardin', 'Il y a une chaise au jardin.', 'There is a chair in the garden.', [$chaise, $jardin, Kit::form('il y a', true)], homophoneNote: self::IL_Y_A_NOTE),
            Kit::listenType($s, 'sentences.listen_type.table-jardin', 'La table est dans le jardin.', 'The table is in the garden.', [$table, $jardin, Kit::form('est', true)], homophoneNote: self::EST_NOTE),
            Kit::listenType($s, 'sentences.listen_type.appartement-pres', 'Mon appartement est près de la maison.', 'My flat is near the house.', [$appart, $maison, Kit::form('près')], homophoneNote: self::EST_NOTE),
            Kit::listenType($s, 'sentences.listen_type.chaises-sous', 'Les chaises sont sous la table.', 'The chairs are under the table.', [Kit::word('la chaise', 'chaises'), $table, Kit::form('sous')]),

            Kit::speakRepeat($s, 'sentences.speak_repeat.cuisine-salon', 'La cuisine est à côté du salon.', 'The kitchen is next to the living room.', [$cuisine, $salon, Kit::form('côté')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.lit-chambre', 'Il y a un lit ici.', 'There is a bed here.', [$lit, Kit::form('il y a', true)]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.cuisine-fenetre', 'Ma cuisine a une fenêtre.', 'My kitchen has a window.', [$cuisine, $fenetre]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.appartement-salon', 'Mon appartement a un salon.', 'My flat has a living room.', [$appart, $salon]),
            Kit::speakAnswer($s, 'sentences.speak_answer.table-salon', 'Il y a une table au salon ?', 'Is there a table in the living room?', [['oui', 'non', 'a'], ['table', 'salon']], 'Oui, il y a une table au salon.', [$table, $salon, Kit::form('il y a', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.lit-chambre', 'Le lit est dans la chambre ?', 'Is the bed in the bedroom?', [['oui', 'non', 'est'], ['lit', 'chambre']], 'Oui, le lit est dans la chambre.', [$lit, $chambre, Kit::form('est', true)]),
            Kit::speakAnswer($s, 'sentences.speak_answer.jardin', 'Il y a quoi dans le jardin ?', 'What is there in the garden?', [['a', 'une', 'un', 'deux'], ['table', 'chaise', 'chaises', 'maison']], 'Il y a une table et deux chaises.', [$jardin, $chaise, Kit::form('il y a', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $maison = Kit::word('la maison', 'maison');
        $appart = Kit::word("l'appartement", 'appartement');
        $cuisine = Kit::word('la cuisine', 'cuisine');
        $salon = Kit::word('le salon', 'salon');
        $chambre = Kit::word('la chambre', 'chambre');
        $jardin = Kit::word('le jardin', 'jardin');
        $table = Kit::word('la table', 'table');
        $chaise = Kit::word('la chaise', 'chaise');
        $lit = Kit::word('le lit', 'lit');
        $fenetre = Kit::word('la fenêtre', 'fenêtre');
        $chaises = Kit::word('la chaise', 'chaises');
        $chambres = Kit::word('la chambre', 'chambres');

        return [
            Kit::readPassage($s, 'task.read_passage.appartement', 'Read the conversation about Marie\'s flat.', [
                Kit::line('Anne', 'Bonjour, Marie. Comment est ton appartement ?'),
                Kit::line('Marie', 'Mon appartement a un salon et deux chambres. Dans le salon, il y a une table et quatre chaises.'),
                Kit::line('Anne', 'Et la cuisine ?'),
                Kit::line('Marie', 'La cuisine est à côté du salon. Il y a une fenêtre.'),
            ], [
                Kit::question('How many bedrooms does the flat have?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What is in the living room?', ['A table and four chairs', 'A bed and a table', 'A garden'], 'A table and four chairs'),
                Kit::question('Where is the kitchen?', ['Next to the living room', 'Under the living room', 'In the garden'], 'Next to the living room'),
            ], [$appart, $salon, $chambres, $table, $chaises, $cuisine, $fenetre], 'read'),
            Kit::gap($s, 'task.choose_gap.ou-est', 'Où ___ la table ?', ['est', 'il y a'], 'est', Kit::form('est', true), 'La table is a table you both know, so we ask where it is with est.', 'read', 'Where is the table?'),
            Kit::gap($s, 'task.choose_gap.salon-il-y-a', 'Dans le salon, ___ une table ?', ['il y a', 'est'], 'il y a', Kit::form('il y a', true), 'We do not know yet whether a table is there, so we ask with il y a. It is a question by intonation: the voice goes up.', 'read', 'Is there a table in the living room?'),

            Kit::transform($s, 'task.transform.chaises', 'Say it about "the chairs" (plural).', 'La chaise est dans le jardin.', ['Les chaises sont dans le jardin.'], [$chaises, $jardin, Kit::form('sont', true)]),
            Kit::transform($s, 'task.transform.table', 'Change it to "the table" (not "a table").', 'Il y a une table dans la cuisine.', ['La table est dans la cuisine.'], [$table, $cuisine, Kit::form('est', true)]),
            Kit::transform($s, 'task.transform.fenetre', 'Change it to "there is a window".', 'La fenêtre est dans le salon.', ['Il y a une fenêtre dans le salon.', 'Dans le salon, il y a une fenêtre.'], [$fenetre, $salon, Kit::form('il y a', true)]),
            Kit::writeGuided($s, 'task.write_guided.appartement', 'Say that your flat has a living room and two bedrooms.', ['appartement', 'a', 'salon', 'chambres'], 'Mon appartement a un salon et deux chambres.', [
                ['forms' => ['appartement'], 'term' => "l'appartement"],
                ['forms' => ['salon'], 'term' => 'le salon'],
                ['forms' => ['chambres'], 'term' => 'la chambre'],
            ], [$appart, $salon, $chambres]),
            Kit::writeGuided($s, 'task.write_guided.jardin', 'Say that there is a table and a chair in the garden.', ['il y a', 'table', 'chaise', 'jardin'], 'Il y a une table et une chaise dans le jardin.', [
                ['forms' => ['a'], 'term' => null],
                ['forms' => ['table'], 'term' => 'la table'],
                ['forms' => ['chaise'], 'term' => 'la chaise'],
                ['forms' => ['jardin'], 'term' => 'le jardin'],
            ], [$table, $chaise, $jardin]),
            Kit::build($s, 'task.build.cuisine-chambre', 'The kitchen is next to the bedroom.', 'La cuisine est à côté de la chambre.', ['sur', 'dans'], [$cuisine, $chambre, Kit::form('côté')], 'write'),
            Kit::build($s, 'task.build.chaise-table', 'There is a chair under the table.', 'Il y a une chaise sous la table.', ['est', 'sur'], [$chaise, $table, Kit::form('il y a', true)], 'write'),
            Kit::build($s, 'task.build.jardin-maison', 'The garden is near the house.', 'Le jardin est près de la maison.', ['dans', 'au'], [$jardin, $maison, Kit::form('près')], 'write'),
            Kit::translate($s, 'task.translate.lit', 'Is there a bed in the bedroom?', ['Il y a un lit dans la chambre ?', 'Dans la chambre, il y a un lit ?'], [$lit, $chambre, Kit::form('il y a', true)], 'write'),
            Kit::translate($s, 'task.translate.chaises', 'Where are the chairs?', ['Où sont les chaises ?'], [$chaises, Kit::form('sont', true)], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.jardin', [
                Kit::line('Luc', 'Nous avons une maison avec un jardin.'),
                Kit::line('Anne', 'Il y a quoi dans le jardin ?'),
                Kit::line('Luc', 'Il y a une table et deux chaises. La table est à côté de la maison.'),
            ], [
                Kit::question('What do Luc and his family have?', ['A house with a garden', 'A flat with a garden', 'A house without a garden'], 'A house with a garden'),
                Kit::question('What is in the garden?', ['A table and two chairs', 'A bed and a chair', 'Two tables'], 'A table and two chairs'),
                Kit::question('Where is the table?', ['Next to the house', 'Under the window', 'In the kitchen'], 'Next to the house'),
            ], [
                Kit::question('Who has a house with a garden?', ['Luc', 'Anne', 'Nobody'], 'Luc'),
                Kit::question('How many chairs are there?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Anne ask about?', ['The garden', 'The kitchen', 'The bed'], 'The garden'),
            ], [$maison, $jardin, $table, $chaises], 'listen'),
            Kit::listenType($s, 'task.listen_type.lit-fenetre', 'Il y a un lit et une fenêtre dans la chambre.', 'There is a bed and a window in the bedroom.', [$lit, $fenetre, $chambre, Kit::form('il y a', true)], 'listen', homophoneNote: self::A_ET_NOTE),
            Kit::listenType($s, 'task.listen_type.maison-cuisine', 'Ma maison a une cuisine et un salon.', 'My house has a kitchen and a living room.', [$maison, $cuisine, $salon], 'listen', homophoneNote: self::A_ET_NOTE),
            Kit::listenType($s, 'task.listen_type.chaise-sur', 'Il y a une chaise sur le lit.', 'There is a chair on the bed.', [$chaise, $lit, Kit::form('sur')], 'listen', homophoneNote: self::IL_Y_A_NOTE),

            Kit::speakAnswer($s, 'task.speak_answer.chambre', 'Il y a quoi dans ta chambre ?', 'What is there in your bedroom?', [['a', 'une', 'un', 'deux'], ['lit', 'table', 'chaise', 'fenêtre']], 'Il y a un lit et une fenêtre.', [$chambre, $lit, Kit::form('il y a', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.maison-jardin', 'Ta maison a un jardin ?', 'Does your house have a garden?', [['oui', 'non', 'a'], ['jardin', 'maison']], 'Oui, ma maison a un jardin.', [$maison, $jardin], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.lit', 'Où est ton lit ?', 'Where is your bed?', [['lit', 'est'], ['chambre', 'appartement', 'maison', 'salon']], 'Mon lit est dans ma chambre.', [$lit, $chambre, Kit::form('est', true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.chaise-salon', 'Il y a une chaise dans ton salon ?', 'Is there a chair in your living room?', [['oui', 'non', 'a'], ['chaise', 'salon']], 'Oui, il y a une chaise dans mon salon.', [$chaise, $salon, Kit::form('il y a', true)], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.appartement-maison', 'Mon appartement est près de la maison de Paul.', 'My flat is near Paul\'s house.', [$appart, $maison, Kit::form('près')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.chambre', 'Dans la chambre, il y a un lit et une fenêtre.', 'In the bedroom there is a bed and a window.', [$chambre, $lit, $fenetre, Kit::form('il y a', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $maison = Kit::word('la maison', 'maison');
        $appart = Kit::word("l'appartement", 'appartement');
        $cuisine = Kit::word('la cuisine', 'cuisine');
        $salon = Kit::word('le salon', 'salon');
        $chambre = Kit::word('la chambre', 'chambre');
        $jardin = Kit::word('le jardin', 'jardin');
        $table = Kit::word('la table', 'table');
        $chaise = Kit::word('la chaise', 'chaise');
        $lit = Kit::word('le lit', 'lit');
        $fenetre = Kit::word('la fenêtre', 'fenêtre');

        return [
            Kit::translate($s, 'check.a.translate.chambre', 'The bedroom is next to the kitchen.', ['La chambre est à côté de la cuisine.'], [$chambre, $cuisine, Kit::form('côté')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.fenetre', 'There is a window in the bedroom.', ['Il y a une fenêtre dans la chambre.', 'Dans la chambre, il y a une fenêtre.'], [$fenetre, $chambre, Kit::form('il y a', true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.appartement', 'My flat has a garden.', ['Mon appartement a un jardin.'], [$appart, $jardin], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.chaise', 'The chair is under the bed.', ['La chaise est sous le lit.'], [$chaise, $lit, Kit::form('sous')], 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.table', 'La table ___ dans la maison.', 'The table is in the house.', 'est', Kit::form('est', true), null, 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.lits', '___ deux lits dans la chambre.', 'There are two beds in the bedroom.', 'Il y a', Kit::form('il y a', true), null, 'sentences', $a),
            Kit::listenType($s, 'check.a.listen_type.maison', 'Ma maison a trois chambres.', 'My house has three bedrooms.', [$maison, Kit::word('la chambre', 'chambres')], 'dictation', $a, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.a.listen_type.table', 'La table est près du salon.', 'The table is near the living room.', [$table, $salon, Kit::form('est', true)], 'dictation', $a, homophoneNote: self::EST_NOTE),
            Kit::listenType($s, 'check.a.listen_type.appartement', 'Mon appartement a un salon et une cuisine.', 'My flat has a living room and a kitchen.', [$appart, $salon, $cuisine], 'dictation', $a, homophoneNote: self::A_ET_NOTE),
            Kit::listenPassage($s, 'check.a.listen_passage.maison', [
                Kit::line('Marie', 'Ma maison a une cuisine et un jardin.'),
                Kit::line('Luc', 'Et il y a un salon ?'),
                Kit::line('Marie', 'Oui. Au salon, il y a une table et deux chaises.'),
            ], [
                Kit::question('What does Marie\'s house have?', ['A kitchen and a garden', 'A kitchen and a bedroom', 'A living room and a bedroom'], 'A kitchen and a garden'),
                Kit::question('What does Luc ask about?', ['The living room', 'The kitchen', 'The garden'], 'The living room'),
                Kit::question('What is in the living room?', ['A table and two chairs', 'A bed and a table', 'Two tables'], 'A table and two chairs'),
            ], [
                Kit::question('Who has a house with a garden?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('How many chairs are there in the living room?', ['Two', 'Four', 'One'], 'Two'),
                Kit::question('What does Luc ask?', ['Whether the house has a living room', 'Whether the house has a garden', 'Whether there is a bed'], 'Whether the house has a living room'),
            ], [$maison, $cuisine, $jardin, $salon, $table, Kit::word('la chaise', 'chaises')], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.appartement', 'Read the conversation.', [
                Kit::line('Anne', 'Bonjour, Luc. Mon appartement a une chambre.'),
                Kit::line('Luc', 'Il y a un lit et une fenêtre ?'),
                Kit::line('Anne', 'Oui, il y a un lit et une fenêtre.'),
            ], [
                Kit::question('What does Anne\'s flat have?', ['A bedroom', 'A garden', 'A kitchen'], 'A bedroom'),
                Kit::question('What does Luc ask about?', ['A bed and a window', 'A table and a chair', 'A garden'], 'A bed and a window'),
            ], [$appart, $chambre, $lit, $fenetre], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.fenetre', 'Il y a une fenêtre dans la cuisine ?', 'Is there a window in the kitchen?', [['oui', 'non', 'a'], ['fenêtre', 'cuisine']], 'Oui, il y a une fenêtre dans la cuisine.', [$fenetre, $cuisine], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.appartement', 'Ton appartement a un jardin ?', 'Does your flat have a garden?', [['oui', 'non', 'a'], ['appartement', 'jardin']], 'Oui, mon appartement a un jardin.', [$appart, $jardin], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.chaise', 'La chaise est dans la cuisine ?', 'Is the chair in the kitchen?', [['oui', 'non', 'est'], ['chaise', 'cuisine']], 'Oui, la chaise est dans la cuisine.', [$chaise, $cuisine], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $maison = Kit::word('la maison', 'maison');
        $appart = Kit::word("l'appartement", 'appartement');
        $cuisine = Kit::word('la cuisine', 'cuisine');
        $salon = Kit::word('le salon', 'salon');
        $chambre = Kit::word('la chambre', 'chambre');
        $jardin = Kit::word('le jardin', 'jardin');
        $table = Kit::word('la table', 'table');
        $chaise = Kit::word('la chaise', 'chaise');
        $lit = Kit::word('le lit', 'lit');
        $fenetre = Kit::word('la fenêtre', 'fenêtre');

        return [
            Kit::translate($s, 'check.b.translate.lit', 'The bed is next to the window.', ['Le lit est à côté de la fenêtre.'], [$lit, $fenetre, Kit::form('côté')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.chaises', 'There are two chairs in the garden.', ['Il y a deux chaises dans le jardin.', 'Dans le jardin, il y a deux chaises.'], [Kit::word('la chaise', 'chaises'), $jardin, Kit::form('il y a', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.appartement', 'My flat has a living room, a kitchen and a garden.', ['Mon appartement a un salon, une cuisine et un jardin.'], [$appart, $salon, $cuisine, $jardin], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.table', 'The table is near the house.', ['La table est près de la maison.'], [$table, $maison, Kit::form('près')], 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.lits', 'Les lits ___ dans la chambre.', 'The beds are in the bedroom.', 'sont', Kit::form('sont', true), null, 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.table-chaises', '___ une table et deux chaises dans le jardin.', 'There is a table and two chairs in the garden.', 'Il y a', Kit::form('il y a', true), null, 'sentences', $b),
            Kit::listenType($s, 'check.b.listen_type.chaises-table', 'La chaise et la table sont dans le salon.', 'The chair and the table are in the living room.', [$chaise, $table, $salon, Kit::form('sont', true)], 'dictation', $b, homophoneNote: self::ET_SONT_NOTE),
            Kit::listenType($s, 'check.b.listen_type.maison', 'Ma maison a une cuisine et deux chambres.', 'My house has a kitchen and two bedrooms.', [$maison, $cuisine, Kit::word('la chambre', 'chambres')], 'dictation', $b, homophoneNote: self::A_ET_NOTE),
            Kit::listenType($s, 'check.b.listen_type.chambre', 'Mon appartement a une chambre avec un lit.', 'My flat has a bedroom with a bed.', [$appart, $chambre, $lit], 'dictation', $b, homophoneNote: self::A_NOTE),
        ];
    }
}
