<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class AtTheMarket implements UnitContent
{
    private const FOODS = ['huevos', 'tomates', 'manzanas', 'naranjas', 'fruta', 'pan', 'leche', 'queso', 'carne', 'pescado', 'vino', 'agua', 'docena', 'kilo', 'kilos'];

    private const AMOUNTS = ['un', 'una', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'doce', 'docena', 'kilo', 'kilos'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'at-the-market';
    }

    public function words(): array
    {
        return [
            new WordData('el mercado', cue: 'market', forms: ['mercados']),
            new WordData('la manzana', cue: 'apple', forms: ['manzanas']),
            new WordData('el tomate', cue: 'tomato', forms: ['tomates']),
            new WordData('el huevo', cue: 'egg', forms: ['huevos'], note: 'The h of huevo is silent. Say it as "wévo". The plural is los huevos.'),
            new WordData('la naranja', cue: 'orange (the fruit)', forms: ['naranjas']),
            new WordData('la docena', cue: 'dozen (twelve of something)', forms: ['docenas'], note: 'Always put de before the thing: una docena de huevos, una docena de naranjas.'),
            new WordData('llevar', cue: 'to take (to buy and take away)', forms: ['llevo', 'llevas', 'lleva', 'llevamos', 'llevan', 'llevarlo', 'llevarla', 'llevarlos', 'llevarlas'], note: 'At the market llevar is what you say when you decide to buy: Llevo un kilo (I will take a kilo). It also means to carry or to wear.'),
            new WordData('comprar', cue: 'to buy', forms: ['compro', 'compras', 'compra', 'compramos', 'compran', 'comprarlo', 'comprarla', 'comprarlos', 'comprarlas']),
            new WordData('necesitar', cue: 'to need', forms: ['necesito', 'necesitas', 'necesita', 'necesitamos', 'necesitan']),
            new WordData('fresco', cue: 'fresh (masculine)', forms: ['fresca', 'frescos', 'frescas'], note: 'Fresco agrees with the noun: pan fresco, fruta fresca, huevos frescos, naranjas frescas.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => '¿El pan? Lo compro en el mercado.', 'english' => 'The bread? I buy it at the market.'],
            ['text' => 'Las naranjas están frescas y las llevo.', 'english' => 'The oranges are fresh and I am taking them.'],
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
            Kit::gap($stage, 'sentences.choose_gap.pan-lo', '¿El pan? ___ compro aquí.', ['lo', 'la', 'los'], 'lo', Kit::form('lo', true), 'El pan is masculine and singular, so you need lo. La is for a feminine word and los is for more than one.', 'choose', 'The bread? I buy it here.'),
            Kit::gap($stage, 'sentences.choose_gap.leche-la', '¿La leche? ___ necesito.', ['la', 'lo', 'las'], 'la', Kit::form('la', true), 'La leche is feminine and singular, so you need la. Lo is for a masculine word and las is for more than one.', 'choose', 'The milk? I need it.'),
            Kit::gap($stage, 'sentences.choose_gap.tomates-los', '¿Los tomates? ___ compro hoy.', ['los', 'las', 'lo'], 'los', Kit::form('los', true), 'Los tomates is masculine and plural, so you need los. Las is for feminine words and lo is for one thing.', 'choose', 'The tomatoes? I am buying them today.'),
            Kit::gap($stage, 'sentences.choose_gap.docena', 'Necesito una ___ de huevos.', ['docena', 'manzana', 'naranja'], 'docena', Kit::word('la docena', 'docena'), 'Una docena de huevos is twelve eggs. Una manzana or una naranja is one piece of fruit, and you do not say it de huevos.', 'choose', 'I need a dozen eggs.'),
            Kit::gap($stage, 'sentences.choose_gap.mercado', 'Voy al ___ con Marta.', ['mercado', 'tomate', 'huevo'], 'mercado', Kit::word('el mercado', 'mercado'), 'You go to a place: al mercado. A tomate or a huevo is a food, not a place.', 'choose', 'I am going to the market with Marta.'),
            Kit::gap($stage, 'sentences.choose_gap.frescas', 'Las manzanas están ___.', ['frescas', 'fresco', 'frescos'], 'frescas', Kit::word('fresco', 'frescas'), 'Fresco agrees with the noun. Las manzanas is feminine and plural, so you need frescas.', 'choose', 'The apples are fresh.'),

            Kit::typeGap($stage, 'sentences.type_gap.manzanas-las', '¿Las manzanas? ___ necesito.', 'The apples? I need them.', 'las', Kit::form('las'), 'Las manzanas is feminine and plural, so you need las, directly before the verb.'),
            Kit::typeGap($stage, 'sentences.type_gap.kilo-manzanas', 'Compro un kilo de ___.', 'I buy a kilo of apples.', 'manzanas', Kit::word('la manzana', 'manzanas')),
            Kit::typeGap($stage, 'sentences.type_gap.kilos-tomates', 'Necesito dos kilos de ___.', 'I need two kilos of tomatoes.', 'tomates', Kit::word('el tomate', 'tomates')),
            Kit::typeGap($stage, 'sentences.type_gap.ana-lleva', 'Ana ___ la fruta fresca.', 'Ana is taking the fresh fruit.', 'lleva', Kit::word('llevar', 'lleva')),
            Kit::typeGap($stage, 'sentences.type_gap.marta-necesita', 'Marta ___ huevos para la cena.', 'Marta needs eggs for dinner.', 'necesita', Kit::word('necesitar', 'necesita')),

            Kit::translate($stage, 'sentences.translate.huevos-mercado', 'The eggs? I buy them at the market.', ['¿Los huevos? Los compro en el mercado.', 'Los huevos los compro en el mercado.'], [Kit::word('el huevo', 'huevos'), Kit::word('comprar', 'compro'), Kit::word('el mercado', 'mercado'), Kit::form('los')]),
            Kit::translate($stage, 'sentences.translate.manzanas-frescas', 'The apples are fresh, I am taking them.', ['Las manzanas están frescas, las llevo.', 'Las manzanas son frescas, las llevo.'], [Kit::word('la manzana', 'manzanas'), Kit::word('fresco', 'frescas'), Kit::word('llevar', 'llevo'), Kit::form('las')]),
            Kit::translate($stage, 'sentences.translate.docena-naranjas', 'I need a dozen oranges.', ['Necesito una docena de naranjas.', 'Yo necesito una docena de naranjas.'], [Kit::word('la docena', 'docena'), Kit::word('la naranja', 'naranjas'), Kit::word('necesitar', 'necesito')]),

            Kit::build($stage, 'sentences.build.naranjas-las', 'The oranges? We are taking them.', '¿Las naranjas? Las llevamos.', ['los'], [Kit::word('la naranja', 'naranjas'), Kit::word('llevar', 'llevamos'), Kit::form('las')]),
            Kit::build($stage, 'sentences.build.necesitamos-docena', 'We need a dozen eggs.', 'Necesitamos una docena de huevos.', ['necesito'], [Kit::word('necesitar', 'necesitamos'), Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos')]),
            Kit::build($stage, 'sentences.build.ana-manzanas', 'Ana buys the apples at the market.', 'Ana compra las manzanas en el mercado.', ['los'], [Kit::word('comprar', 'compra'), Kit::word('la manzana', 'manzanas'), Kit::word('el mercado', 'mercado')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.docena', 'Necesito una docena de huevos.', ['I need a dozen eggs.', 'I need a dozen apples.', 'I am taking a dozen eggs.', 'Ana needs a dozen eggs.'], 'I need a dozen eggs.', [Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos'), Kit::word('necesitar', 'necesito')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.queso', '¿El queso? Lo llevo.', ['The cheese? I am taking it.', 'The milk? I am taking it.', 'The cheese? Ana is taking it.', 'The cheeses? I am taking them.'], 'The cheese? I am taking it.', [Kit::word('llevar', 'llevo'), Kit::form('lo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.tomates', 'Los tomates están frescos.', ['The tomatoes are fresh.', 'The tomato is fresh.', 'The apples are fresh.', 'I am taking the fresh tomatoes.'], 'The tomatoes are fresh.', [Kit::word('el tomate', 'tomates'), Kit::word('fresco', 'frescos')]),
            Kit::listenType($stage, 'sentences.listen_type.compra-kilo', 'Marta compra un kilo de manzanas.', 'Marta buys a kilo of apples.', [Kit::word('comprar', 'compra'), Kit::word('la manzana', 'manzanas')]),
            Kit::listenType($stage, 'sentences.listen_type.mercado-bolsa', 'Voy al mercado y llevo una bolsa.', 'I am going to the market and I am taking a bag.', [Kit::word('el mercado', 'mercado'), Kit::word('llevar', 'llevo')]),
            Kit::listenType($stage, 'sentences.listen_type.naranja-la', 'La naranja está fresca y la compro.', 'The orange is fresh and I buy it.', [Kit::word('la naranja', 'naranja'), Kit::word('fresco', 'fresca'), Kit::form('la')]),
            Kit::listenType($stage, 'sentences.listen_type.seis-huevos', 'Necesito seis huevos y los llevo.', 'I need six eggs and I am taking them.', [Kit::word('el huevo', 'huevos'), Kit::word('necesitar', 'necesito'), Kit::form('los')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.naranjas', 'Las naranjas están frescas.', 'The oranges are fresh.', [Kit::word('la naranja', 'naranjas'), Kit::word('fresco', 'frescas')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.docena', 'Una docena de huevos, por favor.', 'A dozen eggs, please.', [Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.tomates', '¿Cuánto cuestan los tomates?', 'How much are the tomatoes?', [Kit::word('el tomate', 'tomates')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.fruta', 'Compro la fruta en el mercado.', 'I buy the fruit at the market.', [Kit::word('comprar', 'compro'), Kit::word('el mercado', 'mercado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pan', '¿Compras el pan en el mercado?', 'Do you buy the bread at the market?', [['sí', 'no'], ['lo', 'compro']], 'Sí, lo compro en el mercado.', [Kit::word('el mercado', 'mercado'), Kit::word('comprar', 'compro'), Kit::form('lo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.manzanas', '¿Necesitas las manzanas?', 'Do you need the apples?', [['sí', 'no'], ['las', 'necesito']], 'Sí, las necesito.', [Kit::word('necesitar', 'necesito'), Kit::word('la manzana', 'manzanas'), Kit::form('las')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.huevos', '¿Cuántos huevos llevas?', 'How many eggs are you taking?', [['llevo'], self::AMOUNTS], 'Llevo una docena de huevos.', [Kit::word('la docena', 'docena'), Kit::word('llevar', 'llevo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fruta-fresca', 'Read the conversation at the market.', [
                Kit::line('Ana', 'Buenos días, Pablo. Necesito fruta fresca.'),
                Kit::line('Pablo', '¿Las manzanas? Las tengo muy frescas.'),
                Kit::line('Ana', 'Muy bien. Llevo un kilo. ¿Y los tomates?'),
                Kit::line('Pablo', 'Los tomates no están frescos hoy. Hay naranjas.'),
                Kit::line('Ana', 'Llevo también las naranjas. ¿Cuánto es?'),
            ], [
                Kit::question('What does Ana need?', ['Fresh fruit', 'Fresh bread', 'Fresh fish'], 'Fresh fruit'),
                Kit::question('How many apples does Ana take?', ['A kilo', 'A dozen', 'Two kilos'], 'A kilo'),
                Kit::question('Are the tomatoes fresh today?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
            ], [Kit::word('la manzana', 'manzanas'), Kit::word('la naranja', 'naranjas'), Kit::word('el tomate', 'tomates'), Kit::word('fresco', 'frescas'), Kit::word('necesitar', 'Necesito')], 'read'),
            Kit::gap($stage, 'task.choose_gap.huevos-los', 'Necesito huevos. ¿Dónde ___ compro?', ['los', 'las', 'lo'], 'los', Kit::form('los', true), 'Huevos is masculine and plural, so you need los. Las is for feminine words and lo is for one thing.', 'read', 'I need eggs. Where do I buy them?'),
            Kit::gap($stage, 'task.choose_gap.ana-mercado', 'Ana va al ___ para comprar fruta.', ['mercado', 'huevo', 'tomate'], 'mercado', Kit::word('el mercado', 'mercado'), 'You go to a place to buy fruit: al mercado. A huevo or a tomate is a food, not a place.', 'read', 'Ana is going to the market to buy fruit.'),

            Kit::transform($stage, 'task.transform.manzanas', 'Say it with a pronoun instead of the noun.', 'Necesito las manzanas.', ['Las necesito.', 'Yo las necesito.'], [Kit::word('necesitar', 'necesito'), Kit::form('las')]),
            Kit::transform($stage, 'task.transform.huevos', 'Say it with a pronoun instead of the noun.', 'Compramos los huevos.', ['Los compramos.', 'Nosotros los compramos.'], [Kit::word('comprar', 'compramos'), Kit::form('los')]),
            Kit::transform($stage, 'task.transform.fruta', 'Say it with the pronoun on the end of the infinitive.', 'Voy a llevar la fruta.', ['Voy a llevarla.', 'Yo voy a llevarla.'], [Kit::word('llevar', 'llevarla'), Kit::form('llevarla')]),
            Kit::writeGuided($stage, 'task.write_guided.docena-huevos', 'Tell the seller that you need a dozen eggs and that you are taking them.', ['necesito', 'una docena', 'huevos', 'los llevo'], 'Necesito una docena de huevos y los llevo.', [
                ['forms' => ['docena'], 'term' => 'la docena'],
                ['forms' => ['huevos'], 'term' => 'el huevo'],
                ['forms' => ['llevo'], 'term' => 'llevar'],
                ['forms' => ['los'], 'term' => null],
            ], [Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos'), Kit::word('llevar', 'llevo'), Kit::form('los')]),
            Kit::writeGuided($stage, 'task.write_guided.tomates-frescos', 'Tell Ana that the tomatoes are fresh and that you are buying them.', ['los tomates', 'están', 'frescos', 'los compro'], 'Ana, los tomates están frescos y los compro.', [
                ['forms' => ['tomates'], 'term' => 'el tomate'],
                ['forms' => ['frescos'], 'term' => 'fresco'],
                ['forms' => ['compro'], 'term' => 'comprar'],
                ['forms' => ['los'], 'term' => null],
            ], [Kit::word('el tomate', 'tomates'), Kit::word('fresco', 'frescos'), Kit::word('comprar', 'compro'), Kit::form('los')]),
            Kit::build($stage, 'task.build.naranjas-frescas', 'The oranges are fresh and I am taking them.', 'Las naranjas están frescas y las llevo.', ['los', 'llevan'], [Kit::word('la naranja', 'naranjas'), Kit::word('fresco', 'frescas'), Kit::word('llevar', 'llevo'), Kit::form('las')]),
            Kit::build($stage, 'task.build.ana-huevos', 'Ana needs eggs and buys them at the market.', 'Ana necesita huevos y los compra en el mercado.', ['las', 'compran'], [Kit::word('necesitar', 'necesita'), Kit::word('el huevo', 'huevos'), Kit::word('comprar', 'compra'), Kit::word('el mercado', 'mercado'), Kit::form('los')]),
            Kit::build($stage, 'task.build.docena-cuesta', 'A dozen oranges costs four euros.', 'Una docena de naranjas cuesta cuatro euros.', ['cuestan', 'tres'], [Kit::word('la docena', 'docena'), Kit::word('la naranja', 'naranjas')]),
            Kit::translate($stage, 'task.translate.tomates-fiesta', 'The tomatoes? Pablo takes them to the party.', ['¿Los tomates? Pablo los lleva a la fiesta.', 'Los tomates los lleva Pablo a la fiesta.'], [Kit::word('el tomate', 'tomates'), Kit::word('llevar', 'lleva'), Kit::form('los')]),
            Kit::translate($stage, 'task.translate.pablo-naranjas', 'Pablo needs oranges and takes them.', ['Pablo necesita naranjas y las lleva.'], [Kit::word('necesitar', 'necesita'), Kit::word('la naranja', 'naranjas'), Kit::form('las')]),

            Kit::listenPassage($stage, 'task.listen_passage.huevos-tomates', [
                Kit::line('Marta', 'Luis, ¿qué necesitas del mercado?'),
                Kit::line('Luis', 'Necesito huevos y tomates. ¿Los compras tú?'),
                Kit::line('Marta', 'Sí, los compro hoy. ¿Y la fruta?'),
                Kit::line('Luis', 'La fruta la tengo en casa.'),
                Kit::line('Marta', 'Muy bien. Compro una docena de huevos. Y un kilo de tomates.'),
            ], [
                Kit::question('What does Luis need?', ['Eggs and tomatoes', 'Fruit and milk', 'Bread and cheese'], 'Eggs and tomatoes'),
                Kit::question('How many eggs does Marta buy?', ['A dozen', 'Six', 'Two'], 'A dozen'),
                Kit::question('Where does Luis have the fruit?', ['At home', 'At the market', 'Marta has it'], 'At home'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Does Marta buy tomatoes?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el huevo', 'huevos'), Kit::word('el tomate', 'tomates'), Kit::word('la docena', 'docena'), Kit::word('el mercado', 'mercado'), Kit::word('necesitar', 'necesitas'), Kit::word('comprar', 'compro')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.kilos-tomates', 'Necesito dos kilos de tomates y los pago con tarjeta.', 'I need two kilos of tomatoes and I pay for them by card.', [Kit::word('necesitar', 'necesito'), Kit::word('el tomate', 'tomates'), Kit::form('los')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.pablo-docena', 'Pablo compra una docena de huevos en el mercado.', 'Pablo buys a dozen eggs at the market.', [Kit::word('comprar', 'compra'), Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos'), Kit::word('el mercado', 'mercado')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.huevos-no-frescos', 'Los huevos no están frescos, no los compro.', 'The eggs are not fresh, I am not buying them.', [Kit::word('el huevo', 'huevos'), Kit::word('fresco', 'frescos'), Kit::form('los', true)], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.necesitas', '¿Qué necesitas del mercado?', 'What do you need from the market?', [['necesito'], self::FOODS], 'Necesito una docena de huevos.', [Kit::word('necesitar', 'necesito'), Kit::word('la docena', 'docena')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.naranjas', '¿Compras las naranjas en el mercado?', 'Do you buy the oranges at the market?', [['sí', 'no'], ['las', 'compro']], 'Sí, las compro en el mercado.', [Kit::word('comprar', 'compro'), Kit::word('la naranja', 'naranjas'), Kit::word('el mercado', 'mercado'), Kit::form('las')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.tomates-frescos', '¿Están frescos los tomates?', 'Are the tomatoes fresh?', [['sí', 'no'], ['frescos', 'están']], 'Sí, los tomates están frescos.', [Kit::word('el tomate', 'tomates'), Kit::word('fresco', 'frescos')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.manzanas-llevas', '¿Cuántas manzanas llevas?', 'How many apples are you taking?', [['llevo'], self::AMOUNTS], 'Llevo dos kilos de manzanas.', [Kit::word('llevar', 'llevo'), Kit::word('la manzana', 'manzanas')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.huevos', '¿Los huevos? Los compro aquí.', 'The eggs? I buy them here.', [Kit::word('el huevo', 'huevos'), Kit::word('comprar', 'compro'), Kit::form('los')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.docena-cuesta', 'La docena de huevos cuesta tres euros.', 'The dozen eggs cost three euros.', [Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.naranjas-pablo', 'The oranges? Pablo takes them.', ['¿Las naranjas? Pablo las lleva.', 'Las naranjas las lleva Pablo.'], [Kit::word('la naranja', 'naranjas'), Kit::word('llevar', 'lleva'), Kit::form('las')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pan-fresco', 'The bread is fresh and I buy it.', ['El pan está fresco y lo compro.', 'El pan es fresco y lo compro.'], [Kit::word('fresco', 'fresco'), Kit::word('comprar', 'compro'), Kit::form('lo', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.marta-docena', 'Marta buys a dozen eggs at the market.', ['Marta compra una docena de huevos en el mercado.'], [Kit::word('comprar', 'compra'), Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos'), Kit::word('el mercado', 'mercado')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.tomates-necesitas', 'Do you need the tomatoes? Yes, I need them.', ['¿Necesitas los tomates? Sí, los necesito.'], [Kit::word('necesitar', 'necesito'), Kit::word('el tomate', 'tomates'), Kit::form('los', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.kilo-manzanas', 'Compro un kilo de ___ en el mercado.', 'I buy a kilo of apples at the market.', 'manzanas', Kit::word('la manzana', 'manzanas'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.fruta-la', '¿La fruta? Sí, ___ llevo.', 'The fruit? Yes, I am taking it.', 'la', Kit::form('la'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.manzanas-frescas', 'Las manzanas están frescas y las necesito.', 'The apples are fresh and I need them.', [Kit::word('la manzana', 'manzanas'), Kit::word('fresco', 'frescas'), Kit::word('necesitar', 'necesito'), Kit::form('las')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.pablo-tomates', 'Pablo compra tomates y una docena de huevos.', 'Pablo buys tomatoes and a dozen eggs.', [Kit::word('comprar', 'compra'), Kit::word('el tomate', 'tomates'), Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.luis-naranjas', 'Luis tiene naranjas y las lleva al mercado.', 'Luis has oranges and takes them to the market.', [Kit::word('llevar', 'lleva'), Kit::word('la naranja', 'naranjas'), Kit::word('el mercado', 'mercado'), Kit::form('las')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.huevos-fruta', [
                Kit::line('Ana', 'Pablo, ¿qué necesitas del mercado?'),
                Kit::line('Pablo', 'Necesito huevos. ¿Tú los compras?'),
                Kit::line('Ana', 'Sí, los compro. ¿Cuántos necesitas?'),
                Kit::line('Pablo', 'Una docena. Y yo compro la fruta.'),
                Kit::line('Ana', 'Muy bien. Adiós.'),
            ], [
                Kit::question('What does Pablo need?', ['Eggs', 'Apples', 'Bread'], 'Eggs'),
                Kit::question('How many does he need?', ['A dozen', 'Two', 'Six'], 'A dozen'),
                Kit::question('Who buys the fruit?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
            ], [
                Kit::question('Who asks first?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Does Ana buy the eggs?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('el huevo', 'huevos'), Kit::word('la docena', 'docena'), Kit::word('el mercado', 'mercado')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.tomates-naranjas', 'Read the conversation.', [
                Kit::line('Luis', 'Marta, ¿vas al mercado hoy?'),
                Kit::line('Marta', 'Sí. Necesito tomates y naranjas.'),
                Kit::line('Luis', 'Los tomates cuestan dos euros el kilo.'),
                Kit::line('Marta', 'Llevo un kilo de tomates. ¿Y las naranjas?'),
                Kit::line('Luis', 'Están frescas. ¿Las llevas?'),
                Kit::line('Marta', 'Sí, las llevo.'),
            ], [
                Kit::question('What does Marta need?', ['Tomatoes and oranges', 'Eggs and apples', 'Bread and milk'], 'Tomatoes and oranges'),
                Kit::question('How much are the tomatoes?', ['Two euros a kilo', 'Three euros a kilo', 'Six euros a dozen'], 'Two euros a kilo'),
                Kit::question('Does Marta take the oranges?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('el tomate', 'tomates'), Kit::word('la naranja', 'naranjas'), Kit::word('fresco', 'frescas')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.huevos', '¿Compras los huevos en el mercado?', 'Do you buy the eggs at the market?', [['sí', 'no'], ['los', 'compro']], 'Sí, los compro en el mercado.', [Kit::word('el huevo', 'huevos'), Kit::form('los')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.docena', '¿Necesitas una docena de naranjas?', 'Do you need a dozen oranges?', [['sí', 'no'], ['necesito', 'docena', 'naranjas']], 'Sí, necesito una docena.', [Kit::word('necesitar', 'necesito'), Kit::word('la docena', 'docena'), Kit::word('la naranja', 'naranjas')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.frescas', '¿Están frescas las manzanas?', 'Are the apples fresh?', [['sí', 'no'], ['frescas', 'están', 'manzanas']], 'Sí, las manzanas están frescas.', [Kit::word('fresco', 'frescas'), Kit::word('la manzana', 'manzanas')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.manzanas-marta', 'The apples? Marta needs them.', ['¿Las manzanas? Marta las necesita.', 'Las manzanas las necesita Marta.'], [Kit::word('la manzana', 'manzanas'), Kit::word('necesitar', 'necesita'), Kit::form('las', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.tomates-ana', 'The tomatoes are fresh and Ana takes them.', ['Los tomates están frescos y Ana los lleva.', 'Los tomates son frescos y Ana los lleva.'], [Kit::word('el tomate', 'tomates'), Kit::word('fresco', 'frescos'), Kit::word('llevar', 'lleva'), Kit::form('los', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pablo-docena', 'Pablo buys a dozen oranges at the market.', ['Pablo compra una docena de naranjas en el mercado.'], [Kit::word('comprar', 'compra'), Kit::word('la docena', 'docena'), Kit::word('la naranja', 'naranjas'), Kit::word('el mercado', 'mercado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.leche-llevas', 'Do you take the milk? Yes, I take it.', ['¿Llevas la leche? Sí, la llevo.'], [Kit::word('llevar', 'llevo'), Kit::form('la')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.queso-lo', '¿El queso? ___ necesito para la cena.', 'The cheese? I need it for dinner.', 'lo', Kit::form('lo'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.seis-huevos', 'Necesito seis ___ para la cena.', 'I need six eggs for dinner.', 'huevos', Kit::word('el huevo', 'huevos'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.naranjas-mercado', 'Las naranjas están frescas y las compro en el mercado.', 'The oranges are fresh and I buy them at the market.', [Kit::word('la naranja', 'naranjas'), Kit::word('fresco', 'frescas'), Kit::word('comprar', 'compro'), Kit::word('el mercado', 'mercado'), Kit::form('las')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pablo-lleva', 'Pablo lleva una docena de huevos y tomates.', 'Pablo takes a dozen eggs and tomatoes.', [Kit::word('llevar', 'lleva'), Kit::word('la docena', 'docena'), Kit::word('el huevo', 'huevos'), Kit::word('el tomate', 'tomates')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.manzanas-no', 'Las manzanas no están frescas, no las compro.', 'The apples are not fresh, I am not buying them.', [Kit::word('la manzana', 'manzanas'), Kit::word('fresco', 'frescas'), Kit::word('comprar', 'compro'), Kit::form('las')], 'dictation', $set),
        ];
    }
}
