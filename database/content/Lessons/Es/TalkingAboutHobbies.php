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

final class TalkingAboutHobbies implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'talking-about-hobbies';
    }

    public function words(): array
    {
        return [
            new WordData('el deporte', cue: 'sport', forms: ['deportes']),
            new WordData('la música', cue: 'music'),
            new WordData('el fútbol', cue: 'football (soccer)'),
            new WordData('la película', cue: 'film (movie)', forms: ['películas']),
            new WordData('el tiempo libre', cue: 'free time'),
            new WordData('bailar', cue: 'to dance'),
            new WordData('cantar', cue: 'to sing'),
            new WordData('jugar', cue: 'to play (a sport or game)', note: 'You play a sport or a game with jugar: jugar al fútbol.'),
            new WordData('leer', cue: 'to read'),
            new WordData('el libro', cue: 'book', forms: ['libros']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Me gusta bailar.', 'english' => 'I like to dance.'],
            ['text' => 'Me gustan los deportes.', 'english' => 'I like sports.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.musica', 'Me ___ la música.', ['gusta', 'gustan'], 'gusta', Kit::form('gusta', true), 'La música is one thing, so the verb is gusta. The thing you like is the subject.', 'choose', 'I like music.'),
            Kit::gap($stage, 'sentences.choose_gap.deportes', 'Me ___ los deportes.', ['gustan', 'gusta'], 'gustan', Kit::form('gustan', true), 'Los deportes is plural, so the verb is gustan. The thing you like is the subject.', 'choose', 'I like sports.'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-futbol', 'Pablo, ¿___ el fútbol?', ['Te gusta', 'Me gusta'], 'Te gusta', Kit::form('te gusta', true), 'Te means you, which fits a question to Pablo. Me means I.', 'choose', 'Pablo, do you like football?'),
            Kit::gap($stage, 'sentences.choose_gap.peliculas', '___ las películas.', ['Me gustan', 'Me gusta'], 'Me gustan', Kit::form('me gustan'), 'Las películas is plural, so you need gustan.', 'choose', 'I like movies.'),
            Kit::gap($stage, 'sentences.choose_gap.jugar', 'Me gusta ___ al fútbol.', ['jugar', 'leer', 'cantar'], 'jugar', Kit::word('jugar'), 'You play a sport with jugar. Leer is to read and cantar is to sing.', 'choose', 'I like to play football.'),
            Kit::gap($stage, 'sentences.choose_gap.la-musica', 'Me gusta la ___.', ['música', 'deporte', 'libro'], 'música', Kit::word('la música', 'música'), 'La goes with música. Deporte and libro take el.', 'choose', 'I like music.'),

            Kit::typeGap($stage, 'sentences.type_gap.libros', 'Me ___ los libros.', 'I like books.', 'gustan', Kit::form('gustan'), 'Los libros is plural, so the verb is gustan.'),
            Kit::typeGap($stage, 'sentences.type_gap.ana-futbol', '¿___ el fútbol, Ana?', 'Do you like football, Ana? (informal you)', 'Te gusta', Kit::form('te gusta'), 'Te means you, and football is one thing: te gusta.'),
            Kit::typeGap($stage, 'sentences.type_gap.leer', 'Me gusta ___ en mi tiempo libre.', 'I like to read in my free time.', 'leer', Kit::word('leer')),
            Kit::typeGap($stage, 'sentences.type_gap.bailar', '___ bailar.', 'I like to dance.', 'Me gusta', Kit::form('me gusta'), 'Me means I, and after a verb like bailar you always use gusta.'),
            Kit::typeGap($stage, 'sentences.type_gap.cantar', 'Me gusta ___ y bailar.', 'I like to sing and to dance.', 'cantar', Kit::word('cantar')),

            Kit::translate($stage, 'sentences.translate.deportes', 'I like sports.', ['Me gustan los deportes.'], [Kit::word('el deporte', 'deportes'), Kit::form('me gustan')]),
            Kit::translate($stage, 'sentences.translate.musica', 'Do you like music? (informal you)', ['¿Te gusta la música?'], [Kit::word('la música', 'música'), Kit::form('te gusta')]),
            Kit::translate($stage, 'sentences.translate.jugar', 'I like to play football.', ['Me gusta jugar al fútbol.'], [Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::form('me gusta')]),

            Kit::build($stage, 'sentences.build.peliculas', 'I like movies.', 'Me gustan las películas.', ['gusta'], [Kit::word('la película', 'películas'), Kit::form('me gustan')]),
            Kit::build($stage, 'sentences.build.libros', 'Do you like books? (informal you)', '¿Te gustan los libros?', ['gusta'], [Kit::word('el libro', 'libros'), Kit::form('te gustan')]),
            Kit::build($stage, 'sentences.build.leer', 'I like to read.', 'Me gusta leer.', ['gustan'], [Kit::word('leer'), Kit::form('me gusta')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.musica', 'Me gusta la música.', ['I like music.', 'I like sports.', 'You like music.', 'I like football.'], 'I like music.', [Kit::word('la música', 'música'), Kit::form('me gusta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.futbol', '¿Te gusta el fútbol?', ['Do you like football?', 'Do I like football?', 'Do you play football?', 'Do you like sports?'], 'Do you like football?', [Kit::word('el fútbol', 'fútbol'), Kit::form('te gusta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cantar', 'Me gusta cantar y bailar.', ['I like to sing and dance.', 'I like to read and dance.', 'I like to sing and read.', 'You like to sing and dance.'], 'I like to sing and dance.', [Kit::word('cantar'), Kit::word('bailar'), Kit::form('me gusta')]),
            Kit::listenType($stage, 'sentences.listen_type.deportes', 'Me gustan los deportes.', 'I like sports.', [Kit::word('el deporte', 'deportes'), Kit::form('me gustan')]),
            Kit::listenType($stage, 'sentences.listen_type.tiempo-libre', 'En mi tiempo libre me gusta leer.', 'In my free time I like to read.', [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('leer'), Kit::form('me gusta')]),
            Kit::listenType($stage, 'sentences.listen_type.peliculas', '¿Te gustan las películas?', 'Do you like movies? (informal you)', [Kit::word('la película', 'películas'), Kit::form('te gustan')]),
            Kit::listenType($stage, 'sentences.listen_type.futbol', 'El fútbol es un deporte.', 'Football is a sport.', [Kit::word('el fútbol', 'fútbol'), Kit::word('el deporte', 'deporte')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.musica', 'Me gusta la música.', 'I like music.', [Kit::word('la música', 'música'), Kit::form('me gusta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.jugar', 'Me gusta jugar al fútbol.', 'I like to play football.', [Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::form('me gusta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.libros', 'Me gustan los libros.', 'I like books.', [Kit::word('el libro', 'libros'), Kit::form('me gustan')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bailar', 'En mi tiempo libre me gusta bailar.', 'In my free time I like to dance.', [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('bailar'), Kit::form('me gusta')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.futbol', '¿Te gusta el fútbol?', 'Do you like football?', [['sí', 'no'], ['gusta', 'fútbol']], 'Sí, me gusta el fútbol.', [Kit::word('el fútbol', 'fútbol'), Kit::form('me gusta')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cantar', '¿Te gusta cantar?', 'Do you like to sing?', [['sí', 'no'], ['gusta', 'cantar']], 'Sí, me gusta cantar.', [Kit::word('cantar')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.musica', '¿Te gusta la música?', 'Do you like music?', [['sí', 'no'], ['gusta', 'música']], 'Sí, me gusta la música.', [Kit::word('la música', 'música')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tiempo-libre', 'Read the conversation about free time.', [
                Kit::line('Ana', 'Pablo, ¿qué te gusta en tu tiempo libre?'),
                Kit::line('Pablo', 'Me gustan los deportes. Me gusta jugar al fútbol.'),
                Kit::line('Ana', 'Me gusta la música. Me gusta cantar y bailar.'),
                Kit::line('Pablo', '¿Te gusta leer?'),
                Kit::line('Ana', 'No, no me gustan los libros. Me gustan las películas.'),
            ], [
                Kit::question('What does Pablo like to play?', ['Football', 'Music', 'Books'], 'Football'),
                Kit::question('What does Ana like to do?', ['Sing and dance', 'Play football', 'Read'], 'Sing and dance'),
                Kit::question('Does Ana like books?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('el deporte', 'deportes'), Kit::word('el fútbol', 'fútbol'), Kit::word('jugar'), Kit::word('la música', 'música'), Kit::word('cantar'), Kit::word('bailar'), Kit::word('leer'), Kit::word('el libro', 'libros'), Kit::word('la película', 'películas')], 'read'),
            Kit::gap($stage, 'task.choose_gap.cantar-bailar', '¿Te ___ cantar y bailar?', ['gusta', 'gustan'], 'gusta', Kit::form('gusta', true), 'Cantar and bailar are verbs. After a verb you always use gusta, even with two verbs.', 'read', 'Do you like to sing and dance?'),
            Kit::gap($stage, 'task.choose_gap.tiempo-libre', 'En mi tiempo ___ me gusta leer.', ['libre', 'libro'], 'libre', Kit::word('el tiempo libre', 'libre'), 'Tiempo libre means free time. Libro is a book.', 'read', 'In my free time I like to read.'),

            Kit::transform($stage, 'task.transform.futbol', 'Now ask a friend (informal you).', 'Me gusta el fútbol.', ['¿Te gusta el fútbol?'], [Kit::word('el fútbol', 'fútbol'), Kit::form('te gusta')]),
            Kit::transform($stage, 'task.transform.libros', 'Change it to several books.', 'Me gusta el libro.', ['Me gustan los libros.'], [Kit::word('el libro', 'libros'), Kit::form('me gustan', true)]),
            Kit::transform($stage, 'task.transform.no-cantar', 'Say that you do not like it.', 'Me gusta cantar.', ['No me gusta cantar.'], [Kit::word('cantar'), Kit::form('me gusta')]),
            Kit::writeGuided($stage, 'task.write_guided.musica', 'Say that you like music and that you like to sing.', ['me gusta', 'música', 'cantar'], 'Me gusta la música. Me gusta cantar.', [
                ['forms' => ['gusta'], 'term' => null],
                ['forms' => ['música'], 'term' => 'la música'],
                ['forms' => ['cantar'], 'term' => 'cantar'],
            ], [Kit::word('la música', 'música'), Kit::word('cantar')]),
            Kit::writeGuided($stage, 'task.write_guided.tiempo-libre', 'Say that in your free time you like to read and to dance.', ['mi tiempo libre', 'me gusta', 'leer', 'bailar'], 'En mi tiempo libre me gusta leer y bailar.', [
                ['forms' => ['libre'], 'term' => 'el tiempo libre'],
                ['forms' => ['leer'], 'term' => 'leer'],
                ['forms' => ['bailar'], 'term' => 'bailar'],
            ], [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('leer'), Kit::word('bailar')]),
            Kit::build($stage, 'task.build.tiempo-libre-libros', 'In my free time I like books.', 'En mi tiempo libre me gustan los libros.', ['gusta', 'tu'], [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('el libro', 'libros'), Kit::form('me gustan', true)], 'write'),
            Kit::build($stage, 'task.build.jugar', 'Do you like to play football? (informal you)', '¿Te gusta jugar al fútbol?', ['gustan', 'a'], [Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::form('te gusta')], 'write'),
            Kit::build($stage, 'task.build.deportes-peliculas', 'I like sports and movies.', 'Me gustan los deportes y las películas.', ['gusta', 'el'], [Kit::word('el deporte', 'deportes'), Kit::word('la película', 'películas'), Kit::form('me gustan')], 'write'),
            Kit::translate($stage, 'task.translate.cantar-bailar', 'I like to sing, but I do not like to dance.', ['Me gusta cantar, pero no me gusta bailar.'], [Kit::word('cantar'), Kit::word('bailar'), Kit::form('me gusta')], 'write'),
            Kit::translate($stage, 'task.translate.futbol-musica', 'Do you like football or music? (informal you)', ['¿Te gusta el fútbol o la música?', '¿Te gusta la música o el fútbol?'], [Kit::word('el fútbol', 'fútbol'), Kit::word('la música', 'música'), Kit::form('te gusta')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.tiempo-libre', [
                Kit::line('Marta', '¿Qué te gusta en tu tiempo libre, Luis?'),
                Kit::line('Luis', 'Me gustan los deportes. Me gusta jugar al fútbol.'),
                Kit::line('Marta', 'Me gusta leer. Me gustan los libros y las películas.'),
                Kit::line('Luis', '¿Te gusta la música?'),
                Kit::line('Marta', 'Sí, me gusta cantar y bailar.'),
            ], [
                Kit::question('What does Luis like to play?', ['Football', 'Music', 'Movies'], 'Football'),
                Kit::question('What does Marta like?', ['Books and movies', 'Football', 'Sports'], 'Books and movies'),
                Kit::question('What does Marta say about music?', ['She likes to sing and dance.', 'She does not like it.', 'She likes to read it.'], 'She likes to sing and dance.'),
            ], [
                Kit::question('Who asks about free time?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Does Luis like sports?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('el deporte', 'deportes'), Kit::word('el fútbol', 'fútbol'), Kit::word('jugar'), Kit::word('leer'), Kit::word('el libro', 'libros'), Kit::word('la película', 'películas'), Kit::word('la música', 'música'), Kit::word('cantar'), Kit::word('bailar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.musica-bailar', 'Me gusta la música y me gusta bailar.', 'I like music and I like to dance.', [Kit::word('la música', 'música'), Kit::word('bailar'), Kit::form('me gusta')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.jugar-tiempo-libre', '¿Te gusta jugar al fútbol en tu tiempo libre?', 'Do you like to play football in your free time? (informal you)', [Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::word('el tiempo libre', 'tiempo libre'), Kit::form('te gusta')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.no-deportes', 'No me gustan los deportes, pero me gusta leer.', 'I do not like sports, but I like to read.', [Kit::word('el deporte', 'deportes'), Kit::word('leer'), Kit::form('me gustan')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.leer', '¿Te gusta leer?', 'Do you like to read?', [['sí', 'no'], ['gusta', 'leer']], 'Sí, me gusta leer.', [Kit::word('leer'), Kit::form('me gusta')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tiempo-libre', '¿Qué te gusta en tu tiempo libre?', 'What do you like in your free time?', [['gusta', 'gustan'], ['leer', 'bailar', 'cantar', 'jugar', 'fútbol', 'música', 'deporte', 'deportes', 'libro', 'libros', 'película', 'películas']], 'En mi tiempo libre me gusta leer.', [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('leer')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.peliculas', '¿Te gustan las películas?', 'Do you like movies?', [['sí', 'no'], ['gustan', 'películas']], 'Sí, me gustan las películas.', [Kit::word('la película', 'películas'), Kit::form('me gustan')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.deportes', '¿Te gustan los deportes?', 'Do you like sports?', [['sí', 'no'], ['gustan', 'gusta', 'deportes', 'fútbol']], 'Sí, me gustan los deportes.', [Kit::word('el deporte', 'deportes')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.libros-peliculas', 'Me gustan los libros y las películas.', 'I like books and movies.', [Kit::word('el libro', 'libros'), Kit::word('la película', 'películas'), Kit::form('me gustan')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cantar-bailar', 'Me gusta cantar y bailar en mi tiempo libre.', 'I like to sing and dance in my free time.', [Kit::word('cantar'), Kit::word('bailar'), Kit::word('el tiempo libre', 'tiempo libre')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.deportes-libros', 'I like sports and books.', ['Me gustan los deportes y los libros.'], [Kit::word('el deporte', 'deportes'), Kit::word('el libro', 'libros'), Kit::form('me gustan')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bailar', 'Do you like to dance? (informal you)', ['¿Te gusta bailar?'], [Kit::word('bailar'), Kit::form('te gusta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.futbol', 'In my free time I like to play football.', ['En mi tiempo libre me gusta jugar al fútbol.', 'Me gusta jugar al fútbol en mi tiempo libre.'], [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::form('me gusta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.peliculas', 'I do not like movies.', ['No me gustan las películas.'], [Kit::word('la película', 'películas'), Kit::form('me gustan', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cantar', 'Me ___ cantar en mi tiempo libre.', 'I like to sing in my free time.', 'gusta', Kit::form('gusta', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-libros', 'Marta, ¿te ___ los libros?', 'Marta, do you like books?', 'gustan', Kit::form('gustan'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.musica', 'Me gusta la música en mi tiempo libre.', 'I like music in my free time.', [Kit::word('la música', 'música'), Kit::word('el tiempo libre', 'tiempo libre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cantar-bailar', '¿Te gusta cantar o bailar?', 'Do you like to sing or to dance? (informal you)', [Kit::word('cantar'), Kit::word('bailar')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.leer-jugar', 'No me gusta leer, pero me gusta jugar.', 'I do not like to read, but I like to play.', [Kit::word('leer'), Kit::word('jugar')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.peliculas', [
                Kit::line('Ana', 'Pablo, ¿te gustan las películas?'),
                Kit::line('Pablo', 'Sí, me gustan. También me gustan los libros.'),
                Kit::line('Ana', 'Pero no me gustan los libros, y me gusta cantar.'),
            ], [
                Kit::question('What does Pablo like?', ['Movies and books', 'Football and music', 'Books only'], 'Movies and books'),
                Kit::question('What does Ana not like?', ['Books', 'Movies', 'Singing'], 'Books'),
                Kit::question('What does Ana like to do?', ['Sing', 'Read', 'Dance'], 'Sing'),
            ], [
                Kit::question('Who asks the question?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Does Pablo like movies?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la película', 'películas'), Kit::word('el libro', 'libros'), Kit::word('cantar')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.deporte', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿te gusta el deporte?'),
                Kit::line('Luis', 'Sí, me gusta jugar al fútbol.'),
                Kit::line('Marta', 'No me gusta el fútbol, pero me gusta leer.'),
            ], [
                Kit::question('What does Luis like to play?', ['Football', 'Music', 'Nothing'], 'Football'),
                Kit::question('What does Marta like to do?', ['Read', 'Play football', 'Sing'], 'Read'),
            ], [Kit::word('el deporte', 'deporte'), Kit::word('el fútbol', 'fútbol'), Kit::word('jugar'), Kit::word('leer')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.musica', '¿Qué te gusta, los libros o las películas?', 'What do you like, books or movies?', [['me'], ['libros', 'películas']], 'Me gustan las películas.', [Kit::word('el libro', 'libros'), Kit::word('la película', 'películas')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.deporte', '¿Qué deporte te gusta?', 'What sport do you like?', [['me gusta', 'mi'], ['fútbol']], 'Me gusta el fútbol.', [Kit::word('el fútbol', 'fútbol'), Kit::word('el deporte', 'deporte')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.leer-bailar', '¿Qué te gusta, leer o bailar?', 'What do you like, reading or dancing?', [['me'], ['leer', 'bailar']], 'Me gusta leer.', [Kit::word('leer'), Kit::word('bailar')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.musica-cantar-bailar', 'I like music, singing and dancing.', ['Me gusta la música, cantar y bailar.'], [Kit::word('la música', 'música'), Kit::word('cantar'), Kit::word('bailar'), Kit::form('me gusta')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luis-futbol', 'Luis, do you like to play football in your free time? (informal you)', ['Luis, ¿te gusta jugar al fútbol en tu tiempo libre?', '¿Te gusta jugar al fútbol en tu tiempo libre, Luis?'], [Kit::word('jugar'), Kit::word('el fútbol', 'fútbol'), Kit::word('el tiempo libre', 'tiempo libre'), Kit::form('te gusta')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.deportes-libros', 'I like sports, but I do not like books.', ['Me gustan los deportes, pero no me gustan los libros.'], [Kit::word('el deporte', 'deportes'), Kit::word('el libro', 'libros'), Kit::form('me gustan', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.leer-peliculas', 'I like to read, but I do not like movies.', ['Me gusta leer, pero no me gustan las películas.'], [Kit::word('leer'), Kit::word('la película', 'películas'), Kit::form('me gustan', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-musica', 'Pablo, ¿te gusta la ___?', 'Pablo, do you like music?', 'música', Kit::word('la música', 'música'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-jugar', 'Luis, ¿te gusta ___ al fútbol?', 'Luis, do you like to play football?', 'jugar', Kit::word('jugar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tiempo-libre', 'En mi tiempo libre me gusta cantar y leer.', 'In my free time I like to sing and to read.', [Kit::word('el tiempo libre', 'tiempo libre'), Kit::word('cantar'), Kit::word('leer')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.deporte', 'Mi deporte es el fútbol y me gusta la música.', 'My sport is football and I like music.', [Kit::word('el deporte', 'deporte'), Kit::word('el fútbol', 'fútbol'), Kit::word('la música', 'música'), Kit::form('me gusta')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.libros', 'Me gustan los libros, las películas y los deportes.', 'I like books, movies and sports.', [Kit::word('el libro', 'libros'), Kit::word('la película', 'películas'), Kit::word('el deporte', 'deportes'), Kit::form('me gustan')], 'dictation', $set),
        ];
    }
}
