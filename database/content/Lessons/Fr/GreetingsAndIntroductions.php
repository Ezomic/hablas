<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('bonjour', cue: 'hello, good day (polite; the standard greeting until the evening)'),
            new WordData('bonsoir', cue: 'good evening (the greeting in the evening)'),
            new WordData('salut', cue: 'hi, or bye, between friends'),
            new WordData('au revoir', cue: 'goodbye'),
            new WordData('je m\'appelle', cue: 'my name is (introducing yourself)', accepted: ['moi, je m\'appelle']),
            new WordData('enchanté', cue: 'nice to meet you (said by a man)', accepted: ['enchantée']),
            new WordData('comment allez-vous ?', cue: 'how are you? (formal, to one person or more)', accepted: ['comment allez-vous']),
            new WordData('ça va ?', cue: 'how are you? (informal)', accepted: ['ça va', 'comment vas-tu ?']),
            new WordData('bien', cue: 'well, fine (as in Je vais bien, I am fine)', accepted: ['très bien']),
            new WordData('merci', cue: 'thank you', accepted: ['merci beaucoup']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Je suis Anne.', 'english' => 'I am Anne.'],
            ['text' => 'Elle est mon amie.', 'english' => 'She is my friend.'],
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
            Kit::gap($stage, 'sentences.choose_gap.bonjour-suis', 'Bonjour, je ___ Anne.', ['suis', 'vais', 'es'], 'suis', Kit::form('suis'), 'Saying who you are takes être: I am is je suis.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.comment-vous', 'Comment ___-vous ?', ['allez', 'êtes', 'avez'], 'allez', Kit::form('allez', true), 'How someone is, their health, takes aller. Être would ask what someone is like.', 'choose', 'How are you? (formal)'),
            Kit::gap($stage, 'sentences.choose_gap.je-bien', 'Je ___ bien.', ['vais', 'vas', 'va'], 'vais', Kit::form('vais', true), 'How you are, your health, takes aller: je vais.', 'choose', 'I am fine.'),
            Kit::gap($stage, 'sentences.choose_gap.elle-marie', 'Elle ___ Marie.', ['est', 'suis', 'sommes'], 'est', Kit::form('est'), 'He and she take est.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.nous', 'Nous ___ Paul et Luc.', ['sommes', 'sont', 'suis'], 'sommes', Kit::form('sommes'), 'Nous (we) takes sommes.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.bonsoir', '___, Marie.', ['Bonsoir', 'Au revoir', 'Merci'], 'Bonsoir', Kit::word('bonsoir'), 'Bonsoir is the greeting in the evening.', 'choose', 'Good evening, Marie.'),

            Kit::typeGap($stage, 'sentences.type_gap.etudiant', 'Je ___ étudiant.', 'I am a student.', 'suis', Kit::form('suis'), 'Saying who you are takes être: I am is je suis.', glosses: ['étudiant' => 'student']),
            Kit::typeGap($stage, 'sentences.type_gap.ca-va', 'Salut, Anne. Ça ___ ?', 'Hi, Anne. How are you?', 'va', Kit::form('va', true), 'How things are takes aller: ça va. Être would say who someone is.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-luc', 'Tu ___ Luc.', 'You are Luc.', 'es', Kit::form('es'), 'You, to a friend, takes es.'),
            Kit::typeGap($stage, 'sentences.type_gap.ils', 'Ils ___ Anne et Luc.', 'They are Anne and Luc.', 'sont', Kit::form('sont'), 'Ils (they) takes sont.'),
            Kit::typeGap($stage, 'sentences.type_gap.au-revoir', 'Au ___, Paul.', 'Goodbye, Paul.', 'revoir', Kit::word('au revoir', 'revoir')),
            Kit::translate($stage, 'sentences.translate.bonjour-suis', 'Good morning, I am Anne.', ['Bonjour, je suis Anne.'], [Kit::word('bonjour'), Kit::form('suis')]),
            Kit::translate($stage, 'sentences.translate.je-mappelle', 'My name is Paul. Nice to meet you.', ["Je m'appelle Paul. Enchanté.", "Je m'appelle Paul, enchanté."], [Kit::word("je m'appelle"), Kit::word('enchanté')]),
            Kit::translate($stage, 'sentences.translate.bien-merci', 'I am fine, thank you.', ['Je vais bien, merci.', 'Je vais bien. Merci.', 'Je vais bien, merci beaucoup.', 'Je vais très bien, merci.'], [Kit::word('bien'), Kit::word('merci'), Kit::form('vais', true)]),
            Kit::build($stage, 'sentences.build.bonsoir', 'Good evening, I am Anne.', 'Bonsoir, je suis Anne.', ['vais'], [Kit::word('bonsoir'), Kit::form('suis')]),
            Kit::build($stage, 'sentences.build.salut-paul', 'Hi, he is Paul.', 'Salut, il est Paul.', ['suis'], [Kit::word('salut'), Kit::form('est')]),
            Kit::build($stage, 'sentences.build.sommes', 'We are Paul and Luc.', 'Nous sommes Paul et Luc.', ['sont'], [Kit::form('sommes')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.bonsoir', 'Bonsoir, je suis Anne.', ['Good morning, I am Anne.', 'Good evening, I am Anne.', 'Good night, I am Anne.', 'Goodbye, I am Anne.'], 'Good evening, I am Anne.', [Kit::word('bonsoir'), Kit::form('suis')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.enchante', 'Enchanté, Paul.', ['Nice to meet you, Paul.', 'Thank you, Paul.', 'Goodbye, Paul.', 'Hello, Paul.'], 'Nice to meet you, Paul.', [Kit::word('enchanté')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.au-revoir', 'Au revoir, Marie.', ['Hello, Marie.', 'Goodbye, Marie.', 'Thank you, Marie.', 'Nice to meet you, Marie.'], 'Goodbye, Marie.', [Kit::word('au revoir')]),
            Kit::listenType($stage, 'sentences.listen_type.bonjour-luc', "Bonjour, je m'appelle Luc.", 'Hello, my name is Luc.', [Kit::word('bonjour'), Kit::word("je m'appelle")]),
            Kit::listenType($stage, 'sentences.listen_type.comment-allez-vous', 'Comment allez-vous, Anne ?', 'How are you, Anne?', [Kit::word('comment allez-vous ?', 'comment allez-vous')]),
            Kit::listenType($stage, 'sentences.listen_type.salut-marie', 'Salut, je suis Marie.', 'Hi, I am Marie.', [Kit::word('salut'), Kit::form('suis')]),
            Kit::listenType($stage, 'sentences.listen_type.bonsoir-ca-va', 'Bonsoir, Paul. Ça va ?', 'Good evening, Paul. How are you?', [Kit::word('bonsoir'), Kit::word('ça va ?', 'ça va')], homophoneNote: 'Ça (this, it) is written with a cedilla, not sa (his or her).'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.bonjour-mappelle', "Bonjour, je m'appelle Paul. Enchanté.", 'Hello, my name is Paul. Nice to meet you.', [Kit::word('bonjour'), Kit::word("je m'appelle"), Kit::word('enchanté')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.au-revoir', 'Au revoir, Marie. Merci.', 'Goodbye, Marie. Thank you.', [Kit::word('au revoir'), Kit::word('merci')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.bonjour-suis', 'Bonjour, je suis Paul.', 'Hello, I am Paul.', [Kit::word('bonjour'), Kit::form('suis')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ca-va', 'Ça va, Anne ? Je vais bien.', 'How are you, Anne? I am fine.', [Kit::word('ça va ?', 'ça va'), Kit::word('bien'), Kit::form('vais', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.comment-allez-vous', 'Comment allez-vous ?', 'How are you?', [['vais', 'bien', 'va', 'ça'], ['bien', 'merci', 'très']], 'Je vais bien, merci.', [Kit::word('comment allez-vous ?'), Kit::word('bien'), Kit::form('vais', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.tu-luc', 'Salut, tu es Luc ?', 'Hi, are you Luc?', [['oui', 'non', 'suis'], ['suis', 'luc', 'anne', 'paul', 'marie', "c'est", 'moi', "m'appelle"]], 'Oui, je suis Luc.', [Kit::word('salut'), Kit::form('suis')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vous-marie', 'Bonsoir. Vous êtes Marie ?', 'Good evening. Are you Marie?', [['oui', 'non', 'suis'], ['suis', 'marie', 'anne', 'paul', 'luc', "c'est", 'moi', "m'appelle"]], 'Oui, je suis Marie.', [Kit::word('bonsoir'), Kit::form('suis')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.presentations', 'Read the conversation.', [
                Kit::line('Paul', "Bonjour. Je m'appelle Paul. Comment allez-vous ?"),
                Kit::line('Anne', 'Je vais bien, merci. Je suis Anne. Enchantée.'),
                Kit::line('Paul', 'Enchanté, Anne. Voici mon amie Marie.'),
                Kit::line('Marie', 'Bonjour, Anne. Je suis étudiante.'),
            ], [
                Kit::question('Who speaks first?', ['Luc', 'Paul', 'Anne'], 'Paul'),
                Kit::question('How is Anne?', ['She is fine.', 'She is not fine.', 'The text does not say.'], 'She is fine.'),
                Kit::question('Who is Marie?', ["Paul's friend", "Anne's sister", 'A teacher'], "Paul's friend"),
            ], [Kit::word('bonjour'), Kit::word("je m'appelle"), Kit::word('comment allez-vous ?'), Kit::word('bien'), Kit::word('merci'), Kit::word('enchanté')], 'read', null, ['amie' => 'friend (female)', 'étudiante' => 'student (female)']),
            Kit::gap($stage, 'task.choose_gap.au-revoir', 'Au ___, Marie. Merci.', ['revoir', 'suis', 'vais'], 'revoir', Kit::word('au revoir', 'revoir'), 'The set phrase for goodbye is au revoir.', 'read'),
            Kit::gap($stage, 'task.choose_gap.ca-va-bien', 'Ça va ? ___, merci.', ['Bien', 'Bonjour', 'Bonsoir'], 'Bien', Kit::word('bien'), 'Bien is the usual answer to how are you.', 'read'),

            Kit::transform($stage, 'task.transform.sommes', 'Make it plural: we.', 'Je suis étudiant.', ['Nous sommes étudiants.'], [Kit::form('sommes')], ['étudiant' => 'student', 'étudiants' => 'students']),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to tu.', 'Elle est Marie.', ['Tu es Marie.'], [Kit::form('es')]),
            Kit::transform($stage, 'task.transform.allons', 'Change the subject to we.', 'Je vais bien.', ['Nous allons bien.'], [Kit::word('bien'), Kit::form('allons', true)]),
            Kit::writeGuided($stage, 'task.write_guided.salutation', 'Say hello, say your name is Anne and say nice to meet you.', ['bonjour', "je m'appelle", 'enchanté'], "Bonjour. Je m'appelle Anne. Enchantée.", [
                ['forms' => ['bonjour', 'salut'], 'term' => 'bonjour'],
                ['forms' => ["m'appelle", 'suis'], 'term' => "je m'appelle"],
                ['forms' => ['enchanté', 'enchantée'], 'term' => 'enchanté'],
            ], [Kit::word('bonjour'), Kit::word("je m'appelle"), Kit::word('enchanté')]),
            Kit::writeGuided($stage, 'task.write_guided.adieu', 'Say good evening to Marie, say thank you and say goodbye.', ['bonsoir', 'merci', 'au revoir'], 'Bonsoir, Marie. Merci. Au revoir.', [
                ['forms' => ['bonsoir'], 'term' => 'bonsoir'],
                ['forms' => ['merci'], 'term' => 'merci'],
                ['forms' => ['revoir', 'salut'], 'term' => 'au revoir'],
            ], [Kit::word('bonsoir'), Kit::word('merci'), Kit::word('au revoir')]),
            Kit::build($stage, 'task.build.bonjour-vais', 'Hello, I am Anne. I am fine.', 'Bonjour, je suis Anne. Je vais bien.', ['es', 'sommes'], [Kit::word('bonjour'), Kit::word('bien'), Kit::form('vais', true)], 'write'),
            Kit::build($stage, 'task.build.bonsoir', 'Good evening, we are Paul and Luc.', 'Bonsoir, nous sommes Paul et Luc.', ['sont', 'suis'], [Kit::word('bonsoir'), Kit::form('sommes')], 'write'),
            Kit::build($stage, 'task.build.vais-bien', 'I am fine, thank you. Goodbye, Anne.', 'Je vais bien, merci. Au revoir, Anne.', ['suis', 'es'], [Kit::word('bien'), Kit::word('merci'), Kit::word('au revoir'), Kit::form('vais', true)], 'write'),
            Kit::translate($stage, 'task.translate.enchante-paul', 'Nice to meet you. I am Paul.', ['Enchanté. Je suis Paul.', 'Enchanté, je suis Paul.'], [Kit::word('enchanté'), Kit::form('suis')], 'write'),
            Kit::translate($stage, 'task.translate.salut-ca-va', 'Hi, how are you?', ['Salut, ça va ?', 'Salut, comment vas-tu ?', 'Salut, comment ça va ?', 'Salut, tu vas bien ?'], [Kit::word('salut')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.bonsoir', [
                Kit::line('Luc', 'Bonsoir, Marie. Ça va ?'),
                Kit::line('Marie', 'Bien, merci. Voici Paul.'),
                Kit::line('Luc', "Enchanté, Paul. Je m'appelle Luc."),
                Kit::line('Paul', 'Enchanté. Au revoir, Luc.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Afternoon', 'Evening'], 'Evening'),
                Kit::question('How is Marie?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Marie introduce?', ['Luc', 'Paul', 'Anne'], 'Paul'),
            ], [
                Kit::question('What does Luc say to Paul?', ['Nice to meet you', 'Thank you', 'Good morning'], 'Nice to meet you'),
                Kit::question('Who says goodbye?', ['Marie', 'Paul', 'Luc'], 'Paul'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Three'),
            ], [Kit::word('bonsoir'), Kit::word('ça va ?'), Kit::word('bien'), Kit::word('merci'), Kit::word('enchanté'), Kit::word("je m'appelle"), Kit::word('au revoir')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.bonjour-luc', 'Bonjour, Luc. Ça va ?', 'Hello, Luc. How are you?', [Kit::word('bonjour'), Kit::word('ça va ?', 'ça va')], homophoneNote: 'Ça (this, it) is written with a cedilla, not sa (his or her).'),
            Kit::listenType($stage, 'task.listen_type.il-elle', 'Il est Paul et elle est Anne.', 'He is Paul and she is Anne.', [Kit::form('est')], homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb and et joins the two names.'),
            Kit::listenType($stage, 'task.listen_type.sommes', 'Nous sommes Luc et Marie. Bonsoir.', 'We are Luc and Marie. Good evening.', [Kit::form('sommes'), Kit::word('bonsoir')], homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb and et joins the two names.'),

            Kit::speakAnswer($stage, 'task.speak_answer.bonsoir', 'Bonsoir. Comment allez-vous ?', 'Good evening. How are you?', [['vais', 'bien', 'va', 'ça'], ['bien', 'merci', 'très']], 'Je vais bien, merci.', [Kit::word('bonsoir'), Kit::word('comment allez-vous ?'), Kit::word('bien'), Kit::form('vais', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tu-anne', 'Salut. Tu es Anne ?', 'Hi. Are you Anne?', [['oui', 'non', 'suis'], ['suis', 'anne', 'luc', 'paul', 'marie', "c'est", 'moi', "m'appelle"]], 'Oui, je suis Anne.', [Kit::word('salut'), Kit::form('suis')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.enchante', "Je m'appelle Paul. Enchanté.", 'My name is Paul. Nice to meet you.', [['enchanté', 'enchantée', 'aussi']], 'Enchanté, Paul.', [Kit::word("je m'appelle"), Kit::word('enchanté')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.luc-paul', 'Tu es Luc ou Paul ?', 'Are you Luc or Paul?', [['suis', "c'est", 'moi'], ['paul', 'luc']], 'Je suis Paul.', [Kit::form('suis')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.bonsoir', 'Bonsoir, Luc. Au revoir.', 'Good evening, Luc. Goodbye.', [Kit::word('bonsoir'), Kit::word('au revoir')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.allons', 'Nous allons bien, merci.', 'We are fine, thank you.', [Kit::word('bien'), Kit::word('merci'), Kit::form('allons', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.bonsoir', 'Good evening, I am Luc.', ['Bonsoir, je suis Luc.'], [Kit::word('bonsoir'), Kit::form('suis')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.merci', 'Thank you, Marie. Goodbye.', ['Merci, Marie. Au revoir.', 'Merci beaucoup, Marie. Au revoir.'], [Kit::word('merci'), Kit::word('au revoir')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vais', 'How are you? I am fine.', ['Ça va ? Je vais bien.', 'Ça va ? Je vais très bien.'], [Kit::word('ça va ?', 'ça va'), Kit::word('bien'), Kit::form('vais', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.bonjour', 'Good morning, my name is Marie.', ["Bonjour, je m'appelle Marie."], [Kit::word('bonjour'), Kit::word("je m'appelle")], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.ils', 'Ils ___ Paul et Marie.', 'They are Paul and Marie.', 'sont', Kit::form('sont'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.va', 'Marie, ça ___ ?', 'Marie, how are you?', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sommes', 'Nous sommes Marie et Luc.', 'We are Marie and Luc.', [Kit::form('sommes')], 'dictation', $set, homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb and et joins the two names.'),
            Kit::listenType($stage, 'check.a.listen_type.salut', 'Salut, je suis Paul. Enchanté.', 'Hi, I am Paul. Nice to meet you.', [Kit::word('salut'), Kit::form('suis'), Kit::word('enchanté')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.comment', 'Comment allez-vous, Marie ?', 'How are you, Marie?', [Kit::word('comment allez-vous ?', 'comment allez-vous')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.matin', [
                Kit::line('Anne', 'Luc, comment allez-vous ?'),
                Kit::line('Luc', 'Très bien, merci. Et vous ?'),
                Kit::line('Anne', 'Bien. Luc, voici Paul.'),
                Kit::line('Luc', 'Bonjour, Paul. Enchanté.'),
            ], [
                Kit::question('How is Luc?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Anne introduce?', ['Paul', 'Marie', 'Nobody'], 'Paul'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [
                Kit::question('Who says hello to Paul?', ['Anne', 'Luc', 'Marie'], 'Luc'),
                Kit::question('Does Luc say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What does Luc answer when Anne asks how he is?', ['Very well, thank you', 'Not well', 'He does not answer'], 'Very well, thank you'),
            ], [Kit::word('bonjour'), Kit::word('comment allez-vous ?'), Kit::word('bien'), Kit::word('merci'), Kit::word('enchanté')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.soir', 'Read the conversation.', [
                Kit::line('Marie', "Bonsoir. Je m'appelle Marie."),
                Kit::line('Paul', 'Bonsoir, Marie. Je suis Paul. Enchanté.'),
                Kit::line('Marie', 'Enchantée. Ça va ?'),
                Kit::line('Paul', 'Je vais très bien, merci.'),
            ], [
                Kit::question('What is the first speaker called?', ['Anne', 'Marie', 'Luc'], 'Marie'),
                Kit::question('How is Paul?', ['Fine', 'Not fine', 'The text does not say.'], 'Fine'),
            ], [Kit::word('bonsoir'), Kit::word("je m'appelle"), Kit::word('ça va ?'), Kit::word('bien')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ca-va', 'Ça va ?', 'How are you?', [['vais', 'bien', 'très', 'va', 'ça', 'oui'], ['bien', 'merci']], 'Je vais bien, merci.', [Kit::word('ça va ?'), Kit::word('bien'), Kit::word('merci')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tu-anne', 'Bonsoir. Vous êtes Anne ?', 'Good evening. Are you Anne?', [['oui', 'non', 'suis'], ['suis', 'anne', 'luc', 'marie', 'paul', "c'est", 'moi', "m'appelle"]], 'Oui, je suis Anne.', [Kit::word('bonsoir')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.tu-paul', 'Salut, tu es Paul ?', 'Hi, are you Paul?', [['oui', 'non', 'suis'], ['suis', 'paul', 'luc', 'marie', 'anne', "c'est", 'moi', "m'appelle"]], 'Oui, je suis Paul.', [Kit::word('salut')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.salut-elle', 'Hi, she is Marie.', ['Salut, elle est Marie.'], [Kit::word('salut'), Kit::form('est')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.au-revoir', 'Goodbye, thank you.', ['Au revoir, merci.', 'Au revoir, merci beaucoup.', 'Merci, au revoir.', 'Merci beaucoup, au revoir.'], [Kit::word('au revoir'), Kit::word('merci')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.enchante', 'I am Paul. Nice to meet you.', ['Je suis Paul. Enchanté.', 'Je suis Paul, enchanté.'], [Kit::word('enchanté'), Kit::form('suis')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.comment', 'Good morning, how are you?', ['Bonjour, comment allez-vous ?', 'Bonjour, comment vas-tu ?'], [Kit::word('bonjour'), Kit::word('comment allez-vous ?', 'comment')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.va', 'Elle ___ bien.', 'She is fine.', 'va', Kit::form('va', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.etes', 'Vous ___ Anne ?', 'Are you Anne?', 'êtes', Kit::form('êtes'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.bonsoir', "Bonsoir, je m'appelle Paul.", 'Good evening, my name is Paul.', [Kit::word('bonsoir'), Kit::word("je m'appelle")], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.ca-va', 'Paul, ça va ? Je vais bien.', 'Paul, how are you? I am fine.', [Kit::word('ça va ?', 'ça va'), Kit::word('bien'), Kit::form('vais', true)], 'dictation', $set, homophoneNote: 'Ça (this, it) is written with a cedilla, not sa (his or her).'),
            Kit::listenType($stage, 'check.b.listen_type.elle-est', 'Elle est Marie et il est Paul.', 'She is Marie and he is Paul.', [Kit::form('est')], 'dictation', $set, homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb and et joins the two names.'),
        ];
    }
}
