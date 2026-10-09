<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TheBestAndTheWorst implements UnitContent
{
    private const ADJECTIVES = ['tranquilo', 'tranquila', 'ruidoso', 'ruidosa', 'grande', 'bonito', 'bonita', 'antiguo', 'antigua', 'famoso', 'famosa'];

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'the-best-and-the-worst';
    }

    public function words(): array
    {
        return [
            new WordData('famoso', cue: 'famous (masculine)', forms: ['famosa', 'famosos', 'famosas', 'famosísimo', 'famosísima']),
            new WordData('antiguo', cue: 'old, ancient (masculine)', forms: ['antigua', 'antiguos', 'antiguas'], note: 'Antiguo is old in the sense of ancient or from long ago: una catedral antigua. It is not the word for an old person.'),
            new WordData('bonito', cue: 'pretty, nice (masculine)', forms: ['bonita', 'bonitos', 'bonitas', 'bonitísimo', 'bonitísima']),
            new WordData('tranquilo', cue: 'quiet, calm (masculine)', forms: ['tranquila', 'tranquilos', 'tranquilas', 'tranquilísimo', 'tranquilísima']),
            new WordData('ruidoso', cue: 'noisy (masculine)', forms: ['ruidosa', 'ruidosos', 'ruidosas']),
            new WordData('grande', cue: 'big, large', forms: ['grandes', 'grandísimo', 'grandísima'], note: 'Grande has one form for masculine and feminine: un barrio grande, una plaza grande. The plural is grandes.'),
            new WordData('el barrio', cue: 'neighbourhood (a part of a city)', forms: ['barrios']),
            new WordData('la plaza', cue: 'square (in a town or city)', forms: ['plazas']),
            new WordData('la catedral', cue: 'cathedral', forms: ['catedrales']),
            new WordData('el turista', cue: 'tourist', forms: ['turistas'], commonGender: true, note: 'El turista is a man and la turista is a woman. The word itself does not change.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'La catedral es la más antigua del pueblo.', 'english' => 'The cathedral is the oldest in the village.'],
            ['text' => 'Es el mejor barrio de la ciudad.', 'english' => 'It is the best neighbourhood in the city.'],
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
            Kit::gap($stage, 'sentences.choose_gap.plaza-mas', 'La plaza es la ___ bonita.', ['más', 'mejor', 'muy'], 'más', Kit::form('la más'), 'The superlative is article + más + adjective: la más bonita. Mejor is only for good, and muy does not follow an article.', 'choose', 'The square is the prettiest.'),
            Kit::gap($stage, 'sentences.choose_gap.tienda-mejor', 'La tienda es la ___ del barrio.', ['mejor', 'peor', 'más'], 'mejor', Kit::form('la mejor', true), 'The best is la mejor. Good has its own form, so Spanish does not say más bueno. La peor would mean the worst.', 'choose', 'The shop is the best in the neighbourhood.'),
            Kit::gap($stage, 'sentences.choose_gap.catedral-de', 'Es la catedral más antigua ___ España.', ['de', 'que'], 'de', Kit::form('más', true), 'After a superlative the group comes after de: de España, like Dutch van or in. Que is only for comparing two things.', 'choose', 'It is the oldest cathedral in Spain.'),
            Kit::gap($stage, 'sentences.choose_gap.plaza-fiesta', 'En la ___ hay una fiesta.', ['plaza', 'turista', 'barrio'], 'plaza', Kit::word('la plaza', 'plaza'), 'La goes with a feminine noun, and a party in the open air is in the plaza. En la barrio is wrong because barrio is masculine, and a turista is a person.', 'choose', 'There is a party in the square.'),
            Kit::gap($stage, 'sentences.choose_gap.barrio-tranquilo', 'Mi ___ es muy tranquilo.', ['barrio', 'plaza', 'catedral'], 'barrio', Kit::word('el barrio', 'barrio'), 'Tranquilo ends in -o, so it describes a masculine noun: mi barrio. Plaza and catedral are feminine and need tranquila.', 'choose', 'My neighbourhood is very quiet.'),
            Kit::gap($stage, 'sentences.choose_gap.catedral-antigua', 'La catedral es muy ___.', ['antigua', 'antiguo', 'antiguas'], 'antigua', Kit::word('antiguo', 'antigua'), 'Catedral is feminine and singular, so the adjective is antigua.', 'choose', 'The cathedral is very old.'),

            Kit::typeGap($stage, 'sentences.type_gap.plaza-la-mas', 'La plaza es ___ grande del barrio.', 'The square is the biggest in the neighbourhood.', 'la más', Kit::form('la más'), 'Plaza is feminine, so the article is la: la más grande.'),
            Kit::typeGap($stage, 'sentences.type_gap.barrio-el-mas', 'Mi barrio es ___ tranquilo del pueblo.', 'My neighbourhood is the quietest in the village.', 'el más', Kit::form('el más'), 'Barrio is masculine, so the article is el: el más tranquilo.'),
            Kit::typeGap($stage, 'sentences.type_gap.turista', 'Un ___ está en la plaza.', 'A tourist is in the square.', 'turista', Kit::word('el turista', 'turista')),
            Kit::typeGap($stage, 'sentences.type_gap.cafe-peor', 'El café es el ___ del pueblo.', 'The café is the worst in the village.', 'peor', Kit::form('peor'), 'The worst is el peor. Peor has its own form, so Spanish does not say más malo.'),
            Kit::typeGap($stage, 'sentences.type_gap.grandisima', 'La plaza es muy grande, es ___.', 'The square is very big, it is extremely big.', 'grandísima', Kit::form('grandísima'), 'Drop the final -e of grande and add -ísima: grandísima. It agrees with plaza, which is feminine.'),

            Kit::translate($stage, 'sentences.translate.catedral-antigua', 'The cathedral is the oldest.', ['La catedral es la más antigua.'], [Kit::word('la catedral', 'catedral'), Kit::word('antiguo', 'antigua'), Kit::form('la más')]),
            Kit::translate($stage, 'sentences.translate.barrio-tranquilisimo', 'My neighbourhood is extremely quiet (use -ísimo).', ['Mi barrio es tranquilísimo.'], [Kit::word('el barrio', 'barrio'), Kit::form('tranquilísimo')]),
            Kit::translate($stage, 'sentences.translate.barrios-ruidosos', 'They are the noisiest neighbourhoods.', ['Son los barrios más ruidosos.'], [Kit::word('el barrio', 'barrios'), Kit::word('ruidoso', 'ruidosos'), Kit::form('más')]),

            Kit::build($stage, 'sentences.build.luis-turista', 'Luis is the friendliest tourist.', 'Luis es el turista más simpático.', ['mejor'], [Kit::word('el turista', 'turista'), Kit::form('más')]),
            Kit::build($stage, 'sentences.build.cafe-mejor', 'It is the best café in the neighbourhood.', 'Es el mejor café del barrio.', ['más'], [Kit::word('el barrio', 'barrio'), Kit::form('el mejor')]),
            Kit::build($stage, 'sentences.build.plaza-bonita', 'It is the prettiest square in Spain.', 'Es la plaza más bonita de España.', ['mejor'], [Kit::word('la plaza', 'plaza'), Kit::word('bonito', 'bonita'), Kit::form('más')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.catedral-famosa', 'La catedral es la más famosa.', ['The cathedral is the most famous.', 'The cathedral is the oldest.', 'The cathedral is not famous.', 'The square is the most famous.'], 'The cathedral is the most famous.', [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa'), Kit::form('la más')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.barrio-peor', 'Es el peor barrio del pueblo.', ['It is the worst neighbourhood in the village.', 'It is the best neighbourhood in the village.', 'It is the noisiest neighbourhood in the village.', 'It is the quietest neighbourhood in the village.'], 'It is the worst neighbourhood in the village.', [Kit::word('el barrio', 'barrio'), Kit::form('el peor')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.marta-joven', 'Marta es la turista más joven.', ['Marta is the youngest tourist.', 'Marta is the oldest tourist.', 'Marta is the best tourist.', 'Ana is the youngest tourist.'], 'Marta is the youngest tourist.', [Kit::word('el turista', 'turista'), Kit::form('más')]),
            Kit::listenType($stage, 'sentences.listen_type.plaza-grande-tranquila', 'La plaza es grande y tranquila.', 'The square is big and quiet.', [Kit::word('la plaza', 'plaza'), Kit::word('grande'), Kit::word('tranquilo', 'tranquila')]),
            Kit::listenType($stage, 'sentences.listen_type.barrio-ruidoso', 'Mi barrio es el más ruidoso.', 'My neighbourhood is the noisiest.', [Kit::word('el barrio', 'barrio'), Kit::word('ruidoso'), Kit::form('el más')]),
            Kit::listenType($stage, 'sentences.listen_type.ana-turista', 'Ana es la mejor turista del pueblo.', 'Ana is the best tourist in the village.', [Kit::word('el turista', 'turista'), Kit::form('la mejor')]),
            Kit::listenType($stage, 'sentences.listen_type.plaza-bonita', 'Es una plaza tranquila y bonita.', 'It is a quiet and pretty square.', [Kit::word('la plaza', 'plaza'), Kit::word('tranquilo', 'tranquila'), Kit::word('bonito', 'bonita')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.catedral-antigua', 'Es la catedral más antigua.', 'It is the oldest cathedral.', [Kit::word('la catedral', 'catedral'), Kit::word('antiguo', 'antigua'), Kit::form('más')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.barrio-tranquilo', 'Mi barrio es el más tranquilo.', 'My neighbourhood is the quietest.', [Kit::word('el barrio', 'barrio'), Kit::word('tranquilo'), Kit::form('el más')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.museo-famoso', 'Es el museo más famoso del país.', 'It is the most famous museum in the country.', [Kit::word('famoso'), Kit::form('más')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.plaza-ruidosa', 'La plaza es grande y ruidosa.', 'The square is big and noisy.', [Kit::word('la plaza', 'plaza'), Kit::word('grande'), Kit::word('ruidoso', 'ruidosa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.como-barrio', '¿Cómo es tu barrio?', 'What is your neighbourhood like?', [['es', 'mi'], self::ADJECTIVES], 'Mi barrio es muy tranquilo.', [Kit::word('el barrio', 'barrio'), Kit::word('tranquilo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.ciudad-famosa', '¿Es famosa tu ciudad?', 'Is your city famous?', [['sí', 'no'], ['famosa', 'es']], 'Sí, mi ciudad es muy famosa.', [Kit::word('famoso', 'famosa')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.mas-simpatico', '¿Quién es el más simpático?', 'Who is the friendliest?', [['ana', 'pablo', 'marta', 'luis'], ['más']], 'Pablo es el más simpático.', [Kit::form('el más')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.ciudad-de-pablo', 'Read the conversation about Pablo\'s city.', [
                Kit::line('Ana', 'Pablo, ¿cómo es tu ciudad?'),
                Kit::line('Pablo', 'Es grande y muy bonita. La catedral es la más famosa.'),
                Kit::line('Ana', '¿Y tu barrio?'),
                Kit::line('Pablo', 'Es el más tranquilo, pero la plaza es ruidosa.'),
                Kit::line('Ana', 'Para los turistas, ¿cuál es el mejor barrio?'),
                Kit::line('Pablo', 'El barrio antiguo es el mejor.'),
            ], [
                Kit::question('What is the most famous place?', ['The cathedral', 'The square', 'The neighbourhood'], 'The cathedral'),
                Kit::question('How is Pablo\'s neighbourhood?', ['The quietest', 'The noisiest', 'The oldest'], 'The quietest'),
                Kit::question('Which neighbourhood is best for tourists?', ['The old one', 'Pablo\'s', 'The one with the square'], 'The old one'),
            ], [Kit::word('grande'), Kit::word('bonito', 'bonita'), Kit::word('la catedral', 'catedral'), Kit::word('famoso'), Kit::word('el barrio', 'barrio'), Kit::word('tranquilo'), Kit::word('la plaza', 'plaza'), Kit::word('ruidoso', 'ruidosa'), Kit::word('el turista', 'turistas'), Kit::word('antiguo')], 'read'),
            Kit::gap($stage, 'task.choose_gap.iglesia-antigua', 'La catedral es la iglesia más ___ de la ciudad.', ['antigua', 'antiguo', 'antiguas'], 'antigua', Kit::form('más'), 'Iglesia is feminine and singular, so the adjective stays antigua after más. Más never changes.', 'read', 'The cathedral is the oldest church in the city.'),
            Kit::gap($stage, 'task.choose_gap.plaza-ruidosa', 'La plaza no es tranquila, es ___.', ['ruidosa', 'ruidoso', 'tranquila'], 'ruidosa', Kit::word('ruidoso', 'ruidosa'), 'It is not quiet, so it is noisy. Plaza is feminine, so the adjective ends in -a: ruidosa.', 'read', 'The square is not quiet, it is noisy.'),

            Kit::transform($stage, 'task.transform.plaza-bonita', 'Say that it is the prettiest in the village.', 'La plaza es bonita.', ['La plaza es la más bonita del pueblo.'], [Kit::word('la plaza', 'plaza'), Kit::word('bonito', 'bonita'), Kit::form('la más')]),
            Kit::transform($stage, 'task.transform.barrio-tranquilisimo', 'Say it is extremely quiet with -ísimo.', 'Mi barrio es muy tranquilo.', ['Mi barrio es tranquilísimo.'], [Kit::word('el barrio', 'barrio'), Kit::form('tranquilísimo')]),
            Kit::transform($stage, 'task.transform.catedral-peor', 'Say that it is the worst in the village.', 'La catedral es la más bonita.', ['La catedral es la peor del pueblo.'], [Kit::word('la catedral', 'catedral'), Kit::form('la peor', true)]),
            Kit::writeGuided($stage, 'task.write_guided.plaza-catedral', 'Say that the square is the biggest and the cathedral is the oldest.', ['la plaza', 'la más', 'grande', 'la catedral', 'antigua'], 'La plaza es la más grande y la catedral es la más antigua.', [
                ['forms' => ['plaza'], 'term' => 'la plaza'],
                ['forms' => ['grande'], 'term' => 'grande'],
                ['forms' => ['catedral'], 'term' => 'la catedral'],
                ['forms' => ['antigua'], 'term' => 'antiguo'],
                ['forms' => ['más'], 'term' => null],
            ], [Kit::word('la plaza', 'plaza'), Kit::word('grande'), Kit::word('la catedral', 'catedral'), Kit::word('antiguo', 'antigua'), Kit::form('la más')]),
            Kit::writeGuided($stage, 'task.write_guided.ana-turista', 'Say that Ana is the best tourist in the city.', ['Ana', 'la mejor', 'turista', 'ciudad'], 'Ana es la mejor turista de la ciudad.', [
                ['forms' => ['turista'], 'term' => 'el turista'],
                ['forms' => ['mejor'], 'term' => null],
                ['forms' => ['ciudad'], 'term' => null],
            ], [Kit::word('el turista', 'turista'), Kit::form('la mejor')]),
            Kit::build($stage, 'task.build.barrio-antiguo', 'The old neighbourhood is the quietest in the city.', 'El barrio antiguo es el más tranquilo de la ciudad.', ['mejor', 'que'], [Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('tranquilo'), Kit::form('el más')]),
            Kit::build($stage, 'task.build.plaza-turistas', 'It is the best square for tourists.', 'Es la mejor plaza para los turistas.', ['más', 'peor'], [Kit::word('la plaza', 'plaza'), Kit::word('el turista', 'turistas'), Kit::form('la mejor')]),
            Kit::build($stage, 'task.build.catedral-grande', 'The most famous cathedral is also the biggest.', 'La catedral más famosa también es la más grande.', ['mejor', 'muy'], [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa'), Kit::word('grande'), Kit::form('la más')]),
            Kit::translate($stage, 'task.translate.plaza-ruidosa-bonita', 'The noisiest square is the prettiest.', ['La plaza más ruidosa es la más bonita.'], [Kit::word('la plaza', 'plaza'), Kit::word('ruidoso', 'ruidosa'), Kit::word('bonito', 'bonita'), Kit::form('la más')]),
            Kit::translate($stage, 'task.translate.mejores-barrios', 'The best neighbourhoods are quiet.', ['Los mejores barrios son tranquilos.'], [Kit::word('el barrio', 'barrios'), Kit::word('tranquilo', 'tranquilos'), Kit::form('los mejores', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.plaza-o-parque', [
                Kit::line('Marta', 'Luis, ¿vamos a la plaza?'),
                Kit::line('Luis', 'No, la plaza es muy ruidosa. Prefiero el parque.'),
                Kit::line('Marta', 'Pero la plaza es la más bonita de la ciudad.'),
                Kit::line('Luis', 'Sí, y la catedral es la más famosa. Hay turistas aquí.'),
                Kit::line('Marta', 'Vamos al barrio antiguo. Es el más tranquilo.'),
                Kit::line('Luis', 'Claro, vamos.'),
            ], [
                Kit::question('Why does Luis not want to go to the square?', ['It is very noisy', 'It is closed', 'It is very small'], 'It is very noisy'),
                Kit::question('Which place is the most famous?', ['The cathedral', 'The square', 'The park'], 'The cathedral'),
                Kit::question('Where do they decide to go?', ['To the old neighbourhood', 'To the park', 'To the square'], 'To the old neighbourhood'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is the square noisy?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la plaza', 'plaza'), Kit::word('ruidoso', 'ruidosa'), Kit::word('bonito', 'bonita'), Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa'), Kit::word('el turista', 'turistas'), Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('tranquilo')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.barrio-famoso', 'El barrio más antiguo es el más famoso.', 'The oldest neighbourhood is the most famous.', [Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('famoso'), Kit::form('el más')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.turistas-jovenes', 'Ana y Marta son las turistas más jóvenes.', 'Ana and Marta are the youngest tourists.', [Kit::word('el turista', 'turistas'), Kit::form('más')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.tranquilisimo-grandisima', 'El barrio es tranquilísimo y la plaza grandísima.', 'The neighbourhood is extremely quiet and the square extremely big.', [Kit::word('el barrio', 'barrio'), Kit::word('la plaza', 'plaza'), Kit::form('tranquilísimo')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.barrio-tranquilo', '¿Cuál es el barrio más tranquilo de tu ciudad?', 'Which is the quietest neighbourhood in your city?', [['más'], ['barrio', 'tranquilo']], 'Mi barrio es el más tranquilo.', [Kit::word('el barrio', 'barrio'), Kit::word('tranquilo'), Kit::form('el más')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.plaza-ciudad', '¿Cómo es la plaza de tu ciudad?', 'What is the square in your city like?', [['es'], self::ADJECTIVES], 'La plaza es grande y bonita.', [Kit::word('la plaza', 'plaza'), Kit::word('grande'), Kit::word('bonito', 'bonita')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mejor-barrio', '¿Cuál es el mejor barrio para los turistas?', 'Which is the best neighbourhood for tourists?', [['es'], ['mejor', 'antiguo']], 'El barrio antiguo es el mejor.', [Kit::word('el turista', 'turistas'), Kit::form('el mejor')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.catedral-famosa', '¿Es famosa la catedral de tu ciudad?', 'Is the cathedral of your city famous?', [['sí', 'no'], ['famosa', 'es']], 'Sí, es muy famosa.', [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.plaza-bonita-ruidosa', 'La plaza más bonita es muy ruidosa.', 'The prettiest square is very noisy.', [Kit::word('la plaza', 'plaza'), Kit::word('bonito', 'bonita'), Kit::word('ruidoso', 'ruidosa'), Kit::form('más')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.turista-simpatico', 'El turista más simpático es Luis.', 'The friendliest tourist is Luis.', [Kit::word('el turista', 'turista'), Kit::form('más')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.catedral-ciudad', 'The cathedral is the most famous in the city.', ['La catedral es la más famosa de la ciudad.'], [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa'), Kit::form('la más')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.barrio-tranquilo-grande', 'The quietest neighbourhood is also the biggest.', ['El barrio más tranquilo también es el más grande.'], [Kit::word('el barrio', 'barrio'), Kit::word('tranquilo'), Kit::word('grande'), Kit::form('el más')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.plaza-bonitisima', 'The old square is extremely pretty (use -ísimo).', ['La plaza antigua es bonitísima.'], [Kit::word('la plaza', 'plaza'), Kit::word('antiguo', 'antigua'), Kit::form('bonitísima')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.mejor-barrio-turistas', 'The best neighbourhood for tourists is not the noisiest.', ['El mejor barrio para turistas no es el más ruidoso.'], [Kit::word('el barrio', 'barrio'), Kit::word('el turista', 'turistas'), Kit::word('ruidoso'), Kit::form('el mejor', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.plaza-grande', 'La plaza es muy ___.', 'The square is very big.', 'grande', Kit::word('grande'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.catedral-bonita', 'La catedral es muy ___.', 'The cathedral is very pretty.', 'bonita', Kit::word('bonito', 'bonita'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.catedral-plaza', 'La catedral es tranquila, pero la plaza es ruidosa.', 'The cathedral is quiet, but the square is noisy.', [Kit::word('la catedral', 'catedral'), Kit::word('tranquilo', 'tranquila'), Kit::word('la plaza', 'plaza'), Kit::word('ruidoso', 'ruidosa')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.barrio-famoso-espana', 'El barrio antiguo es el más famoso de España.', 'The old neighbourhood is the most famous in Spain.', [Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('famoso'), Kit::form('el más', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.turista-plaza', 'El turista dice que la plaza es la más bonita.', 'The tourist says that the square is the prettiest.', [Kit::word('el turista', 'turista'), Kit::word('la plaza', 'plaza'), Kit::word('bonito', 'bonita'), Kit::form('la más')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.plaza-barrio', [
                Kit::line('Ana', 'Pablo, ¿qué plaza es la más bonita?'),
                Kit::line('Pablo', 'La plaza grande es famosa, pero es ruidosa.'),
                Kit::line('Ana', '¿Y el barrio antiguo?'),
                Kit::line('Pablo', 'Es el más tranquilo y tiene la catedral.'),
                Kit::line('Ana', 'Claro, vamos al barrio antiguo.'),
            ], [
                Kit::question('What is the problem with the big square?', ['It is noisy', 'It is old', 'It is closed'], 'It is noisy'),
                Kit::question('Which neighbourhood is the quietest?', ['The old one', 'The one with the square', 'Pablo\'s'], 'The old one'),
                Kit::question('Where do they go?', ['To the old neighbourhood', 'To the big square', 'To the park'], 'To the old neighbourhood'),
            ], [
                Kit::question('Who asks about the square?', ['Ana', 'Pablo', 'Nobody'], 'Ana'),
                Kit::question('Is the old neighbourhood quiet?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('la plaza', 'plaza'), Kit::word('famoso'), Kit::word('ruidoso'), Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('tranquilo'), Kit::word('la catedral', 'catedral')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.ciudad-de-ana', 'Read the conversation.', [
                Kit::line('Marta', 'Luis, ¿cómo es la ciudad de Ana?'),
                Kit::line('Luis', 'Es muy grande. El barrio antiguo es bonito.'),
                Kit::line('Marta', '¿Y hay turistas?'),
                Kit::line('Luis', 'Sí, la catedral es la más famosa. Es grandísima.'),
            ], [
                Kit::question('What is the most famous place in the city?', ['The cathedral', 'The old neighbourhood', 'The park'], 'The cathedral'),
                Kit::question('How is the old neighbourhood?', ['Pretty', 'Noisy', 'Closed'], 'Pretty'),
            ], [Kit::word('grande'), Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::word('bonito'), Kit::word('el turista', 'turistas'), Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.barrio-tranquilo', '¿Es tranquilo tu barrio?', 'Is your neighbourhood quiet?', [['sí', 'no'], ['tranquilo', 'ruidoso', 'es']], 'Sí, mi barrio es muy tranquilo.', [Kit::word('el barrio', 'barrio'), Kit::word('tranquilo')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.catedral-famosa', '¿Es famosa la catedral?', 'Is the cathedral famous?', [['sí', 'no'], ['famosa', 'es', 'antigua']], 'Sí, la catedral es muy famosa.', [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.plaza-grande', '¿Es grande la plaza de tu ciudad?', 'Is the square in your city big?', [['sí', 'no'], ['grande', 'plaza', 'es']], 'Sí, la plaza es grande.', [Kit::word('la plaza', 'plaza'), Kit::word('grande')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.catedral-espana', 'The cathedral is the biggest in Spain.', ['La catedral es la más grande de España.'], [Kit::word('la catedral', 'catedral'), Kit::word('grande'), Kit::form('la más')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.barrios-bonitos', 'The quiet neighbourhoods are the prettiest.', ['Los barrios tranquilos son los más bonitos.'], [Kit::word('el barrio', 'barrios'), Kit::word('tranquilo', 'tranquilos'), Kit::word('bonito', 'bonitos'), Kit::form('los más')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.plaza-famosisima', 'The noisy square is extremely famous (use -ísimo).', ['La plaza ruidosa es famosísima.'], [Kit::word('la plaza', 'plaza'), Kit::word('ruidoso', 'ruidosa'), Kit::form('famosísima')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.barrio-antiguo-peor', 'The oldest neighbourhood is also the worst.', ['El barrio más antiguo también es el peor.'], [Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::form('el peor', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.ana-turista', 'Ana es una ___ en la catedral.', 'Ana is a tourist in the cathedral.', 'turista', Kit::word('el turista', 'turista'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.iglesia-famosa', 'La iglesia es muy ___.', 'The church is very famous.', 'famosa', Kit::word('famoso', 'famosa'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.plaza-grande-ruidosa', 'La plaza más grande es la más ruidosa.', 'The biggest square is the noisiest.', [Kit::word('la plaza', 'plaza'), Kit::word('grande'), Kit::word('ruidoso', 'ruidosa'), Kit::form('la más')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.catedral-barrio-antiguo', 'La catedral es la más famosa del barrio antiguo.', 'The cathedral is the most famous in the old neighbourhood.', [Kit::word('la catedral', 'catedral'), Kit::word('famoso', 'famosa'), Kit::word('el barrio', 'barrio'), Kit::word('antiguo'), Kit::form('la más', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.turista-barrio', 'El turista dice que el barrio es tranquilo y bonito.', 'The tourist says that the neighbourhood is quiet and pretty.', [Kit::word('el turista', 'turista'), Kit::word('el barrio', 'barrio'), Kit::word('tranquilo'), Kit::word('bonito')], 'dictation', $set),
        ];
    }
}
