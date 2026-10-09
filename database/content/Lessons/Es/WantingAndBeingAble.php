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

final class WantingAndBeingAble implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'wanting-and-being-able';
    }

    public function words(): array
    {
        return [
            new WordData('querer', cue: 'to want', forms: ['quiero', 'quieres', 'quiere', 'queremos', 'quieren'], note: 'Querer is the verb for want. With an infinitive it means to want to do something: quiero dormir. With a person it can also mean to love.'),
            new WordData('poder', cue: 'can (to be able to)', forms: ['puedo', 'puedes', 'puede', 'podemos', 'pueden'], note: 'Poder is can. The second verb stays in the infinitive: puedo ir is I can go.'),
            new WordData('preferir', cue: 'to prefer', forms: ['prefiero', 'prefieres', 'prefiere', 'preferimos', 'prefieren'], note: 'Preferir works like querer: the second verb stays in the infinitive. Prefiero ver una serie.'),
            new WordData('volver', cue: 'to come back', forms: ['vuelvo', 'vuelves', 'vuelve', 'volvemos', 'vuelven'], note: 'Volver a casa is to come back home.'),
            new WordData('dormir', cue: 'to sleep', forms: ['duermo', 'duermes', 'duerme', 'dormimos', 'duermen']),
            new WordData('empezar', cue: 'to start (to begin)', forms: ['empiezo', 'empiezas', 'empieza', 'empezamos', 'empiezan'], note: 'Before an infinitive empezar needs a: empiezo a trabajar.'),
            new WordData('la siesta', cue: 'nap (siesta)', note: 'You say dormir la siesta for taking a nap.'),
            new WordData('el paseo', cue: 'walk (stroll)', note: 'Dar un paseo is to go for a walk. Ir de paseo means the same.'),
            new WordData('el gimnasio', cue: 'gym'),
            new WordData('la serie', cue: 'series (TV show)', note: 'Una serie is a TV series. You watch it with ver: ver una serie.'),
            new WordData('el videojuego', cue: 'video game', forms: ['videojuegos'], note: 'You play a video game with jugar a: jugar a un videojuego.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quiero ver una serie.', 'english' => 'I want to watch a series.'],
            ['text' => 'Queremos ir al gimnasio.', 'english' => 'We want to go to the gym.'],
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
            Kit::gap($stage, 'sentences.choose_gap.ana-quiere', 'Ana ___ ver una película.', ['quiere', 'queremos', 'quieren'], 'quiere', Kit::form('quiere', true), 'Ana is one person (ella), so the verb is quiere, with ie. Queremos is for nosotros and quieren is for several people.', 'choose', 'Ana wants to watch a film.'),
            Kit::gap($stage, 'sentences.choose_gap.queremos', 'Nosotros ___ cenar en casa.', ['queremos', 'quiero', 'quieren'], 'queremos', Kit::form('queremos', true), 'With nosotros the vowel does not change: queremos, not quieremos. The e only becomes ie where the stem is stressed.', 'choose', 'We want to have dinner at home.'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-duerme', 'Pablo ___ la siesta.', ['duerme', 'dormimos', 'duermo'], 'duerme', Kit::word('dormir', 'duerme'), 'Pablo is one person (él), so dormir becomes duerme, with ue. Dormimos is for nosotros and duermo is for yo.', 'choose', 'Pablo takes a nap.'),
            Kit::gap($stage, 'sentences.choose_gap.marta-serie', 'Marta ve una ___ en casa.', ['serie', 'paseo', 'gimnasio'], 'serie', Kit::word('la serie', 'serie'), 'Una goes with a feminine word, and serie is feminine. Paseo and gimnasio are masculine and take un.', 'choose', 'Marta watches a series at home.'),
            Kit::gap($stage, 'sentences.choose_gap.gimnasio', 'Luis va al ___ por la mañana.', ['gimnasio', 'siesta', 'serie'], 'gimnasio', Kit::word('el gimnasio', 'gimnasio'), 'Al is a plus el, so the word is masculine. Siesta and serie are feminine and would need a la.', 'choose', 'Luis goes to the gym in the morning.'),
            Kit::gap($stage, 'sentences.choose_gap.puedes', '¿___ venir al cine, Pablo?', ['Puedes', 'Puedo', 'Pueden'], 'Puedes', Kit::form('puedes', true), 'You are asking Pablo, so the verb is tú: puedes, with ue. Puedo is yo and pueden is for several people.', 'choose', 'Can you come to the cinema, Pablo?'),

            Kit::typeGap($stage, 'sentences.type_gap.siesta', 'Quiero dormir la ___.', 'I want to take a nap.', 'siesta', Kit::word('la siesta', 'siesta')),
            Kit::typeGap($stage, 'sentences.type_gap.videojuego', 'Luis quiere jugar a un ___.', 'Luis wants to play a video game.', 'videojuego', Kit::word('el videojuego', 'videojuego')),
            Kit::typeGap($stage, 'sentences.type_gap.empieza', 'La película ___ a las ocho.', 'The film starts at eight.', 'empieza', Kit::form('empieza'), 'La película is one thing (it), so the verb is third person singular. Empezar changes e to ie there: empieza.'),
            Kit::typeGap($stage, 'sentences.type_gap.vuelvo', 'Hoy ___ a casa temprano.', 'Today I come back home early.', 'vuelvo', Kit::form('vuelvo'), 'With yo, volver changes o to ue: vuelvo. Only nosotros (volvemos) keeps the o.'),
            Kit::typeGap($stage, 'sentences.type_gap.duermen', 'Ana y Pablo ___ en el dormitorio.', 'Ana and Pablo sleep in the bedroom.', 'duermen', Kit::form('duermen'), 'Ana y Pablo is ellos, so dormir becomes duermen, with ue. Only nosotros (dormimos) keeps the o.'),

            Kit::translate($stage, 'sentences.translate.quieres-gimnasio', 'Do you want to go to the gym? (informal you)', ['¿Quieres ir al gimnasio?', '¿Tú quieres ir al gimnasio?'], [Kit::word('el gimnasio', 'gimnasio'), Kit::word('querer', 'quieres'), Kit::form('quieres')]),
            Kit::translate($stage, 'sentences.translate.podemos-paseo', 'We can take a walk today.', ['Podemos dar un paseo hoy.', 'Hoy podemos dar un paseo.', 'Podemos ir de paseo hoy.', 'Hoy podemos ir de paseo.'], [Kit::word('el paseo', 'paseo'), Kit::word('poder', 'podemos'), Kit::form('podemos', true)]),
            Kit::translate($stage, 'sentences.translate.marta-vuelve', 'Marta comes back late today.', ['Marta vuelve tarde hoy.', 'Hoy Marta vuelve tarde.'], [Kit::word('volver', 'vuelve'), Kit::form('vuelve')]),

            Kit::build($stage, 'sentences.build.prefiero-serie', 'I prefer to watch a series.', 'Prefiero ver una serie.', ['prefieres'], [Kit::word('la serie', 'serie'), Kit::word('preferir', 'prefiero'), Kit::form('prefiero')]),
            Kit::build($stage, 'sentences.build.pelicula-empieza', 'The film starts at nine.', 'La película empieza a las nueve.', ['empiezo'], [Kit::word('empezar', 'empieza'), Kit::form('empieza')]),
            Kit::build($stage, 'sentences.build.pablo-duerme', 'Pablo sleeps a lot.', 'Pablo duerme mucho.', ['dormimos'], [Kit::word('dormir', 'duerme'), Kit::form('duerme', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.paseo', 'Quiero dar un paseo por el parque.', ['I want to go for a walk in the park.', 'We want to go for a walk in the park.', 'I want to watch a film in the park.', 'I can go for a walk in the park.'], 'I want to go for a walk in the park.', [Kit::word('el paseo', 'paseo'), Kit::word('querer', 'quiero'), Kit::form('quiero')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.gimnasio', 'Ana puede ir al gimnasio hoy.', ['Ana can go to the gym today.', 'Ana wants to go to the gym today.', 'Ana can go to the gym tomorrow.', 'Ana goes to the gym every day.'], 'Ana can go to the gym today.', [Kit::word('el gimnasio', 'gimnasio'), Kit::word('poder', 'puede'), Kit::form('puede')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.volvemos', 'Nosotros volvemos a casa tarde.', ['We come back home late.', 'I come back home late.', 'They come back home late.', 'We come back home early.'], 'We come back home late.', [Kit::word('volver', 'volvemos'), Kit::form('volvemos', true)]),
            Kit::listenType($stage, 'sentences.listen_type.videojuego', 'Luis quiere jugar a un videojuego.', 'Luis wants to play a video game.', [Kit::word('el videojuego', 'videojuego'), Kit::word('querer', 'quiere'), Kit::form('quiere')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.serie', 'La serie empieza a las ocho.', 'The series starts at eight.', [Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::form('empieza')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.siesta', 'Duermo la siesta en la cama.', 'I take a nap in bed.', [Kit::word('dormir', 'duermo'), Kit::word('la siesta', 'siesta'), Kit::form('duermo')]),
            Kit::listenType($stage, 'sentences.listen_type.puedes', '¿Puedes volver temprano?', 'Can you come back early?', [Kit::word('poder', 'puedes'), Kit::word('volver'), Kit::form('puedes')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.gimnasio', 'Quiero ir al gimnasio.', 'I want to go to the gym.', [Kit::word('el gimnasio', 'gimnasio'), Kit::word('querer', 'quiero')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.empiezo', 'Empiezo a trabajar temprano.', 'I start working early.', [Kit::word('empezar', 'empiezo'), Kit::form('empiezo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paseo', '¿Puedes dar un paseo con Ana?', 'Can you take a walk with Ana?', [Kit::word('el paseo', 'paseo'), Kit::word('poder', 'puedes'), Kit::form('puedes')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.volvemos', 'Volvemos a casa a las nueve.', 'We come back home at nine.', [Kit::word('volver', 'volvemos'), Kit::form('volvemos')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quieres', '¿Qué quieres hacer hoy?', 'What do you want to do today?', [['quiero', 'prefiero'], ['ver', 'jugar', 'dar', 'ir', 'dormir', 'cenar', 'bailar', 'cantar', 'leer', 'trabajar', 'comer', 'salir', 'volver']], 'Quiero ver una serie.', [Kit::word('querer', 'quiero'), Kit::word('preferir', 'prefiero'), Kit::form('quiero')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.puedes', '¿Puedes venir a la fiesta?', 'Can you come to the party?', [['puedo']], 'Sí, puedo venir.', [Kit::word('poder', 'puedo'), Kit::form('puedo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.empieza', '¿A qué hora empieza la película?', 'At what time does the film start?', [['empieza'], ['cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez']], 'Empieza a las ocho.', [Kit::word('empezar', 'empieza'), Kit::form('empieza')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.gimnasio-hoy', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿puedes venir al gimnasio hoy?'),
                Kit::line('Pablo', 'No puedo. Quiero dormir la siesta.'),
                Kit::line('Ana', 'Claro. ¿Y por la noche?'),
                Kit::line('Pablo', 'Quiero ver una serie. Empieza a las seis.'),
                Kit::line('Ana', 'Muy bien. Yo vuelvo a casa a las siete.'),
            ], [
                Kit::question('Where does Ana want Pablo to go?', ['To the gym', 'To the park', 'To the cinema'], 'To the gym'),
                Kit::question('What does Pablo want to do instead?', ['Take a nap', 'Go for a walk', 'Play a video game'], 'Take a nap'),
                Kit::question('When does the series start?', ['At six', 'At seven', 'At eight'], 'At six'),
            ], [Kit::word('el gimnasio', 'gimnasio'), Kit::word('poder', 'puedo'), Kit::word('querer', 'quiero'), Kit::word('dormir'), Kit::word('la siesta', 'siesta'), Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::word('volver', 'vuelvo')], 'read'),
            Kit::gap($stage, 'task.choose_gap.prefieres', 'Ana, ¿qué ___, ver una serie o dar un paseo?', ['prefieres', 'prefiero', 'preferimos'], 'prefieres', Kit::word('preferir', 'prefieres'), 'You are talking to Ana, so the verb is tú: prefieres, with ie. Prefiero is yo and preferimos is nosotros.', 'read', 'Ana, which do you prefer, to watch a series or to go for a walk?'),
            Kit::gap($stage, 'task.choose_gap.abuelos-duermen', 'Mis abuelos ___ la siesta por la tarde.', ['duermen', 'dormimos', 'duerme'], 'duermen', Kit::form('duermen', true), 'Mis abuelos is ellos, so the verb is duermen, with ue. Dormimos is nosotros and duerme is for one person.', 'read', 'My grandparents take a nap in the afternoon.'),

            Kit::transform($stage, 'task.transform.ana-quiere', 'Change it to Ana.', 'Quiero ir al gimnasio.', ['Ana quiere ir al gimnasio.'], [Kit::word('el gimnasio', 'gimnasio'), Kit::word('querer', 'quiere'), Kit::form('quiere')]),
            Kit::transform($stage, 'task.transform.volvemos', 'Change it to nosotros.', 'Vuelvo a casa tarde.', ['Volvemos a casa tarde.', 'Nosotros volvemos a casa tarde.'], [Kit::word('volver', 'volvemos'), Kit::form('volvemos', true)]),
            Kit::transform($stage, 'task.transform.duermen', 'Change it to Pablo and Marta.', 'Duermo la siesta en casa.', ['Pablo y Marta duermen la siesta en casa.'], [Kit::word('dormir', 'duermen'), Kit::word('la siesta', 'siesta'), Kit::form('duermen')]),
            Kit::writeGuided($stage, 'task.write_guided.puedes-gimnasio', 'Ask Luis if he can come to the gym today.', ['puedes venir', 'al gimnasio', 'hoy'], '¿Puedes venir al gimnasio hoy?', [
                ['forms' => ['puedes'], 'term' => 'poder'],
                ['forms' => ['gimnasio'], 'term' => 'el gimnasio'],
            ], [Kit::word('poder', 'puedes'), Kit::word('el gimnasio', 'gimnasio'), Kit::form('puedes')]),
            Kit::writeGuided($stage, 'task.write_guided.prefiero-siesta', 'Say that you prefer to take a nap, but Ana wants to play a video game.', ['prefiero dormir', 'la siesta', 'pero Ana quiere', 'jugar a un videojuego'], 'Prefiero dormir la siesta, pero Ana quiere jugar a un videojuego.', [
                ['forms' => ['prefiero'], 'term' => 'preferir'],
                ['forms' => ['dormir'], 'term' => 'dormir'],
                ['forms' => ['siesta'], 'term' => 'la siesta'],
                ['forms' => ['quiere'], 'term' => 'querer'],
                ['forms' => ['videojuego'], 'term' => 'el videojuego'],
            ], [Kit::word('preferir', 'prefiero'), Kit::word('dormir'), Kit::word('la siesta', 'siesta'), Kit::word('querer', 'quiere'), Kit::word('el videojuego', 'videojuego'), Kit::form('quiere')]),
            Kit::build($stage, 'task.build.quieres-serie', 'Do you want to watch a series with Marta? (informal you)', '¿Quieres ver una serie con Marta?', ['quiere', 'quieren'], [Kit::word('la serie', 'serie'), Kit::word('querer', 'quieres'), Kit::form('quieres')]),
            Kit::build($stage, 'task.build.podemos-lunes', 'We can go to the gym on Monday.', 'Podemos ir al gimnasio el lunes.', ['puedo', 'pueden'], [Kit::word('el gimnasio', 'gimnasio'), Kit::word('poder', 'podemos'), Kit::form('podemos', true)]),
            Kit::build($stage, 'task.build.pablo-vuelve', 'Pablo comes back from the gym and sleeps.', 'Pablo vuelve del gimnasio y duerme.', ['vuelvo', 'duermen'], [Kit::word('volver', 'vuelve'), Kit::word('el gimnasio', 'gimnasio'), Kit::word('dormir', 'duerme'), Kit::form('vuelve')]),
            Kit::translate($stage, 'task.translate.paseo-siesta', 'I prefer to go for a walk, but Marta wants to sleep.', ['Prefiero dar un paseo, pero Marta quiere dormir.', 'Yo prefiero dar un paseo, pero Marta quiere dormir.', 'Prefiero ir de paseo, pero Marta quiere dormir.', 'Yo prefiero ir de paseo, pero Marta quiere dormir.'], [Kit::word('preferir', 'prefiero'), Kit::word('el paseo', 'paseo'), Kit::word('querer', 'quiere'), Kit::word('dormir'), Kit::form('quiere')]),
            Kit::translate($stage, 'task.translate.puedo-videojuego', 'I can come back at nine and play a video game.', ['Puedo volver a las nueve y jugar a un videojuego.', 'Yo puedo volver a las nueve y jugar a un videojuego.'], [Kit::word('poder', 'puedo'), Kit::word('volver'), Kit::word('el videojuego', 'videojuego'), Kit::form('puedo')]),

            Kit::listenPassage($stage, 'task.listen_passage.domingo', [
                Kit::line('Marta', 'Luis, ¿qué quieres hacer el domingo?'),
                Kit::line('Luis', 'Quiero ir al gimnasio por la mañana.'),
                Kit::line('Marta', 'Yo prefiero dar un paseo. ¿Puedes venir?'),
                Kit::line('Luis', 'Sí, puedo. Vuelvo del gimnasio a las diez.'),
                Kit::line('Marta', 'Muy bien. Por la tarde duermo la siesta.'),
            ], [
                Kit::question('What does Luis want to do in the morning?', ['Go to the gym', 'Go for a walk', 'Sleep'], 'Go to the gym'),
                Kit::question('What does Marta prefer?', ['To go for a walk', 'To go to the gym', 'To watch a series'], 'To go for a walk'),
                Kit::question('What does Marta do in the afternoon?', ['She takes a nap', 'She goes to the gym', 'She watches a series'], 'She takes a nap'),
            ], [
                Kit::question('Who asks the first question?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Can Luis come for a walk?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('When does Luis come back from the gym?', ['At ten', 'At nine', 'At eleven'], 'At ten'),
            ], [Kit::word('querer', 'quieres'), Kit::word('preferir', 'prefiero'), Kit::word('el gimnasio', 'gimnasio'), Kit::word('el paseo', 'paseo'), Kit::word('poder', 'puedo'), Kit::word('volver', 'vuelvo'), Kit::word('dormir', 'duermo'), Kit::word('la siesta', 'siesta'), Kit::form('vuelvo', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.no-puedo', 'No puedo, vuelvo a casa tarde.', 'I cannot, I come back home late.', [Kit::word('poder', 'puedo'), Kit::word('volver', 'vuelvo'), Kit::form('puedo')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.ana-duerme', 'Ana duerme la siesta y yo veo una serie.', 'Ana takes a nap and I watch a series.', [Kit::word('dormir', 'duerme'), Kit::word('la siesta', 'siesta'), Kit::word('la serie', 'serie')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.fiesta', 'La fiesta empieza tarde, pero queremos ir.', 'The party starts late, but we want to go.', [Kit::word('empezar', 'empieza'), Kit::word('querer', 'queremos'), Kit::form('queremos', true)], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.gimnasio-paseo', '¿Quieres ir al gimnasio o dar un paseo?', 'Do you want to go to the gym or go for a walk?', [['quiero', 'prefiero'], ['gimnasio', 'paseo']], 'Quiero dar un paseo.', [Kit::word('querer', 'quiero'), Kit::word('preferir', 'prefiero'), Kit::word('el gimnasio', 'gimnasio'), Kit::word('el paseo', 'paseo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.duermes', '¿Cuándo duermes la siesta?', 'When do you take a nap?', [['duermo'], ['tarde', 'mañana', 'noche', 'domingo', 'comer']], 'Duermo la siesta por la tarde.', [Kit::word('dormir', 'duermo'), Kit::word('la siesta', 'siesta'), Kit::form('duermo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vuelves', '¿Cuándo vuelves a casa?', 'When do you come back home?', [['vuelvo'], ['ocho', 'nueve', 'diez', 'siete', 'seis', 'tarde', 'temprano', 'hoy', 'mañana']], 'Vuelvo a casa a las ocho.', [Kit::word('volver', 'vuelvo'), Kit::form('vuelvo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.serie', '¿A qué hora empieza la serie?', 'At what time does the series start?', [['empieza'], ['cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once']], 'Empieza a las nueve.', [Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::form('empieza')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.videojuego', 'Quiero jugar a un videojuego con Luis.', 'I want to play a video game with Luis.', [Kit::word('querer', 'quiero'), Kit::word('el videojuego', 'videojuego')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.volver', '¿Puedes volver a las nueve y media?', 'Can you come back at half past nine?', [Kit::word('poder', 'puedes'), Kit::word('volver'), Kit::form('puedes')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.serie-dormir', 'Marta wants to watch a series, but I prefer to sleep.', ['Marta quiere ver una serie, pero yo prefiero dormir.', 'Marta quiere ver una serie, pero prefiero dormir.'], [Kit::word('preferir', 'prefiero'), Kit::word('la serie', 'serie'), Kit::word('querer', 'quiere'), Kit::word('dormir'), Kit::form('quiere')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.podemos-paseo', 'We can take a walk, but Luis does not want to.', ['Podemos dar un paseo, pero Luis no quiere.', 'Podemos dar un paseo, pero Luis no quiere ir.', 'Podemos ir de paseo, pero Luis no quiere.', 'Podemos ir de paseo, pero Luis no quiere ir.'], [Kit::word('el paseo', 'paseo'), Kit::word('poder', 'podemos'), Kit::word('querer', 'quiere'), Kit::form('podemos', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.serie-vuelvo', 'The series starts at nine, but I come back late.', ['La serie empieza a las nueve, pero vuelvo tarde.', 'La serie empieza a las nueve, pero yo vuelvo tarde.'], [Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::word('volver', 'vuelvo'), Kit::form('empieza')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.no-puedo-gimnasio', 'I cannot go to the gym. I want to play a video game.', ['No puedo ir al gimnasio. Quiero jugar a un videojuego.', 'No puedo ir al gimnasio. Yo quiero jugar a un videojuego.'], [Kit::word('el gimnasio', 'gimnasio'), Kit::word('poder', 'puedo'), Kit::word('querer', 'quiero'), Kit::word('el videojuego', 'videojuego'), Kit::form('puedo', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.paseo-marta', 'Quiero dar un ___ con Marta.', 'I want to take a walk with Marta.', 'paseo', Kit::word('el paseo', 'paseo'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.siesta-ana', 'Ana duerme la ___ por la tarde.', 'Ana takes a nap in the afternoon.', 'siesta', Kit::word('la siesta', 'siesta'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-duerme', 'Luis duerme la siesta y yo voy al gimnasio.', 'Luis takes a nap and I go to the gym.', [Kit::word('dormir', 'duerme'), Kit::word('la siesta', 'siesta'), Kit::word('el gimnasio', 'gimnasio'), Kit::form('duerme')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.quieren-videojuego', 'Quieren jugar a un videojuego, pero no puedo.', 'They want to play a video game, but I cannot.', [Kit::word('querer', 'quieren'), Kit::word('el videojuego', 'videojuego'), Kit::word('poder', 'puedo')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.manana-vuelvo', 'Mañana vuelvo a casa y empiezo a trabajar.', 'Tomorrow I come back home and start working.', [Kit::word('volver', 'vuelvo'), Kit::word('empezar', 'empiezo'), Kit::form('vuelvo')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.hoy', [
                Kit::line('Ana', 'Hola, Pablo. ¿Qué quieres hacer?'),
                Kit::line('Pablo', 'Quiero jugar a un videojuego. ¿Y tú?'),
                Kit::line('Ana', 'Yo prefiero dar un paseo por el parque.'),
                Kit::line('Pablo', 'Puedo dar un paseo, pero vuelvo a casa temprano. La serie empieza a las nueve.'),
                Kit::line('Ana', 'Claro. Yo vuelvo a las diez.'),
            ], [
                Kit::question('What does Pablo want to do?', ['Play a video game', 'Go for a walk', 'Go to the gym'], 'Play a video game'),
                Kit::question('Where does Ana want to go for a walk?', ['In the park', 'At the gym', 'At home'], 'In the park'),
                Kit::question('When does Pablo come back home?', ['Early', 'Late', 'At night'], 'Early'),
            ], [
                Kit::question('Who asks what to do today?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Does Ana prefer to play a video game?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Does the series start at nine?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('querer', 'quieres'), Kit::word('preferir', 'prefiero'), Kit::word('el videojuego', 'videojuego'), Kit::word('el paseo', 'paseo'), Kit::word('poder', 'puedo'), Kit::word('volver', 'vuelvo'), Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::form('vuelvo')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.domingo', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿qué haces el domingo?'),
                Kit::line('Luis', 'Duermo la siesta y voy al gimnasio.'),
                Kit::line('Marta', 'Yo quiero ver una serie. Empieza a las cinco.'),
                Kit::line('Luis', 'Muy bien.'),
            ], [
                Kit::question('Where does Luis go on Sunday?', ['To the gym', 'To the park', 'To the cinema'], 'To the gym'),
                Kit::question('What does Marta want to watch?', ['A series', 'A film', 'Football'], 'A series'),
            ], [Kit::word('dormir', 'duermo'), Kit::word('la siesta', 'siesta'), Kit::word('el gimnasio', 'gimnasio'), Kit::word('querer', 'quiero'), Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.paseo', '¿Puedes dar un paseo hoy?', 'Can you take a walk today?', [['puedo']], 'Sí, puedo dar un paseo hoy.', [Kit::word('poder', 'puedo'), Kit::word('el paseo', 'paseo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.siesta', '¿Duermes la siesta en casa?', 'Do you take a nap at home?', [['duermo'], ['siesta', 'casa']], 'Sí, duermo la siesta en casa.', [Kit::word('dormir', 'duermo'), Kit::word('la siesta', 'siesta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vuelves', '¿A qué hora vuelves hoy?', 'At what time do you come back today?', [['vuelvo'], ['cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once']], 'Vuelvo a las ocho.', [Kit::word('volver', 'vuelvo')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.paseo-siesta', 'Ana prefers to take a walk. I want to take a nap.', ['Ana prefiere dar un paseo. Yo quiero dormir la siesta.', 'Ana prefiere dar un paseo. Quiero dormir la siesta.', 'Ana prefiere ir de paseo. Yo quiero dormir la siesta.', 'Ana prefiere ir de paseo. Quiero dormir la siesta.'], [Kit::word('preferir', 'prefiere'), Kit::word('el paseo', 'paseo'), Kit::word('querer', 'quiero'), Kit::word('dormir'), Kit::word('la siesta', 'siesta'), Kit::form('quiero')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.puedes-videojuego', 'Can you come back and play a video game? (informal you)', ['¿Puedes volver y jugar a un videojuego?', '¿Tú puedes volver y jugar a un videojuego?'], [Kit::word('poder', 'puedes'), Kit::word('volver'), Kit::word('el videojuego', 'videojuego'), Kit::form('puedes', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.queremos-serie', 'We want to watch the series at home. It starts at nine.', ['Queremos ver la serie en casa. Empieza a las nueve.', 'Nosotros queremos ver la serie en casa. Empieza a las nueve.'], [Kit::word('querer', 'queremos'), Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::form('queremos', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-gimnasio', 'Marta sleeps at home, but she wants to go to the gym.', ['Marta duerme en casa, pero quiere ir al gimnasio.', 'Marta duerme en casa, pero ella quiere ir al gimnasio.'], [Kit::word('dormir', 'duerme'), Kit::word('querer', 'quiere'), Kit::word('el gimnasio', 'gimnasio'), Kit::form('duerme')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.gimnasio-pablo', 'Pablo ve una ___ por la tarde.', 'Pablo watches a series in the afternoon.', 'serie', Kit::word('la serie', 'serie'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.paseo-parque', 'Doy un ___ por el parque.', 'I take a walk in the park.', 'paseo', Kit::word('el paseo', 'paseo'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.luis-vuelve', 'Luis vuelve a casa, pero Ana prefiere dormir la siesta.', 'Luis comes back home, but Ana prefers to take a nap.', [Kit::word('volver', 'vuelve'), Kit::word('preferir', 'prefiere'), Kit::word('dormir'), Kit::word('la siesta', 'siesta'), Kit::form('vuelve')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.serie-diez', 'La serie empieza a las diez.', 'The series starts at ten.', [Kit::word('la serie', 'serie'), Kit::word('empezar', 'empieza'), Kit::form('empieza')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.no-puedo-videojuego', 'No puedo, quiero jugar a un videojuego.', 'I cannot, I want to play a video game.', [Kit::word('poder', 'puedo'), Kit::word('querer', 'quiero'), Kit::word('el videojuego', 'videojuego')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
