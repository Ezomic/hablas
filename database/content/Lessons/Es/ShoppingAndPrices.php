<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class ShoppingAndPrices implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'shopping-and-prices';
    }

    public function words(): array
    {
        return [
            new WordData('el probador', cue: 'fitting room', forms: ['probadores'], note: 'Probarse means to try on: me pruebo el vestido en el probador.'),
            new WordData('las rebajas', cue: 'sales (reduced prices)', note: 'Rebajas is always plural: las rebajas. Estar en rebajas means to be on sale.'),
            new WordData('la falda', cue: 'skirt', forms: ['faldas']),
            new WordData('el vestido', cue: 'dress', forms: ['vestidos']),
            new WordData('el abrigo', cue: 'coat', forms: ['abrigos']),
            new WordData('los vaqueros', cue: 'jeans', note: 'Vaqueros is plural, like trousers: los vaqueros son largos.'),
            new WordData('el dependiente', cue: 'shop assistant (man)', accepted: ['la dependienta'], forms: ['dependientes', 'dependientas'], note: 'Use el dependiente for a man and la dependienta for a woman.'),
            new WordData('largo', cue: 'long (masculine)', forms: ['larga', 'largos', 'largas'], note: 'Largo means long, not large. Large is grande.'),
            new WordData('corto', cue: 'short, not long (masculine)', forms: ['corta', 'cortos', 'cortas'], note: 'Corto is short in length, for a skirt or a coat. For a person, short in height is bajo.'),
            new WordData('estrecho', cue: 'tight, narrow (masculine)', forms: ['estrecha', 'estrechos', 'estrechas'], note: 'Estrecho says that clothes are tight on you: los vaqueros me quedan estrechos.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'El vestido es demasiado largo.', 'english' => 'The dress is too long.'],
            ['text' => 'Los vaqueros me quedan bien.', 'english' => 'The jeans fit me well.'],
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
            Kit::gap($stage, 'sentences.choose_gap.demasiado', 'El vestido es ___ caro.', ['demasiado', 'muy', 'bastante'], 'demasiado', Kit::form('demasiado', true), 'Too is demasiado. Muy means very and bastante means quite, and neither says that it is a problem.', 'choose', 'The dress is too expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.quedan', 'Los vaqueros me ___ bien.', ['quedan', 'queda', 'quedamos'], 'quedan', Kit::form('quedan'), 'Los vaqueros is plural, so the verb is plural too: quedan. Queda is for one thing.', 'choose', 'The jeans fit me well.'),
            Kit::gap($stage, 'sentences.choose_gap.mal', 'La falda me queda ___.', ['mal', 'bien', 'muy'], 'mal', Kit::form('mal', true), 'Quedar mal says that clothes do not fit or do not look good. Quedar bien says the opposite.', 'choose', 'The skirt fits me badly.'),
            Kit::gap($stage, 'sentences.choose_gap.esta', 'Hoy el abrigo ___ barato.', ['está', 'es'], 'está', Kit::form('está', true), 'Está says what the price is right now, for example in the sales. Es says what something normally costs.', 'choose', 'Today the coat is cheap (it is on sale).'),
            Kit::gap($stage, 'sentences.choose_gap.poco', 'La dependienta es ___ simpática.', ['poco', 'muy', 'demasiado'], 'poco', Kit::form('poco', true), 'Poco before an adjective means not very. Muy simpática would mean the opposite.', 'choose', 'The shop assistant is not very nice.'),
            Kit::gap($stage, 'sentences.choose_gap.estrechos', 'Los vaqueros son demasiado ___.', ['estrechos', 'estrecho', 'estrecha'], 'estrechos', Kit::word('estrecho', 'estrechos'), 'Vaqueros is masculine and plural, so the adjective is estrechos.', 'choose', 'The jeans are too tight.'),

            Kit::typeGap($stage, 'sentences.type_gap.muy-corta', 'La falda es ___ corta.', 'The skirt is very short.', 'muy', Kit::form('muy', true), 'Very is muy. It never changes. Demasiado would mean too.'),
            Kit::typeGap($stage, 'sentences.type_gap.bastante-largo', 'Mi abrigo es ___ largo.', 'My coat is quite long.', 'bastante', Kit::form('bastante'), 'Quite is bastante. It stays the same before any adjective.'),
            Kit::typeGap($stage, 'sentences.type_gap.dependiente', 'El ___ tiene mi talla.', 'The shop assistant has my size.', 'dependiente', Kit::word('el dependiente', 'dependiente')),
            Kit::typeGap($stage, 'sentences.type_gap.probador', 'Voy al ___ con la falda.', 'I go to the fitting room with the skirt.', 'probador', Kit::word('el probador', 'probador')),
            Kit::typeGap($stage, 'sentences.type_gap.rebajas', 'La falda larga está en ___.', 'The long skirt is on sale.', 'rebajas', Kit::word('las rebajas', 'rebajas')),

            Kit::translate($stage, 'sentences.translate.abrigo-rebajas', 'The coat is on sale today.', ['Hoy el abrigo está en rebajas.', 'El abrigo está en rebajas hoy.'], [Kit::word('el abrigo', 'abrigo'), Kit::word('las rebajas', 'rebajas'), Kit::form('está', true)]),
            Kit::translate($stage, 'sentences.translate.vestido-te-queda', 'The dress fits you well.', ['El vestido te queda bien.', 'Te queda bien el vestido.'], [Kit::word('el vestido', 'vestido'), Kit::form('te queda')]),
            Kit::translate($stage, 'sentences.translate.vaqueros-talla', 'The jeans are size forty.', ['Los vaqueros son de la talla cuarenta.', 'Los vaqueros son talla cuarenta.', 'Los vaqueros son de talla cuarenta.'], [Kit::word('los vaqueros', 'vaqueros'), Kit::form('son')]),

            Kit::build($stage, 'sentences.build.vaqueros-largos', 'The jeans are too long.', 'Los vaqueros son demasiado largos.', ['muy'], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('largo', 'largos'), Kit::form('demasiado')]),
            Kit::build($stage, 'sentences.build.dependienta-simpatica', 'The shop assistant (a woman) is very nice.', 'La dependienta es muy simpática.', ['bastante'], [Kit::word('el dependiente', 'dependienta'), Kit::form('muy')]),
            Kit::build($stage, 'sentences.build.vestido-corto', 'The dress is quite short.', 'El vestido es bastante corto.', ['demasiado'], [Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::form('bastante')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vaqueros-estrechos', 'Los vaqueros me quedan estrechos.', ['The jeans are tight on me.', 'The jeans are long on me.', 'The jeans are too expensive for me.', 'The jeans are on sale.'], 'The jeans are tight on me.', [Kit::word('los vaqueros', 'vaqueros'), Kit::word('estrecho', 'estrechos'), Kit::form('quedan')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.abrigo-rebajas', 'El abrigo está en rebajas.', ['The coat is on sale.', 'The coat is expensive.', 'The coat is long.', 'The coat is in the fitting room.'], 'The coat is on sale.', [Kit::word('el abrigo', 'abrigo'), Kit::word('las rebajas', 'rebajas'), Kit::form('está')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.falda-corta', 'La falda es demasiado corta.', ['The skirt is too short.', 'The skirt is very short.', 'The skirt is too long.', 'The skirt is quite long.'], 'The skirt is too short.', [Kit::word('la falda', 'falda'), Kit::word('corto', 'corta'), Kit::form('demasiado')]),
            Kit::listenType($stage, 'sentences.listen_type.vestido-probador', 'Me pruebo el vestido en el probador.', 'I try on the dress in the fitting room.', [Kit::word('el vestido', 'vestido'), Kit::word('el probador', 'probador')]),
            Kit::listenType($stage, 'sentences.listen_type.dependienta-talla', 'La dependienta tiene mi talla.', 'The shop assistant has my size.', [Kit::word('el dependiente', 'dependienta')]),
            Kit::listenType($stage, 'sentences.listen_type.abrigo-muy-largo', 'El abrigo es muy largo.', 'The coat is very long.', [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::form('muy')]),
            Kit::listenType($stage, 'sentences.listen_type.falda-un-poco', 'La falda es un poco corta.', 'The skirt is a bit short.', [Kit::word('la falda', 'falda'), Kit::word('corto', 'corta'), Kit::form('poco')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.falda-probador', 'Me pruebo la falda en el probador.', 'I try on the skirt in the fitting room.', [Kit::word('la falda', 'falda'), Kit::word('el probador', 'probador')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.rebajas-tienda', 'Hoy hay rebajas en la tienda.', 'Today there are sales in the shop.', [Kit::word('las rebajas', 'rebajas')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.abrigo-bastante', 'El abrigo es bastante largo.', 'The coat is quite long.', [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::form('bastante')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.vestido-corto', 'El vestido corto me queda bien.', 'The short dress fits me well.', [Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::form('queda')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.abrigo', '¿Está el abrigo en rebajas?', 'Is the coat on sale?', [['sí', 'no'], ['rebajas', 'está']], 'Sí, está en rebajas.', [Kit::word('el abrigo', 'abrigo'), Kit::word('las rebajas', 'rebajas'), Kit::form('está', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.falda', '¿Es larga o corta la falda?', 'Is the skirt long or short?', [['falda', 'es'], ['larga', 'corta']], 'La falda es larga.', [Kit::word('la falda', 'falda'), Kit::word('largo', 'larga'), Kit::word('corto', 'corta')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vaqueros', '¿Son estrechos los vaqueros?', 'Are the jeans tight?', [['sí', 'no'], ['estrechos', 'largos', 'cortos']], 'Sí, son estrechos.', [Kit::word('los vaqueros', 'vaqueros'), Kit::word('estrecho', 'estrechos')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.vaqueros', 'Read the conversation in the shop.', [
                Kit::line('Ana', 'Hola, ¿tienes vaqueros en la talla cuarenta?'),
                Kit::line('Dependiente', 'Sí. Hoy están en rebajas y cuestan treinta y cinco euros.'),
                Kit::line('Ana', 'Me pruebo los vaqueros en el probador.'),
                Kit::line('Dependiente', '¿Qué tal te quedan?'),
                Kit::line('Ana', 'Me quedan estrechos y un poco cortos.'),
                Kit::line('Dependiente', 'Tengo la talla cuarenta y dos.'),
            ], [
                Kit::question('What does Ana ask for?', ['Jeans', 'A skirt', 'A coat'], 'Jeans'),
                Kit::question('How do the first jeans fit Ana?', ['Tight and a bit short', 'Too long', 'Very well'], 'Tight and a bit short'),
                Kit::question('What does the shop assistant offer?', ['Another size', 'A discount card', 'A skirt'], 'Another size'),
            ], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('las rebajas', 'rebajas'), Kit::word('el probador', 'probador'), Kit::word('estrecho', 'estrechos'), Kit::word('corto', 'cortos'), Kit::word('el dependiente', 'dependiente'), Kit::form('poco')], 'read'),
            Kit::gap($stage, 'task.choose_gap.normalmente', 'Normalmente el abrigo ___ caro, pero hoy está barato.', ['es', 'está'], 'es', Kit::form('es', true), 'Normalmente points to the normal price, so es. Está is for the price today.', 'read', 'Normally the coat is expensive, but today it is cheap.'),
            Kit::gap($stage, 'task.choose_gap.vestido-queda', 'El vestido me ___ mal: es demasiado largo.', ['queda', 'quedan', 'quedamos'], 'queda', Kit::form('queda'), 'El vestido is one thing, so the verb is queda. Quedan is for more than one.', 'read', 'The dress does not fit me: it is too long.'),

            Kit::transform($stage, 'task.transform.falda-larga', 'Say that it is too long.', 'La falda es larga.', ['La falda es demasiado larga.'], [Kit::word('la falda', 'falda'), Kit::word('largo', 'larga'), Kit::form('demasiado', true)]),
            Kit::transform($stage, 'task.transform.vaqueros-mal', 'Say that the jeans fit you badly.', 'Los vaqueros te quedan bien.', ['Los vaqueros te quedan mal.', 'Te quedan mal los vaqueros.'], [Kit::word('los vaqueros', 'vaqueros'), Kit::form('mal', true)]),
            Kit::transform($stage, 'task.transform.falda-rebajas', 'Say that today it is on sale.', 'Normalmente la falda es cara.', ['Hoy la falda está en rebajas.', 'La falda está en rebajas hoy.', 'Hoy está en rebajas la falda.'], [Kit::word('la falda', 'falda'), Kit::word('las rebajas', 'rebajas'), Kit::form('está', true)]),
            Kit::writeGuided($stage, 'task.write_guided.vestido-corto', 'Say that the dress is quite short, but it fits me well.', ['el vestido', 'bastante corto', 'pero', 'me queda bien'], 'El vestido es bastante corto, pero me queda bien.', [
                ['forms' => ['vestido'], 'term' => 'el vestido'],
                ['forms' => ['corto'], 'term' => 'corto'],
                ['forms' => ['bastante'], 'term' => null],
            ], [Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::form('bastante')]),
            Kit::writeGuided($stage, 'task.write_guided.vaqueros-rebajas', 'Ask whether the jeans are on sale.', ['los vaqueros', 'están', 'en rebajas'], '¿Están los vaqueros en rebajas?', [
                ['forms' => ['vaqueros'], 'term' => 'los vaqueros'],
                ['forms' => ['rebajas'], 'term' => 'las rebajas'],
                ['forms' => ['están'], 'term' => null],
            ], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('las rebajas', 'rebajas'), Kit::form('están', true)]),
            Kit::build($stage, 'task.build.abrigo-corto-estrecho', 'The coat is too short and very tight.', 'El abrigo es demasiado corto y muy estrecho.', ['poco', 'bastante'], [Kit::word('el abrigo', 'abrigo'), Kit::word('corto', 'corto'), Kit::word('estrecho', 'estrecho'), Kit::form('demasiado')]),
            Kit::build($stage, 'task.build.talla-vestido', 'My size is forty, but the dress is too long.', 'Mi talla es la cuarenta, pero el vestido es demasiado largo.', ['muy', 'mal'], [Kit::word('el vestido', 'vestido'), Kit::word('largo', 'largo'), Kit::form('es')]),
            Kit::build($stage, 'task.build.dependienta-talla', 'The shop assistant (a woman) has the dress in size forty.', 'La dependienta tiene el vestido en la talla cuarenta.', ['dos', 'muy'], [Kit::word('el dependiente', 'dependienta'), Kit::word('el vestido', 'vestido')]),
            Kit::translate($stage, 'task.translate.vaqueros-rebajas-estrechos', 'The jeans are on sale, but they are too tight.', ['Los vaqueros están en rebajas, pero son demasiado estrechos.'], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('las rebajas', 'rebajas'), Kit::word('estrecho', 'estrechos'), Kit::form('demasiado')]),
            Kit::translate($stage, 'task.translate.falda-probador', 'I try on the skirt in the fitting room and it fits me well.', ['Me pruebo la falda en el probador y me queda bien.'], [Kit::word('la falda', 'falda'), Kit::word('el probador', 'probador'), Kit::form('queda')]),

            Kit::listenPassage($stage, 'task.listen_passage.vestido-ana', [
                Kit::line('Marta', 'Luis, ¿vamos a la tienda? Hoy hay rebajas.'),
                Kit::line('Luis', 'Sí, quisiera un vestido para Ana.'),
                Kit::line('Marta', 'En la tienda, el dependiente tiene vestidos largos y cortos.'),
                Kit::line('Luis', 'Ana es alta, y su talla es cuarenta.'),
            ], [
                Kit::question('Where do Marta and Luis go?', ['To a shop', 'To a restaurant', 'To the cinema'], 'To a shop'),
                Kit::question('What does Luis want to buy?', ['A dress for Ana', 'A coat for Marta', 'Jeans for himself'], 'A dress for Ana'),
                Kit::question('What does the shop assistant have?', ['Long and short dresses', 'Only coats', 'Only jeans'], 'Long and short dresses'),
            ], [
                Kit::question('Why do they go today?', ['There are sales', 'It is a birthday', 'The shop is near'], 'There are sales'),
                Kit::question('What size is Ana?', ['Forty', 'Fifty', 'Thirty'], 'Forty'),
                Kit::question('Who is tall?', ['Ana', 'Marta', 'Luis'], 'Ana'),
            ], [Kit::word('las rebajas', 'rebajas'), Kit::word('el vestido', 'vestidos'), Kit::word('el dependiente', 'dependiente'), Kit::word('largo', 'largos'), Kit::word('corto', 'cortos')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vestido-largo', 'El vestido largo es bastante caro, pero está en rebajas.', 'The long dress is quite expensive, but it is on sale.', [Kit::word('el vestido', 'vestido'), Kit::word('largo', 'largo'), Kit::word('las rebajas', 'rebajas'), Kit::form('bastante')]),
            Kit::listenType($stage, 'task.listen_type.falda-vaqueros', 'La falda es corta y los vaqueros son estrechos.', 'The skirt is short and the jeans are tight.', [Kit::word('la falda', 'falda'), Kit::word('corto', 'corta'), Kit::word('los vaqueros', 'vaqueros'), Kit::word('estrecho', 'estrechos')]),
            Kit::listenType($stage, 'task.listen_type.abrigo-mal', 'El abrigo me queda mal: es demasiado largo.', 'The coat fits me badly, it is too long.', [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::form('mal', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.vestido-precio', '¿Cuánto cuesta el vestido?', 'How much does the dress cost?', [['cuesta', 'vestido'], ['euros', 'cuarenta', 'treinta', 'cincuenta', 'cien']], 'El vestido cuesta cuarenta euros.', [Kit::word('el vestido', 'vestido')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vaqueros-precio', '¿Cuánto cuestan los vaqueros?', 'How much do the jeans cost?', [['cuestan', 'vaqueros'], ['euros', 'cuarenta', 'treinta', 'cincuenta', 'cien']], 'Los vaqueros cuestan treinta euros.', [Kit::word('los vaqueros', 'vaqueros'), Kit::form('cuestan')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.falda-queda', '¿Cómo te queda la falda?', 'How does the skirt fit you?', [['queda', 'me'], ['bien', 'mal', 'larga', 'corta']], 'Me queda bien.', [Kit::word('la falda', 'falda'), Kit::word('largo', 'larga'), Kit::form('queda')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.probador', '¿Dónde está el probador?', 'Where is the fitting room?', [['está', 'probador', 'aquí', 'allí'], ['aquí', 'allí', 'cerca']], 'El probador está allí.', [Kit::word('el probador', 'probador'), Kit::form('está')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.dependiente-rebajas', 'Hoy hay rebajas y el dependiente es simpático.', 'Today there are sales and the shop assistant is nice.', [Kit::word('el dependiente', 'dependiente'), Kit::word('las rebajas', 'rebajas')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.vaqueros-cortos', 'Los vaqueros me quedan estrechos y un poco cortos.', 'The jeans are tight on me and a bit short.', [Kit::word('los vaqueros', 'vaqueros'), Kit::word('estrecho', 'estrechos'), Kit::word('corto', 'cortos'), Kit::form('poco')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.falda-vestido', 'The skirt is long and the dress is too short.', ['La falda es larga y el vestido es demasiado corto.'], [Kit::word('la falda', 'falda'), Kit::word('largo', 'larga'), Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::form('demasiado', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.abrigo-vaqueros', 'The jeans are on sale, but the coat is expensive.', ['Los vaqueros están en rebajas, pero el abrigo es caro.'], [Kit::word('el abrigo', 'abrigo'), Kit::word('las rebajas', 'rebajas'), Kit::word('los vaqueros', 'vaqueros'), Kit::form('están', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.dependienta-probador', 'The fitting room is here and the shop assistant (a woman) is there.', ['El probador está aquí y la dependienta está allí.'], [Kit::word('el probador', 'probador'), Kit::word('el dependiente', 'dependienta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vaqueros-mal', 'The jeans fit me badly and the dress is cheap.', ['Los vaqueros me quedan mal y el vestido es barato.', 'Los vaqueros me quedan mal y el vestido está barato.'], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('el vestido', 'vestido'), Kit::form('mal', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.vaqueros-estrechos', 'El abrigo es muy ___.', 'The coat is very tight.', 'estrecho', Kit::word('estrecho', 'estrecho'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.muy-larga', 'El abrigo es ___ corto.', 'The coat is too short.', 'demasiado', Kit::form('demasiado'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.dependiente-falda', 'El dependiente tiene una falda en el probador.', 'The shop assistant has a skirt in the fitting room.', [Kit::word('el dependiente', 'dependiente'), Kit::word('la falda', 'falda'), Kit::word('el probador', 'probador')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.abrigo-largo', 'El abrigo es bastante largo y está en rebajas.', 'The coat is quite long and it is on sale.', [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::word('las rebajas', 'rebajas'), Kit::form('bastante')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.vestido-poco', 'El vestido es un poco corto y estrecho.', 'The dress is a bit short and tight.', [Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::word('estrecho', 'estrecho'), Kit::form('poco')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.abrigo-probador', [
                Kit::line('Marta', 'Pablo, hoy hay rebajas aquí, en la tienda.'),
                Kit::line('Pablo', 'Muy bien. Quisiera un abrigo.'),
                Kit::line('Marta', 'El abrigo largo es caro, pero está en rebajas.'),
                Kit::line('Pablo', 'Me pruebo el abrigo en el probador.'),
                Kit::line('Marta', '¿Qué tal te queda?'),
                Kit::line('Pablo', 'Me queda bien de largo, pero es un poco estrecho.'),
            ], [
                Kit::question('What does Pablo want to buy?', ['A coat', 'A dress', 'Jeans'], 'A coat'),
                Kit::question('Why is the coat cheaper today?', ['It is on sale', 'It is old', 'It is short'], 'It is on sale'),
                Kit::question('How does the coat fit Pablo?', ['Well, but a bit tight', 'Badly, it is too long', 'It is too short'], 'Well, but a bit tight'),
            ], [
                Kit::question('Where does Pablo try on the coat?', ['In the fitting room', 'At home', 'In the street'], 'In the fitting room'),
                Kit::question('Is the coat expensive?', ['Yes, but it is on sale', 'No, it is cheap', 'The conversation does not say.'], 'Yes, but it is on sale'),
                Kit::question('Who is in the shop?', ['Marta and Pablo', 'Ana and Luis', 'Only Pablo'], 'Marta and Pablo'),
            ], [Kit::word('las rebajas', 'rebajas'), Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::word('el probador', 'probador'), Kit::word('estrecho', 'estrecho')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.faldas', 'Read the conversation.', [
                Kit::line('Ana', 'Buenas tardes. ¿Tiene faldas en mi talla?'),
                Kit::line('Dependiente', 'Sí. La falda larga está en rebajas hoy.'),
                Kit::line('Ana', 'Me pruebo la falda. ¿Está lejos el probador?'),
                Kit::line('Dependiente', 'No, el probador está aquí, a la derecha.'),
            ], [
                Kit::question('What does Ana ask about?', ['Skirts', 'Jeans', 'Coats'], 'Skirts'),
                Kit::question('Which skirt is on sale?', ['The long one', 'The short one', 'All of them'], 'The long one'),
                Kit::question('Where is the fitting room?', ['To the right', 'To the left', 'Straight ahead'], 'To the right'),
            ], [Kit::word('la falda', 'faldas'), Kit::word('el dependiente', 'dependiente'), Kit::word('largo', 'larga'), Kit::word('las rebajas', 'rebajas'), Kit::word('el probador', 'probador')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vestido-rebajas', '¿Está el vestido en rebajas?', 'Is the dress on sale?', [['sí', 'no'], ['rebajas', 'vestido']], 'Sí, el vestido está en rebajas.', [Kit::word('el vestido', 'vestido'), Kit::word('las rebajas', 'rebajas'), Kit::form('está')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.abrigo-queda', '¿Cómo te queda el abrigo?', 'How does the coat fit you?', [['queda', 'me'], ['bien', 'mal', 'largo', 'corto', 'estrecho']], 'Me queda bien.', [Kit::word('el abrigo', 'abrigo'), Kit::form('queda')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.dependienta', '¿Es simpática la dependienta?', 'Is the shop assistant nice?', [['sí', 'no'], ['simpática', 'simpático', 'es']], 'Sí, es muy simpática.', [Kit::word('el dependiente', 'dependienta'), Kit::form('muy')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.abrigo-vestido', 'The coat is too long, but the dress is short.', ['El abrigo es demasiado largo, pero el vestido es corto.'], [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::form('demasiado', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.falda-vaqueros', 'The skirt and the jeans are on sale.', ['La falda y los vaqueros están en rebajas.'], [Kit::word('la falda', 'falda'), Kit::word('los vaqueros', 'vaqueros'), Kit::word('las rebajas', 'rebajas'), Kit::form('están', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.vaqueros-muy', 'The jeans are very tight on me.', ['Los vaqueros me quedan muy estrechos.'], [Kit::word('los vaqueros', 'vaqueros'), Kit::word('estrecho', 'estrechos'), Kit::form('muy')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.dependienta-simpatica', 'The shop assistant (a woman) is nice, but the fitting room is far.', ['La dependienta es simpática, pero el probador está lejos.'], [Kit::word('el dependiente', 'dependienta'), Kit::word('el probador', 'probador')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.vestido-queda', 'La falda me ___ bien.', 'The skirt fits me well.', 'queda', Kit::form('queda'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.probador', 'El ___ está lejos.', 'The fitting room is far.', 'probador', Kit::word('el probador', 'probador'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.falda-un-poco', 'Mi abrigo es un poco largo, pero me queda bien.', 'My coat is a bit long, but it fits me well.', [Kit::word('el abrigo', 'abrigo'), Kit::word('largo', 'largo'), Kit::form('poco')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.abrigo-normalmente', 'Normalmente el abrigo es caro, pero hoy está en rebajas.', 'Normally the coat is expensive, but today it is on sale.', [Kit::word('el abrigo', 'abrigo'), Kit::word('las rebajas', 'rebajas'), Kit::form('es', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.dependiente-vestido', 'El dependiente tiene un vestido corto y estrecho.', 'The shop assistant has a short and tight dress.', [Kit::word('el dependiente', 'dependiente'), Kit::word('el vestido', 'vestido'), Kit::word('corto', 'corto'), Kit::word('estrecho', 'estrecho')], 'dictation', $set),
        ];
    }
}
