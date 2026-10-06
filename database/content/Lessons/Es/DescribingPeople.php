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

final class DescribingPeople implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'describing-people';
    }

    public function words(): array
    {
        return [
            new WordData('alto', cue: 'tall (masculine)', forms: ['alta', 'altos', 'altas']),
            new WordData('bajo', cue: 'short, not tall (masculine)', forms: ['baja', 'bajos', 'bajas']),
            new WordData('simpático', cue: 'nice, friendly (masculine)', forms: ['simpática', 'simpáticos', 'simpáticas']),
            new WordData('joven', cue: 'young', forms: ['jóvenes'], note: 'Joven has the same form for men and women. The plural is jóvenes.'),
            new WordData('rubio', cue: 'blond (masculine)', forms: ['rubia', 'rubios', 'rubias']),
            new WordData('moreno', cue: 'dark-haired (masculine)', forms: ['morena', 'morenos', 'morenas']),
            new WordData('guapo', cue: 'good-looking (masculine)', forms: ['guapa', 'guapos', 'guapas']),
            new WordData('el pelo', cue: 'hair', note: 'El pelo is masculine and singular: el pelo rubio.'),
            new WordData('el ojo', cue: 'eye', forms: ['ojos']),
            new WordData('la persona', cue: 'person', forms: ['personas'], note: 'La persona is always feminine, also for a man: Luis es una persona simpática.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ana es alta y tiene el pelo rubio.', 'english' => 'Ana is tall and has blond hair.'],
            ['text' => 'Pablo es simpático y está bien.', 'english' => 'Pablo is nice and he is fine.'],
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
            Kit::gap($stage, 'sentences.choose_gap.ana-alta', 'Ana es ___.', ['alta', 'alto', 'altas'], 'alta', Kit::word('alto', 'alta'), 'Ana is one woman, so the adjective ends in -a and stays singular: alta.', 'choose', 'Ana is tall.'),
            Kit::gap($stage, 'sentences.choose_gap.luis-bajo', 'Luis es ___.', ['bajo', 'baja', 'bajos'], 'bajo', Kit::word('bajo'), 'Luis is one man, so the adjective ends in -o and stays singular: bajo.', 'choose', 'Luis is short.'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-pelo', 'Pablo ___ el pelo negro.', ['tiene', 'es'], 'tiene', Kit::form('tiene', true), 'You have hair and eyes, so Spanish uses tener: tiene. Es says what someone is, not what they have.', 'choose', 'Pablo has black hair.', ['negro' => 'black']),
            Kit::gap($stage, 'sentences.choose_gap.ana-ojos', 'Ana tiene los ___ azules.', ['ojos', 'ojo'], 'ojos', Kit::word('el ojo', 'ojos'), 'Los is plural, so the noun is plural too: ojos.', 'choose', 'Ana has blue eyes.', ['azules' => 'blue']),
            Kit::gap($stage, 'sentences.choose_gap.marta-persona', 'Marta es una ___ simpática.', ['persona', 'pelo', 'ojos'], 'persona', Kit::word('la persona', 'persona'), 'Una goes with a feminine singular noun, and a person is persona. Pelo is masculine and ojos is plural.', 'choose', 'Marta is a nice person.'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-bien', 'Pablo ___ bien.', ['está', 'es'], 'está', Kit::form('está', true), 'Está says how someone is at the moment. Es bien is not Spanish.', 'choose', 'Pablo is fine.'),

            Kit::typeGap($stage, 'sentences.type_gap.luis-alto', 'Luis ___ alto.', 'Luis is tall.', 'es', Kit::form('es'), 'Height is a lasting trait, so you use es.'),
            Kit::typeGap($stage, 'sentences.type_gap.marta-pelo', 'Marta ___ el pelo rubio.', 'Marta has blond hair.', 'tiene', Kit::form('tiene'), 'Hair is something you have, so you use tiene.'),
            Kit::typeGap($stage, 'sentences.type_gap.luis-rubio', 'Luis tiene el pelo ___.', 'Luis has blond hair.', 'rubio', Kit::word('rubio')),
            Kit::typeGap($stage, 'sentences.type_gap.pablo-luis-altos', 'Pablo y Luis ___ altos.', 'Pablo and Luis are tall.', 'son', Kit::form('son'), 'Two people need the plural of ser: son. Altos is plural as well.'),
            Kit::typeGap($stage, 'sentences.type_gap.ana-guapa', 'Ana es muy ___.', 'Ana is very good-looking.', 'guapa', Kit::word('guapo', 'guapa')),

            Kit::translate($stage, 'sentences.translate.ana-alta-rubia', 'Ana is tall and blond.', ['Ana es alta y rubia.', 'Ana es rubia y alta.'], [Kit::word('alto', 'alta'), Kit::word('rubio', 'rubia'), Kit::form('es')]),
            Kit::translate($stage, 'sentences.translate.luis-moreno', 'Luis is dark-haired.', ['Luis es moreno.', 'Luis tiene el pelo moreno.', 'Luis tiene pelo moreno.'], [Kit::word('moreno'), Kit::form('es', alternates: ['tiene'])]),
            Kit::translate($stage, 'sentences.translate.marta-joven', 'Marta is young and nice.', ['Marta es joven y simpática.', 'Marta es simpática y joven.'], [Kit::word('joven'), Kit::word('simpático', 'simpática'), Kit::form('es')]),

            Kit::build($stage, 'sentences.build.ana-ojos', 'Ana has blue eyes.', 'Ana tiene los ojos azules.', ['es'], [Kit::word('el ojo', 'ojos'), Kit::form('tiene')], 'write', ['azules' => 'blue']),
            Kit::build($stage, 'sentences.build.ana-muy-alta', 'Ana is very tall.', 'Ana es muy alta.', ['alto'], [Kit::word('alto', 'alta'), Kit::form('es')]),
            Kit::build($stage, 'sentences.build.luis-pelo', 'Luis has blond hair.', 'Luis tiene el pelo rubio.', ['es'], [Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::form('tiene')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.ana-alta-morena', 'Ana es alta y morena.', ['Ana is tall and dark-haired.', 'Ana is short and dark-haired.', 'Ana is tall and blond.', 'Ana is young and dark-haired.'], 'Ana is tall and dark-haired.', [Kit::word('alto', 'alta'), Kit::word('moreno', 'morena'), Kit::form('es')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.luis-pelo', 'Luis tiene el pelo rubio.', ['Luis has blond hair.', 'Luis has dark hair.', 'Ana has blond hair.', 'Luis has blond eyes.'], 'Luis has blond hair.', [Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::form('tiene')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pablo-joven', 'Pablo es joven y guapo.', ['Pablo is young and good-looking.', 'Pablo is old and good-looking.', 'Pablo is young and tall.', 'Pablo is nice and good-looking.'], 'Pablo is young and good-looking.', [Kit::word('joven'), Kit::word('guapo'), Kit::form('es')]),
            Kit::listenType($stage, 'sentences.listen_type.luis-ojos', 'Luis tiene los ojos de Ana.', 'Luis has Ana\'s eyes.', [Kit::word('el ojo', 'ojos'), Kit::form('tiene')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-persona', 'Ana es una persona muy simpática.', 'Ana is a very nice person.', [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática'), Kit::form('es')]),
            Kit::listenType($stage, 'sentences.listen_type.pablo-bajo', 'Pablo no es bajo, es alto.', 'Pablo is not short, he is tall.', [Kit::word('bajo'), Kit::word('alto'), Kit::form('es')]),
            Kit::listenType($stage, 'sentences.listen_type.marta-ana-jovenes', 'Marta y Ana son jóvenes.', 'Marta and Ana are young.', [Kit::word('joven', 'jóvenes'), Kit::form('son')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.marta-alta-guapa', 'Marta es alta y guapa.', 'Marta is tall and good-looking.', [Kit::word('alto', 'alta'), Kit::word('guapo', 'guapa'), Kit::form('es')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pablo-pelo', 'Pablo tiene el pelo moreno.', 'Pablo has dark hair.', [Kit::word('el pelo', 'pelo'), Kit::word('moreno'), Kit::form('tiene')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.marta-ojos', 'Marta tiene los ojos de Pablo.', 'Marta has Pablo\'s eyes.', [Kit::word('el ojo', 'ojos'), Kit::form('tiene')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pablo-persona', 'Pablo es una persona simpática.', 'Pablo is a nice person.', [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática'), Kit::form('es')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.eres-alto', '¿Eres alto?', 'Are you tall?', [['sí', 'no'], ['alto', 'alta', 'bajo', 'baja']], 'Sí, soy alto.', [Kit::word('alto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-es-ana', '¿Cómo es Ana?', 'What is Ana like?', [['es', 'tiene'], ['alta', 'baja', 'rubia', 'morena', 'guapa', 'joven', 'simpática', 'pelo']], 'Es alta y rubia.', [Kit::word('rubio', 'rubia'), Kit::form('es')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pelo-rubio', '¿Tienes el pelo rubio?', 'Do you have blond hair?', [['sí', 'no'], ['rubio', 'rubia', 'moreno', 'morena']], 'Sí, tengo el pelo rubio.', [Kit::word('el pelo', 'pelo'), Kit::word('rubio')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.como-es-pablo', 'Read the conversation about Pablo and Luis.', [
                Kit::line('Marta', 'Ana, ¿cómo es Pablo?'),
                Kit::line('Ana', 'Es alto y moreno. Tiene los ojos azules.'),
                Kit::line('Marta', '¿Es simpático?'),
                Kit::line('Ana', 'Sí, es una persona muy simpática.'),
                Kit::line('Marta', '¿Y Luis?'),
                Kit::line('Ana', 'Luis es bajo y rubio. Es joven y muy guapo.'),
            ], [
                Kit::question('What does Pablo look like?', ['Tall and dark-haired', 'Short and blond', 'Tall and blond'], 'Tall and dark-haired'),
                Kit::question('Is Pablo nice?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Who is blond?', ['Luis', 'Pablo', 'Both of them'], 'Luis'),
            ], [Kit::word('alto'), Kit::word('moreno'), Kit::word('el ojo', 'ojos'), Kit::word('simpático'), Kit::word('la persona', 'persona'), Kit::word('bajo'), Kit::word('rubio'), Kit::word('joven'), Kit::word('guapo')], 'read', null, ['azules' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.como-es-pablo', 'Pablo ___ una persona simpática.', ['es', 'está'], 'es', Kit::form('es', true), 'A nice person is what he is like, a lasting trait, so you use es. Está does not go with a noun like persona.', 'read', 'Pablo is a nice person.'),
            Kit::gap($stage, 'task.choose_gap.ana-rubio', 'Ana tiene el pelo ___ y es muy guapa.', ['rubio', 'rubia'], 'rubio', Kit::word('rubio'), 'El pelo is masculine, so the colour is rubio. Rubia would describe Ana herself: Ana es rubia.', 'read', 'Ana has blond hair and is very good-looking.'),

            Kit::transform($stage, 'task.transform.marta-baja-morena', 'Change it to talk about Marta.', 'Pablo es bajo y moreno.', ['Marta es baja y morena.', 'Marta es morena y baja.'], [Kit::word('bajo', 'baja'), Kit::word('moreno', 'morena'), Kit::form('es')]),
            Kit::transform($stage, 'task.transform.jovenes-simpaticos', 'Change it to talk about Luis and Pablo.', 'Ana es joven y simpática.', ['Luis y Pablo son jóvenes y simpáticos.', 'Pablo y Luis son jóvenes y simpáticos.', 'Luis y Pablo son simpáticos y jóvenes.', 'Pablo y Luis son simpáticos y jóvenes.'], [Kit::word('joven', 'jóvenes'), Kit::word('simpático', 'simpáticos'), Kit::form('son')]),
            Kit::transform($stage, 'task.transform.ana-pelo', 'Say that Ana has blond hair.', 'Ana es rubia.', ['Ana tiene el pelo rubio.', 'Ana tiene pelo rubio.'], [Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::form('tiene', true)]),
            Kit::writeGuided($stage, 'task.write_guided.marta-alta', 'Say that Marta is tall and has blond hair.', ['es', 'alta', 'tiene', 'pelo', 'rubio'], 'Marta es alta y tiene el pelo rubio.', [
                ['forms' => ['es'], 'term' => null],
                ['forms' => ['alta'], 'term' => 'alto'],
                ['forms' => ['pelo'], 'term' => 'el pelo'],
                ['forms' => ['rubio'], 'term' => 'rubio'],
            ], [Kit::word('alto', 'alta'), Kit::word('el pelo', 'pelo'), Kit::word('rubio')]),
            Kit::writeGuided($stage, 'task.write_guided.pablo-ojos', 'Say that Pablo is a nice person and has blue eyes.', ['es', 'persona', 'simpática', 'tiene', 'ojos', 'azules'], 'Pablo es una persona simpática y tiene los ojos azules.', [
                ['forms' => ['es'], 'term' => null],
                ['forms' => ['persona'], 'term' => 'la persona'],
                ['forms' => ['simpática'], 'term' => 'simpático'],
                ['forms' => ['ojos'], 'term' => 'el ojo'],
            ], [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática'), Kit::word('el ojo', 'ojos')], ['azules' => 'blue']),
            Kit::build($stage, 'task.build.pablo-no-alto', 'Pablo is not tall.', 'Pablo no es alto.', ['tiene', 'alta'], [Kit::word('alto'), Kit::form('es')], 'write'),
            Kit::build($stage, 'task.build.luis-bajo-ana-alta', 'Luis is short, but Ana is tall.', 'Luis es bajo, pero Ana es alta.', ['tiene', 'ojos'], [Kit::word('bajo'), Kit::word('alto', 'alta'), Kit::form('es')], 'write'),
            Kit::build($stage, 'task.build.luis-moreno', 'Luis has dark hair.', 'Luis tiene el pelo moreno.', ['es', 'morena'], [Kit::word('moreno'), Kit::form('tiene')], 'write'),
            Kit::translate($stage, 'task.translate.ana-joven-no-rubia', 'Ana is young, but she is not blond.', ['Ana es joven, pero no es rubia.'], [Kit::word('joven'), Kit::word('rubio', 'rubia'), Kit::form('es')], 'write'),
            Kit::translate($stage, 'task.translate.pablo-alto-bajo', 'Is Pablo tall or short?', ['¿Pablo es alto o bajo?', '¿Es Pablo alto o bajo?', '¿Es alto o bajo Pablo?'], [Kit::word('alto'), Kit::word('bajo'), Kit::form('es')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.como-es-ana', [
                Kit::line('Luis', 'Marta, ¿cómo es Ana?'),
                Kit::line('Marta', 'Es joven y muy guapa. Tiene el pelo rubio.'),
                Kit::line('Luis', '¿Es alta?'),
                Kit::line('Marta', 'No, es baja. Pero es una persona muy simpática.'),
            ], [
                Kit::question('What does Ana look like?', ['Young, good-looking and blond', 'Tall and dark-haired', 'Short and dark-haired'], 'Young, good-looking and blond'),
                Kit::question('Is Ana tall?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What is Ana like as a person?', ['Nice', 'Not nice', 'The conversation does not say.'], 'Nice'),
            ], [
                Kit::question('Who asks about Ana?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
                Kit::question('Does Ana have blond hair?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('joven'), Kit::word('guapo', 'guapa'), Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::word('alto', 'alta'), Kit::word('bajo', 'baja'), Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-pelo-ojos', 'Ana tiene el pelo rubio y los ojos de Luis.', 'Ana has blond hair and Luis\'s eyes.', [Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::word('el ojo', 'ojos'), Kit::form('tiene')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.luis-joven-guapo', 'Luis es joven, moreno y muy guapo.', 'Luis is young, dark-haired and very good-looking.', [Kit::word('joven'), Kit::word('moreno'), Kit::word('guapo'), Kit::form('es')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.ana-esta-bien', 'Ana está bien, pero es muy baja.', 'Ana is fine, but she is very short.', [Kit::word('bajo', 'baja'), Kit::form('está', true)], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.como-eres', '¿Cómo eres?', 'What are you like?', [['soy', 'tengo'], ['alto', 'alta', 'bajo', 'baja', 'joven', 'rubio', 'rubia', 'moreno', 'morena', 'simpático', 'simpática', 'guapo', 'guapa', 'pelo', 'ojos']], 'Soy alto y simpático.', [Kit::word('alto'), Kit::word('simpático')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pelo-moreno', '¿Tienes el pelo moreno?', 'Do you have dark hair?', [['sí', 'no'], ['moreno', 'morena', 'rubio', 'rubia']], 'Sí, tengo el pelo moreno.', [Kit::word('el pelo', 'pelo'), Kit::word('moreno')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.marta-alta', '¿Es alta Marta?', 'Is Marta tall?', [['sí', 'no'], ['alta', 'baja']], 'No, es baja.', [Kit::word('bajo', 'baja')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.eres-joven', '¿Eres joven?', 'Are you young?', [['sí', 'no'], ['joven', 'jóvenes']], 'Sí, soy joven.', [Kit::word('joven')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.luis-bajo-pelo', 'Luis es bajo y tiene el pelo moreno.', 'Luis is short and has dark hair.', [Kit::word('bajo'), Kit::word('moreno'), Kit::form('tiene')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.marta-joven-guapa', 'Marta es joven y muy guapa.', 'Marta is young and very good-looking.', [Kit::word('joven'), Kit::word('guapo', 'guapa'), Kit::form('es')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.ana-alta-guapa', 'Ana is tall and good-looking.', ['Ana es alta y guapa.', 'Ana es guapa y alta.'], [Kit::word('alto', 'alta'), Kit::word('guapo', 'guapa'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-pelo', 'Pablo is tall and has blond hair.', ['Pablo es alto y tiene el pelo rubio.', 'Pablo es alto y tiene pelo rubio.'], [Kit::word('alto'), Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::form('tiene')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luis-persona', 'Luis is a young and nice person.', ['Luis es una persona joven y simpática.', 'Luis es una persona simpática y joven.'], [Kit::word('la persona', 'persona'), Kit::word('joven'), Kit::word('simpático', 'simpática'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-bien-baja', 'Marta is fine, but she is short.', ['Marta está bien, pero es baja.'], [Kit::word('bajo', 'baja'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pablo-luis-bajos', 'Pablo y Luis son ___.', 'Pablo and Luis are short.', 'bajos', Kit::word('bajo', 'bajos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ana-ojos', 'Ana tiene los ___ de Luis.', 'Ana has Luis\'s eyes.', 'ojos', Kit::word('el ojo', 'ojos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-alta-simpatica', 'Marta es alta y muy simpática.', 'Marta is tall and very nice.', [Kit::word('alto', 'alta'), Kit::word('simpático', 'simpática'), Kit::form('es')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pablo-joven-pelo', 'Pablo es joven y tiene el pelo moreno.', 'Pablo is young and has dark hair.', [Kit::word('joven'), Kit::word('moreno'), Kit::word('el pelo', 'pelo'), Kit::form('tiene', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ana-baja-guapa', 'Ana es baja y muy guapa.', 'Ana is short and very good-looking.', [Kit::word('bajo', 'baja'), Kit::word('guapo', 'guapa')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.como-es-marta', [
                Kit::line('Ana', 'Pablo, ¿cómo es Marta?'),
                Kit::line('Pablo', 'Es joven y guapa. Tiene los ojos de Luis.'),
                Kit::line('Ana', '¿Es rubia?'),
                Kit::line('Pablo', 'No, es morena.'),
            ], [
                Kit::question('What is Marta like?', ['Young and good-looking', 'Short and nice', 'Tall and blond'], 'Young and good-looking'),
                Kit::question('Whose eyes does Marta have?', ['Luis\'s', 'Pablo\'s', 'Ana\'s'], 'Luis\'s'),
                Kit::question('Is Marta blond?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('Who asks about Marta?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Is Marta young?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('joven'), Kit::word('guapo', 'guapa'), Kit::word('el ojo', 'ojos'), Kit::word('rubio', 'rubia'), Kit::word('moreno', 'morena')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.como-es-luis', 'Read the conversation.', [
                Kit::line('Marta', 'Ana, ¿cómo es Luis?'),
                Kit::line('Ana', 'Es bajo, pero es una persona muy simpática. Tiene los ojos de Pablo.'),
                Kit::line('Marta', '¿Y Pablo?'),
                Kit::line('Ana', 'Pablo es alto y rubio.'),
            ], [
                Kit::question('What is Luis like?', ['Short and nice', 'Tall and nice', 'Short and blond'], 'Short and nice'),
                Kit::question('Who is blond?', ['Pablo', 'Luis', 'Nobody'], 'Pablo'),
            ], [Kit::word('bajo'), Kit::word('la persona', 'persona'), Kit::word('simpático'), Kit::word('el ojo', 'ojos'), Kit::word('alto'), Kit::word('rubio')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.eres-rubio', '¿Eres rubio?', 'Are you blond?', [['sí', 'no'], ['rubio', 'rubia', 'moreno', 'morena']], 'No, soy moreno.', [Kit::word('rubio'), Kit::word('moreno')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pelo-rubio-moreno', '¿Tienes el pelo rubio o moreno?', 'Do you have blond or dark hair?', [['tengo', 'soy'], ['rubio', 'rubia', 'moreno', 'morena']], 'Tengo el pelo moreno.', [Kit::word('el pelo', 'pelo'), Kit::word('rubio')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.persona-simpatica', '¿Eres una persona simpática?', 'Are you a nice person?', [['sí', 'no'], ['soy', 'simpático', 'simpática']], 'Sí, soy una persona simpática.', [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luis-alto-moreno', 'Luis is tall and dark-haired.', ['Luis es alto y moreno.', 'Luis es moreno y alto.'], [Kit::word('alto'), Kit::word('moreno'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-rubia-guapa', 'Marta is blond and good-looking.', ['Marta es rubia y guapa.', 'Marta es guapa y rubia.'], [Kit::word('rubio', 'rubia'), Kit::word('guapo', 'guapa'), Kit::form('es')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-joven-ojos', 'Ana is young and has Pablo\'s eyes.', ['Ana es joven y tiene los ojos de Pablo.'], [Kit::word('joven'), Kit::word('el ojo', 'ojos'), Kit::form('tiene')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luis-bien-persona', 'Luis is fine, but he is not a nice person.', ['Luis está bien, pero no es una persona simpática.'], [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática'), Kit::form('está', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-marta-bajas', 'Ana y Marta son ___.', 'Ana and Marta are short.', 'bajas', Kit::word('bajo', 'bajas'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-ojos', 'Pablo tiene los ___ de Marta.', 'Pablo has Marta\'s eyes.', 'ojos', Kit::word('el ojo', 'ojos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-persona', 'Marta es una persona simpática y guapa.', 'Marta is a nice and good-looking person.', [Kit::word('la persona', 'persona'), Kit::word('simpático', 'simpática'), Kit::word('guapo', 'guapa')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ana-baja-pelo', 'Ana es baja y tiene el pelo rubio.', 'Ana is short and has blond hair.', [Kit::word('bajo', 'baja'), Kit::word('el pelo', 'pelo'), Kit::word('rubio'), Kit::form('es')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.luis-joven-alto', 'Luis es joven, alto y tiene el pelo moreno.', 'Luis is young, tall and has dark hair.', [Kit::word('joven'), Kit::word('alto'), Kit::word('el pelo', 'pelo'), Kit::word('moreno'), Kit::form('tiene', true)], 'dictation', $set),
        ];
    }
}
