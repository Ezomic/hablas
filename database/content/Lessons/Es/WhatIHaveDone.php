<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class WhatIHaveDone implements UnitContent
{
    private const HE_HA_NOTE = 'He and ha are forms of haber. The h is silent, so he sounds like e (and) and ha sounds like a (to).';

    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'what-i-have-done';
    }

    public function words(): array
    {
        return [
            new WordData('terminar', cue: 'to finish', forms: ['he terminado']),
            new WordData('empezar', cue: 'to start (to begin)', forms: ['he empezado']),
            new WordData('limpiar', cue: 'to clean', forms: ['he limpiado']),
            new WordData('visitar', cue: 'to visit', forms: ['he visitado']),
            new WordData('preparar', cue: 'to prepare', forms: ['he preparado']),
            new WordData('lavar', cue: 'to wash', forms: ['he lavado']),
            new WordData('ya', cue: 'already', note: 'Ya means already: Ya he terminado. In a question it means yet: ¿Has terminado ya? Todavía no is the answer when it is not done yet.'),
            new WordData('todavía', cue: 'yet (as in not yet)', accepted: ['aún'], note: 'Todavía no means not yet: Todavía no he terminado. Aún no means the same.'),
            new WordData('nunca', cue: 'never', note: 'Nunca goes before he: Nunca he comido pescado. If nunca comes after the participle, put no before he: No he comido pescado nunca.'),
            new WordData('alguna vez', cue: 'ever (at some time)', note: 'Alguna vez is used in questions about experience: ¿Has visitado el museo alguna vez?'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Ya he terminado el trabajo.', 'english' => 'I have already finished the work.'],
            ['text' => 'Ana todavía no ha limpiado la cocina.', 'english' => 'Ana has not cleaned the kitchen yet.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-08', 'Terms, articles, genders, translations, cues, accepted answers, forms and the grammar explanation checked by a separate reviewer for correct and natural Spanish (Spain). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-08', 'The exercises of this unit were reviewed by a separate reviewer for natural Spanish (Spain), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-08', 'Released on the owner\'s instruction on 2026-10-08, without a line by line review of the lessons.'),
        ];
    }

    private function todavia(): TargetSpec
    {
        return Kit::word('todavía', alternates: ['aún']);
    }

    /**
     * @param  list<string>  $answers
     * @return list<string>
     */
    private function aun(array $answers): array
    {
        return [...$answers, ...array_map(fn (string $answer): string => str_replace(['Todavía', 'todavía'], ['Aún', 'aún'], $answer), $answers)];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.yo-limpiado', 'Yo ___ limpiado la cocina.', ['he', 'ha'], 'he', Kit::form('he', true), 'Yo goes with he: he limpiado. Ha is for él or ella.', 'choose', 'I have cleaned the kitchen.'),
            Kit::gap($stage, 'sentences.choose_gap.ana-terminado', 'Ana ___ terminado el trabajo.', ['ha', 'he', 'han'], 'ha', Kit::form('ha', true), 'Ana is one person, so ha: Ana ha terminado. He is for yo and han is for more than one person.', 'choose', 'Ana has finished the work.'),
            Kit::gap($stage, 'sentences.choose_gap.nosotros-visitado', 'Nosotros ___ visitado el museo.', ['hemos', 'han'], 'hemos', Kit::form('hemos', true), 'Nosotros goes with hemos: hemos visitado. Han is for ellos or ellas.', 'choose', 'We have visited the museum.'),
            Kit::gap($stage, 'sentences.choose_gap.hoy-comido', 'Hoy he ___ pescado.', ['comido', 'comer'], 'comido', Kit::form('comido', true), 'After he you need the participle. Comer is an -er verb, so it becomes comido.', 'choose', 'Today I have eaten fish.'),
            Kit::gap($stage, 'sentences.choose_gap.lavado-coche', 'Ya he ___ el coche.', ['lavado', 'lavar'], 'lavado', Kit::word('lavar', 'lavado'), 'After he you need the participle: lavado. Lavar is the infinitive.', 'choose', 'I have already washed the car.'),
            Kit::gap($stage, 'sentences.choose_gap.alguna-vez', '¿Has visitado el museo ___?', ['alguna vez', 'nunca'], 'alguna vez', Kit::word('alguna vez'), 'In a question you ask about experience with alguna vez. Nunca is used in the answer: No, nunca.', 'choose', 'Have you ever visited the museum?'),

            Kit::typeGap($stage, 'sentences.type_gap.tu-preparado', 'Tú ___ preparado la cena.', 'You have prepared dinner.', 'has', Kit::form('has'), 'Tú goes with has: has preparado.'),
            Kit::typeGap($stage, 'sentences.type_gap.ellos-terminado', 'Ellos ___ terminado hoy.', 'They have finished today.', 'han', Kit::form('han'), 'Ellos goes with han: han terminado.'),
            Kit::typeGap($stage, 'sentences.type_gap.preparado', 'Ya he ___ la cena.', 'I have already prepared dinner.', 'preparado', Kit::word('preparar', 'preparado'), 'After he you need the participle of preparar: preparado.'),
            Kit::typeGap($stage, 'sentences.type_gap.empezado', 'He ___ el libro.', 'I have started the book.', 'empezado', Kit::word('empezar', 'empezado'), 'After he you need the participle of empezar: empezado.'),
            Kit::typeGap($stage, 'sentences.type_gap.nunca', '___ he visitado el pueblo.', 'I have never visited the village.', 'Nunca', Kit::word('nunca'), 'Nunca means never. It goes in front of he.'),

            Kit::translate($stage, 'sentences.translate.terminado-ya', 'I have already finished the book.', ['Ya he terminado el libro.', 'Yo ya he terminado el libro.', 'He terminado ya el libro.', 'He terminado el libro ya.', 'Yo he terminado ya el libro.'], [Kit::word('ya'), Kit::word('terminar', 'terminado'), Kit::form('he terminado')]),
            Kit::translate($stage, 'sentences.translate.limpiado-todavia', 'We have not cleaned the kitchen yet.', $this->aun(['Todavía no hemos limpiado la cocina.', 'No hemos limpiado la cocina todavía.', 'Nosotros todavía no hemos limpiado la cocina.', 'Nosotros no hemos limpiado la cocina todavía.']), [$this->todavia(), Kit::word('limpiar', 'limpiado'), Kit::form('hemos limpiado')]),
            Kit::translate($stage, 'sentences.translate.visitado-alguna-vez', 'Have you ever visited the museum? (informal you)', ['¿Has visitado alguna vez el museo?', '¿Has visitado el museo alguna vez?', '¿Alguna vez has visitado el museo?', '¿Tú has visitado alguna vez el museo?', '¿Tú has visitado el museo alguna vez?'], [Kit::word('alguna vez'), Kit::word('visitar', 'visitado'), Kit::form('has visitado')]),

            Kit::build($stage, 'sentences.build.nunca-lavado', 'Ana has never washed the car.', 'Ana nunca ha lavado el coche.', ['he'], [Kit::word('nunca'), Kit::word('lavar', 'lavado'), Kit::form('ha lavado')]),
            Kit::build($stage, 'sentences.build.han-empezado', 'They have started the film.', 'Han empezado la película.', ['ha'], [Kit::word('empezar', 'empezado'), Kit::form('han empezado')]),
            Kit::build($stage, 'sentences.build.ha-preparado', 'Marta has prepared dinner.', 'Marta ha preparado la cena.', ['han'], [Kit::word('preparar', 'preparado'), Kit::form('ha preparado')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.ya-terminado', 'Ya he terminado.', ['I have already finished.', 'I have not finished yet.', 'You have already finished.', 'I am finishing.'], 'I have already finished.', [Kit::word('ya'), Kit::word('terminar', 'terminado'), Kit::form('he terminado')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.todavia-comido', 'Todavía no hemos comido.', ['We have not eaten yet.', 'We have already eaten.', 'They have not eaten yet.', 'We never eat.'], 'We have not eaten yet.', [$this->todavia(), Kit::form('hemos comido')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.nunca-visitado', 'Ana nunca ha visitado el museo.', ['Ana has never visited the museum.', 'Ana has already visited the museum.', 'Ana has not visited the museum yet.', 'Ana is visiting the museum.'], 'Ana has never visited the museum.', [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('ha visitado')]),
            Kit::listenType($stage, 'sentences.listen_type.limpiado-casa', 'Hoy he limpiado la casa.', 'Today I have cleaned the house.', [Kit::word('limpiar', 'limpiado'), Kit::form('he limpiado')], homophoneNote: self::HE_HA_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.empezado-ya', '¿Has empezado ya el libro?', 'Have you started the book yet? (informal you)', [Kit::word('empezar', 'empezado'), Kit::word('ya'), Kit::form('has empezado')]),
            Kit::listenType($stage, 'sentences.listen_type.han-preparado', 'Luis y Ana han preparado la cena.', 'Luis and Ana have prepared dinner.', [Kit::word('preparar', 'preparado'), Kit::form('han preparado')]),
            Kit::listenType($stage, 'sentences.listen_type.lavado-alguna-vez', '¿Has lavado alguna vez el coche?', 'Have you ever washed the car? (informal you)', [Kit::word('lavar', 'lavado'), Kit::word('alguna vez'), Kit::form('has lavado')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.ya-terminado', 'Ya he terminado el trabajo.', 'I have already finished the work.', [Kit::word('ya'), Kit::word('terminar', 'terminado'), Kit::form('he terminado')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.todavia-empezado', 'Todavía no he empezado la cena.', 'I have not started dinner yet.', [$this->todavia(), Kit::word('empezar', 'empezado'), Kit::form('he empezado')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.limpiado-casa', 'Ana ha limpiado la casa hoy.', 'Ana has cleaned the house today.', [Kit::word('limpiar', 'limpiado'), Kit::form('ha limpiado')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.nunca-visitado', 'Nunca he visitado el museo.', 'I have never visited the museum.', [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('he visitado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.terminado', '¿Has terminado el trabajo?', 'Have you finished the work?', [['sí', 'no'], ['terminado', 'ya', 'todavía', 'aún']], 'Sí, ya he terminado el trabajo.', [Kit::word('terminar', 'terminado'), Kit::word('ya')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pueblo', '¿Has visitado el pueblo alguna vez?', 'Have you ever visited the village?', [['sí', 'no'], ['visitado', 'nunca', 'alguna']], 'No, nunca he visitado el pueblo.', [Kit::word('alguna vez'), Kit::word('nunca'), Kit::word('visitar', 'visitado')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.limpiado', '¿Has limpiado la casa hoy?', 'Have you cleaned the house today?', [['sí', 'no'], ['limpiado', 'ya', 'todavía', 'aún']], 'Sí, ya he limpiado la casa.', [Kit::word('limpiar', 'limpiado'), Kit::word('ya')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $words = fn (string ...$terms): array => array_map(fn (string $term): mixed => Kit::word($term), $terms);

        return [
            Kit::readPassage($stage, 'task.read_passage.hoy', 'Read the conversation about what Ana and Pablo have done today.', [
                Kit::line('Pablo', 'Ana, ¿has limpiado la casa?'),
                Kit::line('Ana', 'Sí, ya he terminado. También he lavado el coche.'),
                Kit::line('Pablo', 'Yo todavía no he empezado. Hoy he preparado la cena.'),
                Kit::line('Ana', 'Mañana voy al museo. ¿Has visitado el museo alguna vez?'),
                Kit::line('Pablo', 'No, nunca he visitado el museo.'),
            ], [
                Kit::question('What has Ana done?', ['She has finished and washed the car', 'She has not started yet', 'She has prepared dinner'], 'She has finished and washed the car'),
                Kit::question('What has Pablo done today?', ['He has prepared dinner', 'He has washed the car', 'He has cleaned the house'], 'He has prepared dinner'),
                Kit::question('Has Pablo ever visited the museum?', ['No, never', 'Yes, he has', 'The text does not say.'], 'No, never'),
            ], [Kit::word('limpiar', 'limpiado'), Kit::word('terminar', 'terminado'), Kit::word('lavar', 'lavado'), Kit::word('empezar', 'empezado'), Kit::word('preparar', 'preparado'), Kit::word('visitar', 'visitado'), $this->todavia(), ...$words('ya', 'nunca', 'alguna vez')]),
            Kit::gap($stage, 'task.choose_gap.bebido', 'Hoy he ___ café con Marta.', ['bebido', 'beber'], 'bebido', Kit::form('bebido', true), 'After he you need the participle. Beber is an -er verb, so it becomes bebido.', 'read', 'Today I have drunk coffee with Marta.'),
            Kit::gap($stage, 'task.choose_gap.todavia', '___ no he terminado el trabajo.', ['Todavía', 'Ya'], 'Todavía', $this->todavia(), 'Not yet is todavía no. Ya means already.', 'read', 'I have not finished the work yet.'),

            Kit::transform($stage, 'task.transform.ellos-salido', 'Change the subject to they.', 'Ana ha salido hoy.', ['Ellos han salido hoy.', 'Ellas han salido hoy.', 'Han salido hoy.'], [Kit::form('han salido', true)]),
            Kit::transform($stage, 'task.transform.nosotros-limpiado', 'Change the subject to we.', 'Ya he limpiado la cocina.', ['Ya hemos limpiado la cocina.', 'Nosotros ya hemos limpiado la cocina.', 'Hemos limpiado ya la cocina.', 'Hemos limpiado la cocina ya.', 'Nosotros hemos limpiado ya la cocina.'], [Kit::word('limpiar', 'limpiado'), Kit::word('ya'), Kit::form('hemos limpiado')]),
            Kit::transform($stage, 'task.transform.nunca-lavado', 'Say that you have never washed the car.', 'He lavado el coche.', ['Nunca he lavado el coche.', 'Yo nunca he lavado el coche.', 'No he lavado nunca el coche.', 'No he lavado el coche nunca.'], [Kit::word('lavar', 'lavado'), Kit::word('nunca'), Kit::form('he lavado')]),
            Kit::writeGuided($stage, 'task.write_guided.terminado-limpiado', 'Say that you have already finished and that you have not cleaned the kitchen yet.', ['ya', 'terminado', 'todavía no', 'limpiado', 'la cocina'], 'Ya he terminado y todavía no he limpiado la cocina.', [
                ['forms' => ['ya'], 'term' => 'ya'],
                ['forms' => ['terminado'], 'term' => 'terminar'],
                ['forms' => ['todavía', 'aún'], 'term' => 'todavía'],
                ['forms' => ['limpiado'], 'term' => 'limpiar'],
            ], [Kit::word('ya'), Kit::word('terminar', 'terminado'), $this->todavia(), Kit::word('limpiar', 'limpiado')]),
            Kit::writeGuided($stage, 'task.write_guided.preparado-visitado', 'Say that you have prepared dinner and that you have never visited the museum.', ['preparado', 'la cena', 'nunca', 'visitado', 'el museo'], 'He preparado la cena y nunca he visitado el museo.', [
                ['forms' => ['preparado'], 'term' => 'preparar'],
                ['forms' => ['nunca'], 'term' => 'nunca'],
                ['forms' => ['visitado'], 'term' => 'visitar'],
            ], [Kit::word('preparar', 'preparado'), Kit::word('nunca'), Kit::word('visitar', 'visitado')]),
            Kit::build($stage, 'task.build.nunca-visitado', 'We have never visited the church.', 'Nunca hemos visitado la iglesia.', ['han', 'el'], [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('hemos visitado')]),
            Kit::build($stage, 'task.build.pablo-preparado', 'Pablo has prepared the coffee.', 'Pablo ha preparado el café.', ['he', 'han'], [Kit::word('preparar', 'preparado'), Kit::form('ha preparado', true)]),
            Kit::build($stage, 'task.build.ana-luis-limpiado', 'Ana and Luis have cleaned the house.', 'Ana y Luis han limpiado la casa.', ['ha', 'hemos'], [Kit::word('limpiar', 'limpiado'), Kit::form('han limpiado', true)]),
            Kit::translate($stage, 'task.translate.empezado-terminado', 'I have already started the book, but I have not finished yet.', $this->aun(['Ya he empezado el libro, pero todavía no he terminado.', 'Ya he empezado el libro, pero no he terminado todavía.', 'Yo ya he empezado el libro, pero todavía no he terminado.', 'Yo ya he empezado el libro, pero no he terminado todavía.', 'He empezado ya el libro, pero todavía no he terminado.', 'He empezado ya el libro, pero no he terminado todavía.']), [Kit::word('ya'), Kit::word('empezar', 'empezado'), $this->todavia(), Kit::word('terminar', 'terminado'), Kit::form('he empezado')]),
            Kit::translate($stage, 'task.translate.marta-luis-pueblo', 'Marta and Luis have never visited the village.', ['Marta y Luis nunca han visitado el pueblo.', 'Marta y Luis no han visitado nunca el pueblo.', 'Marta y Luis no han visitado el pueblo nunca.'], [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('han visitado', true)]),

            Kit::listenPassage($stage, 'task.listen_passage.trabajo-cena', [
                Kit::line('Marta', 'Luis, ¿ya has empezado el trabajo?'),
                Kit::line('Luis', 'No, todavía no. Hoy he limpiado la cocina.'),
                Kit::line('Marta', 'Yo ya he terminado. He preparado la cena y he lavado el coche.'),
                Kit::line('Luis', 'Marta, ¿has visitado el museo alguna vez?'),
                Kit::line('Marta', 'No, nunca.'),
            ], [
                Kit::question('Has Luis started the work?', ['No, not yet', 'Yes, already', 'The conversation does not say.'], 'No, not yet'),
                Kit::question('What has Marta prepared?', ['Dinner', 'Coffee', 'Breakfast'], 'Dinner'),
                Kit::question('Has Marta ever visited the museum?', ['No, never', 'Yes, once', 'The conversation does not say.'], 'No, never'),
            ], [
                Kit::question('Who has already finished?', ['Marta', 'Luis', 'Both of them'], 'Marta'),
                Kit::question('What has Luis cleaned today?', ['The kitchen', 'The car', 'The house'], 'The kitchen'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('empezar', 'empezado'), Kit::word('limpiar', 'limpiado'), Kit::word('terminar', 'terminado'), Kit::word('preparar', 'preparado'), Kit::word('lavar', 'lavado'), Kit::word('visitar', 'visitado'), $this->todavia(), ...$words('ya', 'nunca', 'alguna vez')]),
            Kit::listenType($stage, 'task.listen_type.hemos-limpiado-lavado', 'Hoy hemos limpiado la casa y hemos lavado el coche.', 'Today we have cleaned the house and washed the car.', [Kit::word('limpiar', 'limpiado'), Kit::word('lavar', 'lavado'), Kit::form('hemos limpiado')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.nunca-visitado-hoy', 'Nunca he visitado la biblioteca, pero hoy voy.', 'I have never visited the library, but today I am going.', [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('he visitado')], 'listen', homophoneNote: self::HE_HA_NOTE),
            Kit::listenType($stage, 'task.listen_type.han-terminado-ya', '¿Han terminado ya Ana y Pablo?', 'Have Ana and Pablo finished yet?', [Kit::word('terminar', 'terminado'), Kit::word('ya'), Kit::form('han terminado')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.preparado', '¿Has preparado la cena?', 'Have you prepared dinner?', [['sí', 'no'], ['preparado', 'ya', 'todavía', 'aún']], 'Sí, ya he preparado la cena.', [Kit::word('preparar', 'preparado'), Kit::word('ya')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.lavado', '¿Has lavado el coche hoy?', 'Have you washed the car today?', [['sí', 'no'], ['lavado', 'ya', 'todavía', 'aún', 'nunca']], 'No, todavía no he lavado el coche.', [Kit::word('lavar', 'lavado'), $this->todavia()], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.empezado', '¿Has empezado el libro?', 'Have you started the book?', [['sí', 'no'], ['empezado', 'ya', 'todavía', 'aún']], 'Sí, ya he empezado el libro.', [Kit::word('empezar', 'empezado'), Kit::word('ya')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.museo', '¿Has visitado alguna vez un museo?', 'Have you ever visited a museum?', [['sí', 'no'], ['visitado', 'nunca', 'alguna']], 'No, nunca he visitado un museo.', [Kit::word('visitar', 'visitado'), Kit::word('alguna vez'), Kit::word('nunca')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.todavia-terminado', 'Todavía no hemos terminado el trabajo.', 'We have not finished the work yet.', [$this->todavia(), Kit::word('terminar', 'terminado'), Kit::form('hemos terminado')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.preparado-limpiado', 'Marta ha preparado la cena y ha limpiado la cocina.', 'Marta has prepared dinner and cleaned the kitchen.', [Kit::word('preparar', 'preparado'), Kit::word('limpiar', 'limpiado'), Kit::form('ha preparado')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $words = fn (string ...$terms): array => array_map(fn (string $term): mixed => Kit::word($term), $terms);

        return [
            Kit::translate($stage, 'check.a.translate.nunca-iglesia', 'I have never visited the church.', ['Nunca he visitado la iglesia.', 'Yo nunca he visitado la iglesia.', 'No he visitado nunca la iglesia.', 'No he visitado la iglesia nunca.'], [Kit::word('nunca'), Kit::word('visitar', 'visitado'), Kit::form('he visitado')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.ana-cena', 'Ana has already prepared dinner.', ['Ana ya ha preparado la cena.', 'Ana ha preparado ya la cena.', 'Ana ha preparado la cena ya.'], [Kit::word('ya'), Kit::word('preparar', 'preparado'), Kit::form('ha preparado')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.coche-todavia', 'We have not washed the car yet.', $this->aun(['Todavía no hemos lavado el coche.', 'No hemos lavado el coche todavía.', 'Nosotros todavía no hemos lavado el coche.', 'Nosotros no hemos lavado el coche todavía.']), [$this->todavia(), Kit::word('lavar', 'lavado'), Kit::form('hemos lavado', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pueblo-alguna-vez', 'Have you ever visited Ana\'s village? (informal you)', ['¿Has visitado alguna vez el pueblo de Ana?', '¿Has visitado el pueblo de Ana alguna vez?', '¿Alguna vez has visitado el pueblo de Ana?', '¿Tú has visitado alguna vez el pueblo de Ana?'], [Kit::word('alguna vez'), Kit::word('visitar', 'visitado'), Kit::form('has visitado', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.limpiado', 'Hoy hemos ___ la cocina.', 'Today we have cleaned the kitchen.', 'limpiado', Kit::word('limpiar', 'limpiado'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.empezado', 'Luis ya ha ___ la película.', 'Luis has already started the film.', 'empezado', Kit::word('empezar', 'empezado'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.terminado-todavia', 'Ana ha terminado, pero yo todavía no.', 'Ana has finished, but I have not yet.', [Kit::word('terminar', 'terminado'), $this->todavia(), Kit::form('ha terminado', true)], 'dictation', $set, homophoneNote: self::HE_HA_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.nunca-lavado', 'Nunca han lavado el coche de Pablo.', 'They have never washed Pablo\'s car.', [Kit::word('nunca'), Kit::word('lavar', 'lavado'), Kit::form('han lavado')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.ya-empezado', 'Ya han empezado el libro.', 'They have already started the book.', [Kit::word('ya'), Kit::word('empezar', 'empezado')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.cena', [
                Kit::line('Pablo', '¿Has terminado ya, Ana?'),
                Kit::line('Ana', 'Sí. Hoy he preparado la cena y he limpiado la cocina.'),
                Kit::line('Pablo', '¿Has visitado alguna vez la biblioteca?'),
                Kit::line('Ana', 'No, nunca he visitado la biblioteca.'),
            ], [
                Kit::question('Has Ana finished?', ['Yes, she has', 'No, not yet', 'The conversation does not say.'], 'Yes, she has'),
                Kit::question('What has Ana prepared?', ['Dinner', 'Coffee', 'The car'], 'Dinner'),
                Kit::question('Has Ana ever visited the library?', ['No, never', 'Yes, she has', 'The conversation does not say.'], 'No, never'),
            ], [
                Kit::question('Who asks the questions?', ['Pablo', 'Ana', 'Nobody'], 'Pablo'),
                Kit::question('What has Ana cleaned?', ['The kitchen', 'The car', 'The house'], 'The kitchen'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('terminar', 'terminado'), Kit::word('preparar', 'preparado'), Kit::word('limpiar', 'limpiado'), Kit::word('visitar', 'visitado'), ...$words('ya', 'alguna vez', 'nunca')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.trabajo', 'Read the conversation.', [
                Kit::line('Luis', 'Pablo, ¿has empezado el trabajo?'),
                Kit::line('Pablo', 'Todavía no he empezado. Hoy he lavado el coche y he preparado el café.'),
                Kit::line('Luis', 'Yo he terminado ya.'),
            ], [
                Kit::question('What has Pablo washed?', ['The car', 'The kitchen', 'The house'], 'The car'),
                Kit::question('Has Pablo started the work?', ['No, not yet', 'Yes, already', 'The text does not say.'], 'No, not yet'),
            ], [Kit::word('empezar', 'empezado'), Kit::word('lavar', 'lavado'), Kit::word('preparar', 'preparado'), Kit::word('terminar', 'terminado'), $this->todavia(), ...$words('ya')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.ventana', '¿Has limpiado la ventana?', 'Have you cleaned the window?', [['sí', 'no'], ['limpiado', 'ya', 'todavía', 'aún']], 'Sí, ya he limpiado la ventana.', [Kit::word('limpiar', 'limpiado'), Kit::word('ya')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.cafe', '¿Has preparado el café?', 'Have you prepared the coffee?', [['sí', 'no'], ['preparado', 'ya', 'todavía', 'aún']], 'No, todavía no he preparado el café.', [Kit::word('preparar', 'preparado'), $this->todavia()], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.iglesia', '¿Has visitado alguna vez una iglesia?', 'Have you ever visited a church?', [['sí', 'no'], ['visitado', 'nunca', 'alguna']], 'Sí, he visitado una iglesia.', [Kit::word('alguna vez'), Kit::word('visitar', 'visitado'), Kit::word('nunca')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.terminado-empezado', 'Pablo has not finished yet, but Ana has already started.', $this->aun(['Pablo todavía no ha terminado, pero Ana ya ha empezado.', 'Pablo no ha terminado todavía, pero Ana ya ha empezado.', 'Pablo todavía no ha terminado, pero Ana ha empezado ya.', 'Pablo no ha terminado todavía, pero Ana ha empezado ya.']), [$this->todavia(), Kit::word('terminar', 'terminado'), Kit::word('ya'), Kit::word('empezar', 'empezado'), Kit::form('ha terminado', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-casa-coche', 'Marta has cleaned the house and washed the car.', ['Marta ha limpiado la casa y ha lavado el coche.', 'Marta ha limpiado la casa y lavado el coche.'], [Kit::word('limpiar', 'limpiado'), Kit::word('lavar', 'lavado'), Kit::form('ha limpiado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.nunca-cena-luis', 'We have never prepared dinner for Luis.', ['Nunca hemos preparado la cena para Luis.', 'Nosotros nunca hemos preparado la cena para Luis.', 'No hemos preparado nunca la cena para Luis.', 'No hemos preparado la cena para Luis nunca.'], [Kit::word('nunca'), Kit::word('preparar', 'preparado'), Kit::form('hemos preparado')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.visitado-marta', 'Have you ever visited Marta\'s house? (informal you)', ['¿Has visitado alguna vez la casa de Marta?', '¿Has visitado la casa de Marta alguna vez?', '¿Alguna vez has visitado la casa de Marta?', '¿Tú has visitado alguna vez la casa de Marta?'], [Kit::word('alguna vez'), Kit::word('visitar', 'visitado'), Kit::form('has visitado', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pablo-terminado', 'Pablo ya ha ___ el trabajo.', 'Pablo has already finished the work.', 'terminado', Kit::word('terminar', 'terminado'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.empezado', 'Hoy he ___ la ventana.', 'Today I have cleaned the window.', 'limpiado', Kit::word('limpiar', 'limpiado'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.limpiado-lavado', 'Ya han limpiado y nunca han lavado el coche.', 'They have already cleaned, and they have never washed the car.', [Kit::word('ya'), Kit::word('limpiar', 'limpiado'), Kit::word('nunca'), Kit::word('lavar', 'lavado'), Kit::form('han limpiado')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.visitado-iglesia', '¿Has visitado la iglesia alguna vez? Yo nunca.', 'Have you ever visited the church? I never have.', [Kit::word('visitar', 'visitado'), Kit::word('alguna vez'), Kit::word('nunca')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.marta-cena', 'Marta ha preparado la cena. Ana todavía no.', 'Marta has prepared dinner. Ana has not yet.', [Kit::word('preparar', 'preparado'), $this->todavia(), Kit::form('ha preparado', true)], 'dictation', $set, homophoneNote: self::HE_HA_NOTE),
        ];
    }
}
