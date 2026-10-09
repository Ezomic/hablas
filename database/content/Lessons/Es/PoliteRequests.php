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

final class PoliteRequests implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'polite-requests';
    }

    public function words(): array
    {
        return [
            new WordData('el formulario', cue: 'form (to fill in)'),
            new WordData('el documento', cue: 'document'),
            new WordData('la factura', cue: 'invoice', note: 'La factura is the invoice a company sends you. It is not the form you fill in: that is el formulario.'),
            new WordData('el correo electrónico', cue: 'email', accepted: ['el correo'], note: 'In Spain el correo alone is often enough for an email. Correos, with an s, is the post office.'),
            new WordData('la ventanilla', cue: 'counter (service window)', note: 'La ventanilla is the window or counter where an office or a bank serves you.'),
            new WordData('enviar', cue: 'to send', forms: ['envío', 'envía', 'envíe', 'envíes'], note: 'Enviar is regular but the i carries an accent in most present forms (envío, envías, envía, envían) and in the commands.'),
            new WordData('firmar', cue: 'to sign', forms: ['firmo', 'firma', 'firme', 'firmes']),
            new WordData('rellenar', cue: 'to fill in (a form)', forms: ['relleno', 'rellena', 'rellene', 'rellenes']),
            new WordData('escribir', cue: 'to write', forms: ['escribo', 'escribe', 'escriba', 'escribas']),
            new WordData('pasar', cue: 'to go through (to come in)', forms: ['paso', 'pasa', 'pase', 'pases'], note: 'Pasar has many meanings. In an office, pase por aquí means come this way and pase alone means come in.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Firme aquí, por favor.', 'english' => 'Sign here, please.'],
            ['text' => 'No escriba en el documento.', 'english' => 'Do not write on the document.'],
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
            Kit::gap($stage, 'sentences.choose_gap.firme', '___ aquí, por favor.', ['Firme', 'Firma', 'Firmar'], 'Firme', Kit::form('firme', true), 'To a stranger or a client you use the usted command: firmar becomes firme. Firma is the informal tú form and firmar is only the infinitive.', 'choose', 'Sign here, please. (formal you)'),
            Kit::gap($stage, 'sentences.choose_gap.no-firme', 'No ___ el documento.', ['firme', 'firmes', 'firmo'], 'firme', Kit::form('firme', true), 'After no the usted command stays the same: no firme. Firmes is the tú form and firmo means I sign.', 'choose', 'Do not sign the document. (formal you)'),
            Kit::gap($stage, 'sentences.choose_gap.marta-formulario', 'Marta tiene el ___ en la mesa.', ['formulario', 'factura', 'ventanilla'], 'formulario', Kit::word('el formulario', 'formulario'), 'El goes with a masculine word, and formulario is masculine. Factura and ventanilla take la.', 'choose', 'Marta has the form on the table.'),
            Kit::gap($stage, 'sentences.choose_gap.envie', '___ la factura hoy, por favor.', ['Envíe', 'Envía', 'Enviar'], 'Envíe', Kit::form('envíe', true), 'Enviar is an -ar verb, so the usted command ends in -e, and the i keeps its accent: envíe. Envía is the tú form.', 'choose', 'Send the invoice today, please. (formal you)'),
            Kit::gap($stage, 'sentences.choose_gap.ventanilla', 'Marta está en la ___.', ['ventanilla', 'factura', 'correo'], 'ventanilla', Kit::word('la ventanilla', 'ventanilla'), 'La ventanilla is the counter where an office serves you. A factura is an invoice, and correo is masculine so it takes el.', 'choose', 'Marta is at the counter.'),
            Kit::gap($stage, 'sentences.choose_gap.correo', 'Ana envía un ___ a Pablo.', ['correo electrónico', 'factura', 'ventanilla'], 'correo electrónico', Kit::word('el correo electrónico', 'correo electrónico'), 'Un goes with a masculine word, and correo is masculine. Factura and ventanilla take una.', 'choose', 'Ana sends an email to Pablo.'),

            Kit::typeGap($stage, 'sentences.type_gap.documento', 'Firme el ___, por favor.', 'Sign the document, please. (formal you)', 'documento', Kit::word('el documento', 'documento')),
            Kit::typeGap($stage, 'sentences.type_gap.factura', 'Tengo una ___ para Luis.', 'I have an invoice for Luis.', 'factura', Kit::word('la factura', 'factura')),
            Kit::typeGap($stage, 'sentences.type_gap.rellene', '___ el formulario, por favor.', 'Fill in the form, please. (formal you)', 'Rellene', Kit::form('rellene'), 'Rellenar is an -ar verb, so the usted command ends in -e: rellene.'),
            Kit::typeGap($stage, 'sentences.type_gap.espere', '___ aquí, por favor.', 'Wait here, please. (formal you)', 'Espere', Kit::form('espere'), 'Esperar is an -ar verb, so the usted command ends in -e: espere.'),
            Kit::typeGap($stage, 'sentences.type_gap.escriba', 'No ___ aquí, por favor.', 'Do not write here, please. (formal you)', 'escriba', Kit::form('escriba'), 'Escribir is an -ir verb, so the usted command ends in -a: escriba. After no the form stays the same.'),

            Kit::translate($stage, 'sentences.translate.correo', 'Please send the email. (formal you)', ['Envíe el correo electrónico, por favor.', 'Por favor, envíe el correo electrónico.', 'Envíe el correo, por favor.', 'Por favor, envíe el correo.'], [Kit::word('el correo electrónico', 'correo electrónico', ['correo']), Kit::word('enviar', 'envíe'), Kit::form('envíe')]),
            Kit::translate($stage, 'sentences.translate.factura', 'Please sign the invoice. (formal you)', ['Firme la factura, por favor.', 'Por favor, firme la factura.'], [Kit::word('la factura', 'factura'), Kit::word('firmar', 'firme'), Kit::form('firme')]),
            Kit::translate($stage, 'sentences.translate.esperar', 'Could you wait here, please? (formal you)', ['¿Podría esperar aquí, por favor?', 'Por favor, ¿podría esperar aquí?', '¿Podría usted esperar aquí, por favor?', 'Por favor, ¿podría usted esperar aquí?'], [Kit::form('podría')]),

            Kit::build($stage, 'sentences.build.rellene', 'Fill in the form here. (formal you; start with the verb, then the form)', 'Rellene el formulario aquí.', ['rellena'], [Kit::word('el formulario', 'formulario'), Kit::word('rellenar', 'rellene'), Kit::form('rellene')]),
            Kit::build($stage, 'sentences.build.no-escriba', 'Do not write on the document. (formal you)', 'No escriba en el documento.', ['escribe'], [Kit::word('el documento', 'documento'), Kit::word('escribir', 'escriba'), Kit::form('no escriba', true)]),
            Kit::build($stage, 'sentences.build.podria-enviar', 'Could you send the invoice? (formal you)', '¿Podría enviar la factura?', ['envíe'], [Kit::word('la factura', 'factura'), Kit::word('enviar'), Kit::form('podría')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.pase', 'Pase por aquí, por favor.', ['Come this way, please.', 'Do not come this way, please.', 'Wait here, please.', 'Come this way tomorrow, please.'], 'Come this way, please.', [Kit::word('pasar', 'pase'), Kit::form('pase')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.firme', 'Firme el documento en la ventanilla.', ['Sign the document at the counter.', 'Send the document to the counter.', 'Do not sign the document at the counter.', 'Fill in the document at the counter.'], 'Sign the document at the counter.', [Kit::word('el documento', 'documento'), Kit::word('la ventanilla', 'ventanilla'), Kit::word('firmar', 'firme')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.no-envie', 'No envíe la factura hoy.', ['Do not send the invoice today.', 'Send the invoice today.', 'Do not send the invoice tomorrow.', 'I do not send the invoice today.'], 'Do not send the invoice today.', [Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::form('no envíe', true)]),
            Kit::listenType($stage, 'sentences.listen_type.formulario', 'Rellene el formulario y espere aquí.', 'Fill in the form and wait here. (formal you)', [Kit::word('el formulario', 'formulario'), Kit::word('rellenar', 'rellene'), Kit::form('rellene')]),
            Kit::listenType($stage, 'sentences.listen_type.ventanilla', 'Pase a la ventanilla, por favor.', 'Go to the counter, please. (formal you)', [Kit::word('pasar', 'pase'), Kit::word('la ventanilla', 'ventanilla'), Kit::form('pase')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.podria', '¿Podría enviar el correo electrónico?', 'Could you send the email? (formal you)', [Kit::word('enviar'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::form('podría')]),
            Kit::listenType($stage, 'sentences.listen_type.no-hables', 'No hables aquí, Luis.', 'Do not talk here, Luis. (informal you)', [Kit::form('no hables', true)]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.escriba', 'Escriba su número aquí.', 'Write your number here. (formal you)', [Kit::word('escribir', 'escriba'), Kit::form('escriba')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.envie', 'Envíe la factura hoy, por favor.', 'Send the invoice today, please. (formal you)', [Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::form('envíe')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.firme', 'Firme el formulario, por favor.', 'Sign the form, please. (formal you)', [Kit::word('firmar', 'firme'), Kit::word('el formulario', 'formulario')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pasar', '¿Podría pasar por la ventanilla?', 'Could you go to the counter? (formal you)', [Kit::word('pasar'), Kit::word('la ventanilla', 'ventanilla'), Kit::form('podría')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.firmo', '¿Dónde firmo?', 'Where do I sign?', [['firme'], ['aquí', 'allí', 'ventanilla', 'documento', 'formulario']], 'Firme aquí, por favor.', [Kit::word('firmar', 'firme'), Kit::form('firme')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.escribo', '¿Qué escribo?', 'What do I write?', [['escriba'], ['número', 'dirección', 'apellido', 'teléfono', 'correo']], 'Escriba su número, por favor.', [Kit::word('escribir', 'escriba'), Kit::form('escriba')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.relleno', '¿Dónde relleno el formulario?', 'Where do I fill in the form?', [['rellene'], ['aquí', 'allí', 'ventanilla', 'formulario']], 'Rellene el formulario aquí.', [Kit::word('el formulario', 'formulario'), Kit::word('rellenar', 'rellene')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.ventanilla', 'Read the conversation at the counter.', [
                Kit::line('Ana', 'Buenos días. Pase por aquí, por favor.'),
                Kit::line('Pablo', '¿Dónde firmo el documento?'),
                Kit::line('Ana', 'Firme aquí y escriba su número. Rellene el formulario.'),
                Kit::line('Pablo', 'Tengo una factura. ¿Qué hago?'),
                Kit::line('Ana', 'Envíe la factura por correo electrónico.'),
            ], [
                Kit::question('What does Pablo ask first?', ['Where to sign the document', 'Where to wait', 'Where to pay'], 'Where to sign the document'),
                Kit::question('What must Pablo fill in?', ['The form', 'The invoice', 'The email'], 'The form'),
                Kit::question('How does Pablo send the invoice?', ['By email', 'At the counter', 'By phone'], 'By email'),
            ], [Kit::word('pasar', 'pase'), Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::word('escribir', 'escriba'), Kit::word('rellenar', 'rellene'), Kit::word('el formulario', 'formulario'), Kit::word('la factura', 'factura'), Kit::word('enviar', 'envíe'), Kit::word('el correo electrónico', 'correo electrónico')], 'read'),
            Kit::gap($stage, 'task.choose_gap.podria-enviar', '¿Podría ___ el documento hoy?', ['enviar', 'envíe', 'envía'], 'enviar', Kit::form('enviar', true), 'After podría the verb stays in the infinitive: podría enviar. Envíe is the command and envía is the present.', 'read', 'Could you send the document today? (formal you)'),
            Kit::gap($stage, 'task.choose_gap.no-pongas', 'Luis, no ___ el documento en la mesa.', ['pongas', 'ponga', 'pones'], 'pongas', Kit::form('pongas', true), 'Luis is a friend, so the negative command uses tú: no pongas. Ponga is for usted and pones is a statement.', 'read', 'Luis, do not put the document on the table. (informal you)'),

            Kit::transform($stage, 'task.transform.no-firme', 'Say the opposite: do not do it. (formal you)', 'Firme el documento.', ['No firme el documento.'], [Kit::word('el documento', 'documento'), Kit::word('firmar', 'firme'), Kit::form('no firme', true)]),
            Kit::transform($stage, 'task.transform.podria', 'Ask politely with podría.', 'Envíe la factura.', ['¿Podría enviar la factura?', '¿Podría enviar la factura, por favor?'], [Kit::word('la factura', 'factura'), Kit::word('enviar'), Kit::form('podría')]),
            Kit::transform($stage, 'task.transform.no-firmes', 'Say it to Luis, who is a friend (informal you).', 'No firme aquí.', ['No firmes aquí.', 'Luis, no firmes aquí.'], [Kit::word('firmar', 'firmes'), Kit::form('no firmes', true)]),
            Kit::writeGuided($stage, 'task.write_guided.podria-factura', 'Ask a client politely to send the invoice by email.', ['podría enviar', 'la factura', 'por correo electrónico'], '¿Podría enviar la factura por correo electrónico?', [
                ['forms' => ['podría'], 'term' => null],
                ['forms' => ['enviar'], 'term' => 'enviar'],
                ['forms' => ['factura'], 'term' => 'la factura'],
                ['forms' => ['correo'], 'term' => 'el correo electrónico'],
            ], [Kit::word('la factura', 'factura'), Kit::word('enviar'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::form('podría')]),
            Kit::writeGuided($stage, 'task.write_guided.rellene-firme', 'Tell a client to fill in the form and not to sign the document.', ['rellene', 'el formulario', 'y no firme', 'el documento'], 'Rellene el formulario y no firme el documento.', [
                ['forms' => ['rellene'], 'term' => 'rellenar'],
                ['forms' => ['formulario'], 'term' => 'el formulario'],
                ['forms' => ['firme'], 'term' => 'firmar'],
                ['forms' => ['documento'], 'term' => 'el documento'],
            ], [Kit::word('rellenar', 'rellene'), Kit::word('el formulario', 'formulario'), Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::form('no firme', true)]),
            Kit::build($stage, 'task.build.pase', 'Come this way, please. (formal you; start with the verb)', 'Pase por aquí, por favor.', ['pasa', 'allí'], [Kit::word('pasar', 'pase'), Kit::form('pase')]),
            Kit::build($stage, 'task.build.no-ponga', 'Do not put the invoice on the table. (formal you)', 'No ponga la factura en la mesa.', ['pongas', 'un'], [Kit::word('la factura', 'factura'), Kit::form('no ponga', true)]),
            Kit::build($stage, 'task.build.firmar-enviar', 'Could you sign the document and send the email? (formal you)', '¿Podría firmar el documento y enviar el correo electrónico?', ['firme', 'envíe'], [Kit::word('firmar'), Kit::word('el documento', 'documento'), Kit::word('enviar'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::form('podría')]),
            Kit::translate($stage, 'task.translate.rellene-envie', 'Fill in the form and send the email, please. (formal you)', ['Rellene el formulario y envíe el correo electrónico, por favor.', 'Por favor, rellene el formulario y envíe el correo electrónico.', 'Rellene el formulario y envíe el correo, por favor.', 'Por favor, rellene el formulario y envíe el correo.'], [Kit::word('el formulario', 'formulario'), Kit::word('rellenar', 'rellene'), Kit::word('enviar', 'envíe'), Kit::word('el correo electrónico', 'correo electrónico', ['correo']), Kit::form('rellene')]),
            Kit::translate($stage, 'task.translate.no-firme-no-envie', 'Please do not sign the invoice and do not send the form. (formal you)', ['No firme la factura y no envíe el formulario, por favor.', 'Por favor, no firme la factura y no envíe el formulario.'], [Kit::word('la factura', 'factura'), Kit::word('el formulario', 'formulario'), Kit::word('firmar', 'firme'), Kit::word('enviar', 'envíe'), Kit::form('no firme', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.ventanilla-dos', [
                Kit::line('Marta', 'Buenos días. Tengo una factura y un documento.'),
                Kit::line('Luis', 'Pase a la ventanilla dos. Firme el documento aquí.'),
                Kit::line('Marta', '¿Envío el correo electrónico hoy?'),
                Kit::line('Luis', 'No, no envíe el correo hoy. Mañana, por favor.'),
                Kit::line('Marta', 'Muy bien. Gracias.'),
            ], [
                Kit::question('What does Marta have?', ['An invoice and a document', 'A form and an invoice', 'An email and a form'], 'An invoice and a document'),
                Kit::question('Where does Luis ask Marta to go?', ['To counter two', 'To the office', 'To the table'], 'To counter two'),
                Kit::question('When should Marta send the email?', ['Tomorrow', 'Today', 'On Monday'], 'Tomorrow'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Does Luis say to send the email today?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Does Marta have an invoice?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('la ventanilla', 'ventanilla'), Kit::word('pasar', 'pase'), Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::form('no envíe', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.no-escriba', 'No escriba su número en el documento.', 'Do not write your number on the document. (formal you)', [Kit::word('escribir', 'escriba'), Kit::word('el documento', 'documento'), Kit::form('no escriba', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta-rellena', 'Marta rellena el formulario y firma la factura.', 'Marta fills in the form and signs the invoice.', [Kit::word('rellenar', 'rellena'), Kit::word('el formulario', 'formulario'), Kit::word('firmar', 'firma'), Kit::word('la factura', 'factura')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.podria-pasar', '¿Podría pasar por la ventanilla y esperar aquí?', 'Could you go to the counter and wait here? (formal you)', [Kit::word('pasar'), Kit::word('la ventanilla', 'ventanilla'), Kit::form('podría')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.firmo', '¿Qué firmo?', 'What do I sign?', [['firme'], ['documento', 'factura', 'formulario']], 'Firme el documento, por favor.', [Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::form('firme')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.envio', '¿Qué envío?', 'What do I send?', [['envíe'], ['factura', 'documento', 'formulario', 'correo']], 'Envíe la factura, por favor.', [Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::form('envíe')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.relleno', '¿Qué relleno, el formulario o la factura?', 'What do I fill in, the form or the invoice?', [['rellene'], ['formulario']], 'Rellene el formulario, por favor.', [Kit::word('rellenar', 'rellene'), Kit::word('el formulario', 'formulario')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.paso', '¿Por dónde paso?', 'Which way do I go?', [['pase'], ['aquí', 'allí', 'ventanilla']], 'Pase por la ventanilla, por favor.', [Kit::word('pasar', 'pase'), Kit::word('la ventanilla', 'ventanilla'), Kit::form('pase')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.escriba', 'Escriba su número y su dirección.', 'Write your number and your address. (formal you)', [Kit::word('escribir', 'escriba'), Kit::form('escriba')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.podria', '¿Podría enviar el correo electrónico, por favor?', 'Could you send the email, please? (formal you)', [Kit::word('enviar'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::form('podría')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.formulario-documento', 'Please fill in the form and sign the document. (formal you)', ['Rellene el formulario y firme el documento, por favor.', 'Por favor, rellene el formulario y firme el documento.'], [Kit::word('el formulario', 'formulario'), Kit::word('el documento', 'documento'), Kit::word('rellenar', 'rellene'), Kit::word('firmar', 'firme'), Kit::form('rellene')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.factura-correo', 'Please do not send the invoice by email. (formal you)', ['No envíe la factura por correo electrónico, por favor.', 'Por favor, no envíe la factura por correo electrónico.', 'No envíe la factura por correo, por favor.', 'Por favor, no envíe la factura por correo.'], [Kit::word('la factura', 'factura'), Kit::word('el correo electrónico', 'correo electrónico', ['correo']), Kit::word('enviar', 'envíe'), Kit::form('no envíe', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ventanilla-numero', 'Could you go to the counter and write your number? (formal you)', ['¿Podría pasar por la ventanilla y escribir su número?', '¿Podría pasar a la ventanilla y escribir su número?', '¿Podría usted pasar por la ventanilla y escribir su número?', '¿Podría usted pasar a la ventanilla y escribir su número?'], [Kit::word('la ventanilla', 'ventanilla'), Kit::word('pasar'), Kit::word('escribir'), Kit::form('podría')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.luis-formulario', 'Luis, do not put the form on the table. (informal you)', ['Luis, no pongas el formulario en la mesa.', 'No pongas el formulario en la mesa, Luis.'], [Kit::word('el formulario', 'formulario'), Kit::form('no pongas', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.formulario-direccion', 'Escriba su dirección en el ___.', 'Write your address on the form. (formal you)', 'formulario', Kit::word('el formulario', 'formulario'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ventanilla', 'Pase por la ___, por favor.', 'Come to the counter, please. (formal you)', 'ventanilla', Kit::word('la ventanilla', 'ventanilla'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.escriba-firme', 'Escriba su número y firme aquí.', 'Write your number and sign here. (formal you)', [Kit::word('escribir', 'escriba'), Kit::word('firmar', 'firme'), Kit::form('escriba')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.factura-documento', 'Envíe la factura y el documento por correo electrónico.', 'Send the invoice and the document by email. (formal you)', [Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::word('el documento', 'documento'), Kit::word('el correo electrónico', 'correo electrónico'), Kit::form('envíe')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pase-rellene', 'Pase por aquí y rellene el formulario.', 'Come this way and fill in the form. (formal you)', [Kit::word('pasar', 'pase'), Kit::word('rellenar', 'rellene'), Kit::word('el formulario', 'formulario')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.formulario', [
                Kit::line('Pablo', 'Buenos días. ¿Qué hago con el formulario?'),
                Kit::line('Ana', 'Rellene el formulario, pero no firme el documento aquí.'),
                Kit::line('Pablo', '¿Dónde firmo?'),
                Kit::line('Ana', 'Firme en la ventanilla dos.'),
                Kit::line('Pablo', 'Muy bien. Gracias.'),
            ], [
                Kit::question('What must Pablo fill in?', ['The form', 'The invoice', 'The email'], 'The form'),
                Kit::question('What must Pablo not sign here?', ['The document', 'The form', 'The invoice'], 'The document'),
                Kit::question('Where should Pablo sign?', ['At counter two', 'Here', 'At the table'], 'At counter two'),
            ], [
                Kit::question('Who speaks first?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Does Ana say to sign the document here?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What does Pablo ask about first?', ['The form', 'The invoice', 'The counter'], 'The form'),
            ], [Kit::word('el formulario', 'formulario'), Kit::word('rellenar', 'rellene'), Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::word('la ventanilla', 'ventanilla'), Kit::form('no firme', true)], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ventanilla', 'Read the conversation.', [
                Kit::line('Marta', 'Buenos días. Tengo un documento.'),
                Kit::line('Luis', 'Pase por la ventanilla y espere aquí.'),
                Kit::line('Marta', '¿Dónde firmo?'),
                Kit::line('Luis', 'Firme el formulario y escriba su teléfono.'),
            ], [
                Kit::question('Where does Luis say to go?', ['To the counter', 'To the office', 'To the table'], 'To the counter'),
                Kit::question('What does Luis say to write?', ['Her phone number', 'Her address', 'Her surname'], 'Her phone number'),
            ], [Kit::word('el documento', 'documento'), Kit::word('pasar', 'pase'), Kit::word('la ventanilla', 'ventanilla'), Kit::word('firmar', 'firme'), Kit::word('el formulario', 'formulario'), Kit::word('escribir', 'escriba')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.escribo', '¿Qué escribo en el formulario?', 'What do I write on the form?', [['escriba'], ['número', 'dirección', 'apellido', 'teléfono']], 'Escriba su dirección, por favor.', [Kit::word('escribir', 'escriba'), Kit::word('el formulario', 'formulario')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.envio', '¿Dónde envío la factura?', 'Where do I send the invoice?', [['envíe'], ['correo', 'ventanilla', 'oficina']], 'Envíe la factura por correo electrónico.', [Kit::word('enviar', 'envíe'), Kit::word('la factura', 'factura'), Kit::word('el correo electrónico', 'correo electrónico')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.firmo', '¿Firmo la factura o el formulario?', 'Do I sign the invoice or the form?', [['firme'], ['formulario']], 'Firme el formulario, por favor.', [Kit::word('firmar', 'firme'), Kit::word('el formulario', 'formulario'), Kit::word('la factura', 'factura')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.formulario-factura', 'Send the form and the invoice by email. (formal you)', ['Envíe el formulario y la factura por correo electrónico.', 'Por correo electrónico, envíe el formulario y la factura.', 'Envíe el formulario y la factura por correo.', 'Por correo, envíe el formulario y la factura.'], [Kit::word('el formulario', 'formulario'), Kit::word('la factura', 'factura'), Kit::word('el correo electrónico', 'correo electrónico', ['correo']), Kit::word('enviar', 'envíe'), Kit::form('envíe')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.no-firme-formulario', 'Please do not sign the form. (formal you)', ['No firme el formulario, por favor.', 'Por favor, no firme el formulario.'], [Kit::word('el formulario', 'formulario'), Kit::word('firmar', 'firme'), Kit::form('no firme', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.rellenar-telefono', 'Could you fill in the form and write your phone number? (formal you)', ['¿Podría rellenar el formulario y escribir su teléfono?', '¿Podría rellenar el formulario y escribir su número de teléfono?', '¿Podría usted rellenar el formulario y escribir su teléfono?'], [Kit::word('rellenar'), Kit::word('escribir'), Kit::form('podría')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-factura', 'Ana, do not write on the invoice. (informal you)', ['Ana, no escribas en la factura.', 'No escribas en la factura, Ana.'], [Kit::word('la factura', 'factura'), Kit::form('no escribas', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ventanilla-espere', 'Pase a la ___ y espere aquí.', 'Go to the counter and wait here. (formal you)', 'ventanilla', Kit::word('la ventanilla', 'ventanilla'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pase-firme', '___ por aquí y firme el documento.', 'Come this way and sign the document. (formal you)', 'Pase', Kit::form('pase'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.firme-envie', 'Firme el documento y envíe el correo electrónico.', 'Sign the document and send the email. (formal you)', [Kit::word('firmar', 'firme'), Kit::word('el documento', 'documento'), Kit::word('enviar', 'envíe'), Kit::word('el correo electrónico', 'correo electrónico')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pase-rellene', 'Pase a la ventanilla y rellene el formulario.', 'Go to the counter and fill in the form. (formal you)', [Kit::word('pasar', 'pase'), Kit::word('la ventanilla', 'ventanilla'), Kit::word('rellenar', 'rellene'), Kit::word('el formulario', 'formulario'), Kit::form('rellene')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.pase-escriba', 'Pase y escriba su teléfono en el documento.', 'Come in and write your phone number on the document. (formal you)', [Kit::word('pasar', 'pase'), Kit::word('escribir', 'escriba'), Kit::word('el documento', 'documento')], 'dictation', $set),
        ];
    }
}
