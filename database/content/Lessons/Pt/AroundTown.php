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

final class AroundTown implements UnitContent
{
    private const PLACES = ['banco', 'correios', 'supermercado', 'parque', 'loja', 'igreja', 'museu', 'biblioteca', 'cidade', 'aldeia'];

    private const A_NOTE = 'The a here is the article a (the), not à (to the) and not há (there is). The article is short and weak; à and há are open.';

    private const PREP_NOTE = 'À (with an accent) means to the. It sounds like há (there is) and is easy to confuse with the article a (the), but here it is à.';

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'around-town';
    }

    public function words(): array
    {
        return [
            new WordData('o banco', cue: 'bank'),
            new WordData('os correios', cue: 'post office', portunolSlips: ['correos'], note: 'Os correios is plural in Portuguese, so it takes a plural article: vou aos correios.'),
            new WordData('o supermercado', cue: 'supermarket'),
            new WordData('o parque', cue: 'park'),
            new WordData('a loja', cue: 'shop (store)', portunolSlips: ['la tienda']),
            new WordData('a igreja', cue: 'church', portunolSlips: ['la iglesia']),
            new WordData('o museu', cue: 'museum', portunolSlips: ['el museo']),
            new WordData('a biblioteca', cue: 'library', note: 'A biblioteca is a library, where you borrow books. A bookshop is a livraria.'),
            new WordData('a cidade', cue: 'city', portunolSlips: ['la ciudad']),
            new WordData('a aldeia', cue: 'village', portunolSlips: ['el pueblo']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Vou ao banco.', 'english' => 'I am going to the bank.'],
            ['text' => 'Venho da igreja.', 'english' => 'I come from the church.'],
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

    /** @return list<string> */
    private function placesAnd(string ...$extra): array
    {
        return array_values([...$extra, ...self::PLACES]);
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $s = Stage::Sentences;
        $venho = ['venho' => 'I come'];
        $banco = Kit::word('o banco', 'banco');
        $correios = Kit::word('os correios', 'correios');
        $supermercado = Kit::word('o supermercado', 'supermercado');
        $parque = Kit::word('o parque', 'parque');
        $loja = Kit::word('a loja', 'loja');
        $igreja = Kit::word('a igreja', 'igreja');
        $museu = Kit::word('o museu', 'museu');
        $biblioteca = Kit::word('a biblioteca', 'biblioteca');
        $cidade = Kit::word('a cidade', 'cidade');
        $aldeia = Kit::word('a aldeia', 'aldeia');

        return [
            Kit::gap($s, 'sentences.choose_gap.banco', 'Vou ___ banco.', ['ao', 'à'], 'ao', Kit::form('ao', true), 'Banco is masculine, so a + o joins into ao. À goes with a feminine word.', 'choose', 'I am going to the bank.'),
            Kit::gap($s, 'sentences.choose_gap.loja', 'Vou ___ loja.', ['à', 'ao'], 'à', Kit::form('à', true), 'Loja is feminine, so a + a joins into à, with an accent. Ao is only for a masculine word.', 'choose', 'I am going to the shop.'),
            Kit::gap($s, 'sentences.choose_gap.igreja', 'Venho ___ igreja.', ['da', 'do'], 'da', Kit::form('da', true), 'Igreja is feminine, so de + a joins into da. Do is only for a masculine word.', 'choose', 'I come from the church.', $venho),
            Kit::gap($s, 'sentences.choose_gap.museu', 'Venho ___ museu.', ['do', 'da'], 'do', Kit::form('do', true), 'Museu is masculine, so de + o joins into do. Da goes with a feminine word.', 'choose', 'I come from the museum.', $venho),
            Kit::gap($s, 'sentences.choose_gap.biblioteca', 'Vou à ___.', ['biblioteca', 'museu', 'banco'], 'biblioteca', $biblioteca, 'À (a + a) goes with biblioteca, which is feminine. Museu and banco take ao.', 'choose', 'I am going to the library.'),
            Kit::gap($s, 'sentences.choose_gap.supermercado', 'Estou no ___.', ['supermercado', 'igreja', 'cidade'], 'supermercado', $supermercado, 'No (em + o) goes with supermercado, which is masculine. Igreja and cidade take na.', 'choose', 'I am in the supermarket.'),

            Kit::typeGap($s, 'sentences.type_gap.parque', 'Vou ___ parque.', 'I am going to the park.', 'ao', Kit::form('ao'), 'Parque is masculine, so a + o becomes ao.'),
            Kit::typeGap($s, 'sentences.type_gap.igreja', 'Estou na ___.', 'I am in the church.', 'igreja', $igreja),
            Kit::typeGap($s, 'sentences.type_gap.aldeia', 'Venho ___ aldeia.', 'I come from the village.', 'da', Kit::form('da'), 'Aldeia is feminine, so de + a becomes da.', glosses: $venho),
            Kit::typeGap($s, 'sentences.type_gap.correios', 'Vou aos ___.', 'I am going to the post office.', 'correios', $correios),
            Kit::typeGap($s, 'sentences.type_gap.cidade', 'Estou ___ cidade.', 'I am in the city.', 'na', Kit::form('na'), 'Cidade is feminine, so em + a becomes na.'),

            Kit::translate($s, 'sentences.translate.museu', 'I am going to the museum.', ['Vou ao museu.', 'Eu vou ao museu.'], [$museu, Kit::form('ao')]),
            Kit::translate($s, 'sentences.translate.banco', 'Are you going to the bank? (informal you)', ['Vais ao banco?', 'Tu vais ao banco?'], [$banco, Kit::form('ao')]),
            Kit::translate($s, 'sentences.translate.biblioteca', 'I am in the library.', ['Estou na biblioteca.', 'Eu estou na biblioteca.'], [$biblioteca]),

            Kit::build($s, 'sentences.build.supermercado', 'I am going to the supermarket.', 'Vou ao supermercado.', ['à'], [$supermercado, Kit::form('ao')]),
            Kit::build($s, 'sentences.build.parque', 'We are going to the park.', 'Vamos ao parque.', ['à'], [$parque, Kit::form('ao')]),
            Kit::build($s, 'sentences.build.loja', 'I am going to the shop.', 'Vou à loja.', ['ao'], [$loja, Kit::form('à')]),

            Kit::listenChoose($s, 'sentences.listen_choose.banco', 'Vou ao banco.', ['I am going to the bank.', 'I am going to the park.', 'I am in the bank.', 'I come from the bank.'], 'I am going to the bank.', [$banco, Kit::form('ao')]),
            Kit::listenChoose($s, 'sentences.listen_choose.igreja', 'Vais à igreja?', ['Are you going to the church?', 'Are you going to the museum?', 'Are you in the church?', 'Am I going to the church?'], 'Are you going to the church?', [$igreja, Kit::form('à')]),
            Kit::listenChoose($s, 'sentences.listen_choose.cidade', 'Estou na cidade.', ['I am in the city.', 'I am going to the city.', 'I am in the village.', 'I am in the park.'], 'I am in the city.', [$cidade]),
            Kit::listenType($s, 'sentences.listen_type.correios', 'Vou aos correios.', 'I am going to the post office.', [$correios]),
            Kit::listenType($s, 'sentences.listen_type.parque-aldeia', 'Estou no parque da aldeia.', 'I am in the village park.', [$parque, $aldeia, Kit::form('da')]),
            Kit::listenType($s, 'sentences.listen_type.biblioteca', 'A Marta está na biblioteca.', 'Marta is in the library.', [$biblioteca], homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'sentences.listen_type.supermercado-loja', 'Vais ao supermercado ou à loja?', 'Are you going to the supermarket or to the shop? (informal you)', [$supermercado, $loja, Kit::form('ao', true)], homophoneNote: self::PREP_NOTE),

            Kit::speakRepeat($s, 'sentences.speak_repeat.parque', 'Vou ao parque.', 'I am going to the park.', [$parque, Kit::form('ao')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.cidade', 'Vamos à cidade.', 'We are going to the city.', [$cidade, Kit::form('à')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.aldeia', 'Estou na aldeia.', 'I am in the village.', [$aldeia]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.correios-museu', 'Vou aos correios e ao museu.', 'I am going to the post office and to the museum.', [$correios, $museu, Kit::form('ao')]),
            Kit::speakAnswer($s, 'sentences.speak_answer.onde', 'Onde estás?', 'Where are you?', [['estou'], self::PLACES], 'Estou na igreja.', [$igreja]),
            Kit::speakAnswer($s, 'sentences.speak_answer.loja', 'Vais à loja?', 'Are you going to the shop?', [['sim', 'não'], $this->placesAnd('vou')], 'Sim, vou à loja.', [$loja, Kit::form('à')]),
            Kit::speakAnswer($s, 'sentences.speak_answer.banco', 'Estás no banco?', 'Are you in the bank?', [['sim', 'não'], $this->placesAnd('estou')], 'Sim, estou no banco.', [$banco]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $venho = ['venho' => 'I come'];
        $banco = Kit::word('o banco', 'banco');
        $correios = Kit::word('os correios', 'correios');
        $supermercado = Kit::word('o supermercado', 'supermercado');
        $parque = Kit::word('o parque', 'parque');
        $loja = Kit::word('a loja', 'loja');
        $igreja = Kit::word('a igreja', 'igreja');
        $museu = Kit::word('o museu', 'museu');
        $biblioteca = Kit::word('a biblioteca', 'biblioteca');
        $cidade = Kit::word('a cidade', 'cidade');
        $aldeia = Kit::word('a aldeia', 'aldeia');

        return [
            Kit::readPassage($s, 'task.read_passage.onde-estas', 'Read the conversation about where João and Ana are.', [
                Kit::line('Ana', 'João, onde estás?'),
                Kit::line('João', 'Estou na aldeia. Vou aos correios e ao banco.'),
                Kit::line('Ana', 'Estou na cidade. Venho do museu e vou à biblioteca.'),
            ], [
                Kit::question('Where is João?', ['In the village', 'In the city', 'In the library'], 'In the village'),
                Kit::question('Where does João go?', ['To the post office and the bank', 'To the museum', 'To the park'], 'To the post office and the bank'),
                Kit::question('Where does Ana come from?', ['From the museum', 'From the bank', 'From the village'], 'From the museum'),
            ], [$aldeia, $correios, $banco, $cidade, $museu, $biblioteca], 'read', glosses: $venho),
            Kit::gap($s, 'task.choose_gap.igreja-marta', 'Vou ___ igreja com a Marta.', ['à', 'ao'], 'à', Kit::form('à', true), 'Igreja is feminine, so a + a joins into à: à igreja.', 'read', 'I am going to the church with Marta.'),
            Kit::gap($s, 'task.choose_gap.aldeia-rui', 'Vou à ___ com o Rui.', ['aldeia', 'museu', 'banco'], 'aldeia', $aldeia, 'À (a + a) goes with a feminine word, and aldeia is feminine. Museu and banco take ao.', 'read', 'I am going to the village with Rui.'),

            Kit::transform($s, 'task.transform.parque', 'Say that you are in the park.', 'Vou ao parque.', ['Estou no parque.', 'Eu estou no parque.'], [$parque]),
            Kit::transform($s, 'task.transform.museu', 'Say that you come from the museum.', 'Vou ao museu.', ['Venho do museu.', 'Eu venho do museu.'], [$museu, Kit::form('do', true)], $venho),
            Kit::transform($s, 'task.transform.biblioteca', 'Ask a friend if they are going to the library (informal you).', 'Vou à biblioteca.', ['Vais à biblioteca?', 'Tu vais à biblioteca?'], [$biblioteca, Kit::form('à')]),
            Kit::writeGuided($s, 'task.write_guided.cidade-parque', 'Say that you are in the city and that you are going to the park.', ['estou', 'a cidade', 'vou', 'parque'], 'Estou na cidade e vou ao parque.', [
                ['forms' => ['cidade'], 'term' => 'a cidade'],
                ['forms' => ['ao'], 'term' => null],
                ['forms' => ['parque'], 'term' => 'o parque'],
            ], [$cidade, $parque, Kit::form('ao')]),
            Kit::writeGuided($s, 'task.write_guided.museu-supermercado', 'Say that you come from the museum and that you are going to the supermarket.', ['venho', 'museu', 'vou', 'supermercado'], 'Venho do museu e vou ao supermercado.', [
                ['forms' => ['do'], 'term' => null],
                ['forms' => ['museu'], 'term' => 'o museu'],
                ['forms' => ['ao'], 'term' => null],
                ['forms' => ['supermercado'], 'term' => 'o supermercado'],
            ], [$museu, $supermercado, Kit::form('do', true)], $venho),
            Kit::build($s, 'task.build.parque-venho', 'I come from the park.', 'Venho do parque.', ['da', 'de'], [$parque, Kit::form('do', true)], 'write', $venho),
            Kit::build($s, 'task.build.parque-igreja', 'I am going to the park and to the church.', 'Vou ao parque e à igreja.', ['da', 'no'], [$parque, $igreja, Kit::form('ao')]),
            Kit::build($s, 'task.build.marta-parque', 'Marta is in the park of the village.', 'A Marta está no parque da aldeia.', ['na', 'do'], [$parque, $aldeia, Kit::form('da')]),
            Kit::translate($s, 'task.translate.banco-correios', 'I am going to the bank and to the post office.', ['Vou ao banco e aos correios.', 'Eu vou ao banco e aos correios.', 'Vou aos correios e ao banco.', 'Eu vou aos correios e ao banco.'], [$banco, $correios, Kit::form('ao')]),
            Kit::translate($s, 'task.translate.aldeia-cidade', 'I come from the village, not from the city.', ['Venho da aldeia, não da cidade.', 'Eu venho da aldeia, não da cidade.'], [$aldeia, $cidade, Kit::form('da', true)], glosses: $venho),

            Kit::listenPassage($s, 'task.listen_passage.loja-museu', [
                Kit::line('Marta', 'Rui, onde estás?'),
                Kit::line('Rui', 'Estou na loja do museu.'),
                Kit::line('Marta', 'Vais à igreja?'),
                Kit::line('Rui', 'Não, vou ao supermercado. E tu, onde estás?'),
                Kit::line('Marta', 'Eu estou na biblioteca.'),
            ], [
                Kit::question('Where is Rui?', ['In the museum shop', 'In the library', 'In the supermarket'], 'In the museum shop'),
                Kit::question('Where is Rui going?', ['To the supermarket', 'To the church', 'To the library'], 'To the supermarket'),
                Kit::question('Where is Marta?', ['In the library', 'In the museum', 'In the church'], 'In the library'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Is Rui going to the church?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$loja, $museu, $igreja, $supermercado, $biblioteca, Kit::form('do')], 'listen'),
            Kit::listenType($s, 'task.listen_type.banco-correios', 'Vamos ao banco e aos correios.', 'We are going to the bank and to the post office.', [$banco, $correios, Kit::form('ao')], 'listen'),
            Kit::listenType($s, 'task.listen_type.parque-cidade', 'Estás no parque ou na cidade?', 'Are you in the park or in the city? (informal you)', [$parque, $cidade], 'listen'),
            Kit::listenType($s, 'task.listen_type.igreja-aldeia', 'A Ana está na igreja da aldeia.', 'Ana is in the village church.', [$igreja, $aldeia, Kit::form('da')], 'listen', homophoneNote: self::A_NOTE),

            Kit::speakAnswer($s, 'task.speak_answer.parque-loja', 'Vais ao parque ou à loja?', 'Are you going to the park or to the shop?', [['vou', 'vamos'], self::PLACES], 'Vou à loja.', [$loja, Kit::form('à')], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.marta', 'Onde está a Marta?', 'Where is Marta?', [['está', 'na', 'no'], self::PLACES], 'A Marta está na biblioteca.', [$biblioteca], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.supermercado', 'Estás no supermercado?', 'Are you in the supermarket?', [['sim', 'não'], $this->placesAnd('estou')], 'Sim, estou no supermercado.', [$supermercado], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.cidade-aldeia', 'Estás na cidade ou na aldeia?', 'Are you in the city or in the village?', [['estou'], ['cidade', 'aldeia']], 'Estou na aldeia.', [$cidade, $aldeia], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.museu-biblioteca', 'Vou ao museu e à biblioteca.', 'I am going to the museum and to the library.', [$museu, $biblioteca, Kit::form('ao')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.igreja-aldeia', 'Vamos à igreja da aldeia.', 'We are going to the church of the village.', [$igreja, $aldeia, Kit::form('da')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $banco = Kit::word('o banco', 'banco');
        $correios = Kit::word('os correios', 'correios');
        $supermercado = Kit::word('o supermercado', 'supermercado');
        $parque = Kit::word('o parque', 'parque');
        $loja = Kit::word('a loja', 'loja');
        $igreja = Kit::word('a igreja', 'igreja');
        $museu = Kit::word('o museu', 'museu');
        $biblioteca = Kit::word('a biblioteca', 'biblioteca');
        $cidade = Kit::word('a cidade', 'cidade');
        $aldeia = Kit::word('a aldeia', 'aldeia');

        return [
            Kit::translate($s, 'check.a.translate.banco-loja-parque', 'I am going to the bank, to the shop and to the park.', ['Vou ao banco, à loja e ao parque.', 'Eu vou ao banco, à loja e ao parque.'], [$banco, $loja, $parque, Kit::form('ao')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.ana-museu', 'Ana is going to the museum, to the library and to the supermarket.', ['A Ana vai ao museu, à biblioteca e ao supermercado.', 'Ana vai ao museu, à biblioteca e ao supermercado.'], [$museu, $biblioteca, $supermercado, Kit::form('à')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.igreja-aldeia', 'The church of the village is in the park.', ['A igreja da aldeia está no parque.'], [$igreja, $aldeia, $parque, Kit::form('da', true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.correios-banco', 'I am going to the post office, to the bank and to the supermarket.', ['Vou aos correios, ao banco e ao supermercado.', 'Eu vou aos correios, ao banco e ao supermercado.'], [$correios, $banco, $supermercado, Kit::form('ao')], 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.correios', 'Vamos aos ___.', 'We are going to the post office.', 'correios', $correios, null, 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.marta-cidade', 'A Marta está na ___.', 'Marta is in the city.', 'cidade', $cidade, null, 'sentences', $a),
            Kit::listenType($s, 'check.a.listen_type.loja-museu', 'A Marta está na loja do museu.', 'Marta is in the shop of the museum.', [$loja, $museu, Kit::form('do', true)], 'dictation', $a, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.a.listen_type.parque-biblioteca', 'O Rui vai ao parque e à biblioteca.', 'Rui is going to the park and to the library.', [$parque, $biblioteca, Kit::form('ao')], 'dictation', $a, homophoneNote: self::PREP_NOTE),
            Kit::listenType($s, 'check.a.listen_type.igreja-aldeia', 'Estou na igreja da aldeia.', 'I am in the church of the village.', [$igreja, $aldeia], 'dictation', $a),
            Kit::listenPassage($s, 'check.a.listen_passage.cidade', [
                Kit::line('Marta', 'Ana, estás na cidade?'),
                Kit::line('Ana', 'Sim, estou na cidade e vou à loja.'),
                Kit::line('Marta', 'E o João? Está no parque?'),
                Kit::line('Ana', 'Não, o João está na biblioteca.'),
            ], [
                Kit::question('Where is Ana?', ['In the city', 'In the park', 'In the library'], 'In the city'),
                Kit::question('Where does Ana go?', ['To the shop', 'To the park', 'To the library'], 'To the shop'),
                Kit::question('Where is João?', ['In the library', 'In the park', 'In the city'], 'In the library'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Ana', 'João'], 'Marta'),
                Kit::question('Is João in the park?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$cidade, $loja, $parque, $biblioteca], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.museu', 'Read the conversation.', [
                Kit::line('João', 'Rui, vais ao museu?'),
                Kit::line('Rui', 'Não, vou à igreja. E tu?'),
                Kit::line('João', 'Vou à biblioteca da cidade.'),
            ], [
                Kit::question('Where is Rui going?', ['To the church', 'To the museum', 'To the library'], 'To the church'),
                Kit::question('Where is João going?', ['To the library', 'To the museum', 'To the park'], 'To the library'),
            ], [$museu, $biblioteca, $cidade], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.correios', 'Vais aos correios?', 'Are you going to the post office?', [['sim', 'não'], $this->placesAnd('vou')], 'Sim, vou aos correios.', [$correios], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.supermercado-loja', 'Estás no supermercado ou na loja?', 'Are you in the supermarket or in the shop?', [['estou'], ['supermercado', 'loja']], 'Estou na loja.', [$supermercado, $loja], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.cidade', 'Estás na cidade?', 'Are you in the city?', [['sim', 'não'], $this->placesAnd('estou')], 'Sim, estou na cidade.', [$cidade], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $banco = Kit::word('o banco', 'banco');
        $correios = Kit::word('os correios', 'correios');
        $supermercado = Kit::word('o supermercado', 'supermercado');
        $parque = Kit::word('o parque', 'parque');
        $loja = Kit::word('a loja', 'loja');
        $igreja = Kit::word('a igreja', 'igreja');
        $museu = Kit::word('o museu', 'museu');
        $biblioteca = Kit::word('a biblioteca', 'biblioteca');
        $cidade = Kit::word('a cidade', 'cidade');
        $aldeia = Kit::word('a aldeia', 'aldeia');

        return [
            Kit::translate($s, 'check.b.translate.correios-igreja-museu', 'I am going to the post office, to the church and to the museum.', ['Vou aos correios, à igreja e ao museu.', 'Eu vou aos correios, à igreja e ao museu.'], [$correios, $igreja, $museu, Kit::form('ao')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.rui-biblioteca', 'Rui is going to the library, to the park and to the supermarket.', ['O Rui vai à biblioteca, ao parque e ao supermercado.', 'Rui vai à biblioteca, ao parque e ao supermercado.'], [$biblioteca, $parque, $supermercado, Kit::form('à')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.loja-aldeia', 'I am going to the shop of the village and to the bank.', ['Vou à loja da aldeia e ao banco.', 'Eu vou à loja da aldeia e ao banco.'], [$loja, $aldeia, $banco, Kit::form('da', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.cidade-igreja', 'I am in the city, not in the church.', ['Estou na cidade, não na igreja.', 'Eu estou na cidade, não na igreja.'], [$cidade, $igreja], 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.rui-biblioteca', 'O Rui está na ___.', 'Rui is in the library.', 'biblioteca', $biblioteca, null, 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.marta-parque', 'A Marta vai ___ parque.', 'Marta is going to the park.', 'ao', Kit::form('ao', true), null, 'sentences', $b),
            Kit::listenType($s, 'check.b.listen_type.loja-aldeia', 'Estou na loja da aldeia.', 'I am in the shop of the village.', [$loja, $aldeia, Kit::form('da')], 'dictation', $b),
            Kit::listenType($s, 'check.b.listen_type.parque-supermercado', 'O parque está na cidade.', 'The park is in the city.', [$parque, $cidade], 'dictation', $b),
            Kit::listenType($s, 'check.b.listen_type.banco-correios-museu', 'Vamos ao banco, aos correios e ao museu.', 'We are going to the bank, to the post office and to the museum.', [$banco, $correios, $museu, Kit::form('ao')], 'dictation', $b),
        ];
    }
}
