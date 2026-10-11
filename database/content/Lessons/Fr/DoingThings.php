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

final class DoingThings implements UnitContent
{
    private const A_NOTE = 'À with an accent means to or at. A without an accent is a form of avoir (il a), but here it is à.';

    private const ET_NOTE = 'Et means and. It sounds the same as est (is), but here it is et.';

    private const MAIS_NOTE = 'Mais means but. It sounds the same as mes (my) and mets (I put), but here it is mais.';

    private const OU_NOTE = 'Où with an accent means where. Ou without an accent means or, but here it is où.';

    private const DIS_NOTE = 'Dis (je) and dit (il, elle) sound the same.';

    private const EST_NOTE = 'Est is a form of être (is). It sounds the same as et (and), but here it is est.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'doing-things';
    }

    public function words(): array
    {
        return [
            new WordData('faire', cue: 'to do / to make', forms: ['fais', 'fait', 'faisons']),
            new WordData('voir', cue: 'to see', forms: ['vois', 'voit', 'voyons']),
            new WordData('venir', cue: 'to come', forms: ['viens', 'vient', 'venons']),
            new WordData('savoir', cue: 'to know (a fact)', forms: ['sais', 'sait', 'savons'], note: 'Savoir is to know a fact: Je ne sais pas où est Anne.'),
            new WordData('sortir', cue: 'to leave / to go out', forms: ['sors', 'sort', 'sortons']),
            new WordData('mettre', cue: 'to put', forms: ['mets', 'met', 'mettons']),
            new WordData('dire', cue: 'to say / to tell', forms: ['dis', 'dit', 'disons'], note: 'Dire merci is the set phrase for to thank: Je dis merci à Anne.'),
            new WordData('donner', cue: 'to give', forms: ['donne', 'donnes', 'donnons']),
            new WordData('apporter', cue: 'to bring (a thing)', forms: ["j'apporte", "n'apporte", 'apportes', 'apporte', 'apportons'], note: 'Apporter is to bring a thing.'),
            new WordData('entendre', cue: 'to hear', forms: ["j'entends", "n'entends", 'entends', 'entend', 'entendons']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je sors avec Luc.', 'english' => 'I go out with Luc.'],
            ['text' => 'Je ne sais pas où est Anne.', 'english' => 'I do not know where Anne is.'],
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
        $heures = ['heures' => "o'clock, hours"];

        return [
            Kit::gap($stage, 'sentences.choose_gap.vois', 'Je ___ Anne.', ['vois', 'voit', 'voyons'], 'vois', Kit::form('vois', true), 'Je goes with vois, which is irregular. Voit is for il or elle and voyons is for nous.', 'choose', 'I see Anne.'),
            Kit::gap($stage, 'sentences.choose_gap.viens', 'Je ___ avec Paul.', ['viens', 'vient'], 'viens', Kit::form('viens', true), 'Je goes with viens, which is irregular. Vient is for il or elle.', 'choose', 'I come with Paul.'),
            Kit::gap($stage, 'sentences.choose_gap.sais', 'Tu ___ où est Anne ?', ['sais', 'sait'], 'sais', Kit::form('sais', true), 'Tu goes with sais, the same form as je. Sait is for il or elle.', 'choose', 'Do you know where Anne is?'),
            Kit::gap($stage, 'sentences.choose_gap.sort', 'Anne ___ à sept heures.', ['sort', 'sors'], 'sort', Kit::word('sortir', 'sort'), 'Anne is she, so the verb is sort. Sors is for je or tu.', 'choose', 'Anne leaves at seven.', $heures),
            Kit::gap($stage, 'sentences.choose_gap.donne', 'Je ___ le livre à Anne.', ['donne', 'donnes'], 'donne', Kit::word('donner', 'donne'), 'Je goes with donne, with no s. Donnes is for tu.', 'choose', 'I give the book to Anne.', ['livre' => 'book']),
            Kit::gap($stage, 'sentences.choose_gap.entends', 'Tu ___ bien Anne ?', ['entends', 'entend'], 'entends', Kit::word('entendre', 'entends'), 'Tu goes with entends. Entend is for il or elle.', 'choose', 'Do you hear Anne well?'),

            Kit::typeGap($stage, 'sentences.type_gap.sors', 'Je ___ à huit heures.', 'I leave at eight.', 'sors', Kit::form('sors'), 'Je goes with sors, which is irregular. Sort is for il or elle.', glosses: $heures),
            Kit::typeGap($stage, 'sentences.type_gap.apporte', 'Marie ___ deux ici.', 'Marie brings two here.', 'apporte', Kit::word('apporter', 'apporte'), 'Marie is she, so the verb is apporte. Apportes is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.mets', 'Je ___ deux ici.', 'I put two here.', 'mets', Kit::form('mets'), 'Je goes with mets, which is irregular. Met is for il or elle.'),
            Kit::typeGap($stage, 'sentences.type_gap.dis', 'Je ___ bonjour à Luc.', 'I say hello to Luc.', 'dis', Kit::word('dire', 'dis'), 'Je goes with dis, the irregular form of dire.'),
            Kit::typeGap($stage, 'sentences.type_gap.fait', 'Que ___ Anne ?', 'What does Anne do?', 'fait', Kit::form('fait'), 'Anne is she, so the verb is fait. Fais is for je or tu.'),

            Kit::translate($stage, 'sentences.translate.ne-sais-pas', 'I do not know.', ['Je ne sais pas.'], [Kit::word('savoir', 'sais'), Kit::form('sais')]),
            Kit::translate($stage, 'sentences.translate.vois-anne', 'I see Anne here.', ['Je vois Anne ici.', 'Ici, je vois Anne.'], [Kit::word('voir', 'vois'), Kit::form('vois')]),
            Kit::translate($stage, 'sentences.translate.fais', 'What are you doing here? (informal you)', ['Tu fais quoi ici ?', 'Que fais-tu ici ?', 'Tu fais quoi, ici ?'], [Kit::word('faire', 'fais')]),

            Kit::build($stage, 'sentences.build.viens', 'I come with Anne.', 'Je viens avec Anne.', ['vient'], [Kit::word('venir', 'viens'), Kit::form('viens')]),
            Kit::build($stage, 'sentences.build.sors', 'Do you go out with Anne? (informal you)', 'Tu sors avec Anne ?', ['sort'], [Kit::word('sortir', 'sors')]),
            Kit::build($stage, 'sentences.build.met', 'She puts two here.', 'Elle met deux ici.', ['mets'], [Kit::word('mettre', 'met')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vois', 'Je vois Anne et Paul.', ['I see Anne and Paul.', 'I hear Anne and Paul.', 'You see Anne and Paul.', 'I bring Anne and Paul.'], 'I see Anne and Paul.', [Kit::word('voir', 'vois'), Kit::form('vois')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.sais', 'Tu sais où est Luc ?', ['Do you know where Luc is?', 'Do I know where Luc is?', 'Do you see Luc here?', 'Is Luc here?'], 'Do you know where Luc is?', [Kit::word('savoir', 'sais')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vient', 'Anne vient avec Paul.', ['Anne comes with Paul.', 'Anne leaves with Paul.', 'Anne brings Paul.', 'I come with Paul.'], 'Anne comes with Paul.', [Kit::word('venir', 'vient')]),
            Kit::listenType($stage, 'sentences.listen_type.ne-sais-pas-ou', 'Je ne sais pas où est Marie.', 'I do not know where Marie is.', [Kit::word('savoir', 'sais'), Kit::form('sais')], homophoneNote: self::OU_NOTE.' '.self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.sors', 'Je sors avec Luc et Marie.', 'I go out with Luc and Marie.', [Kit::word('sortir', 'sors'), Kit::form('sors')], homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.fais', 'Je fais deux et tu fais trois.', 'I do two and you do three. (informal you)', [Kit::word('faire', 'fais'), Kit::form('fais')], homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.dis', 'Je dis bonjour et au revoir.', 'I say hello and goodbye.', [Kit::word('dire', 'dis')], homophoneNote: self::ET_NOTE.' '.self::DIS_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.fais', 'Tu fais quoi ici ?', 'What are you doing here? (informal you)', [Kit::word('faire', 'fais')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.apporte', "J'apporte deux ici.", 'I bring two here.', [Kit::word('apporter', "j'apporte")]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mets', 'Je mets un ici et deux là.', 'I put one here and two there.', [Kit::word('mettre', 'mets'), Kit::form('mets')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.donne', 'Je donne deux à Anne.', 'I give two to Anne.', [Kit::word('donner', 'donne')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vois', 'Tu vois Anne ?', 'Do you see Anne? (informal you)', [['oui', 'non'], ['vois']], 'Oui, je vois Anne.', [Kit::word('voir', 'vois'), Kit::form('vois')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.viens', 'Tu viens avec Paul ?', 'Are you coming with Paul? (informal you)', [['oui', 'non'], ['viens']], 'Oui, je viens avec Paul.', [Kit::word('venir', 'viens')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.entends', 'Tu entends bien ?', 'Do you hear well? (informal you)', [["j'entends", "n'entends"], ['bien']], "Oui, j'entends bien.", [Kit::word('entendre', "j'entends")]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.diner', 'Read the conversation.', [
                Kit::line('Anne', 'Paul, tu fais quoi ici ?'),
                Kit::line('Paul', "Je viens avec Luc et j'apporte le dîner."),
                Kit::line('Anne', 'Très bien. Je mets la table. Tu sais où est Marie ?'),
                Kit::line('Paul', 'Je ne sais pas. Ici, je ne vois pas Marie.'),
                Kit::line('Anne', "Moi, j'entends Marie. Elle est là-bas."),
            ], [
                Kit::question('What does Paul bring?', ['The dinner', 'The table', 'Marie'], 'The dinner'),
                Kit::question('What does Anne do for the dinner?', ['She sets the table.', 'She brings the dinner.', 'She sees Marie.'], 'She sets the table.'),
                Kit::question('Where is Marie?', ['There', 'Here', 'The text does not say.'], 'There'),
            ], [Kit::word('faire', 'fais'), Kit::word('venir', 'viens'), Kit::word('apporter', "j'apporte"), Kit::word('mettre', 'mets'), Kit::word('savoir', 'sais'), Kit::word('voir', 'vois'), Kit::word('entendre', "j'entends")], 'read', glosses: ['dîner' => 'dinner', 'table' => 'table', 'moi' => 'me (as for me)', 'bas' => 'over there (là-bas)']),
            Kit::gap($stage, 'task.choose_gap.vois-marie', 'Je ne ___ pas Marie ici.', ['vois', 'voit'], 'vois', Kit::form('vois', true), 'Je goes with vois, which is irregular. Voit is for il or elle.', 'read', 'I do not see Marie here.'),
            Kit::gap($stage, 'task.choose_gap.donne', 'Luc ___ deux à Anne.', ['donne', 'donnes'], 'donne', Kit::word('donner', 'donne'), 'Luc is he, so the verb is donne. Donnes is only for tu.', 'read', 'Luc gives two to Anne.'),

            Kit::transform($stage, 'task.transform.elle-voit', 'Change the subject to she.', 'Je vois Marie.', ['Elle voit Marie.'], [Kit::word('voir', 'voit'), Kit::form('voit')]),
            Kit::transform($stage, 'task.transform.sortons', 'Change the subject to we.', 'Je sors avec Luc.', ['Nous sortons avec Luc.'], [Kit::word('sortir', 'sortons'), Kit::form('sortons')]),
            Kit::transform($stage, 'task.transform.mets', 'Change the subject to I.', 'Anne met deux ici.', ['Je mets deux ici.'], [Kit::word('mettre', 'mets'), Kit::form('mets', true)]),
            Kit::writeGuided($stage, 'task.write_guided.viens-apporte', 'Say that you come with Luc and that you bring two.', ['viens avec', "j'apporte"], "Je viens avec Luc et j'apporte deux.", [
                ['forms' => ['viens', 'venons'], 'term' => 'venir'],
                ['forms' => ["j'apporte", 'apportons'], 'term' => 'apporter'],
            ], [Kit::word('venir', 'viens'), Kit::word('apporter', "j'apporte")]),
            Kit::writeGuided($stage, 'task.write_guided.ne-sais-pas-ne-vois-pas', 'Say that you do not know where Marie is and that you do not see Luc here.', ['ne sais pas', 'ne vois pas', 'où'], 'Je ne sais pas où est Marie et je ne vois pas Luc ici.', [
                ['forms' => ['sais', 'savons'], 'term' => 'savoir'],
                ['forms' => ['vois', 'voyons'], 'term' => 'voir'],
            ], [Kit::word('savoir', 'sais'), Kit::word('voir', 'vois')]),
            Kit::build($stage, 'task.build.dis', 'I say hello to Anne.', 'Je dis bonjour à Anne.', ['dit', 'disons'], [Kit::word('dire', 'dis'), Kit::form('dis')], 'write'),
            Kit::build($stage, 'task.build.venons', 'We come with Luc.', 'Nous venons avec Luc.', ['viens', 'vient'], [Kit::word('venir', 'venons')], 'write'),
            Kit::build($stage, 'task.build.entends', 'Do you hear Marie? (informal you)', 'Tu entends Marie ?', ['entend', 'entendons'], [Kit::word('entendre', 'entends')], 'write'),
            Kit::translate($stage, 'task.translate.vois-entends', 'I see Anne, but I do not hear Marie.', ["Je vois Anne, mais je n'entends pas Marie."], [Kit::word('voir', 'vois'), Kit::word('entendre', "n'entends"), Kit::form('vois')], 'write'),
            Kit::translate($stage, 'task.translate.fais-sors', 'What are you doing and when do you go out? (informal you)', ['Tu fais quoi et quand tu sors ?', 'Tu fais quoi et tu sors quand ?', 'Que fais-tu et quand sors-tu ?'], [Kit::word('faire', 'fais'), Kit::word('sortir', 'sors'), Kit::form('fais', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.sortir', [
                Kit::line('Marie', 'Tu sors avec qui, Luc ?'),
                Kit::line('Luc', 'Je sors avec Anne et Paul. Je dis au revoir à Marie.'),
                Kit::line('Marie', 'Je sors aussi avec Anne. Je donne deux à Paul.'),
            ], [
                Kit::question('Who does Luc go out with?', ['Anne and Paul', 'Marie', 'Nobody'], 'Anne and Paul'),
                Kit::question('Who does Luc say goodbye to?', ['Marie', 'Anne and Paul', 'Nobody'], 'Marie'),
                Kit::question('What does Marie give to Paul?', ['Two', 'One', 'Nothing'], 'Two'),
            ], [
                Kit::question('Who asks who Luc goes out with?', ['Marie', 'Luc', 'Anne'], 'Marie'),
                Kit::question('Does Marie go out with Anne too?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('sortir', 'sors'), Kit::word('dire', 'dis'), Kit::word('donner', 'donne')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.viens-apporte', "Je viens avec Luc et j'apporte deux.", 'I come with Luc and I bring two.', [Kit::word('venir', 'viens'), Kit::word('apporter', "j'apporte"), Kit::form('viens')], 'listen', homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.dit-dis', 'Anne dit bonjour et je dis au revoir.', 'Anne says hello and I say goodbye.', [Kit::word('dire', 'dit'), Kit::form('dis')], 'listen', homophoneNote: self::ET_NOTE.' '.self::DIS_NOTE),
            Kit::listenType($stage, 'task.listen_type.nentends', "Je n'entends pas bien, mais je vois Paul ici.", 'I do not hear well, but I see Paul here.', [Kit::word('entendre', "n'entends"), Kit::word('voir', 'vois')], 'listen', homophoneNote: self::MAIS_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.sors', 'Tu sors avec Luc ?', 'Do you go out with Luc? (informal you)', [['oui', 'non'], ['sors']], 'Oui, je sors avec Luc.', [Kit::word('sortir', 'sors'), Kit::form('sors')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.dis', 'Tu dis bonjour à Paul ?', 'Do you say hello to Paul? (informal you)', [['oui', 'non'], ['dis']], 'Oui, je dis bonjour à Paul.', [Kit::word('dire', 'dis')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.apportes', 'Tu apportes deux ?', 'Are you bringing two? (informal you)', [['oui', 'non'], ["j'apporte", "n'apporte"]], "Oui, j'apporte deux.", [Kit::word('apporter', "j'apporte")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mets', 'Tu mets deux ici ?', 'Do you put two here? (informal you)', [['oui', 'non'], ['mets']], 'Oui, je mets deux ici.', [Kit::word('mettre', 'mets')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.donne', 'Je donne deux à Anne et à Paul.', 'I give two to Anne and Paul.', [Kit::word('donner', 'donne')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ne-sais-pas-faire', 'Je ne sais pas quoi faire ici.', 'I do not know what to do here.', [Kit::word('savoir', 'sais'), Kit::word('faire', 'faire'), Kit::form('sais')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marie-fais', 'Marie, what are you doing here? (informal you)', ['Marie, tu fais quoi ici ?', 'Tu fais quoi ici, Marie ?', 'Marie, que fais-tu ici ?', 'Que fais-tu ici, Marie ?'], [Kit::word('faire', 'fais'), Kit::form('fais', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.viens-apporte', 'I come with Marie and I bring two.', ["Je viens avec Marie et j'apporte deux."], [Kit::word('venir', 'viens'), Kit::word('apporter', "j'apporte"), Kit::form('viens')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.sors-dis', 'I go out with Paul and I say goodbye.', ['Je sors avec Paul et je dis au revoir.'], [Kit::word('sortir', 'sors'), Kit::word('dire', 'dis'), Kit::form('sors', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ne-sais-pas-vois', 'I do not know, but I see Luc here.', ['Je ne sais pas, mais je vois Luc ici.', 'Je ne sais pas, mais ici je vois Luc.'], [Kit::word('savoir', 'sais'), Kit::word('voir', 'vois'), Kit::form('sais')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.dis', 'Anne ___ merci à Marie.', 'Anne says thank you to Marie.', 'dit', Kit::form('dit', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.mets', 'Tu ___ un là ?', 'Do you put one there? (informal you)', 'mets', Kit::form('mets'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.nentends', "Je n'entends pas bien Anne.", 'I do not hear Anne well.', [Kit::word('entendre', "n'entends")], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.dis-mets', 'Je dis bonjour à Luc et je mets deux ici.', 'I say hello to Luc and I put two here.', [Kit::word('dire', 'dis'), Kit::word('mettre', 'mets')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::ET_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.donne', 'Marie donne deux à Paul et à Luc.', 'Marie gives two to Paul and Luc.', [Kit::word('donner', 'donne')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::ET_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.apportes', [
                Kit::line('Anne', 'Tu apportes quoi, Luc ?'),
                Kit::line('Luc', "J'apporte deux. Où je mets les deux ?"),
                Kit::line('Anne', 'Ici, merci.'),
            ], [
                Kit::question('What does Luc bring?', ['Two', 'Three', 'Nothing'], 'Two'),
                Kit::question('Where does Anne say to put them?', ['There', 'Here', 'The conversation does not say.'], 'Here'),
                Kit::question('Who asks the first question?', ['Anne', 'Luc', 'Nobody'], 'Anne'),
            ], [
                Kit::question('Who brings something?', ['Luc', 'Anne', 'Nobody'], 'Luc'),
                Kit::question('Does Luc know where to put them?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('apporter', "j'apporte"), Kit::word('mettre', 'mets')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sais', 'Read the conversation.', [
                Kit::line('Marie', 'Paul, tu sais où est Anne ?'),
                Kit::line('Paul', "Non. Ici, je ne vois pas Anne, mais j'entends Luc."),
                Kit::line('Marie', "J'entends Luc aussi. Il vient de là."),
            ], [
                Kit::question('What does Marie ask Paul?', ['Where Anne is', 'Where Luc is', 'Who is coming'], 'Where Anne is'),
                Kit::question('Does Paul see Anne?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('savoir', 'sais'), Kit::word('voir', 'vois'), Kit::word('entendre', "j'entends"), Kit::word('venir', 'vient')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fais', 'Tu fais quoi ?', 'What do you do? (informal you)', [['vois', 'viens', 'sors', 'mets', "j'apporte", 'dis', 'donne', 'fais', "j'entends", 'sais'], ['ici', 'là', 'avec', 'à', 'un', 'deux', 'trois', 'bonjour']], 'Je vois Anne ici.', [Kit::word('faire', 'fais')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.donnes', 'Tu donnes deux à Anne ?', 'Do you give two to Anne? (informal you)', [['oui', 'non'], ['donne']], 'Oui, je donne deux à Anne.', [Kit::word('donner', 'donnes')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sors', 'Tu sors avec Luc ou avec Paul ?', 'Do you go out with Luc or with Paul? (informal you)', [['sors'], ['luc', 'paul']], 'Je sors avec Luc.', [Kit::word('sortir', 'sors')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.viens-apporte-dis', 'I come with Luc, I bring two and I say hello.', ["Je viens avec Luc, j'apporte deux et je dis bonjour."], [Kit::word('venir', 'viens'), Kit::word('apporter', "j'apporte"), Kit::word('dire', 'dis'), Kit::form('viens')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sors-donne', 'I go out with Luc and I give two to Anne.', ['Je sors avec Luc et je donne deux à Anne.'], [Kit::word('sortir', 'sors'), Kit::word('donner', 'donne'), Kit::form('sors', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ne-sais-pas-quoi-faire', 'I do not know what to do. I do not see Anne.', ['Je ne sais pas quoi faire. Je ne vois pas Anne.'], [Kit::word('savoir', 'sais'), Kit::word('faire', 'faire'), Kit::word('voir', 'vois'), Kit::form('sais', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.mets-nentends', 'I put two here and I do not hear Anne.', ["Je mets deux ici et je n'entends pas Anne."], [Kit::word('mettre', 'mets'), Kit::word('entendre', "n'entends"), Kit::form('mets', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.donne', 'Anne ___ deux à Paul.', 'Anne gives two to Paul.', 'donne', Kit::word('donner', 'donne'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.met', 'Anne ___ deux là.', 'Anne puts two there.', 'met', Kit::word('mettre', 'met'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vois-entends-dis', "Je vois Anne, j'entends Paul et je dis bonjour.", 'I see Anne, I hear Paul and I say hello.', [Kit::word('voir', 'vois'), Kit::word('entendre', "j'entends"), Kit::word('dire', 'dis'), Kit::form('vois')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sait-sort', 'Anne sait quoi faire et sort avec Luc.', 'Anne knows what to do and goes out with Luc.', [Kit::word('savoir', 'sait'), Kit::word('faire', 'faire'), Kit::word('sortir', 'sort'), Kit::form('sort')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.vient-apporte', 'Marie vient avec Luc et apporte deux.', 'Marie comes with Luc and brings two.', [Kit::word('venir', 'vient'), Kit::word('apporter', 'apporte')], 'dictation', $set, homophoneNote: self::ET_NOTE),
        ];
    }
}
