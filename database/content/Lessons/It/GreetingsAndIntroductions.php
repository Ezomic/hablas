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

final class GreetingsAndIntroductions implements UnitContent
{
    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'greetings-and-introductions';
    }

    public function words(): array
    {
        return [
            new WordData('ciao', cue: 'hi, or bye, between friends'),
            new WordData('buongiorno', cue: 'good morning, good day (until the afternoon)', accepted: ['buon giorno']),
            new WordData('buonasera', cue: 'good evening (the greeting from the afternoon on)', accepted: ['buona sera']),
            new WordData('buonanotte', cue: 'good night (said when going to bed)', accepted: ['buona notte']),
            new WordData('arrivederci', cue: 'goodbye'),
            new WordData('mi chiamo', cue: 'my name is (introducing yourself)'),
            new WordData('piacere', cue: 'nice to meet you', accepted: ['molto piacere']),
            new WordData('come stai?', cue: 'how are you? (informal, to a friend)', accepted: ['come stai']),
            new WordData('bene', cue: 'well, fine (as in sto bene, I am fine)'),
            new WordData('grazie', cue: 'thank you', accepted: ['mille grazie', 'molte grazie']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Sono Anna.', 'english' => 'I am Anna.'],
            ['text' => 'Questa è Marta.', 'english' => 'This is Marta.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.ciao-sono', 'Ciao, ___ Anna.', ['sono', 'sto', 'siamo'], 'sono', Kit::form('sono'), 'Saying who you are takes essere: I am is sono.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.come-lei', 'Come ___ Lei?', ['sta', 'siamo', 'sono'], 'sta', Kit::form('sta', true), 'How someone is, their health, takes stare. Essere says who someone is.', 'choose', 'How are you? (formal)'),
            Kit::gap($stage, 'sentences.choose_gap.io-bene', 'Io ___ bene.', ['sto', 'sono', 'sta'], 'sto', Kit::form('sto', true), 'How you are, your health, takes stare, not essere: sto bene.', 'choose', 'I am fine.'),
            Kit::gap($stage, 'sentences.choose_gap.questa-marta', 'Questa ___ Marta.', ['è', 'sono', 'siamo'], 'è', Kit::form('è'), 'He, she and this person take è.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.noi', 'Noi ___ Paolo e Luca.', ['siamo', 'sono', 'siete'], 'siamo', Kit::form('siamo'), 'Noi (we) takes siamo.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.buonanotte', '___, Marta.', ['Buonanotte', 'Sei', 'Sto'], 'Buonanotte', Kit::word('buonanotte'), 'Buonanotte is what you say when someone goes to bed.', 'choose', 'Good night, Marta.'),

            Kit::typeGap($stage, 'sentences.type_gap.studente', 'Io ___ studente.', 'I am a student.', 'sono', Kit::form('sono'), 'Saying who you are takes essere: I am is sono.', glosses: ['studente' => 'student']),
            Kit::typeGap($stage, 'sentences.type_gap.come-stai', 'Ciao, Anna. Come ___?', 'Hi, Anna. How are you?', 'stai', Kit::form('stai', true), 'How you are, your health, takes stare. Sei says who you are.'),
            Kit::typeGap($stage, 'sentences.type_gap.tu-luca', 'Tu ___ Luca.', 'You are Luca.', 'sei', Kit::form('sei'), 'You, to a friend, takes sei.'),
            Kit::typeGap($stage, 'sentences.type_gap.loro', 'Loro ___ Anna e Luca.', 'They are Anna and Luca.', 'sono', Kit::form('sono'), 'More than one person, they, takes sono, the same form as I am.'),
            Kit::typeGap($stage, 'sentences.type_gap.buongiorno', '___, Paolo.', 'Good morning, Paolo.', 'buongiorno', Kit::word('buongiorno')),
            Kit::translate($stage, 'sentences.translate.buongiorno-sono', 'Good morning, I am Anna.', ['Buongiorno, sono Anna.', 'Buon giorno, sono Anna.', 'Buongiorno, io sono Anna.'], [Kit::word('buongiorno', null, ['buon giorno']), Kit::form('sono')]),
            Kit::translate($stage, 'sentences.translate.mi-chiamo', 'My name is Luca. Nice to meet you.', ['Mi chiamo Luca. Piacere.', 'Mi chiamo Luca, piacere.', 'Mi chiamo Luca. Molto piacere.'], [Kit::word('mi chiamo'), Kit::word('piacere')]),
            Kit::translate($stage, 'sentences.translate.bene-grazie', 'I am fine, thank you.', ['Sto bene, grazie.', 'Sto bene. Grazie.', 'Sto bene, mille grazie.', 'Io sto bene, grazie.', 'Sto molto bene, grazie.'], [Kit::word('bene'), Kit::word('grazie'), Kit::form('sto', true)]),
            Kit::build($stage, 'sentences.build.buonasera', 'Good evening, I am Anna.', 'Buonasera, sono Anna.', ['sto'], [Kit::word('buonasera'), Kit::form('sono')]),
            Kit::build($stage, 'sentences.build.ciao-paolo', 'Hello, this is Paolo.', 'Ciao, questo è Paolo.', ['siamo'], [Kit::word('ciao'), Kit::form('è')]),
            Kit::build($stage, 'sentences.build.siamo', 'We are Paolo and Luca.', 'Siamo Paolo e Luca.', ['siete'], [Kit::form('siamo')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.buonanotte', 'Buonanotte, sono Anna.', ['Good morning, I am Anna.', 'Good night, I am Anna.', 'Good evening, I am Anna.', 'Goodbye, I am Anna.'], 'Good night, I am Anna.', [Kit::word('buonanotte'), Kit::form('sono')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.piacere', 'Piacere, Paolo.', ['Nice to meet you, Paolo.', 'Thank you, Paolo.', 'Goodbye, Paolo.', 'Hello, Paolo.'], 'Nice to meet you, Paolo.', [Kit::word('piacere')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.arrivederci', 'Arrivederci, Marta.', ['Hello, Marta.', 'Goodbye, Marta.', 'Thank you, Marta.', 'Nice to meet you, Marta.'], 'Goodbye, Marta.', [Kit::word('arrivederci')]),
            Kit::listenType($stage, 'sentences.listen_type.buongiorno', 'Buongiorno, mi chiamo Luca.', 'Good morning, my name is Luca.', [Kit::word('buongiorno'), Kit::word('mi chiamo')]),
            Kit::listenType($stage, 'sentences.listen_type.come-stai', 'Come stai, Anna?', 'How are you, Anna?', [Kit::word('come stai?', 'come stai')]),
            Kit::listenType($stage, 'sentences.listen_type.ciao-sono', 'Ciao, sono Marta.', 'Hello, I am Marta.', [Kit::word('ciao'), Kit::form('sono')]),
            Kit::listenType($stage, 'sentences.listen_type.buonasera', 'Buonasera, Paolo. Come stai?', 'Good evening, Paolo. How are you?', [Kit::word('buonasera'), Kit::word('come stai?', 'come stai')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ciao-mi-chiamo', 'Ciao, mi chiamo Anna. Piacere.', 'Hello, my name is Anna. Nice to meet you.', [Kit::word('ciao'), Kit::word('mi chiamo'), Kit::word('piacere')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.arrivederci', 'Arrivederci, Marta. Grazie.', 'Goodbye, Marta. Thank you.', [Kit::word('arrivederci'), Kit::word('grazie')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.buongiorno', 'Buongiorno, sono Paolo.', 'Good morning, I am Paolo.', [Kit::word('buongiorno'), Kit::form('sono')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.come-stai', 'Come stai, Anna? Sto bene.', 'How are you, Anna? I am fine.', [Kit::word('come stai?', 'come stai'), Kit::word('bene'), Kit::form('sto', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.come-stai', 'Come stai?', 'How are you?', [['sto', 'bene']], 'Sto bene, grazie.', [Kit::word('come stai?'), Kit::word('bene'), Kit::form('sto', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sei-luca', 'Ciao, sei Luca?', 'Hello, are you Luca?', [['sì', 'no'], ['sono', 'luca', 'anna', 'paolo', 'marta']], 'Sì, sono Luca.', [Kit::word('ciao'), Kit::form('sono')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.chi-questa', 'Chi è questa?', 'Who is this?', [['è', 'questa', 'lei', 'anna', 'marta', 'paolo', 'luca']], 'Questa è Marta.', [Kit::form('è')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.presentazioni', 'Read the conversation.', [
                Kit::line('Paolo', 'Buongiorno. Mi chiamo Paolo. Come stai?'),
                Kit::line('Anna', 'Bene, grazie. Sono Anna. Piacere.'),
                Kit::line('Paolo', 'Piacere, Anna. Questa è Marta.'),
                Kit::line('Marta', 'Ciao, Anna. Sono studentessa.'),
            ], [
                Kit::question('Who speaks first?', ['Luca', 'Paolo', 'Anna'], 'Paolo'),
                Kit::question('How is Anna?', ['She is fine.', 'She is not fine.', 'The text does not say.'], 'She is fine.'),
                Kit::question('Who does Paolo introduce to Anna?', ['Luca', 'Marta', 'Nobody'], 'Marta'),
            ], [Kit::word('buongiorno'), Kit::word('mi chiamo'), Kit::word('come stai?'), Kit::word('bene'), Kit::word('grazie'), Kit::word('piacere'), Kit::word('ciao')], 'read', null, ['studentessa' => 'student (female)']),
            Kit::gap($stage, 'task.choose_gap.mi-chiamo', 'Mi ___ Paolo. Piacere.', ['chiamo', 'sono'], 'chiamo', Kit::word('mi chiamo', 'chiamo'), 'The set phrase for my name is is mi chiamo.', 'read'),
            Kit::gap($stage, 'task.choose_gap.bene-grazie', 'Come stai? ___, grazie.', ['Bene', 'Buonanotte', 'Buonasera'], 'Bene', Kit::word('bene'), 'Bene is the usual answer to how are you.', 'read'),

            Kit::transform($stage, 'task.transform.siamo', 'Make it plural: we.', 'Sono studente.', ['Siamo studenti.', 'Noi siamo studenti.'], [Kit::form('siamo')], ['studente' => 'student', 'studenti' => 'students']),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to tu.', 'Paolo è qui.', ['Tu sei qui.', 'Sei qui.'], [Kit::form('sei')]),
            Kit::transform($stage, 'task.transform.stiamo', 'Change the subject to we.', 'Sto bene.', ['Stiamo bene.', 'Noi stiamo bene.'], [Kit::word('bene'), Kit::form('stiamo', true)]),
            Kit::writeGuided($stage, 'task.write_guided.saluto', 'Say good morning, say your name is Anna and say nice to meet you.', ['buongiorno', 'mi chiamo', 'piacere'], 'Buongiorno. Mi chiamo Anna. Piacere.', [
                ['forms' => ['buongiorno', 'buon giorno'], 'term' => 'buongiorno'],
                ['forms' => ['chiamo'], 'term' => 'mi chiamo'],
                ['forms' => ['piacere'], 'term' => 'piacere'],
            ], [Kit::word('buongiorno'), Kit::word('mi chiamo'), Kit::word('piacere')]),
            Kit::writeGuided($stage, 'task.write_guided.congedo', 'Say good night to Luca, say thank you and say goodbye.', ['buonanotte', 'grazie', 'arrivederci'], 'Buonanotte, Luca. Grazie. Arrivederci.', [
                ['forms' => ['buonanotte', 'buona notte'], 'term' => 'buonanotte'],
                ['forms' => ['grazie'], 'term' => 'grazie'],
                ['forms' => ['arrivederci'], 'term' => 'arrivederci'],
            ], [Kit::word('buonanotte'), Kit::word('grazie'), Kit::word('arrivederci')]),
            Kit::build($stage, 'task.build.buongiorno', 'Good morning, I am Anna. How are you?', 'Buongiorno, sono Anna. Come stai?', ['sto', 'sei'], [Kit::word('buongiorno'), Kit::form('sono'), Kit::word('come stai?', 'come stai')], 'write'),
            Kit::build($stage, 'task.build.buonasera', 'Good evening, we are Paolo and Luca.', 'Buonasera, siamo Paolo e Luca.', ['sono', 'siete'], [Kit::word('buonasera'), Kit::form('siamo')], 'write'),
            Kit::build($stage, 'task.build.sto-bene', 'I am fine, thank you. Goodbye, Anna.', 'Sto bene, grazie. Arrivederci, Anna.', ['sono', 'stai'], [Kit::word('bene'), Kit::word('grazie'), Kit::word('arrivederci'), Kit::form('sto', true)], 'write'),
            Kit::translate($stage, 'task.translate.studente', 'Nice to meet you. I am a student.', ['Piacere. Sono studente.', 'Piacere, sono studente.', 'Piacere. Io sono studente.', 'Piacere. Sono uno studente.', 'Piacere. Sono studentessa.', 'Piacere, sono studentessa.', 'Piacere. Sono una studentessa.'], [Kit::word('piacere'), Kit::form('sono')], 'write', null, ['studente' => 'student', 'studentessa' => 'student (female)']),
            Kit::translate($stage, 'task.translate.ciao-come', 'Hello, how are you?', ['Ciao, come stai?', 'Ciao, come stai tu?'], [Kit::word('ciao'), Kit::word('come stai?', 'come stai')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.buonasera', [
                Kit::line('Luca', 'Buonasera, Marta. Come stai?'),
                Kit::line('Marta', 'Bene, grazie. Questo è Paolo.'),
                Kit::line('Luca', 'Piacere, Paolo. Mi chiamo Luca.'),
                Kit::line('Paolo', 'Piacere. Arrivederci, Luca. Buonanotte.'),
            ], [
                Kit::question('How does Luca greet Marta?', ['Good morning', 'Good evening', 'Hello'], 'Good evening'),
                Kit::question('How is Marta?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Marta introduce?', ['Luca', 'Paolo', 'Anna'], 'Paolo'),
            ], [
                Kit::question('What does Luca say to Paolo?', ['Nice to meet you', 'Thank you', 'Good morning'], 'Nice to meet you'),
                Kit::question('Who says goodbye?', ['Marta', 'Paolo', 'Luca'], 'Paolo'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Three'),
            ], [Kit::word('buonasera'), Kit::word('come stai?'), Kit::word('bene'), Kit::word('grazie'), Kit::word('piacere'), Kit::word('mi chiamo'), Kit::word('arrivederci'), Kit::word('buonanotte')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.buonasera-luca', 'Buonasera, Luca. Come stai?', 'Good evening, Luca. How are you?', [Kit::word('buonasera'), Kit::word('come stai?', 'come stai')]),
            Kit::listenType($stage, 'task.listen_type.questo-questa', 'Questo è Paolo e questa è Anna.', 'This is Paolo and this is Anna.', [Kit::form('è')], homophoneNote: 'È (is, with an accent) and e (and) sound close, and the sentence tells you which is which: è is the verb, e joins the two parts.'),
            Kit::listenType($stage, 'task.listen_type.siamo', 'Siamo Luca e Marta. Piacere.', 'We are Luca and Marta. Nice to meet you.', [Kit::form('siamo'), Kit::word('piacere')], homophoneNote: 'E (and) has no accent and sounds close to è (is); here e joins the two names.'),

            Kit::speakAnswer($stage, 'task.speak_answer.buonasera', 'Buonasera. Come stai?', 'Good evening. How are you?', [['sto', 'bene']], 'Sto bene, grazie.', [Kit::word('buonasera'), Kit::word('come stai?'), Kit::word('bene'), Kit::form('sto', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chi-sei', 'Buongiorno. Chi sei?', 'Good morning. Who are you?', [['sono', 'mi', 'chiamo']], 'Buongiorno, sono Anna.', [Kit::word('buongiorno'), Kit::form('sono')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.piacere', 'Mi chiamo Paolo. Piacere.', 'My name is Paolo. Nice to meet you.', [['piacere']], 'Piacere, Paolo.', [Kit::word('mi chiamo'), Kit::word('piacere')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chi-lui', 'Chi è lui?', 'Who is he?', [['è', 'lui', 'paolo', 'luca', 'anna', 'marta']], 'Lui è Paolo.', [Kit::form('è')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.buonanotte', 'Buonanotte, Luca. Arrivederci.', 'Good night, Luca. Goodbye.', [Kit::word('buonanotte'), Kit::word('arrivederci')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.stiamo', 'Stiamo bene, grazie.', 'We are fine, thank you.', [Kit::word('bene'), Kit::word('grazie'), Kit::form('stiamo', true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sera', 'Good evening, I am Luca.', ['Buonasera, sono Luca.', 'Buonasera, io sono Luca.', 'Buona sera, sono Luca.'], [Kit::word('buonasera', null, ['buona sera']), Kit::form('sono')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.grazie', 'Thank you, Marta. Goodbye.', ['Grazie, Marta. Arrivederci.', 'Mille grazie, Marta. Arrivederci.', 'Molte grazie, Marta. Arrivederci.'], [Kit::word('grazie'), Kit::word('arrivederci')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.sto', 'How are you? I am fine.', ['Come stai? Sto bene.', 'Come stai? Io sto bene.', 'Come stai? Sto molto bene.'], [Kit::word('come stai?', 'come stai'), Kit::word('bene'), Kit::form('sto', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.giorno', 'Good morning, my name is Marta.', ['Buongiorno, mi chiamo Marta.', 'Buon giorno, mi chiamo Marta.'], [Kit::word('buongiorno', null, ['buon giorno']), Kit::word('mi chiamo')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.loro', 'Loro ___ Paolo e Marta.', 'They are Paolo and Marta.', 'sono', Kit::form('sono'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.stai', 'Ciao, Luca. Come ___?', 'Hi, Luca. How are you?', 'stai', Kit::form('stai', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.siamo', 'Ciao, siamo Marta e Luca.', 'Hi, we are Marta and Luca.', [Kit::word('ciao'), Kit::form('siamo')], 'dictation', $set, homophoneNote: 'E (and) has no accent and sounds close to è (is); here e joins the two names.'),
            Kit::listenType($stage, 'check.a.listen_type.piacere', 'Piacere, sono Paolo.', 'Nice to meet you, I am Paolo.', [Kit::word('piacere'), Kit::form('sono')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.buonanotte', 'Buonanotte, Anna. Arrivederci.', 'Good night, Anna. Goodbye.', [Kit::word('buonanotte')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.mattina', [
                Kit::line('Anna', 'Buongiorno, Luca. Come stai?'),
                Kit::line('Luca', 'Molto bene, grazie. E tu?'),
                Kit::line('Anna', 'Bene. Luca, questo è Paolo.'),
                Kit::line('Luca', 'Ciao, Paolo. Piacere.'),
            ], [
                Kit::question('What time of day is it?', ['Morning', 'Evening', 'Night'], 'Morning'),
                Kit::question('How is Luca?', ['Fine', 'Not fine', 'The conversation does not say.'], 'Fine'),
                Kit::question('Who does Anna introduce?', ['Paolo', 'Marta', 'Nobody'], 'Paolo'),
            ], [
                Kit::question('Who says hello to Paolo?', ['Anna', 'Luca', 'Marta'], 'Luca'),
                Kit::question('Does Luca say thank you?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['Two', 'Three', 'Four'], 'Two'),
            ], [Kit::word('buongiorno'), Kit::word('come stai?'), Kit::word('bene'), Kit::word('grazie'), Kit::word('ciao'), Kit::word('piacere')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sera', 'Read the conversation.', [
                Kit::line('Marta', 'Buonasera. Mi chiamo Marta.'),
                Kit::line('Paolo', 'Ciao, Marta. Sono Paolo. Piacere.'),
                Kit::line('Marta', 'Piacere. Come stai?'),
                Kit::line('Paolo', 'Bene, grazie.'),
            ], [
                Kit::question('What is the first speaker called?', ['Anna', 'Marta', 'Luca'], 'Marta'),
                Kit::question('How is Paolo?', ['Fine', 'Not fine', 'The text does not say.'], 'Fine'),
            ], [Kit::word('buonasera'), Kit::word('mi chiamo'), Kit::word('come stai?'), Kit::word('bene')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.come-stai', 'Come stai?', 'How are you?', [['sto', 'bene']], 'Sto bene, grazie.', [Kit::word('come stai?'), Kit::word('bene'), Kit::word('grazie')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.chi-sei', 'Buonasera. Chi sei?', 'Good evening. Who are you?', [['sono', 'mi', 'chiamo']], 'Sono Marta.', [Kit::word('buonasera')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.sei-anna', 'Ciao, sei Anna?', 'Hello, are you Anna?', [['sì', 'no'], ['sono', 'anna', 'luca', 'marta', 'paolo']], 'Sì, sono Anna.', [Kit::word('ciao')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ciao-questa', 'Hello, this is Marta.', ['Ciao, questa è Marta.'], [Kit::word('ciao'), Kit::form('è')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.arrivederci', 'Goodbye, thank you.', ['Arrivederci, grazie.', 'Arrivederci, mille grazie.', 'Grazie, arrivederci.', 'Mille grazie, arrivederci.'], [Kit::word('arrivederci'), Kit::word('grazie')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sono-anna', 'I am Anna. Nice to meet you.', ['Sono Anna. Piacere.', 'Sono Anna, piacere.', 'Io sono Anna. Piacere.', 'Sono Anna. Molto piacere.'], [Kit::word('piacere'), Kit::form('sono')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.come', 'Good morning, how are you?', ['Buongiorno, come stai?', 'Buon giorno, come stai?'], [Kit::word('buongiorno', null, ['buon giorno']), Kit::word('come stai?', 'come stai')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.sta', 'Marta ___ bene, grazie.', 'Marta is fine, thank you.', 'sta', Kit::form('sta', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.siete', 'Voi ___ Anna e Paolo?', 'Are you Anna and Paolo?', 'siete', Kit::form('siete'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.sera', 'Mi chiamo Paolo. Sto bene, grazie.', 'My name is Paolo. I am fine, thank you.', [Kit::word('mi chiamo'), Kit::word('bene'), Kit::form('sto', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.notte', 'Buonasera, Anna. Questo è Luca.', 'Good evening, Anna. This is Luca.', [Kit::word('buonasera'), Kit::form('è')], 'dictation', $set, homophoneNote: 'È (is, with an accent) and e (and) sound close; here è is the verb is, so it is written with an accent.'),
            Kit::listenType($stage, 'check.b.listen_type.grazie', 'Buonanotte, Paolo. Grazie.', 'Good night, Paolo. Thank you.', [Kit::word('buonanotte'), Kit::word('grazie')], 'dictation', $set),
        ];
    }
}
