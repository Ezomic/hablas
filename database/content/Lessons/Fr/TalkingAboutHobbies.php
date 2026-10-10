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

final class TalkingAboutHobbies implements UnitContent
{
    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins two things.';

    private const MAIS_NOTE = 'Mais (but) and mes (my) sound very close, and the sentence tells you which is which: here it means but.';

    private const OU_NOTE = 'Ou (or, no accent) and où (where, with an accent) sound the same, and the sentence tells you which is which: here it means or.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'talking-about-hobbies';
    }

    public function words(): array
    {
        return [
            new WordData('le sport', cue: 'sport', forms: ['sports']),
            new WordData('la musique', cue: 'music'),
            new WordData('le football', cue: 'football (soccer)', accepted: ['le foot']),
            new WordData('le film', cue: 'film (movie)', forms: ['films']),
            new WordData('le temps libre', cue: 'free time', forms: ['pendant mon temps libre']),
            new WordData('danser', cue: 'to dance'),
            new WordData('chanter', cue: 'to sing'),
            new WordData('jouer', cue: 'to play (a sport or game)', forms: ['je joue', 'joue'], note: 'You play a sport with jouer plus au: jouer au football (à plus le becomes au).'),
            new WordData('lire', cue: 'to read'),
            new WordData('le livre', cue: 'book', forms: ['livres']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => "J'aime la musique.", 'english' => 'I like music.'],
            ['text' => 'Tu aimes jouer au football ?', 'english' => 'Do you like to play football?'],
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
            Kit::gap($stage, 'sentences.choose_gap.musique', "J'aime ___ musique.", ['la', 'le', 'les'], 'la', Kit::form('la', true), "Musique is feminine singular, so the article is la. Unlike Dutch, French keeps the article after aimer: j'aime la musique.", 'choose', 'I like music.'),
            Kit::gap($stage, 'sentences.choose_gap.sports', "J'aime ___ sports.", ['les', 'le', 'la'], 'les', Kit::form('les', true), 'Sports is plural, so the article is les.', 'choose', 'I like sports.'),
            Kit::gap($stage, 'sentences.choose_gap.paul-football', '___ le football, Paul ?', ['tu aimes', "j'aime"], 'tu aimes', Kit::form('tu aimes', true), 'Tu means you, which fits a question to Paul. J\'aime means I like.', 'choose', 'Paul, do you like football?'),
            Kit::gap($stage, 'sentences.choose_gap.joue', 'Je joue ___ football.', ['au', 'à la', 'aux'], 'au', Kit::form('au', true), 'Football is masculine singular, and à plus le becomes au: jouer au football.', 'choose', 'I play football.'),
            Kit::gap($stage, 'sentences.choose_gap.jouer', "J'aime ___ au football.", ['jouer', 'lire', 'chanter'], 'jouer', Kit::word('jouer'), 'You play a sport with jouer. Lire is to read and chanter is to sing.', 'choose', 'I like to play football.'),
            Kit::gap($stage, 'sentences.choose_gap.la-musique', "J'aime la ___.", ['musique', 'sport', 'livre'], 'musique', Kit::word('la musique', 'musique'), 'La goes with musique. Sport and livre take le.', 'choose', 'I like music.'),

            Kit::typeGap($stage, 'sentences.type_gap.livres', "J'aime ___ livres.", 'I like books.', 'les', Kit::form('les'), 'Livres is plural, so the article is les.'),
            Kit::typeGap($stage, 'sentences.type_gap.anne-football', 'Tu ___ le football, Anne ?', 'Do you like football, Anne? (informal you)', 'aimes', Kit::form('aimes'), 'With tu the verb takes an -s: tu aimes.'),
            Kit::typeGap($stage, 'sentences.type_gap.lire', "Pendant mon temps libre, j'aime ___.", 'In my free time I like to read.', 'lire', Kit::word('lire')),
            Kit::typeGap($stage, 'sentences.type_gap.danser', '___ danser.', 'I like to dance.', "J'aime", Kit::form("j'aime"), "Je becomes j' before the vowel of aime, and the verb after aimer stays in the infinitive: j'aime danser."),
            Kit::typeGap($stage, 'sentences.type_gap.chanter', "J'aime chanter et ___.", 'I like to sing and to dance.', 'danser', Kit::word('danser')),

            Kit::translate($stage, 'sentences.translate.sports', 'I like sports.', ["J'aime les sports.", "J'aime le sport."], [Kit::word('le sport', 'sports', ['sport']), Kit::form('les', false, ['le'])]),
            Kit::translate($stage, 'sentences.translate.musique', 'Do you like music? (informal you)', ['Tu aimes la musique ?', 'Aimes-tu la musique ?'], [Kit::word('la musique', 'musique'), Kit::form('tu aimes', false, ['aimes-tu'])]),
            Kit::translate($stage, 'sentences.translate.jouer', 'I like to play football.', ["J'aime jouer au football.", "J'aime jouer au foot."], [Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::form('au')]),

            Kit::build($stage, 'sentences.build.films', 'I like movies.', "J'aime les films.", ['le'], [Kit::word('le film', 'films'), Kit::form('les')]),
            Kit::build($stage, 'sentences.build.livres', 'Do you like books? (informal you)', 'Tu aimes les livres ?', ['aime'], [Kit::word('le livre', 'livres'), Kit::form('tu aimes')]),
            Kit::build($stage, 'sentences.build.lire', 'I like to read.', "J'aime lire.", ['aimes'], [Kit::word('lire'), Kit::form("j'aime")]),

            Kit::listenChoose($stage, 'sentences.listen_choose.musique', "J'aime la musique.", ['I like music.', 'I like sports.', 'You like music.', 'I like football.'], 'I like music.', [Kit::word('la musique', 'musique'), Kit::form("j'aime")]),
            Kit::listenChoose($stage, 'sentences.listen_choose.football', 'Paul aime le football.', ['Paul likes football.', 'Paul plays football.', 'Paul likes music.', 'You like football.'], 'Paul likes football.', [Kit::word('le football', 'football', ['foot']), Kit::form('il aime')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.chanter', "J'aime chanter et danser.", ['I like to sing and dance.', 'I like to read and dance.', 'I like to sing and read.', 'You like to sing and dance.'], 'I like to sing and dance.', [Kit::word('chanter'), Kit::word('danser'), Kit::form("j'aime")]),
            Kit::listenType($stage, 'sentences.listen_type.sports', "J'aime les sports.", 'I like sports.', [Kit::word('le sport', 'sports', ['sport']), Kit::form('les', false, ['le'])], alsoAccepted: ["J'aime le sport."]),
            Kit::listenType($stage, 'sentences.listen_type.temps-libre', "Pendant mon temps libre, j'aime lire.", 'In my free time I like to read.', [Kit::word('le temps libre', 'temps libre'), Kit::word('lire'), Kit::form("j'aime")]),
            Kit::listenType($stage, 'sentences.listen_type.films', 'Tu aimes les films ?', 'Do you like movies? (informal you)', [Kit::word('le film', 'films'), Kit::form('tu aimes')]),
            Kit::listenType($stage, 'sentences.listen_type.football', 'Le football est un sport.', 'Football is a sport.', [Kit::word('le football', 'football', ['foot']), Kit::word('le sport', 'sport')], homophoneNote: self::EST_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.musique', "J'aime la musique.", 'I like music.', [Kit::word('la musique', 'musique'), Kit::form("j'aime")]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.jouer', "J'aime jouer au football.", 'I like to play football.', [Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::form('au')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.livres', "J'aime les livres.", 'I like books.', [Kit::word('le livre', 'livres'), Kit::form('les')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.danser', "Pendant mon temps libre, j'aime danser.", 'In my free time I like to dance.', [Kit::word('le temps libre', 'temps libre'), Kit::word('danser'), Kit::form("j'aime")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.football', 'Tu aimes le football ?', 'Do you like football?', [['oui', 'non'], ["j'aime", 'football']], "Oui, j'aime le football.", [Kit::word('le football', 'football', ['foot']), Kit::form("j'aime")]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.chanter', 'Tu aimes chanter ?', 'Do you like to sing?', [['oui', 'non'], ["j'aime", 'chanter']], "Oui, j'aime chanter.", [Kit::word('chanter')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.musique', 'Tu aimes la musique ?', 'Do you like music?', [['oui', 'non'], ["j'aime", 'musique']], "Oui, j'aime la musique.", [Kit::word('la musique', 'musique')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.temps-libre', 'Read the conversation about free time.', [
                Kit::line('Anne', 'Paul, tu aimes quoi pendant ton temps libre ?'),
                Kit::line('Paul', "J'aime les sports. J'aime jouer au football."),
                Kit::line('Anne', "J'aime la musique. J'aime chanter et danser."),
                Kit::line('Paul', 'Tu aimes lire ?'),
                Kit::line('Anne', "Non, je n'aime pas lire. J'aime les films."),
            ], [
                Kit::question('What does Paul like to play?', ['Football', 'Music', 'Books'], 'Football'),
                Kit::question('What does Anne like to do?', ['Sing and dance', 'Play football', 'Read'], 'Sing and dance'),
                Kit::question('Does Anne like to read?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('le temps libre', 'temps libre'), Kit::word('le sport', 'sports'), Kit::word('le football', 'football', ['foot']), Kit::word('jouer'), Kit::word('la musique', 'musique'), Kit::word('chanter'), Kit::word('danser'), Kit::word('lire'), Kit::word('le livre', 'livres'), Kit::word('le film', 'films')], 'read'),
            Kit::gap($stage, 'task.choose_gap.chanter-danser', 'Tu ___ chanter et danser ?', ['aimes', 'aime'], 'aimes', Kit::form('tu aimes', true), 'With tu the verb takes an -s: tu aimes. Aime goes with je (j\'aime), il and elle.', 'read', 'Do you like to sing and dance?'),
            Kit::gap($stage, 'task.choose_gap.temps-libre', "Pendant mon temps ___, j'aime lire.", ['libre', 'livre'], 'libre', Kit::word('le temps libre', 'libre'), 'Temps libre means free time. Livre is a book.', 'read', 'In my free time I like to read.'),

            Kit::transform($stage, 'task.transform.football', 'Now ask a friend (informal you).', "J'aime le football.", ['Tu aimes le football ?', 'Aimes-tu le football ?'], [Kit::word('le football', 'football', ['foot']), Kit::form('tu aimes', false, ['aimes-tu'])]),
            Kit::transform($stage, 'task.transform.livres', 'Change it to several books.', "J'aime le livre.", ["J'aime les livres."], [Kit::word('le livre', 'livres'), Kit::form('les', true)]),
            Kit::transform($stage, 'task.transform.pas-chanter', 'Say that you do not like it.', "J'aime chanter.", ["Je n'aime pas chanter."], [Kit::word('chanter'), Kit::form("je n'aime pas")]),
            Kit::writeGuided($stage, 'task.write_guided.musique', 'Say that you like music and that you like to sing.', ["j'aime", 'musique', 'chanter'], "J'aime la musique. J'aime chanter.", [
                ['forms' => ["j'aime"], 'term' => null],
                ['forms' => ['musique'], 'term' => 'la musique'],
                ['forms' => ['chanter'], 'term' => 'chanter'],
            ], [Kit::word('la musique', 'musique'), Kit::word('chanter')]),
            Kit::writeGuided($stage, 'task.write_guided.temps-libre', 'Say that in your free time you like to read and to dance.', ['mon temps libre', "j'aime", 'lire', 'danser'], "Pendant mon temps libre, j'aime lire et danser.", [
                ['forms' => ['libre'], 'term' => 'le temps libre'],
                ['forms' => ['lire'], 'term' => 'lire'],
                ['forms' => ['danser'], 'term' => 'danser'],
            ], [Kit::word('le temps libre', 'temps libre'), Kit::word('lire'), Kit::word('danser')]),
            Kit::build($stage, 'task.build.temps-libre-livres', 'In my free time I like books.', "Pendant mon temps libre, j'aime les livres.", ['aimes', 'le'], [Kit::word('le temps libre', 'temps libre'), Kit::word('le livre', 'livres'), Kit::form('les', true)], 'write'),
            Kit::build($stage, 'task.build.jouer', 'Do you like to play football? (informal you)', 'Tu aimes jouer au football ?', ['aime', 'aux'], [Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::form('tu aimes')], 'write'),
            Kit::build($stage, 'task.build.sports-films', 'I like sports and movies.', "J'aime les sports et les films.", ['le', 'aimes'], [Kit::word('le sport', 'sports'), Kit::word('le film', 'films'), Kit::form('les')], 'write'),
            Kit::translate($stage, 'task.translate.chanter-danser', 'I like to sing, but I do not like to dance.', ["J'aime chanter, mais je n'aime pas danser."], [Kit::word('chanter'), Kit::word('danser'), Kit::form("j'aime")], 'write'),
            Kit::translate($stage, 'task.translate.football-musique', 'Do you like football or music? (informal you)', ['Tu aimes le football ou la musique ?', 'Tu aimes la musique ou le football ?', 'Aimes-tu le football ou la musique ?', 'Aimes-tu la musique ou le football ?'], [Kit::word('le football', 'football', ['foot']), Kit::word('la musique', 'musique'), Kit::form('tu aimes', false, ['aimes-tu'])], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.temps-libre', [
                Kit::line('Marie', 'Luc, tu aimes quoi pendant ton temps libre ?'),
                Kit::line('Luc', "J'aime les sports. J'aime jouer au football."),
                Kit::line('Marie', "J'aime lire. J'aime les livres et les films."),
                Kit::line('Luc', 'Tu aimes la musique ?'),
                Kit::line('Marie', "Oui, j'aime chanter et danser."),
            ], [
                Kit::question('What does Luc like to play?', ['Football', 'Music', 'Movies'], 'Football'),
                Kit::question('What does Marie like to read and watch?', ['Books and movies', 'Football', 'Sports'], 'Books and movies'),
                Kit::question('What does Marie say about music?', ['She likes to sing and dance.', 'She does not like it.', 'She likes to read it.'], 'She likes to sing and dance.'),
            ], [
                Kit::question('Who asks about free time?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Does Luc like sports?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le temps libre', 'temps libre'), Kit::word('le sport', 'sports'), Kit::word('le football', 'football', ['foot']), Kit::word('jouer'), Kit::word('lire'), Kit::word('le livre', 'livres'), Kit::word('le film', 'films'), Kit::word('la musique', 'musique'), Kit::word('chanter'), Kit::word('danser')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.musique-danser', "J'aime la musique et j'aime danser.", 'I like music and I like to dance.', [Kit::word('la musique', 'musique'), Kit::word('danser'), Kit::form("j'aime")], 'listen', homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'task.listen_type.jouer-temps-libre', 'Tu aimes jouer au football pendant ton temps libre ?', 'Do you like to play football in your free time? (informal you)', [Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::word('le temps libre', 'temps libre'), Kit::form('tu aimes')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pas-sports', "Je n'aime pas le sport, mais j'aime lire.", 'I do not like sport, but I like to read.', [Kit::word('le sport', 'sport'), Kit::word('lire'), Kit::form("je n'aime pas")], 'listen', homophoneNote: self::MAIS_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.lire', 'Tu aimes lire ?', 'Do you like to read?', [['oui', 'non'], ["j'aime", 'lire']], "Oui, j'aime lire.", [Kit::word('lire'), Kit::form("j'aime")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.temps-libre', 'Tu aimes quoi pendant ton temps libre ?', 'What do you like in your free time?', [["j'aime"], ['lire', 'danser', 'chanter', 'jouer', 'football', 'musique', 'sport', 'sports', 'livre', 'livres', 'film', 'films']], "Pendant mon temps libre, j'aime lire.", [Kit::word('le temps libre', 'temps libre'), Kit::word('lire')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.films', 'Tu aimes les films ?', 'Do you like movies?', [['oui', 'non'], ["j'aime", 'films', 'film']], "Oui, j'aime les films.", [Kit::word('le film', 'films'), Kit::form("j'aime")], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sports', 'Tu aimes les sports ?', 'Do you like sports?', [['oui', 'non'], ["j'aime", 'sport', 'sports', 'football']], "Oui, j'aime les sports.", [Kit::word('le sport', 'sports')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.livres-films', "J'aime les livres et les films.", 'I like books and movies.', [Kit::word('le livre', 'livres'), Kit::word('le film', 'films'), Kit::form('les')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.chanter-danser', "J'aime chanter et danser pendant mon temps libre.", 'I like to sing and dance in my free time.', [Kit::word('chanter'), Kit::word('danser'), Kit::word('le temps libre', 'temps libre')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sports-livres', 'I like sports and books.', ["J'aime les sports et les livres.", "J'aime le sport et les livres."], [Kit::word('le sport', 'sports', ['sport']), Kit::word('le livre', 'livres'), Kit::form('les')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.danser', 'Do you like to dance? (informal you)', ['Tu aimes danser ?', 'Aimes-tu danser ?'], [Kit::word('danser'), Kit::form('tu aimes', false, ['aimes-tu'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.football', 'In my free time I like to play football.', ["Pendant mon temps libre, j'aime jouer au football.", "Pendant mon temps libre, j'aime jouer au foot.", "J'aime jouer au football pendant mon temps libre.", "J'aime jouer au foot pendant mon temps libre."], [Kit::word('le temps libre', 'temps libre'), Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::form("j'aime")], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.films', 'I do not like movies.', ["Je n'aime pas les films."], [Kit::word('le film', 'films'), Kit::form("je n'aime pas", true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.joue', 'Paul joue ___ football.', 'Paul plays football.', 'au', Kit::form('au', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.anne-musique', 'Anne, tu ___ la musique ?', 'Anne, do you like music?', 'aimes', Kit::form('aimes'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.musique', "J'aime la musique pendant mon temps libre.", 'I like music in my free time.', [Kit::word('la musique', 'musique'), Kit::word('le temps libre', 'temps libre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.chanter-danser', 'Tu aimes chanter ou danser ?', 'Do you like to sing or to dance? (informal you)', [Kit::word('chanter'), Kit::word('danser')], 'dictation', $set, homophoneNote: self::OU_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.lire-jouer', "Je n'aime pas lire, mais j'aime jouer.", 'I do not like to read, but I like to play.', [Kit::word('lire'), Kit::word('jouer')], 'dictation', $set, homophoneNote: self::MAIS_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.films', [
                Kit::line('Anne', 'Paul, tu aimes les films ?'),
                Kit::line('Paul', "Oui, j'aime les films. J'aime aussi les livres."),
                Kit::line('Anne', "Je n'aime pas les livres, mais j'aime chanter."),
            ], [
                Kit::question('What does Paul like?', ['Movies and books', 'Football and music', 'Books only'], 'Movies and books'),
                Kit::question('What does Anne not like?', ['Books', 'Movies', 'Singing'], 'Books'),
                Kit::question('What does Anne like to do?', ['Sing', 'Read', 'Dance'], 'Sing'),
            ], [
                Kit::question('Who asks the question?', ['Anne', 'Paul', 'Nobody'], 'Anne'),
                Kit::question('Does Paul like movies?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('le film', 'films'), Kit::word('le livre', 'livres'), Kit::word('chanter')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sport', 'Read the conversation.', [
                Kit::line('Marie', 'Luc, tu aimes le sport ?'),
                Kit::line('Luc', "Oui, j'aime jouer au football."),
                Kit::line('Marie', "Je n'aime pas le football, mais j'aime lire."),
            ], [
                Kit::question('What does Luc like to play?', ['Football', 'Music', 'Nothing'], 'Football'),
                Kit::question('What does Marie like to do?', ['Read', 'Play football', 'Sing'], 'Read'),
            ], [Kit::word('le sport', 'sport'), Kit::word('le football', 'football', ['foot']), Kit::word('jouer'), Kit::word('lire')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.livres-films', 'Tu aimes quoi, les livres ou les films ?', 'What do you like, books or movies?', [["j'aime"], ['livres', 'films', 'livre', 'film']], "J'aime les films.", [Kit::word('le livre', 'livres'), Kit::word('le film', 'films')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sport', 'Quel sport tu aimes ?', 'What sport do you like?', [["j'aime", 'joue'], ['football', 'foot']], "J'aime le football.", [Kit::word('le football', 'football', ['foot']), Kit::word('le sport', 'sport')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.lire-danser', 'Tu aimes quoi, lire ou danser ?', 'What do you like, reading or dancing?', [["j'aime"], ['lire', 'danser']], "J'aime lire.", [Kit::word('lire'), Kit::word('danser')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.musique-chanter-danser', 'I like music, singing and dancing.', ["J'aime la musique, chanter et danser."], [Kit::word('la musique', 'musique'), Kit::word('chanter'), Kit::word('danser'), Kit::form("j'aime")], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luc-football', 'Luc, do you like to play football in your free time? (informal you)', ['Luc, tu aimes jouer au football pendant ton temps libre ?', 'Tu aimes jouer au football pendant ton temps libre, Luc ?', 'Luc, tu aimes jouer au foot pendant ton temps libre ?', 'Tu aimes jouer au foot pendant ton temps libre, Luc ?', 'Luc, aimes-tu jouer au football pendant ton temps libre ?', 'Aimes-tu jouer au football pendant ton temps libre, Luc ?'], [Kit::word('jouer'), Kit::word('le football', 'football', ['foot']), Kit::word('le temps libre', 'temps libre'), Kit::form('tu aimes', false, ['aimes-tu'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sports-livres', 'I like sports, but I do not like books.', ["J'aime les sports, mais je n'aime pas les livres.", "J'aime le sport, mais je n'aime pas les livres."], [Kit::word('le sport', 'sports', ['sport']), Kit::word('le livre', 'livres'), Kit::form("je n'aime pas", true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.lire-films', 'I like to read, but I do not like movies.', ["J'aime lire, mais je n'aime pas les films."], [Kit::word('lire'), Kit::word('le film', 'films'), Kit::form("je n'aime pas", true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.paul-musique', 'Paul, tu aimes la ___ ?', 'Paul, do you like music?', 'musique', Kit::word('la musique', 'musique'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luc-jouer', 'Luc, tu aimes ___ au football ?', 'Luc, do you like to play football?', 'jouer', Kit::word('jouer'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.temps-libre', "Pendant mon temps libre, j'aime chanter et lire.", 'In my free time I like to sing and to read.', [Kit::word('le temps libre', 'temps libre'), Kit::word('chanter'), Kit::word('lire')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sport', "Mon sport, c'est le football et j'aime la musique.", 'My sport is football and I like music.', [Kit::word('le sport', 'sport'), Kit::word('le football', 'football', ['foot']), Kit::word('la musique', 'musique'), Kit::form("j'aime")], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.livres', "J'aime les livres, les films et les sports.", 'I like books, movies and sports.', [Kit::word('le livre', 'livres'), Kit::word('le film', 'films'), Kit::word('le sport', 'sports'), Kit::form('les')], 'dictation', $set, homophoneNote: self::ET_NOTE),
        ];
    }
}
