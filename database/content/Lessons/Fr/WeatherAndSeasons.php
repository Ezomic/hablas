<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Fr;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class WeatherAndSeasons implements UnitContent
{
    private const A_NOTE = 'A (has, the verb) and à (to, at) sound identical, and the sentence tells you which is which: in il y a (there is) it is the verb, with no accent.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et means and.';

    private const A_ET_NOTE = 'A (has, the verb) and à (to, at) sound identical, and et (and) sounds very close to est (is): in il y a the a is the verb, with no accent, and et means and.';

    private const MAIS_NOTE = 'Mais (but) and mes (my, plural) sound alike, and the sentence tells you which is which: here mais means but.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'weather-and-seasons';
    }

    public function words(): array
    {
        return [
            new WordData('le temps', cue: 'weather', forms: ['temps'], note: 'Temps also means time, but here it is the weather: Quel temps fait-il ? Do not mix it up with le temps libre (free time).'),
            new WordData('froid', cue: 'cold (weather, or how you feel)'),
            new WordData('chaud', cue: 'hot (weather, or how you feel)'),
            new WordData('le soleil', cue: 'sun', forms: ['soleil']),
            new WordData('la pluie', cue: 'rain (the noun)', forms: ['pluie']),
            new WordData('le nuage', cue: 'cloud', forms: ['nuage', 'nuages']),
            new WordData("l'hiver", cue: 'winter', forms: ['hiver']),
            new WordData("l'été", cue: 'summer', forms: ['été']),
            new WordData('le printemps', cue: 'spring (the season)', forms: ['printemps']),
            new WordData("l'automne", cue: 'autumn (fall)', forms: ['automne']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Il fait froid en hiver.', 'english' => 'It is cold in winter.'],
            ['text' => 'Il pleut et il y a des nuages.', 'english' => 'It rains and there are clouds.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-11', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-11', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-11', 'Released on the owner\'s instruction on 2026-10-11, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $s = Stage::Sentences;
        $temps = Kit::word('le temps', 'temps');
        $froid = Kit::word('froid');
        $chaud = Kit::word('chaud');
        $soleil = Kit::word('le soleil', 'soleil');
        $pluie = Kit::word('la pluie', 'pluie');
        $nuage = Kit::word('le nuage', 'nuage');
        $nuages = Kit::word('le nuage', 'nuages');
        $hiver = Kit::word("l'hiver", 'hiver');
        $ete = Kit::word("l'été", 'été');
        $printemps = Kit::word('le printemps', 'printemps');
        $automne = Kit::word("l'automne", 'automne');

        return [
            Kit::gap($s, 'sentences.choose_gap.hiver', 'En hiver, il ___ froid.', ['fait', 'est'], 'fait', Kit::form('fait', true), 'French says it makes cold: il fait froid. Il est froid would not describe the weather.', 'choose', 'In winter it is cold.'),
            Kit::gap($s, 'sentences.choose_gap.moi-froid', 'Moi, ___ froid.', ["j'ai", 'il fait'], "j'ai", Kit::form("j'ai", true), "When you feel cold yourself, you say j'ai froid. Il fait froid is only for the weather.", 'choose', 'I am cold.', ['moi' => 'me']),
            Kit::gap($s, 'sentences.choose_gap.nuages', '___ des nuages.', ['Il y a', 'Il fait'], 'Il y a', Kit::form('il y a'), 'Clouds are things that are there, so you say il y a des nuages. Il fait is for cold, heat and nice weather.', 'choose', 'There are clouds.'),
            Kit::gap($s, 'sentences.choose_gap.chaud', 'En été, il fait ___.', ['chaud', 'nuage', 'pluie'], 'chaud', $chaud, 'Il fait chaud means it is hot. Il fait nuage and il fait pluie do not exist: clouds use il y a and rain uses il pleut.', 'choose', 'In summer it is hot.'),
            Kit::gap($s, 'sentences.choose_gap.automne-pleut', 'En automne, il ___.', ['pleut', 'fait'], 'pleut', Kit::form('pleut', true), 'Rain has its own verb: il pleut. You do not say il fait for rain.', 'choose', 'In autumn it rains.'),
            Kit::gap($s, 'sentences.choose_gap.soleil', 'Au printemps, il y a du ___.', ['soleil', 'nuage', 'pluie'], 'soleil', $soleil, 'Il y a du soleil means it is sunny. Du goes with le soleil. Pluie is feminine and nuage takes un or des.', 'choose', 'In spring it is sunny.'),

            Kit::typeGap($s, 'sentences.type_gap.printemps-beau', 'Au printemps, il ___ beau.', 'In spring the weather is nice.', 'fait', Kit::form('fait'), 'The weather is nice is il fait beau, with fait as in il fait froid.'),
            Kit::typeGap($s, 'sentences.type_gap.quel-temps', 'Quel ___ fait-il en hiver ?', 'What is the weather like in winter?', 'temps', $temps),
            Kit::typeGap($s, 'sentences.type_gap.soleil-nuages', 'Il y a du soleil, mais pas de ___.', 'There is sun, but there are no clouds.', 'nuages', $nuages),
            Kit::typeGap($s, 'sentences.type_gap.pleut', 'Ici, il ___ en automne.', 'It rains here in autumn.', 'pleut', Kit::form('pleut'), 'It rains is one verb in French: il pleut.'),
            Kit::typeGap($s, 'sentences.type_gap.hiver', 'En ___, il pleut.', 'In autumn it rains.', 'automne', $automne),

            Kit::translate($s, 'sentences.translate.chaud-ete', 'It is hot in summer.', ['Il fait chaud en été.', 'En été, il fait chaud.'], [$chaud, $ete, Kit::form('il fait')]),
            Kit::translate($s, 'sentences.translate.jai-froid', 'I am cold.', ["J'ai froid."], [$froid, Kit::form("j'ai", true)]),
            Kit::translate($s, 'sentences.translate.soleil-printemps', 'Is it sunny in spring?', ['Il y a du soleil au printemps ?', 'Au printemps, il y a du soleil ?'], [$soleil, $printemps, Kit::form('il y a')]),

            Kit::build($s, 'sentences.build.quel-temps-hiver', 'What is the weather like in winter?', 'Quel temps fait-il en hiver ?', ['est'], [$temps, $hiver, Kit::form('fait-il')]),
            Kit::build($s, 'sentences.build.un-nuage', 'There is a cloud.', 'Il y a un nuage.', ['fait'], [$nuage, Kit::form('il y a')]),
            Kit::build($s, 'sentences.build.pas-chaud', 'I am not hot.', "Je n'ai pas chaud.", ['est'], [$chaud, Kit::form("n'ai", true)]),

            Kit::listenChoose($s, 'sentences.listen_choose.chaud-ete', 'Il fait chaud en été.', ['It is hot in summer.', 'It is cold in summer.', 'It is hot in winter.', 'It rains in summer.'], 'It is hot in summer.', [$chaud, $ete, Kit::form('il fait')]),
            Kit::listenChoose($s, 'sentences.listen_choose.automne', 'En automne, il pleut.', ['It rains in autumn.', 'It is sunny in autumn.', 'It rains in spring.', 'It is cold in autumn.'], 'It rains in autumn.', [$automne, Kit::form('il pleut')]),
            Kit::listenChoose($s, 'sentences.listen_choose.nuages-printemps', 'Il y a des nuages au printemps ?', ['Are there clouds in spring?', 'Is it sunny in spring?', 'Are there clouds in autumn?', 'Does it rain in spring?'], 'Are there clouds in spring?', [$printemps, $nuages, Kit::form('il y a')]),
            Kit::listenType($s, 'sentences.listen_type.hiver-froid', 'En hiver, il fait froid.', 'In winter it is cold.', [$hiver, $froid, Kit::form('il fait')]),
            Kit::listenType($s, 'sentences.listen_type.ete-soleil', 'En été, il y a du soleil.', 'In summer there is sun.', [$ete, $soleil, Kit::form('il y a')], homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'sentences.listen_type.pluie-froid', 'Avec la pluie, il fait froid.', 'With the rain it is cold.', [$pluie, $froid, Kit::form('il fait')]),
            Kit::listenType($s, 'sentences.listen_type.temps-automne', 'Quel temps fait-il en automne ?', 'What is the weather like in autumn?', [$temps, $automne, Kit::form('fait-il')]),

            Kit::speakRepeat($s, 'sentences.speak_repeat.beau-printemps', 'Il fait beau ici au printemps.', 'The weather is nice here in spring.', [$printemps, Kit::form('il fait beau')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.jai-chaud', "J'ai chaud en été.", 'I am hot in summer.', [$chaud, $ete, Kit::form("j'ai", true)]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.pluie-ici', 'Il y a beaucoup de pluie ici.', 'There is a lot of rain here.', [$pluie, Kit::form('il y a')]),
            Kit::speakRepeat($s, 'sentences.speak_repeat.pleut-froid', 'Il pleut et il fait froid.', 'It rains and it is cold.', [$froid, Kit::form('il pleut')]),
            Kit::speakAnswer($s, 'sentences.speak_answer.ete', 'Et en été, quel temps fait-il ?', 'What is the weather like in summer?', [['fait', 'a'], ['chaud', 'soleil', 'beau']], 'En été, il fait chaud.', [$chaud, Kit::form('il fait')]),
            Kit::speakAnswer($s, 'sentences.speak_answer.hiver', 'Il fait froid en hiver ?', 'Is it cold in winter?', [['oui', 'non'], ['fait', 'a', 'pleut']], 'Oui, il fait froid.', [$froid, $hiver, Kit::form('il fait')]),
            Kit::speakAnswer($s, 'sentences.speak_answer.pleut', 'Il pleut en automne ?', 'Does it rain in autumn?', [['oui', 'non'], ['pleut', 'pluie']], 'Oui, il pleut en automne.', [$automne, Kit::form('il pleut')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $s = Stage::Task;
        $temps = Kit::word('le temps', 'temps');
        $froid = Kit::word('froid');
        $chaud = Kit::word('chaud');
        $soleil = Kit::word('le soleil', 'soleil');
        $pluie = Kit::word('la pluie', 'pluie');
        $nuages = Kit::word('le nuage', 'nuages');
        $hiver = Kit::word("l'hiver", 'hiver');
        $ete = Kit::word("l'été", 'été');
        $printemps = Kit::word('le printemps', 'printemps');
        $automne = Kit::word("l'automne", 'automne');

        return [
            Kit::readPassage($s, 'task.read_passage.temps-ete', 'Read the conversation about the weather.', [
                Kit::line('Anne', 'Paul, quel temps fait-il en été ?'),
                Kit::line('Paul', 'Il fait chaud et il y a du soleil. Je vais à la plage. Et en hiver ?'),
                Kit::line('Anne', 'Il fait froid et il pleut. Je suis à la maison.'),
                Kit::line('Paul', 'Et au printemps ?'),
                Kit::line('Anne', 'Il fait beau.'),
            ], [
                Kit::question('What is the weather like in summer?', ['Hot and sunny', 'Cold and rainy', 'Cloudy'], 'Hot and sunny'),
                Kit::question('What does Paul do in summer?', ['He goes to the beach.', 'He stays at home.', 'He goes to the cinema.'], 'He goes to the beach.'),
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
            ], [$temps, $ete, $chaud, $soleil, $hiver, $froid, $printemps], 'read', glosses: ['plage' => 'beach', 'maison' => 'home']),
            Kit::gap($s, 'task.choose_gap.ici-chaud', 'Ici, il ___ chaud et il y a du soleil.', ['fait', 'est'], 'fait', Kit::form('fait', true), 'French says it makes hot: il fait chaud. Il est chaud would not describe the weather.', 'read', 'It is hot here and there is sun.'),
            Kit::gap($s, 'task.choose_gap.quel-temps', 'Quel ___ fait-il ici ?', ['temps', 'soleil', 'nuage'], 'temps', $temps, 'Quel temps fait-il ? is the question for the weather. Soleil and nuage are things in the sky, not the weather as a whole.', 'read', 'What is the weather like here?'),

            Kit::transform($s, 'task.transform.question-soleil', 'Now ask a question.', 'Il y a du soleil en été.', ['Il y a du soleil en été ?', 'En été, il y a du soleil ?'], [$soleil, $ete, Kit::form('il y a')]),
            Kit::transform($s, 'task.transform.jai-froid', 'Now say that you are cold.', 'Il fait froid.', ["J'ai froid."], [$froid, Kit::form("j'ai", true)]),
            Kit::transform($s, 'task.transform.nuages', 'Change it to several clouds.', 'Il y a un nuage.', ['Il y a des nuages.'], [$nuages, Kit::form('il y a')]),
            Kit::writeGuided($s, 'task.write_guided.ete', 'Say that in summer it is hot and there is sun.', ['été', 'fait', 'chaud', 'soleil'], 'En été, il fait chaud et il y a du soleil.', [
                ['forms' => ['été'], 'term' => "l'été"],
                ['forms' => ['chaud'], 'term' => 'chaud'],
                ['forms' => ['soleil'], 'term' => 'le soleil'],
            ], [$ete, $chaud, $soleil]),
            Kit::writeGuided($s, 'task.write_guided.automne', 'Say that in autumn it rains and there are clouds.', ['automne', 'pleut', 'il y a', 'nuages'], 'En automne, il pleut et il y a des nuages.', [
                ['forms' => ['automne'], 'term' => "l'automne"],
                ['forms' => ['pleut'], 'term' => null],
                ['forms' => ['nuages'], 'term' => 'le nuage'],
            ], [$automne, $nuages, Kit::form('il pleut')]),
            Kit::build($s, 'task.build.quel-temps-printemps', 'What is the weather like in spring?', 'Quel temps fait-il au printemps ?', ['est', 'sont'], [$temps, $printemps, Kit::form('fait-il')], 'write'),
            Kit::build($s, 'task.build.soleil-nuages', 'There is sun and there are clouds.', 'Il y a du soleil et des nuages.', ['fait', 'est'], [$soleil, $nuages, Kit::form('il y a')], 'write'),
            Kit::build($s, 'task.build.ete-beau', 'The weather is nice in summer.', 'Il fait beau en été.', ['est', 'sont'], [$ete, Kit::form('il fait beau')], 'write'),
            Kit::translate($s, 'task.translate.chaud-froid', 'It is hot in summer, but it is cold in winter.', ['En été, il fait chaud, mais en hiver, il fait froid.', 'Il fait chaud en été, mais il fait froid en hiver.'], [$ete, $chaud, $hiver, $froid, Kit::form('il fait')], 'write'),
            Kit::translate($s, 'task.translate.automne-pluie', 'In autumn there are clouds and rain.', ['En automne, il y a des nuages et de la pluie.', 'Il y a des nuages et de la pluie en automne.'], [$automne, $nuages, $pluie, Kit::form('il y a')], 'write'),

            Kit::listenPassage($s, 'task.listen_passage.froid-chaud', [
                Kit::line('Marie', 'Luc, quel temps fait-il ?'),
                Kit::line('Luc', 'Il fait froid et il y a des nuages.'),
                Kit::line('Marie', 'Il pleut ?'),
                Kit::line('Luc', 'Non, il ne pleut pas. Mais pas de soleil.'),
                Kit::line('Marie', 'Ici, il fait chaud et il y a du soleil.'),
            ], [
                Kit::question('What is the weather like where Luc is?', ['Cold and cloudy', 'Hot and sunny', 'Rainy'], 'Cold and cloudy'),
                Kit::question('Does it rain where Luc is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('What is the weather like where Marie is?', ['Hot and sunny', 'Cold and cloudy', 'Rainy'], 'Hot and sunny'),
            ], [
                Kit::question('Who asks about the weather first?', ['Marie', 'Luc', 'Nobody'], 'Marie'),
                Kit::question('Is there sun where Luc is?', ['No', 'Yes', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$froid, $nuages, $soleil, $chaud], 'listen'),
            Kit::listenType($s, 'task.listen_type.printemps', 'Au printemps, il y a du soleil et de la pluie.', 'In spring there is sun and rain.', [$printemps, $soleil, $pluie, Kit::form('il y a')], 'listen', homophoneNote: self::A_ET_NOTE),
            Kit::listenType($s, 'task.listen_type.chaud-froid', "J'ai chaud, mais il fait froid ici.", 'I am hot, but it is cold here.', [$chaud, $froid, Kit::form("j'ai", true)], 'listen', homophoneNote: self::MAIS_NOTE),
            Kit::listenType($s, 'task.listen_type.ete-pleut', 'En été, il ne fait pas froid et il ne pleut pas.', 'In summer it is not cold and it does not rain.', [$ete, $froid, Kit::form('pleut')], 'listen', homophoneNote: self::ET_NOTE),

            Kit::speakAnswer($s, 'task.speak_answer.printemps', 'Quel temps fait-il au printemps ?', 'What is the weather like in spring?', [['fait', 'a', 'pleut'], ['beau', 'soleil', 'nuages', 'pluie', 'froid', 'chaud']], 'Au printemps, il fait beau.', [$temps, $printemps], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.soleil-hiver', 'Il y a du soleil ici en hiver ?', 'Is there sun here in winter?', [['oui', 'non'], ['a', 'fait', 'nuages', 'pleut']], 'Oui, il y a du soleil.', [$soleil, Kit::form('il y a')], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.tu-as-froid', 'Tu as froid ?', 'Are you cold?', [['oui', 'non'], ['ai', 'froid']], "Oui, j'ai froid.", [$froid, Kit::form("j'ai", true)], 'speak'),
            Kit::speakAnswer($s, 'task.speak_answer.pleut-ete', 'Il pleut en été ?', 'Does it rain in summer?', [['oui', 'non'], ['pleut', 'pluie']], 'Non, il ne pleut pas en été.', [$ete, $pluie, Kit::form('il pleut')], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.ete-chaud-soleil', 'En été, il fait chaud et il y a du soleil.', 'In summer it is hot and there is sun.', [$ete, $chaud, $soleil], 'speak'),
            Kit::speakRepeat($s, 'task.speak_repeat.beau-froid', "Il fait beau, mais j'ai froid.", 'The weather is nice, but I am cold.', [$froid, Kit::form("j'ai", true)], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $s = Stage::Check;
        $a = 'a';
        $temps = Kit::word('le temps', 'temps');
        $froid = Kit::word('froid');
        $chaud = Kit::word('chaud');
        $soleil = Kit::word('le soleil', 'soleil');
        $pluie = Kit::word('la pluie', 'pluie');
        $nuages = Kit::word('le nuage', 'nuages');
        $hiver = Kit::word("l'hiver", 'hiver');
        $ete = Kit::word("l'été", 'été');
        $printemps = Kit::word('le printemps', 'printemps');
        $automne = Kit::word("l'automne", 'automne');

        return [
            Kit::translate($s, 'check.a.translate.hiver-nuages', 'In winter there are clouds.', ['En hiver, il y a des nuages.', 'Il y a des nuages en hiver.'], [$hiver, $nuages, Kit::form('il y a')], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.chaud-automne', 'It is hot in summer, but in autumn it rains.', ['Il fait chaud en été, mais en automne il pleut.', 'En été, il fait chaud, mais en automne, il pleut.'], [$chaud, $ete, $automne, Kit::form('il pleut', true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.froid-automne', 'I am cold in autumn.', ["J'ai froid en automne.", "En automne, j'ai froid."], [$froid, $automne, Kit::form("j'ai", true)], 'sentences', $a),
            Kit::translate($s, 'check.a.translate.beau-printemps', 'The weather is nice in spring.', ['Il fait beau au printemps.', 'Au printemps, il fait beau.'], [$printemps, Kit::form('il fait beau')], 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.soleil', 'Il y a du ___ et pas de nuages.', 'There is sun and there are no clouds.', 'soleil', $soleil, null, 'sentences', $a),
            Kit::typeGap($s, 'check.a.type_gap.pluie', 'Il y a des nuages et de la ___.', 'There are clouds and rain.', 'pluie', $pluie, null, 'sentences', $a),
            Kit::listenType($s, 'check.a.listen_type.soleil-hiver', 'Il y a du soleil en hiver ?', 'Is there sun in winter?', [$soleil, $hiver, Kit::form('il y a')], 'dictation', $a, homophoneNote: self::A_NOTE),
            Kit::listenType($s, 'check.a.listen_type.nuages-pluie', "Avec les nuages et la pluie, j'ai froid.", 'With the clouds and the rain I am cold.', [$nuages, $pluie], 'dictation', $a, homophoneNote: self::ET_NOTE),
            Kit::listenType($s, 'check.a.listen_type.temps-hiver', 'Quel temps fait-il ici en hiver ?', 'What is the weather like here in winter?', [$temps, $hiver, Kit::form('fait-il')], 'dictation', $a),
            Kit::listenPassage($s, 'check.a.listen_passage.printemps', [
                Kit::line('Paul', 'Anne, il pleut au printemps ?'),
                Kit::line('Anne', 'Oui, il pleut et il y a des nuages. Mais en été, il y a du soleil.'),
                Kit::line('Paul', "Ici aussi, il y a du soleil. J'ai chaud !"),
            ], [
                Kit::question('When does it rain, according to Anne?', ['In spring', 'In summer', 'In winter'], 'In spring'),
                Kit::question('What is the weather like in summer?', ['Sunny', 'Rainy', 'Cold'], 'Sunny'),
                Kit::question('How does Paul feel?', ['Hot', 'Cold', 'Ill'], 'Hot'),
            ], [
                Kit::question('Who asks the question?', ['Paul', 'Anne', 'Nobody'], 'Paul'),
                Kit::question('Does it rain in spring?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [$printemps, $nuages, $ete, $soleil, $chaud], 'passages', $a),
            Kit::readPassage($s, 'check.a.read_passage.hiver', 'Read the conversation.', [
                Kit::line('Luc', 'Marie, il fait froid en hiver ?'),
                Kit::line('Marie', "Oui, il fait froid. Il pleut. J'ai froid !"),
                Kit::line('Luc', 'Et en automne ?'),
                Kit::line('Marie', 'En automne, il y a des nuages.'),
            ], [
                Kit::question('What is the weather like in winter?', ['Cold and rainy', 'Hot and sunny', 'Nice'], 'Cold and rainy'),
                Kit::question('How does Marie feel in winter?', ['Cold', 'Hot', 'Fine'], 'Cold'),
            ], [$hiver, $froid, $pluie, $automne, $nuages], 'passages', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.ici', 'Et ici, quel temps fait-il ?', 'What is the weather like here?', [['fait', 'a'], ['chaud', 'froid', 'beau', 'soleil', 'nuages', 'pluie']], 'Ici, il fait chaud.', [$temps, $chaud], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.nuages-automne', 'Il y a des nuages en automne ?', 'Are there clouds in autumn?', [['oui', 'non'], ['a', 'nuages']], 'Oui, il y a des nuages en automne.', [$nuages, $automne], 'speaking', $a),
            Kit::speakAnswer($s, 'check.a.speak_answer.chaud-ete', 'Tu as chaud en été ?', 'Are you hot in summer?', [['oui', 'non'], ['ai', 'chaud']], "Oui, j'ai chaud.", [$chaud, $ete], 'speaking', $a),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $s = Stage::Check;
        $b = 'b';
        $temps = Kit::word('le temps', 'temps');
        $froid = Kit::word('froid');
        $chaud = Kit::word('chaud');
        $soleil = Kit::word('le soleil', 'soleil');
        $pluie = Kit::word('la pluie', 'pluie');
        $nuages = Kit::word('le nuage', 'nuages');
        $hiver = Kit::word("l'hiver", 'hiver');
        $ete = Kit::word("l'été", 'été');
        $printemps = Kit::word('le printemps', 'printemps');
        $automne = Kit::word("l'automne", 'automne');

        return [
            Kit::translate($s, 'check.b.translate.temps-printemps', 'What is the weather like here in spring?', ['Quel temps fait-il ici au printemps ?', 'Quel temps fait-il au printemps ici ?'], [$temps, $printemps, Kit::form('fait-il')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.automne-froid', 'In autumn it rains and I am cold.', ["En automne, il pleut et j'ai froid.", "Il pleut et j'ai froid en automne."], [$automne, $froid, Kit::form('il pleut', true)], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.ete-nuages', 'In summer it is hot, with no clouds.', ['En été, il fait chaud, sans nuages.', 'Il fait chaud en été, sans nuages.'], [$ete, $chaud, $nuages, Kit::form('il fait')], 'sentences', $b),
            Kit::translate($s, 'check.b.translate.hiver-chaud', 'It is not hot in winter.', ['Il ne fait pas chaud en hiver.', 'En hiver, il ne fait pas chaud.'], [$hiver, $chaud, Kit::form('fait')], 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.printemps', 'Ici, il fait beau au ___.', 'The weather is nice here in spring.', 'printemps', $printemps, null, 'sentences', $b),
            Kit::typeGap($s, 'check.b.type_gap.soleil', 'En été, il y a du ___ et des nuages.', 'In summer there is sun and there are clouds.', 'soleil', $soleil, null, 'sentences', $b),
            Kit::listenType($s, 'check.b.listen_type.pluie-nuages', 'Il y a de la pluie et des nuages.', 'There is rain and there are clouds.', [$pluie, $nuages, Kit::form('il y a')], 'dictation', $b, homophoneNote: self::A_ET_NOTE),
            Kit::listenType($s, 'check.b.listen_type.soleil-froid', "Il y a du soleil, mais j'ai froid.", 'There is sun, but I am cold.', [$soleil, $froid, Kit::form("j'ai", true)], 'dictation', $b, homophoneNote: self::MAIS_NOTE),
            Kit::listenType($s, 'check.b.listen_type.temps-ete', 'Quel temps fait-il en été ?', 'What is the weather like in summer?', [$temps, $ete], 'dictation', $b),
        ];
    }
}
