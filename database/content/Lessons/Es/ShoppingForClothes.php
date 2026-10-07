<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingForClothes implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('la ropa', cue: 'clothes'),
            new WordData('la camisa', cue: 'shirt'),
            new WordData('los pantalones', cue: 'trousers', accepted: ['el pantalón']),
            new WordData('el precio', cue: 'price'),
            new WordData('la talla', cue: 'size (of clothes)'),
            new WordData('el color', cue: 'color'),
            new WordData('caro', cue: 'expensive (masculine)', forms: ['cara']),
            new WordData('barato', cue: 'cheap (masculine)', forms: ['barata']),
            new WordData('probarse', cue: 'to try on (clothes)', forms: ['me pruebo']),
            new WordData('el descuento', cue: 'discount'),
            new WordData('¿qué?', cue: 'what? (asking about a thing)'),
            new WordData('¿cuánto?', cue: 'how much? (asking an amount or a price)'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'La camisa es cara.', 'english' => 'The shirt is expensive.'],
            ['text' => 'Los pantalones son baratos.', 'english' => 'The trousers are cheap.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (dictionary pass)', '2026-10-01', 'Sources: RAE excerpts via search (dle.rae.es blocked direct fetch), WordReference. los pantalones with el pantalón accepted; me pruebo is a finite form so it is definitely wrong against the infinitive cue. No data fixes. Open questions answered and removed. The seeder gloss pants (American) is outside this file.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-06', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-06', 'Released on the owner\'s instruction on 2026-10-06, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.camisa-cara', 'La camisa es ___.', ['cara', 'caro', 'caras'], 'cara', Kit::form('cara'), 'Camisa is feminine singular, so the adjective ends in -a: cara.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.pantalon-blanco', 'El pantalón es ___.', ['blanco', 'blanca', 'blancos'], 'blanco', Kit::form('blanco'), 'Pantalón is masculine singular, so the adjective ends in -o: blanco.', 'choose', glosses: ['blanco' => 'white', 'blanca' => 'white', 'blancos' => 'white']),
            Kit::gap($stage, 'sentences.choose_gap.pantalones-baratos', 'Los pantalones son ___.', ['baratos', 'barato', 'baratas'], 'baratos', Kit::form('baratos', true), 'Pantalones is masculine and plural even though it ends in -es, so the adjective is baratos, not the singular barato.', 'choose', 'The trousers are cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.descuento', 'La camisa es cara. ___ descuento, es barata.', ['Con', 'Sin'], 'Con', Kit::word('el descuento', 'descuento'), 'Con means with and sin means without: a discount is what makes the shirt cheap, so con fits.', 'choose', 'The shirt is expensive. With a discount, it is cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.ropa-barata', 'La ropa es ___.', ['barata', 'baratas', 'barato'], 'barata', Kit::form('barata', true), 'Ropa is a singular feminine noun, so the adjective stays singular: barata. In English clothes is plural, but not in Spanish.', 'choose', 'The clothes are cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.color', '¿De qué ___ es la camisa?', ['color', 'precio', 'descuento'], 'color', Kit::word('el color', 'color'), 'Asking about the color of something uses de qué color.', 'choose', 'What color is the shirt?'),

            Kit::typeGap($stage, 'sentences.type_gap.camisa-roja', 'La camisa es ___. (rojo)', 'The shirt is red.', 'roja', Kit::form('roja'), 'Camisa is feminine, so rojo becomes roja.', glosses: ['rojo' => 'red']),
            Kit::typeGap($stage, 'sentences.type_gap.pantalones', 'Los pantalones son ___.', 'The trousers are cheap.', 'baratos', Kit::form('baratos', true), 'Pantalones is masculine and plural, so the adjective is baratos.'),
            Kit::typeGap($stage, 'sentences.type_gap.precio', 'El ___ está aquí.', 'The price is here.', 'precio', Kit::word('el precio', 'precio')),
            Kit::typeGap($stage, 'sentences.type_gap.camisas', 'Las camisas son ___.', 'The shirts are expensive.', 'caras', Kit::form('caras'), 'Feminine plural takes -as: caras.'),
            Kit::typeGap($stage, 'sentences.type_gap.me-pruebo', '___ la camisa.', 'I try on the shirt.', 'Me pruebo', Kit::word('probarse', 'me pruebo')),
            Kit::translate($stage, 'sentences.translate.camisa', 'The shirt is cheap.', ['La camisa es barata.'], [Kit::word('la camisa'), Kit::word('barato', 'barata'), Kit::form('barata')]),
            Kit::translate($stage, 'sentences.translate.pantalones', 'The trousers are expensive.', ['Los pantalones son caros.'], [Kit::word('los pantalones'), Kit::word('caro', 'caros'), Kit::form('caros', true)]),
            Kit::translate($stage, 'sentences.translate.me-pruebo', 'I try on the trousers.', ['Me pruebo los pantalones.', 'Yo me pruebo los pantalones.'], [Kit::word('probarse', 'me pruebo'), Kit::word('los pantalones')]),
            Kit::build($stage, 'sentences.build.camisa', 'The shirt is not expensive.', 'La camisa no es cara.', ['caro'], [Kit::word('la camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::build($stage, 'sentences.build.pantalones', 'The trousers are not cheap.', 'Los pantalones no son baratos.', ['barato'], [Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)]),
            Kit::build($stage, 'sentences.build.descuento', 'How much is the discount?', '¿Cuánto es el descuento?', ['una'], [Kit::word('¿cuánto?', 'cuánto'), Kit::word('el descuento', 'descuento')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.camisa', 'La camisa es cara.', ['The shirt is expensive.', 'The shirt is cheap.', 'The trousers are expensive.', 'The clothes are expensive.'], 'The shirt is expensive.', [Kit::word('la camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.precio', '¿Cuál es el precio?', ['What is the price?', 'What is the size?', 'What color is it?', 'Is there a discount?'], 'What is the price?', [Kit::word('el precio')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.pantalones', 'Los pantalones son baratos.', ['The trousers are cheap.', 'The trousers are expensive.', 'The shirt is cheap.', 'The clothes are cheap.'], 'The trousers are cheap.', [Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)]),
            Kit::listenType($stage, 'sentences.listen_type.talla', '¿Qué talla tienes?', 'What size are you? (to a friend)', [Kit::word('¿qué?', 'qué'), Kit::word('la talla', 'talla')]),
            Kit::listenType($stage, 'sentences.listen_type.me-pruebo', 'Me pruebo la camisa.', 'I try on the shirt.', [Kit::word('probarse', 'me pruebo'), Kit::word('la camisa')]),
            Kit::listenType($stage, 'sentences.listen_type.ropa', 'La ropa es muy barata.', 'The clothes are very cheap.', [Kit::word('la ropa'), Kit::word('barato', 'barata'), Kit::form('barata', true)]),
            Kit::listenType($stage, 'sentences.listen_type.color', '¿De qué color es la camisa?', 'What color is the shirt?', [Kit::word('¿qué?', 'qué'), Kit::word('el color', 'color'), Kit::word('la camisa')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.pantalones', 'Los pantalones son caros.', 'The trousers are expensive.', [Kit::word('los pantalones'), Kit::word('caro', 'caros'), Kit::form('caros', true)]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.talla', '¿Qué talla tiene usted?', 'What size are you? (formal)', [Kit::word('¿qué?', 'qué'), Kit::word('la talla', 'talla')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cuanto', '¿Cuánto es la camisa?', 'How much is the shirt?', [Kit::word('¿cuánto?', 'cuánto'), Kit::word('la camisa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.me-pruebo', 'Me pruebo los pantalones.', 'I try on the trousers.', [Kit::word('probarse', 'me pruebo'), Kit::word('los pantalones')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.camisa', '¿Es cara la camisa?', 'Is the shirt expensive?', [['sí', 'no', 'es', 'cara', 'barata'], ['cara', 'barata']], 'Sí, la camisa es cara.', [Kit::word('la camisa'), Kit::word('caro', 'cara'), Kit::form('cara')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.descuento', '¿Tienes un descuento?', 'Do you have a discount?', [['sí', 'no', 'tengo'], ['descuento']], 'Sí, tengo un descuento.', [Kit::word('el descuento', 'descuento')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ropa', '¿Es barata la ropa?', 'Is the clothing cheap?', [['sí', 'no', 'es', 'barata', 'cara'], ['barata', 'cara']], 'Sí, la ropa es barata.', [Kit::word('la ropa'), Kit::word('barato', 'barata'), Kit::form('barata', true)]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.tienda', 'Read the conversation in the shop.', [
                Kit::line('Ana', 'Buenas tardes. ¿Cuál es el precio de la camisa?'),
                Kit::line('Dependiente', 'La camisa es cara, pero los pantalones son baratos.'),
                Kit::line('Ana', 'Me pruebo la camisa. ¿Hay descuento en la ropa?'),
                Kit::line('Dependiente', 'Sí, hay descuento. ¿Qué talla tiene?'),
                Kit::line('Ana', 'Mi talla es pequeña. ¿De qué color es la camisa?'),
                Kit::line('Dependiente', 'El color es azul.'),
            ], [
                Kit::question('Which clothes are expensive?', ['The shirt', 'The trousers', 'All the clothes'], 'The shirt'),
                Kit::question('Is there a discount?', ['Yes, on the clothes.', 'No, there is not.', 'The text does not say.'], 'Yes, on the clothes.'),
                Kit::question('What color is the shirt?', ['Green', 'Blue', 'Red'], 'Blue'),
            ], [Kit::word('el precio'), Kit::word('la camisa'), Kit::word('caro'), Kit::word('los pantalones'), Kit::word('barato'), Kit::word('probarse'), Kit::word('el descuento'), Kit::word('la ropa'), Kit::word('la talla'), Kit::word('el color'), Kit::word('¿qué?', 'qué')], 'read', glosses: ['pequeña' => 'small', 'azul' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.camisa-verde', 'La camisa es ___.', ['verde', 'verdes'], 'verde', Kit::form('verde', true), 'Adjectives that end in -e, like verde, have one form for masculine and feminine, so verde fits la camisa. Verdes would be plural.', 'read', 'The shirt is green.', ['verde' => 'green', 'verdes' => 'green']),
            Kit::gap($stage, 'task.choose_gap.pantalon-grande', 'Los pantalones son ___.', ['grandes', 'grande'], 'grandes', Kit::form('grandes', true), 'Pantalones is plural, and grande ends in -e, so the plural just adds -s: grandes.', 'read', 'The trousers are big.', ['grande' => 'big', 'grandes' => 'big']),

            Kit::transform($stage, 'task.transform.camisas', 'Make it plural.', 'La camisa es cara.', ['Las camisas son caras.'], [Kit::word('la camisa', 'camisas'), Kit::word('caro', 'caras'), Kit::form('caras')]),
            Kit::transform($stage, 'task.transform.pantalon', 'Make it plural.', 'El pantalón es barato.', ['Los pantalones son baratos.'], [Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)]),
            Kit::transform($stage, 'task.transform.negra', 'Talk about the shirt instead.', 'El pantalón es negro.', ['La camisa es negra.'], [Kit::word('la camisa'), Kit::form('negra')], ['negro' => 'black', 'negra' => 'black']),
            Kit::writeGuided($stage, 'task.write_guided.camisa', 'Say that the shirt is expensive and ask whether there is a discount.', ['camisa', 'cara', 'descuento'], 'La camisa es cara. ¿Hay descuento?', [
                ['forms' => ['camisa'], 'term' => 'la camisa'],
                ['forms' => ['cara'], 'term' => 'caro'],
                ['forms' => ['descuento'], 'term' => 'el descuento'],
            ], [Kit::word('la camisa'), Kit::word('caro', 'cara'), Kit::word('el descuento')]),
            Kit::writeGuided($stage, 'task.write_guided.color', 'Ask what color the trousers are and say that your size is small.', ['color', 'pantalones', 'talla'], '¿De qué color son los pantalones? Mi talla es pequeña.', [
                ['forms' => ['color'], 'term' => 'el color'],
                ['forms' => ['pantalones', 'pantalón'], 'term' => 'los pantalones'],
                ['forms' => ['talla'], 'term' => 'la talla'],
            ], [Kit::word('¿qué?', 'qué'), Kit::word('el color'), Kit::word('los pantalones'), Kit::word('la talla')], ['pequeña' => 'small']),
            Kit::build($stage, 'task.build.ropa', 'The clothes are not expensive.', 'La ropa no es cara.', ['son', 'caro'], [Kit::word('la ropa'), Kit::word('caro', 'cara'), Kit::form('cara', true)]),
            Kit::build($stage, 'task.build.me-pruebo', 'I try on the shirt and the trousers.', 'Me pruebo la camisa y los pantalones.', ['las', 'es'], [Kit::word('probarse', 'me pruebo'), Kit::word('la camisa'), Kit::word('los pantalones')]),
            Kit::build($stage, 'task.build.precio', 'The price is here, on the shirt.', 'El precio está aquí, en la camisa.', ['es', 'las'], [Kit::word('el precio'), Kit::word('la camisa')]),
            Kit::translate($stage, 'task.translate.precio-talla', 'The price and the size are here.', ['El precio y la talla están aquí.', 'Aquí están el precio y la talla.'], [Kit::word('el precio'), Kit::word('la talla')]),
            Kit::translate($stage, 'task.translate.descuento', 'Is there a discount on the clothes?', ['¿Hay descuento en la ropa?', '¿Hay un descuento en la ropa?'], [Kit::word('el descuento', 'descuento'), Kit::word('la ropa')]),

            Kit::listenPassage($stage, 'task.listen_passage.tienda', [
                Kit::line('Pablo', 'Hola. ¿Cuánto es la camisa?'),
                Kit::line('Dependiente', 'Es barata. Y hay un descuento.'),
                Kit::line('Pablo', 'Muy bien. ¿Y los pantalones?'),
                Kit::line('Dependiente', 'Los pantalones son caros.'),
                Kit::line('Pablo', 'Me pruebo la camisa. Gracias.'),
            ], [
                Kit::question('Is the shirt expensive?', ['Yes', 'No, it is cheap.', 'The conversation does not say.'], 'No, it is cheap.'),
                Kit::question('What does Pablo ask about after the shirt?', ['The trousers', 'The size', 'The color'], 'The trousers'),
                Kit::question('What does Pablo try on?', ['The trousers', 'The shirt', 'Nothing'], 'The shirt'),
            ], [
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Are the trousers expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la camisa'), Kit::word('caro'), Kit::word('barato'), Kit::word('el descuento'), Kit::word('los pantalones'), Kit::word('probarse'), Kit::word('¿cuánto?', 'cuánto')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.descuento', 'Con el descuento, los pantalones son baratos.', 'With the discount, the trousers are cheap.', [Kit::word('el descuento', 'descuento'), Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.precio', '¿Cuánto es la camisa?', 'How much is the shirt?', [Kit::word('¿cuánto?', 'cuánto'), Kit::word('la camisa')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.color', '¿De qué color son los pantalones?', 'What color are the trousers?', [Kit::word('¿qué?', 'qué'), Kit::word('el color', 'color'), Kit::word('los pantalones')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.pantalones', 'Los pantalones, ¿son caros o baratos?', 'Are the trousers expensive or cheap?', [['son', 'pantalones', 'caros', 'baratos'], ['caros', 'baratos']], 'Los pantalones son baratos.', [Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.descuento', '¿Hay descuento en la ropa?', 'Is there a discount on the clothes?', [['sí', 'no', 'hay'], ['descuento']], 'Sí, hay descuento en la ropa.', [Kit::word('el descuento', 'descuento'), Kit::word('la ropa')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.camisa', '¿La camisa es cara o barata?', 'Is the shirt expensive or cheap?', [['es', 'camisa', 'cara', 'barata'], ['cara', 'barata']], 'La camisa es barata.', [Kit::word('la camisa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.precio', '¿Dónde está el precio?', 'Where is the price?', [['está', 'aquí', 'allí', 'en'], ['aquí', 'allí', 'camisa', 'pantalones', 'ropa']], 'El precio está aquí.', [Kit::word('el precio', 'precio')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.color', 'El color y la talla están aquí.', 'The color and the size are here.', [Kit::word('el color'), Kit::word('la talla')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.me-pruebo', 'Me pruebo la camisa y los pantalones.', 'I try on the shirt and the trousers.', [Kit::word('probarse', 'me pruebo'), Kit::word('la camisa'), Kit::word('los pantalones')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.camisa', 'The shirt is cheap here.', ['La camisa es barata aquí.', 'Aquí la camisa es barata.', 'La camisa aquí es barata.'], [Kit::word('la camisa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ropa', 'The clothes are expensive here.', ['La ropa es cara aquí.', 'Aquí la ropa es cara.', 'La ropa aquí es cara.'], [Kit::word('la ropa'), Kit::word('caro', 'cara'), Kit::form('cara', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.me-pruebo', 'I try on the trousers here.', ['Me pruebo los pantalones aquí.', 'Aquí me pruebo los pantalones.', 'Me pruebo aquí los pantalones.', 'Yo me pruebo los pantalones aquí.', 'Aquí yo me pruebo los pantalones.'], [Kit::word('probarse', 'me pruebo'), Kit::word('los pantalones')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.color', 'What color are the clothes?', ['¿De qué color es la ropa?'], [Kit::word('¿qué?', 'qué'), Kit::word('el color', 'color'), Kit::word('la ropa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pantalones', 'Los pantalones son muy ___.', 'The trousers are very expensive.', 'caros', Kit::form('caros', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.camisas', 'Las camisas no son ___.', 'The shirts are not cheap.', 'baratas', Kit::form('baratas'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.camisas', 'Las camisas son muy caras. ¿Cuánto son?', 'The shirts are very expensive. How much are they?', [Kit::word('¿cuánto?', 'cuánto'), Kit::word('la camisa', 'camisas'), Kit::word('caro', 'caras'), Kit::form('caras')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.precio', '¿Qué talla es? La talla y el precio están en la camisa.', 'What size is it? The size and the price are on the shirt.', [Kit::word('¿qué?', 'qué'), Kit::word('el precio', 'precio'), Kit::word('la talla', 'talla'), Kit::word('la camisa')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.descuento', 'Con descuento, la camisa es barata.', 'With a discount, the shirt is cheap.', [Kit::word('el descuento', 'descuento'), Kit::word('la camisa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.talla', [
                Kit::line('Marta', 'Buenos días. ¿Tiene la camisa en mi talla?'),
                Kit::line('Dependiente', 'Sí, aquí está. Es cara, pero hay un descuento.'),
                Kit::line('Marta', 'Muy bien. Gracias.'),
            ], [
                Kit::question('What does Marta ask for?', ['A cheaper shirt', 'A shirt in her size', 'The price'], 'A shirt in her size'),
                Kit::question('Is the shirt expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Which greeting does Marta use?', ['Good morning', 'Good afternoon', 'Good evening'], 'Good morning'),
                Kit::question('Who speaks first?', ['Marta', 'The shop assistant', 'Nobody'], 'Marta'),
                Kit::question('How does the conversation end?', ['Marta says thank you.', 'Marta asks the price.', 'Marta tries on trousers.'], 'Marta says thank you.'),
            ], [Kit::word('la camisa'), Kit::word('la talla'), Kit::word('caro'), Kit::word('el descuento')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ropa', 'Read the conversation.', [
                Kit::line('Pablo', 'Buenas tardes. ¿Hay ropa barata aquí?'),
                Kit::line('Dependiente', 'Sí, la ropa es barata. Hay un descuento en los pantalones.'),
                Kit::line('Pablo', 'Muy bien. ¿Y el precio? ¿Cuánto es?'),
            ], [
                Kit::question('Is the clothing cheap?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Which clothes have a discount?', ['The shirts', 'The trousers', 'All the clothes'], 'The trousers'),
            ], [Kit::word('la ropa'), Kit::word('barato'), Kit::word('los pantalones'), Kit::word('el descuento'), Kit::word('el precio'), Kit::word('¿cuánto?', 'cuánto')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.descuento', '¿Hay descuento?', 'Is there a discount?', [['sí', 'no', 'hay'], ['descuento']], 'Sí, hay descuento.', [Kit::word('el descuento', 'descuento')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ropa', '¿Dónde está la ropa?', 'Where are the clothes?', [['está', 'ropa', 'aquí', 'allí'], ['aquí', 'allí']], 'La ropa está aquí.', [Kit::word('la ropa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pantalones', '¿Son caros los pantalones?', 'Are the trousers expensive?', [['sí', 'no', 'son', 'caros', 'baratos'], ['caros', 'baratos']], 'No, los pantalones son baratos.', [Kit::word('los pantalones'), Kit::word('barato', 'baratos')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.pantalones', 'The trousers are very cheap.', ['Los pantalones son muy baratos.'], [Kit::word('los pantalones'), Kit::word('barato', 'baratos'), Kit::form('baratos', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.camisa', 'How much is the shirt? It is very cheap.', ['¿Cuánto es la camisa? Es muy barata.'], [Kit::word('¿cuánto?', 'cuánto'), Kit::word('la camisa'), Kit::word('barato', 'barata'), Kit::form('barata')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.precio', 'The price and the size are not here.', ['El precio y la talla no están aquí.'], [Kit::word('el precio'), Kit::word('la talla')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.me-pruebo', 'What size is it? The size is on the shirt.', ['¿Qué talla es? La talla está en la camisa.'], [Kit::word('¿qué?', 'qué'), Kit::word('la talla'), Kit::word('la camisa')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.camisas', 'Aquí las camisas son ___.', 'The shirts here are expensive.', 'caras', Kit::form('caras'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ropa', 'La ropa no es ___.', 'The clothes are not cheap.', 'barata', Kit::form('barata', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.color', '¿De qué color son las camisas?', 'What color are the shirts?', [Kit::word('¿qué?', 'qué'), Kit::word('el color', 'color'), Kit::word('la camisa', 'camisas')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.descuento', 'Con el descuento, la ropa es barata.', 'With the discount, the clothes are cheap.', [Kit::word('el descuento', 'descuento'), Kit::word('la ropa'), Kit::word('barato', 'barata'), Kit::form('barata', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.me-pruebo', 'Me pruebo la camisa, pero es cara. ¿Y cuánto es?', 'I try on the shirt, but it is expensive. And how much is it?', [Kit::word('¿cuánto?', 'cuánto'), Kit::word('probarse', 'me pruebo'), Kit::word('la camisa'), Kit::word('caro', 'cara'), Kit::form('cara')], 'dictation', $set),
        ];
    }
}
