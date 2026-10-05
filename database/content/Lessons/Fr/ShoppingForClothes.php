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

final class ShoppingForClothes implements UnitContent
{
    private const EST_NOTE = 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.';

    private const ET_NOTE = 'Et (and) and est (is, the verb) sound very close, and the sentence tells you which is which: here et joins two things.';

    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'shopping-for-clothes';
    }

    public function words(): array
    {
        return [
            new WordData('les vêtements', cue: 'clothes', accepted: ['les habits']),
            new WordData('la chemise', cue: 'shirt', forms: ['chemises']),
            new WordData('le pantalon', cue: 'trousers, pants (one pair; a pair is singular in French)', accepted: ['les pantalons']),
            new WordData('le prix', cue: 'price'),
            new WordData('la taille', cue: 'size (of clothes)'),
            new WordData('la couleur', cue: 'color'),
            new WordData('cher', cue: 'expensive (masculine singular; the other forms are chère, chers, chères)', forms: ['chère', 'chers', 'chères']),
            new WordData('bon marché', cue: 'cheap, inexpensive (never changes, whatever the noun)'),
            new WordData('essayer', cue: 'to try on (clothes)', forms: ['j\'essaie', 'j\'essaye', 'essaie', 'essaye', 'essayez']),
            new WordData('la réduction', cue: 'discount', accepted: ['la remise']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'La chemise est chère.', 'english' => 'The shirt is expensive.'],
            ['text' => 'Les pantalons sont bon marché.', 'english' => 'The trousers are cheap.'],
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
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Words, 'independent AI review (model knowledge, no dictionary pass)', '2026-10-05', 'Terms, cues, accepted answers, forms and the grammar note checked for correct and natural French (France). A dictionary pass is still open.'),
            new ContentReview(ReviewKind::IndependentAi, ReviewScope::Lessons, 'independent AI review of the exercises', '2026-10-05', 'The exercises of this unit were reviewed by a separate reviewer for natural French (France), one defensible answer, distractors, accepted answers and speaking slots, and the findings were fixed. Structure is checked by the content test.'),
            new ContentReview(ReviewKind::Owner, ReviewScope::Lessons, 'owner', '2026-10-05', 'Released on the owner\'s instruction on 2026-10-05, without a line by line review of the lessons.'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.chemise-chere', 'La chemise est ___.', ['chère', 'cher', 'chers'], 'chère', Kit::form('chère', true), 'Chemise is feminine singular, so cher takes an -e: chère.', 'choose', 'The shirt is expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.pantalon-bleu', 'Le pantalon est ___.', ['bleu', 'bleue', 'bleus'], 'bleu', Kit::form('bleu'), 'Pantalon is masculine singular, so the adjective has no ending: bleu.', 'choose', 'The pair of trousers is blue.', ['bleu' => 'blue', 'bleue' => 'blue', 'bleus' => 'blue']),
            Kit::gap($stage, 'sentences.choose_gap.pantalons-chers', 'Les pantalons sont ___.', ['chers', 'cher', 'chères'], 'chers', Kit::form('chers', true), 'Pantalons is masculine plural, so cher takes an -s: chers.', 'choose', 'The trousers are expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.reduction', 'La chemise est chère. ___ la réduction, elle est bon marché.', ['Avec', 'Sans'], 'Avec', Kit::word('la réduction', 'réduction'), 'Avec means with and sans means without: the discount is what makes the shirt cheap, so avec fits.', 'choose', 'The shirt is expensive. With the discount, it is cheap.'),
            Kit::gap($stage, 'sentences.choose_gap.chemises-cheres', 'Les chemises sont ___.', ['chères', 'chère', 'chers'], 'chères', Kit::form('chères', true), 'Chemises is feminine plural, so cher takes -e and -s: chères.', 'choose', 'The shirts are expensive.'),
            Kit::gap($stage, 'sentences.choose_gap.couleur', 'De quelle ___ est la chemise ?', ['couleur', 'prix', 'pantalon'], 'couleur', Kit::word('la couleur', 'couleur'), 'To ask about the color you say de quelle couleur.', 'choose', 'What color is the shirt?'),

            Kit::typeGap($stage, 'sentences.type_gap.chemise-noire', 'La chemise est ___. (noir)', 'The shirt is black.', 'noire', Kit::form('noire'), 'Chemise is feminine, so noir becomes noire.', glosses: ['noir' => 'black']),
            Kit::typeGap($stage, 'sentences.type_gap.vetements-bon-marche', 'Les vêtements sont ___.', 'The clothes are cheap.', 'bon marché', Kit::form('bon marché', true), 'Bon marché never changes: no -s for the plural and no -e for the feminine.'),
            Kit::typeGap($stage, 'sentences.type_gap.prix', 'Voici le ___.', 'Here is the price.', 'prix', Kit::word('le prix', 'prix')),
            Kit::typeGap($stage, 'sentences.type_gap.chemises-bleues', 'Voici des chemises ___. (bleu)', 'Here are some blue shirts.', 'bleues', Kit::form('bleues'), 'The adjective comes after the noun and agrees with it: chemises is feminine plural, so bleu becomes bleues.', glosses: ['bleu' => 'blue']),
            Kit::typeGap($stage, 'sentences.type_gap.essaie', '___ la chemise.', 'I try on the shirt.', "J'essaie", Kit::word('essayer', "j'essaie")),
            Kit::translate($stage, 'sentences.translate.chemise', 'The shirt is cheap.', ['La chemise est bon marché.'], [Kit::word('la chemise'), Kit::word('bon marché'), Kit::form('bon marché', true)]),
            Kit::translate($stage, 'sentences.translate.vetements', 'The clothes are very expensive.', ['Les vêtements sont très chers.'], [Kit::word('les vêtements'), Kit::word('cher', 'chers'), Kit::form('chers', true)]),
            Kit::translate($stage, 'sentences.translate.essaie', 'I try on the pair of trousers.', ["J'essaie le pantalon.", "J'essaye le pantalon."], [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('le pantalon')]),
            Kit::build($stage, 'sentences.build.chemise', 'The shirt is very expensive.', 'La chemise est très chère.', ['cher'], [Kit::word('la chemise'), Kit::word('cher', 'chère'), Kit::form('chère', true)]),
            Kit::build($stage, 'sentences.build.vetements', 'The clothes are very cheap.', 'Les vêtements sont très bon marché.', ['est'], [Kit::word('les vêtements'), Kit::word('bon marché'), Kit::form('bon marché', true)]),
            Kit::build($stage, 'sentences.build.prix', 'What is the price?', 'Quel est le prix ?', ['quelle'], [Kit::word('le prix')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.chemise', 'La chemise est chère.', ['The shirt is expensive.', 'The shirt is cheap.', 'The shirt is blue.', 'The clothes are expensive.'], 'The shirt is expensive.', [Kit::word('la chemise'), Kit::word('cher', 'chère'), Kit::form('chère')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.prix', 'Quel est le prix ?', ['What is the price?', 'What is the size?', 'What color is it?', 'Is there a discount?'], 'What is the price?', [Kit::word('le prix')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.chemises', 'Les chemises sont bon marché.', ['The shirts are cheap.', 'The shirts are expensive.', 'The shirt is cheap.', 'The clothes are cheap.'], 'The shirts are cheap.', [Kit::word('la chemise', 'chemises'), Kit::word('bon marché'), Kit::form('bon marché', true)]),
            Kit::listenType($stage, 'sentences.listen_type.taille', 'Quelle est la taille ?', 'What is the size?', [Kit::word('la taille', 'taille')], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.essaie', "J'essaie la chemise.", 'I try on the shirt.', [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('la chemise')], alsoAccepted: ["J'essaye la chemise."]),
            Kit::listenType($stage, 'sentences.listen_type.pantalon', 'Le pantalon est très bon marché.', 'The pair of trousers is very cheap.', [Kit::word('le pantalon'), Kit::word('bon marché'), Kit::form('bon marché', true)], homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.couleur', 'Voici la couleur et la taille.', 'Here are the color and the size.', [Kit::word('la couleur', 'couleur'), Kit::word('la taille', 'taille')], homophoneNote: self::ET_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.pantalon', 'Le pantalon est très cher.', 'The pair of trousers is very expensive.', [Kit::word('le pantalon'), Kit::word('cher'), Kit::form('cher')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.taille', 'Quelle est votre taille ?', 'What is your size?', [Kit::word('la taille', 'taille')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.essayez', 'Vous essayez la chemise ?', 'Are you trying on the shirt?', [Kit::word('essayer', 'essayez'), Kit::word('la chemise')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.reduction', 'Il y a une réduction.', 'There is a discount.', [Kit::word('la réduction', 'réduction')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pantalon', 'Le pantalon est cher ?', 'Is the pair of trousers expensive?', [['oui', 'non', 'est'], ['cher', 'pantalon', 'marché']], 'Oui, le pantalon est cher.', [Kit::word('le pantalon'), Kit::word('cher')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.reduction', 'Vous avez une réduction ?', 'Do you have a discount?', [['oui', 'non', 'avons', 'a'], ['réduction', 'remise']], 'Oui, il y a une réduction.', [Kit::word('la réduction', 'réduction')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.chemise', 'La chemise est chère ou bon marché ?', 'Is the shirt expensive or cheap?', [['est', 'chère', 'marché'], ['chemise', 'chère', 'marché', 'elle']], 'La chemise est bon marché.', [Kit::word('la chemise'), Kit::word('bon marché')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.magasin', 'Read the conversation in the shop.', [
                Kit::line('Anne', 'Bonjour. Quel est le prix de la chemise ?'),
                Kit::line('Vendeuse', 'Bonjour. La chemise est chère, mais le pantalon est bon marché.'),
                Kit::line('Anne', "J'essaie la chemise. Il y a une réduction sur les vêtements ?"),
                Kit::line('Vendeuse', 'Oui, il y a une réduction. Quelle est votre taille ?'),
                Kit::line('Anne', 'Ma taille est quarante. De quelle couleur est la chemise ?'),
                Kit::line('Vendeuse', 'La chemise est bleue.'),
            ], [
                Kit::question('Which item is expensive?', ['The shirt', 'The pair of trousers', 'Both'], 'The shirt'),
                Kit::question('Is there a discount?', ['Yes, on the clothes.', 'No, there is not.', 'The text does not say.'], 'Yes, on the clothes.'),
                Kit::question('What color is the shirt?', ['Green', 'Blue', 'Black'], 'Blue'),
            ], [Kit::word('le prix'), Kit::word('la chemise'), Kit::word('cher'), Kit::word('le pantalon'), Kit::word('bon marché'), Kit::word('essayer'), Kit::word('la réduction'), Kit::word('les vêtements'), Kit::word('la taille'), Kit::word('la couleur')], 'read', null, ['quarante' => 'forty (a size)', 'bleue' => 'blue']),
            Kit::gap($stage, 'task.choose_gap.chemise-rouge', 'La chemise est ___.', ['rouge', 'rouges'], 'rouge', Kit::form('rouge', true), 'Rouge already ends in -e, so it has the same form for masculine and feminine: la chemise rouge. Only the plural adds -s.', 'read', 'The shirt is red.', ['rouge' => 'red', 'rouges' => 'red']),
            Kit::gap($stage, 'task.choose_gap.pantalons-verts', 'Les pantalons sont ___.', ['verts', 'vert'], 'verts', Kit::form('verts', true), 'Pantalons is masculine plural, so vert takes an -s: verts.', 'read', 'The trousers are green.', ['verts' => 'green', 'vert' => 'green']),

            Kit::transform($stage, 'task.transform.chemises', 'Make it plural.', 'La chemise est chère.', ['Les chemises sont chères.'], [Kit::word('la chemise', 'chemises'), Kit::word('cher', 'chères'), Kit::form('chères')]),
            Kit::transform($stage, 'task.transform.pantalons', 'Make it plural.', 'Le pantalon est bon marché.', ['Les pantalons sont bon marché.'], [Kit::word('le pantalon', 'pantalons'), Kit::word('bon marché'), Kit::form('bon marché', true)]),
            Kit::transform($stage, 'task.transform.noire', 'Talk about the shirt instead.', 'Le pantalon est noir.', ['La chemise est noire.'], [Kit::word('la chemise'), Kit::form('noire')], ['noir' => 'black', 'noire' => 'black']),
            Kit::writeGuided($stage, 'task.write_guided.chemise', 'Say that the shirt is expensive and ask whether there is a discount.', ['chemise', 'chère', 'réduction'], 'La chemise est chère. Il y a une réduction ?', [
                ['forms' => ['chemise'], 'term' => 'la chemise'],
                ['forms' => ['chère'], 'term' => 'cher'],
                ['forms' => ['réduction', 'remise'], 'term' => 'la réduction'],
            ], [Kit::word('la chemise'), Kit::word('cher'), Kit::word('la réduction')]),
            Kit::writeGuided($stage, 'task.write_guided.couleur', 'Ask what color the trousers are and say that your size is forty.', ['couleur', 'pantalon', 'taille'], 'De quelle couleur est le pantalon ? Ma taille est quarante.', [
                ['forms' => ['couleur'], 'term' => 'la couleur'],
                ['forms' => ['pantalon', 'pantalons'], 'term' => 'le pantalon'],
                ['forms' => ['taille'], 'term' => 'la taille'],
            ], [Kit::word('la couleur'), Kit::word('le pantalon'), Kit::word('la taille')], ['quarante' => 'forty']),
            Kit::build($stage, 'task.build.bleue', 'The shirt is blue and the pair of trousers is cheap.', 'La chemise est bleue et le pantalon est bon marché.', ['sont', 'chers'], [Kit::word('la chemise'), Kit::word('le pantalon'), Kit::word('bon marché'), Kit::form('bleue')], 'write', ['bleue' => 'blue']),
            Kit::build($stage, 'task.build.essaie', 'I try on the shirt and the pair of trousers.', "J'essaie la chemise et le pantalon.", ['est', 'sont'], [Kit::word('essayer', "j'essaie"), Kit::word('la chemise'), Kit::word('le pantalon')]),
            Kit::build($stage, 'task.build.prix', 'Here is the price of the shirt.', 'Voici le prix de la chemise.', ['sont', 'quel'], [Kit::word('le prix'), Kit::word('la chemise')]),
            Kit::translate($stage, 'task.translate.prix-taille', 'The price and the size are here.', ['Le prix et la taille sont ici.', 'Voici le prix et la taille.'], [Kit::word('le prix'), Kit::word('la taille')]),
            Kit::translate($stage, 'task.translate.reduction', 'Is there a discount on the clothes?', ['Il y a une réduction sur les vêtements ?'], [Kit::word('la réduction', 'réduction'), Kit::word('les vêtements')]),

            Kit::listenPassage($stage, 'task.listen_passage.magasin', [
                Kit::line('Paul', 'Bonjour. Quel est le prix du pantalon ?'),
                Kit::line('Vendeuse', 'Le pantalon est cher, mais la chemise est bon marché.'),
                Kit::line('Paul', "J'essaie la chemise. Il y a une réduction ?"),
                Kit::line('Vendeuse', 'Oui, avec la réduction, la chemise est très bon marché.'),
                Kit::line('Paul', 'Merci. Au revoir.'),
            ], [
                Kit::question('Which item is cheap?', ['The shirt', 'The pair of trousers', 'Both'], 'The shirt'),
                Kit::question('What does Paul try on?', ['The pair of trousers', 'The shirt', 'Nothing'], 'The shirt'),
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('What does Paul ask about first?', ['The price of the trousers', 'The size', 'The color'], 'The price of the trousers'),
                Kit::question('Is the pair of trousers expensive?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How does the conversation end?', ['Paul says thank you and goodbye.', 'Paul asks the price.', 'Paul tries on the trousers.'], 'Paul says thank you and goodbye.'),
            ], [Kit::word('le prix'), Kit::word('le pantalon'), Kit::word('cher'), Kit::word('la chemise'), Kit::word('bon marché'), Kit::word('essayer'), Kit::word('la réduction')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.reduction', 'Avec la réduction, la chemise est bon marché.', 'With the discount, the shirt is cheap.', [Kit::word('la réduction', 'réduction'), Kit::word('la chemise'), Kit::word('bon marché'), Kit::form('bon marché', true)], 'listen', homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'task.listen_type.couleur', 'Quelle est la couleur du pantalon ?', 'What is the color of the pair of trousers?', [Kit::word('la couleur', 'couleur'), Kit::word('le pantalon', 'pantalon')], 'listen', homophoneNote: self::EST_NOTE),
            Kit::listenType($stage, 'task.listen_type.essaie', "J'essaie le pantalon et la chemise.", 'I try on the pair of trousers and the shirt.', [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('le pantalon'), Kit::word('la chemise')], 'listen', alsoAccepted: ["J'essaye le pantalon et la chemise."], homophoneNote: self::ET_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.vetements', 'Les vêtements sont chers ou bon marché ?', 'Are the clothes expensive or cheap?', [['sont', 'chers', 'marché'], ['vêtements', 'chers', 'marché']], 'Les vêtements sont bon marché.', [Kit::word('les vêtements'), Kit::word('bon marché'), Kit::form('bon marché', true)], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.reduction', 'Il y a une réduction sur le pantalon ?', 'Is there a discount on the pair of trousers?', [['oui', 'non', 'a', 'avons'], ['réduction', 'remise']], 'Oui, il y a une réduction.', [Kit::word('la réduction', 'réduction'), Kit::word('le pantalon')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.prix', 'Où est le prix de la chemise ?', 'Where is the price of the shirt?', [['est', 'voici', 'ici', 'là', "c'est"], ['prix', 'chemise', 'ici', 'là', 'voici']], 'Le prix est ici.', [Kit::word('le prix', 'prix')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.essaie', 'Vous essayez la chemise ou le pantalon ?', 'Are you trying on the shirt or the pair of trousers?', [["j'essaie", "j'essaye", 'essaie', 'essaye', 'chemise', 'pantalon'], ['chemise', 'pantalon']], "J'essaie la chemise.", [Kit::word('essayer', "j'essaie"), Kit::word('la chemise')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.couleur', 'La couleur et la taille sont ici.', 'The color and the size are here.', [Kit::word('la couleur'), Kit::word('la taille')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.essaie', "J'essaie le pantalon, mais il est cher.", 'I try on the pair of trousers, but it is expensive.', [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('le pantalon'), Kit::word('cher'), Kit::form('cher')], 'speak', ["J'essaye le pantalon, mais il est cher."]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.chemise', 'The shirt is very expensive here.', ['La chemise est très chère ici.', 'Ici, la chemise est très chère.', 'Ici la chemise est très chère.'], [Kit::word('la chemise'), Kit::word('cher', 'chère'), Kit::form('chère', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vetements', 'The clothes are cheap with the discount.', ['Les vêtements sont bon marché avec la réduction.', 'Avec la réduction, les vêtements sont bon marché.'], [Kit::word('les vêtements'), Kit::word('la réduction', 'réduction'), Kit::word('bon marché'), Kit::form('bon marché', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.essaie', 'I try on the pair of trousers here.', ["J'essaie le pantalon ici.", "Ici, j'essaie le pantalon.", "Ici j'essaie le pantalon.", "J'essaye le pantalon ici.", "Ici, j'essaye le pantalon.", "Ici j'essaye le pantalon."], [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('le pantalon')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.couleur', 'What are the size and the color of the pair of trousers?', ['Quelles sont la taille et la couleur du pantalon ?'], [Kit::word('la taille', 'taille'), Kit::word('la couleur', 'couleur'), Kit::word('le pantalon', 'pantalon')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.chemises', 'Les chemises sont très ___.', 'The shirts are very expensive.', 'chères', Kit::form('chères', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pantalon', 'Ici, le pantalon est ___.', 'Here, the pair of trousers is cheap.', 'bon marché', Kit::form('bon marché', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.vetements', 'Les vêtements sont chers ici.', 'The clothes are expensive here.', [Kit::word('les vêtements'), Kit::word('cher', 'chers'), Kit::form('chers')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.prix', 'Voici le prix et la taille de la chemise.', 'Here are the price and the size of the shirt.', [Kit::word('le prix', 'prix'), Kit::word('la taille', 'taille'), Kit::word('la chemise')], 'dictation', $set, homophoneNote: self::ET_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.chere', "J'essaie la chemise chère.", 'I try on the expensive shirt.', [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('cher', 'chère'), Kit::form('chère')], 'dictation', $set, alsoAccepted: ["J'essaye la chemise chère."]),
            Kit::listenPassage($stage, 'check.a.listen_passage.taille', [
                Kit::line('Marie', 'Bonjour. Le pantalon est cher ici ?'),
                Kit::line('Vendeuse', 'Oui, mais il y a une réduction.'),
                Kit::line('Marie', 'Très bien. Et le prix avec la réduction ?'),
                Kit::line('Vendeuse', 'Avec la réduction, le pantalon est bon marché.'),
            ], [
                Kit::question('Is there a discount?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What does Marie ask about at the end?', ['The price with the discount', 'The size', 'The color'], 'The price with the discount'),
                Kit::question('With the discount, is the pair of trousers cheap?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
            ], [
                Kit::question('Who speaks first?', ['Marie', 'The shop assistant', 'Nobody'], 'Marie'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Which greeting does Marie use?', ['Hello', 'Good evening', 'Goodbye'], 'Hello'),
            ], [Kit::word('le pantalon'), Kit::word('cher'), Kit::word('bon marché'), Kit::word('la réduction'), Kit::word('le prix')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.magasin', 'Read the conversation.', [
                Kit::line('Luc', 'Bonsoir. Il y a une réduction ici ?'),
                Kit::line('Vendeuse', 'Oui, mais la chemise est chère. Le pantalon est bon marché ici.'),
                Kit::line('Luc', "Merci, j'essaie le pantalon. Quelle est la couleur ?"),
                Kit::line('Vendeuse', 'Il est bleu. Le prix est ici.'),
            ], [
                Kit::question('Which item is cheap?', ['The shirt', 'The pair of trousers', 'Both'], 'The pair of trousers'),
                Kit::question('What does Luc try on?', ['The shirt', 'The pair of trousers', 'Nothing'], 'The pair of trousers'),
            ], [Kit::word('la réduction'), Kit::word('la chemise'), Kit::word('cher'), Kit::word('le pantalon'), Kit::word('bon marché'), Kit::word('essayer'), Kit::word('la couleur'), Kit::word('le prix')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pantalon', 'Où est le pantalon ?', 'Where is the pair of trousers?', [['est', 'voici', 'ici', 'là'], ['pantalon', 'ici', 'là', 'voici']], 'Le pantalon est ici.', [Kit::word('le pantalon')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.chemises', 'Les chemises sont chères ou bon marché ?', 'Are the shirts expensive or cheap?', [['sont', 'chères', 'marché'], ['chemises', 'chères', 'marché']], 'Les chemises sont bon marché.', [Kit::word('la chemise', 'chemises'), Kit::word('bon marché')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.essaie', 'Vous essayez le pantalon ?', 'Are you trying on the pair of trousers?', [['oui', 'non'], ["j'essaie", "j'essaye", 'essaie', 'essaye', 'pantalon', 'chemise']], "Oui, j'essaie le pantalon.", [Kit::word('essayer', "j'essaie"), Kit::word('le pantalon')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.prix', 'The price and the size of the shirt are here.', ['Le prix et la taille de la chemise sont ici.', 'Voici le prix et la taille de la chemise.'], [Kit::word('le prix'), Kit::word('la taille'), Kit::word('la chemise')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.essaie', 'I try on the shirt, but it is expensive.', ["J'essaie la chemise, mais elle est chère.", "J'essaye la chemise, mais elle est chère."], [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('la chemise'), Kit::word('cher', 'chère'), Kit::form('chère')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.reduction', 'With the discount, the pair of trousers is very cheap.', ['Avec la réduction, le pantalon est très bon marché.'], [Kit::word('la réduction', 'réduction'), Kit::word('le pantalon'), Kit::word('bon marché'), Kit::form('bon marché', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.taille', 'What are the size and the color of the clothes?', ['Quelles sont la taille et la couleur des vêtements ?'], [Kit::word('la taille', 'taille'), Kit::word('la couleur', 'couleur'), Kit::word('les vêtements', 'vêtements')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pantalon', 'Ici, le pantalon est très ___.', 'Here, the pair of trousers is very expensive.', 'cher', Kit::form('cher'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.couleur', 'Quelle est la ___ de la chemise ?', 'What is the color of the shirt?', 'couleur', Kit::word('la couleur', 'couleur'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.prix', 'Voici le prix des chemises chères.', 'Here is the price of the expensive shirts.', [Kit::word('le prix', 'prix'), Kit::word('la chemise', 'chemises'), Kit::word('cher', 'chères'), Kit::form('chères')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pantalon', "J'essaie le pantalon bon marché.", 'I try on the cheap pair of trousers.', [Kit::word('essayer', "j'essaie", ["j'essaye"]), Kit::word('le pantalon'), Kit::word('bon marché'), Kit::form('bon marché', true)], 'dictation', $set, alsoAccepted: ["J'essaye le pantalon bon marché."]),
            Kit::listenType($stage, 'check.b.listen_type.vetements', 'Les vêtements sont chers, mais il y a une réduction.', 'The clothes are expensive, but there is a discount.', [Kit::word('les vêtements'), Kit::word('cher', 'chers'), Kit::word('la réduction', 'réduction'), Kit::form('chers')], 'dictation', $set, homophoneNote: 'Mais (but) and mes (my) sound very close, and the sentence tells you which is which: here it means but. The a of il y a (there is) has no accent, unlike à (to, at).'),
        ];
    }
}
