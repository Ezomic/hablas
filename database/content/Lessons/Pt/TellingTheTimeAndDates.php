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

final class TellingTheTimeAndDates implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'telling-the-time-and-dates';
    }

    public function words(): array
    {
        return [
            new WordData('a hora', cue: 'time (on the clock) or hour', forms: ['horas'], note: 'A hora is the time of day on the clock, and also one hour. To ask the time you always use the plural: Que horas são?'),
            new WordData('o relógio', cue: 'clock or watch', portunolSlips: ['reloj'], note: 'O relógio is both a wall clock and a watch.'),
            new WordData('o dia', cue: 'day', forms: ['dias'], note: 'Dia ends in -a but is masculine: o dia.'),
            new WordData('a semana', cue: 'week'),
            new WordData('hoje', cue: 'today', portunolSlips: ['hoy']),
            new WordData('amanhã', cue: 'tomorrow (not the morning)', portunolSlips: ['mañana'], note: 'Amanhã on its own means tomorrow. Written as two words, a manhã means the morning.'),
            new WordData('a segunda-feira', cue: 'Monday', portunolSlips: ['lunes'], note: 'Days of the week have no capital letter in Portuguese. People often say just segunda.'),
            new WordData('o domingo', cue: 'Sunday', note: 'Days of the week have no capital letter in Portuguese.'),
            new WordData('em ponto', cue: 'on the dot (exactly, for the hour)', portunolSlips: ['en punto']),
            new WordData('e meia', cue: 'half past', portunolSlips: ['y media']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'É uma hora. São duas horas.', 'english' => 'It is one o\'clock. It is two o\'clock.'],
            ['text' => 'Vou às três.', 'english' => 'I am going at three.'],
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
            Kit::gap($stage, 'sentences.choose_gap.e-uma', '___ uma hora.', ['É', 'São', 'Sou'], 'É', Kit::form('É', true), 'Uma is just one hour, so the time uses é. Sou means I am.', 'choose', 'It is one o\'clock.', ['Sou' => 'I am']),
            Kit::gap($stage, 'sentences.choose_gap.meia', 'São três e ___.', ['meia', 'hora', 'dia'], 'meia', Kit::word('e meia', 'meia'), 'E meia means half past. Hora and dia are not used to add half an hour.', 'choose', 'It is half past three.'),
            Kit::gap($stage, 'sentences.choose_gap.segunda', 'Hoje é ___.', ['segunda-feira', 'semana', 'relógio'], 'segunda-feira', Kit::word('a segunda-feira', 'segunda-feira'), 'Segunda-feira is a day of the week. Semana and relógio are not days.', 'choose', 'Today is Monday.'),
            Kit::gap($stage, 'sentences.choose_gap.sao-duas', '___ duas horas.', ['São', 'É', 'Sou'], 'São', Kit::form('São', true), 'Duas horas is plural, so the time uses são. Sou means I am.', 'choose', 'It is two o\'clock.', ['Sou' => 'I am']),
            Kit::gap($stage, 'sentences.choose_gap.as-cinco', 'Vou ___ cinco.', ['às', 'à', 'ao'], 'às', Kit::form('às', true), 'To say at five, use às with a plural hour. À is only for one o\'clock.', 'choose', 'I go at five.'),
            Kit::gap($stage, 'sentences.choose_gap.amanha', 'Hoje é domingo. ___ é segunda-feira.', ['Amanhã', 'Semana', 'Hora'], 'Amanhã', Kit::word('amanhã'), 'Amanhã means tomorrow, the day after today. Semana and hora are not days.', 'choose', 'Today is Sunday. Tomorrow is Monday.'),

            Kit::typeGap($stage, 'sentences.type_gap.ponto', 'São quatro horas em ___.', 'It is four o\'clock on the dot.', 'ponto', Kit::word('em ponto', 'ponto')),
            Kit::typeGap($stage, 'sentences.type_gap.sao-seis', '___ onze horas.', 'It is eleven o\'clock.', 'São', Kit::form('São', true), 'Seis horas is plural, so the time uses são.'),
            Kit::typeGap($stage, 'sentences.type_gap.horas', 'Ana, que ___ são?', 'Ana, what time is it?', 'horas', Kit::word('a hora', 'horas')),
            Kit::typeGap($stage, 'sentences.type_gap.relogio', 'O meu ___ está aqui.', 'My watch is here.', 'relógio', Kit::word('o relógio', 'relógio')),
            Kit::typeGap($stage, 'sentences.type_gap.dia', 'Que ___ é hoje? É domingo.', 'What day is it today? It is Sunday.', 'dia', Kit::word('o dia', 'dia')),

            Kit::translate($stage, 'sentences.translate.uma-em-ponto', 'It is exactly one o\'clock.', ['É uma hora em ponto.', 'É uma em ponto.'], [Kit::word('em ponto'), Kit::form('É')]),
            Kit::translate($stage, 'sentences.translate.horas', 'What time is it?', ['Que horas são?'], [Kit::word('a hora', 'horas')]),
            Kit::translate($stage, 'sentences.translate.duas-meia', 'It is half past two.', ['São duas e meia.', 'São duas horas e meia.'], [Kit::word('e meia'), Kit::form('São')]),

            Kit::build($stage, 'sentences.build.vou-seis', 'I go at six.', 'Vou às seis.', ['à'], [Kit::form('às')]),
            Kit::build($stage, 'sentences.build.amanha-domingo', 'Tomorrow is Sunday.', 'Amanhã é domingo.', ['são'], [Kit::word('amanhã'), Kit::word('o domingo', 'domingo'), Kit::form('é')]),
            Kit::build($stage, 'sentences.build.sete-em-ponto', 'It is seven o\'clock on the dot.', 'São sete horas em ponto.', ['é'], [Kit::word('em ponto'), Kit::form('São')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.uma-meia', 'É uma e meia.', ['It is half past one.', 'It is one o\'clock.', 'It is half past two.', 'It is two o\'clock.'], 'It is half past one.', [Kit::word('e meia'), Kit::form('É', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hoje-domingo', 'Hoje é domingo.', ['Today is Sunday.', 'Tomorrow is Sunday.', 'Today is Monday.', 'Tomorrow is Monday.'], 'Today is Sunday.', [Kit::word('hoje'), Kit::word('o domingo', 'domingo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.semana', 'Há sete dias numa semana.', ['There are seven days in a week.', 'There are five days in a week.', 'There are seven weeks in a day.', 'There are seven hours in a day.'], 'There are seven days in a week.', [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias')]),
            Kit::listenType($stage, 'sentences.listen_type.relogio', 'O meu relógio está aqui, Ana.', 'My watch is here, Ana.', [Kit::word('o relógio', 'relógio')]),
            Kit::listenType($stage, 'sentences.listen_type.nove-meia', 'São nove e meia.', 'It is half past nine.', [Kit::word('e meia'), Kit::form('São')]),
            Kit::listenType($stage, 'sentences.listen_type.dia-hoje', 'Que dia é hoje, Ana?', 'What day is it today, Ana?', [Kit::word('o dia', 'dia'), Kit::word('hoje')]),
            Kit::listenType($stage, 'sentences.listen_type.horas-uma', 'Que horas são? É uma hora.', 'What time is it? It is one o\'clock.', [Kit::word('a hora', 'horas'), Kit::form('É')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.cinco-meia', 'São cinco e meia.', 'It is half past five.', [Kit::word('e meia'), Kit::form('São')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hoje-amanha', 'Hoje é domingo. Amanhã é segunda-feira.', 'Today is Sunday. Tomorrow is Monday.', [Kit::word('hoje'), Kit::word('amanhã'), Kit::word('o domingo', 'domingo'), Kit::word('a segunda-feira', 'segunda-feira')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.oito-em-ponto', 'Vou às oito em ponto.', 'I go at eight on the dot.', [Kit::word('em ponto'), Kit::form('às')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hora-relogio', 'Que horas são no teu relógio?', 'What time is it on your watch?', [Kit::word('a hora', 'horas'), Kit::word('o relógio', 'relógio')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.uma-meia', 'É uma e meia?', 'Is it half past one?', [['sim', 'não', 'é', 'são'], ['meia', 'uma', 'hora']], 'Sim, é uma e meia.', [Kit::word('e meia')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.amanha', 'Hoje é domingo. Que dia é amanhã?', 'Today is Sunday. What day is tomorrow?', [['amanhã', 'é'], ['segunda-feira', 'segunda']], 'Amanhã é segunda-feira.', [Kit::word('amanhã'), Kit::word('a segunda-feira', 'segunda-feira')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.semana', 'A semana tem sete dias?', 'Does the week have seven days?', [['sim', 'não', 'tem'], ['semana', 'sete', 'dias']], 'Sim, a semana tem sete dias.', [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.hora', 'Read the conversation.', [
                Kit::line('Ana', 'Rui, que horas são?'),
                Kit::line('Rui', 'São duas e meia.'),
                Kit::line('Ana', 'Hoje vou às três. Amanhã vou às cinco.'),
                Kit::line('Rui', 'Adeus, Ana.'),
            ], [
                Kit::question('What time is it?', ['Half past two', 'Two o\'clock', 'Half past three'], 'Half past two'),
                Kit::question('At what time does Ana go today?', ['At three', 'At five', 'At two'], 'At three'),
                Kit::question('At what time does Ana go tomorrow?', ['At five', 'At three', 'At two'], 'At five'),
            ], [Kit::word('a hora', 'horas'), Kit::word('e meia'), Kit::word('hoje'), Kit::word('amanhã')], 'read'),
            Kit::gap($stage, 'task.choose_gap.dias', 'Uma semana tem sete ___.', ['dias', 'hora', 'relógio'], 'dias', Kit::word('o dia', 'dias'), 'Sete means more than one, so we need the plural: dias.', 'read', 'A week has seven days.'),
            Kit::gap($stage, 'task.choose_gap.em-ponto', 'São cinco horas ___ ponto.', ['em', 'de', 'com'], 'em', Kit::word('em ponto', 'em'), 'Em ponto means on the dot, exactly. The words de and com do not fit here.', 'read', 'It is five o\'clock on the dot.'),

            Kit::transform($stage, 'task.transform.tres', 'Change it to three o\'clock on the dot.', 'São duas horas em ponto.', ['São três horas em ponto.', 'São três em ponto.'], [Kit::word('em ponto'), Kit::form('São')]),
            Kit::transform($stage, 'task.transform.uma-meia', 'Change it to half past one.', 'São três e meia.', ['É uma e meia.', 'É uma hora e meia.'], [Kit::word('e meia'), Kit::form('É')]),
            Kit::transform($stage, 'task.transform.amanha', 'Say it for tomorrow.', 'Hoje é segunda-feira.', ['Amanhã é segunda-feira.', 'Amanhã é segunda.'], [Kit::word('amanhã'), Kit::word('a segunda-feira', 'segunda-feira', ['segunda'])]),
            Kit::writeGuided($stage, 'task.write_guided.hoje', 'Say that today is Monday and that it is two o\'clock exactly.', ['hoje', 'segunda-feira', 'são', 'em ponto'], 'Hoje é segunda-feira. São duas horas em ponto.', [
                ['forms' => ['hoje'], 'term' => 'hoje'],
                ['forms' => ['segunda-feira', 'segunda'], 'term' => 'a segunda-feira'],
                ['forms' => ['ponto'], 'term' => 'em ponto'],
            ], [Kit::word('hoje'), Kit::word('a segunda-feira', 'segunda-feira'), Kit::word('em ponto')]),
            Kit::writeGuided($stage, 'task.write_guided.relogio', 'Say that your watch is here and that it is half past four.', ['relógio', 'aqui', 'são', 'e meia'], 'O meu relógio está aqui. São quatro e meia.', [
                ['forms' => ['relógio'], 'term' => 'o relógio'],
                ['forms' => ['meia'], 'term' => 'e meia'],
            ], [Kit::word('o relógio', 'relógio'), Kit::word('e meia')]),
            Kit::build($stage, 'task.build.amanha-meia', 'Tomorrow I go at half past nine.', 'Amanhã vou às nove e meia.', ['à', 'é'], [Kit::word('amanhã'), Kit::word('e meia'), Kit::form('às')], 'write'),
            Kit::build($stage, 'task.build.sete-dias', 'There are seven days in the week.', 'Há sete dias na semana.', ['têm', 'são'], [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias')], 'write'),
            Kit::build($stage, 'task.build.hora-marta', 'What time is it, Marta?', 'Que horas são, Marta?', ['é', 'uma'], [Kit::word('a hora', 'horas'), Kit::form('são')], 'write'),
            Kit::translate($stage, 'task.translate.dias-semana', 'How many days does the week have?', ['Quantos dias tem a semana?', 'Quantos dias tem uma semana?'], [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias')], 'write'),
            Kit::translate($stage, 'task.translate.a-que-horas', 'At what time are you going, Rui? (informal you)', ['A que horas vais, Rui?', 'Rui, a que horas vais?', 'A que horas é que vais, Rui?'], [Kit::word('a hora', 'horas')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.dia', [
                Kit::line('Rui', 'Que dia é hoje, Ana?'),
                Kit::line('Ana', 'É domingo. Amanhã é segunda-feira.'),
                Kit::line('Rui', 'E a que horas vais?'),
                Kit::line('Ana', 'Vou às oito em ponto.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What day is tomorrow?', ['Monday', 'Sunday', 'Friday'], 'Monday'),
                Kit::question('At what time does Ana go?', ['At eight on the dot', 'At half past eight', 'At nine'], 'At eight on the dot'),
            ], [
                Kit::question('Who asks the questions?', ['Rui', 'Ana', 'Nobody'], 'Rui'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is tomorrow Monday?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('o dia', 'dia'), Kit::word('hoje'), Kit::word('amanhã'), Kit::word('o domingo', 'domingo'), Kit::word('a segunda-feira', 'segunda-feira'), Kit::word('em ponto'), Kit::word('a hora', 'horas')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.relogio', 'O relógio do Rui está aqui.', 'Rui\'s watch is here.', [Kit::word('o relógio', 'relógio')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.dez-meia', 'Hoje vou às dez e meia.', 'Today I go at half past ten.', [Kit::word('hoje'), Kit::word('e meia'), Kit::form('às')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.domingo', 'Hoje é domingo. Que horas são, Ana?', 'Today is Sunday. What time is it, Ana?', [Kit::word('hoje'), Kit::word('o domingo', 'domingo'), Kit::word('a hora', 'horas')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.uma-em-ponto', 'É uma hora em ponto?', 'Is it one o\'clock exactly?', [['sim', 'não', 'é', 'são'], ['ponto']], 'Sim, é uma hora em ponto.', [Kit::word('em ponto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.as-tres', 'A que horas vais hoje?', 'At what time are you going today? (informal you)', [['vou', 'hoje', 'às', 'à'], ['às', 'à']], 'Vou às três.', [Kit::form('às')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.hoje-segunda', 'Hoje é segunda-feira?', 'Is today Monday?', [['sim', 'não', 'é', 'hoje'], ['segunda-feira', 'segunda', 'domingo']], 'Não, hoje é domingo.', [Kit::word('hoje'), Kit::word('a segunda-feira', 'segunda-feira')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.relogio', 'Tens um relógio?', 'Do you have a watch? (informal you)', [['sim', 'não', 'tenho'], ['relógio']], 'Sim, tenho um relógio.', [Kit::word('o relógio', 'relógio')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tres-meia', 'Hoje vou às três e meia.', 'Today I go at half past three.', [Kit::word('hoje'), Kit::word('e meia'), Kit::form('às')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.dia', 'Que dia é hoje? É domingo.', 'What day is it today? It is Sunday.', [Kit::word('o dia', 'dia'), Kit::word('o domingo', 'domingo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.quatro-meia', 'It is half past four.', ['São quatro e meia.', 'São quatro horas e meia.'], [Kit::word('e meia'), Kit::form('São')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.amanha', 'Tomorrow is not Monday.', ['Amanhã não é segunda-feira.', 'Amanhã não é segunda.'], [Kit::word('amanhã'), Kit::word('a segunda-feira', 'segunda-feira', ['segunda']), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.semana', 'The week has seven days, Marta.', ['A semana tem sete dias, Marta.', 'Marta, a semana tem sete dias.'], [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nove', 'I go at nine on the dot.', ['Vou às nove em ponto.', 'Vou às nove horas em ponto.', 'Eu vou às nove em ponto.', 'Eu vou às nove horas em ponto.'], [Kit::word('em ponto'), Kit::form('às', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sete', 'Marta vai ___ sete.', 'Marta goes at seven.', 'às', Kit::form('às'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.uma-vinte', '___ uma e vinte.', 'It is twenty past one.', 'É', Kit::form('É', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.oito-meia', 'São oito e meia.', 'It is half past eight.', [Kit::word('e meia'), Kit::form('São')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.relogio', 'O relógio está aqui, Marta.', 'The watch is here, Marta.', [Kit::word('o relógio', 'relógio')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.hoje', 'Hoje é domingo. Que horas são, João?', 'Today is Sunday. What time is it, João?', [Kit::word('hoje'), Kit::word('o domingo', 'domingo'), Kit::word('a hora', 'horas')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.hora', [
                Kit::line('Ana', 'João, que horas são?'),
                Kit::line('João', 'São seis e meia.'),
                Kit::line('Ana', 'Amanhã vou às nove em ponto.'),
            ], [
                Kit::question('What time is it?', ['Half past six', 'Six o\'clock', 'Half past nine'], 'Half past six'),
                Kit::question('At what time does Ana go tomorrow?', ['At nine on the dot', 'At six', 'At half past nine'], 'At nine on the dot'),
                Kit::question('When does Ana go?', ['Tomorrow', 'Today', 'On Sunday'], 'Tomorrow'),
            ], [
                Kit::question('Who answers the question?', ['João', 'Ana', 'Nobody'], 'João'),
                Kit::question('How many people speak?', ['Two', 'Three', 'One'], 'Two'),
                Kit::question('Is it six o\'clock on the dot?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [Kit::word('a hora', 'horas'), Kit::word('e meia'), Kit::word('amanhã'), Kit::word('em ponto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.dia', 'Read the conversation.', [
                Kit::line('Marta', 'João, que dia é hoje?'),
                Kit::line('João', 'Hoje é domingo e amanhã é segunda-feira.'),
                Kit::line('Marta', 'Obrigada. E que horas são?'),
                Kit::line('João', 'São cinco horas.'),
            ], [
                Kit::question('What day is it today?', ['Sunday', 'Monday', 'Friday'], 'Sunday'),
                Kit::question('What does Marta ask at the end?', ['What time it is', 'What day it is', 'Where the watch is'], 'What time it is'),
            ], [Kit::word('o dia', 'dia'), Kit::word('hoje'), Kit::word('o domingo', 'domingo'), Kit::word('a segunda-feira', 'segunda-feira'), Kit::word('a hora', 'horas')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.relogio', 'Onde está o teu relógio?', 'Where is your watch?', [['relógio', 'está'], ['aqui', 'ali']], 'O meu relógio está aqui.', [Kit::word('o relógio', 'relógio')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.hoje', 'Amanhã é segunda-feira. Que dia é hoje?', 'Tomorrow is Monday. What day is it today?', [['hoje', 'é'], ['domingo']], 'Hoje é domingo.', [Kit::word('hoje'), Kit::word('o domingo', 'domingo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.semana', 'Quantos dias tem uma semana?', 'How many days does a week have?', [['semana', 'tem', 'sete', 'dias'], ['sete']], 'Uma semana tem sete dias.', [Kit::word('a semana', 'semana')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.onze', 'It is eleven o\'clock on the dot on my watch.', ['São onze horas em ponto no meu relógio.', 'No meu relógio são onze horas em ponto.'], [Kit::word('em ponto'), Kit::word('o relógio', 'relógio'), Kit::form('São')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hora', 'What time is it on the watch?', ['Que horas são no relógio?', 'No relógio, que horas são?'], [Kit::word('a hora', 'horas'), Kit::word('o relógio', 'relógio'), Kit::form('são', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.amanha', 'Tomorrow is not Sunday.', ['Amanhã não é domingo.'], [Kit::word('amanhã'), Kit::word('o domingo', 'domingo'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.semana', 'The week has seven days and today is Monday.', ['A semana tem sete dias e hoje é segunda-feira.', 'A semana tem sete dias e hoje é segunda.'], [Kit::word('a semana', 'semana'), Kit::word('o dia', 'dias'), Kit::word('hoje'), Kit::word('a segunda-feira', 'segunda-feira', ['segunda'])], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.semana', 'João, a ___ tem sete dias.', 'João, the week has seven days.', 'semana', Kit::word('a semana', 'semana'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.hora', 'João, que ___ são no teu relógio?', 'João, what time is it on your watch?', 'horas', Kit::word('a hora', 'horas'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.doze-meia', 'Amanhã vou às doze e meia.', 'Tomorrow I go at half past twelve.', [Kit::word('amanhã'), Kit::word('e meia'), Kit::form('às', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.segunda', 'Que dia é hoje, segunda-feira ou domingo?', 'What day is it today, Monday or Sunday?', [Kit::word('o dia', 'dia'), Kit::word('hoje'), Kit::word('a segunda-feira', 'segunda-feira'), Kit::word('o domingo', 'domingo'), Kit::form('é')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.doze', 'São doze horas em ponto ou doze e meia?', 'Is it twelve o\'clock on the dot or half past twelve?', [Kit::word('em ponto'), Kit::word('e meia'), Kit::form('São', true)], 'dictation', $set),
        ];
    }
}
