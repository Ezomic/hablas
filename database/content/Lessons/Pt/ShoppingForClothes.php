<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('a roupa', cue: 'clothes (clothing in general)', portunolSlips: ['la ropa'], questions: ['Is the singular "a roupa" the normal way to say "the clothes" in a shop in Portugal (as in "A roupa é barata."), or would people more often say "as roupas"?']),
            new WordData('a camisa', cue: 'shirt'),
            new WordData('as calças', cue: 'trousers', portunolSlips: ['los pantalones'], note: 'In Portugal this word is always plural and feminine: as calças são caras. The singular a calça is Brazilian.', questions: ['Is "as calças" the only answer worth accepting for "trousers" in Portugal, and is it right to leave out the singular "a calça" and the longer "as calças de ganga"?']),
            new WordData('o preço', cue: 'price', portunolSlips: ['el precio']),
            new WordData('o tamanho', cue: 'size (of clothes)', portunolSlips: ['la talla'], questions: ['Is "Que tamanho tens?" natural for "What size do you have?" in a clothes shop in Portugal, or would people say "Qual é o teu tamanho?" or "Que número calças?"']),
            new WordData('a cor', cue: 'color', portunolSlips: ['el color'], note: 'A cor is feminine in Portuguese, but el color is masculine in Spanish: a cor é bonita, not o cor.'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara', 'caros', 'caras']),
            new WordData('barato', cue: 'cheap (masculine)', forms: ['barata', 'baratos', 'baratas']),
            new WordData('experimentar', cue: 'to try on (clothes)', forms: ['experimento'], portunolSlips: ['probarse'], questions: ['Is "experimentar" the right verb for trying on clothes in Portugal, with "Vou experimentar as calças." and "Experimento a camisa." as natural sentences, or would shop talk use "provar" or "Posso experimentar?" instead?']),
            new WordData('o desconto', cue: 'discount', portunolSlips: ['el descuento'], questions: ['Is "Há desconto na roupa?" natural for asking about a discount in a Portuguese shop, or would people say "Tem desconto?" and is "Com desconto" a normal short form?']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'A camisa é cara.', 'english' => 'The shirt is expensive.'],
            ['text' => 'As calças são baratas.', 'english' => 'The trousers are cheap.'],
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
            Kit::gap($stage, 'sentences.choose_gap.camisa-cara', 'A camisa é ___.', ['cara', 'caro', 'caras'], 'cara', Kit::form('cara'), 'Camisa is feminine singular, so the adjective ends in -a: cara.', 'choose', 'The shirt is expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.preco-caro', 'O preço é ___.', ['caro', 'cara', 'caros'], 'caro', Kit::form('caro'), 'Preço is masculine singular, so the adjective ends in -o: caro.', 'choose', 'The price is high.'),
            Kit::gap($stage, 'sentences.choose_gap.calcas-baratas', 'As calças são ___.', ['baratas', 'baratos', 'barata'], 'baratas', Kit::form('baratas', true), 'Calças is feminine and plural (Spanish los pantalones is masculine), so the adjective is baratas, not baratos.', 'choose', 'The trousers are cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.cor-vermelha', 'A cor da camisa é ___.', ['vermelha', 'vermelho', 'vermelhas'], 'vermelha', Kit::form('vermelha', true), 'A cor is feminine in Portuguese (Spanish el color is masculine), so the adjective is vermelha, not vermelho.', 'choose', 'The color of the shirt is red.', ['vermelha' => 'red', 'vermelho' => 'red', 'vermelhas' => 'red']),
            Kit::gap($stage, 'sentences.choose_gap.desconto', 'Com ___, a roupa é barata.', ['desconto', 'tamanho', 'cor'], 'desconto', Kit::word('o desconto', 'desconto'), 'Com means with: a discount is what makes the clothes cheap.', 'choose', 'With a discount, the clothes are cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.vou-experimentar', 'Vou ___ a camisa.', ['experimentar', 'preço', 'desconto'], 'experimentar', Kit::word('experimentar'), 'After vou comes the infinitive. Experimentar is not reflexive in Portuguese, unlike Spanish probarse.', 'choose', 'I am going to try on the shirt.'),

            Kit::typeGap($stage, 'sentences.type_gap.camisas', 'As camisas são ___.', 'The shirts are expensive.', 'caras', Kit::form('caras'), 'Feminine plural takes -as: caras.'),
            Kit::typeGap($stage, 'sentences.type_gap.roupa', 'A roupa é ___.', 'The clothes are cheap.', 'barata', Kit::form('barata', true), 'Roupa is singular in Portuguese, so the adjective stays singular: barata. In English clothes is plural, but not here.'),
            Kit::typeGap($stage, 'sentences.type_gap.preco', 'O ___ está aqui.', 'The price is here.', 'preço', Kit::word('o preço', 'preço')),
            Kit::typeGap($stage, 'sentences.type_gap.tamanho', 'Que ___ tens?', 'What size do you have?', 'tamanho', Kit::word('o tamanho', 'tamanho')),
            Kit::typeGap($stage, 'sentences.type_gap.experimento', 'Eu ___ as calças.', 'I try on the trousers.', 'experimento', Kit::word('experimentar', 'experimento')),
            Kit::translate($stage, 'sentences.translate.camisa', 'The shirt is cheap.', ['A camisa é barata.'], [Kit::word('a camisa'), Kit::word('barato', 'barata'), Kit::form('barata')]),
            Kit::translate($stage, 'sentences.translate.calcas', 'The trousers are expensive.', ['As calças são caras.'], [Kit::word('as calças'), Kit::word('caro', 'caras'), Kit::form('caras', true)]),
            Kit::translate($stage, 'sentences.translate.experimento', 'I try on the shirt.', ['Experimento a camisa.', 'Eu experimento a camisa.'], [Kit::word('experimentar', 'experimento'), Kit::word('a camisa')]),
            Kit::build($stage, 'sentences.build.camisa', 'The shirt is not expensive.', 'A camisa não é cara.', ['caro'], [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::build($stage, 'sentences.build.calcas', 'The trousers are not cheap.', 'As calças não são baratas.', ['baratos'], [Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)]),
            Kit::build($stage, 'sentences.build.desconto', 'I have a discount.', 'Tenho um desconto.', ['uma'], [Kit::word('o desconto', 'desconto')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.camisa', 'A camisa é cara aqui.', ['The shirt is expensive here.', 'The shirt is cheap here.', 'The trousers are expensive here.', 'The clothes are expensive here.'], 'The shirt is expensive here.', [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.preco', 'Qual é o preço?', ['What is the price?', 'What is the size?', 'What color is it?', 'Is there a discount?'], 'What is the price?', [Kit::word('o preço')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.calcas', 'As calças são muito baratas.', ['The trousers are very cheap.', 'The trousers are very expensive.', 'The shirt is very cheap.', 'The clothes are very cheap.'], 'The trousers are very cheap.', [Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)]),
            Kit::listenType($stage, 'sentences.listen_type.tamanho', 'O tamanho e o preço estão aqui.', 'The size and the price are here.', [Kit::word('o tamanho', 'tamanho'), Kit::word('o preço', 'preço')]),
            Kit::listenType($stage, 'sentences.listen_type.experimento', 'Experimento a camisa e as calças.', 'I try on the shirt and the trousers.', [Kit::word('experimentar', 'experimento'), Kit::word('a camisa'), Kit::word('as calças')], homophoneNote: 'The a before camisa is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.roupa', 'A roupa é muito barata.', 'The clothes are very cheap.', [Kit::word('a roupa'), Kit::word('barato', 'barata'), Kit::form('barata', true)], homophoneNote: 'The first a is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.cor', 'A cor da camisa é vermelha.', 'The color of the shirt is red.', [Kit::word('a cor', 'cor'), Kit::word('a camisa', 'camisa'), Kit::form('vermelha', true)], homophoneNote: 'The first a is the article a (the), not à (to the) and not há (there is).'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.calcas', 'As calças aqui são caras.', 'The trousers here are expensive.', [Kit::word('as calças'), Kit::word('caro', 'caras'), Kit::form('caras', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tamanho', 'Que tamanho tens, Ana?', 'What size do you have, Ana?', [Kit::word('o tamanho', 'tamanho')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cor', 'De que cor é a camisa?', 'What color is the shirt?', [Kit::word('a cor', 'cor'), Kit::word('a camisa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.experimentar', 'Vou experimentar as calças.', 'I am going to try on the trousers.', [Kit::word('experimentar'), Kit::word('as calças')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.camisa', 'A camisa é cara?', 'Is the shirt expensive?', [['sim', 'não', 'é'], ['cara', 'barata', 'é']], 'Sim, a camisa é cara.', [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.desconto', 'Há desconto na roupa?', 'Is there a discount on the clothes?', [['sim', 'não', 'há'], ['desconto', 'roupa', 'há']], 'Sim, há desconto na roupa.', [Kit::word('o desconto', 'desconto'), Kit::word('a roupa', 'roupa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.roupa', 'A roupa é cara ou barata?', 'Is the clothing expensive or cheap?', [['é', 'cara', 'barata'], ['roupa', 'cara', 'barata']], 'A roupa é barata.', [Kit::word('a roupa'), Kit::word('barato', 'barata'), Kit::form('barata', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.loja', 'Read the conversation in the shop.', [
                Kit::line('Ana', 'Boa tarde. Qual é o preço da camisa?'),
                Kit::line('Empregada', 'A camisa é cara, mas as calças são baratas.'),
                Kit::line('Ana', 'Vou experimentar a camisa. Há desconto na roupa?'),
                Kit::line('Empregada', 'Sim, há desconto. Que tamanho tem?'),
                Kit::line('Ana', 'O meu tamanho é pequeno. De que cor é a camisa?'),
                Kit::line('Empregada', 'A cor é azul.'),
            ], [
                Kit::question('Which clothes are expensive?', ['The shirt', 'The trousers', 'All the clothes'], 'The shirt'),
                Kit::question('Is there a discount?', ['Yes, on the clothes.', 'No, there is not.', 'The text does not say.'], 'Yes, on the clothes.'),
                Kit::question('What color is the shirt?', ['Green', 'Blue', 'Red'], 'Blue'),
            ], [Kit::word('o preço'), Kit::word('a camisa'), Kit::word('caro'), Kit::word('as calças'), Kit::word('barato'), Kit::word('experimentar'), Kit::word('o desconto'), Kit::word('a roupa'), Kit::word('o tamanho'), Kit::word('a cor')], 'read', null, ['pequeno' => 'small', 'azul' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.roupa-verde', 'A roupa é ___.', ['verde', 'verdes'], 'verde', Kit::form('verde', true), 'Roupa is singular, and verde ends in -e, so it has one form for masculine and feminine: verde.', 'read', 'The clothes are green.', ['verde' => 'green', 'verdes' => 'green']),
            Kit::gap($stage, 'task.choose_gap.camisas-grandes', 'As camisas são ___.', ['grandes', 'grande'], 'grandes', Kit::form('grandes'), 'Grande has one form for masculine and feminine, but it still takes -s in the plural: grandes.', 'read', 'The shirts are big.', ['grandes' => 'big', 'grande' => 'big']),

            Kit::transform($stage, 'task.transform.camisas', 'Make it plural.', 'A camisa é cara.', ['As camisas são caras.'], [Kit::word('a camisa', 'camisas'), Kit::word('caro', 'caras'), Kit::form('caras')]),
            Kit::transform($stage, 'task.transform.calcas', 'Talk about the trousers instead.', 'A camisa é barata.', ['As calças são baratas.'], [Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)]),
            Kit::transform($stage, 'task.transform.camisa', 'Talk about the shirt instead.', 'O preço é caro.', ['A camisa é cara.'], [Kit::word('a camisa'), Kit::form('cara')]),
            Kit::writeGuided($stage, 'task.write_guided.camisa', 'Say that the shirt is expensive and ask whether there is a discount.', ['camisa', 'cara', 'desconto'], 'A camisa é cara. Há desconto?', [
                ['forms' => ['camisa'], 'term' => 'a camisa'],
                ['forms' => ['cara'], 'term' => 'caro'],
                ['forms' => ['desconto'], 'term' => 'o desconto'],
            ], [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::word('o desconto')]),
            Kit::writeGuided($stage, 'task.write_guided.cor', 'Ask what color the trousers are and say that your size is small.', ['cor', 'calças', 'tamanho'], 'De que cor são as calças? O meu tamanho é pequeno.', [
                ['forms' => ['cor'], 'term' => 'a cor'],
                ['forms' => ['calças'], 'term' => 'as calças'],
                ['forms' => ['tamanho'], 'term' => 'o tamanho'],
            ], [Kit::word('a cor'), Kit::word('as calças'), Kit::word('o tamanho')], ['pequeno' => 'small']),
            Kit::build($stage, 'task.build.roupa', 'The clothes are not expensive.', 'A roupa não é cara.', ['são', 'caro'], [Kit::word('a roupa'), Kit::word('caro', 'cara'), Kit::form('cara', true)], 'write'),
            Kit::build($stage, 'task.build.experimento', 'I try on the trousers and the shirt.', 'Experimento as calças e a camisa.', ['uma', 'é'], [Kit::word('experimentar', 'experimento'), Kit::word('as calças'), Kit::word('a camisa')], 'write'),
            Kit::build($stage, 'task.build.preco', 'The price is here and the size is there.', 'O preço está aqui e o tamanho está ali.', ['são', 'estão'], [Kit::word('o preço'), Kit::word('o tamanho')], 'write'),
            Kit::translate($stage, 'task.translate.baratas', 'The shirt and the trousers are cheap.', ['A camisa e as calças são baratas.'], [Kit::word('a camisa'), Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)], 'write'),
            Kit::translate($stage, 'task.translate.desconto', 'Is there a discount on the trousers?', ['Há desconto nas calças?', 'Há um desconto nas calças?', 'As calças têm desconto?', 'Têm desconto as calças?'], [Kit::word('o desconto', 'desconto'), Kit::word('as calças', 'calças')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.loja', [
                Kit::line('João', 'Boa tarde. Qual é o preço das calças?'),
                Kit::line('Empregada', 'As calças são baratas. Há desconto na roupa.'),
                Kit::line('João', 'Muito bem. Vou experimentar as calças. Obrigado.'),
            ], [
                Kit::question('What does João ask about?', ['The price of the trousers', 'The size of the shirt', 'The color of the clothes'], 'The price of the trousers'),
                Kit::question('Are the trousers expensive?', ['Yes', 'No, they are cheap.', 'The conversation does not say.'], 'No, they are cheap.'),
                Kit::question('What does João try on?', ['The shirt', 'The trousers', 'Nothing'], 'The trousers'),
            ], [
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who speaks first?', ['João', 'The shop assistant', 'Nobody'], 'João'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o preço'), Kit::word('as calças'), Kit::word('barato'), Kit::word('o desconto'), Kit::word('a roupa'), Kit::word('experimentar')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.desconto', 'Com o desconto, as calças são baratas.', 'With the discount, the trousers are cheap.', [Kit::word('o desconto', 'desconto'), Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.preco', 'Qual é o preço da roupa?', 'What is the price of the clothes?', [Kit::word('o preço', 'preço'), Kit::word('a roupa', 'roupa')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.cor', 'De que cor são as calças?', 'What color are the trousers?', [Kit::word('a cor', 'cor'), Kit::word('as calças')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.calcas', 'As calças são caras ou baratas?', 'Are the trousers expensive or cheap?', [['são', 'caras', 'baratas'], ['calças', 'caras', 'baratas']], 'As calças são baratas.', [Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.desconto', 'Tens um desconto?', 'Do you have a discount?', [['sim', 'não', 'tenho'], ['desconto', 'tenho']], 'Sim, tenho um desconto.', [Kit::word('o desconto', 'desconto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cor', 'Qual é a cor da roupa?', 'What color is the clothing?', [['cor', 'é'], ['verde', 'roupa', 'é']], 'A cor é verde.', [Kit::word('a cor'), Kit::word('a roupa', 'roupa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.preco', 'Onde está o preço?', 'Where is the price?', [['está', 'aqui', 'ali', 'na'], ['preço', 'camisa', 'calças', 'roupa', 'aqui', 'ali']], 'O preço está aqui.', [Kit::word('o preço', 'preço')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.tamanho', 'A cor e o tamanho estão aqui.', 'The color and the size are here.', [Kit::word('a cor'), Kit::word('o tamanho')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.experimento', 'Experimento a camisa, mas é cara.', 'I try on the shirt, but it is expensive.', [Kit::word('experimentar', 'experimento'), Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.camisa', 'The shirt is very expensive here.', ['A camisa é muito cara aqui.', 'Aqui a camisa é muito cara.', 'A camisa aqui é muito cara.'], [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.roupa', 'The clothes are cheap here.', ['A roupa é barata aqui.', 'Aqui a roupa é barata.', 'A roupa aqui é barata.'], [Kit::word('a roupa'), Kit::word('barato', 'barata'), Kit::form('barata', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.experimento', 'I try on the trousers here.', ['Experimento as calças aqui.', 'Aqui experimento as calças.', 'Experimento aqui as calças.', 'Eu experimento as calças aqui.', 'Aqui eu experimento as calças.'], [Kit::word('experimentar', 'experimento'), Kit::word('as calças')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tamanho', 'What is the size of the shirt?', ['Qual é o tamanho da camisa?', 'Que tamanho tem a camisa?'], [Kit::word('o tamanho', 'tamanho'), Kit::word('a camisa', 'camisa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.calcas', 'As calças são muito ___.', 'The trousers are very expensive.', 'caras', Kit::form('caras', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.camisas', 'As camisas não são ___.', 'The shirts are not cheap.', 'baratas', Kit::form('baratas'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.cor', 'A cor e o preço estão aqui.', 'The color and the price are here.', [Kit::word('a cor', 'cor'), Kit::word('o preço', 'preço')], 'dictation', $set, homophoneNote: 'The first a is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.desconto', 'Com desconto, a camisa é barata.', 'With a discount, the shirt is cheap.', [Kit::word('o desconto', 'desconto'), Kit::word('a camisa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'dictation', $set, homophoneNote: 'The a before camisa is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.camisas', 'As camisas são muito caras.', 'The shirts are very expensive.', [Kit::word('a camisa', 'camisas'), Kit::word('caro', 'caras'), Kit::form('caras')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.camisa', [
                Kit::line('Marta', 'Bom dia. A camisa é muito cara?'),
                Kit::line('Empregada', 'Não, é barata. As calças são caras, mas há desconto.'),
                Kit::line('Marta', 'Muito bem. Vou experimentar a camisa e as calças.'),
            ], [
                Kit::question('What does Marta ask about?', ['The shirt', 'The trousers', 'The size'], 'The shirt'),
                Kit::question('Is the shirt expensive?', ['Yes', 'No, it is cheap.', 'The conversation does not say.'], 'No, it is cheap.'),
                Kit::question('What does Marta try on?', ['The shirt', 'The trousers', 'The shirt and the trousers'], 'The shirt and the trousers'),
            ], [
                Kit::question('Are the trousers expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Who speaks first?', ['Marta', 'The shop assistant', 'Nobody'], 'Marta'),
            ], [Kit::word('a camisa'), Kit::word('caro'), Kit::word('barato'), Kit::word('as calças'), Kit::word('o desconto'), Kit::word('experimentar')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.roupa', 'Read the conversation.', [
                Kit::line('João', 'Boa tarde. Há roupa barata aqui?'),
                Kit::line('Empregada', 'Sim, a roupa é barata. As calças têm desconto.'),
                Kit::line('João', 'Muito bem. Qual é o tamanho?'),
            ], [
                Kit::question('Is the clothing cheap?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Which clothes have a discount?', ['The shirts', 'The trousers', 'All the clothes'], 'The trousers'),
            ], [Kit::word('a roupa'), Kit::word('barato'), Kit::word('o desconto'), Kit::word('as calças'), Kit::word('o tamanho')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.desconto', 'Há desconto?', 'Is there a discount?', [['sim', 'não', 'há'], ['desconto', 'há']], 'Sim, há desconto.', [Kit::word('o desconto', 'desconto')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.roupa', 'Onde está a roupa?', 'Where are the clothes?', [['está', 'aqui', 'ali'], ['roupa', 'aqui', 'ali']], 'A roupa está aqui.', [Kit::word('a roupa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.calcas', 'As calças são caras aqui?', 'Are the trousers expensive here?', [['sim', 'não', 'são'], ['caras', 'baratas', 'calças']], 'Não, as calças são baratas.', [Kit::word('as calças'), Kit::word('barato', 'baratas')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.calcas', 'The trousers are very cheap here.', ['As calças são muito baratas aqui.', 'Aqui as calças são muito baratas.', 'As calças aqui são muito baratas.'], [Kit::word('as calças'), Kit::word('barato', 'baratas'), Kit::form('baratas', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.camisa', 'The shirt is not expensive here.', ['A camisa não é cara aqui.', 'Aqui a camisa não é cara.', 'A camisa aqui não é cara.'], [Kit::word('a camisa'), Kit::word('caro', 'cara'), Kit::form('cara')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.preco', 'The price of the clothes is not expensive.', ['O preço da roupa não é caro.'], [Kit::word('o preço'), Kit::word('a roupa', 'roupa'), Kit::form('caro')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.experimento', 'I try on the shirt here.', ['Experimento a camisa aqui.', 'Aqui experimento a camisa.', 'Experimento aqui a camisa.', 'Eu experimento a camisa aqui.', 'Aqui eu experimento a camisa.'], [Kit::word('experimentar', 'experimento'), Kit::word('a camisa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.camisas', 'Aqui as camisas são ___.', 'The shirts here are expensive.', 'caras', Kit::form('caras'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.roupa', 'A roupa não é ___.', 'The clothes are not cheap.', 'barata', Kit::form('barata', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.cor', 'De que cor são as camisas?', 'What color are the shirts?', [Kit::word('a cor', 'cor'), Kit::word('a camisa', 'camisas')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.desconto', 'Com o desconto, a roupa é barata.', 'With the discount, the clothes are cheap.', [Kit::word('o desconto', 'desconto'), Kit::word('a roupa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'dictation', $set, homophoneNote: 'The a before roupa is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.tamanho', 'Que tamanho tem a senhora?', 'What size do you have, madam?', [Kit::word('o tamanho', 'tamanho')], 'dictation', $set, homophoneNote: 'The a before senhora is the article a (the), not à (to the) and not há (there is).'),
        ];
    }
}
