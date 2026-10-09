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

final class OpinionsAndAgreeing implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'opinions-and-agreeing';
    }

    public function words(): array
    {
        return [
            new WordData('la opinión', cue: 'opinion', note: 'En mi opinión means in my opinion. Opinión has an accent and is feminine: la opinión.'),
            new WordData('la razón', cue: 'reason (tener razón = to be right)', note: 'To be right is tener razón, with tener, like Dutch gelijk hebben. Never ser razón.'),
            new WordData('la verdad', cue: 'truth', note: 'Es verdad means it is true. No es verdad means it is not true.'),
            new WordData('creer', cue: 'to believe', forms: ['creo', 'crees', 'cree', 'creemos', 'creen'], note: 'Creer means to believe, and creo que is also a way to say I think. Creer a una persona is to believe a person.'),
            new WordData('pensar', cue: 'to think', forms: ['pienso', 'piensas', 'piensa', 'pensamos', 'piensan'], note: 'Pensar changes its stem: pienso, piensas, piensa, pensamos, piensan. Pensar que gives an opinion, pensar en is to think about something.'),
            new WordData('parecer', cue: 'to seem', forms: ['parece', 'parecen'], note: 'Me parece que means it seems to me that, a soft way to give an opinion. La película parece interesante means the film seems interesting.'),
            new WordData('interesante', cue: 'interesting'),
            new WordData('aburrido', cue: 'boring', forms: ['aburrida'], note: 'Aburrido ends in -o for a masculine word and in -a for a feminine one: el libro es aburrido, la película es aburrida.'),
            new WordData('divertido', cue: 'fun', forms: ['divertida'], note: 'Divertido ends in -o for a masculine word and in -a for a feminine one: el museo es divertido, la fiesta es divertida.'),
            new WordData('importante', cue: 'important'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Creo que el libro es interesante.', 'english' => 'I think the book is interesting.'],
            ['text' => 'Estoy de acuerdo porque es verdad.', 'english' => 'I agree because it is true.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-09', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-09', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-09', 'Released on the owner\'s instruction on 2026-10-09, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.creo-que', '___ la película es interesante.', ['Creo que', 'Creo de', 'Creo'], 'Creo que', Kit::form('creo que', true), 'An opinion starts with creo que. The que always stays: English can say I think it is good, but Spanish cannot drop it.', 'choose', 'I think the film is interesting.'),
            Kit::gap($stage, 'sentences.choose_gap.pienso-que', '___ el libro es aburrido.', ['Pienso que', 'Pienso de', 'Pienso'], 'Pienso que', Kit::form('pienso que', true), 'Pienso que works like creo que: the que links the opinion to the verb. Pienso de or pienso alone does not give an opinion.', 'choose', 'I think the book is boring.'),
            Kit::gap($stage, 'sentences.choose_gap.me-parece', '___ la fiesta es divertida.', ['Me parece que', 'Mi parece que', 'Me parece'], 'Me parece que', Kit::form('me parece que', true), 'Me parece que means it seems to me that. It is me, not mi (my), and the que stays.', 'choose', 'It seems to me that the party is fun.'),
            Kit::gap($stage, 'sentences.choose_gap.acuerdo', '___ con Pablo.', ['Estoy de acuerdo', 'Soy de acuerdo', 'Estoy acuerdo'], 'Estoy de acuerdo', Kit::form('estoy de acuerdo', true), 'To agree you say estoy de acuerdo, with estar and de. Dutch ik ben het eens uses zijn, but Spanish never says soy de acuerdo.', 'choose', 'I agree with Pablo.'),
            Kit::gap($stage, 'sentences.choose_gap.razon', 'Ana tiene ___.', ['razón', 'verdad', 'opinión'], 'razón', Kit::word('la razón', 'razón'), 'To be right is tener razón. Verdad is the truth and opinión is an opinion.', 'choose', 'Ana is right.'),
            Kit::gap($stage, 'sentences.choose_gap.verdad', 'No es ___.', ['verdad', 'razón', 'opinión'], 'verdad', Kit::word('la verdad', 'verdad'), 'It is true is es verdad. Razón goes with tener: tiene razón, not es razón.', 'choose', 'It is not true.'),

            Kit::typeGap($stage, 'sentences.type_gap.porque', 'Es divertido ___ es interesante.', 'It is fun because it is interesting.', 'porque', Kit::form('porque', true), 'Because is porque, one word without an accent. Por qué in two words, with an accent, is the question why.'),
            Kit::typeGap($stage, 'sentences.type_gap.opinion', 'Mi ___ es importante.', 'My opinion is important.', 'opinión', Kit::word('la opinión', 'opinión')),
            Kit::typeGap($stage, 'sentences.type_gap.cree', 'Ana no ___ a Pablo.', 'Ana does not believe Pablo.', 'cree', Kit::word('creer', 'cree')),
            Kit::typeGap($stage, 'sentences.type_gap.piensa', 'Luis ___ en la fiesta.', 'Luis is thinking about the party.', 'piensa', Kit::word('pensar', 'piensa')),
            Kit::typeGap($stage, 'sentences.type_gap.parece', 'La película me ___ interesante.', 'The film seems interesting to me.', 'parece', Kit::word('parecer', 'parece')),

            Kit::translate($stage, 'sentences.translate.libro', 'I think the book is boring.', ['Creo que el libro es aburrido.', 'Yo creo que el libro es aburrido.', 'Pienso que el libro es aburrido.', 'Yo pienso que el libro es aburrido.', 'Me parece que el libro es aburrido.'], [Kit::word('aburrido'), Kit::form('creo que', false, ['pienso que', 'me parece que'])]),
            Kit::translate($stage, 'sentences.translate.importante', 'It is important. I agree.', ['Es importante. Estoy de acuerdo.', 'Es importante. Yo estoy de acuerdo.'], [Kit::word('importante'), Kit::form('estoy de acuerdo')]),
            Kit::translate($stage, 'sentences.translate.no-verdad', 'I do not agree, it is not true.', ['No estoy de acuerdo, no es verdad.', 'No estoy de acuerdo. No es verdad.', 'Yo no estoy de acuerdo. No es verdad.'], [Kit::word('la verdad', 'verdad'), Kit::form('no estoy de acuerdo', true)]),

            Kit::build($stage, 'sentences.build.porque', 'It is interesting because it is important.', 'Es interesante porque es importante.', ['por'], [Kit::word('interesante'), Kit::word('importante'), Kit::form('porque')]),
            Kit::build($stage, 'sentences.build.pienso', 'I think that the museum is interesting.', 'Pienso que el museo es interesante.', ['de'], [Kit::word('interesante'), Kit::form('pienso que')]),
            Kit::build($stage, 'sentences.build.razon', 'Ana is right.', 'Ana tiene razón.', ['verdad'], [Kit::word('la razón', 'razón')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.libro', 'Creo que el libro es divertido.', ['I think the book is fun.', 'I think the book is boring.', 'I think the film is fun.', 'I do not think the book is fun.'], 'I think the book is fun.', [Kit::word('divertido'), Kit::form('creo que')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.opinion', 'En mi opinión, no es verdad.', ['In my opinion, it is not true.', 'In my opinion, it is true.', 'In your opinion, it is not true.', 'In my opinion, you are not right.'], 'In my opinion, it is not true.', [Kit::word('la opinión', 'opinión'), Kit::word('la verdad', 'verdad')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.acuerdo', 'Estoy de acuerdo con Pablo.', ['I agree with Pablo.', 'I do not agree with Pablo.', 'Pablo agrees with me.', 'I agree with Ana.'], 'I agree with Pablo.', [Kit::form('estoy de acuerdo', true)]),
            Kit::listenType($stage, 'sentences.listen_type.cree', 'Ana cree que la fiesta es divertida.', 'Ana thinks the party is fun.', [Kit::word('creer', 'cree'), Kit::word('divertido', 'divertida'), Kit::form('creo que', false, ['cree que'])]),
            Kit::listenType($stage, 'sentences.listen_type.piensa', 'Luis piensa en el plan.', 'Luis is thinking about the plan.', [Kit::word('pensar', 'piensa')]),
            Kit::listenType($stage, 'sentences.listen_type.parece', 'Me parece que el libro es importante.', 'It seems to me that the book is important.', [Kit::word('parecer', 'parece'), Kit::word('importante'), Kit::form('me parece que')]),
            Kit::listenType($stage, 'sentences.listen_type.porque', 'La película es interesante porque es divertida.', 'The film is interesting because it is fun.', [Kit::word('interesante'), Kit::word('divertido', 'divertida'), Kit::form('porque')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.razon', 'Tienes razón, es verdad.', 'You are right, it is true.', [Kit::word('la razón', 'razón'), Kit::word('la verdad', 'verdad')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.opinion', 'En mi opinión, el fútbol es aburrido.', 'In my opinion, football is boring.', [Kit::word('la opinión', 'opinión'), Kit::word('aburrido')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.parece', 'Me parece que la fiesta es divertida.', 'It seems to me that the party is fun.', [Kit::word('parecer', 'parece'), Kit::word('divertido', 'divertida'), Kit::form('me parece que')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.creo', 'Creo que la música es interesante.', 'I think the music is interesting.', [Kit::word('creer', 'creo'), Kit::word('interesante'), Kit::form('creo que')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pelicula', '¿Qué piensas de la película?', 'What do you think of the film?', [['pienso', 'creo', 'parece'], ['interesante', 'aburrida', 'divertida']], 'Pienso que es interesante.', [Kit::word('pensar', 'pienso'), Kit::word('interesante')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.futbol', '¿Es aburrido el fútbol?', 'Is football boring?', [['sí', 'no'], ['aburrido', 'divertido', 'interesante', 'creo']], 'Sí, creo que es aburrido.', [Kit::word('aburrido'), Kit::word('divertido')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ana', '¿Estás de acuerdo con Ana?', 'Do you agree with Ana?', [['sí', 'no'], ['estoy', 'acuerdo']], 'Sí, estoy de acuerdo con Ana.', [Kit::form('estoy de acuerdo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.libro', 'Read the conversation about a book.', [
                Kit::line('Marta', 'Pablo, ¿qué piensas del libro?'),
                Kit::line('Pablo', 'Me parece que es aburrido. No es interesante.'),
                Kit::line('Marta', 'No estoy de acuerdo. En mi opinión, es muy divertido.'),
                Kit::line('Pablo', 'Creo que tienes razón. Es importante leer.'),
                Kit::line('Marta', 'Es verdad. Es un libro muy interesante.'),
            ], [
                Kit::question('What does Pablo think of the book at first?', ['It is boring', 'It is fun', 'It is important'], 'It is boring'),
                Kit::question('What is Marta\'s opinion of the book?', ['It is fun', 'It is boring', 'It is not true'], 'It is fun'),
                Kit::question('What does Pablo say in the end?', ['Marta is right', 'Marta is not right', 'The book is boring'], 'Marta is right'),
            ], [Kit::word('pensar', 'piensas'), Kit::word('parecer', 'parece'), Kit::word('aburrido'), Kit::word('interesante'), Kit::word('la opinión', 'opinión'), Kit::word('divertido'), Kit::word('creer', 'creo'), Kit::word('la razón', 'razón'), Kit::word('importante'), Kit::word('la verdad', 'verdad')], 'read'),
            Kit::gap($stage, 'task.choose_gap.porque', '¿Por qué? ___ es importante.', ['Porque', 'Por qué', 'Que'], 'Porque', Kit::form('porque', true), 'The answer to ¿por qué? is porque, one word without an accent. Por qué in two words, with an accent, is the question.', 'read', 'Why? Because it is important.'),
            Kit::gap($stage, 'task.choose_gap.piensan', 'Luis y Ana ___ que es importante.', ['piensan', 'piensa', 'pienso'], 'piensan', Kit::word('pensar', 'piensan'), 'Luis y Ana are two people, so the verb ends in -an: piensan. Piensa is for one person and pienso means I think.', 'read', 'Luis and Ana think that it is important.'),

            Kit::transform($stage, 'task.transform.no-acuerdo', 'Say that you do not agree with Marta.', 'Estoy de acuerdo con Marta.', ['No estoy de acuerdo con Marta.', 'Yo no estoy de acuerdo con Marta.'], [Kit::form('no estoy de acuerdo', true)]),
            Kit::transform($stage, 'task.transform.ana-cree', 'Say that Ana believes that it is true.', 'Creo que es verdad.', ['Ana cree que es verdad.'], [Kit::word('creer', 'cree'), Kit::word('la verdad', 'verdad'), Kit::form('creo que', false, ['cree que'])]),
            Kit::transform($stage, 'task.transform.mi-opinion', 'Say that in your opinion the plan is important.', 'El plan es importante.', ['En mi opinión, el plan es importante.', 'En mi opinión el plan es importante.'], [Kit::word('la opinión', 'opinión'), Kit::word('importante')]),
            Kit::writeGuided($stage, 'task.write_guided.pelicula', 'Say that in your opinion the film is boring because it is not interesting.', ['en mi opinión', 'la película es aburrida', 'porque no es interesante'], 'En mi opinión, la película es aburrida porque no es interesante.', [
                ['forms' => ['opinión'], 'term' => 'la opinión'],
                ['forms' => ['aburrida', 'aburrido'], 'term' => 'aburrido'],
                ['forms' => ['porque'], 'term' => null],
                ['forms' => ['interesante'], 'term' => 'interesante'],
            ], [Kit::word('la opinión', 'opinión'), Kit::word('aburrido', 'aburrida'), Kit::word('interesante'), Kit::form('porque')]),
            Kit::writeGuided($stage, 'task.write_guided.pablo', 'Say that Pablo is right, that it is true, and that you agree.', ['Pablo tiene razón', 'es verdad', 'estoy de acuerdo'], 'Pablo tiene razón, es verdad y estoy de acuerdo.', [
                ['forms' => ['razón'], 'term' => 'la razón'],
                ['forms' => ['verdad'], 'term' => 'la verdad'],
                ['forms' => ['acuerdo'], 'term' => null],
            ], [Kit::word('la razón', 'razón'), Kit::word('la verdad', 'verdad'), Kit::form('estoy de acuerdo')]),
            Kit::build($stage, 'task.build.no-creo', 'I do not believe Pablo, but Marta is right.', 'No creo a Pablo, pero Marta tiene razón.', ['cree', 'verdad'], [Kit::word('creer', 'creo'), Kit::word('la razón', 'razón')]),
            Kit::build($stage, 'task.build.me-parece', 'It seems to me that the museum is interesting.', 'Me parece que el museo es interesante.', ['mi', 'parecen'], [Kit::word('parecer', 'parece'), Kit::word('interesante'), Kit::form('me parece que')]),
            Kit::build($stage, 'task.build.cual', 'What is your opinion, Ana?', '¿Cuál es tu opinión, Ana?', ['mi', 'qué'], [Kit::word('la opinión', 'opinión')]),
            Kit::translate($stage, 'task.translate.marta', 'I agree with Marta because the book is interesting.', ['Estoy de acuerdo con Marta porque el libro es interesante.', 'Yo estoy de acuerdo con Marta porque el libro es interesante.'], [Kit::word('interesante'), Kit::form('estoy de acuerdo')]),
            Kit::translate($stage, 'task.translate.fiesta', 'In my opinion, the party is fun, but the museum is boring.', ['En mi opinión, la fiesta es divertida, pero el museo es aburrido.', 'En mi opinión la fiesta es divertida pero el museo es aburrido.'], [Kit::word('la opinión', 'opinión'), Kit::word('divertido', 'divertida'), Kit::word('aburrido')]),

            Kit::listenPassage($stage, 'task.listen_passage.plan', [
                Kit::line('Ana', 'Luis, ¿qué piensas del plan?'),
                Kit::line('Luis', 'Creo que es muy importante.'),
                Kit::line('Ana', 'Estoy de acuerdo, es verdad.'),
                Kit::line('Luis', 'Pero Pablo no está de acuerdo.'),
                Kit::line('Ana', 'En mi opinión, no tiene razón.'),
            ], [
                Kit::question('What does Luis think of the plan?', ['It is important', 'It is boring', 'It is fun'], 'It is important'),
                Kit::question('Does Ana agree with Luis?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who does not agree?', ['Pablo', 'Luis', 'Ana'], 'Pablo'),
            ], [
                Kit::question('Who asks the question?', ['Ana', 'Luis', 'Pablo'], 'Ana'),
                Kit::question('Does Ana think Pablo is right?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Who first says the plan is very important?', ['Luis', 'Ana', 'Pablo'], 'Luis'),
            ], [Kit::word('pensar', 'piensas'), Kit::word('importante'), Kit::word('la verdad', 'verdad'), Kit::word('la razón', 'razón'), Kit::word('la opinión', 'opinión'), Kit::form('estoy de acuerdo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta', 'Marta piensa que el museo es muy aburrido.', 'Marta thinks the museum is very boring.', [Kit::word('pensar', 'piensa'), Kit::word('aburrido'), Kit::form('pienso que', false, ['piensa que'])], 'listen'),
            Kit::listenType($stage, 'task.listen_type.verdad', 'Ana cree que la verdad es importante.', 'Ana thinks the truth is important.', [Kit::word('creer', 'cree'), Kit::word('la verdad', 'verdad'), Kit::word('importante')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.parece', 'La película parece divertida y es muy interesante.', 'The film seems fun and is very interesting.', [Kit::word('parecer', 'parece'), Kit::word('divertido', 'divertida'), Kit::word('interesante')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.futbol', '¿Qué piensas del fútbol?', 'What do you think of football?', [['creo', 'pienso', 'parece'], ['interesante', 'divertido', 'aburrido', 'importante']], 'Creo que es divertido.', [Kit::word('creer', 'creo'), Kit::word('divertido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ana', '¿Tiene razón Ana?', 'Is Ana right?', [['sí', 'no'], ['razón', 'verdad', 'acuerdo']], 'Sí, tiene razón.', [Kit::word('la razón', 'razón')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.museo', '¿Cuál es tu opinión del museo?', 'What is your opinion of the museum?', [['creo', 'pienso', 'parece', 'opinión'], ['interesante', 'aburrido', 'divertido', 'importante']], 'Me parece que es interesante.', [Kit::word('la opinión', 'opinión'), Kit::word('interesante'), Kit::form('me parece que')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.verdad', '¿Es importante la verdad?', 'Is the truth important?', [['sí', 'no'], ['importante', 'verdad', 'es']], 'Sí, la verdad es importante.', [Kit::word('importante'), Kit::word('la verdad', 'verdad')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.pienso', 'Pienso que es importante porque es verdad.', 'I think it is important because it is true.', [Kit::word('importante'), Kit::word('la verdad', 'verdad'), Kit::form('porque')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.razon', 'No estoy de acuerdo, pero tienes razón.', 'I do not agree, but you are right.', [Kit::word('la razón', 'razón'), Kit::form('no estoy de acuerdo', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.acuerdo', 'I agree, it is important and it is true.', ['Estoy de acuerdo, es importante y es verdad.', 'Yo estoy de acuerdo, es importante y es verdad.'], [Kit::word('importante'), Kit::word('la verdad', 'verdad'), Kit::form('estoy de acuerdo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.museo', 'I think the museum is fun, not boring.', ['Creo que el museo es divertido, no aburrido.', 'Yo creo que el museo es divertido, no aburrido.', 'Pienso que el museo es divertido, no aburrido.', 'Yo pienso que el museo es divertido, no aburrido.', 'Me parece que el museo es divertido, no aburrido.'], [Kit::word('divertido'), Kit::word('aburrido'), Kit::form('creo que', true, ['pienso que', 'me parece que'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.razon', 'Ana is right because it is true.', ['Ana tiene razón porque es verdad.', 'Ana tiene razón, porque es verdad.', 'Ana tiene razón porque es la verdad.'], [Kit::word('la razón', 'razón'), Kit::word('la verdad', 'verdad'), Kit::form('porque')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.parece', 'It seems to me that your opinion is interesting.', ['Me parece que tu opinión es interesante.'], [Kit::word('parecer', 'parece'), Kit::word('la opinión', 'opinión'), Kit::word('interesante'), Kit::form('me parece que')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.piensa', 'Pablo ___ en el plan.', 'Pablo is thinking about the plan.', 'piensa', Kit::word('pensar', 'piensa'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.opinion', 'En mi ___, el libro es aburrido.', 'In my opinion, the book is boring.', 'opinión', Kit::word('la opinión', 'opinión'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.musica', 'Creo que la música es muy interesante.', 'I think the music is very interesting.', [Kit::word('creer', 'creo'), Kit::word('interesante'), Kit::form('creo que')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta', 'Marta cree que Ana tiene razón. Es importante.', 'Marta thinks Ana is right. It is important.', [Kit::word('creer', 'cree'), Kit::word('la razón', 'razón'), Kit::word('importante')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pablo', 'Pablo piensa que la película parece divertida, no aburrida.', 'Pablo thinks the film seems fun, not boring.', [Kit::word('pensar', 'piensa'), Kit::word('parecer', 'parece'), Kit::word('divertido', 'divertida'), Kit::word('aburrido', 'aburrida'), Kit::form('pienso que', false, ['piensa que'])], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.libro', [
                Kit::line('Pablo', 'Ana, ¿cuál es tu opinión del libro?'),
                Kit::line('Ana', 'Pienso que es muy divertido.'),
                Kit::line('Pablo', 'Yo creo que es aburrido.'),
                Kit::line('Ana', '¿Por qué?'),
                Kit::line('Pablo', 'Porque no es interesante.'),
            ], [
                Kit::question('What does Ana think of the book?', ['It is fun', 'It is boring', 'It is important'], 'It is fun'),
                Kit::question('What does Pablo think of the book?', ['It is boring', 'It is fun', 'It is true'], 'It is boring'),
                Kit::question('Do they agree?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('Who asks for an opinion?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Does Pablo say the book is interesting?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Who thinks the book is fun?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
            ], [Kit::word('la opinión', 'opinión'), Kit::word('divertido'), Kit::word('aburrido'), Kit::word('interesante'), Kit::form('porque')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.museo', 'Read the conversation.', [
                Kit::line('Luis', 'Hoy hay un plan: el museo o el parque.'),
                Kit::line('Marta', 'Me parece que el museo es aburrido.'),
                Kit::line('Luis', 'Yo no estoy de acuerdo. Es interesante.'),
                Kit::line('Marta', 'Tienes razón. Es importante ver el museo.'),
            ], [
                Kit::question('What does Marta think of the museum at first?', ['It is boring', 'It is fun', 'It is important'], 'It is boring'),
                Kit::question('Who is right in the end?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
            ], [Kit::word('parecer', 'parece'), Kit::word('aburrido'), Kit::word('interesante'), Kit::word('la razón', 'razón'), Kit::word('importante')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.musica', '¿Qué piensas de la música?', 'What do you think of the music?', [['creo', 'pienso', 'parece'], ['música', 'interesante', 'divertida', 'importante', 'aburrida']], 'Pienso que la música es divertida.', [Kit::word('pensar', 'pienso'), Kit::word('divertido', 'divertida')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.opinion', '¿Cuál es tu opinión del fútbol?', 'What is your opinion of football?', [['opinión', 'creo', 'pienso', 'parece'], ['aburrido', 'divertido', 'interesante', 'importante']], 'Creo que el fútbol es divertido.', [Kit::word('la opinión', 'opinión'), Kit::word('creer', 'creo'), Kit::word('divertido')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.verdad', '¿Es verdad que Ana tiene razón?', 'Is it true that Ana is right?', [['sí', 'no'], ['verdad', 'razón', 'tiene']], 'Sí, es verdad.', [Kit::word('la verdad', 'verdad'), Kit::word('la razón', 'razón')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.museo', 'I think the museum is interesting, fun and not boring.', ['Creo que el museo es interesante, divertido y no aburrido.', 'Pienso que el museo es interesante, divertido y no aburrido.'], [Kit::word('interesante'), Kit::word('divertido'), Kit::word('aburrido'), Kit::form('creo que', true, ['pienso que', 'me parece que'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luis', 'I do not agree with Luis because he is not right.', ['No estoy de acuerdo con Luis porque no tiene razón.'], [Kit::word('la razón', 'razón'), Kit::form('no estoy de acuerdo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.verdad', 'It seems to me that the truth is important.', ['Me parece que la verdad es importante.'], [Kit::word('parecer', 'parece'), Kit::word('la verdad', 'verdad'), Kit::word('importante'), Kit::form('me parece que')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana', 'Ana thinks that my opinion is important.', ['Ana piensa que mi opinión es importante.', 'Ana cree que mi opinión es importante.'], [Kit::word('la opinión', 'opinión'), Kit::word('importante'), Kit::form('pienso que', false, ['piensa que', 'cree que'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.cree', 'Mi madre no ___ a Luis.', 'My mother does not believe Luis.', 'cree', Kit::word('creer', 'cree'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.piensan', 'Marta y Pablo ___ en la cena.', 'Marta and Pablo are thinking about the dinner.', 'piensan', Kit::word('pensar', 'piensan'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.acuerdo', 'Estoy de acuerdo, tienes razón, es verdad.', 'I agree, you are right, it is true.', [Kit::word('la razón', 'razón'), Kit::word('la verdad', 'verdad'), Kit::form('estoy de acuerdo')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.opinion', 'En mi opinión, es interesante porque parece divertido.', 'In my opinion, it is interesting because it seems fun.', [Kit::word('la opinión', 'opinión'), Kit::word('parecer', 'parece'), Kit::word('interesante'), Kit::form('porque')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.luis', 'Luis cree que es divertido, Ana piensa que es aburrido.', 'Luis thinks it is fun, Ana thinks it is boring.', [Kit::word('creer', 'cree'), Kit::word('pensar', 'piensa'), Kit::word('divertido'), Kit::word('aburrido')], 'dictation', $set),
        ];
    }
}
