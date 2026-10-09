<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class MyJob implements UnitContent
{
    private const A_NOTE = 'The little word a (to) sounds just like ha (has). Here it is the preposition a, which Spanish puts before a person: ayudar a Ana.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'my-job';
    }

    public function words(): array
    {
        return [
            new WordData('ayudar', cue: 'to help', forms: ['estoy ayudando'], note: 'Ayudar puts a before a person: estoy ayudando a Ana. The thing you help with comes after con: ayudar con la tarea.'),
            new WordData('atender', cue: 'to serve (to attend to)', forms: ['estoy atendiendo'], note: 'Atender is what you do for a customer or a visitor, and it puts a before the person: atender a un cliente. It is an -er verb, so the gerundio is atendiendo.'),
            new WordData('revisar', cue: 'to check (to go over)', forms: ['estoy revisando'], note: 'Revisar is to go over something to see if it is right: revisar el informe.'),
            new WordData('contestar', cue: 'to answer', forms: ['estoy contestando'], note: 'Contestar una llamada and contestar el teléfono both mean to answer the phone.'),
            new WordData('el cliente', cue: 'customer (client, man)', accepted: ['la clienta'], forms: ['clienta', 'clientes', 'clientas'], note: 'A woman is la clienta. Clientes is used for a group with men or for men only.'),
            new WordData('el informe', cue: 'report', forms: ['informes']),
            new WordData('la tarea', cue: 'task (a piece of work)', forms: ['tareas']),
            new WordData('la llamada', cue: 'call (phone call)', forms: ['llamadas']),
            new WordData('la reunión', cue: 'meeting', forms: ['reuniones'], note: 'Estar en una reunión is to be in a meeting.'),
            new WordData('ahora mismo', cue: 'right now', accepted: ['ahora'], note: 'Ahora mismo is right now, a little stronger than ahora (now). It goes at the start or at the end: Ahora mismo estoy trabajando.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Estoy trabajando ahora mismo.', 'english' => 'I am working right now.'],
            ['text' => 'Ana está atendiendo a un cliente.', 'english' => 'Ana is serving a customer.'],
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

    private function am(): TargetSpec
    {
        return Kit::word('ahora mismo', alternates: ['ahora']);
    }

    private function ay(): TargetSpec
    {
        return Kit::word('ayudar', 'ayudando');
    }

    private function at(): TargetSpec
    {
        return Kit::word('atender', 'atendiendo');
    }

    private function re(): TargetSpec
    {
        return Kit::word('revisar', 'revisando');
    }

    private function co(): TargetSpec
    {
        return Kit::word('contestar', 'contestando');
    }

    /** @param  list<string>  $alternates */
    private function cl(string $form = 'cliente', array $alternates = []): TargetSpec
    {
        return Kit::word('el cliente', $form, $alternates);
    }

    private function inf(): TargetSpec
    {
        return Kit::word('el informe', 'informe');
    }

    private function ta(): TargetSpec
    {
        return Kit::word('la tarea', 'tarea');
    }

    private function ll(): TargetSpec
    {
        return Kit::word('la llamada', 'llamada');
    }

    private function ru(): TargetSpec
    {
        return Kit::word('la reunión', 'reunión');
    }

    /**
     * @param  list<string>  $answers
     * @return list<string>
     */
    private function ahora(array $answers): array
    {
        return array_values(array_unique([...$answers, ...array_map(fn (string $answer): string => str_replace(['Ahora mismo', 'ahora mismo'], ['Ahora', 'ahora'], $answer), $answers)]));
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.ana-contestando', 'Ana ___ contestando el teléfono.', ['está', 'estoy'], 'está', Kit::form('está', true), 'Ana is one other person, so está. Estoy is only for yo.', 'choose', 'Ana is answering the phone.'),
            Kit::gap($stage, 'sentences.choose_gap.nosotros-revisando', 'Nosotros ___ revisando el informe.', ['estamos', 'están'], 'estamos', Kit::form('estamos', true), 'Nosotros goes with estamos. Están is for ellos or ellas.', 'choose', 'We are checking the report.'),
            Kit::gap($stage, 'sentences.choose_gap.marta-atendiendo', 'Marta está ___ a un cliente.', ['atendiendo', 'atender'], 'atendiendo', $this->at(), 'After está you need the gerundio. Atender is an -er verb, so it becomes atendiendo.', 'choose', 'Marta is serving a customer.'),
            Kit::gap($stage, 'sentences.choose_gap.companero-ayudando', 'Estoy ___ a mi compañero.', ['ayudando', 'ayudar'], 'ayudando', $this->ay(), 'After estoy you need the gerundio. Ayudar is an -ar verb, so it becomes ayudando.', 'choose', 'I am helping my colleague.'),
            Kit::gap($stage, 'sentences.choose_gap.luis-ahora-mismo', 'Luis está atendiendo a un cliente ___.', ['ahora mismo', 'por favor'], 'ahora mismo', $this->am(), 'Está atendiendo is something happening now, so ahora mismo fits. Por favor means please.', 'choose', 'Luis is serving a customer right now.'),
            Kit::gap($stage, 'sentences.choose_gap.reunion', 'Luis y Marta están en una ___.', ['reunión', 'informe', 'tarea'], 'reunión', $this->ru(), 'You are in a reunión. You cannot be in an informe or in a tarea, and informe is masculine, so una does not fit it.', 'choose', 'Luis and Marta are in a meeting.'),

            Kit::typeGap($stage, 'sentences.type_gap.tu-trabajando', 'Tú ___ trabajando en la oficina.', 'You are working in the office.', 'estás', Kit::form('estás'), 'Tú goes with estás: estás trabajando.'),
            Kit::typeGap($stage, 'sentences.type_gap.ellos-ayudando', 'Ellos ___ ayudando a Ana.', 'They are helping Ana.', 'están', Kit::form('están'), 'Ellos goes with están: están ayudando.'),
            Kit::typeGap($stage, 'sentences.type_gap.revisando', 'Estoy ___ el informe.', 'I am checking the report.', 'revisando', $this->re(), 'After estoy you need the gerundio of revisar: revisando.'),
            Kit::typeGap($stage, 'sentences.type_gap.luis-contestando', 'Luis está ___ el teléfono.', 'Luis is answering the phone.', 'contestando', $this->co(), 'After está you need the gerundio of contestar: contestando.'),
            Kit::typeGap($stage, 'sentences.type_gap.ahora', '___ mismo estoy atendiendo a un cliente.', 'Right now I am serving a customer.', 'Ahora', Kit::word('ahora mismo', 'ahora'), 'Ahora mismo means right now.'),

            Kit::translate($stage, 'sentences.translate.revisando-ahora', 'I am checking the report right now.', $this->ahora(['Estoy revisando el informe ahora mismo.', 'Ahora mismo estoy revisando el informe.', 'Yo estoy revisando el informe ahora mismo.', 'Yo ahora mismo estoy revisando el informe.']), [$this->re(), $this->inf(), $this->am(), Kit::form('estoy revisando')]),
            Kit::translate($stage, 'sentences.translate.pablo-llamada', 'Pablo is answering a call.', ['Pablo está contestando una llamada.'], [$this->co(), $this->ll(), Kit::form('está contestando')]),
            Kit::translate($stage, 'sentences.translate.ayudando-cliente', 'We are helping the customer (a man).', ['Estamos ayudando al cliente.', 'Nosotros estamos ayudando al cliente.'], [$this->ay(), $this->cl(), Kit::form('estamos ayudando', true)]),

            Kit::build($stage, 'sentences.build.luis-atendiendo', 'Luis is serving a customer.', 'Luis está atendiendo a un cliente.', ['estoy'], [$this->at(), $this->cl(), Kit::form('está atendiendo')]),
            Kit::build($stage, 'sentences.build.revisando-reunion', 'They are checking the report in the meeting.', 'Están revisando el informe en la reunión.', ['está'], [$this->re(), $this->inf(), $this->ru(), Kit::form('están revisando', true)]),
            Kit::build($stage, 'sentences.build.contestando-telefono', 'I am answering the phone.', 'Estoy contestando el teléfono.', ['estás'], [$this->co(), Kit::form('estoy contestando')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.atendiendo', 'Estoy atendiendo a un cliente.', ['I am serving a customer.', 'You are serving a customer.', 'He is serving a customer.', 'I am calling a customer.'], 'I am serving a customer.', [$this->at(), $this->cl(), Kit::form('estoy atendiendo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ahora-ayudando', 'Ahora mismo estamos ayudando a Ana.', ['Right now we are helping Ana.', 'Right now they are helping Ana.', 'Right now Ana is helping us.', 'Tomorrow we are helping Ana.'], 'Right now we are helping Ana.', [$this->am(), $this->ay(), Kit::form('estamos ayudando')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.revisando-tarea', 'Pablo está revisando la tarea.', ['Pablo is checking the task.', 'Pablo is checking the report.', 'Pablo is answering the call.', 'Ana is checking the task.'], 'Pablo is checking the task.', [$this->re(), $this->ta(), Kit::form('está revisando')]),
            Kit::listenType($stage, 'sentences.listen_type.contestando-llamada', 'Estoy contestando una llamada.', 'I am answering a call.', [$this->co(), $this->ll(), Kit::form('estoy contestando')]),
            Kit::listenType($stage, 'sentences.listen_type.marta-reunion', 'Marta está en una reunión.', 'Marta is in a meeting.', [Kit::word('la reunión', 'reunión')]),
            Kit::listenType($stage, 'sentences.listen_type.ahora-revisando', 'Ahora mismo estás revisando la tarea.', 'Right now you are checking the task.', [$this->am(), $this->re(), $this->ta(), Kit::form('estás revisando')]),
            Kit::listenType($stage, 'sentences.listen_type.ellos-ayudando', 'Ellos están ayudando a su jefe.', 'They are helping their boss.', [$this->ay(), Kit::form('están ayudando')], homophoneNote: self::A_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.revisando-reunion', 'Estoy revisando el informe en la reunión.', 'I am checking the report in the meeting.', [$this->re(), $this->inf(), $this->ru(), Kit::form('estoy revisando')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ahora-trabajando', 'Ahora mismo estoy trabajando.', 'Right now I am working.', [$this->am(), Kit::form('estoy trabajando')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ana-llamada', 'Ana está contestando una llamada.', 'Ana is answering a call.', [$this->co(), $this->ll(), Kit::form('está contestando')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.estamos-atendiendo', 'Estamos atendiendo a un cliente.', 'We are serving a customer.', [$this->at(), $this->cl(), Kit::form('estamos atendiendo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.trabajando', '¿Estás trabajando ahora mismo?', 'Are you working right now?', [['sí', 'no'], ['estoy', 'trabajando']], 'Sí, estoy trabajando ahora mismo.', [$this->am()]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.atendiendo', '¿Estás atendiendo a un cliente?', 'Are you serving a customer?', [['sí', 'no'], ['atendiendo', 'estoy']], 'Sí, estoy atendiendo a un cliente.', [$this->at(), $this->cl()]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.contestando', '¿Estás contestando una llamada?', 'Are you answering a call?', [['sí', 'no'], ['contestando', 'estoy']], 'Sí, estoy contestando una llamada.', [$this->co(), $this->ll()]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $all = [$this->am(), $this->re(), $this->inf(), $this->ru(), $this->ay(), $this->ta(), $this->at(), $this->cl(), $this->co(), $this->ll()];

        return [
            Kit::readPassage($stage, 'task.read_passage.oficina', 'Read the conversation between Ana and Pablo at work.', [
                Kit::line('Ana', 'Hola, Pablo. ¿Qué estás haciendo ahora mismo?'),
                Kit::line('Pablo', 'Estoy revisando el informe en la oficina. ¿Y tú?'),
                Kit::line('Ana', 'Estoy en una reunión. Estoy ayudando a mi jefe con la tarea.'),
                Kit::line('Pablo', 'Luis está atendiendo a un cliente y Marta está contestando una llamada.'),
            ], [
                Kit::question('What is Pablo doing?', ['He is checking the report', 'He is answering a call', 'He is serving a customer'], 'He is checking the report'),
                Kit::question('Where is Ana?', ['In a meeting', 'On the phone', 'At home'], 'In a meeting'),
                Kit::question('What is Marta doing?', ['She is answering a call', 'She is serving a customer', 'She is helping her boss'], 'She is answering a call'),
            ], $all, glosses: ['haciendo' => 'doing']),
            Kit::gap($stage, 'task.choose_gap.ellas-revisando', 'Ellas ___ revisando la tarea.', ['están', 'está'], 'están', Kit::form('están', true), 'Ellas is more than one person, so están. Está is for one person.', 'read', 'They are checking the task.'),
            Kit::gap($stage, 'task.choose_gap.estoy-contestando', 'Estoy ___ una llamada.', ['contestando', 'contestar'], 'contestando', $this->co(), 'After estoy you need the gerundio. Contestar is an -ar verb, so it becomes contestando.', 'read', 'I am answering a call.'),

            Kit::transform($stage, 'task.transform.nosotros-revisando', 'Change the subject to we.', 'Ana está revisando el informe en la reunión.', ['Estamos revisando el informe en la reunión.', 'Nosotros estamos revisando el informe en la reunión.', 'Nosotras estamos revisando el informe en la reunión.'], [$this->re(), $this->inf(), $this->ru(), Kit::form('estamos revisando', true)]),
            Kit::transform($stage, 'task.transform.ellos-atendiendo', 'Change the subject to they.', 'Marta está atendiendo a un cliente.', ['Ellos están atendiendo a un cliente.', 'Ellas están atendiendo a un cliente.', 'Están atendiendo a un cliente.'], [$this->at(), $this->cl(), Kit::form('están atendiendo', true)]),
            Kit::transform($stage, 'task.transform.pablo-ahora', 'Say that it is happening right now.', 'Pablo trabaja en la oficina.', $this->ahora(['Pablo está trabajando en la oficina ahora mismo.', 'Ahora mismo Pablo está trabajando en la oficina.', 'Pablo ahora mismo está trabajando en la oficina.', 'Pablo está trabajando ahora mismo en la oficina.']), [$this->am(), Kit::form('está trabajando', true)]),
            Kit::writeGuided($stage, 'task.write_guided.reunion-llamada', 'Say that you are in a meeting and that Marta is answering a call.', ['reunión', 'contestando', 'una llamada'], 'Estoy en una reunión y Marta está contestando una llamada.', [
                ['forms' => ['reunión'], 'term' => 'la reunión'],
                ['forms' => ['contestando'], 'term' => 'contestar'],
            ], [$this->ru(), $this->co(), $this->ll()]),
            Kit::writeGuided($stage, 'task.write_guided.atendiendo-ayudando', 'Say that Luis is serving a customer and that you are helping Ana.', ['atendiendo', 'un cliente', 'ayudando'], 'Luis está atendiendo a un cliente y yo estoy ayudando a Ana.', [
                ['forms' => ['atendiendo'], 'term' => 'atender'],
                ['forms' => ['ayudando'], 'term' => 'ayudar'],
            ], [$this->at(), $this->ay(), $this->cl()]),
            Kit::build($stage, 'task.build.pablo-ana-ayudando', 'Pablo and Ana are helping a customer (a man).', 'Pablo y Ana están ayudando a un cliente.', ['está', 'ayudar'], [$this->ay(), $this->cl(), Kit::form('están ayudando', true)]),
            Kit::build($stage, 'task.build.revisando-tarea', 'I am checking the task.', 'Estoy revisando la tarea.', ['estás', 'revisar'], [$this->re(), $this->ta(), Kit::form('estoy revisando')]),
            Kit::build($stage, 'task.build.contestando-llamada', 'We are answering the call.', 'Estamos contestando la llamada.', ['están', 'contestar'], [$this->co(), $this->ll(), Kit::form('estamos contestando', true)]),
            Kit::translate($stage, 'task.translate.ayudando-contestando', 'I am helping Ana, but Luis is answering a call.', ['Estoy ayudando a Ana, pero Luis está contestando una llamada.', 'Yo estoy ayudando a Ana, pero Luis está contestando una llamada.'], [$this->ay(), $this->co(), $this->ll(), Kit::form('estoy ayudando')]),
            Kit::translate($stage, 'task.translate.no-trabajando', 'Luis and Marta are not working right now.', $this->ahora(['Luis y Marta no están trabajando ahora mismo.', 'Ahora mismo Luis y Marta no están trabajando.', 'Luis y Marta ahora mismo no están trabajando.']), [$this->am(), Kit::form('están trabajando', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.reunion', [
                Kit::line('Marta', 'Luis, ¿dónde estás ahora mismo?'),
                Kit::line('Luis', 'Estoy en una reunión con el jefe.'),
                Kit::line('Marta', 'Yo estoy contestando una llamada. Ana está atendiendo a un cliente.'),
                Kit::line('Luis', 'Pablo está revisando el informe y ayudando a Ana con la tarea.'),
            ], [
                Kit::question('Where is Luis?', ['In a meeting', 'In the office', 'On the phone'], 'In a meeting'),
                Kit::question('What is Marta doing?', ['Answering a call', 'Serving a customer', 'Checking the report'], 'Answering a call'),
                Kit::question('Who is serving a customer?', ['Ana', 'Pablo', 'Luis'], 'Ana'),
            ], [
                Kit::question('Who is Luis in the meeting with?', ['His boss', 'A customer', 'Marta'], 'His boss'),
                Kit::question('What is Pablo checking?', ['The report', 'The email', 'The phone'], 'The report'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], $all),
            Kit::listenType($stage, 'task.listen_type.revisando-tarea-jefe', 'Ahora mismo estamos revisando la tarea con el jefe.', 'Right now we are checking the task with the boss.', [$this->am(), $this->re(), $this->ta(), Kit::form('estamos revisando')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.atendiendo-clientes', 'Ellos están atendiendo a los clientes en la oficina.', 'They are serving the customers in the office.', [$this->at(), $this->cl('clientes'), Kit::form('están atendiendo')], 'listen', homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'task.listen_type.ayudando-reunion', 'Estás ayudando a Luis en la reunión.', 'You are helping Luis in the meeting.', [$this->ay(), $this->ru(), Kit::form('estás ayudando')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.revisando', '¿Estás revisando el informe?', 'Are you checking the report?', [['sí', 'no'], ['revisando', 'contestando', 'estoy']], 'No, estoy contestando una llamada.', [$this->re(), $this->inf()], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.jefe-reunion', '¿Está tu jefe en una reunión ahora mismo?', 'Is your boss in a meeting right now?', [['sí', 'no'], ['reunión', 'está']], 'Sí, mi jefe está en una reunión.', [$this->ru(), $this->am()], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ayudando-companero', '¿Estás ayudando a un compañero?', 'Are you helping a colleague?', [['sí', 'no'], ['ayudando', 'estoy']], 'Sí, estoy ayudando a mi compañero con la tarea.', [$this->ay(), $this->ta()], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.trabajando-informe', '¿Estás trabajando en el informe ahora mismo?', 'Are you working on the report right now?', [['sí', 'no'], ['trabajando', 'estoy']], 'Sí, estoy trabajando en el informe.', [$this->inf(), $this->am()], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.ahora-ayudando', 'Ahora mismo estamos ayudando a un cliente.', 'Right now we are helping a customer.', [$this->am(), $this->ay(), $this->cl(), Kit::form('estamos ayudando')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.revisando-tarea', 'Marta y Pablo están revisando la tarea.', 'Marta and Pablo are checking the task.', [$this->re(), $this->ta(), Kit::form('están revisando')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.ayudando-cliente-ahora', 'I am helping a customer (a man) right now.', $this->ahora(['Estoy ayudando a un cliente ahora mismo.', 'Ahora mismo estoy ayudando a un cliente.', 'Yo estoy ayudando a un cliente ahora mismo.', 'Yo ahora mismo estoy ayudando a un cliente.']), [$this->ay(), $this->cl(), $this->am(), Kit::form('estoy ayudando')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-llamada-cliente', 'Ana is answering a call from a customer.', ['Ana está contestando una llamada de un cliente.', 'Ana está contestando una llamada de una clienta.'], [$this->co(), $this->ll(), $this->cl('cliente', ['clienta']), Kit::form('está contestando', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-tarea-reunion', 'Pablo is checking the task in the meeting.', ['Pablo está revisando la tarea en la reunión.'], [$this->re(), $this->ta(), $this->ru(), Kit::form('está revisando', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.atendiendo-clienta', 'We are serving the customer (a woman).', ['Estamos atendiendo a la clienta.', 'Nosotros estamos atendiendo a la clienta.'], [$this->at(), $this->cl('clienta'), Kit::form('estamos atendiendo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.luis-llamada', 'Luis está contestando una ___.', 'Luis is answering a call.', 'llamada', $this->ll(), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ayudando-tarea', 'Estoy ayudando con la ___.', 'I am helping with the task.', 'tarea', $this->ta(), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.trabajando-ayudando', 'Pablo está trabajando, pero Ana está ayudando a un cliente.', 'Pablo is working, but Ana is helping a customer.', [$this->ay(), $this->cl(), Kit::form('está trabajando', true)], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.llamada-cliente', 'Estás contestando la llamada del cliente.', 'You are answering the customer\'s call.', [$this->co(), $this->ll(), $this->cl(), Kit::form('estás contestando')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.informe-tarea', 'El informe y la tarea son para la reunión.', 'The report and the task are for the meeting.', [$this->inf(), $this->ta(), $this->ru()], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.oficina', [
                Kit::line('Pablo', 'Ana, ¿dónde estás ahora mismo?'),
                Kit::line('Ana', 'Estoy en la oficina. Estoy contestando una llamada de un cliente.'),
                Kit::line('Pablo', '¿Y Marta? ¿Está en una reunión?'),
                Kit::line('Ana', 'Sí, y Luis está revisando el informe.'),
            ], [
                Kit::question('Where is Ana?', ['In the office', 'In a meeting', 'At home'], 'In the office'),
                Kit::question('What is Ana doing?', ['Answering a call', 'Checking the report', 'Serving a customer'], 'Answering a call'),
                Kit::question('What is Luis doing?', ['Checking the report', 'Answering a call', 'Helping Marta'], 'Checking the report'),
            ], [
                Kit::question('Who is in a meeting?', ['Marta', 'Luis', 'Pablo'], 'Marta'),
                Kit::question('Who asks the questions?', ['Pablo', 'Ana', 'Luis'], 'Pablo'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$this->am(), $this->co(), $this->ll(), $this->ru(), $this->re(), $this->inf()], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.cliente', 'Read the conversation.', [
                Kit::line('Luis', 'Marta, ¿estás atendiendo a un cliente?'),
                Kit::line('Marta', 'No, ahora mismo estoy ayudando a Pablo con la tarea.'),
                Kit::line('Luis', 'Yo estoy revisando el informe para el jefe.'),
            ], [
                Kit::question('What is Marta doing?', ['Helping Pablo', 'Serving a customer', 'Answering a call'], 'Helping Pablo'),
                Kit::question('What is Luis checking?', ['The report', 'The task', 'The call'], 'The report'),
            ], [$this->at(), $this->cl(), $this->am(), $this->ay(), $this->ta(), $this->re(), $this->inf()], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cliente-ahora', '¿Estás atendiendo a un cliente ahora mismo?', 'Are you serving a customer right now?', [['sí', 'no'], ['atendiendo', 'estoy', 'ahora']], 'Sí, estoy atendiendo a un cliente.', [$this->at(), $this->cl(), $this->am()], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.jefe-llamada', '¿Está tu jefe contestando una llamada?', 'Is your boss answering a call?', [['sí', 'no'], ['contestando', 'está']], 'Sí, mi jefe está contestando una llamada.', [$this->co(), $this->ll()], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.revisando-tarea', '¿Estás revisando la tarea?', 'Are you checking the task?', [['sí', 'no'], ['revisando', 'ayudando', 'estoy']], 'No, estoy ayudando a un compañero.', [$this->re(), $this->ta(), $this->ay()], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.luis-marta-tarea', 'Luis is helping Marta with the task.', ['Luis está ayudando a Marta con la tarea.'], [$this->ay(), $this->ta(), Kit::form('está ayudando', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.contestando-ahora', 'I am answering a call right now.', $this->ahora(['Estoy contestando una llamada ahora mismo.', 'Ahora mismo estoy contestando una llamada.', 'Yo estoy contestando una llamada ahora mismo.', 'Yo ahora mismo estoy contestando una llamada.']), [$this->co(), $this->ll(), $this->am(), Kit::form('estoy contestando')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.atendiendo-clientes', 'They are serving the customers.', ['Están atendiendo a los clientes.', 'Ellos están atendiendo a los clientes.', 'Ellas están atendiendo a los clientes.', 'Están atendiendo a las clientas.'], [$this->at(), $this->cl('clientes', ['clientas']), Kit::form('están atendiendo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-informe-reunion', 'Marta is checking the report in the meeting.', ['Marta está revisando el informe en la reunión.'], [$this->re(), $this->inf(), $this->ru(), Kit::form('está revisando')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-reunion', 'Pablo está en una ___.', 'Pablo is in a meeting.', 'reunión', $this->ru(), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-atendiendo', 'Ana está ___ a una clienta.', 'Ana is serving a customer.', 'atendiendo', $this->at(), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.ahora-trabajando-informe', 'Ahora mismo estamos trabajando en el informe.', 'Right now we are working on the report.', [$this->am(), $this->inf(), Kit::form('estamos trabajando')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ayudando-cliente-llamada', 'Estás ayudando a un cliente con la llamada.', 'You are helping a customer with the call.', [$this->ay(), $this->cl(), $this->ll(), Kit::form('estás ayudando', true)], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.contestando-revisando', 'Luis está contestando el teléfono y revisando la tarea.', 'Luis is answering the phone and checking the task.', [$this->co(), $this->re(), $this->ta()], 'dictation', $set),
        ];
    }
}
