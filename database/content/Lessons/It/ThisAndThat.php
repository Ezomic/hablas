<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

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
    private const array THINGS = ['bicchiere', 'borsa', 'macchina', 'zaino', 'foto', 'regalo', 'ombrello', 'penna', 'cappello'];

    private const array NAMES = ['anna', 'paolo', 'marta', 'luca'];

    private const string NOTE_E = 'È (is) and e (and) sound the same. The accent only shows in writing.';

    private const string NOTE_O = 'O (or) and ho (I have) sound the same. The h is silent and only shows in writing.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'this-and-that';
    }

    public function words(): array
    {
        return [
            new WordData('il bicchiere', cue: 'glass (for drinking)', forms: ['bicchieri']),
            new WordData('la borsa', cue: 'bag, handbag', forms: ['borse']),
            new WordData('la macchina', cue: 'car', forms: ['macchine']),
            new WordData('lo zaino', cue: 'backpack', forms: ['zaini'], note: 'Zaino starts with z, so the article is lo: lo zaino. The plural is gli zaini.'),
            new WordData('la foto', cue: 'photo', accepted: ['la fotografia'], forms: ['foto'], note: 'Foto is feminine and does not change in the plural: la foto, le foto.'),
            new WordData('il regalo', cue: 'gift, present', forms: ['regali']),
            new WordData("l'ombrello", cue: 'umbrella', accepted: ['ombrello'], forms: ['ombrelli'], note: "Ombrello starts with a vowel, so the article is l': l'ombrello."),
            new WordData('la penna', cue: 'pen', forms: ['penne']),
            new WordData('il cappello', cue: 'hat', forms: ['cappelli']),
            new WordData('preferire', cue: 'to prefer', forms: ['preferisco', 'preferisci', 'preferisce'], note: 'In the singular the verb adds isc: preferisco, preferisci, preferisce.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Questa borsa è di Anna.', 'english' => 'This bag is Anna\'s.'],
            ['text' => 'Quale preferisci, questo zaino o quel cappello?', 'english' => 'Which do you prefer, this backpack or that hat?'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.bicchiere', '___ bicchiere è di Anna.', ['Questo', 'Questa', 'Questi'], 'Questo', Kit::form('questo', true), 'Bicchiere is masculine and singular, so this is questo. Questa is for feminine words and questi is for several things.', 'choose', 'This glass is Anna\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.borsa', '___ borsa è di Paolo.', ['Questa', 'Questo', 'Queste'], 'Questa', Kit::form('questa', true), 'Borsa is feminine and singular, so this is questa. Questo is for masculine words and queste is for several things.', 'choose', 'This bag is Paolo\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.macchine', '___ macchine sono di Luca.', ['Quelle', 'Quella', 'Quei'], 'Quelle', Kit::form('quelle', true), 'Macchine is feminine and plural, so that is quelle. Quella is for one thing and quei is for masculine plural words.', 'choose', 'Those cars are Luca\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.foto', 'Di chi è ___ foto?', ['quella', 'quel', 'quelle'], 'quella', Kit::form('quella', true), 'Foto is feminine and singular, so that is quella. Quel is for masculine words and quelle is for several things.', 'choose', 'Whose photo is that?'),
            Kit::gap($stage, 'sentences.choose_gap.questo', "Che cos'è ___?", ['questo', 'quello', 'queste'], 'questo', Kit::form('questo', true), 'This asks about something close, so questo. Quello is for far away and queste is for several things.', 'choose', 'What is this?'),
            Kit::gap($stage, 'sentences.choose_gap.preferisce', 'Paolo ___ questo bicchiere.', ['preferisce', 'preferisco', 'preferisci'], 'preferisce', Kit::word('preferire', 'preferisce'), 'Paolo is he, so the verb ends in -isce. Preferisco is for io and preferisci is for tu.', 'choose', 'Paolo prefers this glass.'),

            Kit::typeGap($stage, 'sentences.type_gap.zaino', '___ zaino è di Marta.', 'This backpack is Marta\'s.', 'Questo', Kit::form('questo'), 'Zaino is masculine and singular, so this is questo.'),
            Kit::typeGap($stage, 'sentences.type_gap.preferisci', 'Quale ___, questa borsa o quella?', 'Which do you prefer, this bag or that one? (informal you)', 'preferisci', Kit::word('preferire', 'preferisci')),
            Kit::typeGap($stage, 'sentences.type_gap.penne', 'Queste ___ sono di Anna.', 'These pens are Anna\'s.', 'penne', Kit::word('la penna', 'penne')),
            Kit::typeGap($stage, 'sentences.type_gap.regalo', '___ regalo è per Luca.', 'That gift is for Luca.', 'Quel', Kit::form('quel'), 'Regalo takes il (il regalo), so that is quel: quel regalo.'),
            Kit::typeGap($stage, 'sentences.type_gap.quello', "Che cos'è ___? È un ombrello.", 'What is that (over there)? It is an umbrella.', 'quello', Kit::form('quello'), 'When you do not name the thing, Italian uses the masculine singular: quello.'),

            Kit::translate($stage, 'sentences.translate.penna', 'This pen is Luca\'s.', ['Questa penna è di Luca.'], [Kit::word('la penna', 'penna'), Kit::form('questa')]),
            Kit::translate($stage, 'sentences.translate.cappello', 'I prefer that hat.', ['Preferisco quel cappello.', 'Io preferisco quel cappello.'], [Kit::word('il cappello', 'cappello'), Kit::word('preferire', 'preferisco'), Kit::form('quel')]),
            Kit::translate($stage, 'sentences.translate.questo', 'What is this? It is a gift.', ["Che cos'è questo? È un regalo.", "Cos'è questo? È un regalo."], [Kit::word('il regalo', 'regalo'), Kit::form('questo', false, ["cos'è"])]),

            Kit::build($stage, 'sentences.build.foto', 'That photo is Marta\'s.', 'Quella foto è di Marta.', ['quel'], [Kit::word('la foto', 'foto'), Kit::form('quella')]),
            Kit::build($stage, 'sentences.build.zaino', 'I prefer this backpack.', 'Preferisco questo zaino.', ['questa'], [Kit::word('lo zaino', 'zaino'), Kit::word('preferire', 'preferisco'), Kit::form('questo')]),
            Kit::build($stage, 'sentences.build.borsa', 'Whose bag is this?', 'Di chi è questa borsa?', ['questo'], [Kit::word('la borsa', 'borsa'), Kit::form('questa')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.zaino', 'Questo zaino è di Marta.', ['This backpack is Marta\'s.', 'That backpack is Marta\'s.', 'This backpack is Anna\'s.', 'These backpacks are Marta\'s.'], 'This backpack is Marta\'s.', [Kit::word('lo zaino', 'zaino'), Kit::form('questo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.quello', "Che cos'è quello?", ['What is that?', 'What is this?', 'Who is that?', 'Where is that?'], 'What is that?', [Kit::form('quello')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.bicchiere', 'Preferisco questo bicchiere.', ['I prefer this glass.', 'I prefer that glass.', 'You prefer this glass.', 'I prefer these glasses.'], 'I prefer this glass.', [Kit::word('il bicchiere', 'bicchiere'), Kit::word('preferire', 'preferisco'), Kit::form('questo')]),
            Kit::listenType($stage, 'sentences.listen_type.macchine', 'Quelle macchine sono di Paolo.', 'Those cars are Paolo\'s.', [Kit::word('la macchina', 'macchine'), Kit::form('quelle')]),
            Kit::listenType($stage, 'sentences.listen_type.borsa-cappello', 'Quale preferisci, la borsa o il cappello?', 'Which do you prefer, the bag or the hat? (informal you)', [Kit::word('la borsa', 'borsa'), Kit::word('il cappello', 'cappello'), Kit::word('preferire', 'preferisci')], homophoneNote: self::NOTE_O),
            Kit::listenType($stage, 'sentences.listen_type.foto-regalo', 'Questa foto è un regalo.', 'This photo is a gift.', [Kit::word('la foto', 'foto'), Kit::word('il regalo', 'regalo'), Kit::form('questa')], homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'sentences.listen_type.ombrello', "Quell'ombrello è di Marta.", 'That umbrella is Marta\'s.', [Kit::word("l'ombrello", "quell'ombrello"), Kit::form("quell'ombrello")], homophoneNote: self::NOTE_E),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bicchiere', 'Questo bicchiere è di Anna.', 'This glass is Anna\'s.', [Kit::word('il bicchiere', 'bicchiere'), Kit::form('questo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.borsa', 'Preferisco questa borsa.', 'I prefer this bag.', [Kit::word('la borsa', 'borsa'), Kit::word('preferire', 'preferisco'), Kit::form('questa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.zaino', 'Quello zaino è di Marta.', 'That backpack is Marta\'s.', [Kit::word('lo zaino', 'zaino'), Kit::form('quello')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ombrello', "Che cos'è quello? È un ombrello.", 'What is that? It is an umbrella.', [Kit::word("l'ombrello", 'ombrello'), Kit::form('quello')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.questo', "Che cos'è questo?", 'What is this?', [['è', 'un', 'uno', 'una'], self::THINGS], 'È un regalo.', [Kit::word('il regalo', 'regalo'), Kit::form('questo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.borsa-zaino', 'Quale preferisci, la borsa o lo zaino?', 'Which do you prefer, the bag or the backpack?', [['preferisco', 'questo', 'questa', 'quello', 'quella'], ['borsa', 'zaino']], 'Preferisco la borsa.', [Kit::word('la borsa', 'borsa'), Kit::word('preferire', 'preferisco')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.macchina', 'Di chi è la macchina?', 'Whose car is it?', [['è', 'di'], self::NAMES], 'È di Luca.', [Kit::word('la macchina', 'macchina')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.regalo', 'Read the conversation.', [
                Kit::line('Anna', 'Paolo, quale preferisci, questa borsa o quella?'),
                Kit::line('Paolo', 'Preferisco quella. È un regalo?'),
                Kit::line('Anna', 'Sì, è un regalo per Marta. Queste penne sono di Luca.'),
            ], [
                Kit::question('What does Paolo prefer?', ['That bag', 'This bag', 'The backpack'], 'That bag'),
                Kit::question('Who is the gift for?', ['Marta', 'Luca', 'Paolo'], 'Marta'),
                Kit::question('Whose are the pens?', ['Luca\'s', 'Marta\'s', 'Paolo\'s'], 'Luca\'s'),
            ], [Kit::word('la borsa', 'borsa'), Kit::word('preferire', 'preferisco'), Kit::word('il regalo', 'regalo'), Kit::word('la penna', 'penne')], 'read'),
            Kit::gap($stage, 'task.choose_gap.foto', '___ foto sono di Anna.', ['Queste', 'Questi', 'Questa'], 'Queste', Kit::form('queste', true), 'Foto is feminine (la foto) and stays foto in the plural, so this is queste. Questi is for masculine words and questa is for one thing.', 'read', 'These photos are Anna\'s.'),
            Kit::gap($stage, 'task.choose_gap.bicchieri', '___ bicchieri sono di Paolo.', ['Quei', 'Quelle', 'Quel'], 'Quei', Kit::form('quei', true), 'Bicchieri is masculine and plural (il bicchiere, i bicchieri), so that is quei. Quel is for one thing and quelle is for feminine words.', 'read', 'Those glasses are Paolo\'s.'),

            Kit::transform($stage, 'task.transform.borse', 'Change it to several things: these.', 'Questa borsa è di Anna.', ['Queste borse sono di Anna.'], [Kit::word('la borsa', 'borse'), Kit::form('queste', true)]),
            Kit::transform($stage, 'task.transform.zaino', 'Now it is further away: use that.', 'Questo zaino è di Marta.', ['Quello zaino è di Marta.'], [Kit::word('lo zaino', 'zaino'), Kit::form('quello')]),
            Kit::transform($stage, 'task.transform.foto', 'Change it to several things: those.', 'Quella foto è di Luca.', ['Quelle foto sono di Luca.'], [Kit::word('la foto', 'foto'), Kit::form('quelle', true)]),
            Kit::writeGuided($stage, 'task.write_guided.cappello-borsa', 'Say that you prefer this hat and that bag.', ['preferisco', 'questo', 'cappello', 'quella', 'borsa'], 'Preferisco questo cappello e quella borsa.', [
                ['forms' => ['preferisco'], 'term' => 'preferire'],
                ['forms' => ['questo'], 'term' => null],
                ['forms' => ['cappello'], 'term' => 'il cappello'],
                ['forms' => ['quella'], 'term' => null],
                ['forms' => ['borsa'], 'term' => 'la borsa'],
            ], [Kit::word('preferire', 'preferisco'), Kit::word('il cappello', 'cappello'), Kit::word('la borsa', 'borsa'), Kit::form('questo')]),
            Kit::writeGuided($stage, 'task.write_guided.penne-regalo', 'Say that these pens are Luca\'s and that this gift is for Anna.', ['queste', 'penne', 'questo', 'regalo', 'per'], 'Queste penne sono di Luca e questo regalo è per Anna.', [
                ['forms' => ['queste'], 'term' => null],
                ['forms' => ['penne'], 'term' => 'la penna'],
                ['forms' => ['questo'], 'term' => null],
                ['forms' => ['regalo'], 'term' => 'il regalo'],
            ], [Kit::word('la penna', 'penne'), Kit::word('il regalo', 'regalo'), Kit::form('queste')]),
            Kit::build($stage, 'task.build.penne', 'Whose pens are those?', 'Di chi sono quelle penne?', ['quei', 'questa'], [Kit::word('la penna', 'penne'), Kit::form('quelle')], 'write'),
            Kit::build($stage, 'task.build.foto-regalo', 'This photo is a gift for Luca.', 'Questa foto è un regalo per Luca.', ['questo', 'quelle'], [Kit::word('la foto', 'foto'), Kit::word('il regalo', 'regalo'), Kit::form('questa')], 'write'),
            Kit::build($stage, 'task.build.paolo-zaino', 'Paolo prefers that backpack.', 'Paolo preferisce quello zaino.', ['quel', 'preferisco'], [Kit::word('preferire', 'preferisce'), Kit::word('lo zaino', 'zaino'), Kit::form('quello')], 'write'),
            Kit::translate($stage, 'task.translate.bicchiere', 'I prefer this glass, but Paolo prefers that glass.', ['Preferisco questo bicchiere, ma Paolo preferisce quel bicchiere.', 'Io preferisco questo bicchiere, ma Paolo preferisce quel bicchiere.'], [Kit::word('il bicchiere', 'bicchiere'), Kit::word('preferire', 'preferisco'), Kit::form('questo')], 'write'),
            Kit::translate($stage, 'task.translate.zaino', 'Whose backpack is that? It is Marta\'s.', ['Di chi è quello zaino? È di Marta.'], [Kit::word('lo zaino', 'zaino'), Kit::form('quello')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.zaino', [
                Kit::line('Luca', 'Marta, di chi è quello zaino?'),
                Kit::line('Marta', 'È di Anna. Queste penne sono di Anna.'),
                Kit::line('Luca', 'E questo ombrello, è di Paolo?'),
                Kit::line('Marta', 'Sì, è di Paolo.'),
            ], [
                Kit::question('Whose is the backpack?', ['Anna\'s', 'Marta\'s', 'Paolo\'s'], 'Anna\'s'),
                Kit::question('Whose are the pens?', ['Anna\'s', 'Luca\'s', 'Paolo\'s'], 'Anna\'s'),
                Kit::question('Whose is the umbrella?', ['Paolo\'s', 'Anna\'s', 'Marta\'s'], 'Paolo\'s'),
            ], [
                Kit::question('Who asks about the backpack?', ['Luca', 'Marta', 'Paolo'], 'Luca'),
                Kit::question('Is the umbrella Paolo\'s?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('lo zaino', 'zaino'), Kit::word('la penna', 'penne'), Kit::word("l'ombrello", 'ombrello')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.foto-regali', 'Queste foto sono regali per Marta.', 'These photos are gifts for Marta.', [Kit::word('la foto', 'foto'), Kit::word('il regalo', 'regali'), Kit::form('queste')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.macchina', 'Quale preferisci, questa macchina o quella?', 'Which do you prefer, this car or that one? (informal you)', [Kit::word('la macchina', 'macchina'), Kit::word('preferire', 'preferisci'), Kit::form('questa')], 'listen', homophoneNote: self::NOTE_O),
            Kit::listenType($stage, 'task.listen_type.zaino-borsa', 'Preferisco quello zaino, non questa borsa.', 'I prefer that backpack, not this bag.', [Kit::word('lo zaino', 'zaino'), Kit::word('la borsa', 'borsa'), Kit::word('preferire', 'preferisco'), Kit::form('quello')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.quello', "Che cos'è quello?", 'What is that?', [['è', 'un', 'uno', 'una'], self::THINGS], 'È un ombrello.', [Kit::word("l'ombrello", 'ombrello'), Kit::form('quello')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cappello-ombrello', "Quale preferisci, il cappello o l'ombrello?", 'Which do you prefer, the hat or the umbrella?', [['preferisco', 'questo', 'questa', 'quello', 'quella'], ['cappello', 'ombrello']], 'Preferisco il cappello.', [Kit::word('il cappello', 'cappello'), Kit::word('preferire', 'preferisco')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.zaino', 'Di chi è lo zaino?', 'Whose backpack is it?', [['è', 'di'], self::NAMES], 'È di Marta.', [Kit::word('lo zaino', 'zaino')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.penna', 'Di chi è la penna?', 'Whose pen is it?', [['è', 'di'], self::NAMES], 'È di Paolo.', [Kit::word('la penna', 'penna')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.macchine', 'Quelle macchine sono di Anna.', 'Those cars are Anna\'s.', [Kit::word('la macchina', 'macchine'), Kit::form('quelle')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bicchiere', 'Preferisco quel bicchiere.', 'I prefer that glass.', [Kit::word('il bicchiere', 'bicchiere'), Kit::word('preferire', 'preferisco'), Kit::form('quel')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.zaino-regalo', 'This backpack is a gift for Anna.', ['Questo zaino è un regalo per Anna.'], [Kit::word('lo zaino', 'zaino'), Kit::word('il regalo', 'regalo'), Kit::form('questo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.cappello-ombrello', 'Which do you prefer, that hat or this umbrella? (informal you)', ['Quale preferisci, quel cappello o questo ombrello?'], [Kit::word('il cappello', 'cappello'), Kit::word("l'ombrello", 'ombrello'), Kit::word('preferire', 'preferisci'), Kit::form('quel', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.penne', 'Those pens are not Paolo\'s.', ['Quelle penne non sono di Paolo.'], [Kit::word('la penna', 'penne'), Kit::form('quelle')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.macchina', 'Whose car is that?', ['Di chi è quella macchina?'], [Kit::word('la macchina', 'macchina'), Kit::form('quella')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.borsa', '___ borsa è di Luca.', 'That bag is Luca\'s.', 'Quella', Kit::form('quella', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.cappelli', '___ cappelli sono di Paolo.', 'These hats are Paolo\'s.', 'Questi', Kit::form('questi'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.borsa-zaino', 'Preferisco la borsa, non lo zaino.', 'I prefer the bag, not the backpack.', [Kit::word('preferire', 'preferisco'), Kit::word('la borsa', 'borsa'), Kit::word('lo zaino', 'zaino')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.bicchiere-cappello', 'Il bicchiere e il cappello sono regali.', 'The glass and the hat are gifts.', [Kit::word('il bicchiere', 'bicchiere'), Kit::word('il cappello', 'cappello'), Kit::word('il regalo', 'regali')], 'dictation', $set, homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'check.a.listen_type.foto-macchina', 'La foto e la macchina sono di Marta.', 'The photo and the car are Marta\'s.', [Kit::word('la foto', 'foto'), Kit::word('la macchina', 'macchina')], 'dictation', $set, homophoneNote: self::NOTE_E),
            Kit::listenPassage($stage, 'check.a.listen_passage.regalo', [
                Kit::line('Luca', "Marta, che cos'è questo?"),
                Kit::line('Marta', 'È un regalo per Paolo.'),
                Kit::line('Luca', 'È una penna o un cappello?'),
                Kit::line('Marta', 'È un cappello.'),
            ], [
                Kit::question('Who is the gift for?', ['Paolo', 'Luca', 'Anna'], 'Paolo'),
                Kit::question('What is the gift?', ['A hat', 'A pen', 'A bag'], 'A hat'),
                Kit::question('Who asks what it is?', ['Luca', 'Marta', 'Paolo'], 'Luca'),
            ], [
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Is the gift for Paolo?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is the gift a pen?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('il regalo', 'regalo'), Kit::word('la penna', 'penna'), Kit::word('il cappello', 'cappello')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ombrello', 'Read the conversation.', [
                Kit::line('Paolo', 'Anna, di chi è questo ombrello?'),
                Kit::line('Anna', 'È di Luca. Questa foto è anche di Luca.'),
                Kit::line('Paolo', 'Ho un regalo per Marta: una borsa e un bicchiere.'),
            ], [
                Kit::question('Whose umbrella is it?', ['Luca\'s', 'Anna\'s', 'Marta\'s'], 'Luca\'s'),
                Kit::question('What does Paolo have for Marta?', ['A bag and a glass', 'An umbrella and a photo', 'A hat and a pen'], 'A bag and a glass'),
            ], [Kit::word("l'ombrello", 'ombrello'), Kit::word('la foto', 'foto'), Kit::word('il regalo', 'regalo'), Kit::word('la borsa', 'borsa'), Kit::word('il bicchiere', 'bicchiere')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.macchina-cappello', 'Quale preferisci, la macchina o il cappello?', 'Which do you prefer, the car or the hat?', [['preferisco', 'questo', 'questa', 'quello', 'quella'], ['macchina', 'cappello']], 'Preferisco la macchina.', [Kit::word('la macchina', 'macchina'), Kit::word('il cappello', 'cappello'), Kit::word('preferire', 'preferisco')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.borsa', 'Di chi è la borsa?', 'Whose bag is it?', [['è', 'di'], self::NAMES], 'È di Anna.', [Kit::word('la borsa', 'borsa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.foto', 'Di chi è la foto?', 'Whose photo is it?', [['è', 'di'], self::NAMES], 'È di Marta.', [Kit::word('la foto', 'foto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.borse-regali', 'These bags are gifts for Marta.', ['Queste borse sono regali per Marta.'], [Kit::word('la borsa', 'borse'), Kit::word('il regalo', 'regali'), Kit::form('queste')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.cappello-foto', 'I prefer that hat, not this photo.', ['Preferisco quel cappello, non questa foto.', 'Io preferisco quel cappello, non questa foto.'], [Kit::word('il cappello', 'cappello'), Kit::word('la foto', 'foto'), Kit::word('preferire', 'preferisco'), Kit::form('quel', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ombrelli-macchine', 'Whose umbrellas and cars are these?', ['Di chi sono questi ombrelli e queste macchine?', 'Di chi sono questi ombrelli e macchine?'], [Kit::word("l'ombrello", 'ombrelli'), Kit::word('la macchina', 'macchine'), Kit::form('questi')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.penna-bicchiere', 'This pen and this glass are Luca\'s.', ['Questa penna e questo bicchiere sono di Luca.'], [Kit::word('la penna', 'penna'), Kit::word('il bicchiere', 'bicchiere'), Kit::form('questa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.foto', 'Questa ___ è di Marta.', 'This photo is Marta\'s.', 'foto', Kit::word('la foto', 'foto'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.zaini', 'Questi ___ sono di Paolo.', 'These backpacks are Paolo\'s.', 'zaini', Kit::word('lo zaino', 'zaini'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.bicchiere-borsa', 'Preferisco questo bicchiere, non quella borsa.', 'I prefer this glass, not that bag.', [Kit::word('preferire', 'preferisco'), Kit::word('il bicchiere', 'bicchiere'), Kit::word('la borsa', 'borsa'), Kit::form('questo', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.penne-ombrelli', 'Queste penne e questi ombrelli sono regali.', 'These pens and umbrellas are gifts.', [Kit::word('la penna', 'penne'), Kit::word("l'ombrello", 'ombrelli'), Kit::word('il regalo', 'regali'), Kit::form('queste')], 'dictation', $set, homophoneNote: self::NOTE_E),
            Kit::listenType($stage, 'check.b.listen_type.cappello-macchina-zaino', 'Il cappello, la macchina e lo zaino sono di Anna.', 'The hat, the car and the backpack are Anna\'s.', [Kit::word('il cappello', 'cappello'), Kit::word('la macchina', 'macchina'), Kit::word('lo zaino', 'zaino')], 'dictation', $set, homophoneNote: self::NOTE_E),
        ];
    }
}
