<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('olá', cue: 'hello', questions: ['Is "olá" the right everyday greeting for a stranger in Portugal, and should the informal "oi" be accepted as well or kept out?']),
            new WordData('bom dia', cue: 'good morning'),
            new WordData('boa tarde', cue: 'good afternoon'),
            new WordData('boa noite', cue: 'good evening or good night (greeting after dark)'),
            new WordData('adeus', cue: 'goodbye', questions: ['Is "adeus" natural as a plain goodbye in Portugal, or would most people say "até logo", "até amanhã" or "tchau"? Should "tchau" be accepted?']),
            new WordData('chamo-me', cue: 'my name is (introducing yourself)', accepted: ['eu chamo-me', 'o meu nome é'], portunolSlips: ['me llamo']),
            new WordData('muito prazer', cue: 'nice to meet you', accepted: ['prazer', 'muito gosto', 'muito prazer em conhecer-te', 'prazer em conhecer-te'], questions: ['Are "prazer", "muito gosto" and the "em conhecer-te" forms all natural answers for "nice to meet you" in Portugal?']),
            new WordData('como estás?', cue: 'how are you? (to a friend)', accepted: ['como está?', 'como estás tu?', 'tudo bem?'], questions: ['Is "tudo bem?" a fair accepted answer for "how are you?", and is "como está?" right for the polite form (o senhor, a senhora)?']),
            new WordData('bem', cue: 'well, fine (as in I am fine)', accepted: ['estou bem']),
            new WordData('obrigado', cue: 'thank you', accepted: ['obrigada', 'muito obrigado', 'muito obrigada'], portunolSlips: ['gracias'], note: 'A man says obrigado and a woman says obrigada, because the word agrees with the speaker. Both are accepted when you type it from the English cue.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Sou a Ana.', 'english' => 'I am Ana.'],
            ['text' => 'Ela é a minha amiga.', 'english' => 'She is my friend.'],
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
            Kit::gap($stage, 'sentences.choose_gap.ola-sou', 'Olá, ___ a Ana.', ['sou', 'estou', 'somos'], 'sou', Kit::form('sou'), 'Saying who you are takes ser: I am is sou.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.como-senhor', 'Como ___ o senhor?', ['está', 'é', 'são'], 'está', Kit::form('está', true), 'How someone is, a state, takes estar. Ser says who someone is.', 'choose', 'How are you? (formal)'),
            Kit::gap($stage, 'sentences.choose_gap.eu-bem', 'Eu ___ bem.', ['estou', 'sou', 'é'], 'estou', Kit::form('estou', true), 'How you are, a state, takes estar, not ser.', 'choose', 'I am fine.'),
            Kit::gap($stage, 'sentences.choose_gap.ela-marta', 'Ela ___ a Marta.', ['é', 'sou', 'somos'], 'é', Kit::form('é'), 'He, she and o senhor take é.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.nos-joao-rui', 'Nós ___ o João e o Rui.', ['somos', 'são', 'sou'], 'somos', Kit::form('somos'), 'We takes somos.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.boa-noite', 'Boa ___, Marta.', ['noite', 'dia', 'tarde'], 'noite', Kit::word('boa noite', 'noite'), 'Boa is feminine, so it goes with noite or tarde, and good night is boa noite.', 'choose', 'Good night, Marta.'),

            Kit::typeGap($stage, 'sentences.type_gap.estudante', 'Eu ___ estudante.', 'I am a student.', 'sou', Kit::form('sou'), 'Saying who you are takes ser: I am is sou.', glosses: ['estudante' => 'student']),
            Kit::typeGap($stage, 'sentences.type_gap.como-estas', 'Olá, Ana. Como ___?', 'Hi, Ana. How are you?', 'estás', Kit::form('estás', true), 'How you are, a state, takes estar. És says who you are.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-rui', 'Tu ___ o Rui.', 'You are Rui.', 'és', Kit::form('és'), 'You, to a friend, takes és.'),
            Kit::typeGap($stage, 'sentences.type_gap.eles', 'Eles ___ a Ana e o Rui.', 'They are Ana and Rui.', 'são', Kit::form('são'), 'More than one person, they, takes são, with the nasal ão of não.'),
            Kit::typeGap($stage, 'sentences.type_gap.bom-dia', 'Bom ___, Rui.', 'Good morning, Rui.', 'dia', Kit::word('bom dia', 'dia')),
            Kit::translate($stage, 'sentences.translate.ola-sou', 'Hello, I am Ana.', ['Olá, sou a Ana.', 'Olá, eu sou a Ana.', 'Olá, sou Ana.', 'Olá, eu sou Ana.'], [Kit::word('olá'), Kit::form('sou')]),
            Kit::translate($stage, 'sentences.translate.chamo-me', 'My name is Rui. Nice to meet you.', ['Chamo-me Rui. Muito prazer.', 'Chamo-me Rui, muito prazer.', 'Eu chamo-me Rui. Muito prazer.'], [Kit::word('chamo-me'), Kit::word('muito prazer')]),
            Kit::translate($stage, 'sentences.translate.bem-obrigada', 'I am fine, thank you. (A woman speaking.)', ['Estou bem, obrigada.', 'Estou bem. Obrigada.', 'Estou bem, muito obrigada.', 'Eu estou bem, obrigada.', 'Estou muito bem, obrigada.'], [Kit::word('bem'), Kit::word('obrigado', 'obrigada'), Kit::form('estou', true)]),
            Kit::build($stage, 'sentences.build.boa-tarde', 'Good afternoon, I am Ana.', 'Boa tarde, sou a Ana.', ['estou'], [Kit::word('boa tarde'), Kit::form('sou')]),
            Kit::build($stage, 'sentences.build.ola-joao', 'Hello, he is João.', 'Olá, ele é o João.', ['sou'], [Kit::word('olá'), Kit::form('é')]),
            Kit::build($stage, 'sentences.build.somos', 'We are João and Rui.', 'Somos o João e o Rui.', ['são'], [Kit::form('somos')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.boa-noite', 'Boa noite, sou a Ana.', ['Good morning, I am Ana.', 'Good night, I am Ana.', 'Good afternoon, I am Ana.', 'Goodbye, I am Ana.'], 'Good night, I am Ana.', [Kit::word('boa noite'), Kit::form('sou')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.muito-prazer', 'Muito prazer, João.', ['Nice to meet you, João.', 'Thank you, João.', 'Goodbye, João.', 'Hello, João.'], 'Nice to meet you, João.', [Kit::word('muito prazer')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.adeus', 'Adeus, Marta.', ['Hello, Marta.', 'Goodbye, Marta.', 'Thank you, Marta.', 'Nice to meet you, Marta.'], 'Goodbye, Marta.', [Kit::word('adeus')]),
            Kit::listenType($stage, 'sentences.listen_type.bom-dia', 'Bom dia, chamo-me Rui.', 'Good morning, my name is Rui.', [Kit::word('bom dia'), Kit::word('chamo-me')]),
            Kit::listenType($stage, 'sentences.listen_type.como-estas', 'Como estás, Ana?', 'How are you, Ana?', [Kit::word('como estás?', 'como estás')]),
            Kit::listenType($stage, 'sentences.listen_type.ola-marta', 'Olá, sou a Marta.', 'Hello, I am Marta.', [Kit::word('olá'), Kit::form('sou')], homophoneNote: 'The a before the name is the article a, not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.tarde', 'Boa tarde, Ana. Como estás?', 'Good afternoon, Ana. How are you?', [Kit::word('boa tarde'), Kit::word('como estás?', 'como estás')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ola-chamo-me', 'Olá, chamo-me Ana. Muito prazer.', 'Hello, my name is Ana. Nice to meet you.', [Kit::word('olá'), Kit::word('chamo-me'), Kit::word('muito prazer')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.adeus', 'Adeus, Marta. Obrigado.', 'Goodbye, Marta. Thank you.', [Kit::word('adeus'), Kit::word('obrigado')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bom-dia', 'Bom dia, sou o João.', 'Good morning, I am João.', [Kit::word('bom dia'), Kit::form('sou')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.como-estas', 'Como estás, Ana? Estou bem.', 'How are you, Ana? I am fine.', [Kit::word('como estás?', 'como estás'), Kit::word('bem'), Kit::form('estou', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-estas', 'Como estás?', 'How are you?', [['estou', 'bem'], ['bem', 'obrigado', 'obrigada', 'muito']], 'Estou bem, obrigado.', [Kit::word('como estás?'), Kit::word('bem'), Kit::form('estou', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.es-rui', 'Olá, és o Rui?', 'Hello, are you Rui?', [['sim', 'não', 'sou'], ['sou', 'rui', 'ana', 'joão', 'marta']], 'Sim, sou o Rui.', [Kit::word('olá'), Kit::form('sou')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.quem-ela', 'Quem é ela?', 'Who is she?', [['é', 'ela'], ['ana', 'marta', 'joão', 'rui']], 'Ela é a Marta.', [Kit::form('é')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.apresentacoes', 'Read the conversation.', [
                Kit::line('João', 'Bom dia. Chamo-me João. Como estás?'),
                Kit::line('Ana', 'Bem, obrigada. Sou a Ana. Muito prazer.'),
                Kit::line('João', 'Muito prazer, Ana. Ela é a minha amiga Marta.'),
                Kit::line('Marta', 'Olá, Ana. Eu sou estudante.'),
            ], [
                Kit::question('Who speaks first?', ['Rui', 'João', 'Ana'], 'João'),
                Kit::question('How is Ana?', ['She is fine.', 'She is not fine.', 'The text does not say.'], 'She is fine.'),
                Kit::question('Who is Marta?', ['João\'s friend', 'Ana\'s sister', 'A teacher'], 'João\'s friend'),
            ], [Kit::word('bom dia'), Kit::word('chamo-me'), Kit::word('como estás?'), Kit::word('bem'), Kit::word('obrigado', 'obrigada'), Kit::word('muito prazer'), Kit::word('olá')], 'read', null, ['amiga' => 'friend (female)', 'estudante' => 'student']),
            Kit::gap($stage, 'task.choose_gap.muito-prazer', 'Muito ___, Ana.', ['prazer', 'adeus'], 'prazer', Kit::word('muito prazer', 'prazer'), 'The set phrase for nice to meet you is muito prazer.', 'read'),
            Kit::gap($stage, 'task.choose_gap.bem-obrigado', 'Como estás? ___, obrigado.', ['Bem', 'Bom', 'Boa'], 'Bem', Kit::word('bem'), 'Bem is the usual answer to how are you. Bom is an adjective, as in bom dia.', 'read'),

            Kit::transform($stage, 'task.transform.somos', 'Make it plural: we.', 'Sou estudante.', ['Somos estudantes.', 'Nós somos estudantes.'], [Kit::form('somos')], ['estudante' => 'student', 'estudantes' => 'students']),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to tu.', 'Ela é a Marta.', ['Tu és a Marta.'], [Kit::form('és')]),
            Kit::transform($stage, 'task.transform.estamos', 'Change the subject to we.', 'Estou bem.', ['Estamos bem.', 'Nós estamos bem.'], [Kit::word('bem'), Kit::form('estamos', true)]),
            Kit::writeGuided($stage, 'task.write_guided.cumprimento', 'Say good morning, say your name is Ana and say nice to meet you.', ['bom dia', 'chamo-me', 'muito prazer'], 'Bom dia. Chamo-me Ana. Muito prazer.', [
                ['forms' => ['dia'], 'term' => 'bom dia'],
                ['forms' => ['chamo', 'sou', 'nome'], 'term' => 'chamo-me'],
                ['forms' => ['prazer', 'gosto'], 'term' => 'muito prazer'],
            ], [Kit::word('bom dia'), Kit::word('chamo-me'), Kit::word('muito prazer')]),
            Kit::writeGuided($stage, 'task.write_guided.despedida', 'Say good night to João, say thank you and say goodbye.', ['boa noite', 'obrigado', 'adeus'], 'Boa noite, João. Obrigado. Adeus.', [
                ['forms' => ['noite'], 'term' => 'boa noite'],
                ['forms' => ['obrigado', 'obrigada'], 'term' => 'obrigado'],
                ['forms' => ['adeus'], 'term' => 'adeus'],
            ], [Kit::word('boa noite'), Kit::word('obrigado'), Kit::word('adeus')]),
            Kit::build($stage, 'task.build.bom-dia', 'Good morning, I am Ana. How are you?', 'Bom dia, sou a Ana. Como estás?', ['estou', 'és'], [Kit::word('bom dia'), Kit::form('sou'), Kit::word('como estás?', 'como estás')], 'write'),
            Kit::build($stage, 'task.build.tarde', 'Good afternoon, we are João and Rui.', 'Boa tarde, somos o João e o Rui.', ['são', 'sou'], [Kit::word('boa tarde'), Kit::form('somos')], 'write'),
            Kit::build($stage, 'task.build.estou-bem', 'I am fine, thank you (a man speaking). Goodbye, Ana.', 'Estou bem, obrigado. Adeus, Ana.', ['sou', 'estás'], [Kit::word('bem'), Kit::word('obrigado'), Kit::word('adeus'), Kit::form('estou', true)], 'write'),
            Kit::translate($stage, 'task.translate.estudante', 'Nice to meet you. I am a student.', ['Muito prazer. Sou estudante.', 'Muito prazer, sou estudante.', 'Muito prazer. Eu sou estudante.', 'Muito prazer. Sou um estudante.', 'Muito prazer. Sou uma estudante.', 'Muito prazer. Eu sou um estudante.', 'Muito prazer. Eu sou uma estudante.'], [Kit::word('muito prazer'), Kit::form('sou')], 'write', null, ['estudante' => 'student']),
            Kit::translate($stage, 'task.translate.ola-como', 'Hello, how are you?', ['Olá, como estás?', 'Olá, como estás tu?', 'Olá, como está?', 'Olá, como está o senhor?', 'Olá, como está a senhora?'], [Kit::word('olá'), Kit::word('como estás?', 'como')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.boa-noite', [
                Kit::line('João', 'Boa noite, Marta. Como estás?'),
                Kit::line('Marta', 'Bem, obrigada. Ele é o Rui.'),
                Kit::line('João', 'Muito prazer, Rui. Chamo-me João.'),
                Kit::line('Rui', 'Muito prazer. Adeus, João. Boa noite.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Evening or night'], 'Evening or night'),
                Kit::question('How is Marta?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Marta introduce?', ['João', 'Rui', 'Ana'], 'Rui'),
            ], [
                Kit::question('What does João say to Rui?', ['Nice to meet you', 'Thank you', 'Good morning'], 'Nice to meet you'),
                Kit::question('Who says goodbye?', ['Marta', 'Rui', 'João'], 'Rui'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Three'),
            ], [Kit::word('boa noite'), Kit::word('como estás?'), Kit::word('bem'), Kit::word('obrigado'), Kit::word('muito prazer'), Kit::word('chamo-me'), Kit::word('adeus')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.tarde-rui', 'Boa tarde, Rui. Como estás?', 'Good afternoon, Rui. How are you?', [Kit::word('boa tarde'), Kit::word('como estás?', 'como estás')]),
            Kit::listenType($stage, 'task.listen_type.ele-ela', 'Ele é o João e ela é a Ana.', 'He is João and she is Ana.', [Kit::form('é')], homophoneNote: 'Both a words are the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.somos', 'Somos o Rui e a Marta. Muito prazer.', 'We are Rui and Marta. Nice to meet you.', [Kit::form('somos'), Kit::word('muito prazer')], homophoneNote: 'The a before Marta is the article a (the), not à (to the) and not há (there is).'),

            Kit::speakAnswer($stage, 'task.speak_answer.tarde', 'Boa tarde. Como estás?', 'Good afternoon. How are you?', [['estou', 'bem'], ['bem', 'obrigado', 'obrigada', 'muito']], 'Estou bem, obrigado.', [Kit::word('boa tarde'), Kit::word('como estás?'), Kit::word('bem'), Kit::form('estou', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quem-es', 'Bom dia. Quem és tu?', 'Good morning. Who are you?', [['sou', 'chamo', 'me', 'nome'], ['ana', 'joão', 'marta', 'rui']], 'Bom dia, sou a Ana.', [Kit::word('bom dia'), Kit::form('sou')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.muito-prazer', 'Chamo-me Rui. Muito prazer.', 'My name is Rui. Nice to meet you.', [['muito', 'prazer', 'gosto']], 'Muito prazer, Rui.', [Kit::word('chamo-me'), Kit::word('muito prazer')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.quem-ele', 'Quem é ele?', 'Who is he?', [['é', 'ele'], ['rui', 'joão']], 'Ele é o Rui.', [Kit::form('é')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.boa-noite', 'Boa noite, Marta. Adeus.', 'Good night, Marta. Goodbye.', [Kit::word('boa noite'), Kit::word('adeus')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.estamos', 'Estamos bem, obrigado.', 'We are fine, thank you.', [Kit::word('bem'), Kit::word('obrigado'), Kit::form('estamos', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.noite', 'Good night, I am Rui.', ['Boa noite, sou o Rui.', 'Boa noite, eu sou o Rui.', 'Boa noite, sou Rui.', 'Boa noite, eu sou Rui.'], [Kit::word('boa noite'), Kit::form('sou')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.obrigada', 'Thank you, Marta. Goodbye. (A woman speaking.)', ['Obrigada, Marta. Adeus.', 'Muito obrigada, Marta. Adeus.'], [Kit::word('obrigado', 'obrigada'), Kit::word('adeus')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.estou', 'How are you? I am fine.', ['Como estás? Estou bem.', 'Como está o senhor? Estou bem.', 'Como estás? Eu estou bem.', 'Como estás tu? Estou bem.', 'Como está? Estou bem.', 'Como está a senhora? Estou bem.'], [Kit::word('como estás?', 'como'), Kit::word('bem'), Kit::form('estou', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dia', 'Good morning, my name is Marta.', ['Bom dia, chamo-me Marta.', 'Bom dia, eu chamo-me Marta.'], [Kit::word('bom dia'), Kit::word('chamo-me')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.eles', 'Eles ___ o Rui e a Marta.', 'They are Rui and Marta.', 'são', Kit::form('são'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.estas', 'Bom dia, João. Como ___?', 'Good morning, João. How are you? (to a friend)', 'estás', Kit::form('estás', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.somos', 'Somos a Marta e o João.', 'We are Marta and João.', [Kit::form('somos')], 'dictation', $set, homophoneNote: 'The a before Marta is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.ola-tarde', 'Olá, boa tarde, sou o Rui.', 'Hello, good afternoon, I am Rui.', [Kit::word('olá'), Kit::word('boa tarde'), Kit::form('sou')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.muito-prazer', 'Muito prazer, chamo-me Rui.', 'Nice to meet you, my name is Rui.', [Kit::word('muito prazer'), Kit::word('chamo-me')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.manha', [
                Kit::line('Ana', 'Bom dia, João. Como estás?'),
                Kit::line('João', 'Muito bem, obrigado. E tu?'),
                Kit::line('Ana', 'Bem. João, ele é o Rui.'),
                Kit::line('João', 'Olá, Rui. Muito prazer.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Night'], 'Morning'),
                Kit::question('How is João?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Ana introduce?', ['Rui', 'Marta', 'Nobody'], 'Rui'),
            ], [
                Kit::question('Who says hello to Rui?', ['Ana', 'João', 'Marta'], 'João'),
                Kit::question('Does João say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('bom dia'), Kit::word('como estás?'), Kit::word('bem'), Kit::word('obrigado'), Kit::word('olá'), Kit::word('muito prazer')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.noite', 'Read the conversation.', [
                Kit::line('Marta', 'Boa noite. Chamo-me Marta.'),
                Kit::line('Rui', 'Olá, Marta. Sou o Rui. Muito prazer.'),
                Kit::line('Marta', 'Muito prazer. Como estás?'),
                Kit::line('Rui', 'Bem, obrigado.'),
            ], [
                Kit::question('What is the first speaker called?', ['Ana', 'Marta', 'João'], 'Marta'),
                Kit::question('How is Rui?', ['Fine', 'Not fine', 'The text does not say.'], 'Fine'),
            ], [Kit::word('boa noite'), Kit::word('chamo-me'), Kit::word('como estás?'), Kit::word('bem')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.como-estas', 'Como estás?', 'How are you?', [['estou', 'bem', 'muito'], ['bem', 'obrigado', 'obrigada']], 'Estou bem, obrigado.', [Kit::word('como estás?'), Kit::word('bem'), Kit::word('obrigado')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quem-es', 'Boa noite. Quem és tu?', 'Good night. Who are you?', [['sou', 'chamo', 'me', 'nome'], ['ana', 'rui', 'marta', 'joão']], 'Sou a Marta.', [Kit::word('boa noite')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.es-ana', 'Olá, és a Ana?', 'Hello, are you Ana?', [['sim', 'não', 'sou'], ['sou', 'ana', 'joão', 'marta', 'rui']], 'Sim, sou a Ana.', [Kit::word('olá')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ola-ela', 'Hello, she is Marta.', ['Olá, ela é a Marta.', 'Olá, ela é Marta.'], [Kit::word('olá'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.adeus', 'Goodbye, thank you. (A man speaking.)', ['Adeus, obrigado.', 'Adeus, muito obrigado.', 'Obrigado, adeus.', 'Muito obrigado, adeus.'], [Kit::word('adeus'), Kit::word('obrigado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sou-ana', 'I am Ana. Nice to meet you.', ['Sou a Ana. Muito prazer.', 'Sou a Ana, muito prazer.', 'Eu sou a Ana. Muito prazer.', 'Sou Ana. Muito prazer.'], [Kit::word('muito prazer'), Kit::form('sou')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.como', 'Good morning, how are you?', ['Bom dia, como estás?', 'Bom dia, como está?', 'Bom dia, como está o senhor?', 'Bom dia, como estás tu?', 'Bom dia, como está a senhora?'], [Kit::word('bom dia'), Kit::word('como estás?', 'como')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.estou', 'Eu ___ bem, obrigada.', 'I am fine, thank you.', 'estou', Kit::form('estou', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta', 'Como ___ a Marta?', 'How is Marta?', 'está', Kit::form('está', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.noite', 'Boa noite, sou a Marta.', 'Good night, I am Marta.', [Kit::word('boa noite'), Kit::form('sou')], 'dictation', $set, homophoneNote: 'The a before Marta is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.tarde-rui', 'Boa tarde, Rui. Muito prazer.', 'Good afternoon, Rui. Nice to meet you.', [Kit::word('boa tarde'), Kit::word('muito prazer')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.chamo-me', 'Chamo-me Rui e estou bem.', 'My name is Rui and I am fine.', [Kit::word('chamo-me'), Kit::word('bem'), Kit::form('estou', true)], 'dictation', $set),
        ];
    }
}
