<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class EatingOut implements UnitContent
{
    private const HAY_NOTE = 'Hay (there is) sounds like ay (a cry of surprise or pain). Here it means there is or there are.';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'eating-out';
    }

    public function words(): array
    {
        return [
            new WordData('el plato', cue: 'dish (a course)', note: 'El plato is the plate and also the dish or course you order. El plato del día is the dish of the day.'),
            new WordData('la sopa', cue: 'soup'),
            new WordData('la ensalada', cue: 'salad'),
            new WordData('el postre', cue: 'dessert', note: 'De postre means for dessert: De postre, un helado.'),
            new WordData('el helado', cue: 'ice cream'),
            new WordData('el zumo', cue: 'juice', note: 'In Spain juice is el zumo. Latin America says el jugo.'),
            new WordData('la cerveza', cue: 'beer'),
            new WordData('pedir', cue: 'to order (food or drink)', forms: ['pido', 'pides', 'pide', 'pedimos', 'piden'], note: 'Pedir changes its stem: pido, pides, pide, pedimos, piden. In a restaurant it means to order or to ask for.'),
            new WordData('picante', cue: 'spicy', note: 'Picante is spicy hot, from chilli or pepper. It is not about temperature: hot soup is caliente.'),
            new WordData('dulce', cue: 'sweet'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Quisiera un poco de sopa.', 'english' => 'I would like a little soup.'],
            ['text' => 'Me apetece un helado.', 'english' => 'I feel like an ice cream.'],
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
            Kit::gap($stage, 'sentences.choose_gap.un-poco', 'Quisiera ___ sopa.', ['un poco de', 'un poco', 'nada de'], 'un poco de', Kit::form('un poco de', true), 'A little of a thing is un poco de plus the thing. The de always stays. Un poco alone is not enough, and nada de means none at all.', 'choose', 'I would like a little soup.'),
            Kit::gap($stage, 'sentences.choose_gap.algo', '¿Quieres ___ postre?', ['algo de', 'nada de', 'algo'], 'algo de', Kit::form('algo de', true), 'Some of a thing is algo de plus the thing. Nada de means none at all, and algo without de does not fit in front of postre.', 'choose', 'Do you want some dessert?'),
            Kit::gap($stage, 'sentences.choose_gap.nada', 'No pido ___ cerveza.', ['nada de', 'algo de', 'un poco'], 'nada de', Kit::form('nada de', true), 'After no, none at all is nada de plus the thing: no pido nada de cerveza. Algo de is for a positive sentence.', 'choose', 'I am not ordering any beer.'),
            Kit::gap($stage, 'sentences.choose_gap.mucha', 'Hay ___ ensalada.', ['mucha', 'mucho', 'algo'], 'mucha', Kit::form('mucha', true), 'Mucho agrees with the thing, and ensalada is feminine, so mucha ensalada. Mucho goes with a masculine word such as el zumo.', 'choose', 'There is a lot of salad.'),
            Kit::gap($stage, 'sentences.choose_gap.apetece', '___ un helado.', ['Me apetece', 'Mi apetece', 'Yo apetece'], 'Me apetece', Kit::form('me apetece', true), 'What you feel like is me apetece plus the thing. The ice cream is the subject, so the verb stays apetece. Do not say yo apetece.', 'choose', 'I feel like an ice cream.'),
            Kit::gap($stage, 'sentences.choose_gap.plato', 'El ___ del día es el pescado.', ['plato', 'postre', 'zumo'], 'plato', Kit::word('el plato', 'plato'), 'El plato del día is the dish of the day. Postre is dessert and zumo is juice.', 'choose', 'The dish of the day is the fish.'),

            Kit::typeGap($stage, 'sentences.type_gap.postre', 'De ___ quisiera un helado.', 'For dessert I would like an ice cream.', 'postre', Kit::word('el postre', 'postre')),
            Kit::typeGap($stage, 'sentences.type_gap.un-poco-zumo', 'Quisiera ___ zumo.', 'I would like a little juice.', 'un poco de', Kit::form('un poco de'), 'A little of a thing is un poco de plus the thing.'),
            Kit::typeGap($stage, 'sentences.type_gap.nada-helado', 'No pedimos ___ helado.', 'We are not ordering any ice cream.', 'nada de', Kit::form('nada de', true), 'After no, none at all is nada de plus the thing: nada de helado.'),
            Kit::typeGap($stage, 'sentences.type_gap.pide', 'Marta ___ la sopa.', 'Marta orders the soup.', 'pide', Kit::word('pedir', 'pide')),
            Kit::typeGap($stage, 'sentences.type_gap.picante', 'La sopa es muy ___.', 'The soup is very spicy.', 'picante', Kit::word('picante')),

            Kit::translate($stage, 'sentences.translate.ensalada', 'I order a little salad.', ['Pido un poco de ensalada.', 'Yo pido un poco de ensalada.'], [Kit::word('la ensalada', 'ensalada'), Kit::word('pedir', 'pido'), Kit::form('un poco de')]),
            Kit::translate($stage, 'sentences.translate.cerveza-helado', 'We order a beer and an ice cream.', ['Pedimos una cerveza y un helado.', 'Nosotros pedimos una cerveza y un helado.'], [Kit::word('la cerveza', 'cerveza'), Kit::word('el helado', 'helado'), Kit::word('pedir', 'pedimos')]),
            Kit::translate($stage, 'sentences.translate.postre-dulce', 'I feel like a sweet dessert.', ['Me apetece un postre dulce.'], [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::form('me apetece')]),

            Kit::build($stage, 'sentences.build.sopa-picante', 'I prefer the spicy soup.', 'Prefiero la sopa picante.', ['el'], [Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::form('prefiero')]),
            Kit::build($stage, 'sentences.build.nada-postre', 'I am not ordering any dessert.', 'No pido nada de postre.', ['algo'], [Kit::word('el postre', 'postre'), Kit::word('pedir', 'pido'), Kit::form('nada de', true)]),
            Kit::build($stage, 'sentences.build.ana-plato', 'Ana prefers the dish of the day.', 'Ana prefiere el plato del día.', ['la'], [Kit::word('el plato', 'plato'), Kit::form('prefiero', false, ['prefiere'])]),

            Kit::listenChoose($stage, 'sentences.listen_choose.sopa', 'Quisiera un poco de sopa.', ['I would like a little soup.', 'I would like a lot of soup.', 'I would like no soup.', 'I would like a little salad.'], 'I would like a little soup.', [Kit::word('la sopa', 'sopa'), Kit::form('un poco de')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.postre', 'No pedimos nada de postre.', ['We are not ordering any dessert.', 'We are ordering some dessert.', 'I am not ordering any dessert.', 'We are not ordering any ice cream.'], 'We are not ordering any dessert.', [Kit::word('pedir', 'pedimos'), Kit::word('el postre', 'postre'), Kit::form('nada de', true)]),
            Kit::listenChoose($stage, 'sentences.listen_choose.helado', 'Me apetece un helado.', ['I feel like an ice cream.', 'I feel like a juice.', 'I prefer an ice cream.', 'I do not feel like an ice cream.'], 'I feel like an ice cream.', [Kit::word('el helado', 'helado'), Kit::form('me apetece')]),
            Kit::listenType($stage, 'sentences.listen_type.plato-sopa', 'El plato del día es la sopa.', 'The dish of the day is the soup.', [Kit::word('el plato', 'plato'), Kit::word('la sopa', 'sopa')]),
            Kit::listenType($stage, 'sentences.listen_type.pide', 'Pablo pide una cerveza y un zumo.', 'Pablo orders a beer and a juice.', [Kit::word('pedir', 'pide'), Kit::word('la cerveza', 'cerveza'), Kit::word('el zumo', 'zumo')]),
            Kit::listenType($stage, 'sentences.listen_type.mucha-sopa', 'Hay mucha sopa picante.', 'There is a lot of spicy soup.', [Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::form('mucha', true)], homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.prefiero', 'Prefiero un postre dulce.', 'I prefer a sweet dessert.', [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::form('prefiero')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ensalada', 'Quisiera un poco de ensalada.', 'I would like a little salad.', [Kit::word('la ensalada', 'ensalada'), Kit::form('un poco de')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cuenta', 'Pedimos la cuenta, por favor.', 'We ask for the bill, please.', [Kit::word('pedir', 'pedimos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.cerveza', 'Me apetece una cerveza.', 'I feel like a beer.', [Kit::word('la cerveza', 'cerveza'), Kit::form('me apetece')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.helado', 'El helado es muy dulce.', 'The ice cream is very sweet.', [Kit::word('el helado', 'helado'), Kit::word('dulce')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.postre', '¿Qué pides de postre?', 'What do you order for dessert?', [['pido', 'apetece', 'quisiera', 'prefiero'], ['helado', 'fruta', 'queso', 'dulce']], 'Pido un helado.', [Kit::word('pedir', 'pido'), Kit::word('el helado', 'helado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.sopa-ensalada', '¿Qué prefieres, la sopa o la ensalada?', 'Which do you prefer, the soup or the salad?', [['prefiero'], ['sopa', 'ensalada']], 'Prefiero la sopa.', [Kit::word('la sopa', 'sopa'), Kit::word('la ensalada', 'ensalada'), Kit::form('prefiero')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.helado', '¿Quieres un helado de postre?', 'Do you want an ice cream for dessert?', [['sí', 'no'], ['helado', 'postre', 'apetece', 'pido', 'prefiero', 'quisiera', 'gracias']], 'Sí, me apetece un helado.', [Kit::word('el helado', 'helado'), Kit::word('el zumo', 'zumo'), Kit::form('me apetece')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.restaurante', 'Read the conversation in the restaurant.', [
                Kit::line('Luis', 'Buenas tardes. El plato del día es el pescado.'),
                Kit::line('Ana', 'Prefiero la sopa. ¿Es picante?'),
                Kit::line('Luis', 'No, no es picante.'),
                Kit::line('Pablo', 'Yo pido un poco de ensalada y una cerveza.'),
                Kit::line('Ana', 'De postre me apetece algo dulce.'),
            ], [
                Kit::question('What does Ana prefer?', ['The soup', 'The fish', 'The salad'], 'The soup'),
                Kit::question('What does Pablo order?', ['Some salad and a beer', 'Soup and a juice', 'Fish and a beer'], 'Some salad and a beer'),
                Kit::question('What does Ana feel like for dessert?', ['Something sweet', 'Something spicy', 'Nothing'], 'Something sweet'),
            ], [Kit::word('el plato', 'plato'), Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::word('pedir', 'pido'), Kit::word('la ensalada', 'ensalada'), Kit::word('la cerveza', 'cerveza'), Kit::word('el postre', 'postre'), Kit::word('dulce')], 'read'),
            Kit::gap($stage, 'task.choose_gap.nada-cerveza', 'Ana no pide ___ cerveza.', ['nada de', 'algo de', 'un poco'], 'nada de', Kit::form('nada de', true), 'After no, none at all is nada de plus the thing: no pide nada de cerveza. Algo de is for a positive sentence.', 'read', 'Ana is not ordering any beer.'),
            Kit::gap($stage, 'task.choose_gap.piden', 'Pablo y Ana ___ el plato del día.', ['piden', 'pide', 'pido'], 'piden', Kit::word('pedir', 'piden'), 'Pablo y Ana are two people, so the verb ends in -en: piden. Pide is for one person and pido means I order.', 'read', 'Pablo and Ana order the dish of the day.'),

            Kit::transform($stage, 'task.transform.nada-postre', 'Say that you order no dessert at all, with nada de.', 'Pido algo de postre.', ['No pido nada de postre.', 'Yo no pido nada de postre.'], [Kit::word('el postre', 'postre'), Kit::word('pedir', 'pido'), Kit::form('nada de', true)]),
            Kit::transform($stage, 'task.transform.ana-sopa', 'Say that Ana prefers the soup.', 'Prefiero la sopa.', ['Ana prefiere la sopa.'], [Kit::word('la sopa', 'sopa'), Kit::form('prefiero', false, ['prefiere'])]),
            Kit::transform($stage, 'task.transform.mucho-zumo', 'Say that there is a lot of juice.', 'Hay un poco de zumo.', ['Hay mucho zumo.'], [Kit::word('el zumo', 'zumo'), Kit::form('mucho', true)]),
            Kit::writeGuided($stage, 'task.write_guided.sopa-helado', 'Say that you order a little soup and an ice cream.', ['pido', 'un poco de sopa', 'y un helado'], 'Pido un poco de sopa y un helado.', [
                ['forms' => ['pido'], 'term' => 'pedir'],
                ['forms' => ['poco'], 'term' => null],
                ['forms' => ['sopa'], 'term' => 'la sopa'],
                ['forms' => ['helado'], 'term' => 'el helado'],
            ], [Kit::word('pedir', 'pido'), Kit::word('la sopa', 'sopa'), Kit::word('el helado', 'helado'), Kit::form('un poco de')]),
            Kit::writeGuided($stage, 'task.write_guided.postre-dulce', 'Say that for dessert you feel like something sweet.', ['de postre', 'me apetece', 'algo dulce'], 'De postre me apetece algo dulce.', [
                ['forms' => ['postre'], 'term' => 'el postre'],
                ['forms' => ['apetece'], 'term' => null],
                ['forms' => ['dulce'], 'term' => 'dulce'],
            ], [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::form('me apetece')]),
            Kit::build($stage, 'task.build.mucha-sopa', 'There is a lot of soup and a little salad.', 'Hay mucha sopa y un poco de ensalada.', ['mucho', 'algo'], [Kit::word('la sopa', 'sopa'), Kit::word('la ensalada', 'ensalada'), Kit::form('un poco de')]),
            Kit::build($stage, 'task.build.plato-zumo', 'We order the dish of the day and a juice.', 'Pedimos el plato del día y un zumo.', ['pido', 'pide'], [Kit::word('pedir', 'pedimos'), Kit::word('el plato', 'plato'), Kit::word('el zumo', 'zumo')]),
            Kit::build($stage, 'task.build.no-postre', 'I do not feel like dessert, I prefer an ice cream.', 'No me apetece postre, prefiero un helado.', ['pido', 'mucha'], [Kit::word('el postre', 'postre'), Kit::word('el helado', 'helado'), Kit::form('me apetece')]),
            Kit::translate($stage, 'task.translate.sopa-ensalada', 'We order a little soup and a lot of salad.', ['Pedimos un poco de sopa y mucha ensalada.', 'Nosotros pedimos un poco de sopa y mucha ensalada.'], [Kit::word('pedir', 'pedimos'), Kit::word('la sopa', 'sopa'), Kit::word('la ensalada', 'ensalada'), Kit::form('un poco de')]),
            Kit::translate($stage, 'task.translate.dulce-zumo', 'I would like something sweet and a juice, please.', ['Quisiera algo dulce y un zumo, por favor.', 'Por favor, quisiera algo dulce y un zumo.', 'Me gustaría algo dulce y un zumo, por favor.', 'Por favor, me gustaría algo dulce y un zumo.'], [Kit::word('dulce'), Kit::word('el zumo', 'zumo')]),

            Kit::listenPassage($stage, 'task.listen_passage.pedimos', [
                Kit::line('Marta', 'Luis, ¿qué pedimos?'),
                Kit::line('Luis', 'Yo pido la sopa. Es muy picante.'),
                Kit::line('Marta', 'Yo prefiero la ensalada. ¿Y de postre?'),
                Kit::line('Luis', 'Me apetece un helado.'),
                Kit::line('Marta', 'No me apetece nada de postre.'),
            ], [
                Kit::question('What does Luis order?', ['The soup', 'The salad', 'The fish'], 'The soup'),
                Kit::question('What does Marta prefer?', ['The salad', 'The soup', 'The ice cream'], 'The salad'),
                Kit::question('What does Marta feel like for dessert?', ['Nothing', 'An ice cream', 'Something sweet'], 'Nothing'),
            ], [
                Kit::question('Who speaks first?', ['Marta', 'Luis', 'Nobody'], 'Marta'),
                Kit::question('Is the soup spicy?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Does Luis feel like an ice cream?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [Kit::word('pedir', 'pido'), Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::word('la ensalada', 'ensalada'), Kit::word('el postre', 'postre'), Kit::word('el helado', 'helado'), Kit::form('nada de', true)], 'listen'),
            Kit::listenType($stage, 'task.listen_type.un-poco-sopa', 'Marta pide un poco de sopa.', 'Marta orders a little soup.', [Kit::word('pedir', 'pide'), Kit::word('la sopa', 'sopa'), Kit::form('un poco de')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.prefiere', 'De postre, Pablo prefiere algo dulce.', 'For dessert Pablo prefers something sweet.', [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::form('prefiero', false, ['prefiere'])], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mucho-zumo', 'Hay mucho zumo y mucha cerveza.', 'There is a lot of juice and a lot of beer.', [Kit::word('el zumo', 'zumo'), Kit::word('la cerveza', 'cerveza'), Kit::form('mucho', true)], 'listen', homophoneNote: self::HAY_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.sopa-ensalada', '¿Qué pides, la sopa o la ensalada?', 'What do you order, the soup or the salad?', [['pido', 'prefiero'], ['sopa', 'ensalada']], 'Pido la sopa.', [Kit::word('la sopa', 'sopa'), Kit::word('la ensalada', 'ensalada'), Kit::word('pedir', 'pido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.picante-dulce', '¿Es la sopa picante o dulce?', 'Is the soup spicy or sweet?', [['es'], ['picante', 'dulce']], 'La sopa es picante.', [Kit::word('picante'), Kit::word('dulce')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.helado-fruta', '¿Qué pides de postre, un helado o un poco de fruta?', 'What do you order for dessert, an ice cream or a little fruit?', [['pido', 'prefiero', 'quisiera'], ['helado', 'fruta']], 'Pido un poco de fruta.', [Kit::word('el postre', 'postre'), Kit::word('el helado', 'helado'), Kit::form('un poco de')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.cerveza-zumo', '¿Quieres una cerveza o un zumo?', 'Do you want a beer or a juice?', [['prefiero', 'apetece', 'quisiera', 'pido'], ['cerveza', 'zumo']], 'Prefiero un zumo.', [Kit::word('la cerveza', 'cerveza'), Kit::word('el zumo', 'zumo'), Kit::form('prefiero')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.plato-postre', 'Pedimos el plato del día y un postre.', 'We order the dish of the day and a dessert.', [Kit::word('pedir', 'pedimos'), Kit::word('el plato', 'plato'), Kit::word('el postre', 'postre')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.zumo-helado', 'Prefiero un zumo y un poco de helado.', 'I prefer a juice and a little ice cream.', [Kit::word('el zumo', 'zumo'), Kit::word('el helado', 'helado'), Kit::form('un poco de')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.sopa-ensalada', 'We order a little soup and a salad.', ['Pedimos un poco de sopa y una ensalada.', 'Nosotros pedimos un poco de sopa y una ensalada.'], [Kit::word('pedir', 'pedimos'), Kit::word('la sopa', 'sopa'), Kit::word('la ensalada', 'ensalada'), Kit::form('un poco de')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.postre-dulce', 'I feel like a sweet dessert, not a spicy soup.', ['Me apetece un postre dulce, no una sopa picante.'], [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::word('picante'), Kit::word('la sopa', 'sopa'), Kit::form('me apetece')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.nada-cerveza', 'I am not ordering any beer, I order a juice.', ['No pido nada de cerveza, pido un zumo.', 'Yo no pido nada de cerveza, pido un zumo.', 'No pido nada de cerveza y pido un zumo.'], [Kit::word('la cerveza', 'cerveza'), Kit::word('el zumo', 'zumo'), Kit::word('pedir', 'pido'), Kit::form('nada de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.plato-helado', 'I prefer the dish of the day, not the ice cream.', ['Prefiero el plato del día, no el helado.', 'Yo prefiero el plato del día, no el helado.'], [Kit::word('el plato', 'plato'), Kit::word('el helado', 'helado'), Kit::form('prefiero')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.plato', 'El ___ del día es picante.', 'The dish of the day is spicy.', 'plato', Kit::word('el plato', 'plato'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.helado', 'De postre, Pablo pide un ___.', 'For dessert Pablo orders an ice cream.', 'helado', Kit::word('el helado', 'helado'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.cerveza-zumo', 'Marta pide una cerveza y un zumo.', 'Marta orders a beer and a juice.', [Kit::word('pedir', 'pide'), Kit::word('la cerveza', 'cerveza'), Kit::word('el zumo', 'zumo')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.mucha-ensalada', 'Hay mucha ensalada y un poco de sopa.', 'There is a lot of salad and a little soup.', [Kit::word('la ensalada', 'ensalada'), Kit::word('la sopa', 'sopa'), Kit::form('mucha', true)], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.prefiero-dulce', 'Prefiero un postre dulce, no picante.', 'I prefer a sweet dessert, not a spicy one.', [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::word('picante'), Kit::form('prefiero')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.postre', [
                Kit::line('Pablo', 'Ana, ¿qué pedimos de postre?'),
                Kit::line('Ana', 'Me apetece algo dulce.'),
                Kit::line('Pablo', 'Hay helado y fruta.'),
                Kit::line('Ana', 'Pido un helado. ¿Y tú?'),
                Kit::line('Pablo', 'Yo no pido postre.'),
            ], [
                Kit::question('What does Ana feel like?', ['Something sweet', 'Something spicy', 'Nothing'], 'Something sweet'),
                Kit::question('What does Ana order?', ['An ice cream', 'Fruit', 'A juice'], 'An ice cream'),
                Kit::question('What does Pablo say about dessert?', ['He wants none', 'He wants fruit', 'He wants an ice cream'], 'He wants none'),
            ], [
                Kit::question('Who asks what to order?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('Is there fruit?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Does Pablo order an ice cream?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::word('el helado', 'helado'), Kit::word('pedir', 'pido')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sopa', 'Read the conversation.', [
                Kit::line('Luis', 'Buenas tardes. Hoy hay sopa y ensalada.'),
                Kit::line('Marta', 'Pido un poco de sopa y una cerveza.'),
                Kit::line('Luis', 'Muy bien. ¿Quieres postre?'),
                Kit::line('Marta', 'Nada, gracias.'),
            ], [
                Kit::question('What does Marta order to drink?', ['A beer', 'A juice', 'Water'], 'A beer'),
                Kit::question('Does Marta want dessert?', ['No', 'Yes, an ice cream', 'Yes, fruit'], 'No'),
            ], [Kit::word('la sopa', 'sopa'), Kit::word('pedir', 'pido'), Kit::word('la cerveza', 'cerveza'), Kit::word('el postre', 'postre')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.zumo-cerveza', '¿Qué pides, un zumo o una cerveza?', 'What do you order, a juice or a beer?', [['pido', 'prefiero'], ['zumo', 'cerveza']], 'Pido un zumo, por favor.', [Kit::word('el zumo', 'zumo'), Kit::word('la cerveza', 'cerveza'), Kit::word('pedir', 'pido')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.plato', '¿Qué plato prefieres, el pescado o la sopa?', 'Which dish do you prefer, the fish or the soup?', [['prefiero'], ['pescado', 'sopa']], 'Prefiero el pescado.', [Kit::word('el plato', 'plato'), Kit::word('la sopa', 'sopa')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.picante', '¿Es dulce el postre?', 'Is the dessert sweet?', [['sí', 'no'], ['es', 'dulce']], 'Sí, es muy dulce.', [Kit::word('el postre', 'postre'), Kit::word('dulce')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.ensalada-zumo', 'I order a little salad and a juice.', ['Pido un poco de ensalada y un zumo.', 'Yo pido un poco de ensalada y un zumo.'], [Kit::word('pedir', 'pido'), Kit::word('la ensalada', 'ensalada'), Kit::word('el zumo', 'zumo'), Kit::form('un poco de')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.sopa-helado', 'I feel like a spicy soup, not an ice cream.', ['Me apetece una sopa picante, no un helado.'], [Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::word('el helado', 'helado'), Kit::form('me apetece')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ana-postre', 'Ana does not order any dessert, she prefers a juice.', ['Ana no pide nada de postre, prefiere un zumo.', 'Ana no pide nada de postre y prefiere un zumo.'], [Kit::word('pedir', 'pide'), Kit::word('el postre', 'postre'), Kit::word('el zumo', 'zumo'), Kit::form('nada de', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.cerveza-postre', 'There is a lot of beer and the dessert is sweet.', ['Hay mucha cerveza y el postre es dulce.'], [Kit::word('la cerveza', 'cerveza'), Kit::word('el postre', 'postre'), Kit::word('dulce'), Kit::form('mucha', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.plato', 'De ___, Ana pide algo dulce.', 'For dessert Ana orders something sweet.', 'postre', Kit::word('el postre', 'postre'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.dulce', 'El postre es muy ___.', 'The dessert is very sweet.', 'dulce', Kit::word('dulce'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.cerveza-ensalada', 'Pedimos una cerveza y una ensalada.', 'We order a beer and a salad.', [Kit::word('pedir', 'pedimos'), Kit::word('la cerveza', 'cerveza'), Kit::word('la ensalada', 'ensalada')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.mucho-zumo', 'Hay mucho zumo y poco helado.', 'There is a lot of juice and little ice cream.', [Kit::word('el zumo', 'zumo'), Kit::word('el helado', 'helado'), Kit::form('mucho', true)], 'dictation', $set, homophoneNote: self::HAY_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.prefiero-plato', 'Prefiero el plato del día, no la sopa picante.', 'I prefer the dish of the day, not the spicy soup.', [Kit::word('el plato', 'plato'), Kit::word('la sopa', 'sopa'), Kit::word('picante'), Kit::form('prefiero')], 'dictation', $set),
        ];
    }
}
