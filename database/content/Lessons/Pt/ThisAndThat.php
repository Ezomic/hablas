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

final class ThisAndThat implements UnitContent
{
    private const array THINGS = ['copo', 'mala', 'carro', 'mochila', 'foto', 'presente', 'guarda', 'caneta', 'chapéu'];

    private const array NAMES = ['ana', 'joão', 'marta', 'rui'];

    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'this-and-that';
    }

    public function words(): array
    {
        return [
            new WordData('o copo', cue: 'glass (for drinking)', forms: ['copos'], portunolSlips: ['el vaso', 'vaso']),
            new WordData('a mala', cue: 'bag, handbag', forms: ['malas'], portunolSlips: ['el bolso', 'bolso'], note: 'Mala is feminine: esta mala, not este mala.'),
            new WordData('o carro', cue: 'car', forms: ['carros'], portunolSlips: ['el coche', 'coche']),
            new WordData('a mochila', cue: 'backpack', forms: ['mochilas']),
            new WordData('a foto', cue: 'photo', accepted: ['a fotografia'], forms: ['fotos']),
            new WordData('o presente', cue: 'gift, present', accepted: ['a prenda'], forms: ['presentes'], portunolSlips: ['el regalo', 'regalo']),
            new WordData('o guarda-chuva', cue: 'umbrella', forms: ['guarda-chuvas'], portunolSlips: ['el paraguas', 'paraguas']),
            new WordData('a caneta', cue: 'pen', forms: ['canetas'], portunolSlips: ['el bolígrafo', 'bolígrafo'], note: 'Caneta is feminine: esta caneta, not este caneta.'),
            new WordData('o chapéu', cue: 'hat', forms: ['chapéus'], portunolSlips: ['el sombrero', 'sombrero']),
            new WordData('preferir', cue: 'to prefer', forms: ['prefiro', 'preferes', 'prefere'], portunolSlips: ['prefiero'], note: 'Only eu changes the stem (e becomes i): prefiro, but preferes, prefere.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Esta mochila é da Ana.', 'english' => 'This backpack is Ana\'s.'],
            ['text' => 'Qual preferes, esse chapéu ou aquele carro?', 'english' => 'Which do you prefer, that hat or that car over there?'],
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
        $note = 'The first a is the article a (the), not à (to the) and not há (there is).';

        return [
            Kit::gap($stage, 'sentences.choose_gap.copo', '___ copo é da Ana.', ['Este', 'Esta', 'Estes'], 'Este', Kit::form('este', true), 'Copo is masculine and singular, so this is este. Esta is for feminine words and estes is for several things.', 'choose', 'This glass is Ana\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.mochila', '___ mochila é do João.', ['Esta', 'Este', 'Estas'], 'Esta', Kit::form('esta', true), 'Mochila is feminine and singular, so this is esta. Este is for masculine words and estas is for several things.', 'choose', 'This backpack is João\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.carros', '___ carros são do Rui.', ['Esses', 'Esse', 'Essas'], 'Esses', Kit::form('esses', true), 'Carros is masculine and plural, so that is esses. Esse is for one thing and essas is for feminine words.', 'choose', 'Those cars (near you) are Rui\'s.'),
            Kit::gap($stage, 'sentences.choose_gap.foto', 'De quem é ___ foto?', ['essa', 'esse', 'essas'], 'essa', Kit::form('essa', true), 'Foto is feminine and singular, so that is essa. Esse is for masculine words and essas is for several things.', 'choose', 'Whose photo is that (near you)?'),
            Kit::gap($stage, 'sentences.choose_gap.isto', 'O que é ___?', ['isto', 'este', 'esta'], 'isto', Kit::form('isto', true), 'When you do not know the thing, use the neutral isto. It has no gender, so este and esta do not fit.', 'choose', 'What is this?'),
            Kit::gap($stage, 'sentences.choose_gap.prefiro', 'Eu ___ este carro.', ['prefiro', 'preferes', 'prefere'], 'prefiro', Kit::word('preferir', 'prefiro'), 'Eu goes with prefiro. Preferes is for tu and prefere is for ele or ela.', 'choose', 'I prefer this car.'),

            Kit::typeGap($stage, 'sentences.type_gap.mala', '___ mala é da Marta.', 'This bag is Marta\'s.', 'Esta', Kit::form('esta'), 'Mala is feminine and singular, so this is esta.'),
            Kit::typeGap($stage, 'sentences.type_gap.preferes', 'Qual ___, este copo ou esse copo?', 'Which do you prefer, this glass (near me) or that glass (near you)? (informal you)', 'preferes', Kit::word('preferir', 'preferes')),
            Kit::typeGap($stage, 'sentences.type_gap.canetas', 'Estas ___ são da Ana.', 'These pens are Ana\'s.', 'canetas', Kit::word('a caneta', 'canetas')),
            Kit::typeGap($stage, 'sentences.type_gap.presente', '___ presente é para o Rui.', 'That gift (near you) is for Rui.', 'Esse', Kit::form('esse'), 'Presente is masculine and singular, so that is esse.'),
            Kit::typeGap($stage, 'sentences.type_gap.isso', 'O que é ___? É um guarda-chuva.', 'What is that (near you)? It is an umbrella.', 'isso', Kit::form('isso'), 'The neutral isso has no gender, so it is right when you have not named the thing.'),

            Kit::translate($stage, 'sentences.translate.caneta', 'This pen is Rui\'s.', ['Esta caneta é do Rui.', 'Esta caneta é de Rui.'], [Kit::word('a caneta', 'caneta'), Kit::form('esta')]),
            Kit::translate($stage, 'sentences.translate.chapeu', 'I prefer that hat (near you).', ['Prefiro esse chapéu.', 'Eu prefiro esse chapéu.'], [Kit::word('o chapéu', 'chapéu'), Kit::word('preferir', 'prefiro'), Kit::form('esse')]),
            Kit::translate($stage, 'sentences.translate.isto', 'What is this? It is a gift.', ['O que é isto? É um presente.'], [Kit::word('o presente', 'presente'), Kit::form('isto')]),

            Kit::build($stage, 'sentences.build.foto', 'That photo (near you) is Marta\'s.', 'Essa foto é da Marta.', ['esse'], [Kit::word('a foto', 'foto'), Kit::form('essa')]),
            Kit::build($stage, 'sentences.build.mochila', 'I prefer this backpack.', 'Prefiro esta mochila.', ['este'], [Kit::word('a mochila', 'mochila'), Kit::word('preferir', 'prefiro'), Kit::form('esta')]),
            Kit::build($stage, 'sentences.build.mala', 'Whose bag is this?', 'De quem é esta mala?', ['este'], [Kit::word('a mala', 'mala'), Kit::form('esta')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.mochila', 'Esta mochila é da Marta.', ['This backpack is Marta\'s.', 'That backpack is Marta\'s.', 'This backpack is Ana\'s.', 'These backpacks are Marta\'s.'], 'This backpack is Marta\'s.', [Kit::word('a mochila', 'mochila'), Kit::form('esta')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.isso', 'O que é isso?', ['What is that?', 'What is this?', 'Who is that?', 'Where is that?'], 'What is that?', [Kit::form('isso')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.copo', 'Prefiro este copo.', ['I prefer this glass.', 'I prefer that glass.', 'You prefer this glass.', 'I prefer these glasses.'], 'I prefer this glass.', [Kit::word('o copo', 'copo'), Kit::word('preferir', 'prefiro'), Kit::form('este')]),
            Kit::listenType($stage, 'sentences.listen_type.carros', 'Esses carros são do João.', 'Those cars (near you) are João\'s.', [Kit::word('o carro', 'carros'), Kit::form('esses')]),
            Kit::listenType($stage, 'sentences.listen_type.mala-chapeu', 'Qual preferes, a mala ou o chapéu?', 'Which do you prefer, the bag or the hat? (informal you)', [Kit::word('a mala', 'mala'), Kit::word('o chapéu', 'chapéu'), Kit::word('preferir', 'preferes')], homophoneNote: 'The a before mala is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.foto-presente', 'Esta foto é um presente.', 'This photo is a gift.', [Kit::word('a foto', 'foto'), Kit::word('o presente', 'presente'), Kit::form('esta')]),
            Kit::listenType($stage, 'sentences.listen_type.guarda-chuva', 'Esse guarda-chuva é da Marta.', 'That umbrella (near you) is Marta\'s.', [Kit::word('o guarda-chuva', 'guarda-chuva'), Kit::form('esse')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.copo', 'Este copo é da Ana.', 'This glass is Ana\'s.', [Kit::word('o copo', 'copo'), Kit::form('este')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mala', 'Prefiro esta mala.', 'I prefer this bag.', [Kit::word('a mala', 'mala'), Kit::word('preferir', 'prefiro'), Kit::form('esta')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.mochila', 'Essa mochila é da Marta.', 'That backpack (near you) is Marta\'s.', [Kit::word('a mochila', 'mochila'), Kit::form('essa')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.guarda-chuva', 'O que é isso? É um guarda-chuva.', 'What is that? It is an umbrella.', [Kit::word('o guarda-chuva', 'guarda-chuva'), Kit::form('isso')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.isto', 'O que é isto?', 'What is this?', [['é', 'um', 'uma'], self::THINGS], 'É um presente.', [Kit::word('o presente', 'presente'), Kit::form('isto')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.mala-mochila', 'Qual preferes, a mala ou a mochila?', 'Which do you prefer, the bag or the backpack?', [['prefiro', 'este', 'esta', 'esse', 'essa'], ['mala', 'mochila']], 'Prefiro a mala.', [Kit::word('a mala', 'mala'), Kit::word('preferir', 'prefiro')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.carro', 'De quem é o carro?', 'Whose car is it?', [['é', 'do', 'da'], self::NAMES], 'É do Rui.', [Kit::word('o carro', 'carro')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.presente', 'Read the conversation.', [
                Kit::line('Ana', 'João, qual preferes, esta mala ou essa mala?'),
                Kit::line('João', 'Prefiro essa mala. É um presente?'),
                Kit::line('Ana', 'Sim, é um presente para a Marta. Estas canetas são do Rui.'),
            ], [
                Kit::question('What does João prefer?', ['That bag', 'This bag', 'The backpack'], 'That bag'),
                Kit::question('Who is the gift for?', ['Marta', 'Rui', 'João'], 'Marta'),
                Kit::question('Whose are the pens?', ['Rui\'s', 'Marta\'s', 'João\'s'], 'Rui\'s'),
            ], [Kit::word('a mala', 'mala'), Kit::word('preferir', 'prefiro'), Kit::word('o presente', 'presente'), Kit::word('a caneta', 'canetas')], 'read'),
            Kit::gap($stage, 'task.choose_gap.fotos', '___ fotos são da Ana.', ['Estas', 'Estes', 'Esta'], 'Estas', Kit::form('estas', true), 'Fotos is feminine and plural, so this is estas. Estes is for masculine words and esta is for one thing.', 'read', 'These photos are Ana\'s.'),
            Kit::gap($stage, 'task.choose_gap.mochilas', '___ mochilas são do João.', ['Essas', 'Esses', 'Essa'], 'Essas', Kit::form('essas', true), 'Mochilas is feminine and plural, so that is essas. Esses is for masculine words and essa is for one thing.', 'read', 'Those backpacks (near you) are João\'s.'),

            Kit::transform($stage, 'task.transform.malas', 'Change it to several things: these.', 'Esta mala é da Ana.', ['Estas malas são da Ana.'], [Kit::word('a mala', 'malas'), Kit::form('estas', true)]),
            Kit::transform($stage, 'task.transform.mochila', 'Now it is near the person you talk to: use that.', 'Esta mochila é da Marta.', ['Essa mochila é da Marta.'], [Kit::word('a mochila', 'mochila'), Kit::form('essa')]),
            Kit::transform($stage, 'task.transform.fotos', 'Change it to several things: those (near you).', 'Essa foto é do Rui.', ['Essas fotos são do Rui.'], [Kit::word('a foto', 'fotos'), Kit::form('essas', true)]),
            Kit::writeGuided($stage, 'task.write_guided.chapeu-mala', 'Say that you prefer this hat and that bag (near you).', ['prefiro', 'este', 'chapéu', 'essa', 'mala'], 'Prefiro este chapéu e essa mala.', [
                ['forms' => ['prefiro'], 'term' => 'preferir'],
                ['forms' => ['este'], 'term' => null],
                ['forms' => ['chapéu'], 'term' => 'o chapéu'],
                ['forms' => ['essa'], 'term' => null],
                ['forms' => ['mala'], 'term' => 'a mala'],
            ], [Kit::word('preferir', 'prefiro'), Kit::word('o chapéu', 'chapéu'), Kit::word('a mala', 'mala'), Kit::form('este')]),
            Kit::writeGuided($stage, 'task.write_guided.canetas-presente', 'Say that these pens are Rui\'s and that this gift is for Ana.', ['estas', 'canetas', 'este', 'presente', 'para'], 'Estas canetas são do Rui e este presente é para a Ana.', [
                ['forms' => ['estas'], 'term' => null],
                ['forms' => ['canetas'], 'term' => 'a caneta'],
                ['forms' => ['este'], 'term' => null],
                ['forms' => ['presente'], 'term' => 'o presente'],
            ], [Kit::word('a caneta', 'canetas'), Kit::word('o presente', 'presente'), Kit::form('estas')]),
            Kit::build($stage, 'task.build.canetas', 'Whose pens are those (near you)?', 'De quem são essas canetas?', ['esse', 'esta'], [Kit::word('a caneta', 'canetas'), Kit::form('essas')], 'write'),
            Kit::build($stage, 'task.build.foto-presente', 'This photo is a gift for Rui.', 'Esta foto é um presente para o Rui.', ['este', 'esses'], [Kit::word('a foto', 'foto'), Kit::word('o presente', 'presente'), Kit::form('esta')], 'write'),
            Kit::build($stage, 'task.build.joao-mochila', 'João prefers that backpack (near you).', 'João prefere essa mochila.', ['esse', 'prefiro'], [Kit::word('preferir', 'prefere'), Kit::word('a mochila', 'mochila'), Kit::form('essa')], 'write'),
            Kit::translate($stage, 'task.translate.copo', 'I prefer this glass, but João prefers that glass (near you).', ['Prefiro este copo, mas o João prefere esse copo.', 'Eu prefiro este copo, mas o João prefere esse copo.', 'Prefiro este copo, mas João prefere esse copo.', 'Eu prefiro este copo, mas João prefere esse copo.'], [Kit::word('o copo', 'copo'), Kit::word('preferir', 'prefiro'), Kit::form('este')], 'write'),
            Kit::translate($stage, 'task.translate.mochila', 'Whose backpack is that (near you)? It is Marta\'s.', ['De quem é essa mochila? É da Marta.', 'De quem é essa mochila? É de Marta.'], [Kit::word('a mochila', 'mochila'), Kit::form('essa')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.mochila', [
                Kit::line('Rui', 'Marta, de quem é essa mochila?'),
                Kit::line('Marta', 'É da Ana. Estas canetas também são da Ana.'),
                Kit::line('Rui', 'E este guarda-chuva, é do João?'),
                Kit::line('Marta', 'Sim, é do João.'),
            ], [
                Kit::question('Whose is the backpack?', ['Ana\'s', 'Marta\'s', 'João\'s'], 'Ana\'s'),
                Kit::question('Whose are the pens?', ['Ana\'s', 'Rui\'s', 'João\'s'], 'Ana\'s'),
                Kit::question('Whose is the umbrella?', ['João\'s', 'Ana\'s', 'Marta\'s'], 'João\'s'),
            ], [
                Kit::question('Who asks about the backpack?', ['Rui', 'Marta', 'João'], 'Rui'),
                Kit::question('Is the backpack Marta\'s?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('a mochila', 'mochila'), Kit::word('a caneta', 'canetas'), Kit::word('o guarda-chuva', 'guarda-chuva')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.fotos-presentes', 'Estas fotos são presentes para o Rui.', 'These photos are gifts for Rui.', [Kit::word('a foto', 'fotos'), Kit::word('o presente', 'presentes'), Kit::form('estas')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.carro', 'Qual preferes, este carro ou esse carro?', 'Which do you prefer, this car (near me) or that car (near you)? (informal you)', [Kit::word('o carro', 'carro'), Kit::word('preferir', 'preferes'), Kit::form('este')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.mochila-copo', 'Prefiro essa mochila, não este copo.', 'I prefer that backpack (near you), not this glass.', [Kit::word('a mochila', 'mochila'), Kit::word('o copo', 'copo'), Kit::word('preferir', 'prefiro'), Kit::form('essa')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.isso', 'O que é isso?', 'What is that?', [['é', 'um', 'uma'], self::THINGS], 'É um guarda-chuva.', [Kit::word('o guarda-chuva', 'guarda-chuva'), Kit::form('isso')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.chapeu-guarda-chuva', 'Qual preferes, o chapéu ou o guarda-chuva?', 'Which do you prefer, the hat or the umbrella?', [['prefiro', 'este', 'esta', 'esse', 'essa'], ['chapéu', 'guarda']], 'Prefiro o chapéu.', [Kit::word('o chapéu', 'chapéu'), Kit::word('preferir', 'prefiro')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mochila', 'De quem é a mochila?', 'Whose backpack is it?', [['é', 'do', 'da'], self::NAMES], 'É da Marta.', [Kit::word('a mochila', 'mochila')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.caneta', 'De quem são as canetas?', 'Whose pens are they?', [['são', 'do', 'da'], self::NAMES], 'São do João.', [Kit::word('a caneta', 'canetas')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.carros', 'Prefiro esses carros.', 'I prefer those cars (near you).', [Kit::word('o carro', 'carros'), Kit::word('preferir', 'prefiro'), Kit::form('esses')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.copo', 'Prefiro esse copo.', 'I prefer that glass (near you).', [Kit::word('o copo', 'copo'), Kit::word('preferir', 'prefiro'), Kit::form('esse')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.mochila-presente', 'This backpack is a gift for Ana.', ['Esta mochila é um presente para a Ana.', 'Esta mochila é um presente para Ana.'], [Kit::word('a mochila', 'mochila'), Kit::word('o presente', 'presente'), Kit::form('esta')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.chapeu-guarda-chuva', 'Which do you prefer, that hat (near you) or that umbrella (near you)? (informal you)', ['Qual preferes, esse chapéu ou esse guarda-chuva?'], [Kit::word('o chapéu', 'chapéu'), Kit::word('o guarda-chuva', 'guarda-chuva'), Kit::word('preferir', 'preferes'), Kit::form('esse', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.canetas', 'Those pens (near you) are not João\'s.', ['Essas canetas não são do João.', 'Essas canetas não são de João.'], [Kit::word('a caneta', 'canetas'), Kit::form('essas')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.carro', 'Whose car is this?', ['De quem é este carro?'], [Kit::word('o carro', 'carro'), Kit::form('este')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.copo', '___ copo é do Rui.', 'That glass (near you) is Rui\'s.', 'Esse', Kit::form('esse', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.chapeus', '___ chapéus são do João.', 'These hats are João\'s.', 'Estes', Kit::form('estes'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.mala-mochila', 'Prefiro a mala, não a mochila.', 'I prefer the bag, not the backpack.', [Kit::word('preferir', 'prefiro'), Kit::word('a mala', 'mala'), Kit::word('a mochila', 'mochila')], 'dictation', $set, homophoneNote: 'Both a before mala and a before mochila are the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.copo-chapeu', 'O copo e o chapéu são presentes.', 'The glass and the hat are gifts.', [Kit::word('o copo', 'copo'), Kit::word('o chapéu', 'chapéu'), Kit::word('o presente', 'presentes')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.foto-carro', 'A foto e o carro são da Marta.', 'The photo and the car are Marta\'s.', [Kit::word('a foto', 'foto'), Kit::word('o carro', 'carro')], 'dictation', $set, homophoneNote: 'The first a is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.presente', [
                Kit::line('Rui', 'Marta, o que é isto?'),
                Kit::line('Marta', 'É um presente para o João.'),
                Kit::line('Rui', 'É uma caneta ou um chapéu?'),
                Kit::line('Marta', 'É um chapéu.'),
            ], [
                Kit::question('Who is the gift for?', ['João', 'Rui', 'Ana'], 'João'),
                Kit::question('What is the gift?', ['A hat', 'A pen', 'A bag'], 'A hat'),
                Kit::question('Who asks what it is?', ['Rui', 'Marta', 'João'], 'Rui'),
            ], [
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Is the gift for João?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('Is the gift a pen?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [Kit::word('o presente', 'presente'), Kit::word('a caneta', 'caneta'), Kit::word('o chapéu', 'chapéu')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.guarda-chuva', 'Read the conversation.', [
                Kit::line('João', 'Ana, de quem é este guarda-chuva?'),
                Kit::line('Ana', 'É do Rui. Esta foto também é do Rui.'),
                Kit::line('João', 'São presentes: uma mala e um copo.'),
            ], [
                Kit::question('Whose umbrella is it?', ['Rui\'s', 'Ana\'s', 'Marta\'s'], 'Rui\'s'),
                Kit::question('Which gifts does João mention?', ['A bag and a glass', 'An umbrella and a photo', 'A hat and a pen'], 'A bag and a glass'),
            ], [Kit::word('o guarda-chuva', 'guarda-chuva'), Kit::word('a foto', 'foto'), Kit::word('o presente', 'presente'), Kit::word('a mala', 'mala'), Kit::word('o copo', 'copo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.carro-chapeu', 'Qual preferes, o carro ou o chapéu?', 'Which do you prefer, the car or the hat?', [['prefiro', 'este', 'esta', 'esse', 'essa'], ['carro', 'chapéu']], 'Prefiro o carro.', [Kit::word('o carro', 'carro'), Kit::word('o chapéu', 'chapéu'), Kit::word('preferir', 'prefiro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.mala', 'De quem é a mala?', 'Whose bag is it?', [['é', 'do', 'da'], self::NAMES], 'É da Ana.', [Kit::word('a mala', 'mala')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.foto', 'De quem é a foto?', 'Whose photo is it?', [['é', 'do', 'da'], self::NAMES], 'É da Marta.', [Kit::word('a foto', 'foto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.mochilas-presentes', 'These backpacks are gifts for Marta.', ['Estas mochilas são presentes para a Marta.', 'Estas mochilas são presentes para Marta.'], [Kit::word('a mochila', 'mochilas'), Kit::word('o presente', 'presentes'), Kit::form('estas')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.chapeu-foto', 'I prefer that hat (near you), not this photo.', ['Prefiro esse chapéu, não esta foto.', 'Eu prefiro esse chapéu, não esta foto.'], [Kit::word('o chapéu', 'chapéu'), Kit::word('a foto', 'foto'), Kit::word('preferir', 'prefiro'), Kit::form('esse', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.carros', 'Whose cars and umbrellas are those (near you)?', ['De quem são esses carros e guarda-chuvas?', 'De quem são esses carros e esses guarda-chuvas?'], [Kit::word('o carro', 'carros'), Kit::word('o guarda-chuva', 'guarda-chuvas'), Kit::form('esses')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.copo-caneta', 'This glass and this pen are Rui\'s.', ['Este copo e esta caneta são do Rui.', 'Este copo e esta caneta são de Rui.'], [Kit::word('o copo', 'copo'), Kit::word('a caneta', 'caneta'), Kit::form('este')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.foto', 'Esta ___ é da Marta.', 'This photo is Marta\'s.', 'foto', Kit::word('a foto', 'foto'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.malas', 'Estas ___ são do João.', 'These bags are João\'s.', 'malas', Kit::word('a mala', 'malas'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.copo-mala', 'Prefiro este copo, não essa mala.', 'I prefer this glass, not that bag (near you).', [Kit::word('preferir', 'prefiro'), Kit::word('o copo', 'copo'), Kit::word('a mala', 'mala'), Kit::form('este', true)], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.guarda-chuvas-carros', 'Esses guarda-chuvas e carros são presentes.', 'Those umbrellas and cars (near you) are gifts.', [Kit::word('o guarda-chuva', 'guarda-chuvas'), Kit::word('o carro', 'carros'), Kit::word('o presente', 'presentes'), Kit::form('esses')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.chapeu-caneta', 'O chapéu, a caneta e a mochila são da Ana.', 'The hat, the pen and the backpack are Ana\'s.', [Kit::word('o chapéu', 'chapéu'), Kit::word('a caneta', 'caneta'), Kit::word('a mochila', 'mochila')], 'dictation', $set, homophoneNote: 'The a before caneta and the a before mochila are the article a (the), not à (to the) and not há (there is).'),
        ];
    }
}
