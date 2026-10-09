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

final class HowDoYouFeel implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'how-do-you-feel';
    }

    public function words(): array
    {
        return [
            new WordData('sentirse', cue: 'to feel (tired, well, ill)', forms: ['me siento', 'te sientes', 'se siente', 'nos sentimos', 'se sienten'], note: 'Sentirse changes e to ie in most forms (me siento, te sientes, se siente, se sienten), but not in nos sentimos. Me siento cansado is I feel tired.'),
            new WordData('deber', cue: 'should, ought to', forms: ['debo', 'debes', 'debe', 'debemos', 'deben'], note: 'Deber + infinitive gives advice: Debes descansar is you should rest. Tener que is stronger, like have to.'),
            new WordData('tomar', cue: 'to take (medicine, a drink)', forms: ['tomo', 'tomas', 'toma', 'tomamos', 'toman']),
            new WordData('descansar', cue: 'to rest', forms: ['descanso', 'descansas', 'descansa', 'descansamos', 'descansan']),
            new WordData('la tos', cue: 'cough', note: 'Spanish says tener tos, with no article: Tengo tos.'),
            new WordData('el estómago', cue: 'stomach'),
            new WordData('la espalda', cue: 'back (of the body)', note: 'Spanish uses the article with body parts: me duele la espalda, not mi espalda.'),
            new WordData('el resfriado', cue: 'cold (illness)', note: 'Tengo un resfriado is I have a cold. You can also say estoy resfriado.'),
            new WordData('cansado', cue: 'tired', forms: ['cansada', 'cansados', 'cansadas']),
            new WordData('la pastilla', cue: 'pill, tablet', forms: ['pastillas']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Me siento cansado y me duele la espalda.', 'english' => 'I feel tired and my back hurts.'],
            ['text' => 'Tienes tos. Debes descansar.', 'english' => 'You have a cough. You should rest.'],
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
            Kit::gap($stage, 'sentences.choose_gap.duele-garganta', 'Me ___ la garganta.', ['duele', 'duelen'], 'duele', Kit::form('duele'), 'La garganta is one thing, so the verb is duele. Duelen is for more than one thing.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.duelen-pies', 'Me ___ los pies.', ['duele', 'duelen'], 'duelen', Kit::form('duelen', true), 'Los pies is more than one thing, so the verb is duelen.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tengo-frio', 'Yo ___ frío.', ['tengo', 'estoy', 'soy'], 'tengo', Kit::form('tengo', true), 'Spanish says what you have: tengo frío. Estoy frío is wrong.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.ana-siente', 'Ana ___ cansada.', ['se siente', 'me siento', 'te sientes'], 'se siente', Kit::form('se siente'), 'Ana is one person, so se siente. The pronoun and the ending match the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-debes', 'Tú ___ descansar hoy.', ['debes', 'debo', 'debe'], 'debes', Kit::form('debes'), 'Tú goes with debes. Debo is for yo and debe is for he, she or you (formal).', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-tiene-que', 'Pablo ___ trabajar hoy.', ['tiene que', 'tiene', 'tienes que'], 'tiene que', Kit::form('tiene que', true), 'To say has to, you need que before the infinitive: tiene que trabajar. Tiene alone would mean has, and tienes goes with tú.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.estomago', 'Me duele el ___.', 'My stomach hurts.', 'estómago', Kit::word('el estómago', 'estómago')),
            Kit::typeGap($stage, 'sentences.type_gap.ana-tiene', 'Ana ___ un resfriado.', 'Ana has a cold.', 'tiene', Kit::form('tiene'), 'Ana is one person, so the verb is tiene.'),
            Kit::typeGap($stage, 'sentences.type_gap.me-siento', 'Yo ___ cansado (sentirse).', 'I feel tired.', 'me siento', Kit::form('me siento'), 'Yo goes with me: me siento. The e becomes ie.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-debes', 'Tú ___ tomar una pastilla.', 'You should take a pill.', 'debes', Kit::form('debes'), 'Tú goes with debes, and the next verb stays in the infinitive: tomar.'),
            Kit::typeGap($stage, 'sentences.type_gap.se-sienten', 'Ellos ___ cansados (sentirse).', 'They feel tired.', 'se sienten', Kit::form('se sienten'), 'Ellos goes with se, and the ending -en shows more than one person: se sienten.'),

            Kit::translate($stage, 'sentences.translate.espalda', 'My back hurts.', ['Me duele la espalda.'], [Kit::word('la espalda', 'espalda'), Kit::form('me duele')]),
            Kit::translate($stage, 'sentences.translate.ana-cansada', 'Ana feels tired.', ['Ana se siente cansada.', 'Se siente cansada.'], [Kit::word('sentirse', 'se siente'), Kit::word('cansado', 'cansada'), Kit::form('se siente')]),
            Kit::translate($stage, 'sentences.translate.medicina', 'You should take the pill.', ['Debes tomar la pastilla.', 'Tú debes tomar la pastilla.'], [Kit::word('deber', 'debes'), Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::form('debes')]),

            Kit::build($stage, 'sentences.build.resfriado', 'I have a cold.', 'Tengo un resfriado.', ['tienes'], [Kit::word('el resfriado', 'resfriado'), Kit::form('tengo', true)]),
            Kit::build($stage, 'sentences.build.descansamos', 'We rest at home.', 'Descansamos en casa.', ['descanso'], [Kit::word('descansar', 'descansamos')]),
            Kit::build($stage, 'sentences.build.pablo-toma', 'Pablo takes a pill.', 'Pablo toma una pastilla.', ['tomo'], [Kit::word('tomar', 'toma'), Kit::word('la pastilla', 'pastilla')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.tos-garganta', 'Tengo tos y me duele la garganta.', ['I have a cough and my throat hurts.', 'I have a cough and my head hurts.', 'I have a cold and my throat hurts.', 'She has a cough and her throat hurts.'], 'I have a cough and my throat hurts.', [Kit::word('la tos', 'tos'), Kit::form('tengo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marta-cansada', 'Marta se siente cansada hoy.', ['Marta feels tired today.', 'Marta feels ill today.', 'I feel tired today.', 'Marta is at home today.'], 'Marta feels tired today.', [Kit::word('sentirse', 'se siente'), Kit::word('cansado', 'cansada'), Kit::form('se siente')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.luis-debe', 'Luis debe descansar en la cama.', ['Luis should rest in bed.', 'Luis rests in bed.', 'I should rest in bed.', 'Luis should rest at home.'], 'Luis should rest in bed.', [Kit::word('deber', 'debe'), Kit::word('descansar', 'descansar'), Kit::form('debe')]),
            Kit::listenType($stage, 'sentences.listen_type.duele-estomago', 'Me duele el estómago.', 'My stomach hurts.', [Kit::word('el estómago', 'estómago'), Kit::form('me duele')]),
            Kit::listenType($stage, 'sentences.listen_type.tienes-resfriado', 'Tienes un resfriado y tos.', 'You have a cold and a cough.', [Kit::word('el resfriado', 'resfriado'), Kit::word('la tos', 'tos'), Kit::form('tienes')]),
            Kit::listenType($stage, 'sentences.listen_type.nos-sentimos', 'Nos sentimos bien en casa.', 'We feel good at home.', [Kit::word('sentirse', 'nos sentimos'), Kit::form('nos sentimos')]),
            Kit::listenType($stage, 'sentences.listen_type.pastilla-tos', 'La pastilla es para la tos.', 'The pill is for the cough.', [Kit::word('la pastilla', 'pastilla'), Kit::word('la tos', 'tos')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.duelen-manos', 'Me duelen las manos y los pies.', 'My hands and my feet hurt.', [Kit::form('me duelen', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tomo-descanso', 'Tomo una pastilla y descanso.', 'I take a pill and I rest.', [Kit::word('tomar', 'tomo'), Kit::word('la pastilla', 'pastilla'), Kit::word('descansar', 'descanso')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.debes-cama', 'Debes descansar en la cama.', 'You should rest in bed.', [Kit::word('deber', 'debes'), Kit::word('descansar', 'descansar'), Kit::form('debes')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hoy-cansado', 'Hoy me siento muy cansado.', 'Today I feel very tired.', [Kit::word('sentirse', 'me siento'), Kit::word('cansado', 'cansado'), Kit::form('me siento')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-te-sientes', '¿Cómo te sientes hoy?', 'How do you feel today?', [['siento', 'bien', 'cansado', 'cansada', 'enfermo', 'enferma']], 'Hoy me siento cansado.', [Kit::word('sentirse', 'me siento'), Kit::form('me siento')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.que-te-duele', '¿Qué te duele?', 'What hurts you?', [['duele', 'duelen'], ['cabeza', 'espalda', 'estómago', 'garganta', 'pies', 'manos']], 'Me duele la espalda.', [Kit::word('la espalda', 'espalda'), Kit::form('me duele')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tienes-tos', '¿Tienes tos?', 'Do you have a cough?', [['sí', 'no'], ['tengo', 'tos']], 'Sí, tengo tos.', [Kit::word('la tos', 'tos'), Kit::form('tengo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.pablo-enfermo', 'Read the conversation between two friends.', [
                Kit::line('Marta', 'Hola, Pablo. ¿Cómo te sientes hoy?'),
                Kit::line('Pablo', 'Estoy cansado. Me duele la espalda y tengo tos.'),
                Kit::line('Marta', 'Tienes un resfriado. Debes descansar en casa.'),
                Kit::line('Pablo', 'Tengo que trabajar hoy. ¿Tomo una pastilla?'),
                Kit::line('Marta', 'Sí, debes tomar una pastilla. Y mañana debes ir al médico.'),
                Kit::line('Pablo', 'Gracias, Marta.'),
            ], [
                Kit::question('How does Pablo feel?', ['Tired', 'Well', 'Angry'], 'Tired'),
                Kit::question('What does Pablo have?', ['A cough and a backache', 'A fever', 'A headache'], 'A cough and a backache'),
                Kit::question('What does Marta advise today?', ['Rest at home and take a pill', 'Go to the doctor today', 'Go to work'], 'Rest at home and take a pill'),
            ], [Kit::word('sentirse', 'te sientes'), Kit::word('cansado', 'cansado'), Kit::word('la espalda', 'espalda'), Kit::word('la tos', 'tos'), Kit::word('el resfriado', 'resfriado'), Kit::word('deber', 'debes'), Kit::word('descansar', 'descansar'), Kit::word('tomar', 'tomo'), Kit::word('la pastilla', 'pastilla')]),
            Kit::gap($stage, 'task.choose_gap.ana-fiebre', 'Ana tiene fiebre. Ella ___ descansar en la cama.', ['debe', 'debes', 'debo'], 'debe', Kit::word('deber', 'debe'), 'Ella is one person, so debe. Debes goes with tú and debo with yo.', 'read', 'Ana has a fever. She should rest in bed.'),
            Kit::gap($stage, 'task.choose_gap.pastilla', 'Me duele el estómago. Tomo una ___.', ['pastilla', 'tos', 'espalda'], 'pastilla', Kit::word('la pastilla', 'pastilla'), 'You take a pill. You do not take a cough or a back.', 'read', 'My stomach hurts. I take a ...'),

            Kit::transform($stage, 'task.transform.manos', 'Change la espalda to las manos.', 'Me duele la espalda.', ['Me duelen las manos.'], [Kit::form('me duelen', true)]),
            Kit::transform($stage, 'task.transform.nosotros', 'Change the subject to we.', 'Me siento cansado.', ['Nos sentimos cansados.', 'Nosotros nos sentimos cansados.', 'Nos sentimos cansadas.', 'Nosotras nos sentimos cansadas.'], [Kit::word('sentirse', 'nos sentimos'), Kit::word('cansado', 'cansados', ['cansadas']), Kit::form('nos sentimos')]),
            Kit::transform($stage, 'task.transform.luis', 'Say it about Luis.', 'Debes descansar hoy.', ['Luis debe descansar hoy.', 'Debe descansar hoy.'], [Kit::word('deber', 'debe'), Kit::word('descansar', 'descansar'), Kit::form('debe')]),
            Kit::writeGuided($stage, 'task.write_guided.cansado', 'Say how you feel and what hurts. Use the words feel, tired and back.', ['me siento', 'cansado', 'la espalda'], 'Me siento cansado y me duele la espalda.', [
                ['forms' => ['siento', 'sientes', 'siente', 'sentimos', 'sienten'], 'term' => 'sentirse'],
                ['forms' => ['cansado', 'cansada', 'cansados', 'cansadas'], 'term' => 'cansado'],
                ['forms' => ['espalda'], 'term' => 'la espalda'],
            ], [Kit::word('sentirse', 'me siento'), Kit::word('cansado', 'cansado'), Kit::word('la espalda', 'espalda')]),
            Kit::writeGuided($stage, 'task.write_guided.consejo', 'Give advice to a friend with a cold. Use the words cold, rest and pill.', ['un resfriado', 'descansar', 'una pastilla'], 'Tienes un resfriado. Debes descansar y tomar una pastilla.', [
                ['forms' => ['resfriado'], 'term' => 'el resfriado'],
                ['forms' => ['descansar', 'descanso', 'descansas', 'descansa', 'descansamos', 'descansan'], 'term' => 'descansar'],
                ['forms' => ['pastilla', 'pastillas'], 'term' => 'la pastilla'],
            ], [Kit::word('el resfriado', 'resfriado'), Kit::word('descansar', 'descansar'), Kit::word('la pastilla', 'pastilla')]),
            Kit::build($stage, 'task.build.estomago-resfriado', 'My stomach hurts and I have a cold.', 'Me duele el estómago y tengo un resfriado.', ['duelen', 'tiene'], [Kit::word('el estómago', 'estómago'), Kit::word('el resfriado', 'resfriado'), Kit::form('me duele')], 'write'),
            Kit::build($stage, 'task.build.pablo-debe', 'Pablo should rest and take a pill.', 'Pablo debe descansar y tomar una pastilla.', ['debes', 'toma'], [Kit::word('deber', 'debe'), Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::form('debe')], 'write'),
            Kit::build($stage, 'task.build.cansados-tos', 'We feel tired and we have a cough.', 'Nos sentimos cansados y tenemos tos.', ['me', 'cansada'], [Kit::word('sentirse', 'nos sentimos'), Kit::word('cansado', 'cansados'), Kit::word('la tos', 'tos'), Kit::form('tenemos')], 'write'),
            Kit::translate($stage, 'task.translate.tos-estomago', 'She has a cough and her stomach hurts.', ['Tiene tos y le duele el estómago.', 'Ella tiene tos y le duele el estómago.'], [Kit::word('la tos', 'tos'), Kit::word('el estómago', 'estómago'), Kit::form('le duele')], 'write'),
            Kit::translate($stage, 'task.translate.tienes-que', 'You have to take a pill and rest.', ['Tienes que tomar una pastilla y descansar.', 'Tú tienes que tomar una pastilla y descansar.'], [Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::word('descansar', 'descansar'), Kit::form('tienes que')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.ana-cansada', [
                Kit::line('Luis', '¿Qué tienes, Ana? ¿Estás enferma?'),
                Kit::line('Ana', 'Sí. Estoy cansada y me duele el estómago.'),
                Kit::line('Luis', 'Debes descansar en casa. Hoy no tienes que trabajar.'),
                Kit::line('Ana', 'Tengo tos y frío. ¿Tomo una pastilla?'),
                Kit::line('Luis', 'Sí, y mañana vas al médico.'),
            ], [
                Kit::question('What hurts Ana?', ['Her stomach', 'Her back', 'Her head'], 'Her stomach'),
                Kit::question('What does Luis say about work?', ['Ana does not have to work today', 'Ana has to work today', 'Ana can work at the office'], 'Ana does not have to work today'),
                Kit::question('When should Ana go to the doctor?', ['Today', 'Tomorrow', 'On Monday'], 'Tomorrow'),
            ], [
                Kit::question('How does Ana feel?', ['Tired', 'Happy', 'Angry'], 'Tired'),
                Kit::question('What else does Ana have?', ['A cough, and she feels cold', 'A fever', 'A headache'], 'A cough, and she feels cold'),
                Kit::question('Where should Ana rest?', ['At home', 'At the office', 'At the cinema'], 'At home'),
            ], [Kit::word('el estómago', 'estómago'), Kit::word('cansado', 'cansada'), Kit::word('deber', 'debes'), Kit::word('descansar', 'descansar'), Kit::word('la tos', 'tos'), Kit::word('tomar', 'tomo'), Kit::word('la pastilla', 'pastilla')]),
            Kit::listenType($stage, 'task.listen_type.marta-resfriado', 'Marta se siente cansada y tiene un resfriado.', 'Marta feels tired and has a cold.', [Kit::word('sentirse', 'se siente'), Kit::word('cansado', 'cansada'), Kit::word('el resfriado', 'resfriado'), Kit::form('se siente')]),
            Kit::listenType($stage, 'task.listen_type.debo-descansar', 'Me duele la espalda. Debo descansar hoy.', 'My back hurts. I should rest today.', [Kit::word('la espalda', 'espalda'), Kit::word('deber', 'debo'), Kit::word('descansar', 'descansar'), Kit::form('debo')]),
            Kit::listenType($stage, 'task.listen_type.agua', 'Tienes que tomar la pastilla con agua.', 'You have to take the pill with water.', [Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::form('tienes que')]),

            Kit::speakAnswer($stage, 'task.speak_answer.que-tienes', '¿Qué tienes hoy?', 'What is wrong with you today?', [['tengo', 'duele', 'duelen', 'siento', 'estoy'], ['tos', 'resfriado', 'fiebre', 'espalda', 'estómago', 'cansado', 'cansada', 'cabeza', 'garganta', 'frío', 'calor', 'enfermo', 'enferma']], 'Tengo tos y estoy cansado.', [Kit::word('la tos', 'tos'), Kit::form('tengo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.resfriado', '¿Qué debes hacer con un resfriado?', 'What should you do with a cold?', [['debes', 'debe'], ['descansar', 'tomar']], 'Debes descansar y tomar una pastilla.', [Kit::word('deber', 'debes'), Kit::word('descansar', 'descansar'), Kit::word('tomar', 'tomar')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.duele-espalda', '¿Te duele la espalda?', 'Does your back hurt?', [['sí', 'no'], ['duele', 'espalda']], 'Sí, me duele la espalda.', [Kit::word('la espalda', 'espalda'), Kit::form('me duele')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cansado', '¿Te sientes cansado?', 'Do you feel tired?', [['sí', 'no'], ['siento', 'cansado', 'cansada', 'bien']], 'Sí, me siento cansado.', [Kit::word('sentirse', 'me siento'), Kit::word('cansado', 'cansado'), Kit::form('me siento')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.estomago-tos', 'Me duele el estómago y tengo tos.', 'My stomach hurts and I have a cough.', [Kit::word('el estómago', 'estómago'), Kit::word('la tos', 'tos'), Kit::form('me duele')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tienes-que', 'Tienes que descansar y tomar una pastilla.', 'You have to rest and take a pill.', [Kit::word('descansar', 'descansar'), Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::form('tienes que')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.estomago-tos', 'My throat hurts. I have a cough.', ['Me duele la garganta. Tengo tos.', 'Me duele la garganta. Yo tengo tos.'], [Kit::word('la tos', 'tos'), Kit::form('me duele')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.resfriado-debes', 'You have a cold. You should rest.', ['Tienes un resfriado. Debes descansar.', 'Tú tienes un resfriado. Tú debes descansar.', 'Estás resfriado. Debes descansar.'], [Kit::word('el resfriado', 'resfriado'), Kit::word('descansar', 'descansar'), Kit::word('deber', 'debes'), Kit::form('debes')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-espalda', 'Pablo feels tired and his back hurts.', ['Pablo se siente cansado y le duele la espalda.', 'Se siente cansado y le duele la espalda.'], [Kit::word('sentirse', 'se siente'), Kit::word('cansado', 'cansado'), Kit::word('la espalda', 'espalda'), Kit::form('le duele')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.duelen-manos', 'My hands hurt and I take a pill.', ['Me duelen las manos y tomo una pastilla.'], [Kit::word('tomar', 'tomo'), Kit::word('la pastilla', 'pastilla'), Kit::form('me duelen', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luis-frio', 'Luis ___ frío.', 'Luis is cold.', 'tiene', Kit::form('tiene', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.nos-sentimos', 'Nosotros ___ cansados (sentirse).', 'We feel tired.', 'nos sentimos', Kit::form('nos sentimos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.siento-tomo', 'Me siento cansado. Tomo una pastilla.', 'I feel tired. I take a pill.', [Kit::word('sentirse', 'me siento'), Kit::word('cansado', 'cansado'), Kit::word('tomar', 'tomo'), Kit::word('la pastilla', 'pastilla')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.debo-espalda', 'Debo descansar. Me duele la cabeza.', 'I should rest. My head hurts.', [Kit::word('deber', 'debo'), Kit::word('descansar', 'descansar')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.tos-estomago', 'Tengo tos y un resfriado. El estómago está bien.', 'I have a cough and a cold. The stomach is fine.', [Kit::word('la tos', 'tos'), Kit::word('el resfriado', 'resfriado'), Kit::word('el estómago', 'estómago')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.pablo-marta', [
                Kit::line('Pablo', 'Hola, Marta. Hoy estoy muy cansado.'),
                Kit::line('Marta', 'Tienes que descansar, Pablo. ¿Te duele la cabeza?'),
                Kit::line('Pablo', 'No, me duele el estómago.'),
                Kit::line('Marta', 'Debes tomar una pastilla con agua.'),
            ], [
                Kit::question('How does Pablo feel?', ['Tired', 'Hungry', 'Happy'], 'Tired'),
                Kit::question('What hurts?', ['His head', 'His stomach', 'His back'], 'His stomach'),
                Kit::question('What should he take?', ['A pill with water', 'Nothing', 'A cold drink'], 'A pill with water'),
            ], [
                Kit::question('Who gives the advice?', ['Marta', 'Pablo', 'The doctor'], 'Marta'),
                Kit::question('Does his head hurt?', ['No', 'Yes', 'The text does not say'], 'No'),
                Kit::question('What does Marta say he has to do?', ['Rest', 'Work', 'Go to the cinema'], 'Rest'),
            ], [Kit::word('cansado', 'cansado'), Kit::word('descansar', 'descansar'), Kit::word('el estómago', 'estómago'), Kit::word('deber', 'debes'), Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.luis-ana', 'Read the conversation.', [
                Kit::line('Ana', 'Luis, ¿cómo te sientes?'),
                Kit::line('Luis', 'Tengo un resfriado y tos. Me siento muy cansado.'),
                Kit::line('Ana', 'Debes descansar en la cama hoy.'),
                Kit::line('Luis', 'Gracias. ¿Hay una farmacia aquí?'),
            ], [
                Kit::question('What does Luis have?', ['A cold and a cough', 'A fever', 'A stomachache'], 'A cold and a cough'),
                Kit::question('What does Ana advise?', ['Rest in bed', 'Go to work', 'Go to the pharmacy'], 'Rest in bed'),
            ], [Kit::word('sentirse', 'sientes'), Kit::word('el resfriado', 'resfriado'), Kit::word('la tos', 'tos'), Kit::word('cansado', 'cansado'), Kit::word('deber', 'debes'), Kit::word('descansar', 'descansar')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.duele-estomago', '¿Te duele el estómago?', 'Does your stomach hurt?', [['sí', 'no'], ['duele', 'estómago']], 'No, no me duele el estómago.', [Kit::word('el estómago', 'estómago')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tomas-tos', '¿Qué tomas para la tos?', 'What do you take for a cough?', [['tomo', 'pastilla', 'medicina']], 'Tomo una pastilla.', [Kit::word('tomar', 'tomo'), Kit::word('la tos', 'tos')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sientes-bien', '¿Te sientes bien hoy?', 'Do you feel well today?', [['sí', 'no'], ['siento', 'bien', 'cansado', 'cansada']], 'Sí, me siento bien.', [Kit::word('sentirse', 'me siento')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luis-cansado', 'Luis feels tired. His back hurts.', ['Luis se siente cansado. Le duele la espalda.', 'Luis se siente cansado. A Luis le duele la espalda.', 'Se siente cansado. Le duele la espalda.'], [Kit::word('sentirse', 'se siente'), Kit::word('cansado', 'cansado'), Kit::word('la espalda', 'espalda'), Kit::form('le duele')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-resfriado', 'Pablo has a cold. He should rest.', ['Pablo tiene un resfriado. Debe descansar.', 'Tiene un resfriado. Debe descansar.', 'Pablo está resfriado. Debe descansar.', 'Está resfriado. Debe descansar.', 'Él tiene un resfriado. Él debe descansar.'], [Kit::word('el resfriado', 'resfriado'), Kit::word('descansar', 'descansar'), Kit::word('deber', 'debe'), Kit::form('debe')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pies-estomago', 'My feet hurt, but my stomach is fine.', ['Me duelen los pies, pero mi estómago está bien.', 'Me duelen los pies, pero el estómago está bien.', 'Me duelen los pies pero mi estómago está bien.', 'Me duelen los pies pero el estómago está bien.', 'Me duelen los pies, pero no me duele el estómago.'], [Kit::word('el estómago', 'estómago'), Kit::form('me duelen', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tenemos-que', 'We have to take a pill for the cough.', ['Tenemos que tomar una pastilla para la tos.', 'Nosotros tenemos que tomar una pastilla para la tos.'], [Kit::word('tomar', 'tomar'), Kit::word('la pastilla', 'pastilla'), Kit::word('la tos', 'tos'), Kit::form('tenemos que')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ellos-calor', 'Ellos ___ calor.', 'They are hot.', 'tienen', Kit::form('tienen', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.te-sientes', 'Tú ___ bien hoy (sentirse).', 'You feel well today.', 'te sientes', Kit::form('te sientes'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.estomago-cansado', 'Me duele el estómago. Me siento cansado.', 'My stomach hurts. I feel tired.', [Kit::word('el estómago', 'estómago'), Kit::word('sentirse', 'me siento'), Kit::word('cansado', 'cansado')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.resfriado-debo', 'Tengo un resfriado y tos. Debo descansar.', 'I have a cold and a cough. I should rest.', [Kit::word('el resfriado', 'resfriado'), Kit::word('la tos', 'tos'), Kit::word('deber', 'debo'), Kit::word('descansar', 'descansar')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ana-toma', 'Ana toma una pastilla para la espalda.', 'Ana takes a pill for her back.', [Kit::word('tomar', 'toma'), Kit::word('la pastilla', 'pastilla'), Kit::word('la espalda', 'espalda')], 'dictation', $set),
        ];
    }
}
