<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class CheckingIntoAHotel implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'checking-into-a-hotel';
    }

    public function words(): array
    {
        return [
            new WordData('o hotel', cue: 'hotel'),
            new WordData('o quarto', cue: 'room (in a hotel)', portunolSlips: ['habitación', 'cuarto'], questions: ['Is "o quarto" the word a Portuguese hotel receptionist expects for a hotel room, and is it right that no other word is accepted for it?', 'Is "O meu quarto é o três." a natural way to say which room is yours, or would people say "o número três" or "o quarto número três"?']),
            new WordData('a reserva', cue: 'reservation (booking)'),
            new WordData('a chave', cue: 'key (for your room)', portunolSlips: ['llave'], questions: ['At a Portuguese reception desk, is "Aqui tem a chave." as natural as "Aqui está a chave." when handing over the key?']),
            new WordData('o rececionista', cue: 'receptionist (at the hotel desk)', commonGender: true, portunolSlips: ['recepcionista'], questions: ['Is the 1990 agreement spelling "rececionista" the right one to teach, and are "o rececionista" and "a rececionista" both natural for a man and a woman at the desk?']),
            new WordData('disponível', cue: 'available', portunolSlips: ['disponible'], questions: ['Is "Tem um quarto disponível para duas noites?" the natural way for a guest to ask for a room at a Portuguese hotel desk, and is "Há um quarto disponível." fine as a statement?']),
            new WordData('a noite', cue: 'night', forms: ['noites'], portunolSlips: ['noche']),
            new WordData('a casa de banho', cue: 'bathroom (in a hotel room)', portunolSlips: ['baño'], note: 'A casa de banho is the bathroom. The word o banho alone means a bath, and Spanish baño is the trap, so say casa de banho.', questions: ['Is "a casa de banho" right for the bathroom in a hotel room, and is it correct that no shorter form such as "o WC" is accepted?']),
            new WordData('incluído', cue: 'included (masculine)', forms: ['incluída']),
            new WordData('o pequeno-almoço', cue: 'breakfast', portunolSlips: ['desayuno'], questions: ['Is "O pequeno-almoço é às sete." natural with ser for the time an event takes place, and would "está" be wrong there?']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'O hotel está aqui.', 'english' => 'The hotel is here.'],
            ['text' => 'O quarto é grande e está disponível.', 'english' => 'The room is big and it is available.'],
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
        $recepcao = 'The a at the start is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::gap($stage, 'sentences.choose_gap.hotel-aqui', 'O hotel ___ aqui.', ['está', 'estão', 'são'], 'está', Kit::form('está'), 'Where something is takes estar.', 'choose', 'The hotel is here.'),
            Kit::gap($stage, 'sentences.choose_gap.quarto-tres', 'O meu quarto ___ o três.', ['é', 'está', 'tem'], 'é', Kit::form('é', true), 'Which room it is, what it is, takes ser. Estar says where or how something is now.', 'choose', 'My room is number three.'),
            Kit::gap($stage, 'sentences.choose_gap.quartos-disponiveis', 'Os quartos ___ disponíveis.', ['estão', 'está', 'estamos'], 'estão', Kit::form('estão'), 'Available is a state that can change, so it takes estar. More than one thing takes estão.', 'choose', 'The rooms are available.'),
            Kit::gap($stage, 'sentences.choose_gap.pequeno-almoco-incluido', 'O pequeno-almoço ___ incluído.', ['está', 'estão', 'são'], 'está', Kit::form('está'), 'Included is a state that can change, so it takes estar.', 'choose', 'Breakfast is included.'),
            Kit::gap($stage, 'sentences.choose_gap.chave-quarto', 'A ___ está no quarto.', ['chave', 'hotel', 'quarto'], 'chave', Kit::word('a chave', 'chave'), 'Chave is feminine, so it goes with a. Hotel and quarto are masculine and take o.', 'choose', 'The key is in the room.'),
            Kit::gap($stage, 'sentences.choose_gap.casa-de-banho', 'Onde está a casa de ___?', ['banho', 'quarto', 'noite'], 'banho', Kit::word('a casa de banho', 'banho'), 'The bathroom is a casa de banho. Banho alone means a bath.', 'choose', 'Where is the bathroom?'),

            Kit::typeGap($stage, 'sentences.type_gap.estamos', '___ no hotel.', 'We are in the hotel.', 'Estamos', Kit::form('estamos'), 'We are, about where we are, is estamos.'),
            Kit::typeGap($stage, 'sentences.type_gap.estou', 'Eu ___ na casa de banho.', 'I am in the bathroom.', 'estou', Kit::form('estou'), 'I am, about where I am, is estou.'),
            Kit::typeGap($stage, 'sentences.type_gap.rececionista', 'O rececionista ___ no hotel.', 'The receptionist is in the hotel.', 'está', Kit::form('está'), 'Where someone is takes estar.'),
            Kit::typeGap($stage, 'sentences.type_gap.eles', 'Eles ___ no quarto.', 'They are in the room.', 'estão', Kit::form('estão'), 'More than one person, they, takes estão, with the nasal ão of não.'),
            Kit::typeGap($stage, 'sentences.type_gap.sou', 'Eu ___ o rececionista.', 'I am the receptionist.', 'sou', Kit::form('sou', true), 'Saying who you are takes ser, not estar.'),

            Kit::translate($stage, 'sentences.translate.pequeno-almoco', 'The breakfast is included.', ['O pequeno-almoço está incluído.', 'Está incluído o pequeno-almoço.'], [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::form('está')]),
            Kit::translate($stage, 'sentences.translate.quarto', 'The room is available.', ['O quarto está disponível.'], [Kit::word('o quarto'), Kit::word('disponível'), Kit::form('está')]),
            Kit::translate($stage, 'sentences.translate.casa-de-banho', 'The bathroom is here.', ['A casa de banho está aqui.', 'Está aqui a casa de banho.', 'Aqui está a casa de banho.'], [Kit::word('a casa de banho'), Kit::form('está')]),

            Kit::build($stage, 'sentences.build.chave', 'The key is in the room.', 'A chave está no quarto.', ['estão'], [Kit::word('a chave'), Kit::word('o quarto', 'quarto'), Kit::form('está')]),
            Kit::build($stage, 'sentences.build.ha-quarto', 'There is a room available.', 'Há um quarto disponível.', ['estão'], [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::form('há', true)]),
            Kit::build($stage, 'sentences.build.banho-quarto', 'The bathroom is in the room.', 'A casa de banho está no quarto.', ['estão'], [Kit::word('a casa de banho'), Kit::word('o quarto', 'quarto'), Kit::form('está')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.chave', 'A chave está aqui.', ['The key is here.', 'The room is here.', 'The hotel is here.', 'The bathroom is here.'], 'The key is here.', [Kit::word('a chave'), Kit::form('está')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.ha-quarto', 'Há um quarto disponível.', ['There is a room available.', 'The room is not available.', 'There is a bathroom in the room.', 'The reservation is for one night.'], 'There is a room available.', [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::form('há', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pequeno-almoco', 'O pequeno-almoço está incluído.', ['Breakfast is included.', 'Breakfast is here.', 'The bathroom is included.', 'The key is included.'], 'Breakfast is included.', [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::form('está')]),
            Kit::listenType($stage, 'sentences.listen_type.reserva', 'A reserva é para duas noites.', 'The reservation is for two nights.', [Kit::word('a reserva'), Kit::word('a noite', 'noites'), Kit::form('é', true)], homophoneNote: $recepcao),
            Kit::listenType($stage, 'sentences.listen_type.rececionista', 'Sou o rececionista.', 'I am the receptionist.', [Kit::word('o rececionista', 'rececionista'), Kit::form('sou', true)], 'listen', null, ['Sou a rececionista.']),
            Kit::listenType($stage, 'sentences.listen_type.pequeno-almoco-sete', 'O pequeno-almoço é às sete.', 'Breakfast is at seven.', [Kit::word('o pequeno-almoço'), Kit::form('é', true)]),
            Kit::listenType($stage, 'sentences.listen_type.quartos', 'Os quartos estão disponíveis.', 'The rooms are available.', [Kit::word('o quarto', 'quartos'), Kit::word('disponível', 'disponíveis'), Kit::form('estão')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.quarto-noites', 'Tem um quarto disponível para duas noites?', 'Do you have a room available for two nights?', [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::word('a noite', 'noites')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.chave', 'A chave está no quarto.', 'The key is in the room.', [Kit::word('a chave'), Kit::word('o quarto', 'quarto'), Kit::form('está')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.rececionista', 'Bom dia, sou o rececionista do hotel.', 'Good morning, I am the hotel receptionist.', [Kit::word('o rececionista', 'rececionista'), Kit::word('o hotel', 'hotel'), Kit::form('sou', true)], 'speak', ['Bom dia, sou a rececionista do hotel.']),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.casa-de-banho', 'Estou na casa de banho.', 'I am in the bathroom.', [Kit::word('a casa de banho', 'casa de banho'), Kit::form('estou')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.reserva', 'Tem uma reserva?', 'Do you have a reservation?', [['tenho', 'tem', 'temos'], ['reserva']], 'Sim, tenho uma reserva.', [Kit::word('a reserva')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.casa-de-banho', 'Onde está a casa de banho?', 'Where is the bathroom?', [['banho', 'casa', 'está'], ['aqui', 'ali', 'quarto']], 'A casa de banho está aqui.', [Kit::word('a casa de banho'), Kit::form('está')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pequeno-almoco', 'Há pequeno-almoço no hotel?', 'Is there breakfast in the hotel?', [['sim', 'não', 'há'], ['almoço', 'pequeno', 'hotel']], 'Sim, há pequeno-almoço no hotel.', [Kit::word('o pequeno-almoço'), Kit::word('o hotel')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $chave = 'The a before chave is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::readPassage($stage, 'task.read_passage.recepcao', 'Read the conversation at the reception desk.', [
                Kit::line('Rececionista', 'Boa tarde. Sou o rececionista. Tem uma reserva?'),
                Kit::line('Ana', 'Sim, tenho uma reserva para duas noites.'),
                Kit::line('Rececionista', 'Muito bem. O quarto está disponível. Aqui está a chave.'),
                Kit::line('Ana', 'E o pequeno-almoço?'),
                Kit::line('Rececionista', 'O pequeno-almoço está incluído.'),
                Kit::line('Ana', 'Obrigada. Onde está a casa de banho?'),
                Kit::line('Rececionista', 'A casa de banho está no quarto.'),
            ], [
                Kit::question('How many nights is the reservation for?', ['one', 'two', 'three'], 'two'),
                Kit::question('Is the room available?', ['No, it is not available.', 'Yes, it is available.', 'The text does not say.'], 'Yes, it is available.'),
                Kit::question('Where is the bathroom?', ['In the room.', 'Next to the reception desk.', 'The text does not say.'], 'In the room.'),
            ], [Kit::word('a reserva'), Kit::word('a noite'), Kit::word('o rececionista'), Kit::word('o quarto'), Kit::word('disponível'), Kit::word('a chave'), Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::word('a casa de banho')]),
            Kit::gap($stage, 'task.choose_gap.quartos', 'Os quartos estão ___.', ['disponíveis', 'disponível'], 'disponíveis', Kit::word('disponível', 'disponíveis'), 'The adjective agrees with the noun: more than one room, so disponíveis.', 'read'),
            Kit::gap($stage, 'task.choose_gap.pequeno-almoco', 'O pequeno-almoço está ___.', ['incluída', 'incluído'], 'incluído', Kit::word('incluído'), 'Pequeno-almoço is masculine, so the adjective is incluído.', 'read'),

            Kit::transform($stage, 'task.transform.quarto', 'Make it plural.', 'O quarto está disponível.', ['Os quartos estão disponíveis.'], [Kit::word('o quarto', 'os quartos'), Kit::word('disponível', 'disponíveis'), Kit::form('estão')]),
            Kit::transform($stage, 'task.transform.hotel', 'Make it plural.', 'O hotel está aqui.', ['Os hotéis estão aqui.'], [Kit::word('o hotel', 'os hotéis'), Kit::form('estão')]),
            Kit::transform($stage, 'task.transform.pergunta', 'Make it a question.', 'O pequeno-almoço está incluído.', ['O pequeno-almoço está incluído?', 'Está incluído o pequeno-almoço?', 'Está o pequeno-almoço incluído?'], [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::form('está')]),
            Kit::writeGuided($stage, 'task.write_guided.quarto', 'Ask for a room for two nights and ask whether breakfast is included.', ['quarto', 'noites', 'pequeno-almoço', 'incluído'], 'Tem um quarto disponível para duas noites? O pequeno-almoço está incluído?', [
                ['forms' => ['quarto', 'quartos'], 'term' => 'o quarto'],
                ['forms' => ['noites'], 'term' => 'a noite'],
                ['forms' => ['almoço'], 'term' => 'o pequeno-almoço'],
                ['forms' => ['incluído'], 'term' => 'incluído'],
            ], [Kit::word('o quarto'), Kit::word('a noite'), Kit::word('o pequeno-almoço'), Kit::word('incluído')]),
            Kit::writeGuided($stage, 'task.write_guided.reserva', 'Say that you have a reservation and ask where the bathroom is.', ['reserva', 'onde', 'casa de banho'], 'Tenho uma reserva. Onde está a casa de banho?', [
                ['forms' => ['reserva'], 'term' => 'a reserva'],
                ['forms' => ['onde'], 'term' => null],
                ['forms' => ['banho'], 'term' => 'a casa de banho'],
            ], [Kit::word('a reserva'), Kit::word('a casa de banho')]),
            Kit::build($stage, 'task.build.chaves', 'The keys are in the room.', 'As chaves estão no quarto.', ['são', 'está'], [Kit::word('a chave', 'chaves'), Kit::word('o quarto', 'quarto'), Kit::form('estão')], 'write'),
            Kit::build($stage, 'task.build.estamos', 'We are in the hotel, in room three.', 'Estamos no hotel, no quarto três.', ['somos', 'estou'], [Kit::word('o hotel', 'hotel'), Kit::word('o quarto', 'quarto'), Kit::form('estamos')], 'write'),
            Kit::build($stage, 'task.build.nao-ha', 'There are no rooms available.', 'Não há quartos disponíveis.', ['está', 'estão'], [Kit::word('o quarto', 'quartos'), Kit::word('disponível', 'disponíveis'), Kit::form('há', true)], 'write'),
            Kit::translate($stage, 'task.translate.reserva', 'Do you have a reservation?', ['Tem uma reserva?', 'Tens uma reserva?', 'Tem reserva?', 'Tens reserva?', 'O senhor tem uma reserva?', 'A senhora tem uma reserva?', 'Têm uma reserva?'], [Kit::word('a reserva', 'reserva')], 'write'),
            Kit::translate($stage, 'task.translate.tres-noites', 'The room is available for three nights.', ['O quarto está disponível para três noites.', 'O quarto está disponível por três noites.'], [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::word('a noite', 'noites'), Kit::form('está')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.recepcao', [
                Kit::line('Rececionista', 'Boa noite. O seu quarto é o quatro.'),
                Kit::line('Ana', 'Onde está a casa de banho?'),
                Kit::line('Rececionista', 'Está no quarto. Aqui tem a chave.'),
                Kit::line('Ana', 'E o pequeno-almoço?'),
                Kit::line('Rececionista', 'Não, o pequeno-almoço não está incluído.'),
            ], [
                Kit::question('Which room is it?', ['three', 'four', 'five'], 'four'),
                Kit::question('Where is the bathroom?', ['In the room.', 'Next to the reception desk.', 'The conversation does not say.'], 'In the room.'),
                Kit::question('Is breakfast included?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Evening'], 'Evening'),
                Kit::question('What does the receptionist give Ana?', ['The key', 'The bill', 'The breakfast'], 'The key'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('a noite'), Kit::word('o quarto'), Kit::word('a casa de banho'), Kit::word('a chave'), Kit::word('o pequeno-almoço'), Kit::word('incluído')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.reserva', 'Boa tarde, tenho uma reserva.', 'Good afternoon, I have a reservation.', [Kit::word('a reserva', 'reserva')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.noites', 'O quarto está disponível para duas noites.', 'The room is available for two nights.', [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::word('a noite', 'noites'), Kit::form('está')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.chave', 'Onde está a chave?', 'Where is the key?', [Kit::word('a chave'), Kit::form('está')], 'listen', homophoneNote: $chave),

            Kit::speakAnswer($stage, 'task.speak_answer.noites', 'Para quantas noites?', 'For how many nights?', [['uma', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete']], 'Para duas noites.', [Kit::word('a noite', 'noites')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pequeno-almoco', 'O pequeno-almoço está incluído?', 'Is breakfast included?', [['sim', 'não', 'está'], ['incluído']], 'Sim, está incluído.', [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::form('está')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chave', 'Onde está a chave?', 'Where is the key?', [['chave', 'está'], ['aqui', 'ali', 'quarto']], 'A chave está aqui.', [Kit::word('a chave'), Kit::form('está')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.casa-de-banho', 'Há uma casa de banho no quarto?', 'Is there a bathroom in the room?', [['sim', 'não', 'há', 'está'], ['banho', 'casa']], 'Sim, há uma casa de banho no quarto.', [Kit::word('a casa de banho'), Kit::word('o quarto'), Kit::form('há', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.hotel', 'Boa noite, estamos no hotel.', 'Good evening, we are in the hotel.', [Kit::word('o hotel', 'hotel'), Kit::form('estamos')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.rececionista', 'Boa tarde, sou o rececionista.', 'Good afternoon, I am the receptionist.', [Kit::word('o rececionista', 'rececionista')], 'speak', ['Boa tarde, sou a rececionista.']),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.chave-hotel', 'The key is in the hotel.', ['A chave está no hotel.'], [Kit::word('a chave'), Kit::word('o hotel', 'hotel'), Kit::form('está')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ha-quartos', 'There are rooms available for three nights.', ['Há quartos disponíveis para três noites.'], [Kit::word('o quarto', 'quartos'), Kit::word('disponível', 'disponíveis'), Kit::word('a noite', 'noites'), Kit::form('há', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.rececionista-chave', 'The receptionist has the key.', ['O rececionista tem a chave.', 'A rececionista tem a chave.'], [Kit::word('o rececionista', 'rececionista'), Kit::word('a chave')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.reserva-noite', 'I have a reservation for one night.', ['Tenho uma reserva para uma noite.', 'Eu tenho uma reserva para uma noite.'], [Kit::word('a reserva', 'reserva'), Kit::word('a noite', 'noite')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.eles', 'Eles ___ no hotel.', 'They are in the hotel.', 'estão', Kit::form('estão'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.marta', 'A Marta ___ a rececionista.', 'Marta is the receptionist.', 'é', Kit::form('é', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.pequeno-almoco', 'O pequeno-almoço não está incluído.', 'Breakfast is not included.', [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::form('está')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.estou', 'Estou no quarto.', 'I am in the room.', [Kit::word('o quarto', 'quarto'), Kit::form('estou')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.casa-de-banho', 'A casa de banho não está aqui.', 'The bathroom is not here.', [Kit::word('a casa de banho')], 'dictation', $set, homophoneNote: 'The a at the start is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.recepcao', [
                Kit::line('Rececionista', 'Bom dia. O seu quarto é o seis.'),
                Kit::line('Ana', 'Obrigada. Há pequeno-almoço?'),
                Kit::line('Rececionista', 'Sim, o pequeno-almoço é às oito.'),
            ], [
                Kit::question('Which room is it?', ['four', 'five', 'six'], 'six'),
                Kit::question('What does Ana ask about?', ['The breakfast', 'The key', 'The bathroom'], 'The breakfast'),
                Kit::question('At what time is breakfast?', ['Seven', 'Eight', 'Nine'], 'Eight'),
            ], [
                Kit::question('What does the receptionist say first?', ['Good morning', 'Good afternoon', 'Good evening'], 'Good morning'),
                Kit::question('Is there breakfast?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who asks about breakfast?', ['Ana', 'The receptionist', 'Nobody'], 'Ana'),
            ], [Kit::word('o quarto'), Kit::word('o pequeno-almoço')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.reserva', 'Read the conversation.', [
                Kit::line('Rececionista', 'Boa noite. A sua reserva é para três noites.'),
                Kit::line('Ana', 'Muito bem. E há pequeno-almoço?'),
                Kit::line('Rececionista', 'Não, não está incluído.'),
            ], [
                Kit::question('How many nights is the reservation for?', ['two', 'three', 'four'], 'three'),
                Kit::question('Is breakfast included?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('a reserva'), Kit::word('a noite'), Kit::word('o pequeno-almoço'), Kit::word('incluído')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.noites', 'Para quantas noites é a reserva?', 'For how many nights is the reservation?', [['uma', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete'], ['noite', 'noites']], 'A reserva é para duas noites.', [Kit::word('a reserva'), Kit::word('a noite', 'noites')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quarto', 'Tem um quarto disponível?', 'Do you have a room available?', [['sim', 'não', 'tenho', 'tem', 'temos'], ['quarto', 'disponível']], 'Sim, tenho um quarto disponível.', [Kit::word('o quarto'), Kit::word('disponível')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.rececionista', 'Quem é o rececionista?', 'Who is the receptionist?', [['é', 'sou'], ['rececionista', 'joão', 'rui', 'ana', 'marta']], 'O rececionista é o João.', [Kit::word('o rececionista')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.quarto', 'The room is not available.', ['O quarto não está disponível.'], [Kit::word('o quarto', 'quarto'), Kit::word('disponível'), Kit::form('está')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.rececionista', 'The receptionist is not here.', ['O rececionista não está aqui.', 'A rececionista não está aqui.'], [Kit::word('o rececionista', 'rececionista'), Kit::form('está')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.chave', 'I have the key to the room.', ['Tenho a chave do quarto.', 'Eu tenho a chave do quarto.'], [Kit::word('a chave'), Kit::word('o quarto', 'quarto')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.reserva', 'Do you have a reservation for two nights?', ['Tem uma reserva para duas noites?', 'Tens uma reserva para duas noites?', 'Tem reserva para duas noites?', 'Tens reserva para duas noites?', 'O senhor tem uma reserva para duas noites?', 'A senhora tem uma reserva para duas noites?', 'Têm uma reserva para duas noites?'], [Kit::word('a reserva', 'reserva'), Kit::word('a noite', 'noites')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estamos', '___ no quarto.', 'We are in the room.', 'Estamos', Kit::form('estamos'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.joao', 'O João ___ o rececionista.', 'João is the receptionist.', 'é', Kit::form('é', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.ha-chave', 'Há uma chave no quarto.', 'There is a key in the room.', [Kit::word('a chave', 'chave'), Kit::word('o quarto', 'quarto'), Kit::form('há', true)], 'dictation', $set, homophoneNote: 'The first word is há (there is), not the article a (the) and not à (to the): the sentence says that something exists.'),
            Kit::listenType($stage, 'check.b.listen_type.casa-de-banho', 'A casa de banho está no hotel.', 'The bathroom is in the hotel.', [Kit::word('a casa de banho'), Kit::word('o hotel', 'hotel'), Kit::form('está')], 'dictation', $set, homophoneNote: 'The first a is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.incluido-reserva', 'O pequeno-almoço está incluído na reserva.', 'Breakfast is included in the reservation.', [Kit::word('o pequeno-almoço'), Kit::word('incluído'), Kit::word('a reserva', 'reserva')], 'dictation', $set),
        ];
    }
}
