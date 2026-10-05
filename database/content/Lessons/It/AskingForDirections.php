<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('la strada', cue: 'street, road', accepted: ['la via']),
            new WordData('l\'angolo', cue: 'corner (of a street)', forms: ['all\'angolo']),
            new WordData('a destra', cue: 'to the right'),
            new WordData('a sinistra', cue: 'to the left'),
            new WordData('sempre dritto', cue: 'straight ahead', accepted: ['dritto', 'sempre diritto', 'tutto dritto']),
            new WordData('vicino', cue: 'near, close', accepted: ['vicino a'], forms: ['vicina']),
            new WordData('lontano', cue: 'far', accepted: ['lontano da'], forms: ['lontana']),
            new WordData('la cartina', cue: 'map of a town', accepted: ['la mappa', 'la piantina']),
            new WordData('dov\'è… ?', cue: 'where is…? (asking for a place)', accepted: ['dov\'è']),
            new WordData('la piazza', cue: 'square, plaza (in a town)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Prendo la strada a destra.', 'english' => 'I take the street on the right.'],
            ['text' => 'La strada finisce all\'angolo.', 'english' => 'The street ends at the corner.'],
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
            Kit::gap($stage, 'sentences.choose_gap.luca-strada', 'Luca ___ la strada a destra.', ['prende', 'prendi', 'prendo'], 'prende', Kit::form('prende'), 'Luca is a he, so the -ere verb ends in -e.', 'choose', 'Luca takes the street on the right.'),
            Kit::gap($stage, 'sentences.choose_gap.voi-prendete', 'Voi ___ la strada a sinistra.', ['prendete', 'partite', 'prendiamo'], 'prendete', Kit::form('prendete', true), 'Prendere is an -ere verb, so voi ends in -ete. The -ite ending belongs to -ire verbs such as partire.', 'choose', 'You (all) take the street on the left.'),
            Kit::gap($stage, 'sentences.choose_gap.voi-partite', 'Voi ___ da qui.', ['partite', 'vivete', 'partiamo'], 'partite', Kit::form('partite', true), 'Partire is an -ire verb, so voi ends in -ite and not in -ete like prendere.', 'choose', 'You (all) leave from here.'),
            Kit::gap($stage, 'sentences.choose_gap.anna-parte', 'Anna ___ da qui.', ['parte', 'vive', 'finisce'], 'parte', Kit::form('parte'), 'Anna is a she, so the -ire verb ends in -e.', 'choose', 'Anna leaves from here.'),
            Kit::gap($stage, 'sentences.choose_gap.vicino', 'Anna non vive lontano, vive ___.', ['vicino', 'lontano'], 'vicino', Kit::word('vicino'), 'Vicino means near, the opposite of lontano.', 'choose', 'Anna does not live far, she lives near.'),
            Kit::gap($stage, 'sentences.choose_gap.destra', 'La piazza è a ___.', ['destra', 'vicino', 'lontano'], 'destra', Kit::word('a destra', 'destra'), 'On the right is the fixed phrase a destra.', 'choose', 'The square is on the right.'),

            Kit::typeGap($stage, 'sentences.type_gap.marta-vive', 'Marta ___ vicino alla piazza.', 'Marta lives near the square.', 'vive', Kit::form('vive'), 'Marta is a she, so the -ere verb ends in -e.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-prendi', 'Tu ___ la strada a destra.', 'You take the street on the right.', 'prendi', Kit::form('prendi'), 'With tu, an -ere verb ends in -i.'),
            Kit::typeGap($stage, 'sentences.type_gap.noi-viviamo', 'Noi ___ lontano da qui.', 'We live far from here.', 'viviamo', Kit::form('viviamo'), 'With noi, -ere and -ire verbs both end in -iamo.'),
            Kit::typeGap($stage, 'sentences.type_gap.vivono', 'Luca e Paolo ___ vicino all\'angolo.', 'Luca and Paolo live near the corner.', 'vivono', Kit::form('vivono'), 'With they, an -ere verb ends in -ono.'),
            Kit::typeGap($stage, 'sentences.type_gap.finisce', 'La strada ___ all\'angolo.', 'The street ends at the corner.', 'finisce', Kit::form('finisce', true), 'Finire is an -ire verb that adds -isc- with io, tu, lui, lei and loro (finisco, finisci, finisce, finiscono): finisce, not fine.'),

            Kit::translate($stage, 'sentences.translate.viviamo-piazza', 'We live near the square.', ['Viviamo vicino alla piazza.', 'Noi viviamo vicino alla piazza.'], [Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::form('viviamo')]),
            Kit::translate($stage, 'sentences.translate.prendo-angolo', 'I take the street on the right at the corner.', ['Prendo la strada a destra all\'angolo.', 'Io prendo la strada a destra all\'angolo.', 'All\'angolo prendo la strada a destra.'], [Kit::word('la strada', 'strada'), Kit::word('a destra'), Kit::word('l\'angolo', 'all\'angolo'), Kit::form('prendo')]),
            Kit::translate($stage, 'sentences.translate.anna-lontano', 'Anna lives far away.', ['Anna vive lontano.'], [Kit::word('lontano'), Kit::form('vive')]),
            Kit::build($stage, 'sentences.build.dove-piazza', 'Where is the square?', 'Dov\'è la piazza?', ['sei'], [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la piazza', 'piazza')]),
            Kit::build($stage, 'sentences.build.piazza-destra', 'The square is to the right.', 'La piazza è a destra.', ['sono'], [Kit::word('la piazza', 'piazza'), Kit::word('a destra')]),
            Kit::build($stage, 'sentences.build.viviamo-qui', 'We live far from here.', 'Viviamo lontano da qui.', ['partiamo'], [Kit::word('lontano'), Kit::form('viviamo')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vivo-piazza', 'Vivo vicino alla piazza.', ['I live near the square.', 'I live far from the square.', 'We live near the square.', 'I leave from the square.'], 'I live near the square.', [Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::form('vivo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.cartina-qui', 'La cartina è qui.', ['The map is here.', 'The street is here.', 'The square is here.', 'I have the map.'], 'The map is here.', [Kit::word('la cartina', 'cartina')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.angolo-sinistra', 'L\'angolo è a sinistra.', ['The corner is on the left.', 'The corner is on the right.', 'The street is on the left.', 'The corner is near.'], 'The corner is on the left.', [Kit::word('l\'angolo'), Kit::word('a sinistra')]),
            Kit::listenType($stage, 'sentences.listen_type.marta-lontano', 'Marta vive lontano da qui.', 'Marta lives far from here.', [Kit::word('lontano'), Kit::form('vive')]),
            Kit::listenType($stage, 'sentences.listen_type.dove-cartina', 'Dov\'è la mia cartina?', 'Where is my map?', [Kit::word('la cartina', 'cartina'), Kit::word('dov\'è… ?', 'dov\'è')]),
            Kit::listenType($stage, 'sentences.listen_type.voi-prendete', 'Voi prendete la strada a destra.', 'You (all) take the street on the right.', [Kit::word('la strada', 'strada'), Kit::word('a destra'), Kit::form('prendete')], homophoneNote: 'The a in a destra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here.'),
            Kit::listenType($stage, 'sentences.listen_type.piazza-li', 'Sempre dritto, la piazza è lì.', 'Straight ahead, the square is there.', [Kit::word('la piazza', 'piazza'), Kit::word('sempre dritto')], homophoneNote: 'È (is) sounds close to e (and), but the sentence tells you it is the verb. Lì (there) also takes an accent.'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.dove-piazza', 'Dov\'è la piazza? È a destra.', 'Where is the square? It is to the right.', [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la piazza', 'piazza'), Kit::word('a destra')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.viviamo-angolo', 'Viviamo vicino all\'angolo.', 'We live near the corner.', [Kit::word('vicino'), Kit::word('l\'angolo', 'all\'angolo'), Kit::form('viviamo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.strada-sinistra', 'La strada è a sinistra.', 'The street is on the left.', [Kit::word('la strada', 'strada'), Kit::word('a sinistra')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.strada-dritto', 'Prendi la strada, sempre dritto.', 'Take the street, straight ahead.', [Kit::word('la strada', 'strada'), Kit::word('sempre dritto'), Kit::form('prendi')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.piazza', 'Dov\'è la piazza?', 'Where is the square?', [['destra', 'sinistra', 'dritto', 'vicino', 'lontano', 'qui', 'lì', 'all\'angolo', 'l\'angolo']], 'La piazza è a destra.', [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la piazza', 'piazza')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vicino-lontano', 'Vivi vicino o lontano?', 'Do you live near or far?', [['vicino', 'lontano']], 'Vivo vicino.', [Kit::word('vicino'), Kit::word('lontano'), Kit::form('vivo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cartina', 'Hai una cartina?', 'Do you have a map?', [['sì', 'no']], 'Sì, ho una cartina.', [Kit::word('la cartina', 'cartina')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.piazza', 'Read the conversation in the street.', [
                Kit::line('Anna', 'Ciao, Paolo. Ho la cartina. Dov\'è la piazza?'),
                Kit::line('Paolo', 'Sempre dritto per la strada. Non è lontano.'),
                Kit::line('Anna', 'E l\'angolo? A destra o a sinistra?'),
                Kit::line('Paolo', 'All\'angolo, a destra. Io vivo vicino alla piazza.'),
                Kit::line('Anna', 'Molto bene, grazie.'),
            ], [
                Kit::question('What does Anna have?', ['A map', 'A square', 'A corner'], 'A map'),
                Kit::question('Is the square far?', ['Yes, it is far.', 'No, it is not far.', 'The text does not say.'], 'No, it is not far.'),
                Kit::question('Which way does Paolo say at the corner?', ['To the left', 'To the right', 'Straight ahead'], 'To the right'),
            ], [Kit::word('la cartina', 'cartina'), Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la piazza', 'piazza'), Kit::word('lontano'), Kit::word('sempre dritto'), Kit::word('la strada', 'strada'), Kit::word('l\'angolo'), Kit::word('a destra'), Kit::word('a sinistra'), Kit::word('vicino')], 'read'),
            Kit::gap($stage, 'task.choose_gap.voi-vivete', 'Voi ___ vicino alla piazza.', ['vivete', 'partite', 'vivono'], 'vivete', Kit::form('vivete', true), 'Vivere is an -ere verb, so voi ends in -ete. Partire, an -ire verb, would give partite.', 'read', 'You (all) live near the square.'),
            Kit::gap($stage, 'task.choose_gap.dritto', 'Prendi la strada, sempre ___.', ['dritto', 'destra'], 'dritto', Kit::word('sempre dritto', 'dritto'), 'Sempre dritto is a fixed phrase meaning straight ahead.', 'read'),

            Kit::transform($stage, 'task.transform.noi-prendiamo', 'Change to noi.', 'Luca prende la strada a destra.', ['Noi prendiamo la strada a destra.', 'Prendiamo la strada a destra.'], [Kit::word('la strada', 'strada'), Kit::word('a destra'), Kit::form('prendiamo')], ['prende' => 'takes']),
            Kit::transform($stage, 'task.transform.noi-partiamo', 'Change to noi.', 'Paolo parte da qui.', ['Noi partiamo da qui.', 'Partiamo da qui.'], [Kit::form('partiamo')], ['parte' => 'leaves']),
            Kit::transform($stage, 'task.transform.tu-vivi', 'Change to tu.', 'Io vivo vicino all\'angolo.', ['Tu vivi vicino all\'angolo.', 'Vivi vicino all\'angolo.'], [Kit::word('vicino'), Kit::word('l\'angolo', 'all\'angolo'), Kit::form('vivi')]),
            Kit::writeGuided($stage, 'task.write_guided.vivo', 'Say that you live far from here.', ['vivo', 'lontano', 'qui'], 'Vivo lontano da qui.', [
                ['forms' => ['vivo'], 'term' => null],
                ['forms' => ['lontano'], 'term' => 'lontano'],
                ['forms' => ['qui'], 'term' => null],
            ], [Kit::word('lontano'), Kit::form('vivo')]),
            Kit::writeGuided($stage, 'task.write_guided.dove', 'Ask where the corner is and say that the square is on the left.', ['dov\'è', 'l\'angolo', 'piazza', 'sinistra'], 'Dov\'è l\'angolo? La piazza è a sinistra.', [
                ['forms' => ['dov\'è', 'dove'], 'term' => 'dov\'è… ?'],
                ['forms' => ['l\'angolo'], 'term' => 'l\'angolo'],
                ['forms' => ['piazza'], 'term' => 'la piazza'],
                ['forms' => ['sinistra'], 'term' => 'a sinistra'],
            ], [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('l\'angolo'), Kit::word('la piazza', 'piazza'), Kit::word('a sinistra', 'sinistra')]),
            Kit::build($stage, 'task.build.cartina-destra', 'The map is on the right.', 'La cartina è a destra.', ['sono', 'sei'], [Kit::word('la cartina', 'cartina'), Kit::word('a destra')], 'write'),
            Kit::build($stage, 'task.build.prendiamo-angolo', 'We take the street near the corner.', 'Prendiamo la strada vicino all\'angolo.', ['viviamo', 'prendete'], [Kit::word('vicino'), Kit::word('la strada', 'strada'), Kit::word('l\'angolo', 'all\'angolo'), Kit::form('prendiamo')], 'write'),
            Kit::build($stage, 'task.build.paolo-vive', 'Paolo lives near the square.', 'Paolo vive vicino alla piazza.', ['vivete', 'parte'], [Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::form('vive')], 'write'),
            Kit::translate($stage, 'task.translate.vivi-angolo', 'Do you live near the corner?', ['Vivi vicino all\'angolo?', 'Tu vivi vicino all\'angolo?', 'Lei vive vicino all\'angolo?', 'Vive vicino all\'angolo?', 'Vivete vicino all\'angolo?', 'Voi vivete vicino all\'angolo?'], [Kit::word('vicino'), Kit::word('l\'angolo', 'all\'angolo')]),
            Kit::translate($stage, 'task.translate.piazza-dritto', 'Straight ahead, and the square is near the corner.', ['Sempre dritto, e la piazza è vicino all\'angolo.', 'Sempre dritto e la piazza è vicino all\'angolo.', 'Sempre dritto, e la piazza è vicina all\'angolo.', 'Sempre dritto e la piazza è vicina all\'angolo.'], [Kit::word('la piazza', 'piazza'), Kit::word('sempre dritto'), Kit::word('vicino', null, ['vicina']), Kit::word('l\'angolo', 'all\'angolo')]),

            Kit::listenPassage($stage, 'task.listen_passage.piazza', [
                Kit::line('Luca', 'Ciao, Marta. Dov\'è la piazza?'),
                Kit::line('Marta', 'È vicino. Sempre dritto. All\'angolo, a sinistra.'),
                Kit::line('Luca', 'È lontano?'),
                Kit::line('Marta', 'No, non è lontano. Io vivo vicino alla piazza.'),
                Kit::line('Luca', 'Grazie, ciao.'),
            ], [
                Kit::question('What does Luca ask about?', ['The square', 'The map', 'The street'], 'The square'),
                Kit::question('Which way does Marta say at the corner?', ['To the right', 'To the left', 'Marta does not say.'], 'To the left'),
                Kit::question('Is it far?', ['Yes', 'No', 'Marta does not say.'], 'No'),
            ], [
                Kit::question('Who asks the first question?', ['Luca', 'Marta', 'Paolo'], 'Luca'),
                Kit::question('Where does Marta live?', ['Near the square', 'Far from the square', 'The conversation does not say.'], 'Near the square'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la piazza', 'piazza'), Kit::word('vicino'), Kit::word('sempre dritto'), Kit::word('l\'angolo', 'all\'angolo'), Kit::word('a sinistra'), Kit::word('lontano')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.cartina-destra', 'La cartina è a destra.', 'The map is on the right.', [Kit::word('la cartina', 'cartina'), Kit::word('a destra')], 'listen', null, [], 'The a in a destra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here. È (is) sounds close to e (and), and the sentence tells you which one it is.'),
            Kit::listenType($stage, 'task.listen_type.paolo-lontano', 'Paolo vive lontano da qui.', 'Paolo lives far from here.', [Kit::word('lontano'), Kit::form('vive')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.anna-angolo', 'Anna vive vicino all\'angolo.', 'Anna lives near the corner.', [Kit::word('vicino'), Kit::word('l\'angolo', 'all\'angolo'), Kit::form('vive')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.lontano', 'Vivi lontano da qui?', 'Do you live far from here?', [['sì', 'no', 'lontano', 'vicino']], 'No, vivo vicino.', [Kit::word('lontano'), Kit::word('vicino'), Kit::form('vivo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.dove-cartina', 'Dov\'è la cartina?', 'Where is the map?', [['qui', 'lì', 'destra', 'sinistra', 'dritto', 'vicino', 'lontano', 'all\'angolo']], 'La cartina è qui.', [Kit::word('la cartina', 'cartina'), Kit::word('dov\'è… ?', 'dov\'è')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.angolo', 'Dov\'è l\'angolo?', 'Where is the corner?', [['destra', 'sinistra', 'dritto', 'vicino', 'lontano', 'qui', 'lì']], 'L\'angolo è a destra.', [Kit::word('l\'angolo'), Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('a destra')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.strada', 'Prendi la strada a destra o a sinistra?', 'Do you take the street on the right or on the left?', [['destra', 'sinistra']], 'Prendo la strada a destra.', [Kit::word('la strada', 'strada'), Kit::word('a destra'), Kit::word('a sinistra'), Kit::form('prendo')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.dritto-destra', 'Sempre dritto e a destra, vicino alla piazza.', 'Straight ahead and to the right, near the square.', [Kit::word('sempre dritto'), Kit::word('a destra'), Kit::word('vicino'), Kit::word('la piazza', 'piazza')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.viviamo-prendiamo', 'Viviamo lontano da qui e prendiamo la strada.', 'We live far from here and take the street.', [Kit::word('lontano'), Kit::word('la strada', 'strada'), Kit::form('viviamo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.paolo-strada', 'Paolo takes the street on the left, near the square.', ['Paolo prende la strada a sinistra, vicino alla piazza.'], [Kit::word('la strada', 'strada'), Kit::word('a sinistra'), Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::form('prende')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-anna', 'Marta and Anna live on the right, far from here.', ['Marta e Anna vivono a destra, lontano da qui.'], [Kit::word('a destra'), Kit::word('lontano'), Kit::form('vivono')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dove-strada', 'Where is the street? Straight ahead.', ['Dov\'è la strada? Sempre dritto.', 'Dov\'è la strada? Sempre diritto.', 'Dov\'è la strada? Tutto dritto.'], [Kit::word('dov\'è… ?', 'dov\'è', ['dove è']), Kit::word('la strada', 'strada'), Kit::word('sempre dritto', null, ['sempre diritto', 'tutto dritto'])], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dove-cartina', 'Where is the map? It is near the corner.', ['Dov\'è la cartina? È vicino all\'angolo.', 'Dov\'è la cartina? È vicina all\'angolo.', 'Dove è la cartina? È vicino all\'angolo.', 'Dove è la cartina? È vicina all\'angolo.'], [Kit::word('dov\'è… ?', 'dov\'è', ['dove è']), Kit::word('la cartina', 'cartina'), Kit::word('vicino', null, ['vicina']), Kit::word('l\'angolo', 'all\'angolo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.voi-partite', 'Voi ___ con Luca.', 'You (all) leave with Luca.', 'partite', Kit::form('partite', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.finisce', 'La strada ___ qui.', 'The street ends here.', 'finisce', Kit::form('finisce', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.anna-piazza', 'Anna vive vicino alla piazza, a destra.', 'Anna lives near the square, on the right.', [Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::word('a destra'), Kit::form('vive')], 'dictation', $set, [], 'The a in a destra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here.'),
            Kit::listenType($stage, 'check.a.listen_type.dritto-angolo', 'Sempre dritto, all\'angolo a sinistra.', 'Straight ahead, at the corner on the left.', [Kit::word('sempre dritto'), Kit::word('l\'angolo', 'all\'angolo'), Kit::word('a sinistra')], 'dictation', $set, [], 'The a in a sinistra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here.'),
            Kit::listenType($stage, 'check.a.listen_type.voi-prendete', 'Voi prendete la cartina, la piazza è lontano.', 'You (all) take the map, the square is far.', [Kit::word('la cartina', 'cartina'), Kit::word('la piazza', 'piazza'), Kit::word('lontano'), Kit::form('prendete', true)], 'dictation', $set, [], 'È (is) sounds close to e (and), and the sentence tells you which one it is: here è is the verb.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.marta', [
                Kit::line('Paolo', 'Ciao, Marta. Vivi vicino alla piazza?'),
                Kit::line('Marta', 'No, vivo lontano da qui. La piazza è lì, a destra.'),
                Kit::line('Paolo', 'Io vivo vicino. Ho la cartina qui.'),
                Kit::line('Marta', 'Molto bene. Ciao, Paolo.'),
            ], [
                Kit::question('Does Marta live near the square?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Which side is the square on?', ['On the left', 'On the right', 'The conversation does not say.'], 'On the right'),
                Kit::question('What does Paolo have?', ['A map', 'A square', 'A corner'], 'A map'),
            ], [
                Kit::question('Who asks the first question?', ['Paolo', 'Marta', 'Luca'], 'Paolo'),
                Kit::question('Does Paolo live near the square?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('la piazza', 'piazza'), Kit::word('vicino'), Kit::word('lontano'), Kit::word('a destra'), Kit::word('la cartina', 'cartina')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.luca', 'Read the conversation.', [
                Kit::line('Luca', 'Ciao, Anna. Hai la cartina?'),
                Kit::line('Anna', 'Sì, ho la cartina. La piazza è a sinistra.'),
                Kit::line('Luca', 'È lontano da qui?'),
                Kit::line('Anna', 'No, è vicino. Sempre dritto e a sinistra.'),
            ], [
                Kit::question('Does Anna have the map?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Is the square far?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('la cartina', 'cartina'), Kit::word('la piazza', 'piazza'), Kit::word('a sinistra'), Kit::word('lontano'), Kit::word('vicino'), Kit::word('sempre dritto')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vivi-piazza', 'Vivi vicino alla piazza?', 'Do you live near the square?', [['sì', 'no', 'vicino', 'lontano']], 'Sì, vivo vicino alla piazza.', [Kit::word('vicino'), Kit::word('la piazza', 'piazza')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.piazza-lontano', 'La piazza è lontano?', 'Is the square far?', [['sì', 'no', 'vicino']], 'No, è vicino.', [Kit::word('la piazza', 'piazza'), Kit::word('lontano')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.strada-destra', 'La strada è a destra o a sinistra?', 'Is the street on the right or on the left?', [['destra', 'sinistra']], 'La strada è a sinistra.', [Kit::word('la strada', 'strada'), Kit::word('a destra'), Kit::word('a sinistra')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.prendiamo-strada', 'We take the street near the square.', ['Prendiamo la strada vicino alla piazza.', 'Noi prendiamo la strada vicino alla piazza.'], [Kit::word('la strada', 'strada'), Kit::word('vicino'), Kit::word('la piazza', 'piazza'), Kit::form('prendiamo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.luca-paolo', 'Luca and Paolo live far from here.', ['Luca e Paolo vivono lontano da qui.'], [Kit::word('lontano'), Kit::form('vivono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.dove-angolo', 'Where is the corner? Straight ahead.', ['Dov\'è l\'angolo? Sempre dritto.', 'Dov\'è l\'angolo? Sempre diritto.', 'Dov\'è l\'angolo? Tutto dritto.'], [Kit::word('dov\'è… ?', 'dov\'è', ['dove è']), Kit::word('l\'angolo'), Kit::word('sempre dritto', null, ['sempre diritto', 'tutto dritto'])], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.cartina-destra', 'The map is on the right, near the corner.', ['La cartina è a destra, vicino all\'angolo.'], [Kit::word('la cartina', 'cartina'), Kit::word('a destra'), Kit::word('vicino'), Kit::word('l\'angolo', 'all\'angolo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-parte', 'Marta ___ da qui.', 'Marta leaves from here.', 'parte', Kit::form('parte'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.voi-finite', 'Voi ___ qui.', 'You (all) finish here.', 'finite', Kit::form('finite', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.anna-cartina', 'Anna, dov\'è la cartina? A sinistra.', 'Anna, where is the map? On the left.', [Kit::word('dov\'è… ?', 'dov\'è'), Kit::word('la cartina', 'cartina'), Kit::word('a sinistra')], 'dictation', $set, [], 'The a in a sinistra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here.'),
            Kit::listenType($stage, 'check.b.listen_type.luca-strada', 'Luca prende la strada a sinistra, sempre dritto.', 'Luca takes the street on the left, straight ahead.', [Kit::word('la strada', 'strada'), Kit::word('a sinistra'), Kit::word('sempre dritto'), Kit::form('prende')], 'dictation', $set, [], 'The a in a sinistra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here.'),
            Kit::listenType($stage, 'check.b.listen_type.voi-vivete', 'Voi vivete lontano, la piazza è a destra.', 'You (all) live far away, the square is on the right.', [Kit::word('lontano'), Kit::word('la piazza', 'piazza'), Kit::word('a destra'), Kit::form('vivete', true)], 'dictation', $set, [], 'The a in a destra is the preposition a. It sounds the same as ha (has), but ha is a verb and is never written here. È (is) sounds close to e (and), and the sentence tells you which one it is.'),
        ];
    }
}
