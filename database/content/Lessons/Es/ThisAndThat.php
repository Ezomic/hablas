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

final class ThisAndThat implements UnitContent
{
    private const array THINGS = ['vaso', 'bolso', 'coche', 'mochila', 'foto', 'regalo', 'paraguas', 'bolígrafo', 'sombrero'];

    private const array NAMES = ['ana', 'pablo', 'marta', 'luis'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'this-and-that';
    }

    public function words(): array
    {
        return [
            new WordData('el vaso', cue: 'glass (for drinking)', forms: ['vasos']),
            new WordData('el bolso', cue: 'bag, handbag', forms: ['bolsos']),
            new WordData('el coche', cue: 'car', forms: ['coches']),
            new WordData('la mochila', cue: 'backpack', forms: ['mochilas']),
            new WordData('la foto', cue: 'photo', accepted: ['la fotografía'], forms: ['fotos']),
            new WordData('el regalo', cue: 'gift, present', forms: ['regalos']),
            new WordData('el paraguas', cue: 'umbrella', note: 'Paraguas does not change in the plural: el paraguas, los paraguas.'),
            new WordData('el bolígrafo', cue: 'pen', accepted: ['el boli'], forms: ['bolígrafos']),
            new WordData('el sombrero', cue: 'hat', forms: ['sombreros']),
            new WordData('preferir', cue: 'to prefer', forms: ['prefiero', 'prefieres', 'prefiere'], note: 'The verb changes its stem: prefiero, prefieres, prefiere.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Esta mochila es de Ana.', 'english' => 'This backpack is Ana\'s.'],
            ['text' => '¿Cuál prefieres, ese bolso o aquel coche?', 'english' => 'Which do you prefer, that bag or that car over there?'],
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
            Kit::gap($stage, 'sentences.choose_gap.vaso', '___ vaso es de Ana.', ['Este', 'Esta', 'Estos'], 'Este', Kit::form('este', true), 'Vaso is masculine and singular, so this is este. Esta is for feminine words and estos is for several things.', 'choose', 'This glass is Ana\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.mochila', '___ mochila es de Pablo.', ['Esta', 'Este', 'Estas'], 'Esta', Kit::form('esta', true), 'Mochila is feminine and singular, so this is esta. Este is for masculine words and estas is for several things.', 'choose', 'This backpack is Pablo\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.coches', '___ coches son de Luis.', ['Esos', 'Ese', 'Esas'], 'Esos', Kit::form('esos', true), 'Coches is masculine and plural, so that is esos. Ese is for one thing and esas is for feminine words.', 'choose', 'Those cars (near you) are Luis\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.foto', '¿De quién es ___ foto?', ['esa', 'ese', 'esas'], 'esa', Kit::form('esa', true), 'Foto is feminine and singular, so that is esa. Ese is for masculine words and esas is for several things.', 'choose', 'Whose photo is that (near you)?'),
            Kit::gap($stage, 'sentences.choose_gap.esto', '¿Qué es ___?', ['esto', 'este', 'esta'], 'esto', Kit::form('esto', true), 'When you do not know the thing, use the neutral esto. It has no gender, so este and esta do not fit.', 'choose', 'What is this?'),
            Kit::gap($stage, 'sentences.choose_gap.prefiero', 'Yo ___ este coche.', ['prefiero', 'prefieres', 'prefiere'], 'prefiero', Kit::word('preferir', 'prefiero'), 'Yo goes with prefiero. Prefieres is for tú and prefiere is for él or ella.', 'choose', 'I prefer this car.'),

            Kit::typeGap($stage, 'sentences.type_gap.bolso', '___ bolso es de Marta.', 'This bag is Marta\'s.', 'Este', Kit::form('este'), 'Bolso is masculine and singular, so this is este.'),
            Kit::typeGap($stage, 'sentences.type_gap.prefieres', '¿Cuál ___, este bolso o ese bolso?', 'Which do you prefer, this bag (near me) or that bag (near you)? (informal you)', 'prefieres', Kit::word('preferir', 'prefieres')),
            Kit::typeGap($stage, 'sentences.type_gap.boligrafos', 'Estos ___ son de Ana.', 'These pens are Ana\'s.', 'bolígrafos', Kit::word('el bolígrafo', 'bolígrafos')),
            Kit::typeGap($stage, 'sentences.type_gap.regalo', '___ regalo es para Luis.', 'That gift (near you) is for Luis.', 'Ese', Kit::form('ese'), 'Regalo is masculine and singular, so that is ese.'),
            Kit::typeGap($stage, 'sentences.type_gap.eso', '¿Qué es ___? Es un paraguas.', 'What is that (near you)? It is an umbrella.', 'eso', Kit::form('eso'), 'The neutral eso has no gender, so it is right when you have not named the thing.'),

            Kit::translate($stage, 'sentences.translate.boligrafo', 'This pen is Luis\'s.', ['Este bolígrafo es de Luis.'], [Kit::word('el bolígrafo', 'bolígrafo'), Kit::form('este')]),
            Kit::translate($stage, 'sentences.translate.sombrero', 'I prefer that hat (near you).', ['Prefiero ese sombrero.', 'Yo prefiero ese sombrero.'], [Kit::word('el sombrero', 'sombrero'), Kit::word('preferir', 'prefiero'), Kit::form('ese')]),
            Kit::translate($stage, 'sentences.translate.esto', 'What is this? It is a gift.', ['¿Qué es esto? Es un regalo.'], [Kit::word('el regalo', 'regalo'), Kit::form('esto')]),

            Kit::build($stage, 'sentences.build.foto', 'That photo (near you) is Marta\'s.', 'Esa foto es de Marta.', ['ese'], [Kit::word('la foto', 'foto'), Kit::form('esa')]),
            Kit::build($stage, 'sentences.build.mochila', 'I prefer this backpack.', 'Prefiero esta mochila.', ['este'], [Kit::word('la mochila', 'mochila'), Kit::word('preferir', 'prefiero'), Kit::form('esta')]),
            Kit::build($stage, 'sentences.build.bolso', 'Whose bag is this?', '¿De quién es este bolso?', ['esta'], [Kit::word('el bolso', 'bolso'), Kit::form('este')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.mochila', 'Esta mochila es de Marta.', ['This backpack is Marta\'s.', 'That backpack is Marta\'s.', 'This backpack is Ana\'s.', 'These backpacks are Marta\'s.'], 'This backpack is Marta\'s.', [Kit::word('la mochila', 'mochila'), Kit::form('esta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.eso', '¿Qué es eso?', ['What is that?', 'What is this?', 'Who is that?', 'Where is that?'], 'What is that?', [Kit::form('eso')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vaso', 'Prefiero este vaso.', ['I prefer this glass.', 'I prefer that glass.', 'You prefer this glass.', 'I prefer these glasses.'], 'I prefer this glass.', [Kit::word('el vaso', 'vaso'), Kit::word('preferir', 'prefiero'), Kit::form('este')]),
            Kit::listenType($stage, 'sentences.listen_type.coches', 'Esos coches son de Pablo.', 'Those cars (near you) are Pablo\'s.', [Kit::word('el coche', 'coches'), Kit::form('esos')]),
            Kit::listenType($stage, 'sentences.listen_type.bolso-sombrero', '¿Cuál prefieres, el bolso o el sombrero?', 'Which do you prefer, the bag or the hat? (informal you)', [Kit::word('el bolso', 'bolso'), Kit::word('el sombrero', 'sombrero'), Kit::word('preferir', 'prefieres')]),
            Kit::listenType($stage, 'sentences.listen_type.foto-regalo', 'Esta foto es un regalo.', 'This photo is a gift.', [Kit::word('la foto', 'foto'), Kit::word('el regalo', 'regalo'), Kit::form('esta')]),
            Kit::listenType($stage, 'sentences.listen_type.paraguas', 'Ese paraguas es de Marta.', 'That umbrella (near you) is Marta\'s.', [Kit::word('el paraguas', 'paraguas'), Kit::form('ese')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.vaso', 'Este vaso es de Ana.', 'This glass is Ana\'s.', [Kit::word('el vaso', 'vaso'), Kit::form('este')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bolso', 'Prefiero este bolso.', 'I prefer this bag.', [Kit::word('el bolso', 'bolso'), Kit::word('preferir', 'prefiero'), Kit::form('este')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mochila', 'Esa mochila es de Marta.', 'That backpack (near you) is Marta\'s.', [Kit::word('la mochila', 'mochila'), Kit::form('esa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.paraguas', '¿Qué es eso? Es un paraguas.', 'What is that? It is an umbrella.', [Kit::word('el paraguas', 'paraguas'), Kit::form('eso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.esto', '¿Qué es esto?', 'What is this?', [['es', 'un', 'una'], self::THINGS], 'Es un regalo.', [Kit::word('el regalo', 'regalo'), Kit::form('esto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.bolso-mochila', '¿Cuál prefieres, el bolso o la mochila?', 'Which do you prefer, the bag or the backpack?', [['prefiero', 'este', 'esta', 'ese', 'esa'], ['bolso', 'mochila']], 'Prefiero el bolso.', [Kit::word('el bolso', 'bolso'), Kit::word('preferir', 'prefiero')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.coche', '¿De quién es el coche?', 'Whose car is it?', [['es', 'de'], self::NAMES], 'Es de Luis.', [Kit::word('el coche', 'coche')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.regalo', 'Read the conversation.', [
                Kit::line('Ana', 'Pablo, ¿cuál prefieres, este bolso o ese bolso?'),
                Kit::line('Pablo', 'Prefiero ese bolso. ¿Es un regalo?'),
                Kit::line('Ana', 'Sí, es un regalo para Marta. Estos bolígrafos son de Luis.'),
            ], [
                Kit::question('What does Pablo prefer?', ['That bag', 'This bag', 'The backpack'], 'That bag'),
                Kit::question('Who is the gift for?', ['Marta', 'Luis', 'Pablo'], 'Marta'),
                Kit::question('Whose are the pens?', ['Luis\'s', 'Marta\'s', 'Pablo\'s'], 'Luis\'s'),
            ], [Kit::word('el bolso', 'bolso'), Kit::word('preferir', 'prefiero'), Kit::word('el regalo', 'regalo'), Kit::word('el bolígrafo', 'bolígrafos')], 'read'),
            Kit::gap($stage, 'task.choose_gap.fotos', '___ fotos son de Ana.', ['Estas', 'Estos', 'Esta'], 'Estas', Kit::form('estas', true), 'Fotos is feminine and plural, so this is estas. Estos is for masculine words and esta is for one thing.', 'read', 'These photos are Ana\'s.'),
            Kit::gap($stage, 'task.choose_gap.mochilas', '___ mochilas son de Pablo.', ['Esas', 'Esos', 'Esa'], 'Esas', Kit::form('esas', true), 'Mochilas is feminine and plural, so that is esas. Esos is for masculine words and esa is for one thing.', 'read', 'Those backpacks (near you) are Pablo\'s.'),

            Kit::transform($stage, 'task.transform.bolsos', 'Change it to several things: these.', 'Este bolso es de Ana.', ['Estos bolsos son de Ana.'], [Kit::word('el bolso', 'bolsos'), Kit::form('estos', true)]),
            Kit::transform($stage, 'task.transform.mochila', 'Now it is near the person you talk to: use that.', 'Esta mochila es de Marta.', ['Esa mochila es de Marta.'], [Kit::word('la mochila', 'mochila'), Kit::form('esa')]),
            Kit::transform($stage, 'task.transform.fotos', 'Change it to several things: those (near you).', 'Esa foto es de Luis.', ['Esas fotos son de Luis.'], [Kit::word('la foto', 'fotos'), Kit::form('esas', true)]),
            Kit::writeGuided($stage, 'task.write_guided.sombrero-bolso', 'Say that you prefer this hat and that bag (near you).', ['prefiero', 'este', 'sombrero', 'ese', 'bolso'], 'Prefiero este sombrero y ese bolso.', [
                ['forms' => ['prefiero'], 'term' => 'preferir'],
                ['forms' => ['este'], 'term' => null],
                ['forms' => ['sombrero'], 'term' => 'el sombrero'],
                ['forms' => ['ese'], 'term' => null],
                ['forms' => ['bolso'], 'term' => 'el bolso'],
            ], [Kit::word('preferir', 'prefiero'), Kit::word('el sombrero', 'sombrero'), Kit::word('el bolso', 'bolso'), Kit::form('este')]),
            Kit::writeGuided($stage, 'task.write_guided.boligrafos-regalo', 'Say that these pens are Luis\'s and that this gift is for Ana.', ['estos', 'bolígrafos', 'este', 'regalo', 'para'], 'Estos bolígrafos son de Luis y este regalo es para Ana.', [
                ['forms' => ['estos'], 'term' => null],
                ['forms' => ['bolígrafos'], 'term' => 'el bolígrafo'],
                ['forms' => ['este'], 'term' => null],
                ['forms' => ['regalo'], 'term' => 'el regalo'],
            ], [Kit::word('el bolígrafo', 'bolígrafos'), Kit::word('el regalo', 'regalo'), Kit::form('estos')]),
            Kit::build($stage, 'task.build.boligrafos', 'Whose pens are those (near you)?', '¿De quién son esos bolígrafos?', ['ese', 'esta'], [Kit::word('el bolígrafo', 'bolígrafos'), Kit::form('esos')], 'write'),
            Kit::build($stage, 'task.build.foto-regalo', 'This photo is a gift for Luis.', 'Esta foto es un regalo para Luis.', ['este', 'esos'], [Kit::word('la foto', 'foto'), Kit::word('el regalo', 'regalo'), Kit::form('esta')], 'write'),
            Kit::build($stage, 'task.build.pablo-mochila', 'Pablo prefers that backpack (near you).', 'Pablo prefiere esa mochila.', ['ese', 'prefiero'], [Kit::word('preferir', 'prefiere'), Kit::word('la mochila', 'mochila'), Kit::form('esa')], 'write'),
            Kit::translate($stage, 'task.translate.vaso', 'I prefer this glass, but Pablo prefers that glass (near you).', ['Prefiero este vaso, pero Pablo prefiere ese vaso.', 'Yo prefiero este vaso, pero Pablo prefiere ese vaso.'], [Kit::word('el vaso', 'vaso'), Kit::word('preferir', 'prefiero'), Kit::form('este')], 'write'),
            Kit::translate($stage, 'task.translate.mochila', 'Whose backpack is that (near you)? It is Marta\'s.', ['¿De quién es esa mochila? Es de Marta.'], [Kit::word('la mochila', 'mochila'), Kit::form('esa')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.mochila', [
                Kit::line('Luis', 'Marta, ¿de quién es esa mochila?'),
                Kit::line('Marta', 'Es de Ana. Estos bolígrafos también son de Ana.'),
                Kit::line('Luis', 'Y este paraguas, ¿es de Pablo?'),
                Kit::line('Marta', 'Sí, es de Pablo.'),
            ], [
                Kit::question('Whose is the backpack?', ['Ana\'s', 'Marta\'s', 'Pablo\'s'], 'Ana\'s'),
                Kit::question('Whose are the pens?', ['Ana\'s', 'Luis\'s', 'Pablo\'s'], 'Ana\'s'),
                Kit::question('Whose is the umbrella?', ['Pablo\'s', 'Ana\'s', 'Marta\'s'], 'Pablo\'s'),
            ], [
                Kit::question('Who asks about the backpack?', ['Luis', 'Marta', 'Pablo'], 'Luis'),
                Kit::question('Is the umbrella Pablo\'s?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la mochila', 'mochila'), Kit::word('el bolígrafo', 'bolígrafos'), Kit::word('el paraguas', 'paraguas')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.fotos-regalos', 'Estas fotos son regalos para Marta.', 'These photos are gifts for Marta.', [Kit::word('la foto', 'fotos'), Kit::word('el regalo', 'regalos'), Kit::form('estas')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.coche', '¿Cuál prefieres, este coche o ese coche?', 'Which do you prefer, this car (near me) or that car (near you)? (informal you)', [Kit::word('el coche', 'coche'), Kit::word('preferir', 'prefieres'), Kit::form('este')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mochila-bolso', 'Prefiero esa mochila, no este bolso.', 'I prefer that backpack (near you), not this bag.', [Kit::word('la mochila', 'mochila'), Kit::word('el bolso', 'bolso'), Kit::word('preferir', 'prefiero'), Kit::form('esa')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.eso', '¿Qué es eso?', 'What is that?', [['es', 'un', 'una'], self::THINGS], 'Es un paraguas.', [Kit::word('el paraguas', 'paraguas'), Kit::form('eso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.sombrero-paraguas', '¿Cuál prefieres, el sombrero o el paraguas?', 'Which do you prefer, the hat or the umbrella?', [['prefiero', 'este', 'esta', 'ese', 'esa'], ['sombrero', 'paraguas']], 'Prefiero el sombrero.', [Kit::word('el sombrero', 'sombrero'), Kit::word('preferir', 'prefiero')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mochila', '¿De quién es la mochila?', 'Whose backpack is it?', [['es', 'de'], self::NAMES], 'Es de Marta.', [Kit::word('la mochila', 'mochila')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.boligrafo', '¿De quién es el bolígrafo?', 'Whose pen is it?', [['es', 'de'], self::NAMES], 'Es de Pablo.', [Kit::word('el bolígrafo', 'bolígrafo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.coches', 'Esos coches son de Ana.', 'Those cars (near you) are Ana\'s.', [Kit::word('el coche', 'coches'), Kit::form('esos')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.vaso', 'Prefiero ese vaso.', 'I prefer that glass (near you).', [Kit::word('el vaso', 'vaso'), Kit::word('preferir', 'prefiero'), Kit::form('ese')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.mochila-regalo', 'This backpack is a gift for Ana.', ['Esta mochila es un regalo para Ana.'], [Kit::word('la mochila', 'mochila'), Kit::word('el regalo', 'regalo'), Kit::form('esta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.sombrero-paraguas', 'Which do you prefer, that hat (near you) or that umbrella (near you)? (informal you)', ['¿Cuál prefieres, ese sombrero o ese paraguas?'], [Kit::word('el sombrero', 'sombrero'), Kit::word('el paraguas', 'paraguas'), Kit::word('preferir', 'prefieres'), Kit::form('ese', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.boligrafos', 'Those pens (near you) are not Pablo\'s.', ['Esos bolígrafos no son de Pablo.'], [Kit::word('el bolígrafo', 'bolígrafos'), Kit::form('esos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.coche', 'Whose car is this?', ['¿De quién es este coche?'], [Kit::word('el coche', 'coche'), Kit::form('este')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vaso', '___ vaso es de Luis.', 'That glass (near you) is Luis\'s.', 'Ese', Kit::form('ese', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sombreros', '___ sombreros son de Pablo.', 'These hats are Pablo\'s.', 'Estos', Kit::form('estos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.bolso-mochila', 'Prefiero el bolso, no la mochila.', 'I prefer the bag, not the backpack.', [Kit::word('preferir', 'prefiero'), Kit::word('el bolso', 'bolso'), Kit::word('la mochila', 'mochila')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.vaso-sombrero', 'El vaso y el sombrero son regalos.', 'The glass and the hat are gifts.', [Kit::word('el vaso', 'vaso'), Kit::word('el sombrero', 'sombrero'), Kit::word('el regalo', 'regalos')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.foto-coche', 'La foto y el coche son de Marta.', 'The photo and the car are Marta\'s.', [Kit::word('la foto', 'foto'), Kit::word('el coche', 'coche')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.regalo', [
                Kit::line('Luis', 'Marta, ¿qué es esto?'),
                Kit::line('Marta', 'Es un regalo para Pablo.'),
                Kit::line('Luis', '¿Es un bolígrafo o un sombrero?'),
                Kit::line('Marta', 'Es un sombrero.'),
            ], [
                Kit::question('Who is the gift for?', ['Pablo', 'Luis', 'Ana'], 'Pablo'),
                Kit::question('What is the gift?', ['A hat', 'A pen', 'A bag'], 'A hat'),
                Kit::question('Who asks what it is?', ['Luis', 'Marta', 'Pablo'], 'Luis'),
            ], [
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Is the gift for Pablo?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is the gift a pen?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('el regalo', 'regalo'), Kit::word('el bolígrafo', 'bolígrafo'), Kit::word('el sombrero', 'sombrero')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.paraguas', 'Read the conversation.', [
                Kit::line('Pablo', 'Ana, ¿de quién es este paraguas?'),
                Kit::line('Ana', 'Es de Luis. Esta foto también es de Luis.'),
                Kit::line('Pablo', 'Tengo un regalo para Marta: un bolso y un vaso.'),
            ], [
                Kit::question('Whose umbrella is it?', ['Luis\'s', 'Ana\'s', 'Marta\'s'], 'Luis\'s'),
                Kit::question('What does Pablo have for Marta?', ['A bag and a glass', 'An umbrella and a photo', 'A hat and a pen'], 'A bag and a glass'),
            ], [Kit::word('el paraguas', 'paraguas'), Kit::word('la foto', 'foto'), Kit::word('el regalo', 'regalo'), Kit::word('el bolso', 'bolso'), Kit::word('el vaso', 'vaso')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.coche-sombrero', '¿Cuál prefieres, el coche o el sombrero?', 'Which do you prefer, the car or the hat?', [['prefiero', 'este', 'esta', 'ese', 'esa'], ['coche', 'sombrero']], 'Prefiero el coche.', [Kit::word('el coche', 'coche'), Kit::word('el sombrero', 'sombrero'), Kit::word('preferir', 'prefiero')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.bolso', '¿De quién es el bolso?', 'Whose bag is it?', [['es', 'de'], self::NAMES], 'Es de Ana.', [Kit::word('el bolso', 'bolso')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.foto', '¿De quién es la foto?', 'Whose photo is it?', [['es', 'de'], self::NAMES], 'Es de Marta.', [Kit::word('la foto', 'foto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.mochilas-regalos', 'These backpacks are gifts for Marta.', ['Estas mochilas son regalos para Marta.'], [Kit::word('la mochila', 'mochilas'), Kit::word('el regalo', 'regalos'), Kit::form('estas')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sombrero-foto', 'I prefer that hat (near you), not this photo.', ['Prefiero ese sombrero, no esta foto.', 'Yo prefiero ese sombrero, no esta foto.'], [Kit::word('el sombrero', 'sombrero'), Kit::word('la foto', 'foto'), Kit::word('preferir', 'prefiero'), Kit::form('ese', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.coches', 'Whose cars and umbrellas are those (near you)?', ['¿De quién son esos coches y paraguas?', '¿De quién son esos coches y esos paraguas?'], [Kit::word('el coche', 'coches'), Kit::word('el paraguas', 'paraguas'), Kit::form('esos')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.boligrafo-vaso', 'This pen and this glass are Luis\'s.', ['Este bolígrafo y este vaso son de Luis.'], [Kit::word('el bolígrafo', 'bolígrafo'), Kit::word('el vaso', 'vaso'), Kit::form('este')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.foto', 'Esta ___ es de Marta.', 'This photo is Marta\'s.', 'foto', Kit::word('la foto', 'foto'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.bolsos', 'Estos ___ son de Pablo.', 'These bags are Pablo\'s.', 'bolsos', Kit::word('el bolso', 'bolsos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vaso-bolso', 'Prefiero este vaso, no ese bolso.', 'I prefer this glass, not that bag (near you).', [Kit::word('preferir', 'prefiero'), Kit::word('el vaso', 'vaso'), Kit::word('el bolso', 'bolso'), Kit::form('este', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.paraguas-coches', 'Esos paraguas y coches son regalos.', 'Those umbrellas and cars (near you) are gifts.', [Kit::word('el paraguas', 'paraguas'), Kit::word('el coche', 'coches'), Kit::word('el regalo', 'regalos'), Kit::form('esos')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.sombrero-boligrafo', 'El sombrero, el bolígrafo y la mochila son de Ana.', 'The hat, the pen and the backpack are Ana\'s.', [Kit::word('el sombrero', 'sombrero'), Kit::word('el bolígrafo', 'bolígrafo'), Kit::word('la mochila', 'mochila')], 'dictation', $set),
        ];
    }
}
