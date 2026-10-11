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

final class YourPersonalDetails implements UnitContent
{
    private const NUMBERS = ['un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf', 'vingt'];

    private const EST_NOTE = 'Est (is) and et (and) sound the same: here est is the verb être. In néerlandaise you hear a final z sound that néerlandais does not have.';

    private const ET_NOTE = 'Et (and) and est (is) sound the same: here et joins two things.';

    private const ET_EST_NOTE = 'Et (and) and est (is) sound the same: in the first sentence et joins two things, in the second est is the verb être.';

    private const A_NOTE = 'A (has) and à (to, at) sound the same: here a is the verb avoir, as in Paul a quinze ans.';

    private const EPELEZ_NOTE = 'Épelez (command), épeler (to spell) and épelé sound the same. Here it is a command, so -ez.';

    private const EST_EPELEZ_NOTE = 'Est (is) and et (and) sound the same: here est is the verb être. Also, épelez (command), épeler (to spell) and épelé sound the same: here it is a command, so -ez.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'your-personal-details';
    }

    public function words(): array
    {
        return [
            new WordData('un an', cue: 'year (âge means age)', forms: ['ans', 'âge'], note: "You give your age in ans (years): j'ai vingt ans. To ask for it you use âge (age): tu as quel âge ?"),
            new WordData('le pays', cue: 'country'),
            new WordData('les Pays-Bas', cue: 'the Netherlands (Holland)', note: 'Always plural, so it takes les: les Pays-Bas.'),
            new WordData('la France', cue: 'France'),
            new WordData('néerlandais', cue: 'Dutch (nationality)', forms: ['néerlandaise'], note: 'Néerlandais is for a man, néerlandaise for a woman. In néerlandaise you hear a final z sound that néerlandais does not have. No capital letter and no article: je suis néerlandais.'),
            new WordData('le téléphone', cue: 'telephone, phone'),
            new WordData('le numéro', cue: 'number'),
            new WordData("l'adresse", cue: 'address (feminine: une adresse)', forms: ['adresse', 'adresses'], note: 'Before a vowel, a feminine noun takes mon/ton: mon adresse, ton adresse (not ma/ta).'),
            new WordData('le nom de famille', cue: 'surname, last name'),
            new WordData('épeler', cue: 'to spell', forms: ['épelle', 'épèle', 'épelez'], note: 'Épelle (or épèle) is the informal command, épelez the polite one: épelez votre nom de famille.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => "J'ai vingt ans.", 'english' => 'I am twenty years old.'],
            ['text' => 'Je suis de France.', 'english' => 'I am from France.'],
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
            Kit::gap($stage, 'sentences.choose_gap.ans', "J'ai douze ___.", ['ans', 'pays'], 'ans', Kit::word('un an', 'ans'), "You count your age in ans (years): j'ai douze ans. Pays means country.", 'choose', 'I am twelve years old.'),
            Kit::gap($stage, 'sentences.choose_gap.jai', '___ dix ans.', ["J'ai", 'Je suis'], "J'ai", Kit::form("j'ai", true), "In French you have your age: j'ai dix ans. Je suis dix ans would be like saying I am ten years, which French does not say.", 'choose', 'I am ten years old.'),
            Kit::gap($stage, 'sentences.choose_gap.tu-as', 'Anne, tu ___ quel âge ?', ['as', 'ai'], 'as', Kit::form('as', true), 'You are asking Anne, so you use as (you have). Ai is for talking about yourself.', 'choose', 'Anne, how old are you? (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.suis-de', 'Je ___ de France.', ['suis', 'ai'], 'suis', Kit::form('suis', true), "To say where you are from, use être with de: je suis de France. Ai (from avoir) is for age, so j'ai de France is wrong.", 'choose', 'I am from France.'),
            Kit::gap($stage, 'sentences.choose_gap.france', 'Paul est de ___.', ['France', 'adresse', 'ans'], 'France', Kit::word('la France', 'France'), 'France is a country, so it fits after est de. Adresse is address and ans is years.', 'choose', 'Paul is from France.'),
            Kit::gap($stage, 'sentences.choose_gap.neerlandaise', 'Anne est ___.', ['néerlandaise', 'néerlandais'], 'néerlandaise', Kit::word('néerlandais', 'néerlandaise'), 'Anne is a woman, so the nationality ends in -e: néerlandaise. Néerlandais is for a man.', 'choose', 'Anne is Dutch.'),

            Kit::typeGap($stage, 'sentences.type_gap.ans', "J'ai quinze ___.", 'I am fifteen years old.', 'ans', Kit::word('un an', 'ans')),
            Kit::typeGap($stage, 'sentences.type_gap.paul-as', 'Paul, tu ___ quel âge ?', 'Paul, how old are you? (informal you)', 'as', Kit::form('as'), 'You are talking to Paul, so you use as. Age takes avoir in French.'),
            Kit::typeGap($stage, 'sentences.type_gap.luc-es', 'Luc, tu ___ de France ?', 'Luc, are you from France? (informal you)', 'es', Kit::form('es'), 'Es is the tu form of être, the verb you use for where you are from.'),
            Kit::typeGap($stage, 'sentences.type_gap.suis', 'Je ___ néerlandais.', 'I am Dutch.', 'suis', Kit::form('suis'), "Nationality takes être: je suis néerlandais. J'ai would mean I have."),
            Kit::typeGap($stage, 'sentences.type_gap.numero', "Votre ___ de téléphone, s'il vous plaît.", 'Your phone number, please. (polite or plural you)', 'numéro', Kit::word('le numéro', 'numéro')),

            Kit::translate($stage, 'sentences.translate.vingt', 'I am twenty years old.', ["J'ai vingt ans."], [Kit::word('un an', 'ans'), Kit::form("j'ai")]),
            Kit::translate($stage, 'sentences.translate.quel-age', 'How old are you? (informal you)', ['Tu as quel âge ?', 'Quel âge as-tu ?', 'Quel âge tu as ?'], [Kit::word('un an', 'âge'), Kit::form('as', false, ['as-tu'])]),
            Kit::translate($stage, 'sentences.translate.nom', 'Spell your surname. (polite or plural you)', ['Épelez votre nom de famille.'], [Kit::word('épeler', 'épelez'), Kit::word('le nom de famille', 'nom de famille')]),

            Kit::build($stage, 'sentences.build.suis-de', 'I am from France.', 'Je suis de France.', ['ai'], [Kit::word('la France', 'France'), Kit::form('suis')]),
            Kit::build($stage, 'sentences.build.quel-pays', 'What is your country? (informal you)', 'Quel est ton pays ?', ['es'], [Kit::word('le pays', 'pays'), Kit::form('est')]),
            Kit::build($stage, 'sentences.build.adresse', 'What is your address? (informal you)', 'Quelle est ton adresse ?', ['es'], [Kit::word("l'adresse", 'adresse')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vingt', "J'ai vingt ans.", ['I am twenty years old.', 'I am twelve years old.', 'You are twenty years old.', 'I am from France.'], 'I am twenty years old.', [Kit::word('un an', 'ans'), Kit::form("j'ai")]),
            Kit::listenChoose($stage, 'sentences.listen_choose.neerlandais', 'Tu es néerlandais ?', ['Are you Dutch?', 'Is Anne Dutch?', 'Are you from France?', 'How old are you?'], 'Are you Dutch?', [Kit::word('néerlandais'), Kit::form('es')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.telephone', "Mon numéro, c'est le douze, quinze.", ['My phone number is twelve, fifteen.', 'My phone number is twelve, five.', 'My phone number is two, fifteen.', 'My address is number twelve.'], 'My phone number is twelve, fifteen.', [Kit::word('le numéro', 'numéro')]),
            Kit::listenType($stage, 'sentences.listen_type.numero', "Mon numéro de téléphone, c'est le douze.", 'My phone number is twelve.', [Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone')]),
            Kit::listenType($stage, 'sentences.listen_type.neerlandaise', 'Anne est néerlandaise.', 'Anne is Dutch.', [Kit::word('néerlandais', 'néerlandaise'), Kit::form('est')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.nom', 'Épelez votre nom de famille.', 'Spell your surname. (polite or plural you)', [Kit::word('épeler', 'épelez'), Kit::word('le nom de famille', 'nom de famille')], homophoneNote: self::EPELEZ_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.paul-a', 'Paul a quinze ans.', 'Paul is fifteen years old.', [Kit::word('un an', 'ans'), Kit::form('a')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.jai', "J'ai vingt ans.", 'I am twenty years old.', [Kit::word('un an', 'ans'), Kit::form("j'ai")]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pays-bas', "Les Pays-Bas, c'est mon pays.", 'The Netherlands is my country.', [Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::word('le pays', 'pays')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.adresse', 'Mon adresse et mon téléphone.', 'My address and my phone number.', [Kit::word("l'adresse", 'adresse'), Kit::word('le téléphone', 'téléphone')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.france', "Mon pays, c'est la France.", 'My country is France.', [Kit::word('le pays', 'pays'), Kit::word('la France', 'France')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ans', 'Tu as quel âge ?', 'How old are you?', [["j'ai", 'ans'], self::NUMBERS], "J'ai vingt ans.", [Kit::word('un an', 'ans')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.france', 'Tu es de France ?', 'Are you from France?', [['oui', 'non'], ['suis', 'france', 'néerlandais', 'néerlandaise']], 'Non, je suis néerlandais.', [Kit::word('néerlandais'), Kit::form('suis')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pays', 'Tu es de quel pays ?', 'Which country are you from?', [['mon', "c'est"], ['pays-bas', 'france']], "Mon pays, c'est les Pays-Bas.", [Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.donnees', 'Read the conversation.', [
                Kit::line('Marie', 'Bonjour, Luc. Tu es de quel pays ?'),
                Kit::line('Luc', "Mon pays, c'est les Pays-Bas. Je suis néerlandais."),
                Kit::line('Marie', 'Je suis de France. Quel âge as-tu ?'),
                Kit::line('Luc', "J'ai vingt ans. Et toi ?"),
                Kit::line('Marie', "J'ai dix-huit ans. Quelle est ton adresse ?"),
                Kit::line('Luc', "Mon adresse, c'est le numéro douze. Et ton numéro ?"),
                Kit::line('Marie', "Mon numéro de téléphone, c'est le sept, quinze."),
            ], [
                Kit::question('Where is Luc from?', ['The Netherlands', 'France', 'The text does not say.'], 'The Netherlands'),
                Kit::question('How old is Marie?', ['Eighteen', 'Twenty', 'Twelve'], 'Eighteen'),
                Kit::question('What is Marie\'s phone number?', ['Seven, fifteen', 'Seven, five', 'Twelve'], 'Seven, fifteen'),
            ], [Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::word('néerlandais'), Kit::word('la France', 'France'), Kit::word('un an', 'ans'), Kit::word("l'adresse", 'adresse'), Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone')], 'read', null, ['toi' => 'you (informal, after et)']),
            Kit::gap($stage, 'task.choose_gap.marie-a', 'Marie ___ quinze ans.', ['a', 'est'], 'a', Kit::form('a', true), 'Age takes avoir, so Marie a quinze ans. Est quinze ans would be like saying she is fifteen years, which French does not say.', 'read', 'Marie is fifteen years old.'),
            Kit::gap($stage, 'task.choose_gap.adresse', "Mon ___, c'est le numéro douze.", ['adresse', 'pays', 'nom de famille'], 'adresse', Kit::word("l'adresse", 'adresse'), 'An address has a number, so adresse fits. Pays is country and nom de famille is surname.', 'read', 'My address is number twelve.'),

            Kit::transform($stage, 'task.transform.question', 'Now ask a friend how old they are (informal you).', "J'ai vingt ans.", ['Tu as quel âge ?', 'Quel âge as-tu ?', 'Quel âge tu as ?'], [Kit::word('un an', 'âge'), Kit::form('as', false, ['as-tu'])]),
            Kit::transform($stage, 'task.transform.anne', 'Now say it about Anne.', 'Je suis de France.', ['Anne est de France.', 'Elle est de France.'], [Kit::word('la France', 'France'), Kit::form('est', true)]),
            Kit::transform($stage, 'task.transform.paul', 'Now say it about Paul.', "J'ai quinze ans.", ['Paul a quinze ans.', 'Il a quinze ans.'], [Kit::word('un an', 'ans'), Kit::form('a', true)]),
            Kit::writeGuided($stage, 'task.write_guided.age', 'Say that you are twenty years old and that you are from France.', ["j'ai", 'ans', 'suis de', 'France'], "J'ai vingt ans. Je suis de France.", [
                ['forms' => ["j'ai"], 'term' => null],
                ['forms' => ['ans'], 'term' => 'un an'],
                ['forms' => ['france'], 'term' => 'la France'],
            ], [Kit::word('un an', 'ans'), Kit::word('la France', 'France'), Kit::form("j'ai")]),
            Kit::writeGuided($stage, 'task.write_guided.nom', 'Ask Paul to spell his surname (informal you).', ['épelle', 'ton nom de famille'], 'Paul, épelle ton nom de famille.', [
                ['forms' => ['épelle', 'épèle'], 'term' => 'épeler'],
                ['forms' => ['famille'], 'term' => 'le nom de famille'],
            ], [Kit::word('épeler', 'épelle', ['épèle']), Kit::word('le nom de famille', 'nom de famille')]),
            Kit::build($stage, 'task.build.telephone', 'What is your phone number? (informal you)', 'Quel est ton numéro de téléphone ?', ['es', 'ai'], [Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone')], 'write'),
            Kit::build($stage, 'task.build.anne-france', 'Anne is from France.', 'Anne est de France.', ['a', 'ans'], [Kit::word('la France', 'France'), Kit::form('est')], 'write'),
            Kit::build($stage, 'task.build.paul', 'Paul is fifteen years old.', 'Paul a quinze ans.', ['est', 'as'], [Kit::word('un an', 'ans'), Kit::form('a')], 'write'),
            Kit::translate($stage, 'task.translate.pays-bas-france', 'The Netherlands is my country, but Marie is from France.', ["Les Pays-Bas, c'est mon pays, mais Marie est de France.", "Mon pays, c'est les Pays-Bas, mais Marie est de France."], [Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::word('le pays', 'pays'), Kit::word('la France', 'France'), Kit::form('est')], 'write'),
            Kit::translate($stage, 'task.translate.telephone', 'My phone number is eight, nine.', ["Mon numéro de téléphone, c'est le huit, neuf.", "Mon téléphone, c'est le huit, neuf."], [Kit::word('le téléphone', 'téléphone')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.donnees', [
                Kit::line('Paul', 'Bonjour, Anne. Tu es de quel pays ?'),
                Kit::line('Anne', "Je suis néerlandaise. Mon pays, c'est les Pays-Bas."),
                Kit::line('Paul', 'Je suis de France. Tu as quel âge ?'),
                Kit::line('Anne', "J'ai quinze ans."),
                Kit::line('Paul', 'Quel est ton numéro de téléphone ?'),
                Kit::line('Anne', "Mon numéro de téléphone, c'est le six, quatorze."),
            ], [
                Kit::question('Where is Anne from?', ['The Netherlands', 'France', 'The conversation does not say.'], 'The Netherlands'),
                Kit::question('How old is Anne?', ['Fifteen', 'Twelve', 'Twenty'], 'Fifteen'),
                Kit::question('What does Paul ask for last?', ['Anne\'s phone number', 'Anne\'s address', 'Anne\'s surname'], 'Anne\'s phone number'),
            ], [
                Kit::question('Where is Paul from?', ['France', 'The Netherlands', 'The conversation does not say.'], 'France'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Is Anne Dutch?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::word('néerlandais', 'néerlandaise'), Kit::word('la France', 'France'), Kit::word('un an', 'ans'), Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marie', 'Marie a dix-sept ans.', 'Marie is seventeen years old.', [Kit::word('un an', 'ans'), Kit::form('a')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.nom', "Quel est votre nom de famille ? Épelez, s'il vous plaît.", 'What is your surname? Spell it, please. (polite or plural you)', [Kit::word('le nom de famille', 'nom de famille'), Kit::word('épeler', 'épelez')], 'listen', homophoneNote: self::EST_EPELEZ_NOTE),
            Kit::listenType($stage, 'task.listen_type.pays-adresse', "Mon pays, c'est la France et mon adresse, c'est le numéro huit.", 'My country is France and my address is number eight.', [Kit::word('le pays', 'pays'), Kit::word('la France', 'France'), Kit::word("l'adresse", 'adresse'), Kit::word('le numéro', 'numéro')], 'listen', homophoneNote: self::ET_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.telephone', 'Quel est ton numéro de téléphone ?', 'What is your phone number?', [['mon', "c'est"], self::NUMBERS], "Mon numéro de téléphone, c'est le cinq, sept.", [Kit::word('le téléphone', 'téléphone')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.adresse', 'Quelle est ton adresse ?', 'What is your address?', [['mon', "c'est"], ['numéro', ...self::NUMBERS]], "Mon adresse, c'est le numéro huit.", [Kit::word("l'adresse", 'adresse'), Kit::word('le numéro', 'numéro')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.neerlandais', 'Tu es néerlandais ?', 'Are you Dutch?', [['oui', 'non'], ['suis', 'néerlandais', 'néerlandaise']], 'Oui, je suis néerlandais.', [Kit::word('néerlandais')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.france', 'Anne, tu es de France ?', 'Anne, are you from France?', [['oui', 'non'], ['france', 'pays-bas', 'suis', 'de']], "Non, mon pays, c'est les Pays-Bas.", [Kit::word('la France', 'France'), Kit::word('les Pays-Bas', 'Pays-Bas')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.nom', 'Paul, épelle ton nom de famille.', 'Paul, spell your surname.', [Kit::word('épeler', 'épelle'), Kit::word('le nom de famille', 'nom de famille')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ans-france', "J'ai vingt ans et je suis de France.", 'I am twenty years old and I am from France.', [Kit::word('un an', 'ans'), Kit::word('la France', 'France')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.dix-neuf', 'I am nineteen years old.', ["J'ai dix-neuf ans."], [Kit::word('un an', 'ans'), Kit::form("j'ai", true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marie-as', 'Marie, how old are you? (informal you)', ['Marie, tu as quel âge ?', 'Tu as quel âge, Marie ?', 'Marie, quel âge as-tu ?', 'Quel âge as-tu, Marie ?', 'Marie, quel âge tu as ?', 'Quel âge tu as, Marie ?'], [Kit::word('un an', 'âge'), Kit::form('as', false, ['as-tu'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.paul-france', 'Paul is from France, but I am Dutch.', ['Paul est de France, mais je suis néerlandais.', 'Paul est de France, mais je suis néerlandaise.'], [Kit::word('la France', 'France'), Kit::word('néerlandais', null, ['néerlandaise']), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luc-nom', 'Luc, spell your surname. (informal you)', ['Luc, épelle ton nom de famille.', 'Luc, épèle ton nom de famille.', 'Épelle ton nom de famille, Luc.', 'Épèle ton nom de famille, Luc.'], [Kit::word('épeler', 'épelle', ['épèle']), Kit::word('le nom de famille', 'nom de famille')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.adresse', "Mon ___, c'est le numéro neuf.", 'My address is number nine.', 'adresse', Kit::word("l'adresse", 'adresse'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luc-a', 'Luc ___ dix-sept ans.', 'Luc is seventeen years old.', 'a', Kit::form('a', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.neerlandais', "Je suis néerlandais et j'ai vingt ans.", 'I am Dutch and I am twenty years old.', [Kit::word('néerlandais'), Kit::word('un an', 'ans'), Kit::form("j'ai")], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.pays', "Mon pays, c'est la France, pas les Pays-Bas.", 'My country is France, not the Netherlands.', [Kit::word('le pays', 'pays'), Kit::word('la France', 'France'), Kit::word('les Pays-Bas', 'Pays-Bas')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.telephone', "Oui, je suis de France. Mon numéro de téléphone, c'est le six.", 'Yes, I am from France. My phone number is six.', [Kit::word('la France', 'France'), Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone'), Kit::form('suis')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.neerlandaise', [
                Kit::line('Luc', 'Bonjour, Marie. Tu es néerlandaise ?'),
                Kit::line('Marie', 'Non, je suis de France.'),
                Kit::line('Luc', 'Je suis néerlandais, Marie. Quel est ton numéro ?'),
                Kit::line('Marie', "Mon numéro de téléphone, c'est le neuf, treize."),
            ], [
                Kit::question('Is Marie Dutch?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Where is Marie from?', ['France', 'The Netherlands', 'The conversation does not say.'], 'France'),
                Kit::question('What does Luc ask for?', ['A phone number', 'An address', 'A surname'], 'A phone number'),
            ], [
                Kit::question('Who is Dutch?', ['Luc', 'Marie', 'Both'], 'Luc'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Does Marie say her phone number?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('néerlandais', 'néerlandaise'), Kit::word('le téléphone', 'téléphone'), Kit::word('le numéro', 'numéro'), Kit::word('la France', 'France')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.adresse', 'Read the conversation.', [
                Kit::line('Paul', 'Bonjour, Anne. Anne, quelle est ton adresse ?'),
                Kit::line('Anne', "Mon adresse, c'est le numéro huit."),
                Kit::line('Paul', 'Anne, épelle ton nom de famille.'),
                Kit::line('Anne', 'Oui, Paul.'),
            ], [
                Kit::question('What does Paul ask first?', ['Her address', 'Her age', 'Her phone number'], 'Her address'),
                Kit::question('What number is Anne\'s address?', ['Eight', 'Six', 'Twelve'], 'Eight'),
            ], [Kit::word("l'adresse", 'adresse'), Kit::word('le numéro', 'numéro'), Kit::word('épeler', 'épelle'), Kit::word('le nom de famille', 'nom de famille')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.anne-ans', 'Tu as quel âge, Anne ?', 'How old are you, Anne?', [["j'ai", 'ans'], self::NUMBERS], "J'ai dix-huit ans.", [Kit::word('un an', 'ans')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.paul-france', 'Paul, tu es de France ?', 'Paul, are you from France?', [['oui', 'non'], ['suis', 'de', 'france', 'néerlandais']], 'Oui, je suis de France.', [Kit::word('la France', 'France'), Kit::form('suis')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.y-pays', 'Paul, tu es de quel pays ?', 'Paul, which country are you from?', [['mon', "c'est"], ['pays-bas', 'france']], "Mon pays, c'est les Pays-Bas.", [Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.onze', 'I am eleven years old and I am from France.', ["J'ai onze ans et je suis de France.", "Je suis de France et j'ai onze ans."], [Kit::word('un an', 'ans'), Kit::word('la France', 'France'), Kit::form("j'ai", true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.adresse-telephone', 'My address is number six. My phone number is five.', ["Mon adresse, c'est le numéro six. Mon numéro de téléphone, c'est le cinq.", "Mon adresse, c'est le numéro six. Mon téléphone, c'est le cinq."], [Kit::word("l'adresse", 'adresse'), Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.anne-neerlandaise', 'Anne is Dutch and my country is the Netherlands.', ["Anne est néerlandaise et mon pays, c'est les Pays-Bas."], [Kit::word('néerlandais', 'néerlandaise'), Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marie-nom', 'Marie, spell your surname. (informal you)', ['Marie, épelle ton nom de famille.', 'Marie, épèle ton nom de famille.', 'Épelle ton nom de famille, Marie.', 'Épèle ton nom de famille, Marie.'], [Kit::word('épeler', 'épelle', ['épèle']), Kit::word('le nom de famille', 'nom de famille')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luc-as', 'Luc, tu ___ quel âge ?', 'Luc, how old are you? (informal you)', 'as', Kit::form('as', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marie-est', 'Marie ___ de France.', 'Marie is from France.', 'est', Kit::form('est'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.neerlandais', "Je suis néerlandais et mon pays, c'est les Pays-Bas. Luc est de France.", 'I am Dutch and my country is the Netherlands. Luc is from France.', [Kit::word('néerlandais'), Kit::word('le pays', 'pays'), Kit::word('les Pays-Bas', 'Pays-Bas'), Kit::word('la France', 'France'), Kit::form('est')], 'dictation', $set, homophoneNote: self::ET_EST_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.nom', 'Luc, épelle ton nom de famille et ton adresse.', 'Luc, spell your surname and your address. (informal you)', [Kit::word('épeler', 'épelle'), Kit::word('le nom de famille', 'nom de famille'), Kit::word("l'adresse", 'adresse')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.douze', "J'ai douze ans. Mon numéro de téléphone, c'est le cinq.", 'I am twelve years old. My phone number is five.', [Kit::word('un an', 'ans'), Kit::word('le numéro', 'numéro'), Kit::word('le téléphone', 'téléphone'), Kit::form("j'ai")], 'dictation', $set),
        ];
    }
}
