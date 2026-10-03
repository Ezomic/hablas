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

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la familia', cue: 'family'),
            new WordData('el padre', cue: 'father', accepted: ['el papá']),
            new WordData('la madre', cue: 'mother', accepted: ['la mamá']),
            new WordData('el hermano', cue: 'brother'),
            new WordData('la hermana', cue: 'sister'),
            new WordData('el hijo', cue: 'son'),
            new WordData('los abuelos', cue: 'grandparents'),
            new WordData('casado', cue: 'married (masculine)', forms: ['casada']),
            new WordData('soltero', cue: 'single, not married (masculine)', forms: ['soltera']),
            new WordData('mayor', cue: 'older (than someone else)', accepted: ['más viejo']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mi hermano es mayor.', 'english' => 'My brother is older.'],
            ['text' => 'Mis hermanos son mayores.', 'english' => 'My brothers and sisters are older.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference. papá and mamá accepted for padre and madre (accents kept). Fixed: accepted más viejo for mayor; grammar example Mis abuelos son mayores replaced by Mis hermanos son mayores (mayores alone with grandparents reads as elderly, not older). Open questions answered and removed.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.mis-hermanos', '___ hermanos son mayores.', ['Mis', 'Mi', 'Yo'], 'Mis', Kit::form('mis', true), 'Hermanos is plural, so the possessive is plural too.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.mi-madre', '___ madre está aquí.', ['Mi', 'Mis', 'Tú'], 'Mi', Kit::form('mi'), 'Madre is one person, so the possessive stays singular.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tus-hermanos', '___ hermanos están aquí.', ['Tus', 'Tu', 'Tú'], 'Tus', Kit::form('tus', true), 'A plural noun needs the plural possessive. Tú with an accent means you, not your.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.hermana-soltera', 'Mi hermana es ___.', ['soltera', 'soltero'], 'soltera', Kit::word('soltero', 'soltera'), 'Hermana is feminine, so the adjective ends in -a.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.hermano-casado', 'Pablo está ___.', ['casado', 'casada'], 'casado', Kit::word('casado'), 'Pablo is a man, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.marta-hermana', 'Marta es mi ___.', ['hermana', 'hermano'], 'hermana', Kit::word('la hermana', 'hermana'), 'Marta is a woman, so the feminine noun.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.mi-hermano', '___ hermano es mayor.', 'My brother is older.', 'Mi', Kit::form('mi'), 'One brother, so the possessive is singular.'),
            Kit::typeGap($stage, 'sentences.type_gap.mis-abuelos', '___ abuelos están aquí.', 'My grandparents are here.', 'Mis', Kit::form('mis', true), 'Abuelos is plural, so the possessive is plural too.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-hermano', 'Pablo es ___ hermano.', 'Pablo is your brother.', 'tu', Kit::form('tu'), 'Tu without an accent means your. One brother, so it stays singular.'),
            Kit::typeGap($stage, 'sentences.type_gap.familia', 'La ___ está aquí.', 'The family is here.', 'familia', Kit::word('la familia', 'familia')),
            Kit::typeGap($stage, 'sentences.type_gap.sus-hermanos', 'Marta y ___ hermanos están aquí.', 'Marta and her brothers are here.', 'sus', Kit::form('sus', true), 'Su and sus agree with the thing owned: more than one brother, so the plural.'),

            Kit::translate($stage, 'sentences.translate.padre', 'My father is here.', ['Mi padre está aquí.'], [Kit::word('el padre', 'padre'), Kit::form('mi')]),
            Kit::translate($stage, 'sentences.translate.hermanos', 'My brothers are here.', ['Mis hermanos están aquí.'], [Kit::word('el hermano', 'hermanos'), Kit::form('mis', true)]),
            Kit::translate($stage, 'sentences.translate.madre', 'Your mother is married.', ['Tu madre está casada.'], [Kit::word('la madre', 'madre'), Kit::word('casado', 'casada'), Kit::form('tu')]),
            Kit::build($stage, 'sentences.build.hijo-soltero', 'Her son is single.', 'Su hijo es soltero.', ['sus'], [Kit::word('el hijo', 'hijo'), Kit::word('soltero'), Kit::form('su')]),
            Kit::build($stage, 'sentences.build.abuelos', 'My grandparents are here.', 'Mis abuelos están aquí.', ['mi'], [Kit::word('los abuelos', 'abuelos'), Kit::form('mis', true)]),
            Kit::build($stage, 'sentences.build.hermano-mayor', 'Your brother is older.', 'Tu hermano es mayor.', ['tus'], [Kit::word('el hermano', 'hermano'), Kit::word('mayor'), Kit::form('tu')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.madre', 'Mi madre está aquí.', ['My mother is here.', 'My father is here.', 'My sister is here.', 'My family is here.'], 'My mother is here.', [Kit::word('la madre', 'madre'), Kit::form('mi')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hermanos', 'Mis hermanos son mayores.', ['My brothers are older.', 'My brother is older.', 'My sons are older.', 'My grandparents are older.'], 'My brothers are older.', [Kit::word('el hermano', 'hermanos'), Kit::word('mayor', 'mayores'), Kit::form('mis', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.hijo', 'Su hijo está casado.', ['Her son is married.', 'Her son is single.', 'Her sons are married.', 'Her mother is married.'], 'Her son is married.', [Kit::word('el hijo', 'hijo'), Kit::word('casado'), Kit::form('su')]),
            Kit::listenType($stage, 'sentences.listen_type.hermana', 'Mi hermana es soltera.', 'My sister is single.', [Kit::word('la hermana', 'hermana'), Kit::word('soltero', 'soltera'), Kit::form('mi')]),
            Kit::listenType($stage, 'sentences.listen_type.abuelos', 'Tus abuelos están aquí.', 'Your grandparents are here.', [Kit::word('los abuelos', 'abuelos'), Kit::form('tus', true)]),
            Kit::listenType($stage, 'sentences.listen_type.familia', 'La familia está aquí.', 'The family is here.', [Kit::word('la familia')]),
            Kit::listenType($stage, 'sentences.listen_type.padre-madre', 'Mi padre y mi madre están aquí.', 'My father and my mother are here.', [Kit::word('el padre', 'padre'), Kit::word('la madre', 'madre'), Kit::form('mi')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.hermano', 'Mi hermano es mayor.', 'My brother is older.', [Kit::word('el hermano', 'hermano'), Kit::word('mayor'), Kit::form('mi')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hijo', 'Su hijo está casado.', 'Her son is married.', [Kit::word('el hijo', 'hijo'), Kit::word('casado'), Kit::form('su')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hermana', 'Marta es la hermana de Pablo.', 'Marta is Pablo\'s sister.', [Kit::word('la hermana')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.hermana-mayor', 'Mi hermana es mayor.', 'My sister is older.', [Kit::word('la hermana', 'hermana'), Kit::word('mayor'), Kit::form('mi')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.hermanos', '¿Tienes hermanos?', 'Do you have brothers or sisters?', [['sí', 'no', 'tengo', 'hermano', 'hermanos', 'hermana', 'una', 'dos', 'uno']], 'Sí, tengo una hermana.', [Kit::word('la hermana', 'hermana')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.casado', '¿Tu hermano está casado?', 'Is your brother married?', [['sí', 'no', 'está', 'mi', 'casado', 'soltero']], 'Sí, mi hermano está casado.', [Kit::word('el hermano', 'hermano'), Kit::word('casado'), Kit::form('mi')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.marta', '¿Quién es Marta?', 'Who is Marta?', [['mi', 'es', 'marta', 'la'], ['hermana', 'madre', 'familia']], 'Marta es mi hermana.', [Kit::word('la hermana', 'hermana'), Kit::form('mi')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.familia', 'Read the conversation about Marta\'s family.', [
                Kit::line('Pablo', '¿Tienes hermanos, Marta?'),
                Kit::line('Marta', 'Sí, tengo una hermana y un hermano. Mi hermana es soltera.'),
                Kit::line('Pablo', '¿Y tu hermano?'),
                Kit::line('Marta', 'Mi hermano es mayor y está casado. Su hijo está aquí.'),
                Kit::line('Pablo', '¿Y tu padre y tu madre?'),
                Kit::line('Marta', 'Mi familia está aquí: mis abuelos, mi padre y mi madre.'),
            ], [
                Kit::question('Who is single?', ['Marta\'s sister', 'Marta\'s brother', 'Marta\'s mother'], 'Marta\'s sister'),
                Kit::question('Who is described as older and married?', ['Her sister', 'Her brother', 'Her father'], 'Her brother'),
                Kit::question('Who is here with Marta?', ['Only her brother', 'Her grandparents, her father and her mother', 'Nobody'], 'Her grandparents, her father and her mother'),
            ], [Kit::word('la familia'), Kit::word('el padre'), Kit::word('la madre'), Kit::word('el hermano'), Kit::word('la hermana'), Kit::word('el hijo'), Kit::word('los abuelos'), Kit::word('casado'), Kit::word('soltero'), Kit::word('mayor')]),
            Kit::gap($stage, 'task.choose_gap.padre', 'Luis es el ___ de Marta.', ['padre', 'madre'], 'padre', Kit::word('el padre', 'padre'), 'El goes with a masculine noun, so the father.', 'read'),
            Kit::gap($stage, 'task.choose_gap.madre', 'Ana es la ___ de Luis.', ['madre', 'padre'], 'madre', Kit::word('la madre', 'madre'), 'La goes with a feminine noun, so the mother.', 'read'),

            Kit::transform($stage, 'task.transform.hermanos', 'Make it plural.', 'Su hermano está casado.', ['Sus hermanos están casados.'], [Kit::word('el hermano', 'hermanos'), Kit::word('casado', 'casados'), Kit::form('sus', true)]),
            Kit::transform($stage, 'task.transform.hijos', 'Make it plural.', 'Tu hijo es soltero.', ['Tus hijos son solteros.'], [Kit::word('el hijo', 'hijos'), Kit::word('soltero', 'solteros'), Kit::form('tus', true)]),
            Kit::transform($stage, 'task.transform.pregunta', 'Make it a question.', 'Tu padre está aquí.', ['¿Está aquí tu padre?', '¿Tu padre está aquí?', '¿Está tu padre aquí?'], [Kit::word('el padre', 'padre'), Kit::form('tu')]),
            Kit::writeGuided($stage, 'task.write_guided.hermana', 'Say that you have a sister and that she is single.', ['hermana', 'soltera'], 'Tengo una hermana soltera.', [
                ['forms' => ['hermana'], 'term' => 'la hermana'],
                ['forms' => ['soltera'], 'term' => 'soltero'],
            ], [Kit::word('la hermana'), Kit::word('soltero')]),
            Kit::writeGuided($stage, 'task.write_guided.hermano', 'Say that your brother is older and married.', ['hermano', 'mayor', 'casado'], 'Mi hermano es mayor y está casado.', [
                ['forms' => ['hermano'], 'term' => 'el hermano'],
                ['forms' => ['mayor'], 'term' => 'mayor'],
                ['forms' => ['casado'], 'term' => 'casado'],
            ], [Kit::word('el hermano'), Kit::word('mayor'), Kit::word('casado')]),
            Kit::build($stage, 'task.build.familia', 'Marta and her family are here.', 'Marta y su familia están aquí.', ['sus', 'está'], [Kit::word('la familia', 'familia'), Kit::form('su')]),
            Kit::build($stage, 'task.build.madre-abuelos', 'My mother is here with my grandparents.', 'Mi madre está aquí con mis abuelos.', ['tus', 'son'], [Kit::word('la madre', 'madre'), Kit::word('los abuelos', 'abuelos'), Kit::form('mis', true)]),
            Kit::build($stage, 'task.build.hermano-casado', 'Her brother is older and married.', 'Su hermano es mayor y está casado.', ['casada', 'sus'], [Kit::word('el hermano', 'hermano'), Kit::word('mayor'), Kit::word('casado'), Kit::form('su')]),
            Kit::translate($stage, 'task.translate.hermanos', 'Pablo is my brother and Marta is my sister.', ['Pablo es mi hermano y Marta es mi hermana.'], [Kit::word('el hermano', 'hermano'), Kit::word('la hermana', 'hermana'), Kit::form('mi')]),
            Kit::translate($stage, 'task.translate.casada', 'My mother is married and my father is too.', ['Mi madre está casada y mi padre también.', 'Mi madre está casada y mi padre está casado también.', 'Mi madre está casada y mi padre también está casado.'], [Kit::word('la madre', 'madre'), Kit::word('el padre', 'padre'), Kit::word('casado', 'casada'), Kit::form('mi')]),

            Kit::listenPassage($stage, 'task.listen_passage.hermano', [
                Kit::line('Luis', '¿Tu hermano está casado, Ana?'),
                Kit::line('Ana', 'Sí, y su hijo está aquí.'),
                Kit::line('Luis', '¿Y tu hermana?'),
                Kit::line('Ana', 'Mi hermana es soltera. Mis abuelos están con ella.'),
                Kit::line('Luis', 'Muy bien. Mi familia también está aquí.'),
            ], [
                Kit::question('Is Ana\'s brother married?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who is single?', ['Ana\'s brother', 'Ana\'s sister', 'Ana\'s mother'], 'Ana\'s sister'),
                Kit::question('Who is with Ana\'s sister?', ['Her grandparents', 'Her father', 'Luis'], 'Her grandparents'),
            ], [
                Kit::question('Where is the son of Ana\'s brother?', ['Here', 'At home', 'The conversation does not say.'], 'Here'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Whose family is mentioned at the end?', ['Luis\'s', 'Ana\'s', 'Marta\'s'], 'Luis\'s'),
            ], [Kit::word('el hermano'), Kit::word('casado'), Kit::word('el hijo'), Kit::word('la hermana'), Kit::word('soltero'), Kit::word('los abuelos'), Kit::word('la familia')]),
            Kit::listenType($stage, 'task.listen_type.hermano-mayor', 'Mi hermano mayor está casado.', 'My older brother is married.', [Kit::word('el hermano', 'hermano'), Kit::word('mayor'), Kit::word('casado'), Kit::form('mi')]),
            Kit::listenType($stage, 'task.listen_type.familia', 'La familia de Pablo está aquí.', 'Pablo\'s family is here.', [Kit::word('la familia')]),
            Kit::listenType($stage, 'task.listen_type.abuelos-padre', 'Tus abuelos y tu padre están aquí.', 'Your grandparents and your father are here.', [Kit::word('los abuelos', 'abuelos'), Kit::word('el padre', 'padre'), Kit::form('tus', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.madre', '¿Está casada tu madre?', 'Is your mother married?', [['sí', 'no', 'está', 'mi', 'casada', 'soltera']], 'Sí, mi madre está casada.', [Kit::word('la madre', 'madre'), Kit::word('casado', 'casada'), Kit::form('mi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.familia', '¿Tienes familia aquí?', 'Do you have family here?', [['sí', 'no', 'tengo', 'mi', 'familia', 'hermano', 'hermana', 'padre', 'madre', 'abuelos']], 'Sí, mi familia está aquí.', [Kit::word('la familia', 'familia'), Kit::form('mi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mayor', '¿Tu hermano es mayor?', 'Is your brother older?', [['sí', 'no', 'es', 'mi', 'mayor', 'hermano']], 'Sí, mi hermano es mayor.', [Kit::word('el hermano', 'hermano'), Kit::word('mayor'), Kit::form('mi')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.abuelos', '¿Dónde están tus abuelos?', 'Where are your grandparents?', [['mis', 'abuelos', 'están', 'aquí', 'allí']], 'Mis abuelos están allí.', [Kit::word('los abuelos', 'abuelos'), Kit::form('mis', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.padre', '¿Dónde está tu padre?', 'Where is your father?', [Kit::word('el padre', 'padre'), Kit::form('tu')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.hijo', 'Su hijo es soltero.', 'Her son is single.', [Kit::word('el hijo', 'hijo'), Kit::word('soltero'), Kit::form('su')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.hermana', 'Your sister is older.', ['Tu hermana es mayor.'], [Kit::word('la hermana', 'hermana'), Kit::word('mayor'), Kit::form('tu')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.padre', 'Her father is married.', ['Su padre está casado.'], [Kit::word('el padre', 'padre'), Kit::word('casado'), Kit::form('su')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.hermanos', 'My brothers are single.', ['Mis hermanos son solteros.'], [Kit::word('el hermano', 'hermanos'), Kit::word('soltero', 'solteros'), Kit::form('mis', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.hijo', 'My son is with my grandparents.', ['Mi hijo está con mis abuelos.'], [Kit::word('el hijo', 'hijo'), Kit::word('los abuelos', 'abuelos')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sus-abuelos', 'Marta y ___ abuelos están aquí.', 'Marta and her grandparents are here.', 'sus', Kit::form('sus', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.tu-madre', '¿Dónde está ___ madre?', 'Where is your mother?', 'tu', Kit::form('tu'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.soltero', 'Mi hermano es soltero.', 'My brother is single.', [Kit::word('el hermano', 'hermano'), Kit::word('soltero'), Kit::form('mi')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.familia', 'Luis está con su familia.', 'Luis is with his family.', [Kit::word('la familia', 'familia')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.madre', 'La madre de Pablo está aquí.', 'Pablo\'s mother is here.', [Kit::word('la madre', 'madre')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.hermana', [
                Kit::line('Ana', '¿Tu hermana está casada, Pablo?'),
                Kit::line('Pablo', 'No, es soltera. Mi hermano está casado.'),
                Kit::line('Ana', '¿Y tus abuelos?'),
                Kit::line('Pablo', 'Mis abuelos están allí.'),
            ], [
                Kit::question('Is Pablo\'s sister married?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Who is married?', ['Pablo\'s sister', 'Pablo\'s brother', 'Ana\'s brother'], 'Pablo\'s brother'),
                Kit::question('Where are Pablo\'s grandparents?', ['Here', 'There', 'The conversation does not say.'], 'There'),
            ], [
                Kit::question('Who asks the questions?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Ana ask about last?', ['The grandparents', 'The sister', 'The brother'], 'The grandparents'),
            ], [Kit::word('la hermana'), Kit::word('casado'), Kit::word('soltero'), Kit::word('el hermano'), Kit::word('los abuelos')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.madre', 'Read the conversation.', [
                Kit::line('Luis', 'Ana, ¿tu madre está aquí?'),
                Kit::line('Ana', 'Sí, y mi padre también.'),
                Kit::line('Luis', '¿Y tu hijo?'),
                Kit::line('Ana', 'Mi hijo no está aquí.'),
            ], [
                Kit::question('Who is here?', ['Her mother and her father', 'Her mother and her son', 'Only her son'], 'Her mother and her father'),
                Kit::question('Is Ana\'s son here?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('la madre'), Kit::word('el padre'), Kit::word('el hijo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.hermana', '¿Tienes una hermana o un hermano?', 'Do you have a sister or a brother?', [['sí', 'no', 'tengo', 'hermana', 'hermano', 'hermanos', 'una', 'un']], 'Tengo una hermana.', [Kit::word('la hermana', 'hermana')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.soltero', '¿Tu hermano es soltero?', 'Is your brother single?', [['sí', 'no', 'es', 'está', 'soltero', 'casado']], 'Sí, mi hermano es soltero.', [Kit::word('soltero')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.mayor', '¿Quién es mayor, tu hermano o tu hermana?', 'Who is older, your brother or your sister?', [['mi', 'mis', 'es', 'tengo', 'el', 'la'], ['hermano', 'hermana', 'mayor']], 'Mi hermano es mayor.', [Kit::word('mayor')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.padre-madre', 'My mother is here with my father.', ['Mi madre está aquí con mi padre.'], [Kit::word('la madre', 'madre'), Kit::word('el padre', 'padre'), Kit::form('mi')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hermana', 'Her sister is married.', ['Su hermana está casada.'], [Kit::word('la hermana', 'hermana'), Kit::word('casado', 'casada'), Kit::form('su')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.hijos', 'My sons are single.', ['Mis hijos son solteros.'], [Kit::word('el hijo', 'hijos'), Kit::word('soltero', 'solteros'), Kit::form('mis', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.abuelos', 'My grandparents are married.', ['Mis abuelos están casados.'], [Kit::word('los abuelos', 'abuelos'), Kit::word('casado', 'casados'), Kit::form('mis', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.tus-hijos', '___ hijos están aquí.', 'Your sons are here.', 'Tus', Kit::form('tus', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.su-madre', 'Ana está con ___ madre.', 'Ana is with her mother.', 'su', Kit::form('su'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.hermana', 'Mi hermana mayor está aquí.', 'My older sister is here.', [Kit::word('la hermana', 'hermana'), Kit::word('mayor')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.familia', 'Ana tiene familia aquí.', 'Ana has family here.', [Kit::word('la familia', 'familia')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.hijo', 'Mi hermano tiene un hijo.', 'My brother has a son.', [Kit::word('el hermano', 'hermano'), Kit::word('el hijo', 'hijo')], 'dictation', $set),
        ];
    }
}
