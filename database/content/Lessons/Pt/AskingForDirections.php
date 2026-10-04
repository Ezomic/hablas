<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AskingForDirections implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'asking-for-directions';
    }

    public function words(): array
    {
        return [
            new WordData('a rua', cue: 'street', portunolSlips: ['la calle', 'calle']),
            new WordData('a esquina', cue: 'corner (of a street)', questions: ['Is "a esquina" the natural word for a street corner in Portugal, or would people say "o cruzamento" (crossing, junction) instead? Should "o cruzamento" be accepted as well?']),
            new WordData('à direita', cue: 'to the right', portunolSlips: ['a la derecha', 'derecha']),
            new WordData('à esquerda', cue: 'to the left', portunolSlips: ['a la izquierda', 'izquierda']),
            new WordData('sempre em frente', cue: 'straight ahead', accepted: ['em frente', 'sempre a direito', 'a direito'], portunolSlips: ['todo recto', 'todo derecho', 'recto'], questions: ['Are "em frente", "sempre a direito" and "a direito" all natural, accepted ways to say straight ahead in Portugal, and is "sempre em frente" itself the most common?']),
            new WordData('perto', cue: 'near', portunolSlips: ['cerca']),
            new WordData('longe', cue: 'far', portunolSlips: ['lejos']),
            new WordData('o mapa', cue: 'map', questions: ['Is "o mapa" right for a street map in Portugal, or would "a planta" (town plan) be said for a city? The exercises say "Tens o mapa?" and "O mapa está aqui."']),
            new WordData('onde fica...?', cue: 'where is...? (asking where a place is)', accepted: ['onde está...?', 'onde fica', 'onde está'], portunolSlips: ['dónde está', 'dónde queda'], questions: ['Are "onde fica a rua?" and "onde está a rua?" both right and equally natural, and is the answer "Fica à direita" natural (not "Está à direita")? The exercises also accept "onde está" and "está" in the translations.']),
            new WordData('a praça', cue: 'square (in a town)', portunolSlips: ['la plaza', 'a plaza'], questions: ['Is "a praça" the natural word for a town square in Portugal, and is "Parto da praça às três" natural for I leave the square at three, or would "saio da praça" be said?']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Como na praça.', 'english' => 'I eat in the square.'],
            ['text' => 'Partimos da rua.', 'english' => 'We leave the street.'],
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
            Kit::gap($stage, 'sentences.choose_gap.nos-praca', 'Nós ___ na praça.', ['comemos', 'comes', 'comem'], 'comemos', Kit::form('comemos'), 'Nós takes -emos in an -er verb like comer.', 'choose', 'We eat in the square.'),
            Kit::gap($stage, 'sentences.choose_gap.nos-partimos', 'Nós ___ da praça às duas.', ['partimos', 'comemos', 'partem'], 'partimos', Kit::form('partimos', true), 'The verb is partir, an -ir verb, so nós ends in -imos and not in -emos like comer.', 'choose', 'We leave the square at two.'),
            Kit::gap($stage, 'sentences.choose_gap.ana-parte', 'A Ana ___ da praça às três.', ['parte', 'parto', 'partes'], 'parte', Kit::form('parte'), 'Ana is a she, so the -ir verb ends in -e.', 'choose', 'Ana leaves the square at three.'),
            Kit::gap($stage, 'sentences.choose_gap.perto', 'Não fica longe, fica ___.', ['perto', 'cerca'], 'perto', Kit::word('perto'), 'Perto is the Portuguese word for near. Cerca is the Spanish word.', 'choose', 'It is not far, it is near.', ['cerca' => 'near (Spanish, not Portuguese)']),
            Kit::gap($stage, 'sentences.choose_gap.fica-perto', 'A rua ___ perto da praça.', ['fica', 'come', 'parte'], 'fica', Kit::form('fica', true), 'Where a place is takes ficar. Comer and partir are verbs for doing, not for saying where something is.', 'choose', 'The street is near the square.'),
            Kit::gap($stage, 'sentences.choose_gap.esquerda', 'A esquina fica ___.', ['à esquerda', 'à direita', 'longe'], 'à esquerda', Kit::word('à esquerda'), 'On the left is the fixed phrase à esquerda.', 'choose', 'The corner is on the left.'),

            Kit::typeGap($stage, 'sentences.type_gap.comemos', '___ na praça.', 'We eat in the square.', 'Comemos', Kit::form('comemos'), 'With we and an -er verb, the ending is -emos.'),
            Kit::typeGap($stage, 'sentences.type_gap.comes', 'Tu ___ perto da praça.', 'You eat near the square.', 'comes', Kit::form('comes'), 'With tu and an -er verb, the ending is -es.'),
            Kit::typeGap($stage, 'sentences.type_gap.partem', 'Eles ___ da praça às três.', 'They leave the square at three.', 'partem', Kit::form('partem'), 'With they and an -ir verb, the ending is -em, with the nasal vowel of bem.'),
            Kit::typeGap($stage, 'sentences.type_gap.parto', 'Eu ___ da esquina às duas.', 'I leave the corner at two.', 'parto', Kit::form('parto'), 'With eu, an -ir verb ends in -o.'),
            Kit::typeGap($stage, 'sentences.type_gap.mapa', 'O ___ está aqui.', 'The map is here.', 'mapa', Kit::word('o mapa', 'mapa')),

            Kit::translate($stage, 'sentences.translate.comemos-perto', 'We eat near the square.', ['Comemos perto da praça.', 'Nós comemos perto da praça.'], [Kit::word('perto'), Kit::word('a praça', 'praça'), Kit::form('comemos')]),
            Kit::translate($stage, 'sentences.translate.onde-rua', 'Where is the street? It is on the right.', ['Onde fica a rua? Fica à direita.', 'Onde está a rua? Está à direita.', 'Onde fica a rua? Está à direita.', 'Onde está a rua? Fica à direita.'], [Kit::word('onde fica...?', 'onde'), Kit::word('a rua'), Kit::word('à direita')]),
            Kit::translate($stage, 'sentences.translate.parto-praca', 'I leave the square at three.', ['Parto da praça às três.', 'Eu parto da praça às três.'], [Kit::word('a praça', 'praça'), Kit::form('parto')]),
            Kit::build($stage, 'sentences.build.praca-longe', 'The square is far.', 'A praça fica longe.', ['come'], [Kit::word('a praça'), Kit::word('longe'), Kit::form('fica', true)]),
            Kit::build($stage, 'sentences.build.onde-mapa', 'Where is the map?', 'Onde está o mapa?', ['come'], [Kit::word('onde fica...?', 'onde'), Kit::word('o mapa')]),
            Kit::build($stage, 'sentences.build.partimos-rua', 'We leave the street at two.', 'Partimos da rua às duas.', ['comemos'], [Kit::word('a rua', 'rua'), Kit::form('partimos', true)]),

            Kit::listenChoose($stage, 'sentences.listen_choose.rua-direita', 'A rua fica à direita.', ['The street is on the right.', 'The street is on the left.', 'The street is far.', 'The square is on the right.'], 'The street is on the right.', [Kit::word('a rua'), Kit::word('à direita'), Kit::form('fica')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.praca-frente', 'A praça fica sempre em frente.', ['The square is straight ahead.', 'The square is on the left.', 'The square is near.', 'The map is straight ahead.'], 'The square is straight ahead.', [Kit::word('a praça'), Kit::word('sempre em frente')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.esquina-esquerda', 'A esquina fica à esquerda.', ['The corner is on the left.', 'The corner is on the right.', 'The street is on the left.', 'The corner is near.'], 'The corner is on the left.', [Kit::word('a esquina'), Kit::word('à esquerda')]),
            Kit::listenType($stage, 'sentences.listen_type.onde-praca', 'Onde fica a praça?', 'Where is the square?', [Kit::word('onde fica...?', 'onde fica'), Kit::word('a praça')], homophoneNote: 'The a before praça is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.comes-rua', 'Tu comes perto da rua.', 'You eat near the street.', [Kit::word('perto'), Kit::word('a rua', 'rua'), Kit::form('comes')]),
            Kit::listenType($stage, 'sentences.listen_type.partem-esquina', 'Eles partem da esquina.', 'They leave the corner.', [Kit::word('a esquina', 'esquina'), Kit::form('partem')]),
            Kit::listenType($stage, 'sentences.listen_type.mapa-esquerda', 'O mapa está à esquerda.', 'The map is on the left.', [Kit::word('o mapa'), Kit::word('à esquerda')], homophoneNote: 'The à in à esquerda is a plus a (to the, on the), not the article a and not há (there is).'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.onde-praca', 'Onde fica a praça? Fica à direita.', 'Where is the square? It is on the right.', [Kit::word('onde fica...?', 'onde fica'), Kit::word('a praça'), Kit::word('à direita')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.comemos-esquina', 'Nós comemos perto da esquina.', 'We eat near the corner.', [Kit::word('perto'), Kit::word('a esquina', 'esquina'), Kit::form('comemos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.rua-longe', 'A rua fica longe da praça.', 'The street is far from the square.', [Kit::word('a rua'), Kit::word('longe'), Kit::word('a praça', 'praça'), Kit::form('fica')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.frente-esquerda', 'Sempre em frente e à esquerda.', 'Straight ahead and to the left.', [Kit::word('sempre em frente'), Kit::word('à esquerda')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.onde-praca', 'Onde fica a praça?', 'Where is the square?', [['praça', 'fica', 'está'], ['direita', 'direito', 'esquerda', 'frente', 'perto', 'longe', 'aqui', 'ali', 'lá']], 'A praça fica sempre em frente.', [Kit::word('onde fica...?', 'onde'), Kit::word('a praça'), Kit::word('sempre em frente')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.rua-praca', 'Comes na rua ou na praça?', 'Do you eat in the street or in the square?', [['como', 'comemos'], ['rua', 'praça']], 'Como na praça.', [Kit::word('a rua', 'rua'), Kit::word('a praça', 'praça'), Kit::form('como')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.mapa', 'Tens um mapa?', 'Do you have a map?', [['sim', 'não', 'tenho'], ['mapa']], 'Sim, tenho o mapa.', [Kit::word('o mapa', 'mapa')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.praca', 'Read the conversation in the street.', [
                Kit::line('Ana', 'Olá, Rui. Tenho o mapa. Onde fica a praça?'),
                Kit::line('Rui', 'A praça não fica longe. Fica sempre em frente.'),
                Kit::line('Ana', 'E a esquina? Fica à direita ou à esquerda?'),
                Kit::line('Rui', 'Na esquina, à direita. A rua fica perto.'),
                Kit::line('Ana', 'Muito bem, obrigada.'),
            ], [
                Kit::question('What does Ana have?', ['A map', 'A square', 'A corner'], 'A map'),
                Kit::question('Is the square far?', ['Yes, it is far.', 'No, it is not far.', 'The text does not say.'], 'No, it is not far.'),
                Kit::question('Which way does Rui say at the corner?', ['To the left', 'To the right', 'Straight ahead'], 'To the right'),
            ], [Kit::word('o mapa'), Kit::word('onde fica...?', 'onde fica'), Kit::word('a praça'), Kit::word('longe'), Kit::word('sempre em frente'), Kit::word('a esquina'), Kit::word('à direita'), Kit::word('à esquerda'), Kit::word('a rua'), Kit::word('perto')], 'read'),
            Kit::gap($stage, 'task.choose_gap.vocês-comem', 'Vocês ___ perto da praça.', ['comem', 'comemos', 'comes'], 'comem', Kit::form('comem'), 'Vocês, you all, takes -em, like eles. Comemos goes with nós and comes with tu.', 'read', 'You (all) eat near the square.'),
            Kit::gap($stage, 'task.choose_gap.longe', 'A praça não fica perto, fica ___.', ['longe', 'lejos'], 'longe', Kit::word('longe'), 'Longe is the Portuguese word for far. Lejos is the Spanish word.', 'read', 'The square is not near, it is far.', ['lejos' => 'far (Spanish, not Portuguese)']),

            Kit::transform($stage, 'task.transform.nos-partimos', 'Change the subject to nós.', 'Eu parto da praça.', ['Nós partimos da praça.', 'Partimos da praça.'], [Kit::word('a praça', 'praça'), Kit::form('partimos', true)]),
            Kit::transform($stage, 'task.transform.tu-comes', 'Change the subject to tu.', 'Ela come perto da rua.', ['Tu comes perto da rua.', 'Comes perto da rua.'], [Kit::word('perto'), Kit::word('a rua', 'rua'), Kit::form('comes')]),
            Kit::transform($stage, 'task.transform.eles-comem', 'Change the subject to eles.', 'Nós comemos na esquina.', ['Eles comem na esquina.', 'Comem na esquina.'], [Kit::word('a esquina', 'esquina'), Kit::form('comem')]),
            Kit::writeGuided($stage, 'task.write_guided.como', 'Say about yourself that you eat near the square.', ['como', 'perto', 'praça'], 'Como perto da praça.', [
                ['forms' => ['como'], 'term' => null],
                ['forms' => ['perto'], 'term' => 'perto'],
                ['forms' => ['praça'], 'term' => 'a praça'],
            ], [Kit::word('perto'), Kit::word('a praça', 'praça')]),
            Kit::writeGuided($stage, 'task.write_guided.onde', 'Ask where the street is and say that the corner is on the left.', ['onde', 'rua', 'esquina', 'esquerda'], 'Onde fica a rua? A esquina fica à esquerda.', [
                ['forms' => ['onde'], 'term' => 'onde fica...?'],
                ['forms' => ['rua'], 'term' => 'a rua'],
                ['forms' => ['esquina'], 'term' => 'a esquina'],
                ['forms' => ['esquerda'], 'term' => 'à esquerda'],
            ], [Kit::word('onde fica...?', 'onde'), Kit::word('a rua'), Kit::word('a esquina'), Kit::word('à esquerda')]),
            Kit::build($stage, 'task.build.mapa-direita', 'The map is on the right.', 'O mapa está à direita.', ['derecha', 'come'], [Kit::word('o mapa'), Kit::word('à direita')], 'write', ['derecha' => 'right (Spanish, not Portuguese)']),
            Kit::build($stage, 'task.build.comemos-rua', 'We eat near the street.', 'Comemos perto da rua.', ['partimos', 'comem'], [Kit::word('perto'), Kit::word('a rua', 'rua'), Kit::form('comemos', true)], 'write'),
            Kit::build($stage, 'task.build.partes-praca', 'You leave the square at three.', 'Partes da praça às três.', ['parto', 'comes'], [Kit::word('a praça', 'praça'), Kit::form('partes')], 'write'),
            Kit::translate($stage, 'task.translate.rua-longe', 'The street is far from the square, but the corner is near.', ['A rua fica longe da praça, mas a esquina fica perto.', 'A rua está longe da praça, mas a esquina está perto.', 'A rua fica longe da praça, mas a esquina está perto.', 'A rua está longe da praça, mas a esquina fica perto.'], [Kit::word('a rua'), Kit::word('a esquina'), Kit::word('a praça', 'praça'), Kit::word('longe'), Kit::word('perto')], 'write'),
            Kit::translate($stage, 'task.translate.mapa-onde', 'I have the map here, but where is the square?', ['Tenho o mapa aqui, mas onde fica a praça?', 'Tenho o mapa aqui, mas onde está a praça?', 'Eu tenho o mapa aqui, mas onde fica a praça?', 'Eu tenho o mapa aqui, mas onde está a praça?', 'Tenho aqui o mapa, mas onde fica a praça?', 'Tenho aqui o mapa, mas onde está a praça?', 'Eu tenho aqui o mapa, mas onde fica a praça?', 'Eu tenho aqui o mapa, mas onde está a praça?'], [Kit::word('o mapa'), Kit::word('onde fica...?', 'onde'), Kit::word('a praça')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.esquina', [
                Kit::line('Marta', 'Bom dia, João. Onde fica a esquina?'),
                Kit::line('João', 'A esquina fica perto. Sempre em frente e à esquerda.'),
                Kit::line('Marta', 'A praça fica longe?'),
                Kit::line('João', 'Não, a praça fica perto da rua. Eu como lá.'),
                Kit::line('Marta', 'Muito obrigada, João.'),
            ], [
                Kit::question('What does Marta ask about first?', ['The corner', 'The map', 'The street'], 'The corner'),
                Kit::question('Which way after going straight ahead?', ['To the right', 'To the left', 'Straight ahead again'], 'To the left'),
                Kit::question('Is the square far?', ['Yes', 'No', 'João does not say.'], 'No'),
            ], [
                Kit::question('What does João say about the corner?', ['It is near.', 'It is far.', 'He does not know.'], 'It is near.'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
                Kit::question('Who says thank you?', ['Marta', 'João', 'Both of them'], 'Marta'),
            ], [Kit::word('onde fica...?', 'onde fica'), Kit::word('a esquina'), Kit::word('perto'), Kit::word('sempre em frente'), Kit::word('à esquerda'), Kit::word('a praça'), Kit::word('longe'), Kit::word('a rua', 'rua')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.parto-rua', 'Eu parto da rua às duas.', 'I leave the street at two.', [Kit::word('a rua', 'rua'), Kit::form('parto')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.esquina-longe', 'A esquina fica longe da praça.', 'The corner is far from the square.', [Kit::word('a esquina'), Kit::word('longe'), Kit::word('a praça', 'praça'), Kit::form('fica')], 'listen', null, [], 'The a before esquina is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.comem-direita', 'Eles comem perto da praça, à direita.', 'They eat near the square, on the right.', [Kit::word('perto'), Kit::word('a praça', 'praça'), Kit::word('à direita'), Kit::form('comem')], 'listen', null, [], 'The à in à direita is a plus a (to the, on the), not the article a and not há (there is).'),

            Kit::speakAnswer($stage, 'task.speak_answer.onde-esquina', 'Onde fica a esquina?', 'Where is the corner?', [['esquina', 'fica', 'está'], ['direita', 'direito', 'esquerda', 'frente', 'perto', 'longe', 'aqui', 'ali', 'lá']], 'A esquina fica à esquerda.', [Kit::word('onde fica...?', 'onde'), Kit::word('a esquina'), Kit::word('à esquerda')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.perto-longe', 'A praça fica perto ou longe?', 'Is the square near or far?', [['perto', 'longe']], 'A praça fica perto.', [Kit::word('perto'), Kit::word('longe'), Kit::word('a praça')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.comes-rua', 'Comes na rua?', 'Do you eat in the street?', [['sim', 'não', 'como'], ['rua', 'praça']], 'Não, como na praça.', [Kit::word('a rua', 'rua'), Kit::word('a praça', 'praça'), Kit::form('como')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tens-mapa', 'Tens o mapa?', 'Do you have the map?', [['sim', 'não', 'tenho'], ['mapa']], 'Sim, tenho o mapa aqui.', [Kit::word('o mapa', 'mapa')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.frente-direita', 'Sempre em frente e à direita, perto da praça.', 'Straight ahead and to the right, near the square.', [Kit::word('sempre em frente'), Kit::word('à direita'), Kit::word('perto'), Kit::word('a praça', 'praça')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.partimos-comemos', 'Nós partimos da esquina e comemos na rua.', 'We leave the corner and eat in the street.', [Kit::word('a esquina', 'esquina'), Kit::word('a rua', 'rua'), Kit::form('partimos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.partimos-esquina', 'We leave the corner at three.', ['Partimos da esquina às três.', 'Nós partimos da esquina às três.'], [Kit::word('a esquina', 'esquina'), Kit::form('partimos', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.onde-rua', 'Where is the street? It is far.', ['Onde fica a rua? Fica longe.', 'Onde está a rua? Está longe.', 'Onde fica a rua? Está longe.', 'Onde está a rua? Fica longe.'], [Kit::word('onde fica...?', 'onde'), Kit::word('a rua'), Kit::word('longe')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-come', 'Ana eats near the square, on the right.', ['A Ana come perto da praça, à direita.', 'Ana come perto da praça, à direita.'], [Kit::word('perto'), Kit::word('a praça', 'praça'), Kit::word('à direita'), Kit::form('come')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mapa-frente', 'I have the map, but the square is straight ahead.', ['Tenho o mapa, mas a praça fica sempre em frente.', 'Tenho o mapa, mas a praça está sempre em frente.'], [Kit::word('o mapa'), Kit::word('a praça'), Kit::word('sempre em frente')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vocês', 'Vocês ___ perto da esquina.', 'You (all) eat near the corner.', 'comem', Kit::form('comem'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.tu-partes', 'Tu ___ da rua às duas.', 'You leave the street at two.', 'partes', Kit::form('partes', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.partem-esquerda', 'Eles partem da praça, à esquerda.', 'They leave the square, on the left.', [Kit::word('a praça', 'praça'), Kit::word('à esquerda'), Kit::form('partem')], 'dictation', $set, [], 'The à in à esquerda is a plus a (to the, on the), not the article a and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.esquina-direita', 'A esquina fica longe, à direita.', 'The corner is far, on the right.', [Kit::word('a esquina'), Kit::word('longe'), Kit::word('à direita')], 'dictation', $set, [], 'The first a is the article a (the). The à in à direita is a plus a (to the, on the). Neither is há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.comemos-longe', 'Nós comemos longe da rua.', 'We eat far from the street.', [Kit::word('longe'), Kit::word('a rua', 'rua'), Kit::form('comemos')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.rui', [
                Kit::line('Rui', 'Ana, onde fica a praça?'),
                Kit::line('Ana', 'A praça fica muito longe. E tu, tens o mapa?'),
                Kit::line('Rui', 'Tenho o mapa aqui.'),
                Kit::line('Ana', 'Sempre em frente e à direita.'),
            ], [
                Kit::question('What does Rui ask about?', ['The square', 'The map', 'The corner'], 'The square'),
                Kit::question('Is the square near?', ['Yes', 'No', 'Ana does not say.'], 'No'),
                Kit::question('Which way does Ana say to go?', ['Straight ahead and to the right', 'Straight ahead and to the left', 'Only to the left'], 'Straight ahead and to the right'),
            ], [
                Kit::question('Who has the map?', ['Rui', 'Ana', 'Nobody'], 'Rui'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
                Kit::question('Who asks about the map?', ['Ana', 'Rui', 'Marta'], 'Ana'),
            ], [Kit::word('onde fica...?', 'onde fica'), Kit::word('a praça'), Kit::word('longe'), Kit::word('o mapa'), Kit::word('sempre em frente'), Kit::word('à direita')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.marta', 'Read the conversation.', [
                Kit::line('Marta', 'Boa tarde, João. Tens aqui o mapa?'),
                Kit::line('João', 'Tenho. A rua fica à esquerda.'),
                Kit::line('Marta', 'E a esquina fica perto?'),
                Kit::line('João', 'Sim, fica perto. A praça fica muito longe.'),
            ], [
                Kit::question('Does João have the map?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Is the corner near?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
            ], [Kit::word('o mapa'), Kit::word('a rua'), Kit::word('à esquerda'), Kit::word('a esquina'), Kit::word('perto'), Kit::word('a praça'), Kit::word('longe')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.direita-esquerda', 'A praça fica à direita ou à esquerda?', 'Is the square on the right or on the left?', [['direita', 'esquerda']], 'A praça fica à direita.', [Kit::word('à direita'), Kit::word('à esquerda'), Kit::word('a praça')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.comes-esquina', 'Comes perto da esquina?', 'Do you eat near the corner?', [['sim', 'não', 'como'], ['perto', 'longe', 'esquina']], 'Sim, como perto da esquina.', [Kit::word('perto'), Kit::word('a esquina', 'esquina')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.mapa-praca', 'Tens o mapa da praça?', 'Do you have the map of the square?', [['sim', 'não', 'tenho'], ['mapa', 'praça']], 'Sim, tenho o mapa da praça.', [Kit::word('o mapa', 'mapa'), Kit::word('a praça', 'praça')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.comes-longe', 'You eat far from the corner.', ['Comes longe da esquina.', 'Tu comes longe da esquina.'], [Kit::word('longe'), Kit::word('a esquina', 'esquina'), Kit::form('comes')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.mapa-esquerda', 'The map is on the left, near the street.', ['O mapa está à esquerda, perto da rua.'], [Kit::word('o mapa'), Kit::word('à esquerda'), Kit::word('perto'), Kit::word('a rua', 'rua')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.onde-esquina', 'Where is the corner? It is straight ahead.', ['Onde fica a esquina? Fica sempre em frente.', 'Onde está a esquina? Está sempre em frente.', 'Onde fica a esquina? Está sempre em frente.', 'Onde está a esquina? Fica sempre em frente.'], [Kit::word('onde fica...?', 'onde'), Kit::word('a esquina'), Kit::word('sempre em frente')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.partem-praca', 'They leave the square at two.', ['Eles partem da praça às duas.', 'Elas partem da praça às duas.', 'Partem da praça às duas.'], [Kit::word('a praça', 'praça'), Kit::form('partem', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.partimos', 'Nós ___ da rua.', 'We leave the street.', 'partimos', Kit::form('partimos', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-come', 'A Ana ___ longe da praça.', 'Ana eats far from the square.', 'come', Kit::form('come'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.como-praca', 'Eu como na praça.', 'I eat in the square.', [Kit::word('a praça', 'praça'), Kit::form('como')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.mapa-direita', 'O mapa está à direita da rua.', 'The map is on the right of the street.', [Kit::word('o mapa'), Kit::word('à direita'), Kit::word('a rua', 'rua')], 'dictation', $set, [], 'The à in à direita is a plus a (to the, on the), not the article a and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.esquina-frente', 'A esquina fica perto, sempre em frente.', 'The corner is near, straight ahead.', [Kit::word('a esquina'), Kit::word('perto'), Kit::word('sempre em frente'), Kit::form('fica', true)], 'dictation', $set, [], 'The a before esquina is the article a (the), not à (to the) and not há (there is).'),
        ];
    }
}
