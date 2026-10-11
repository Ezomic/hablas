<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

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
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'talking-about-hobbies';
    }

    public function words(): array
    {
        return [
            new WordData('o desporto', cue: 'sport', forms: ['desportos'], portunolSlips: ['deporte']),
            new WordData('a música', cue: 'music'),
            new WordData('o futebol', cue: 'football (soccer)', portunolSlips: ['fútbol']),
            new WordData('o filme', cue: 'film (movie)', forms: ['filmes'], portunolSlips: ['película']),
            new WordData('o tempo livre', cue: 'free time', portunolSlips: ['tiempo libre']),
            new WordData('dançar', cue: 'to dance', portunolSlips: ['bailar']),
            new WordData('cantar', cue: 'to sing'),
            new WordData('jogar', cue: 'to play (a sport or game)', portunolSlips: ['jugar'], note: 'You play a sport or a game with jogar: jogar futebol.'),
            new WordData('ler', cue: 'to read', portunolSlips: ['leer']),
            new WordData('o livro', cue: 'book', forms: ['livros'], portunolSlips: ['libro']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Gosto de dançar.', 'english' => 'I like to dance.'],
            ['text' => 'Gosto dos filmes.', 'english' => 'I like the movies.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.musica', 'Gosto ___ minha música.', ['da', 'do'], 'da', Kit::form('da', true), 'A música is feminine, so de + a becomes da. Portugal keeps the article before minha: gosto da minha música.', 'choose', 'I like my music.'),
            Kit::gap($stage, 'sentences.choose_gap.livros', 'Gosto ___ meus livros.', ['dos', 'do'], 'dos', Kit::form('dos', true), 'Os meus livros is plural, so de + os becomes dos.', 'choose', 'I like my books.'),
            Kit::gap($stage, 'sentences.choose_gap.rui-futebol', 'Rui, ___ futebol?', ['Gostas do', 'Gosto do'], 'Gostas do', Kit::form('gostas do', true), 'You ask Rui, so you need gostas (you). Gosto means I.', 'choose', 'Rui, do you like football?'),
            Kit::gap($stage, 'sentences.choose_gap.filmes', '___ filmes.', ['Gosto dos', 'Gosto do'], 'Gosto dos', Kit::form('gosto dos'), 'Filmes is plural, so de + os becomes dos.', 'choose', 'I like the movies.'),
            Kit::gap($stage, 'sentences.choose_gap.jogar', 'Gosto de ___ futebol.', ['jogar', 'ler', 'cantar'], 'jogar', Kit::word('jogar'), 'You play a sport with jogar. Ler is to read and cantar is to sing.', 'choose', 'I like to play football.'),
            Kit::gap($stage, 'sentences.choose_gap.a-musica', 'Gosto da ___.', ['música', 'desporto', 'livro'], 'música', Kit::word('a música', 'música'), 'Da goes with a: a música. Desporto and livro take o, so you say do desporto and do livro.', 'choose', 'I like the music.'),

            Kit::typeGap($stage, 'sentences.type_gap.livros', 'Gosto ___ livros.', 'I like the books.', 'dos', Kit::form('dos'), 'Os livros is plural, so de + os becomes dos.'),
            Kit::typeGap($stage, 'sentences.type_gap.ana-futebol', '___ do futebol, Ana?', 'Do you like football, Ana? (informal you)', 'Gostas', Kit::form('gostas'), 'You ask Ana, so you need gostas (you).'),
            Kit::typeGap($stage, 'sentences.type_gap.ler', 'Gosto de ___ no meu tempo livre.', 'I like to read in my free time.', 'ler', Kit::word('ler')),
            Kit::typeGap($stage, 'sentences.type_gap.dancar', '___ dançar.', 'I like to dance.', 'Gosto de', Kit::form('gosto de'), 'Before a verb you always use de: gosto de dançar.'),
            Kit::typeGap($stage, 'sentences.type_gap.cantar', 'Gosto de ___ e dançar.', 'I like to sing and to dance.', 'cantar', Kit::word('cantar')),

            Kit::translate($stage, 'sentences.translate.desportos', 'I like sports.', ['Gosto de desportos.', 'Gosto de desporto.', 'Gosto dos desportos.'], [Kit::word('o desporto', 'desportos', ['desporto']), Kit::form('gosto de', false, ['gosto dos'])]),
            Kit::translate($stage, 'sentences.translate.musica', 'Do you like music? (informal you)', ['Gostas de música?', 'Gostas da música?'], [Kit::word('a música', 'música'), Kit::form('gostas de', false, ['gostas da'])]),
            Kit::translate($stage, 'sentences.translate.jogar', 'I like to play football.', ['Gosto de jogar futebol.', 'Gosto de jogar ao futebol.'], [Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::form('gosto de')]),

            Kit::build($stage, 'sentences.build.filmes', 'I like the movies.', 'Gosto dos filmes.', ['gostas'], [Kit::word('o filme', 'filmes'), Kit::form('gosto dos')]),
            Kit::build($stage, 'sentences.build.livros', 'Do you like the books? (informal you)', 'Gostas dos livros?', ['gosto'], [Kit::word('o livro', 'livros'), Kit::form('gostas dos')]),
            Kit::build($stage, 'sentences.build.ler', 'I like to read.', 'Gosto de ler.', ['gostas'], [Kit::word('ler'), Kit::form('gosto de')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.musica', 'Gosto de música.', ['I like music.', 'I like sports.', 'You like music.', 'I like football.'], 'I like music.', [Kit::word('a música', 'música'), Kit::form('gosto de')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.futebol', 'Gostas de futebol?', ['Do you like football?', 'Do I like football?', 'Do you play football?', 'Do you like sports?'], 'Do you like football?', [Kit::word('o futebol', 'futebol'), Kit::form('gostas de')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cantar', 'Gosto de cantar e dançar.', ['I like to sing and dance.', 'I like to read and dance.', 'I like to sing and read.', 'You like to sing and dance.'], 'I like to sing and dance.', [Kit::word('cantar'), Kit::word('dançar'), Kit::form('gosto de')]),
            Kit::listenType($stage, 'sentences.listen_type.desportos', 'Gosto dos desportos.', 'I like sports.', [Kit::word('o desporto', 'desportos'), Kit::form('gosto dos')]),
            Kit::listenType($stage, 'sentences.listen_type.tempo-livre', 'No meu tempo livre gosto de ler.', 'In my free time I like to read.', [Kit::word('o tempo livre', 'tempo livre'), Kit::word('ler'), Kit::form('gosto de')]),
            Kit::listenType($stage, 'sentences.listen_type.filmes', 'Gostas dos filmes?', 'Do you like the movies? (informal you)', [Kit::word('o filme', 'filmes'), Kit::form('gostas dos')]),
            Kit::listenType($stage, 'sentences.listen_type.futebol', 'O futebol é um desporto.', 'Football is a sport.', [Kit::word('o futebol', 'futebol'), Kit::word('o desporto', 'desporto')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.musica', 'Gosto da música.', 'I like the music.', [Kit::word('a música', 'música'), Kit::form('gosto da')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.jogar', 'Gosto de jogar futebol.', 'I like to play football.', [Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::form('gosto de')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.livros', 'Gosto dos livros.', 'I like the books.', [Kit::word('o livro', 'livros'), Kit::form('gosto dos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.dancar', 'No meu tempo livre gosto de dançar.', 'In my free time I like to dance.', [Kit::word('o tempo livre', 'tempo livre'), Kit::word('dançar'), Kit::form('gosto de')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.futebol', 'Gostas de futebol?', 'Do you like football?', [['sim', 'não'], ['gosto', 'futebol']], 'Sim, gosto de futebol.', [Kit::word('o futebol', 'futebol'), Kit::form('gosto de')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cantar', 'Gostas de cantar?', 'Do you like to sing?', [['sim', 'não'], ['gosto', 'cantar']], 'Sim, gosto de cantar.', [Kit::word('cantar')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.musica', 'Gostas de música?', 'Do you like music?', [['sim', 'não'], ['gosto', 'música']], 'Sim, gosto de música.', [Kit::word('a música', 'música')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tempo-livre', 'Read the conversation about free time.', [
                Kit::line('Ana', 'Rui, de que gostas no teu tempo livre?'),
                Kit::line('Rui', 'Gosto de desporto. Gosto de jogar futebol.'),
                Kit::line('Ana', 'Gosto de música. Gosto de cantar e dançar.'),
                Kit::line('Rui', 'Gostas de ler?'),
                Kit::line('Ana', 'Não, não gosto de livros. Gosto de filmes.'),
            ], [
                Kit::question('What does Rui like to play?', ['Football', 'Music', 'Books'], 'Football'),
                Kit::question('What does Ana like to do?', ['Sing and dance', 'Play football', 'Read'], 'Sing and dance'),
                Kit::question('Does Ana like books?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('o tempo livre', 'tempo livre'), Kit::word('o desporto', 'desporto'), Kit::word('o futebol', 'futebol'), Kit::word('jogar'), Kit::word('a música', 'música'), Kit::word('cantar'), Kit::word('dançar'), Kit::word('ler'), Kit::word('o livro', 'livros'), Kit::word('o filme', 'filmes')], 'read'),
            Kit::gap($stage, 'task.choose_gap.cantar-dancar', 'Gostas ___ cantar e dançar?', ['de', 'do'], 'de', Kit::form('de', true), 'Cantar and dançar are verbs. Before a verb you always use de, even with two verbs.', 'read', 'Do you like to sing and dance?'),
            Kit::gap($stage, 'task.choose_gap.tempo-livre', 'No meu tempo ___ gosto de ler.', ['livre', 'livro'], 'livre', Kit::word('o tempo livre', 'livre'), 'Tempo livre means free time. Livro is a book.', 'read', 'In my free time I like to read.'),

            Kit::transform($stage, 'task.transform.futebol', 'Now ask a friend (informal you).', 'Gosto do futebol.', ['Gostas do futebol?', 'Gostas de futebol?'], [Kit::word('o futebol', 'futebol'), Kit::form('gostas do', false, ['gostas de'])]),
            Kit::transform($stage, 'task.transform.livros', 'Change it to several books.', 'Gosto do livro.', ['Gosto dos livros.', 'Gosto de livros.'], [Kit::word('o livro', 'livros'), Kit::form('gosto dos', true, ['gosto de'])]),
            Kit::transform($stage, 'task.transform.nao-cantar', 'Say that you do not like it.', 'Gosto de cantar.', ['Não gosto de cantar.'], [Kit::word('cantar'), Kit::form('gosto de')]),
            Kit::writeGuided($stage, 'task.write_guided.musica', 'Say that you like music and that you like to sing.', ['gosto de', 'música', 'cantar'], 'Gosto de música. Gosto de cantar.', [
                ['forms' => ['gosto'], 'term' => null],
                ['forms' => ['música'], 'term' => 'a música'],
                ['forms' => ['cantar'], 'term' => 'cantar'],
            ], [Kit::word('a música', 'música'), Kit::word('cantar')]),
            Kit::writeGuided($stage, 'task.write_guided.tempo-livre', 'Say that in your free time you like to read and to dance.', ['no meu tempo livre', 'gosto de', 'ler', 'dançar'], 'No meu tempo livre gosto de ler e dançar.', [
                ['forms' => ['livre'], 'term' => 'o tempo livre'],
                ['forms' => ['ler'], 'term' => 'ler'],
                ['forms' => ['dançar'], 'term' => 'dançar'],
            ], [Kit::word('o tempo livre', 'tempo livre'), Kit::word('ler'), Kit::word('dançar')]),
            Kit::build($stage, 'task.build.tempo-livre-livros', 'In my free time I like books. (start with the time)', 'No meu tempo livre gosto de livros.', ['gostas', 'da'], [Kit::word('o tempo livre', 'tempo livre'), Kit::word('o livro', 'livros'), Kit::form('gosto de', true)], 'write'),
            Kit::build($stage, 'task.build.jogar', 'Do you like to play football? (informal you)', 'Gostas de jogar futebol?', ['gosto', 'do'], [Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::form('gostas de')], 'write'),
            Kit::build($stage, 'task.build.desportos-filmes', 'I like the sports and the movies. (sports first)', 'Gosto dos desportos e dos filmes.', ['gostas', 'de'], [Kit::word('o desporto', 'desportos'), Kit::word('o filme', 'filmes'), Kit::form('gosto dos')], 'write'),
            Kit::translate($stage, 'task.translate.cantar-dancar', 'I like to sing, but I do not like to dance.', ['Gosto de cantar, mas não gosto de dançar.'], [Kit::word('cantar'), Kit::word('dançar'), Kit::form('gosto de')], 'write'),
            Kit::translate($stage, 'task.translate.futebol-musica', 'Do you like football or music? (informal you)', ['Gostas de futebol ou de música?', 'Gostas de música ou de futebol?', 'Gostas de futebol ou música?', 'Gostas de música ou futebol?'], [Kit::word('o futebol', 'futebol'), Kit::word('a música', 'música'), Kit::form('gostas de')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.tempo-livre', [
                Kit::line('Marta', 'De que gostas no teu tempo livre, João?'),
                Kit::line('João', 'Gosto de desporto. Gosto de jogar futebol.'),
                Kit::line('Marta', 'Gosto de ler. Gosto de livros e de filmes.'),
                Kit::line('João', 'Gostas de música?'),
                Kit::line('Marta', 'Sim, gosto de cantar e dançar.'),
            ], [
                Kit::question('What does João like to play?', ['Football', 'Music', 'Movies'], 'Football'),
                Kit::question('What does Marta like?', ['Books and movies', 'Football', 'Sports'], 'Books and movies'),
                Kit::question('What does Marta say about music?', ['She likes to sing and dance.', 'She does not like it.', 'She likes to read it.'], 'She likes to sing and dance.'),
            ], [
                Kit::question('Who asks about free time?', ['Marta', 'João', 'Nobody'], 'Marta'),
                Kit::question('Does João like sports?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o tempo livre', 'tempo livre'), Kit::word('o desporto', 'desporto'), Kit::word('o futebol', 'futebol'), Kit::word('jogar'), Kit::word('ler'), Kit::word('o livro', 'livros'), Kit::word('o filme', 'filmes'), Kit::word('a música', 'música'), Kit::word('cantar'), Kit::word('dançar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.musica-dancar', 'Gosto de música e gosto de dançar.', 'I like music and I like to dance.', [Kit::word('a música', 'música'), Kit::word('dançar'), Kit::form('gosto de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.jogar-tempo-livre', 'Gostas de jogar futebol no teu tempo livre?', 'Do you like to play football in your free time? (informal you)', [Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::word('o tempo livre', 'tempo livre'), Kit::form('gostas de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.nao-desportos', 'Não gosto dos desportos, mas gosto de ler.', 'I do not like sports, but I like to read.', [Kit::word('o desporto', 'desportos'), Kit::word('ler'), Kit::form('gosto dos')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.ler', 'Gostas de ler?', 'Do you like to read?', [['sim', 'não'], ['gosto', 'ler']], 'Sim, gosto de ler.', [Kit::word('ler'), Kit::form('gosto de')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tempo-livre', 'De que gostas no teu tempo livre?', 'What do you like in your free time?', [['gosto'], ['ler', 'dançar', 'cantar', 'jogar', 'futebol', 'música', 'desporto', 'desportos', 'livro', 'livros', 'filme', 'filmes']], 'No meu tempo livre gosto de ler.', [Kit::word('o tempo livre', 'tempo livre'), Kit::word('ler')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.filmes', 'Gostas de filmes?', 'Do you like movies?', [['sim', 'não'], ['gosto', 'filmes']], 'Sim, gosto de filmes.', [Kit::word('o filme', 'filmes'), Kit::form('gosto de')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.desportos', 'Gostas de desporto?', 'Do you like sports?', [['sim', 'não'], ['gosto', 'desporto', 'desportos', 'futebol']], 'Sim, gosto de desporto.', [Kit::word('o desporto', 'desporto')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.livros-filmes', 'Gosto de livros e de filmes.', 'I like books and movies.', [Kit::word('o livro', 'livros'), Kit::word('o filme', 'filmes'), Kit::form('gosto de')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.cantar-dancar', 'Gosto de cantar e dançar no meu tempo livre.', 'I like to sing and dance in my free time.', [Kit::word('cantar'), Kit::word('dançar'), Kit::word('o tempo livre', 'tempo livre')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.desportos-livros', 'I like sports and books.', ['Gosto de desportos e de livros.', 'Gosto de desporto e de livros.', 'Gosto dos desportos e dos livros.', 'Gosto de desportos e livros.', 'Gosto de desporto e livros.', 'Gosto dos desportos e livros.'], [Kit::word('o desporto', 'desportos', ['desporto']), Kit::word('o livro', 'livros'), Kit::form('gosto de', false, ['gosto dos'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dancar', 'Do you like to dance? (informal you)', ['Gostas de dançar?'], [Kit::word('dançar'), Kit::form('gostas de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.futebol', 'In my free time I like to play football.', ['No meu tempo livre gosto de jogar futebol.', 'Gosto de jogar futebol no meu tempo livre.', 'No meu tempo livre gosto de jogar ao futebol.', 'Gosto de jogar ao futebol no meu tempo livre.'], [Kit::word('o tempo livre', 'tempo livre'), Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::form('gosto de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.filmes', 'I do not like movies.', ['Não gosto de filmes.', 'Não gosto dos filmes.'], [Kit::word('o filme', 'filmes'), Kit::form('gosto de', true, ['gosto dos'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cantar', 'Gosto ___ cantar no meu tempo livre.', 'I like to sing in my free time.', 'de', Kit::form('de', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta-livros', 'Marta, gostas ___ teus livros?', 'Marta, do you like your books?', 'dos', Kit::form('dos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.musica', 'No meu tempo livre gosto da música.', 'I like the music in my free time.', [Kit::word('a música', 'música'), Kit::word('o tempo livre', 'tempo livre')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cantar-dancar', 'Gostas de cantar ou de dançar?', 'Do you like to sing or to dance? (informal you)', [Kit::word('cantar'), Kit::word('dançar')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ler-jogar', 'Não gosto de ler, mas gosto de jogar.', 'I do not like to read, but I like to play.', [Kit::word('ler'), Kit::word('jogar')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.filmes', [
                Kit::line('Ana', 'Rui, gostas de filmes?'),
                Kit::line('Rui', 'Sim, gosto. Também gosto de livros.'),
                Kit::line('Ana', 'Eu não gosto de livros, mas gosto de cantar.'),
            ], [
                Kit::question('What does Rui like?', ['Movies and books', 'Football and music', 'Books only'], 'Movies and books'),
                Kit::question('What does Ana not like?', ['Books', 'Movies', 'Singing'], 'Books'),
                Kit::question('What does Ana like to do?', ['Sing', 'Read', 'Dance'], 'Sing'),
            ], [
                Kit::question('Who asks the question?', ['Ana', 'Rui', 'Nobody'], 'Ana'),
                Kit::question('Does Rui like movies?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o filme', 'filmes'), Kit::word('o livro', 'livros'), Kit::word('cantar')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.desporto', 'Read the conversation.', [
                Kit::line('Marta', 'João, gostas de desporto?'),
                Kit::line('João', 'Sim, gosto de jogar futebol.'),
                Kit::line('Marta', 'Não gosto de futebol, mas gosto de ler.'),
            ], [
                Kit::question('What does João like to play?', ['Football', 'Music', 'Nothing'], 'Football'),
                Kit::question('What does Marta like to do?', ['Read', 'Play football', 'Sing'], 'Read'),
            ], [Kit::word('o desporto', 'desporto'), Kit::word('o futebol', 'futebol'), Kit::word('jogar'), Kit::word('ler')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.livros-filmes', 'De que gostas, de livros ou de filmes?', 'What do you like, books or movies?', [['gosto'], ['livros', 'filmes']], 'Gosto de filmes.', [Kit::word('o livro', 'livros'), Kit::word('o filme', 'filmes')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.desporto', 'De que desporto gostas?', 'What sport do you like?', [['gosto'], ['futebol', 'desporto', 'desportos', 'jogar']], 'Gosto de futebol.', [Kit::word('o futebol', 'futebol'), Kit::word('o desporto', 'desporto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ler-dancar', 'De que gostas, de ler ou de dançar?', 'What do you like, reading or dancing?', [['gosto'], ['ler', 'dançar']], 'Gosto de ler.', [Kit::word('ler'), Kit::word('dançar')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.musica-cantar-dancar', 'I like music, singing and dancing.', ['Gosto de música, de cantar e de dançar.', 'Gosto de música, cantar e dançar.', 'Gosto de música, de cantar e dançar.'], [Kit::word('a música', 'música'), Kit::word('cantar'), Kit::word('dançar'), Kit::form('gosto de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.joao-futebol', 'João, do you like to play football in your free time? (informal you)', ['João, gostas de jogar futebol no teu tempo livre?', 'Gostas de jogar futebol no teu tempo livre, João?', 'João, gostas de jogar ao futebol no teu tempo livre?', 'Gostas de jogar ao futebol no teu tempo livre, João?'], [Kit::word('jogar'), Kit::word('o futebol', 'futebol'), Kit::word('o tempo livre', 'tempo livre'), Kit::form('gostas de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.desportos-livros', 'I like sports, but I do not like books.', ['Gosto de desportos, mas não gosto de livros.', 'Gosto de desporto, mas não gosto de livros.', 'Gosto dos desportos, mas não gosto dos livros.'], [Kit::word('o desporto', 'desportos', ['desporto']), Kit::word('o livro', 'livros'), Kit::form('gosto de', true, ['gosto dos'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ler-filmes', 'I like to read, but I do not like movies.', ['Gosto de ler, mas não gosto de filmes.', 'Gosto de ler, mas não gosto dos filmes.'], [Kit::word('ler'), Kit::word('o filme', 'filmes'), Kit::form('gosto de', true, ['gosto dos'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.rui-musica', 'Rui, gostas da ___?', 'Rui, do you like the music?', 'música', Kit::word('a música', 'música'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.rui-jogar', 'Rui, gostas de ___ futebol?', 'Rui, do you like to play football?', 'jogar', Kit::word('jogar'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.tempo-livre', 'No meu tempo livre gosto de cantar e ler.', 'In my free time I like to sing and to read.', [Kit::word('o tempo livre', 'tempo livre'), Kit::word('cantar'), Kit::word('ler')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.desporto', 'O meu desporto é o futebol e gosto da música.', 'My sport is football and I like the music.', [Kit::word('o desporto', 'desporto'), Kit::word('o futebol', 'futebol'), Kit::word('a música', 'música'), Kit::form('gosto da')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.livros', 'Gosto dos livros, dos filmes e dos desportos.', 'I like the books, the movies and the sports.', [Kit::word('o livro', 'livros'), Kit::word('o filme', 'filmes'), Kit::word('o desporto', 'desportos'), Kit::form('gosto dos')], 'dictation', $set),
        ];
    }
}
