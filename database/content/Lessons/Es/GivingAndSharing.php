<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GivingAndSharing implements UnitContent
{
    private const A_NOTE = 'A without an h means to. It sounds the same as ha, a form of haber, but here it is a, as in a Ana.';

    private const GIFTS = ['flor', 'flores', 'ramo', 'tarta', 'libro', 'regalo', 'sorpresa', 'abrazo'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'giving-and-sharing';
    }

    public function words(): array
    {
        return [
            new WordData('regalar', cue: 'to give (as a gift)', forms: ['regalo', 'regalas', 'regala', 'regalamos', 'regalan'], note: 'Regalar is to give something as a present. For giving in general, use dar: le doy un libro.'),
            new WordData('gustar', cue: 'to like (to be pleasing)', forms: ['gusta', 'gustan'], note: 'Gustar means to be pleasing to someone. Me gusta la tarta is the cake pleases me, so the verb follows the thing: gusta for one thing, gustan for several.'),
            new WordData('enseñar', cue: 'to show', forms: ['enseño', 'enseñas', 'enseña', 'enseñamos', 'enseñan'], note: 'Enseñar is to show. It can also mean to teach.'),
            new WordData('preguntar', cue: 'to ask (a question)', forms: ['pregunto', 'preguntas', 'pregunta', 'preguntamos', 'preguntan'], note: 'Preguntar is to ask a question. To ask for something, like a coffee, you use pedir.'),
            new WordData('la sorpresa', cue: 'surprise'),
            new WordData('la tarta', cue: 'cake', forms: ['tartas']),
            new WordData('la flor', cue: 'flower', forms: ['flores'], note: 'Flor is feminine, and the plural is flores without an accent mark.'),
            new WordData('la invitación', cue: 'invitation', forms: ['invitaciones']),
            new WordData('el ramo', cue: 'bouquet', note: 'El ramo de flores is a bouquet of flowers.'),
            new WordData('el abrazo', cue: 'hug', note: 'Dar un abrazo is to give a hug.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Le doy un regalo a Ana.', 'english' => 'I give Ana a gift.'],
            ['text' => 'A Marta le gustan las flores.', 'english' => 'Marta likes flowers.'],
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
        return [];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.le-libro', 'Luis ___ da un libro a Marta.', ['le', 'les', 'me'], 'le', Kit::form('le', true), 'A Marta is one person, so you need le. Les is for more than one person, and me would mean to me.', 'choose', 'Luis gives Marta a book.'),
            Kit::gap($stage, 'sentences.choose_gap.les-abuelos', 'Ana ___ da flores a los abuelos.', ['les', 'le', 'nos'], 'les', Kit::form('les', true), 'Los abuelos is more than one person, so you need les. Le is for one person.', 'choose', 'Ana gives flowers to the grandparents.'),
            Kit::gap($stage, 'sentences.choose_gap.me-abrazo', 'Ana, ¿___ das un abrazo?', ['me', 'te', 'le'], 'me', Kit::form('me'), 'The speaker asks Ana for a hug, so the hug is for me. Te would mean to you.', 'choose', 'Ana, will you give me a hug?'),
            Kit::gap($stage, 'sentences.choose_gap.nos-tarta', 'A nosotros ___ gusta la tarta.', ['nos', 'les', 'me'], 'nos', Kit::form('nos'), 'A nosotros means us, so the pronoun is nos. Les is for them and me is for me.', 'choose', 'We like the cake.'),
            Kit::gap($stage, 'sentences.choose_gap.gustan-flores', 'A Marta le ___ las flores.', ['gustan', 'gusta'], 'gustan', Kit::word('gustar', 'gustan'), 'Gustar agrees with what is liked. Las flores is plural, so you need gustan. Gusta is for one thing.', 'choose', 'Marta likes the flowers.'),
            Kit::gap($stage, 'sentences.choose_gap.abrazo-marta', 'Doy un ___ a Marta.', ['abrazo', 'tarta', 'flor'], 'abrazo', Kit::word('el abrazo', 'abrazo'), 'Un is masculine, so it goes with abrazo. Tarta and flor are feminine, so they would need una.', 'choose', 'I give Marta a hug.'),

            Kit::typeGap($stage, 'sentences.type_gap.le-flor', 'Yo ___ regalo una flor a Ana.', 'I give Ana a flower as a gift.', 'le', Kit::form('le'), 'A Ana is one person, so you need le before the verb.'),
            Kit::typeGap($stage, 'sentences.type_gap.tarta', 'El café y la ___ son para Marta.', 'The coffee and the cake are for Marta.', 'tarta', Kit::word('la tarta', 'tarta')),
            Kit::typeGap($stage, 'sentences.type_gap.ramo', 'Doy un ___ de flores a mi madre.', 'I give my mother a bouquet of flowers.', 'ramo', Kit::word('el ramo', 'ramo')),
            Kit::typeGap($stage, 'sentences.type_gap.te-invitacion', 'Marta, ¿___ doy la invitación?', 'Marta, shall I give you the invitation?', 'te', Kit::form('te'), 'Marta is the person you speak to, so the pronoun is te.'),
            Kit::typeGap($stage, 'sentences.type_gap.gusta-tarta', 'Me ___ la tarta.', 'I like the cake.', 'gusta', Kit::word('gustar', 'gusta')),

            Kit::translate($stage, 'sentences.translate.abrazo-ana', 'I give Ana a hug.', ['Le doy un abrazo a Ana.', 'Yo le doy un abrazo a Ana.', 'A Ana le doy un abrazo.'], [Kit::word('el abrazo', 'abrazo'), Kit::form('le')]),
            Kit::translate($stage, 'sentences.translate.nos-tarta', 'They give us a cake as a gift.', ['Nos regalan una tarta.', 'Ellos nos regalan una tarta.'], [Kit::word('regalar', 'regalan'), Kit::word('la tarta', 'tarta'), Kit::form('nos')]),
            Kit::translate($stage, 'sentences.translate.marta-flores', 'Marta likes the flowers.', ['A Marta le gustan las flores.', 'Le gustan las flores a Marta.', 'A Marta le gustan las flores'], [Kit::word('gustar', 'gustan'), Kit::word('la flor', 'flores'), Kit::form('le')]),

            Kit::build($stage, 'sentences.build.ensenar-invitacion', 'I show Pablo the invitation.', 'Le enseño la invitación a Pablo.', ['les'], [Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación'), Kit::form('le')]),
            Kit::build($stage, 'sentences.build.preguntar-fiesta', 'Luis asks me when the party is.', 'Luis me pregunta cuándo es la fiesta.', ['te'], [Kit::word('preguntar', 'pregunta'), Kit::form('me')]),
            Kit::build($stage, 'sentences.build.sorpresa', 'Pablo gives me a surprise.', 'Pablo me da una sorpresa.', ['le'], [Kit::word('la sorpresa', 'sorpresa'), Kit::form('me')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.flor', 'Le regalo una flor a Ana.', ['I give Ana a flower as a gift.', 'Ana gives me a flower as a gift.', 'We give Ana a flower as a gift.', 'I give Ana flowers as a gift.'], 'I give Ana a flower as a gift.', [Kit::word('la flor', 'flor'), Kit::word('regalar', 'regalo'), Kit::form('le')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nos-gusta', 'Nos gusta la tarta.', ['We like the cake.', 'I like the cake.', 'They like the cake.', 'We like the cakes.'], 'We like the cake.', [Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gusta'), Kit::form('nos')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.invitacion', 'Pablo les da una invitación.', ['Pablo gives them an invitation.', 'Pablo gives her an invitation.', 'Pablo gives us an invitation.', 'Pablo gives me an invitation.'], 'Pablo gives them an invitation.', [Kit::word('la invitación', 'invitación'), Kit::form('les')]),
            Kit::listenType($stage, 'sentences.listen_type.ramo', 'Marta me regala un ramo de flores.', 'Marta gives me a bouquet of flowers.', [Kit::word('el ramo', 'ramo'), Kit::word('regalar', 'regala'), Kit::form('me')]),
            Kit::listenType($stage, 'sentences.listen_type.ensenar', 'Te enseño la invitación.', 'I show you the invitation.', [Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación'), Kit::form('te')]),
            Kit::listenType($stage, 'sentences.listen_type.preguntar', 'Luis le pregunta a Ana.', 'Luis asks Ana.', [Kit::word('preguntar', 'pregunta'), Kit::form('le')], homophoneNote: self::A_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.abrazo', 'Ana nos da un abrazo.', 'Ana gives us a hug.', [Kit::word('el abrazo', 'abrazo'), Kit::form('nos')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.abrazo', 'Le doy un abrazo a Ana.', 'I give Ana a hug.', [Kit::word('el abrazo', 'abrazo'), Kit::form('le')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tarta', 'Nos gusta la tarta.', 'We like the cake.', [Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gusta'), Kit::form('nos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ramo', 'Te regalo un ramo de flores.', 'I give you a bouquet of flowers.', [Kit::word('el ramo', 'ramo'), Kit::word('la flor', 'flores'), Kit::word('regalar', 'regalo'), Kit::form('te')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.invitacion', 'Les enseño la invitación.', 'I show them the invitation.', [Kit::word('la invitación', 'invitación'), Kit::word('enseñar', 'enseño'), Kit::form('les')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.regalas', '¿Qué le regalas a Ana?', 'What do you give Ana as a gift?', [['regalo', 'doy'], self::GIFTS], 'Le regalo una sorpresa.', [Kit::word('regalar', 'regalo'), Kit::word('la sorpresa', 'sorpresa'), Kit::form('le')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.gusta', '¿Te gusta la tarta?', 'Do you like the cake?', [['sí', 'no'], ['gusta', 'tarta']], 'Sí, me gusta la tarta.', [Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gusta'), Kit::form('me')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.abrazo', '¿Le das un abrazo a Ana?', 'Do you give Ana a hug?', [['sí', 'no'], ['doy', 'abrazo']], 'Sí, le doy un abrazo.', [Kit::word('el abrazo', 'abrazo'), Kit::form('le')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.cumpleanos-marta', 'Read the conversation about the birthday.', [
                Kit::line('Ana', 'Pablo, mañana es la fiesta de Marta.'),
                Kit::line('Pablo', '¿Qué le regalas tú?'),
                Kit::line('Ana', 'Le regalo un ramo de flores. ¿Y tú?'),
                Kit::line('Pablo', 'Yo le doy un libro y una tarta.'),
                Kit::line('Ana', '¡Qué bien! A Marta le gustan las flores.'),
                Kit::line('Pablo', 'Y Luis nos pregunta por la fiesta.'),
                Kit::line('Ana', 'Yo le enseño la invitación.'),
            ], [
                Kit::question('What does Ana give Marta?', ['A bouquet of flowers', 'A book', 'A cake'], 'A bouquet of flowers'),
                Kit::question('What does Pablo give Marta?', ['A book and a cake', 'A bouquet of flowers', 'An invitation'], 'A book and a cake'),
                Kit::question('What does Ana show Luis?', ['The invitation', 'The cake', 'The book'], 'The invitation'),
            ], [Kit::word('regalar', 'regalo'), Kit::word('el ramo', 'ramo'), Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gustan'), Kit::word('preguntar', 'pregunta'), Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación')], 'read'),
            Kit::gap($stage, 'task.choose_gap.cumpleanos', 'Hoy es mi fiesta. Ana ___ regala un libro.', ['me', 'te', 'le'], 'me', Kit::form('me', true), 'It is my party, so the gift is for me. Te would be for the person you speak to, and le for a third person.', 'read', 'Today is my party. Ana gives me a book as a gift.'),
            Kit::gap($stage, 'task.choose_gap.pregunta', 'Luis le ___ a Ana: ¿Quieres venir a la fiesta?', ['pregunta', 'regala', 'enseña'], 'pregunta', Kit::word('preguntar', 'pregunta'), 'Luis asks a question, so you need pregunta. Regala is to give a gift and enseña is to show.', 'read', 'Luis asks Ana: Do you want to come to the party?'),

            Kit::transform($stage, 'task.transform.les-flor', 'Say it about two people: Marta and Luis.', 'Le regalo una flor a Ana.', ['Les regalo una flor a Marta y a Luis.', 'Les regalo una flor a Marta y Luis.', 'A Marta y a Luis les regalo una flor.', 'A Marta y Luis les regalo una flor.'], [Kit::word('la flor', 'flor'), Kit::word('regalar', 'regalo'), Kit::form('les', true)]),
            Kit::transform($stage, 'task.transform.te-sorpresa', 'Say that Pablo gives it to you (informal you).', 'Pablo le da una sorpresa a Ana.', ['Pablo te da una sorpresa.'], [Kit::word('la sorpresa', 'sorpresa'), Kit::form('te', true)]),
            Kit::transform($stage, 'task.transform.gustan-tartas', 'Say it about more than one cake.', 'Me gusta la tarta.', ['Me gustan las tartas.', 'Me gustan las tartas'], [Kit::word('la tarta', 'tartas'), Kit::word('gustar', 'gustan'), Kit::form('me', true)]),
            Kit::writeGuided($stage, 'task.write_guided.ramo-tarta', 'Say that you give Marta a bouquet of flowers and a cake.', ['le', 'doy', 'ramo', 'flores', 'tarta', 'Marta'], 'Le doy un ramo de flores y una tarta a Marta.', [
                ['forms' => ['le'], 'term' => null],
                ['forms' => ['ramo'], 'term' => 'el ramo'],
                ['forms' => ['tarta'], 'term' => 'la tarta'],
            ], [Kit::word('el ramo', 'ramo'), Kit::word('la tarta', 'tarta'), Kit::form('le')]),
            Kit::writeGuided($stage, 'task.write_guided.invitacion-sorpresa', 'Say that you show Pablo and Marta the invitation, and that they like the surprise.', ['les', 'enseño', 'invitación', 'gusta', 'sorpresa'], 'Les enseño la invitación y les gusta la sorpresa.', [
                ['forms' => ['les'], 'term' => null],
                ['forms' => ['invitación'], 'term' => 'la invitación'],
                ['forms' => ['sorpresa'], 'term' => 'la sorpresa'],
                ['forms' => ['enseño'], 'term' => 'enseñar'],
            ], [Kit::word('la invitación', 'invitación'), Kit::word('la sorpresa', 'sorpresa'), Kit::word('enseñar', 'enseño'), Kit::form('les')]),
            Kit::build($stage, 'task.build.nos-ramo', 'Marta gives us a bouquet of flowers (start with the person).', 'Marta nos regala un ramo de flores.', ['me', 'le'], [Kit::word('el ramo', 'ramo'), Kit::word('regalar', 'regala'), Kit::form('nos')]),
            Kit::build($stage, 'task.build.pregunta-invitacion', 'Luis asks Ana about the invitation (start with the person).', 'Luis le pregunta a Ana por la invitación.', ['les', 'me'], [Kit::word('preguntar', 'pregunta'), Kit::word('la invitación', 'invitación'), Kit::form('le')]),
            Kit::build($stage, 'task.build.no-gusta', 'I do not like the cake.', 'No me gusta la tarta.', ['gustan', 'te'], [Kit::word('gustar', 'gusta'), Kit::form('me')]),
            Kit::translate($stage, 'task.translate.nos-ensena', 'Ana shows us the flowers and the cake.', ['Ana nos enseña las flores y la tarta.'], [Kit::word('enseñar', 'enseña'), Kit::word('la flor', 'flores'), Kit::word('la tarta', 'tarta'), Kit::form('nos')]),
            Kit::translate($stage, 'task.translate.les-sorpresa', 'I give my grandparents a surprise for their party.', ['Les doy una sorpresa a mis abuelos para su fiesta.', 'Les doy una sorpresa a los abuelos para su fiesta.', 'A mis abuelos les doy una sorpresa para su fiesta.', 'A los abuelos les doy una sorpresa para su fiesta.'], [Kit::word('la sorpresa', 'sorpresa'), Kit::form('les')]),

            Kit::listenPassage($stage, 'task.listen_passage.regalos', [
                Kit::line('Marta', 'Luis, ¿qué le regalas a Ana?'),
                Kit::line('Luis', 'Le regalo un ramo de flores. ¿Y tú?'),
                Kit::line('Marta', 'Yo le doy una tarta. A Ana le gustan las tartas.'),
                Kit::line('Luis', 'Muy bien. ¡Es una sorpresa para Ana!'),
                Kit::line('Marta', 'Sí, claro.'),
            ], [
                Kit::question('What does Luis give Ana?', ['A bouquet of flowers', 'A cake', 'A book'], 'A bouquet of flowers'),
                Kit::question('What does Marta give Ana?', ['A cake', 'A bouquet of flowers', 'A hug'], 'A cake'),
                Kit::question('What does Ana like?', ['Cakes', 'Flowers', 'Books'], 'Cakes'),
            ], [
                Kit::question('Who asks the first question?', ['Marta', 'Luis', 'Ana'], 'Marta'),
                Kit::question('Is it a surprise for Ana?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('regalar', 'regalo'), Kit::word('el ramo', 'ramo'), Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gustan'), Kit::word('la sorpresa', 'sorpresa')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.abuelos', 'Mis abuelos me regalan un ramo de flores.', 'My grandparents give me a bouquet of flowers.', [Kit::word('regalar', 'regalan'), Kit::word('el ramo', 'ramo'), Kit::form('me')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.luis-nos', 'Luis nos pregunta cuándo es la fiesta.', 'Luis asks us when the party is.', [Kit::word('preguntar', 'pregunta'), Kit::form('nos')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.te-da', 'Ana te da una sorpresa y un abrazo.', 'Ana gives you a surprise and a hug.', [Kit::word('la sorpresa', 'sorpresa'), Kit::word('el abrazo', 'abrazo'), Kit::form('te')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.pablo', '¿Qué le regalas a Pablo?', 'What do you give Pablo as a gift?', [['regalo', 'doy'], self::GIFTS], 'Le regalo un libro.', [Kit::word('regalar', 'regalo'), Kit::form('le')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tarta-flores', '¿Te gusta la tarta o las flores?', 'Do you like the cake or the flowers?', [['gusta', 'gustan'], ['tarta', 'flores']], 'Me gustan las flores.', [Kit::word('la tarta', 'tarta'), Kit::word('la flor', 'flores'), Kit::word('gustar', 'gustan'), Kit::form('me')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.ensenas', '¿Qué le enseñas a Luis?', 'What do you show Luis?', [['enseño'], ['invitación', 'foto', 'tarta', 'ramo']], 'Le enseño la invitación.', [Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación'), Kit::form('le')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.preguntas', '¿Le preguntas a Ana por la fiesta?', 'Do you ask Ana about the party?', [['sí', 'no'], ['pregunto', 'pregunta']], 'Sí, le pregunto por la fiesta.', [Kit::word('preguntar', 'pregunto'), Kit::form('le')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.sorpresa', 'Le doy una sorpresa y un abrazo a Marta.', 'I give Marta a surprise and a hug.', [Kit::word('la sorpresa', 'sorpresa'), Kit::word('el abrazo', 'abrazo'), Kit::form('le')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.flores-tarta', 'Nos gustan las flores y la tarta.', 'We like the flowers and the cake.', [Kit::word('la flor', 'flores'), Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gustan'), Kit::form('nos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.libro-abrazo', 'I give Luis a book and a hug.', ['Le doy un libro y un abrazo a Luis.', 'Yo le doy un libro y un abrazo a Luis.', 'A Luis le doy un libro y un abrazo.'], [Kit::word('el abrazo', 'abrazo'), Kit::form('le', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tarta-fiesta', 'Ana gives them a cake for the party.', ['Ana les da una tarta para la fiesta.', 'Ana les regala una tarta para la fiesta.'], [Kit::word('la tarta', 'tarta'), Kit::form('les', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pablo-ensena', 'Pablo shows me the invitation.', ['Pablo me enseña la invitación.'], [Kit::word('enseñar', 'enseña'), Kit::word('la invitación', 'invitación'), Kit::form('me')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.gustan-flores', 'We like the flowers.', ['Nos gustan las flores.', 'Las flores nos gustan.'], [Kit::word('gustar', 'gustan'), Kit::word('la flor', 'flores'), Kit::form('nos')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.madre-ramo', 'Mi madre me regala un ___ de flores.', 'My mother gives me a bouquet of flowers.', 'ramo', Kit::word('el ramo', 'ramo'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.fiesta-sorpresa', 'La fiesta es una ___ para Luis.', 'The party is a surprise for Luis.', 'sorpresa', Kit::word('la sorpresa', 'sorpresa'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pablo-te', 'Pablo te regala una sorpresa.', 'Pablo gives you a surprise.', [Kit::word('regalar', 'regala'), Kit::word('la sorpresa', 'sorpresa'), Kit::form('te')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-flores', 'Luis le pregunta por las flores.', 'Luis asks her about the flowers.', [Kit::word('preguntar', 'pregunta'), Kit::word('la flor', 'flores'), Kit::form('le')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.marta-gusta', 'A Marta le gusta la tarta.', 'Marta likes the cake.', [Kit::word('gustar', 'gusta'), Kit::word('la tarta', 'tarta')], 'dictation', $set, homophoneNote: self::A_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.cumpleanos-ana', [
                Kit::line('Luis', 'Marta, mañana es la fiesta de Ana.'),
                Kit::line('Marta', 'Sí. ¿Qué le regalamos?'),
                Kit::line('Luis', 'Yo le doy un ramo de flores y un abrazo.'),
                Kit::line('Marta', 'Yo le regalo una sorpresa.'),
                Kit::line('Luis', 'Muy bien.'),
            ], [
                Kit::question('Whose party is tomorrow?', ['Ana', 'Marta', 'Luis'], 'Ana'),
                Kit::question('What does Luis give?', ['A bouquet of flowers and a hug', 'A cake', 'A surprise'], 'A bouquet of flowers and a hug'),
                Kit::question('What does Marta give Ana?', ['A surprise', 'A cake', 'A bouquet of flowers'], 'A surprise'),
            ], [
                Kit::question('Who speaks first?', ['Luis', 'Marta', 'Nobody'], 'Luis'),
                Kit::question('Do they give Ana something?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('regalar', 'regalamos'), Kit::word('el ramo', 'ramo'), Kit::word('el abrazo', 'abrazo'), Kit::word('la sorpresa', 'sorpresa')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.invitacion', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿te enseño la invitación?'),
                Kit::line('Pablo', 'Sí. ¿Es para la fiesta de Marta?'),
                Kit::line('Ana', 'Sí. Luis nos pregunta por la tarta.'),
                Kit::line('Pablo', 'A Marta le gustan las tartas.'),
            ], [
                Kit::question('What does Ana show Pablo?', ['The invitation', 'The cake', 'The flowers'], 'The invitation'),
                Kit::question('What does Luis ask about?', ['The cake', 'The party', 'The flowers'], 'The cake'),
            ], [Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación'), Kit::word('preguntar', 'pregunta'), Kit::word('la tarta', 'tarta')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.regalas-flores', '¿Le regalas flores a Marta?', 'Do you give Marta flowers?', [['sí', 'no'], ['regalo', 'flores']], 'Sí, le regalo flores.', [Kit::word('regalar', 'regalo'), Kit::word('la flor', 'flores'), Kit::form('le')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.gusta-ramo', '¿Te gusta el ramo de flores?', 'Do you like the bouquet of flowers?', [['sí', 'no'], ['gusta', 'ramo']], 'Sí, me gusta el ramo.', [Kit::word('el ramo', 'ramo'), Kit::word('gustar', 'gusta'), Kit::form('me')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ensena-invitacion', '¿Te enseña Pablo la invitación?', 'Does Pablo show you the invitation?', [['sí', 'no'], ['enseña', 'invitación']], 'Sí, me enseña la invitación.', [Kit::word('enseñar', 'enseña'), Kit::word('la invitación', 'invitación'), Kit::form('me')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ramo-cumpleanos', 'Pablo gives her a bouquet of flowers for the party.', ['Pablo le regala un ramo de flores para la fiesta.', 'Pablo le da un ramo de flores para la fiesta.'], [Kit::word('regalar', 'regala', ['da']), Kit::word('el ramo', 'ramo'), Kit::word('la flor', 'flores'), Kit::form('le', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ensenar-tarta', 'I show you the invitation and the cake.', ['Te enseño la invitación y la tarta.', 'Yo te enseño la invitación y la tarta.'], [Kit::word('enseñar', 'enseño'), Kit::word('la invitación', 'invitación'), Kit::word('la tarta', 'tarta'), Kit::form('te')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.abuelos-sorpresa', 'My grandparents like the surprise.', ['A mis abuelos les gusta la sorpresa.', 'Les gusta la sorpresa a mis abuelos.'], [Kit::word('gustar', 'gusta'), Kit::word('la sorpresa', 'sorpresa'), Kit::form('les', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-abrazo', 'Marta gives us a hug and flowers.', ['Marta nos da un abrazo y flores.', 'Marta nos regala un abrazo y flores.'], [Kit::word('el abrazo', 'abrazo'), Kit::word('la flor', 'flores'), Kit::form('nos')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-pregunta', 'Pablo le ___ a Ana por la fiesta.', 'Pablo asks Ana about the party.', 'pregunta', Kit::word('preguntar', 'pregunta'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.luis-sorpresa', 'Pablo tiene una ___ para Marta.', 'Pablo has a surprise for Marta.', 'sorpresa', Kit::word('la sorpresa', 'sorpresa'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-me', 'Marta me regala un ramo y un abrazo.', 'Marta gives me a bouquet and a hug.', [Kit::word('regalar', 'regala'), Kit::word('el ramo', 'ramo'), Kit::word('el abrazo', 'abrazo'), Kit::form('me')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ana-pregunta', 'Ana le pregunta por la invitación.', 'Ana asks him about the invitation.', [Kit::word('preguntar', 'pregunta'), Kit::word('la invitación', 'invitación'), Kit::form('le')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pablo-tarta', 'Pablo enseña la tarta y a Luis le gusta.', 'Pablo shows the cake and Luis likes it.', [Kit::word('enseñar', 'enseña'), Kit::word('la tarta', 'tarta'), Kit::word('gustar', 'gusta')], 'dictation', $set, homophoneNote: self::A_NOTE),
        ];
    }
}
