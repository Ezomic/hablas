<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class YourPersonalDetails implements UnitContent
{
    private const NUMBERS = ['um', 'uma', 'dois', 'duas', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez', 'onze', 'doze', 'treze', 'catorze', 'quinze', 'dezasseis', 'dezassete', 'dezoito', 'dezanove', 'vinte'];

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'your-personal-details';
    }

    public function words(): array
    {
        return [
            new WordData('o ano', cue: 'year', forms: ['anos'], portunolSlips: ['año'], note: 'You give your age in anos: tenho vinte anos.'),
            new WordData('o país', cue: 'country'),
            new WordData('a Holanda', cue: 'Holland (the Netherlands)', accepted: ['Holanda']),
            new WordData('Espanha', cue: 'Spain', portunolSlips: ['España']),
            new WordData('holandês', cue: 'Dutch (nationality)', forms: ['holandesa'], portunolSlips: ['holandés'], note: 'Holandês is for a man, holandesa for a woman.'),
            new WordData('o telefone', cue: 'telephone, phone', portunolSlips: ['teléfono'], note: 'A mobile phone is telemóvel in Portugal; for a number, say o número de telefone.'),
            new WordData('o número', cue: 'number'),
            new WordData('a morada', cue: 'address', portunolSlips: ['dirección']),
            new WordData('o apelido', cue: 'surname, last name', portunolSlips: ['apellido']),
            new WordData('soletrar', cue: 'to spell', forms: ['soletra'], portunolSlips: ['deletrear', 'deletrea'], note: 'Soletra is the informal command: soletra o teu apelido, por favor.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Tenho vinte anos.', 'english' => 'I am twenty years old.'],
            ['text' => 'Sou da Holanda.', 'english' => 'I am from Holland.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural European Portuguese (Portugal). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural European Portuguese (Portugal), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.anos', 'Tenho doze ___.', ['anos', 'país'], 'anos', Kit::word('o ano', 'anos'), 'You count your age in anos (years): tenho doze anos. País means country.', 'choose', 'I am twelve years old.'),
            Kit::gap($stage, 'sentences.choose_gap.tenho', '___ dez anos.', ['Tenho', 'Sou'], 'Tenho', Kit::form('tenho', true), 'In Portuguese you have your age: tenho dez anos. Sou dez anos would be like saying I am ten years, which Portuguese does not say.', 'choose', 'I am ten years old.'),
            Kit::gap($stage, 'sentences.choose_gap.tens', 'Ana, quantos anos ___?', ['tens', 'tenho'], 'tens', Kit::form('tens', true), 'You are asking Ana, so you use tens (you have). Tenho is for talking about yourself.', 'choose', 'Ana, how old are you? (informal you)'),
            Kit::gap($stage, 'sentences.choose_gap.sou-da', 'Sou ___ Holanda.', ['da', 'de'], 'da', Kit::form('sou da', true), 'Holanda takes the article a, and de plus a becomes da: sou da Holanda. Espanha takes no article, so it stays sou de Espanha.', 'choose', 'I am from Holland.'),
            Kit::gap($stage, 'sentences.choose_gap.espanha', 'O João é de ___.', ['Espanha', 'morada', 'ano'], 'Espanha', Kit::word('Espanha'), 'Espanha is a country, so it fits after é de. Morada is address and ano is year.', 'choose', 'João is from Spain.'),
            Kit::gap($stage, 'sentences.choose_gap.holandesa', 'A Ana é ___.', ['holandesa', 'holandês'], 'holandesa', Kit::word('holandês', 'holandesa'), 'Ana is a woman, so the nationality ends in -a: holandesa. Holandês is for a man.', 'choose', 'Ana is Dutch.'),

            Kit::typeGap($stage, 'sentences.type_gap.anos', 'Tenho quinze ___.', 'I am fifteen years old.', 'anos', Kit::word('o ano', 'anos')),
            Kit::typeGap($stage, 'sentences.type_gap.joao-tens', 'João, quantos anos ___?', 'João, how old are you? (informal you)', 'tens', Kit::form('tens'), 'You are talking to João, so you use tens. Age takes ter in Portuguese.'),
            Kit::typeGap($stage, 'sentences.type_gap.rui-es', 'Rui, de onde ___?', 'Rui, where are you from? (informal you)', 'és', Kit::form('és'), 'És is the tu form of ser, the verb you use for where you are from.'),
            Kit::typeGap($stage, 'sentences.type_gap.sou', '___ de Espanha.', 'I am from Spain.', 'Sou', Kit::form('sou'), 'Where you are from takes ser: sou de Espanha. Tenho would mean I have.'),
            Kit::typeGap($stage, 'sentences.type_gap.numero', 'O teu ___ de telefone, por favor.', 'Your phone number, please. (informal you)', 'número', Kit::word('o número', 'número')),

            Kit::translate($stage, 'sentences.translate.vinte', 'I am twenty years old.', ['Tenho vinte anos.', 'Eu tenho vinte anos.'], [Kit::word('o ano', 'anos'), Kit::form('tenho')]),
            Kit::translate($stage, 'sentences.translate.de-onde', 'Where are you from? (informal you)', ['De onde és?', 'De onde és tu?', 'De onde é que és?', 'És de onde?', 'Tu és de onde?'], [Kit::form('és')]),
            Kit::translate($stage, 'sentences.translate.apelido', 'Spell your surname, please. (informal you)', ['Soletra o teu apelido, por favor.', 'Por favor, soletra o teu apelido.'], [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')]),

            Kit::build($stage, 'sentences.build.sou-da', 'I am from Holland.', 'Sou da Holanda.', ['tenho'], [Kit::word('a Holanda', 'Holanda'), Kit::form('sou da')]),
            Kit::build($stage, 'sentences.build.quantos', 'How old are you? (informal you)', 'Quantos anos tens?', ['tenho'], [Kit::word('o ano', 'anos'), Kit::form('tens')]),
            Kit::build($stage, 'sentences.build.morada', 'What is your address? (informal you)', 'Qual é a tua morada?', ['és'], [Kit::word('a morada', 'morada')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vinte', 'Tenho vinte anos.', ['I am twenty years old.', 'I am twelve years old.', 'You are twenty years old.', 'I am from Spain.'], 'I am twenty years old.', [Kit::word('o ano', 'anos'), Kit::form('tenho')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.de-onde', 'De onde és?', ['Where are you from?', 'Where are you?', 'How old are you?', 'What is your surname?'], 'Where are you from?', [Kit::form('és')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.telefone', 'O meu telefone é quatro, nove, dois.', ['My phone number is four, nine, two.', 'My phone number is four, nine, three.', 'My phone number is six, nine, two.', 'My address is number four.'], 'My phone number is four, nine, two.', [Kit::word('o telefone', 'telefone')]),
            Kit::listenType($stage, 'sentences.listen_type.numero', 'O meu número é cinco, sete, três.', 'My number is five, seven, three.', [Kit::word('o número', 'número')]),
            Kit::listenType($stage, 'sentences.listen_type.holandesa', 'Ela é holandesa.', 'She is Dutch.', [Kit::word('holandês', 'holandesa')]),
            Kit::listenType($stage, 'sentences.listen_type.apelido', 'Por favor, soletra o teu apelido.', 'Please spell your surname. (informal you)', [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')]),
            Kit::listenType($stage, 'sentences.listen_type.joao-tem', 'O João tem quinze anos.', 'João is fifteen years old.', [Kit::word('o ano', 'anos'), Kit::form('tem')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.tenho', 'Tenho dezasseis anos.', 'I am sixteen years old.', [Kit::word('o ano', 'anos'), Kit::form('tenho')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.ana', 'A Ana é da Holanda.', 'Ana is from Holland.', [Kit::word('a Holanda', 'Holanda'), Kit::form('é da')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.morada', 'A morada e o telefone, por favor.', 'The address and the phone number, please.', [Kit::word('a morada', 'morada'), Kit::word('o telefone', 'telefone')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pais', 'O meu país é Espanha.', 'My country is Spain.', [Kit::word('o país', 'país'), Kit::word('Espanha')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.anos', 'Quantos anos tens?', 'How old are you?', [['tenho', 'anos'], self::NUMBERS], 'Tenho vinte anos.', [Kit::word('o ano', 'anos')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.de-onde', 'De onde és?', 'Where are you from?', [['sou', 'de', 'da'], ['holanda', 'espanha', 'holandês', 'holandesa']], 'Sou da Holanda.', [Kit::word('a Holanda', 'Holanda')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pais', 'Qual é o teu país?', 'What is your country?', [['meu', 'é', 'sou'], ['holanda', 'espanha']], 'O meu país é a Holanda.', [Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.dados', 'Read the conversation.', [
                Kit::line('Marta', 'Olá, Rui. Qual é o teu país?'),
                Kit::line('Rui', 'O meu país é a Holanda. Sou holandês.'),
                Kit::line('Marta', 'Eu sou de Espanha. Quantos anos tens?'),
                Kit::line('Rui', 'Tenho vinte anos. E tu?'),
                Kit::line('Marta', 'Tenho dezoito anos. Qual é a tua morada?'),
                Kit::line('Rui', 'A minha morada é o número doze. E o teu telefone?'),
                Kit::line('Marta', 'O meu telefone é cinco, sete, três.'),
            ], [
                Kit::question('Where is Rui from?', ['Holland', 'Spain', 'The text does not say.'], 'Holland'),
                Kit::question('How old is Marta?', ['Eighteen', 'Twenty', 'Twelve'], 'Eighteen'),
                Kit::question('What is Marta\'s phone number?', ['Five, seven, three', 'Five, seven, two', 'Twelve'], 'Five, seven, three'),
            ], [Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda'), Kit::word('holandês'), Kit::word('Espanha'), Kit::word('o ano', 'anos'), Kit::word('a morada', 'morada'), Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone')], 'read'),
            Kit::gap($stage, 'task.choose_gap.marta-tem', 'A Marta ___ dezoito anos.', ['tem', 'é'], 'tem', Kit::form('tem', true), 'Age takes ter, so a Marta tem dezoito anos. É dezoito anos would be like saying she is eighteen years, which Portuguese does not say.', 'read', 'Marta is eighteen years old.'),
            Kit::gap($stage, 'task.choose_gap.morada', 'A minha ___ é o número doze.', ['morada', 'país', 'apelido'], 'morada', Kit::word('a morada', 'morada'), 'An address has a number, so morada fits. País is country and apelido is surname.', 'read', 'My address is number twelve.'),

            Kit::transform($stage, 'task.transform.pergunta', 'Now ask a friend how old they are (informal you).', 'Tenho vinte anos.', ['Quantos anos tens?', 'Quantos anos tens tu?', 'Tu quantos anos tens?', 'Quantos anos é que tens?'], [Kit::word('o ano', 'anos'), Kit::form('tens')]),
            Kit::transform($stage, 'task.transform.ana', 'Now say it about Ana.', 'Sou da Holanda.', ['A Ana é da Holanda.', 'Ana é da Holanda.', 'É da Holanda.', 'Ela é da Holanda.'], [Kit::word('a Holanda', 'Holanda'), Kit::form('é da', true)]),
            Kit::transform($stage, 'task.transform.joao', 'Now say it about João.', 'Tenho quinze anos.', ['O João tem quinze anos.', 'João tem quinze anos.', 'Tem quinze anos.', 'Ele tem quinze anos.'], [Kit::word('o ano', 'anos'), Kit::form('tem', true)]),
            Kit::writeGuided($stage, 'task.write_guided.idade', 'Say that you are twenty years old and that you are from Holland.', ['tenho', 'anos', 'sou da', 'Holanda'], 'Tenho vinte anos. Sou da Holanda.', [
                ['forms' => ['tenho'], 'term' => null],
                ['forms' => ['anos'], 'term' => 'o ano'],
                ['forms' => ['da'], 'term' => null],
                ['forms' => ['holanda'], 'term' => 'a Holanda'],
            ], [Kit::word('o ano', 'anos'), Kit::word('a Holanda', 'Holanda'), Kit::form('tenho')]),
            Kit::writeGuided($stage, 'task.write_guided.apelido', 'Ask João to spell his surname, please (informal you).', ['soletra', 'o teu apelido', 'por favor'], 'João, soletra o teu apelido, por favor.', [
                ['forms' => ['soletra'], 'term' => 'soletrar'],
                ['forms' => ['apelido'], 'term' => 'o apelido'],
            ], [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')]),
            Kit::build($stage, 'task.build.telefone', 'What is your phone number? (informal you)', 'Qual é o teu número de telefone?', ['és', 'tenho'], [Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone')], 'write'),
            Kit::build($stage, 'task.build.ana-holanda', 'Ana is from Holland.', 'A Ana é da Holanda.', ['tem', 'anos'], [Kit::word('a Holanda', 'Holanda'), Kit::form('é da')], 'write'),
            Kit::build($stage, 'task.build.joao', 'How old is João?', 'Quantos anos tem o João?', ['sou', 'de'], [Kit::word('o ano', 'anos'), Kit::form('tem')], 'write'),
            Kit::translate($stage, 'task.translate.holanda-espanha', 'I am from Holland, but Marta is from Spain.', ['Sou da Holanda, mas a Marta é de Espanha.', 'Eu sou da Holanda, mas a Marta é de Espanha.'], [Kit::word('a Holanda', 'Holanda'), Kit::word('Espanha'), Kit::form('sou da')], 'write'),
            Kit::translate($stage, 'task.translate.telefone', 'My phone number is eight, nine, two.', ['O meu número de telefone é oito, nove, dois.', 'O meu telefone é oito, nove, dois.'], [Kit::word('o telefone', 'telefone')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.dados', [
                Kit::line('Rui', 'Olá, Ana. De onde és?'),
                Kit::line('Ana', 'Sou holandesa. O meu país é a Holanda.'),
                Kit::line('Rui', 'Eu sou de Espanha. Quantos anos tens?'),
                Kit::line('Ana', 'Tenho quinze anos.'),
                Kit::line('Rui', 'Qual é o teu número de telefone?'),
                Kit::line('Ana', 'O meu telefone é seis, dois, quatro.'),
            ], [
                Kit::question('Where is Ana from?', ['Holland', 'Spain', 'The conversation does not say.'], 'Holland'),
                Kit::question('How old is Ana?', ['Fifteen', 'Twelve', 'Twenty'], 'Fifteen'),
                Kit::question('What does Rui ask for last?', ['Ana\'s phone number', 'Ana\'s address', 'Ana\'s surname'], 'Ana\'s phone number'),
            ], [
                Kit::question('Where is Rui from?', ['Spain', 'Holland', 'The conversation does not say.'], 'Spain'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Is Ana Dutch?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda'), Kit::word('holandês', 'holandesa'), Kit::word('Espanha'), Kit::word('o ano', 'anos'), Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.marta', 'Ela tem dezassete anos.', 'She is seventeen years old.', [Kit::word('o ano', 'anos'), Kit::form('tem')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.apelido', 'Rui, qual é o teu apelido? Soletra, por favor.', 'Rui, what is your surname? Spell it, please. (informal you)', [Kit::word('o apelido', 'apelido'), Kit::word('soletrar', 'soletra')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pais-morada', 'O meu país é Espanha. A minha morada é o número oito.', 'My country is Spain. My address is number eight.', [Kit::word('o país', 'país'), Kit::word('Espanha'), Kit::word('a morada', 'morada'), Kit::word('o número', 'número')], 'listen', homophoneNote: 'The a before minha is the article a (the), not à (to the) and not há (there is).'),

            Kit::speakAnswer($stage, 'task.speak_answer.telefone', 'Qual é o teu telefone?', 'What is your phone number?', [['meu', 'é'], self::NUMBERS], 'O meu telefone é cinco, sete, três.', [Kit::word('o telefone', 'telefone')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.morada', 'Qual é a tua morada?', 'What is your address?', [['minha', 'é'], ['número', ...self::NUMBERS]], 'A minha morada é o número oito.', [Kit::word('a morada', 'morada'), Kit::word('o número', 'número')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.holandes', 'És holandês?', 'Are you Dutch?', [['sim', 'não'], ['sou', 'holandês', 'holandesa']], 'Sim, sou holandês.', [Kit::word('holandês')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.espanha', 'És de Espanha?', 'Are you from Spain?', [['sim', 'não'], ['sou', 'espanha', 'holanda', 'holandês', 'holandesa']], 'Não, sou da Holanda.', [Kit::word('Espanha'), Kit::word('a Holanda', 'Holanda')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.apelido', 'João, soletra o teu apelido, por favor.', 'João, spell your surname, please.', [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.anos-holanda', 'Tenho vinte anos e sou da Holanda.', 'I am twenty years old and I am from Holland.', [Kit::word('o ano', 'anos'), Kit::word('a Holanda', 'Holanda')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.dezanove', 'I am nineteen years old.', ['Tenho dezanove anos.', 'Eu tenho dezanove anos.'], [Kit::word('o ano', 'anos'), Kit::form('tenho', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-tens', 'Marta, how old are you? (informal you)', ['Marta, quantos anos tens?', 'Quantos anos tens, Marta?', 'Marta, quantos anos é que tens?', 'Quantos anos é que tens, Marta?'], [Kit::word('o ano', 'anos'), Kit::form('tens')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.joao-espanha', 'João is from Spain, but I am from Holland.', ['O João é de Espanha, mas sou da Holanda.', 'O João é de Espanha, mas eu sou da Holanda.', 'João é de Espanha, mas sou da Holanda.', 'João é de Espanha, mas eu sou da Holanda.'], [Kit::word('Espanha'), Kit::word('a Holanda', 'Holanda'), Kit::form('é de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.rui-apelido', 'Rui, spell your surname, please. (informal you)', ['Rui, soletra o teu apelido, por favor.', 'Por favor, Rui, soletra o teu apelido.', 'Soletra o teu apelido, por favor, Rui.'], [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.morada', 'A minha ___ é o número nove.', 'My address is number nine.', 'morada', Kit::word('a morada', 'morada'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.rui-tem', 'O Rui ___ dezassete anos.', 'Rui is seventeen years old.', 'tem', Kit::form('tem', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.holandes', 'Sou holandês e tenho vinte anos.', 'I am Dutch and I am twenty years old.', [Kit::word('holandês'), Kit::word('o ano', 'anos'), Kit::form('tenho')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pais', 'O meu país é Espanha, não a Holanda.', 'My country is Spain, not Holland.', [Kit::word('o país', 'país'), Kit::word('Espanha'), Kit::word('a Holanda', 'Holanda')], 'dictation', $set, homophoneNote: 'The a before Holanda is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.telefone', 'Sou de Espanha. O meu número de telefone é seis.', 'I am from Spain. My phone number is six.', [Kit::word('Espanha'), Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone'), Kit::form('sou de')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.holandesa', [
                Kit::line('Rui', 'Olá, Marta. És holandesa?'),
                Kit::line('Marta', 'Não, sou de Espanha. E tu?'),
                Kit::line('Rui', 'Eu sou holandês. E o teu telefone, Marta?'),
                Kit::line('Marta', 'O meu número de telefone é nove, dois, três.'),
            ], [
                Kit::question('Is Marta Dutch?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('Where is Marta from?', ['Spain', 'Holland', 'The conversation does not say.'], 'Spain'),
                Kit::question('What does Rui ask for?', ['A phone number', 'An address', 'A surname'], 'A phone number'),
            ], [
                Kit::question('Who is Dutch?', ['Rui', 'Marta', 'Both'], 'Rui'),
                Kit::question('How many people speak?', ['Two', 'One', 'Three'], 'Two'),
                Kit::question('Does Marta say her phone number?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('holandês', 'holandesa'), Kit::word('o telefone', 'telefone'), Kit::word('o número', 'número'), Kit::word('Espanha')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.morada', 'Read the conversation.', [
                Kit::line('João', 'Olá, Ana. A tua morada, por favor?'),
                Kit::line('Ana', 'A minha morada é o número oito.'),
                Kit::line('João', 'Ana, soletra o teu apelido, por favor.'),
                Kit::line('Ana', 'Sim, João.'),
            ], [
                Kit::question('What does João ask first?', ['Her address', 'Her age', 'Her phone number'], 'Her address'),
                Kit::question('What number is Ana\'s address?', ['Eight', 'Six', 'Twelve'], 'Eight'),
            ], [Kit::word('a morada', 'morada'), Kit::word('o número', 'número'), Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ana-anos', 'Quantos anos tens, Ana?', 'How old are you, Ana?', [['tenho', 'anos'], self::NUMBERS], 'Tenho dezoito anos.', [Kit::word('o ano', 'anos')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.joao-de-onde', 'João, de onde és?', 'João, where are you from?', [['sou', 'de', 'da'], ['espanha', 'holanda', 'holandês', 'holandesa']], 'Sou de Espanha.', [Kit::word('Espanha'), Kit::form('sou de')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.e-pais', 'E qual é o teu país?', 'And what is your country?', [['meu', 'é', 'sou'], ['holanda', 'espanha']], 'O meu país é a Holanda.', [Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.onze', 'I am eleven years old and I am from Spain.', ['Tenho onze anos e sou de Espanha.', 'Eu tenho onze anos e sou de Espanha.', 'Sou de Espanha e tenho onze anos.'], [Kit::word('o ano', 'anos'), Kit::word('Espanha'), Kit::form('tenho', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.morada-telefone', 'My address is number six. My phone number is five.', ['A minha morada é o número seis. O meu número de telefone é cinco.', 'A minha morada é o número seis. O meu telefone é cinco.'], [Kit::word('a morada', 'morada'), Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-holandesa', 'Ana is Dutch. Her country is Holland.', ['A Ana é holandesa. O seu país é a Holanda.', 'Ana é holandesa. O seu país é a Holanda.'], [Kit::word('holandês', 'holandesa'), Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda'), Kit::form('é')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-apelido', 'Marta, spell your surname, please. (informal you)', ['Marta, soletra o teu apelido, por favor.', 'Por favor, Marta, soletra o teu apelido.', 'Soletra o teu apelido, por favor, Marta.'], [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.rui-tens', 'Rui, quantos anos ___?', 'Rui, how old are you? (informal you)', 'tens', Kit::form('tens', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.marta-e', 'A Marta ___ da Holanda.', 'Marta is from Holland.', 'é', Kit::form('é'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.holandes', 'Sou holandês e o meu país é a Holanda. O Rui é de Espanha.', 'I am Dutch and my country is Holland. Rui is from Spain.', [Kit::word('holandês'), Kit::word('o país', 'país'), Kit::word('a Holanda', 'Holanda'), Kit::word('Espanha'), Kit::form('é de')], 'dictation', $set, homophoneNote: 'The a before Holanda is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.apelido', 'Soletra o teu apelido e a tua morada, por favor.', 'Spell your surname and your address, please. (informal you)', [Kit::word('soletrar', 'soletra'), Kit::word('o apelido', 'apelido'), Kit::word('a morada', 'morada')], 'dictation', $set, homophoneNote: 'The a before tua is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.doze', 'Tenho doze anos. O meu número de telefone é cinco.', 'I am twelve years old. My phone number is five.', [Kit::word('o ano', 'anos'), Kit::word('o número', 'número'), Kit::word('o telefone', 'telefone'), Kit::form('tenho')], 'dictation', $set),
        ];
    }
}
