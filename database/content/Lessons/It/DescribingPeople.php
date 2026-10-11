<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\TargetSpec;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DescribingPeople implements UnitContent
{
    private const NOTE_IS = 'È (is) and e (and) sound very close, and the accent is only a written difference. Here it is the verb is, so it takes the accent.';

    private const NOTE_AND = 'E (and) and è (is) sound very close, and the accent is only a written difference. Here it means and, so it has no accent.';

    private const NOTE_HA = 'Ha (has) sounds the same as a (to); only the h on paper tells them apart. Here it is the verb has.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'describing-people';
    }

    public function words(): array
    {
        return [
            new WordData('alto', cue: 'tall (masculine)', forms: ['alta', 'alti', 'alte']),
            new WordData('basso', cue: 'short, not tall (masculine)', forms: ['bassa', 'bassi', 'basse']),
            new WordData('simpatico', cue: 'nice, friendly (masculine)', forms: ['simpatica', 'simpatici', 'simpatiche']),
            new WordData('giovane', cue: 'young', forms: ['giovani'], note: 'Giovane has the same form for men and women. The plural is giovani.'),
            new WordData('biondo', cue: 'blond (masculine)', forms: ['bionda', 'biondi', 'bionde']),
            new WordData('moro', cue: 'dark-haired (masculine)', forms: ['mora', 'mori', 'more']),
            new WordData('bello', cue: 'good-looking (masculine)', forms: ['bella', 'belli', 'belle']),
            new WordData('i capelli', cue: 'hair', note: 'Hair as a whole is plural in Italian (i capelli), so the article and the colour are plural too: i capelli biondi (blond hair).'),
            new WordData('l\'occhio', cue: 'eye', forms: ['occhio', 'occhi']),
            new WordData('la persona', cue: 'person', forms: ['persone'], note: 'La persona is always feminine, also for a man: Luca è una persona simpatica.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Anna è alta e ha i capelli biondi.', 'english' => 'Anna is tall and has blond hair.'],
            ['text' => 'Paolo è simpatico e sta bene.', 'english' => 'Paolo is nice and he is fine.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural Italian (Italy). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural Italian (Italy), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return array<string, TargetSpec> */
    private function t(): array
    {
        return [
            'alto' => Kit::word('alto'),
            'alta' => Kit::word('alto', 'alta'),
            'alti' => Kit::word('alto', 'alti'),
            'basso' => Kit::word('basso'),
            'bassa' => Kit::word('basso', 'bassa'),
            'bassi' => Kit::word('basso', 'bassi'),
            'basse' => Kit::word('basso', 'basse'),
            'simpatico' => Kit::word('simpatico'),
            'simpatica' => Kit::word('simpatico', 'simpatica'),
            'simpatici' => Kit::word('simpatico', 'simpatici'),
            'giovane' => Kit::word('giovane'),
            'giovani' => Kit::word('giovane', 'giovani'),
            'biondo' => Kit::word('biondo'),
            'bionda' => Kit::word('biondo', 'bionda'),
            'biondi' => Kit::word('biondo', 'biondi'),
            'moro' => Kit::word('moro'),
            'mori' => Kit::word('moro', 'mori'),
            'mora' => Kit::word('moro', 'mora'),
            'bello' => Kit::word('bello'),
            'bella' => Kit::word('bello', 'bella'),
            'capelli' => Kit::word('i capelli', 'capelli'),
            'occhio' => Kit::word('l\'occhio', 'occhio', ['l\'occhio']),
            'occhi' => Kit::word('l\'occhio', 'occhi'),
            'persona' => Kit::word('la persona', 'persona'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $s = Stage::Sentences;
        $t = $this->t();
        $e = Kit::form('è');
        $ha = Kit::form('ha');
        $sono = Kit::form('sono');

        return [
            Kit::gap($s, 'sentences.choose_gap.anna-alta', 'Anna è ___.', ['alta', 'alto', 'alte'], 'alta', $t['alta'], 'Anna is one woman, so the adjective ends in -a and stays singular: alta.', 'choose', 'Anna is tall.'),
            Kit::gap($s, 'sentences.choose_gap.luca-basso', 'Luca è ___.', ['basso', 'bassa', 'bassi'], 'basso', $t['basso'], 'Luca is one man, so the adjective ends in -o and stays singular: basso.', 'choose', 'Luca is short.'),
            Kit::gap($s, 'sentences.choose_gap.paolo-capelli', 'Paolo ___ i capelli biondi.', ['ha', 'è'], 'ha', Kit::form('ha', true), 'You have hair and eyes, so Italian uses avere: ha. È says what someone is, not what they have.', 'choose', 'Paolo has blond hair.'),
            Kit::gap($s, 'sentences.choose_gap.anna-occhi', 'Anna ha gli ___ azzurri.', ['occhi', 'occhio'], 'occhi', $t['occhi'], 'Gli is plural, so the noun is plural too: occhi.', 'choose', 'Anna has blue eyes.', ['azzurri' => 'blue']),
            Kit::gap($s, 'sentences.choose_gap.marta-persona', 'Marta è una ___ simpatica.', ['persona', 'capelli', 'occhi'], 'persona', $t['persona'], 'Una goes with a feminine singular noun, and a person is persona. Capelli and occhi are plural.', 'choose', 'Marta is a nice person.'),
            Kit::gap($s, 'sentences.choose_gap.paolo-bene', 'Paolo ___ bene.', ['sta', 'è'], 'sta', Kit::form('sta', true), 'Sta bene says how someone is at the moment: he is fine. È bene does not say how someone feels.', 'choose', 'Paolo is fine.'),

            Kit::typeGap($s, 'sentences.type_gap.luca-alto', 'Luca ___ alto.', 'Luca is tall.', 'è', $e, 'Height is a lasting trait, so you use è.'),
            Kit::typeGap($s, 'sentences.type_gap.marta-capelli', 'Marta ___ i capelli biondi.', 'Marta has blond hair.', 'ha', $ha, 'Hair is something you have, so you use ha.'),
            Kit::typeGap($s, 'sentences.type_gap.luca-biondi', 'Luca ha i capelli ___.', 'Luca has blond hair.', 'biondi', $t['biondi']),
            Kit::typeGap($s, 'sentences.type_gap.paolo-luca-alti', 'Paolo e Luca ___ alti.', 'Paolo and Luca are tall.', 'sono', $sono, 'Two people need the plural of essere: sono. Alti is plural as well.'),
            Kit::typeGap($s, 'sentences.type_gap.anna-bella', 'Anna è molto ___.', 'Anna is very good-looking.', 'bella', $t['bella']),

            Kit::translate($s, 'sentences.translate.anna-alta-bionda', 'Anna is tall and blond.', ['Anna è alta e bionda.', 'Anna è bionda e alta.'], [$t['alta'], $t['bionda'], $e]),
            Kit::translate($s, 'sentences.translate.luca-moro', 'Luca is dark-haired.', ['Luca è moro.', 'Luca ha i capelli mori.', 'Luca ha capelli mori.'], [Kit::word('moro', 'moro', ['mori'])]),
            Kit::translate($s, 'sentences.translate.marta-giovane', 'Marta is young and nice.', ['Marta è giovane e simpatica.', 'Marta è simpatica e giovane.'], [$t['giovane'], $t['simpatica'], $e]),

            Kit::build($s, 'sentences.build.anna-occhi', 'Anna has blue eyes.', 'Anna ha gli occhi azzurri.', ['è'], [$t['occhi'], $ha], 'write', ['azzurri' => 'blue']),
            Kit::build($s, 'sentences.build.anna-molto-alta', 'Anna is very tall.', 'Anna è molto alta.', ['alto'], [$t['alta'], $e]),
            Kit::build($s, 'sentences.build.luca-capelli', 'Luca has blond hair.', 'Luca ha i capelli biondi.', ['è'], [$t['capelli'], $t['biondi'], $ha]),

            Kit::listenChoose($s, 'sentences.listen_choose.anna-alta-mora', 'Anna è alta e mora.', ['Anna is tall and dark-haired.', 'Anna is short and dark-haired.', 'Anna is tall and blond.', 'Anna is young and dark-haired.'], 'Anna is tall and dark-haired.', [$t['alta'], $t['mora'], $e]),
            Kit::listenChoose($s, 'sentences.listen_choose.luca-capelli', 'Luca ha i capelli biondi.', ['Luca has blond hair.', 'Luca has dark hair.', 'Anna has blond hair.', 'Luca has blond eyes.'], 'Luca has blond hair.', [$t['capelli'], $t['biondi'], $ha]),
            Kit::listenChoose($s, 'sentences.listen_choose.paolo-giovane', 'Paolo è giovane e bello.', ['Paolo is young and good-looking.', 'Paolo is old and good-looking.', 'Paolo is young and tall.', 'Paolo is nice and good-looking.'], 'Paolo is young and good-looking.', [$t['giovane'], $t['bello'], $e]),
            Kit::listenType($s, 'sentences.listen_type.luca-occhi', 'Luca ha gli occhi di Anna.', 'Luca has Anna\'s eyes.', [$t['occhi'], $ha], homophoneNote: self::NOTE_HA),
            Kit::listenType($s, 'sentences.listen_type.anna-persona', 'Anna è una persona molto simpatica.', 'Anna is a very nice person.', [$t['persona'], $t['simpatica'], $e], homophoneNote: self::NOTE_IS),
            Kit::listenType($s, 'sentences.listen_type.paolo-basso', 'Paolo non è basso, è alto.', 'Paolo is not short, he is tall.', [$t['basso'], $t['alto'], $e], homophoneNote: self::NOTE_IS),
            Kit::listenType($s, 'sentences.listen_type.marta-anna-giovani', 'Marta e Anna sono giovani.', 'Marta and Anna are young.', [$t['giovani'], $sono], homophoneNote: self::NOTE_AND),

            Kit::speakRepeat($s, 'sentences.speak_repeat.marta-alta-bella', 'Marta è alta e bella.', 'Marta is tall and good-looking.', [$t['alta'], $t['bella'], $e]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.paolo-moro', 'Paolo è moro e Luca è biondo.', 'Paolo is dark-haired and Luca is blond.', [$t['moro'], $t['biondo'], $e]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.marta-occhi', 'Marta ha gli occhi di Paolo.', 'Marta has Paolo\'s eyes.', [$t['occhi'], $ha]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.paolo-persona', 'Paolo è una persona simpatica.', 'Paolo is a nice person.', [$t['persona'], $t['simpatica'], $e]),
            Kit::speakAnswer($s, 'sentences.speak_answer.sei-alto', 'Sei alto?', 'Are you tall?', [['sì', 'no'], ['alto', 'alta', 'basso', 'bassa']], 'Sì, sono alto.', [$t['alto']]),
            Kit::speakAnswer($s, 'sentences.speak_answer.com-e-anna', 'Com\'è Anna?', 'What is Anna like?', [['è', 'ha'], ['alta', 'bassa', 'bionda', 'mora', 'bella', 'giovane', 'simpatica', 'capelli']], 'È alta e bionda.', [$t['bionda'], Kit::form('com\'è', false, ['è'])]),
            Kit::speakAnswer($s, 'sentences.speak_answer.anna-capelli', 'Hai i capelli mori?', 'Do you have dark hair?', [['sì', 'no'], ['ho', 'capelli', 'mori']], 'Sì, ho i capelli mori.', [$t['capelli'], $t['mori']]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $t = $this->t();
        $e = Kit::form('è');
        $ha = Kit::form('ha');
        $sono = Kit::form('sono');
        $comE = Kit::form('com\'è', false, ['è']);

        return [
            Kit::readPassage($s, 'task.read_passage.com-e-paolo', 'Read the conversation about Paolo and Luca.', [
                Kit::line('Marta', 'Anna, com\'è Paolo?'),
                Kit::line('Anna', 'È alto e moro. Ha gli occhi azzurri.'),
                Kit::line('Marta', 'È simpatico?'),
                Kit::line('Anna', 'Sì, è una persona molto simpatica.'),
                Kit::line('Marta', 'E Luca?'),
                Kit::line('Anna', 'Luca è basso e biondo. È giovane e molto bello.'),
            ], [
                Kit::question('What does Paolo look like?', ['Tall and dark-haired', 'Short and blond', 'Tall and blond'], 'Tall and dark-haired'),
                Kit::question('Is Paolo nice?', ['Yes', 'No', 'The text does not say.'], 'Yes'),
                Kit::question('Who is blond?', ['Luca', 'Paolo', 'Both of them'], 'Luca'),
            ], [$t['alto'], $t['moro'], $t['occhi'], $t['simpatico'], $t['persona'], $t['basso'], $t['biondo'], $t['giovane'], $t['bello'], $comE], 'read', null, ['azzurri' => 'blue']),
            Kit::gap($s, 'task.choose_gap.paolo-persona', 'Paolo ___ una persona simpatica.', ['è', 'sta'], 'è', Kit::form('è', true), 'A nice person is what he is like, a lasting trait, so you use è. Sta does not go with a noun like persona.', 'read', 'Paolo is a nice person.'),
            Kit::gap($s, 'task.choose_gap.anna-biondi', 'Anna è molto bella e ha i capelli ___.', ['biondi', 'biondo', 'bionda'], 'biondi', $t['biondi'], 'I capelli is masculine plural, so the colour is biondi. Bionda would describe Anna herself: Anna è bionda.', 'read', 'Anna is very good-looking and has blond hair.'),

            Kit::transform($s, 'task.transform.marta-bassa-mora', 'Change it to talk about Marta.', 'Paolo è basso e moro.', ['Marta è bassa e mora.', 'Marta è mora e bassa.'], [$t['bassa'], $t['mora'], $e]),
            Kit::transform($s, 'task.transform.giovani-simpatici', 'Change it to talk about Luca and Paolo.', 'Anna è giovane e simpatica.', ['Luca e Paolo sono giovani e simpatici.', 'Paolo e Luca sono giovani e simpatici.', 'Luca e Paolo sono simpatici e giovani.', 'Paolo e Luca sono simpatici e giovani.'], [$t['giovani'], $t['simpatici'], $sono]),
            Kit::transform($s, 'task.transform.anna-capelli', 'Say that Anna has blond hair.', 'Anna è bionda.', ['Anna ha i capelli biondi.', 'Anna ha capelli biondi.'], [$t['capelli'], $t['biondi'], Kit::form('ha', true)]),
            Kit::writeGuided($s, 'task.write_guided.marta-alta', 'Say that Marta is tall and has blond hair.', ['è', 'alta', 'ha', 'capelli', 'biondi'], 'Marta è alta e ha i capelli biondi.', [
                ['forms' => ['è'], 'term' => null],
                ['forms' => ['alta'], 'term' => 'alto'],
                ['forms' => ['capelli'], 'term' => 'i capelli'],
                ['forms' => ['biondi'], 'term' => 'biondo'],
            ], [$t['alta'], $t['capelli'], $t['biondi']]),
            Kit::writeGuided($s, 'task.write_guided.paolo-occhi', 'Say that Paolo is a nice person and has blue eyes.', ['è', 'persona', 'simpatica', 'ha', 'occhi', 'azzurri'], 'Paolo è una persona simpatica e ha gli occhi azzurri.', [
                ['forms' => ['è'], 'term' => null],
                ['forms' => ['persona'], 'term' => 'la persona'],
                ['forms' => ['simpatica'], 'term' => 'simpatico'],
                ['forms' => ['occhi'], 'term' => 'l\'occhio'],
            ], [$t['persona'], $t['simpatica'], $t['occhi']], ['azzurri' => 'blue']),
            Kit::build($s, 'task.build.paolo-non-alto', 'Paolo is not tall.', 'Paolo non è alto.', ['ha', 'alta'], [$t['alto'], $e], 'write'),
            Kit::build($s, 'task.build.luca-basso-anna-alta', 'Luca is short, but Anna is tall.', 'Luca è basso, ma Anna è alta.', ['ha', 'occhi'], [$t['basso'], $t['alta'], $e], 'write'),
            Kit::build($s, 'task.build.luca-moro', 'Luca has dark hair.', 'Luca ha i capelli mori.', ['è', 'mora'], [$t['capelli'], $ha, $t['mori']], 'write'),
            Kit::translate($s, 'task.translate.anna-giovane-non-bionda', 'Anna is young, but she is not blond.', ['Anna è giovane, ma non è bionda.'], [$t['giovane'], $t['bionda'], $e], 'write'),
            Kit::translate($s, 'task.translate.paolo-alto-basso', 'Is Paolo tall or short?', ['Paolo è alto o basso?', 'È alto o basso Paolo?', 'Paolo è alto o è basso?'], [$t['alto'], $t['basso'], $e], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.com-e-anna', [
                Kit::line('Luca', 'Marta, com\'è Anna?'),
                Kit::line('Marta', 'È giovane e molto bella. Ha i capelli biondi.'),
                Kit::line('Luca', 'È alta?'),
                Kit::line('Marta', 'No, è bassa. Ma è una persona molto simpatica.'),
            ], [
                Kit::question('What does Anna look like?', ['Young, good-looking and blond', 'Tall and dark-haired', 'Short and dark-haired'], 'Young, good-looking and blond'),
                Kit::question('Is Anna tall?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('What is Anna like as a person?', ['Nice', 'Not nice', 'The conversation does not say.'], 'Nice'),
            ], [
                Kit::question('Who asks about Anna?', ['Luca', 'Marta', 'Nobody'], 'Luca'),
                Kit::question('Does Anna have blond hair?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$t['giovane'], $t['bella'], $t['capelli'], $t['biondo'], $t['alto'], $t['bassa'], $t['persona'], $t['simpatico'], $comE], 'listen'),
            Kit::listenType($s, 'task.listen_type.anna-capelli-occhi', 'Anna ha i capelli biondi e gli occhi di Luca.', 'Anna has blond hair and Luca\'s eyes.', [$t['capelli'], $t['biondi'], $t['occhi'], $ha], 'listen', homophoneNote: self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenType($s, 'task.listen_type.luca-giovane-bello', 'Luca è giovane, moro e molto bello.', 'Luca is young, dark-haired and very good-looking.', [$t['giovane'], $t['moro'], $t['bello'], $e], 'listen', homophoneNote: self::NOTE_IS.' '.self::NOTE_AND),
            Kit::listenType($s, 'task.listen_type.anna-sta-bene', 'Anna sta bene, ma è molto bassa.', 'Anna is fine, but she is very short.', [$t['bassa'], Kit::form('sta', true)], 'listen', homophoneNote: self::NOTE_IS),

            Kit::speakAnswer($s, 'task.speak_answer.sei-alto-basso', 'Sei alto o basso?', 'Are you tall or short?', [['sono'], ['alto', 'alta', 'basso', 'bassa']], 'Sono alto.', [$t['alto'], $t['basso']], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.sei-moro', 'Sei moro?', 'Are you dark-haired?', [['sì', 'no'], ['moro', 'mora', 'biondo', 'bionda']], 'Sì, sono moro.', [$t['moro']], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.marta-alta', 'Marta è alta?', 'Is Marta tall?', [['sì', 'no'], ['alta', 'bassa']], 'No, è bassa.', [$t['bassa']], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.sei-giovane', 'Sei giovane?', 'Are you young?', [['sì', 'no'], ['giovane']], 'Sì, sono giovane.', [$t['giovane']], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.luca-basso-capelli', 'Luca è basso e ha i capelli biondi.', 'Luca is short and has blond hair.', [$t['basso'], $t['capelli'], $t['biondi'], $ha], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.marta-giovane-bella', 'Marta è giovane e molto bella.', 'Marta is young and very good-looking.', [$t['giovane'], $t['bella'], $e], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $t = $this->t();
        $e = Kit::form('è');
        $ha = Kit::form('ha');

        return [
            Kit::translate($s, 'check.a.translate.anna-alta-giovane', 'Anna is tall, young and dark-haired.', ['Anna è alta, giovane e mora.', 'Anna è alta, mora e giovane.', 'Anna è giovane, alta e mora.', 'Anna è giovane, mora e alta.', 'Anna è mora, alta e giovane.', 'Anna è mora, giovane e alta.'], [$t['alta'], $t['giovane'], $t['mora'], $e], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.paolo-capelli', 'Paolo is tall and has blond hair.', ['Paolo è alto e ha i capelli biondi.', 'Paolo è alto e ha capelli biondi.'], [$t['alto'], $t['capelli'], $t['biondi'], $ha], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.luca-persona', 'Luca is a young and nice person.', ['Luca è una persona giovane e simpatica.', 'Luca è una persona simpatica e giovane.'], [$t['persona'], $t['giovane'], $t['simpatica'], $e], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.marta-bene-bassa', 'Marta is fine, but she is short and good-looking.', ['Marta sta bene, ma è bassa e bella.', 'Marta sta bene, ma è bella e bassa.'], [$t['bassa'], $t['bella'], Kit::form('sta', true)], 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.paolo-luca-bassi', 'Paolo e Luca sono ___.', 'Paolo and Luca are short.', 'bassi', $t['bassi'], null, 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.anna-occhi', 'Anna ha gli ___ di Luca.', 'Anna has Luca\'s eyes.', 'occhi', $t['occhi'], null, 'sentences', $a),
            Kit::listenType($s, 'check.a.listen_type.marta-persona', 'Marta è una persona giovane e simpatica.', 'Marta is a young and nice person.', [$t['persona'], $t['giovane'], $t['simpatica'], $e], 'dictation', $a, homophoneNote: self::NOTE_IS.' '.self::NOTE_AND),
            Kit::listenType($s, 'check.a.listen_type.paolo-capelli-occhi', 'Paolo ha i capelli e gli occhi di Luca.', 'Paolo has Luca\'s hair and eyes.', [$t['capelli'], $t['occhi'], Kit::form('ha', true)], 'dictation', $a, homophoneNote: self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenType($s, 'check.a.listen_type.anna-bionda-marta-mora', 'Anna è bionda e Marta è mora.', 'Anna is blond and Marta is dark-haired.', [$t['bionda'], $t['mora']], 'dictation', $a, homophoneNote: self::NOTE_IS.' '.self::NOTE_AND),
            Kit::listenPassage($s, 'check.a.listen_passage.marta', [
                Kit::line('Anna', 'Paolo, Marta è alta?'),
                Kit::line('Paolo', 'No, Marta è bassa. Ma è giovane e molto bella.'),
                Kit::line('Anna', 'È bionda?'),
                Kit::line('Paolo', 'No, è mora. Ha gli occhi di Luca.'),
            ], [
                Kit::question('What is Marta like?', ['Short, young and good-looking', 'Tall and blond', 'Short and nice'], 'Short, young and good-looking'),
                Kit::question('Whose eyes does Marta have?', ['Luca\'s', 'Paolo\'s', 'Anna\'s'], 'Luca\'s'),
                Kit::question('Is Marta blond?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
            ], [
                Kit::question('Who asks about Marta?', ['Anna', 'Paolo', 'Nobody'], 'Anna'),
                Kit::question('Is Marta young?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$t['alta'], $t['bassa'], $t['giovane'], $t['bella'], $t['bionda'], $t['mora'], $t['occhi']], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.luca', 'Read the conversation.', [
                Kit::line('Marta', 'Anna, Luca è alto?'),
                Kit::line('Anna', 'No, è basso, ma è una persona molto simpatica. Ha gli occhi di Paolo.'),
                Kit::line('Marta', 'E Paolo?'),
                Kit::line('Anna', 'Paolo è alto e biondo.'),
            ], [
                Kit::question('What is Luca like?', ['Short and nice', 'Tall and nice', 'Short and blond'], 'Short and nice'),
                Kit::question('Who is blond?', ['Paolo', 'Luca', 'Nobody'], 'Paolo'),
            ], [$t['basso'], $t['persona'], $t['simpatico'], $t['occhi'], $t['alto'], $t['biondo']], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.sei-biondo', 'Sei biondo?', 'Are you blond?', [['sì', 'no'], ['biondo', 'bionda', 'moro', 'mora']], 'No, sono moro.', [$t['biondo'], $t['moro']], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.capelli-biondi', 'Hai i capelli biondi?', 'Do you have blond hair?', [['sì', 'no'], ['ho', 'capelli', 'biondi']], 'Sì, ho i capelli biondi.', [$t['capelli'], $t['biondi']], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.persona-simpatica', 'Sei una persona simpatica?', 'Are you a nice person?', [['sì', 'no'], ['sono', 'simpatico', 'simpatica']], 'Sì, sono una persona simpatica.', [$t['persona'], $t['simpatica']], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $t = $this->t();
        $e = Kit::form('è');
        $ha = Kit::form('ha');

        return [
            Kit::translate($s, 'check.b.translate.luca-alto-moro', 'Luca is tall and dark-haired.', ['Luca è alto e moro.', 'Luca è moro e alto.'], [$t['alto'], $t['moro'], $e], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.marta-bella-capelli', 'Marta is good-looking and has blond hair.', ['Marta è bella e ha i capelli biondi.', 'Marta è bella e ha capelli biondi.'], [$t['bella'], $t['capelli'], $t['biondi'], $ha], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.anna-giovane-occhi', 'Anna is young and has Paolo\'s eyes.', ['Anna è giovane e ha gli occhi di Paolo.'], [$t['giovane'], $t['occhi'], $ha], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.luca-bene-persona', 'Luca is fine, but he is not a nice person.', ['Luca sta bene, ma non è una persona simpatica.'], [$t['persona'], $t['simpatica'], Kit::form('sta', true)], 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.anna-marta-basse', 'Anna e Marta sono ___.', 'Anna and Marta are short.', 'basse', $t['basse'], null, 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.paolo-occhi', 'Paolo ha gli ___ di Marta.', 'Paolo has Marta\'s eyes.', 'occhi', $t['occhi'], null, 'sentences', $b),
            Kit::listenType($s, 'check.b.listen_type.marta-persona', 'Marta è una persona simpatica e alta.', 'Marta is a nice and tall person.', [$t['persona'], $t['simpatica'], $t['alta']], 'dictation', $b, homophoneNote: self::NOTE_IS.' '.self::NOTE_AND),
            Kit::listenType($s, 'check.b.listen_type.anna-bassa-capelli', 'Anna è bassa e ha i capelli biondi.', 'Anna is short and has blond hair.', [$t['bassa'], $t['capelli'], $t['biondi'], $e], 'dictation', $b, homophoneNote: self::NOTE_IS.' '.self::NOTE_HA.' '.self::NOTE_AND),
            Kit::listenType($s, 'check.b.listen_type.luca-giovane-alto', 'Luca è giovane, alto e moro. Ha gli occhi di Anna.', 'Luca is young, tall and dark-haired. He has Anna\'s eyes.', [$t['giovane'], $t['alto'], $t['moro'], $t['occhi'], Kit::form('ha', true)], 'dictation', $b, homophoneNote: self::NOTE_IS.' '.self::NOTE_HA.' '.self::NOTE_AND),
        ];
    }
}
