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

final class TalkingAboutHobbies implements UnitContent
{
    private const A = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';

    private const E = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

    private const IS = 'È (is) sounds close to e (and); the accent is the written difference. Here it is è, meaning is.';

    private const O = 'O (or) sounds the same as ho (I have), which is written with an h. Here it is plain o, meaning or.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'talking-about-hobbies';
    }

    public function words(): array
    {
        return [
            new WordData('lo sport', cue: 'sport', forms: ['gli sport']),
            new WordData('la musica', cue: 'music'),
            new WordData('il calcio', cue: 'football (soccer)'),
            new WordData('il film', cue: 'film (movie)', forms: ['i film']),
            new WordData('il tempo libero', cue: 'free time', forms: ['nel tempo libero'], note: 'In my free time is nel tempo libero (nel = in + il).'),
            new WordData('ballare', cue: 'to dance'),
            new WordData('cantare', cue: 'to sing'),
            new WordData('giocare', cue: 'to play (a sport or game)', note: 'You play a sport or a game with giocare: giocare a calcio. For an instrument or music you use suonare.'),
            new WordData('leggere', cue: 'to read'),
            new WordData('il libro', cue: 'book', forms: ['i libri']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mi piace ballare.', 'english' => 'I like to dance.'],
            ['text' => 'Mi piacciono gli sport.', 'english' => 'I like sports.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.musica', 'Mi ___ la musica.', ['piace', 'piacciono'], 'piace', Kit::form('piace', true), 'La musica is one thing, so the verb is piace. The thing you like is the subject.', 'choose', 'I like music.'),
            Kit::gap($stage, 'sentences.choose_gap.sport', 'Mi ___ gli sport.', ['piacciono', 'piace'], 'piacciono', Kit::form('piacciono', true), 'Gli sport is plural, so the verb is piacciono. The thing you like is the subject.', 'choose', 'I like sports.'),
            Kit::gap($stage, 'sentences.choose_gap.paolo-calcio', 'Paolo, ___ il calcio?', ['ti piace', 'mi piace'], 'ti piace', Kit::form('ti piace', true), 'Ti means you, which fits a question to Paolo. Mi means I.', 'choose', 'Paolo, do you like football?'),
            Kit::gap($stage, 'sentences.choose_gap.film', '___ i film.', ['Mi piacciono', 'Mi piace'], 'Mi piacciono', Kit::form('mi piacciono'), 'I film is plural, so you need piacciono.', 'choose', 'I like movies.'),
            Kit::gap($stage, 'sentences.choose_gap.giocare', 'Mi piace ___ a calcio.', ['giocare', 'leggere', 'cantare'], 'giocare', Kit::word('giocare'), 'You play a sport with giocare. Leggere is to read and cantare is to sing.', 'choose', 'I like to play football.'),
            Kit::gap($stage, 'sentences.choose_gap.la-musica', 'Mi piace la ___.', ['musica', 'sport', 'libro'], 'musica', Kit::word('la musica', 'musica'), 'La goes with musica. Sport takes lo and libro takes il.', 'choose', 'I like music.'),

            Kit::typeGap($stage, 'sentences.type_gap.libri', 'Mi ___ i libri.', 'I like books.', 'piacciono', Kit::form('piacciono'), 'I libri is plural, so the verb is piacciono.'),
            Kit::typeGap($stage, 'sentences.type_gap.anna-calcio', '___ il calcio, Anna?', 'Do you like football, Anna? (informal you)', 'Ti piace', Kit::form('ti piace'), 'Ti means you, and football is one thing: ti piace.'),
            Kit::typeGap($stage, 'sentences.type_gap.leggere', 'Mi piace ___ nel tempo libero.', 'I like to read in my free time.', 'leggere', Kit::word('leggere')),
            Kit::typeGap($stage, 'sentences.type_gap.ballare', '___ ballare.', 'I like to dance.', 'Mi piace', Kit::form('mi piace'), 'Mi means I, and after a verb like ballare you always use piace.'),
            Kit::typeGap($stage, 'sentences.type_gap.cantare', 'Mi piace ___ e ballare.', 'I like to sing and to dance.', 'cantare', Kit::word('cantare')),

            Kit::translate($stage, 'sentences.translate.sport', 'I like sports.', ['Mi piacciono gli sport.', 'Mi piace lo sport.'], [Kit::word('lo sport', 'sport'), Kit::form('mi piacciono', false, ['mi piace'])]),
            Kit::translate($stage, 'sentences.translate.musica', 'Do you like music? (informal you)', ['Ti piace la musica?'], [Kit::word('la musica', 'musica'), Kit::form('ti piace')]),
            Kit::translate($stage, 'sentences.translate.giocare', 'I like to play football.', ['Mi piace giocare a calcio.'], [Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::form('mi piace')]),

            Kit::build($stage, 'sentences.build.film', 'I like movies.', 'Mi piacciono i film.', ['piace'], [Kit::word('il film', 'film'), Kit::form('mi piacciono')]),
            Kit::build($stage, 'sentences.build.libri', 'Do you like books? (informal you)', 'Ti piacciono i libri?', ['piace'], [Kit::word('il libro', 'libri'), Kit::form('ti piacciono')]),
            Kit::build($stage, 'sentences.build.leggere', 'I like to read.', 'Mi piace leggere.', ['piacciono'], [Kit::word('leggere'), Kit::form('mi piace')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.musica', 'Mi piace la musica.', ['I like music.', 'I like sports.', 'You like music.', 'I like football.'], 'I like music.', [Kit::word('la musica', 'musica'), Kit::form('mi piace')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.calcio', 'Ti piace il calcio?', ['Do you like football?', 'Do I like football?', 'Do you play football?', 'Do you like sports?'], 'Do you like football?', [Kit::word('il calcio', 'calcio'), Kit::form('ti piace')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cantare', 'Mi piace cantare e ballare.', ['I like to sing and dance.', 'I like to read and dance.', 'I like to sing and read.', 'You like to sing and dance.'], 'I like to sing and dance.', [Kit::word('cantare'), Kit::word('ballare'), Kit::form('mi piace')]),
            Kit::listenType($stage, 'sentences.listen_type.sport', 'Mi piacciono gli sport.', 'I like sports.', [Kit::word('lo sport', 'sport'), Kit::form('mi piacciono')]),
            Kit::listenType($stage, 'sentences.listen_type.tempo-libero', 'Nel tempo libero mi piace leggere.', 'In my free time I like to read.', [Kit::word('il tempo libero', 'tempo libero'), Kit::word('leggere'), Kit::form('mi piace')]),
            Kit::listenType($stage, 'sentences.listen_type.film', 'Ti piacciono i film?', 'Do you like movies? (informal you)', [Kit::word('il film', 'film'), Kit::form('ti piacciono')]),
            Kit::listenType($stage, 'sentences.listen_type.calcio', 'Il calcio è uno sport.', 'Football is a sport.', [Kit::word('il calcio', 'calcio'), Kit::word('lo sport', 'sport')], homophoneNote: self::IS),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.musica', 'Mi piace la musica.', 'I like music.', [Kit::word('la musica', 'musica'), Kit::form('mi piace')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.giocare', 'Mi piace giocare a calcio.', 'I like to play football.', [Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::form('mi piace')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.libri', 'Mi piacciono i libri.', 'I like books.', [Kit::word('il libro', 'libri'), Kit::form('mi piacciono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ballare', 'Nel tempo libero mi piace ballare.', 'In my free time I like to dance.', [Kit::word('il tempo libero', 'tempo libero'), Kit::word('ballare'), Kit::form('mi piace')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.calcio', 'Ti piace il calcio?', 'Do you like football?', [['sì', 'no'], ['piace', 'calcio']], 'Sì, mi piace il calcio.', [Kit::word('il calcio', 'calcio'), Kit::form('mi piace')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cantare', 'Ti piace cantare?', 'Do you like to sing?', [['sì', 'no'], ['piace', 'cantare']], 'Sì, mi piace cantare.', [Kit::word('cantare')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.musica', 'Ti piace la musica?', 'Do you like music?', [['sì', 'no'], ['piace', 'musica']], 'Sì, mi piace la musica.', [Kit::word('la musica', 'musica')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tempo-libero', 'Read the conversation about free time.', [
                Kit::line('Anna', 'Paolo, cosa ti piace fare nel tempo libero?'),
                Kit::line('Paolo', 'Mi piacciono gli sport. Mi piace giocare a calcio.'),
                Kit::line('Anna', 'Mi piace la musica. Mi piace cantare e ballare.'),
                Kit::line('Paolo', 'Ti piace leggere?'),
                Kit::line('Anna', 'No, non mi piacciono i libri. Mi piacciono i film.'),
            ], [
                Kit::question('What does Paolo like to play?', ['Football', 'Music', 'Books'], 'Football'),
                Kit::question('What does Anna like to do?', ['Sing and dance', 'Play football', 'Read'], 'Sing and dance'),
                Kit::question('Does Anna like books?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('il tempo libero', 'tempo libero'), Kit::word('lo sport', 'sport'), Kit::word('il calcio', 'calcio'), Kit::word('giocare'), Kit::word('la musica', 'musica'), Kit::word('cantare'), Kit::word('ballare'), Kit::word('leggere'), Kit::word('il libro', 'libri'), Kit::word('il film', 'film')], 'read', null, ['cosa' => 'what', 'fare' => 'to do']),
            Kit::gap($stage, 'task.choose_gap.cantare-ballare', 'Ti ___ cantare e ballare?', ['piace', 'piacciono'], 'piace', Kit::form('piace', true), 'Cantare and ballare are verbs. After a verb you always use piace, even with two verbs.', 'read', 'Do you like to sing and dance?'),
            Kit::gap($stage, 'task.choose_gap.tempo-libero', 'Nel tempo ___ mi piace leggere.', ['libero', 'libro'], 'libero', Kit::word('il tempo libero', 'libero'), 'Tempo libero means free time. Libro is a book.', 'read', 'In my free time I like to read.'),

            Kit::transform($stage, 'task.transform.calcio', 'Now ask a friend (informal you).', 'Mi piace il calcio.', ['Ti piace il calcio?'], [Kit::word('il calcio', 'calcio'), Kit::form('ti piace')]),
            Kit::transform($stage, 'task.transform.libri', 'Change it to several books.', 'Mi piace il libro.', ['Mi piacciono i libri.'], [Kit::word('il libro', 'libri'), Kit::form('mi piacciono', true)]),
            Kit::transform($stage, 'task.transform.non-cantare', 'Say that you do not like it.', 'Mi piace cantare.', ['Non mi piace cantare.'], [Kit::word('cantare'), Kit::form('mi piace')]),
            Kit::writeGuided($stage, 'task.write_guided.musica', 'Say that you like music and that you like to sing.', ['mi piace', 'musica', 'cantare'], 'Mi piace la musica. Mi piace cantare.', [
                ['forms' => ['piace'], 'term' => null],
                ['forms' => ['musica'], 'term' => 'la musica'],
                ['forms' => ['cantare'], 'term' => 'cantare'],
            ], [Kit::word('la musica', 'musica'), Kit::word('cantare')]),
            Kit::writeGuided($stage, 'task.write_guided.tempo-libero', 'Say that in your free time you like to read and to dance.', ['tempo libero', 'mi piace', 'leggere', 'ballare'], 'Nel tempo libero mi piace leggere e ballare.', [
                ['forms' => ['libero'], 'term' => 'il tempo libero'],
                ['forms' => ['leggere'], 'term' => 'leggere'],
                ['forms' => ['ballare'], 'term' => 'ballare'],
            ], [Kit::word('il tempo libero', 'tempo libero'), Kit::word('leggere'), Kit::word('ballare')]),
            Kit::build($stage, 'task.build.tempo-libero-libri', 'In my free time I like books.', 'Nel tempo libero mi piacciono i libri.', ['piace', 'tu'], [Kit::word('il tempo libero', 'tempo libero'), Kit::word('il libro', 'libri'), Kit::form('mi piacciono', true)], 'write'),
            Kit::build($stage, 'task.build.giocare', 'Do you like to play football? (informal you)', 'Ti piace giocare a calcio?', ['piacciono', 'di'], [Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::form('ti piace')], 'write'),
            Kit::build($stage, 'task.build.sport-film', 'I like sports and movies.', 'Mi piacciono gli sport e i film.', ['piace', 'il'], [Kit::word('lo sport', 'sport'), Kit::word('il film', 'film'), Kit::form('mi piacciono')], 'write'),
            Kit::translate($stage, 'task.translate.cantare-ballare', 'I like to sing, but I do not like to dance.', ['Mi piace cantare, ma non mi piace ballare.'], [Kit::word('cantare'), Kit::word('ballare'), Kit::form('mi piace')], 'write'),
            Kit::translate($stage, 'task.translate.calcio-musica', 'Do you like football or music? (informal you)', ['Ti piace il calcio o la musica?', 'Ti piace la musica o il calcio?'], [Kit::word('il calcio', 'calcio'), Kit::word('la musica', 'musica'), Kit::form('ti piace')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.tempo-libero', [
                Kit::line('Marta', 'Luca, nel tempo libero ti piacciono gli sport?'),
                Kit::line('Luca', 'Sì, mi piacciono. Mi piace giocare a calcio. Ti piace leggere?'),
                Kit::line('Marta', 'Sì, mi piace leggere. Mi piacciono i libri e i film.'),
                Kit::line('Luca', 'Ti piace la musica?'),
                Kit::line('Marta', 'Sì, mi piace cantare e ballare.'),
            ], [
                Kit::question('What does Luca like to play?', ['Football', 'Music', 'Movies'], 'Football'),
                Kit::question('What does Marta like?', ['Books and movies', 'Football', 'Sports'], 'Books and movies'),
                Kit::question('What does Marta say about music?', ['She likes to sing and dance.', 'She does not like it.', 'She likes to read it.'], 'She likes to sing and dance.'),
            ], [
                Kit::question('Who asks about free time?', ['Marta', 'Luca', 'Nobody'], 'Marta'),
                Kit::question('Does Luca like sports?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il tempo libero', 'tempo libero'), Kit::word('lo sport', 'sport'), Kit::word('il calcio', 'calcio'), Kit::word('giocare'), Kit::word('leggere'), Kit::word('il libro', 'libri'), Kit::word('il film', 'film'), Kit::word('la musica', 'musica'), Kit::word('cantare'), Kit::word('ballare')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.musica-ballare', 'Mi piace la musica e mi piace ballare.', 'I like music and I like to dance.', [Kit::word('la musica', 'musica'), Kit::word('ballare'), Kit::form('mi piace')], 'listen', homophoneNote: self::E),
            Kit::listenType($stage, 'task.listen_type.giocare-tempo-libero', 'Ti piace giocare a calcio nel tempo libero?', 'Do you like to play football in your free time? (informal you)', [Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::word('il tempo libero', 'tempo libero'), Kit::form('ti piace')], 'listen', homophoneNote: self::A),
            Kit::listenType($stage, 'task.listen_type.no-sport', 'Non mi piacciono gli sport, ma mi piace leggere.', 'I do not like sports, but I like to read.', [Kit::word('lo sport', 'sport'), Kit::word('leggere'), Kit::form('mi piacciono')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.leggere', 'Ti piace leggere?', 'Do you like to read?', [['sì', 'no'], ['piace', 'leggere']], 'Sì, mi piace leggere.', [Kit::word('leggere'), Kit::form('mi piace')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tempo-libero', 'Nel tempo libero ti piace leggere o ballare?', 'What do you like in your free time, reading or dancing?', [['piace', 'piacciono'], ['leggere', 'ballare', 'cantare', 'giocare', 'calcio', 'musica', 'sport', 'libro', 'libri', 'film']], 'Nel tempo libero mi piace leggere.', [Kit::word('il tempo libero', 'tempo libero'), Kit::word('leggere')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.film', 'Ti piacciono i film?', 'Do you like movies?', [['sì', 'no'], ['piacciono', 'film']], 'Sì, mi piacciono i film.', [Kit::word('il film', 'film'), Kit::form('mi piacciono')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sport', 'Ti piacciono gli sport?', 'Do you like sports?', [['sì', 'no'], ['piacciono', 'piace', 'sport', 'calcio']], 'Sì, mi piacciono gli sport.', [Kit::word('lo sport', 'sport')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.libri-film', 'Mi piacciono i libri e i film.', 'I like books and movies.', [Kit::word('il libro', 'libri'), Kit::word('il film', 'film'), Kit::form('mi piacciono')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cantare-ballare', 'Mi piace cantare e ballare nel tempo libero.', 'I like to sing and dance in my free time.', [Kit::word('cantare'), Kit::word('ballare'), Kit::word('il tempo libero', 'tempo libero')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sport-libri', 'I like sports and books.', ['Mi piacciono gli sport e i libri.', 'Mi piace lo sport e mi piacciono i libri.'], [Kit::word('lo sport', 'sport'), Kit::word('il libro', 'libri'), Kit::form('mi piacciono', false, ['mi piace'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ballare', 'Do you like to dance? (informal you)', ['Ti piace ballare?'], [Kit::word('ballare'), Kit::form('ti piace')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.calcio', 'In my free time I like to play football.', ['Nel tempo libero mi piace giocare a calcio.', 'Mi piace giocare a calcio nel tempo libero.', 'Nel mio tempo libero mi piace giocare a calcio.', 'Mi piace giocare a calcio nel mio tempo libero.'], [Kit::word('il tempo libero', 'tempo libero'), Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::form('mi piace')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.film', 'I do not like movies.', ['Non mi piacciono i film.'], [Kit::word('il film', 'film'), Kit::form('mi piacciono', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cantare', 'Mi ___ cantare nel tempo libero.', 'I like to sing in my free time.', 'piace', Kit::form('piace', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-libri', 'Marta, ti ___ i libri?', 'Marta, do you like books?', 'piacciono', Kit::form('piacciono'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.musica', 'Mi piace la musica nel tempo libero.', 'I like music in my free time.', [Kit::word('la musica', 'musica'), Kit::word('il tempo libero', 'tempo libero')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cantare-ballare', 'Ti piace cantare o ballare?', 'Do you like to sing or to dance? (informal you)', [Kit::word('cantare'), Kit::word('ballare')], 'dictation', $set, homophoneNote: self::O),
            Kit::listenType($stage, 'check.a.listen_type.leggere-giocare', 'Non mi piace leggere, ma mi piace giocare.', 'I do not like to read, but I like to play.', [Kit::word('leggere'), Kit::word('giocare')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.film', [
                Kit::line('Anna', 'Paolo, ti piacciono i film?'),
                Kit::line('Paolo', 'Sì, mi piacciono i film e i libri.'),
                Kit::line('Anna', 'Non mi piacciono i libri, ma mi piace cantare.'),
            ], [
                Kit::question('What does Paolo like?', ['Movies and books', 'Football and music', 'Books only'], 'Movies and books'),
                Kit::question('What does Anna not like?', ['Books', 'Movies', 'Singing'], 'Books'),
                Kit::question('What does Anna like to do?', ['Sing', 'Read', 'Dance'], 'Sing'),
            ], [
                Kit::question('Who asks the question?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('Does Paolo like movies?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('il film', 'film'), Kit::word('il libro', 'libri'), Kit::word('cantare')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sport', 'Read the conversation.', [
                Kit::line('Marta', 'Luca, ti piace lo sport?'),
                Kit::line('Luca', 'Sì, mi piace giocare a calcio.'),
                Kit::line('Marta', 'Non mi piace il calcio, ma mi piace leggere.'),
            ], [
                Kit::question('What does Luca like to play?', ['Football', 'Music', 'Nothing'], 'Football'),
                Kit::question('What does Marta like to do?', ['Read', 'Play football', 'Sing'], 'Read'),
            ], [Kit::word('lo sport', 'sport'), Kit::word('il calcio', 'calcio'), Kit::word('giocare'), Kit::word('leggere')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.libri-film', 'Ti piacciono i libri o i film?', 'What do you like, books or movies?', [['mi'], ['libri', 'film']], 'Mi piacciono i film.', [Kit::word('il libro', 'libri'), Kit::word('il film', 'film')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sport', 'Che sport ti piace?', 'What sport do you like?', [['piace', 'mio'], ['calcio', 'sport']], 'Mi piace il calcio.', [Kit::word('il calcio', 'calcio'), Kit::word('lo sport', 'sport')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.leggere-ballare', 'Ti piace leggere o ballare?', 'What do you like, reading or dancing?', [['mi'], ['leggere', 'ballare']], 'Mi piace leggere.', [Kit::word('leggere'), Kit::word('ballare')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.musica-cantare-ballare', 'I like music, singing and dancing.', ['Mi piace la musica e mi piace cantare e ballare.', 'Mi piace la musica, cantare e ballare.', 'Mi piace la musica e cantare e ballare.'], [Kit::word('la musica', 'musica'), Kit::word('cantare'), Kit::word('ballare'), Kit::form('mi piace')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luca-calcio', 'Luca, do you like to play football in your free time? (informal you)', ['Luca, ti piace giocare a calcio nel tempo libero?', 'Ti piace giocare a calcio nel tempo libero, Luca?', 'Luca, nel tempo libero ti piace giocare a calcio?', 'Nel tempo libero ti piace giocare a calcio, Luca?'], [Kit::word('giocare'), Kit::word('il calcio', 'calcio'), Kit::word('il tempo libero', 'tempo libero'), Kit::form('ti piace')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sport-libri', 'I like sports, but I do not like books.', ['Mi piacciono gli sport, ma non mi piacciono i libri.', 'Mi piace lo sport, ma non mi piacciono i libri.'], [Kit::word('lo sport', 'sport'), Kit::word('il libro', 'libri'), Kit::form('mi piacciono', true, ['mi piace'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.leggere-film', 'I like to read, but I do not like movies.', ['Mi piace leggere, ma non mi piacciono i film.'], [Kit::word('leggere'), Kit::word('il film', 'film'), Kit::form('mi piacciono', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.paolo-musica', 'Paolo, ti piace la ___?', 'Paolo, do you like music?', 'musica', Kit::word('la musica', 'musica'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luca-giocare', 'Luca, ti piace ___ a calcio?', 'Luca, do you like to play football?', 'giocare', Kit::word('giocare'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tempo-libero', 'Nel tempo libero mi piace cantare e leggere.', 'In my free time I like to sing and to read.', [Kit::word('il tempo libero', 'tempo libero'), Kit::word('cantare'), Kit::word('leggere')], 'dictation', $set, homophoneNote: self::E),
            Kit::listenType($stage, 'check.b.listen_type.sport', 'Il mio sport è il calcio. Mi piace la musica.', 'My sport is football. I like music.', [Kit::word('lo sport', 'sport'), Kit::word('il calcio', 'calcio'), Kit::word('la musica', 'musica'), Kit::form('mi piace')], 'dictation', $set, homophoneNote: self::IS),
            Kit::listenType($stage, 'check.b.listen_type.libri', 'Mi piacciono i libri, i film e gli sport.', 'I like books, movies and sports.', [Kit::word('il libro', 'libri'), Kit::word('il film', 'film'), Kit::word('lo sport', 'sport'), Kit::form('mi piacciono')], 'dictation', $set, homophoneNote: self::E),
        ];
    }
}
