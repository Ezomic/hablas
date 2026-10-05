<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('la famiglia', cue: 'family'),
            new WordData('il padre', cue: 'father'),
            new WordData('la madre', cue: 'mother'),
            new WordData('il fratello', cue: 'brother', forms: ['fratelli']),
            new WordData('la sorella', cue: 'sister', forms: ['sorelle']),
            new WordData('il figlio', cue: 'son', forms: ['figli']),
            new WordData('i nonni', cue: 'grandparents'),
            new WordData('sposato', cue: 'married (masculine)', forms: ['sposata', 'sposati', 'sposate']),
            new WordData('single', cue: 'single, unmarried (the same for a man and a woman)', accepted: ['celibe', 'nubile'], forms: ['celibi']),
            new WordData('maggiore', cue: 'older, elder (of the siblings; the same for a man and a woman)', forms: ['maggiori']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Mio fratello è sposato.', 'english' => 'My brother is married.'],
            ['text' => 'I miei fratelli sono sposati.', 'english' => 'My brothers are married.'],
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
            Kit::gap($stage, 'sentences.choose_gap.miei-fratelli', '___ fratelli sono sposati.', ['I miei', 'Miei', 'Il mio'], 'I miei', Kit::form('miei', true), 'Fratelli is plural, so the possessive is plural too, and a plural keeps its article: i miei fratelli.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.mia-madre', '___ madre è qui.', ['Mia', 'Mio', 'Mie'], 'Mia', Kit::form('mia'), 'Madre is one family member and feminine, so mia, and without an article: mia madre.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tue-sorelle', '___ sorelle sono qui.', ['Le tue', 'Tue', 'La tua'], 'Le tue', Kit::form('tue', true), 'Sorelle is plural feminine, so tue, and a plural keeps its article: le tue sorelle.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.sorella-sposata', 'Mia sorella è ___.', ['sposata', 'sposato'], 'sposata', Kit::word('sposato', 'sposata'), 'Sorella is feminine, so the adjective ends in -a.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.paolo-sposato', 'Paolo è ___.', ['sposato', 'sposata'], 'sposato', Kit::word('sposato'), 'Paolo is a man, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.marta-sorella', 'Marta è mia ___.', ['sorella', 'fratello'], 'sorella', Kit::word('la sorella', 'sorella'), 'Marta is a woman, so sister.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.mio-fratello', '___ fratello è maggiore di Luca.', 'My brother is older than Luca.', 'Mio', Kit::form('mio'), 'One brother, so mio, and without an article: mio fratello.'),
            Kit::typeGap($stage, 'sentences.type_gap.miei-nonni', 'I ___ nonni sono qui.', 'My grandparents are here.', 'miei', Kit::form('miei', true), 'Nonni is plural, so miei, and the article i stays.'),
            Kit::typeGap($stage, 'sentences.type_gap.tuo-fratello', 'Paolo è ___ fratello.', 'Paolo is your brother.', 'tuo', Kit::form('tuo'), 'One brother, so tuo, and without an article.'),
            Kit::typeGap($stage, 'sentences.type_gap.famiglia', 'La ___ è qui.', 'The family is here.', 'famiglia', Kit::word('la famiglia', 'famiglia')),
            Kit::typeGap($stage, 'sentences.type_gap.suoi-fratelli', 'Marta e i ___ fratelli sono qui.', 'Marta and her brothers are here.', 'suoi', Kit::form('suoi', true), 'Suo agrees with the thing owned, not with the owner: more than one brother, so suoi.'),

            Kit::translate($stage, 'sentences.translate.padre', 'My father is here.', ['Mio padre è qui.'], [Kit::word('il padre', 'padre'), Kit::form('mio')]),
            Kit::translate($stage, 'sentences.translate.fratelli', 'My brothers are here.', ['I miei fratelli sono qui.'], [Kit::word('il fratello', 'fratelli'), Kit::form('miei', true)]),
            Kit::translate($stage, 'sentences.translate.madre', 'Your mother is married.', ['Tua madre è sposata.'], [Kit::word('la madre', 'madre'), Kit::word('sposato', 'sposata'), Kit::form('tua')]),
            Kit::build($stage, 'sentences.build.figlio-single', 'Her son is single.', 'Suo figlio è single.', ['suoi'], [Kit::word('il figlio', 'figlio'), Kit::word('single'), Kit::form('suo')]),
            Kit::build($stage, 'sentences.build.nonni', 'My grandparents are here.', 'I miei nonni sono qui.', ['mio'], [Kit::word('i nonni', 'nonni'), Kit::form('miei', true)]),
            Kit::build($stage, 'sentences.build.fratello-maggiore', 'Your brother is older than Luca.', 'Tuo fratello è maggiore di Luca.', ['tuoi'], [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::form('tuo')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.madre', 'Mia madre è qui.', ['My mother is here.', 'My father is here.', 'My sister is here.', 'My family is here.'], 'My mother is here.', [Kit::word('la madre', 'madre'), Kit::form('mia')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.fratelli', 'I miei fratelli sono sposati.', ['My brothers are married.', 'My brother is married.', 'My sons are married.', 'My grandparents are married.'], 'My brothers are married.', [Kit::word('il fratello', 'fratelli'), Kit::word('sposato', 'sposati'), Kit::form('miei', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.figlio', 'Suo figlio è sposato.', ['Her son is married.', 'Her son is single.', 'Her sons are married.', 'Her mother is married.'], 'Her son is married.', [Kit::word('il figlio', 'figlio'), Kit::word('sposato'), Kit::form('suo')]),
            Kit::listenType($stage, 'sentences.listen_type.sorella', 'Mia sorella è single.', 'My sister is single.', [Kit::word('la sorella', 'sorella'), Kit::word('single'), Kit::form('mia')], homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.'),
            Kit::listenType($stage, 'sentences.listen_type.nonni', 'I tuoi nonni sono qui.', 'Your grandparents are here.', [Kit::word('i nonni', 'nonni'), Kit::form('tuoi', true)]),
            Kit::listenType($stage, 'sentences.listen_type.famiglia', 'La famiglia è qui.', 'The family is here.', [Kit::word('la famiglia')], homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.'),
            Kit::listenType($stage, 'sentences.listen_type.padre-madre', 'Mio padre e mia madre sono qui.', 'My father and my mother are here.', [Kit::word('il padre', 'padre'), Kit::word('la madre', 'madre'), Kit::form('mio')], homophoneNote: 'E (and) and è (is) sound very close, and the accent is only a written difference. Here it means and, so it has no accent.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.fratello', 'Mio fratello è maggiore di Anna.', 'My brother is older than Anna.', [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::form('mio')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.figlio', 'Suo figlio è sposato.', 'Her son is married.', [Kit::word('il figlio', 'figlio'), Kit::word('sposato'), Kit::form('suo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sorella', 'Marta è la sorella di Paolo.', 'Marta is Paolo\'s sister.', [Kit::word('la sorella')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.sorella-maggiore', 'Mia sorella è maggiore di Luca.', 'My sister is older than Luca.', [Kit::word('la sorella', 'sorella'), Kit::word('maggiore'), Kit::form('mia')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.fratelli', 'Hai fratelli?', 'Do you have brothers or sisters?', [['sì', 'no', 'non', 'ho', 'sono'], ['fratello', 'fratelli', 'sorella', 'sorelle', 'figlio', 'una', 'un', 'due']], 'Sì, ho una sorella.', [Kit::word('la sorella', 'sorella')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sposato', 'Tuo fratello è sposato?', 'Is your brother married?', [['sì', 'no', 'è', 'mio', 'sposato', 'single']], 'Sì, mio fratello è sposato.', [Kit::word('il fratello', 'fratello'), Kit::word('sposato'), Kit::form('mio')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.marta', 'Chi è Marta?', 'Who is Marta?', [['mia', 'tua', 'sua', 'la'], ['sorella', 'madre']], 'Marta è mia sorella.', [Kit::word('la sorella', 'sorella'), Kit::form('mia')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.famiglia', 'Read the conversation about Marta\'s family.', [
                Kit::line('Paolo', 'Hai fratelli, Marta?'),
                Kit::line('Marta', 'Sì, ho una sorella e un fratello. Mia sorella è single.'),
                Kit::line('Paolo', 'E tuo fratello?'),
                Kit::line('Marta', 'Mio fratello è maggiore e sposato. Suo figlio è qui.'),
                Kit::line('Paolo', 'E tuo padre e tua madre?'),
                Kit::line('Marta', 'La mia famiglia è qui. Ecco i miei nonni, mio padre e mia madre.'),
            ], [
                Kit::question('Who is single?', ['Marta\'s sister', 'Marta\'s brother', 'Marta\'s mother'], 'Marta\'s sister'),
                Kit::question('Who is described as older and married?', ['Her sister', 'Her brother', 'Her father'], 'Her brother'),
                Kit::question('Whom does Marta point out at the end?', ['Her sister and her brother', 'Her grandparents, her father and her mother', 'Nobody'], 'Her grandparents, her father and her mother'),
            ], [Kit::word('la famiglia'), Kit::word('il padre'), Kit::word('la madre'), Kit::word('il fratello'), Kit::word('la sorella'), Kit::word('il figlio'), Kit::word('i nonni'), Kit::word('sposato'), Kit::word('single'), Kit::word('maggiore')]),
            Kit::gap($stage, 'task.choose_gap.padre', 'Luca è il ___ di Marta.', ['padre', 'madre'], 'padre', Kit::word('il padre', 'padre'), 'Il goes with a masculine noun, so the father.', 'read'),
            Kit::gap($stage, 'task.choose_gap.madre', 'Anna è la ___ di Luca.', ['madre', 'padre'], 'madre', Kit::word('la madre', 'madre'), 'La goes with a feminine noun, so the mother.', 'read'),

            Kit::transform($stage, 'task.transform.fratelli', 'Make it plural.', 'Suo fratello è sposato.', ['I suoi fratelli sono sposati.'], [Kit::word('il fratello', 'fratelli'), Kit::word('sposato', 'sposati'), Kit::form('suoi', true)]),
            Kit::transform($stage, 'task.transform.figli', 'Make it plural.', 'Tuo figlio è single.', ['I tuoi figli sono single.'], [Kit::word('il figlio', 'figli'), Kit::word('single'), Kit::form('tuoi', true)]),
            Kit::transform($stage, 'task.transform.negativa', 'Make it negative.', 'Suo figlio è sposato.', ['Suo figlio non è sposato.'], [Kit::word('il figlio', 'figlio'), Kit::word('sposato'), Kit::form('suo')]),
            Kit::writeGuided($stage, 'task.write_guided.sorella', 'Say that you have a sister and that she is single.', ['sorella', 'single'], 'Ho una sorella single.', [
                ['forms' => ['sorella'], 'term' => 'la sorella'],
                ['forms' => ['single', 'nubile'], 'term' => 'single'],
            ], [Kit::word('la sorella'), Kit::word('single')]),
            Kit::writeGuided($stage, 'task.write_guided.fratello', 'Say that your older brother is married.', ['fratello', 'maggiore', 'sposato'], 'Mio fratello maggiore è sposato.', [
                ['forms' => ['fratello'], 'term' => 'il fratello'],
                ['forms' => ['maggiore'], 'term' => 'maggiore'],
                ['forms' => ['sposato'], 'term' => 'sposato'],
            ], [Kit::word('il fratello'), Kit::word('maggiore'), Kit::word('sposato')]),
            Kit::build($stage, 'task.build.famiglia', 'Marta and her family are here.', 'Marta e la sua famiglia sono qui.', ['suo', 'mia'], [Kit::word('la famiglia', 'famiglia'), Kit::form('sua')]),
            Kit::build($stage, 'task.build.madre-nonni', 'My mother is here with my grandparents.', 'Mia madre è qui con i miei nonni.', ['mie', 'sono'], [Kit::word('la madre', 'madre'), Kit::word('i nonni', 'nonni'), Kit::form('miei', true)]),
            Kit::build($stage, 'task.build.fratello-sposato', 'Her older brother is married.', 'Suo fratello maggiore è sposato.', ['sposata', 'suoi'], [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::word('sposato'), Kit::form('suo')]),
            Kit::translate($stage, 'task.translate.fratello-sorella', 'Paolo is my brother and Marta is my sister.', ['Paolo è mio fratello e Marta è mia sorella.'], [Kit::word('il fratello', 'fratello'), Kit::word('la sorella', 'sorella'), Kit::form('mio')]),
            Kit::translate($stage, 'task.translate.sposata', 'My mother is married and my father is too.', ['Mia madre è sposata e anche mio padre.', 'Mia madre è sposata e anche mio padre è sposato.', 'Mia madre è sposata e mio padre anche.'], [Kit::word('la madre', 'madre'), Kit::word('il padre', 'padre'), Kit::word('sposato', 'sposata'), Kit::form('mia')]),

            Kit::listenPassage($stage, 'task.listen_passage.fratello', [
                Kit::line('Luca', 'Anna, tuo fratello è sposato?'),
                Kit::line('Anna', 'Sì, e suo figlio è qui.'),
                Kit::line('Luca', 'E tua sorella?'),
                Kit::line('Anna', 'Mia sorella è single. I miei nonni sono con lei.'),
                Kit::line('Luca', 'Molto bene. Anche la mia famiglia è qui.'),
            ], [
                Kit::question('Is Anna\'s brother married?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who is single?', ['Anna\'s brother', 'Anna\'s sister', 'Anna\'s mother'], 'Anna\'s sister'),
                Kit::question('Who is with Anna\'s sister?', ['Her grandparents', 'Her father', 'Luca'], 'Her grandparents'),
            ], [
                Kit::question('Where is the son of Anna\'s brother?', ['Here', 'At home', 'The conversation does not say.'], 'Here'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Whose family is mentioned at the end?', ['Luca\'s', 'Anna\'s', 'Marta\'s'], 'Luca\'s'),
            ], [Kit::word('il fratello'), Kit::word('sposato'), Kit::word('il figlio'), Kit::word('la sorella'), Kit::word('single'), Kit::word('i nonni'), Kit::word('la famiglia')]),
            Kit::listenType($stage, 'task.listen_type.fratello-maggiore', 'Mio fratello maggiore è sposato.', 'My older brother is married.', [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::word('sposato'), Kit::form('mio')], homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.'),
            Kit::listenType($stage, 'task.listen_type.famiglia', 'La famiglia di Paolo è qui.', 'Paolo\'s family is here.', [Kit::word('la famiglia')], homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.'),
            Kit::listenType($stage, 'task.listen_type.nonni-padre', 'I tuoi nonni e tuo padre sono qui.', 'Your grandparents and your father are here.', [Kit::word('i nonni', 'nonni'), Kit::word('il padre', 'padre'), Kit::form('tuoi', true)], homophoneNote: 'E (and) and è (is) sound very close, and the accent is only a written difference. Here it means and, so it has no accent.'),

            Kit::speakAnswer($stage, 'task.speak_answer.madre', 'Tua madre è sposata?', 'Is your mother married?', [['sì', 'no', 'è', 'mia', 'sposata', 'single', 'nubile']], 'Sì, mia madre è sposata.', [Kit::word('la madre', 'madre'), Kit::word('sposato', 'sposata'), Kit::form('mia')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.famiglia', 'La tua famiglia è qui?', 'Is your family here?', [['sì', 'no', 'è', 'mia', 'famiglia', 'qui']], 'Sì, la mia famiglia è qui.', [Kit::word('la famiglia', 'famiglia'), Kit::form('mia')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.maggiore', 'Tuo fratello è maggiore di Paolo?', 'Is your brother older than Paolo?', [['sì', 'no', 'è', 'mio', 'maggiore', 'fratello']], 'Sì, mio fratello è maggiore di Paolo.', [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::form('mio')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.nonni', 'Dove sono i tuoi nonni?', 'Where are your grandparents?', [['qui', 'lì', 'a']], 'I miei nonni sono lì.', [Kit::word('i nonni', 'nonni'), Kit::form('miei', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.padre', 'Tuo padre è qui con Luca.', 'Your father is here with Luca.', [Kit::word('il padre', 'padre'), Kit::form('tuo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.figlio', 'Suo figlio è single.', 'Her son is single.', [Kit::word('il figlio', 'figlio'), Kit::word('single'), Kit::form('suo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sorella', 'Your sister is older than Paolo.', ['Tua sorella è maggiore di Paolo.'], [Kit::word('la sorella', 'sorella'), Kit::word('maggiore'), Kit::form('tua')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.padre-madre', 'Her father is married and her mother is single.', ['Suo padre è sposato, sua madre è single.', 'Suo padre è sposato e sua madre è single.', 'Suo padre è sposato, sua madre è nubile.', 'Suo padre è sposato e sua madre è nubile.'], [Kit::word('il padre', 'padre'), Kit::word('sposato'), Kit::word('la madre', 'madre'), Kit::word('single', null, ['nubile']), Kit::form('suo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.fratelli', 'My brothers are single.', ['I miei fratelli sono single.', 'I miei fratelli sono celibi.'], [Kit::word('il fratello', 'fratelli'), Kit::word('single', null, ['celibi']), Kit::form('miei', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.figlio', 'My son is with my family.', ['Mio figlio è con la mia famiglia.'], [Kit::word('il figlio', 'figlio'), Kit::word('la famiglia', 'famiglia'), Kit::form('mio')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.nonni', 'I tuoi ___ sono con Luca.', 'Your grandparents are with Luca.', 'nonni', Kit::word('i nonni', 'nonni'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.sposata', 'Mia madre è ___.', 'My mother is married.', 'sposata', Kit::word('sposato', 'sposata'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.madre-fratello', 'Paolo è qui con sua madre e suo fratello.', 'Paolo is here with his mother and his brother.', [Kit::word('la madre', 'madre'), Kit::word('il fratello', 'fratello'), Kit::form('suo')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. The first is the verb is, the second joins two nouns, so it has no accent.'),
            Kit::listenType($stage, 'check.a.listen_type.padre-nonni', 'Mio padre e i miei nonni sono con la famiglia.', 'My father and my grandparents are with the family.', [Kit::word('il padre', 'padre'), Kit::word('i nonni', 'nonni'), Kit::word('la famiglia', 'famiglia'), Kit::form('miei', true)], 'dictation', $set, homophoneNote: 'E (and) and è (is) sound very close, and the accent is only a written difference. Here it means and, so it has no accent.'),
            Kit::listenType($stage, 'check.a.listen_type.sorella-maggiore', 'Marta è la sorella maggiore e ha un figlio.', 'Marta is the older sister and has a son.', [Kit::word('la sorella', 'sorella'), Kit::word('maggiore'), Kit::word('il figlio', 'figlio')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference: the first is the verb is, the second means and. Ha (has) sounds the same as a (to); only the h on paper tells them apart, and here it is the verb has.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.sorella', [
                Kit::line('Anna', 'Paolo, tua sorella è sposata?'),
                Kit::line('Paolo', 'No, è single. Mio fratello è sposato.'),
                Kit::line('Anna', 'E i tuoi nonni?'),
                Kit::line('Paolo', 'I miei nonni sono lì.'),
            ], [
                Kit::question('Is Paolo\'s sister married?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Who is married?', ['Paolo\'s sister', 'Paolo\'s brother', 'Anna\'s brother'], 'Paolo\'s brother'),
                Kit::question('Where are Paolo\'s grandparents?', ['Here', 'There', 'The conversation does not say.'], 'There'),
            ], [
                Kit::question('Who asks the questions?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('What does Anna ask about last?', ['The grandparents', 'The sister', 'The brother'], 'The grandparents'),
            ], [Kit::word('la sorella'), Kit::word('sposato'), Kit::word('single'), Kit::word('il fratello'), Kit::word('i nonni')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.madre', 'Read the conversation.', [
                Kit::line('Luca', 'Anna, tua madre è qui?'),
                Kit::line('Anna', 'Sì, e anche mio padre.'),
                Kit::line('Luca', 'E tuo figlio?'),
                Kit::line('Anna', 'Mio figlio non è qui.'),
            ], [
                Kit::question('Who is here?', ['Her mother and her father', 'Her mother and her son', 'Only her son'], 'Her mother and her father'),
                Kit::question('Is Anna\'s son here?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('la madre'), Kit::word('il padre'), Kit::word('il figlio')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sorella', 'Hai una sorella o un fratello?', 'Do you have a sister or a brother?', [['sì', 'no', 'non', 'ho', 'sono'], ['sorella', 'fratello', 'sorelle', 'fratelli', 'figlio', 'una', 'un']], 'Ho una sorella.', [Kit::word('la sorella', 'sorella')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.single', 'Tuo fratello è single?', 'Is your brother single?', [['sì', 'no', 'è', 'single', 'sposato']], 'Sì, mio fratello è single.', [Kit::word('single')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.maggiore', 'Chi è maggiore, tuo fratello o tua sorella?', 'Who is older, your brother or your sister?', [['mio', 'mia', 'il', 'la'], ['fratello', 'sorella']], 'Mio fratello è maggiore.', [Kit::word('maggiore')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.madre-padre', 'My mother is here with my father.', ['Mia madre è qui con mio padre.'], [Kit::word('la madre', 'madre'), Kit::word('il padre', 'padre'), Kit::form('mia')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sorella-fratello', 'Her sister is married and her brother is single.', ['Sua sorella è sposata e suo fratello è single.', 'Sua sorella è sposata e suo fratello è celibe.'], [Kit::word('la sorella', 'sorella'), Kit::word('sposato', 'sposata'), Kit::word('il fratello', 'fratello'), Kit::word('single', null, ['celibe']), Kit::form('sua')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.figli', 'My sons are single.', ['I miei figli sono single.', 'I miei figli sono celibi.'], [Kit::word('il figlio', 'figli'), Kit::word('single', null, ['celibi']), Kit::form('miei', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.nonni', 'Your grandparents are married.', ['I tuoi nonni sono sposati.'], [Kit::word('i nonni', 'nonni'), Kit::word('sposato', 'sposati'), Kit::form('tuoi', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.suoi-nonni', 'Paolo e i ___ nonni sono qui.', 'Paolo and his grandparents are here.', 'suoi', Kit::form('suoi', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.maggiore', 'Marta è ___ di Luca.', 'Marta is older than Luca.', 'maggiore', Kit::word('maggiore'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.fratello-maggiore', 'Il fratello maggiore è con la famiglia.', 'The older brother is with the family.', [Kit::word('il fratello', 'fratello'), Kit::word('maggiore'), Kit::word('la famiglia', 'famiglia')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.'),
            Kit::listenType($stage, 'check.b.listen_type.famiglia-nonni', 'La famiglia è qui con i nonni e un figlio.', 'The family is here with the grandparents and a son.', [Kit::word('la famiglia', 'famiglia'), Kit::word('i nonni', 'nonni'), Kit::word('il figlio', 'figlio')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. The first is the verb is, the second means and, so it has no accent.'),
            Kit::listenType($stage, 'check.b.listen_type.padre-madre-sorella', 'Luca è con suo padre, sua madre e sua sorella.', 'Luca is with his father, his mother and his sister.', [Kit::word('il padre', 'padre'), Kit::word('la madre', 'madre'), Kit::word('la sorella', 'sorella'), Kit::form('suo')], 'dictation', $set, homophoneNote: 'È (is) and e (and) sound very close, and the accent is only a written difference. The first is the verb is, the second means and, so it has no accent.'),
        ];
    }
}
