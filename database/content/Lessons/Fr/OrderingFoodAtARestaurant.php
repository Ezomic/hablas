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

final class OrderingFoodAtARestaurant implements UnitContent
{
    public function languageCode(): string
    {
        return 'fr';
    }

    public function unitSlug(): string
    {
        return 'ordering-food-at-a-restaurant';
    }

    public function words(): array
    {
        return [
            new WordData('le restaurant', cue: 'restaurant'),
            new WordData('le menu', cue: 'the fixed-price meal; the full list of dishes is la carte, not le menu'),
            new WordData('l\'addition', cue: 'the bill (in a restaurant)'),
            new WordData('je voudrais', cue: 'I would like (polite request)', accepted: ['moi, je voudrais']),
            new WordData('à boire', cue: 'something to drink (as in "quelque chose à boire")', accepted: ['quelque chose à boire']),
            new WordData('à manger', cue: 'something to eat (as in "quelque chose à manger")', accepted: ['quelque chose à manger']),
            new WordData('le serveur', cue: 'waiter (a man)', accepted: ['la serveuse'], forms: ['serveurs', 'serveuses']),
            new WordData('délicieux', cue: 'delicious (masculine)', forms: ['délicieuse', 'délicieuses']),
            new WordData('le pourboire', cue: 'tip (money left for the waiter)'),
            new WordData('végétarien', cue: 'vegetarian (masculine)', forms: ['végétarienne', 'végétariens', 'végétariennes']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Le serveur parle avec Anne.', 'english' => 'The waiter speaks with Anne.'],
            ['text' => 'Nous mangeons au restaurant.', 'english' => 'We eat at the restaurant.'],
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
        $people = ['je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles', 'on', 'serveur', 'serveuse', 'anne', 'paul', 'marie', 'luc'];

        return [
            Kit::gap($stage, 'sentences.choose_gap.parle-serveur', 'Je ___ avec le serveur.', ['parle', 'parles', 'parlons'], 'parle', Kit::form('parle', true), 'The subject decides the ending. Je takes -e: je parle. The other endings belong to tu and nous.', 'choose', 'I speak with the waiter.', ['parle' => 'I speak', 'parles' => 'you speak (tu)', 'parlons' => 'we speak']),
            Kit::gap($stage, 'sentences.choose_gap.le-serveur', 'Le ___ travaille ici.', ['serveur', 'pourboire', 'menu'], 'serveur', Kit::word('le serveur', 'serveur'), 'The waiter is the person who works in the restaurant. A tip or a menu cannot work.', 'choose', 'The waiter works here.'),
            Kit::gap($stage, 'sentences.choose_gap.nous-mangeons', 'Nous ___ avec Anne.', ['mangeons', 'mangez', 'mangent'], 'mangeons', Kit::form('mangeons', true), 'Nous takes -ons. With manger the e stays before -ons (mangeons), so the g keeps its soft sound.', 'choose', 'We eat with Anne.', ['mangeons' => 'we eat', 'mangez' => 'you eat (vous)', 'mangent' => 'they eat']),
            Kit::gap($stage, 'sentences.choose_gap.restaurant-vegetarien', 'Le restaurant est ___.', ['végétarien', 'végétarienne'], 'végétarien', Kit::word('végétarien'), 'Restaurant is masculine, so the adjective has no extra -e.', 'choose', 'The restaurant is vegetarian.'),
            Kit::gap($stage, 'sentences.choose_gap.je-voudrais', '___ un menu végétarien, s\'il vous plaît.', ['Je voudrais', 'Je parle', 'Je travaille'], 'Je voudrais', Kit::word('je voudrais'), 'Je voudrais is the polite way to ask for something. Je parle and je travaille do not ask for anything.', 'choose', 'I would like a vegetarian menu, please.'),
            Kit::gap($stage, 'sentences.choose_gap.delicieux', 'Le menu est ___, merci.', ['délicieux', 'délicieuse'], 'délicieux', Kit::word('délicieux'), 'Menu is masculine, so the adjective has no extra -e.', 'choose', 'The menu is delicious, thank you.'),

            Kit::typeGap($stage, 'sentences.type_gap.commande', 'Paul ___ un menu végétarien.', 'Paul orders a vegetarian menu.', 'commande', Kit::form('commande'), 'Paul is one person (il), so the verb ends in -e.', glosses: ['commande' => 'orders']),
            Kit::typeGap($stage, 'sentences.type_gap.laissons', 'Nous ___ le pourboire.', 'We leave the tip.', 'laissons', Kit::form('laissons'), 'Nous takes -ons.', glosses: ['laissons' => 'we leave']),
            Kit::typeGap($stage, 'sentences.type_gap.mangent', 'Ils ___ ici.', 'They eat here.', 'mangent', Kit::form('mangent', true), 'Ils takes -ent. The ending is silent, so mangent sounds like mange: only the subject tells you it is plural.', glosses: ['mangent' => 'they eat']),
            Kit::typeGap($stage, 'sentences.type_gap.mangez', 'Vous ___ ici ?', 'Are you eating here?', 'mangez', Kit::form('mangez'), 'Vous takes -ez.', glosses: ['mangez' => 'you eat']),
            Kit::typeGap($stage, 'sentences.type_gap.pourboire', 'Voici le ___, merci.', 'Here is the tip, thank you.', 'pourboire', Kit::word('le pourboire', 'pourboire')),

            Kit::translate($stage, 'sentences.translate.mangeons', 'We eat in a restaurant.', ['Nous mangeons dans un restaurant.', 'Nous mangeons au restaurant.'], [Kit::word('le restaurant', 'restaurant'), Kit::form('mangeons')]),
            Kit::translate($stage, 'sentences.translate.addition', 'I would like the bill, please.', ['Je voudrais l\'addition, s\'il vous plaît.', 'S\'il vous plaît, je voudrais l\'addition.'], [Kit::word('je voudrais'), Kit::word('l\'addition')]),
            Kit::translate($stage, 'sentences.translate.menu', 'The set menu is delicious.', ['Le menu est délicieux.'], [Kit::word('le menu', 'menu'), Kit::word('délicieux')]),

            Kit::build($stage, 'sentences.build.parle', 'Paul speaks with the waiter.', 'Paul parle avec le serveur.', ['parlent'], [Kit::word('le serveur', 'serveur'), Kit::form('parle')]),
            Kit::build($stage, 'sentences.build.travaillent', 'The waiters work here.', 'Les serveurs travaillent ici.', ['travaille'], [Kit::word('le serveur', 'serveurs'), Kit::form('travaillent', true)]),
            Kit::build($stage, 'sentences.build.menu-vegetarien', 'I would like a vegetarian menu.', 'Je voudrais un menu végétarien.', ['végétarienne'], [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.serveur-parle', 'Le serveur parle avec Anne.', ['The waiter speaks with Anne.', 'The waiter works with Anne.', 'The waiter eats with Anne.', 'The waiter speaks with Paul.'], 'The waiter speaks with Anne.', [Kit::word('le serveur', 'serveur'), Kit::form('parle')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.laissons', 'Nous laissons le pourboire.', ['We leave the tip.', 'I leave the tip.', 'They leave the tip.', 'We leave the bill.'], 'We leave the tip.', [Kit::word('le pourboire', 'pourboire'), Kit::form('laissons')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.boire', 'Je voudrais quelque chose à boire.', ['I would like something to eat.', 'I would like something to drink.', 'I would like the menu.', 'I would like the bill.'], 'I would like something to drink.', [Kit::word('je voudrais'), Kit::word('à boire')]),
            Kit::listenType($stage, 'sentences.listen_type.pourboire', 'Le pourboire est pour le serveur.', 'The tip is for the waiter.', [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur')], homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.'),
            Kit::listenType($stage, 'sentences.listen_type.manger', 'Je voudrais quelque chose à manger.', 'I would like something to eat.', [Kit::word('je voudrais'), Kit::word('à manger')], homophoneNote: 'À (to, for) has an accent and a (has) does not, but they sound the same. The sentence tells you which one is meant.'),
            Kit::listenType($stage, 'sentences.listen_type.menu', 'Le menu végétarien est délicieux.', 'The vegetarian menu is delicious.', [Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('délicieux')], homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.'),
            Kit::listenType($stage, 'sentences.listen_type.travaille', 'Je travaille dans un restaurant.', 'I work in a restaurant.', [Kit::word('le restaurant', 'restaurant'), Kit::form('travaille')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.addition', 'Je voudrais l\'addition, s\'il vous plaît.', 'I would like the bill, please.', [Kit::word('je voudrais'), Kit::word('l\'addition')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.laisse', 'Anne laisse le pourboire.', 'Anne leaves the tip.', [Kit::word('le pourboire', 'pourboire'), Kit::form('laisse')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.travaille', 'Anne travaille au restaurant.', 'Anne works at the restaurant.', [Kit::word('le restaurant', 'restaurant'), Kit::form('travaille')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.boire-manger', 'Quelque chose à boire et à manger.', 'Something to drink and to eat.', [Kit::word('à boire'), Kit::word('à manger')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.mangez', 'Vous mangez ici ?', 'Are you eating here?', [['oui', 'non'], ['mange', 'mangeons', 'mangez', 'ici']], 'Oui, je mange ici.', [Kit::form('mange', true)]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.commande', 'Qui commande le menu ?', 'Who orders the set menu?', [[...$people, 'moi']], 'Paul commande le menu.', [Kit::word('le menu', 'menu'), Kit::form('commande')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.menu-vegetarien', 'Il y a un menu végétarien ?', 'Is there a vegetarian set menu?', [['oui', 'non', 'il'], ['menu', 'végétarien', 'végétarienne']], 'Oui, il y a un menu végétarien.', [Kit::word('le menu', 'menu'), Kit::word('végétarien')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;
        $people = ['je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles', 'on', 'serveur', 'serveuse', 'anne', 'paul', 'marie', 'luc'];

        return [
            Kit::readPassage($stage, 'task.read_passage.menu', 'Read the conversation.', [
                Kit::line('Serveur', 'Bonjour, Marie. Vous mangez ici ?'),
                Kit::line('Marie', 'Oui. Je voudrais un menu végétarien, s\'il vous plaît.'),
                Kit::line('Serveur', 'Très bien. Et quelque chose à boire ?'),
                Kit::line('Marie', 'Non, merci.'),
            ], [
                Kit::question('What does Marie order?', ['A vegetarian set menu', 'Something to drink', 'The bill'], 'A vegetarian set menu'),
                Kit::question('Does Marie want something to drink?', ['Yes', 'No', 'The text does not say.'], 'No'),
                Kit::question('Who asks about something to drink?', ['The waiter', 'Marie', 'Nobody'], 'The waiter'),
            ], [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('à boire')], 'read'),
            Kit::gap($stage, 'task.choose_gap.pourboire', 'Je laisse le ___ pour le serveur.', ['pourboire', 'délicieux', 'végétarien'], 'pourboire', Kit::word('le pourboire', 'pourboire'), 'A tip is the money you leave for the waiter. The other two are adjectives and cannot follow le here.', 'read', 'I leave the tip for the waiter.'),
            Kit::gap($stage, 'task.choose_gap.addition', '___, s\'il vous plaît.', ['L\'addition', 'Délicieux', 'Végétarien'], 'L\'addition', Kit::word('l\'addition'), 'To ask for the bill, you say l\'addition, s\'il vous plaît.', 'read', 'The bill, please.'),

            Kit::transform($stage, 'task.transform.mangeons', 'Change the subject to nous.', 'Je mange au restaurant.', ['Nous mangeons au restaurant.'], [Kit::word('le restaurant', 'restaurant'), Kit::form('mangeons')]),
            Kit::transform($stage, 'task.transform.parlent', 'Make it plural: the waiters.', 'Le serveur parle avec Marie.', ['Les serveurs parlent avec Marie.'], [Kit::word('le serveur', 'serveurs'), Kit::form('parlent', true)]),
            Kit::transform($stage, 'task.transform.commandez', 'Change the subject to vous.', 'Je commande un menu végétarien.', ['Vous commandez un menu végétarien.'], [Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::form('commandez')]),
            Kit::writeGuided($stage, 'task.write_guided.voudrais', 'Say that you would like a vegetarian set menu and something to drink.', ['je voudrais', 'menu', 'végétarien', 'à boire'], 'Je voudrais un menu végétarien et quelque chose à boire.', [
                ['forms' => ['voudrais'], 'term' => 'je voudrais'],
                ['forms' => ['menu'], 'term' => 'le menu'],
                ['forms' => ['végétarien'], 'term' => 'végétarien'],
                ['forms' => ['boire'], 'term' => 'à boire'],
            ], [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('à boire')]),
            Kit::writeGuided($stage, 'task.write_guided.addition', 'Say that you would like the bill and that you leave the tip.', ['l\'addition', 'pourboire', 'laisse'], 'Je voudrais l\'addition. Je laisse le pourboire.', [
                ['forms' => ['l\'addition'], 'term' => 'l\'addition'],
                ['forms' => ['pourboire'], 'term' => 'le pourboire'],
                ['forms' => ['laisse'], 'term' => null],
            ], [Kit::word('l\'addition'), Kit::word('le pourboire', 'pourboire'), Kit::form('laisse')]),
            Kit::build($stage, 'task.build.commandons', 'We order the set menu at the restaurant.', 'Nous commandons le menu au restaurant.', ['commandez', 'commande'], [Kit::word('le restaurant', 'restaurant'), Kit::word('le menu', 'menu'), Kit::form('commandons')], 'write'),
            Kit::build($stage, 'task.build.manger', 'I would like something to eat, please.', 'Je voudrais quelque chose à manger, s\'il vous plaît.', ['mange', 'travaille'], [Kit::word('je voudrais'), Kit::word('à manger')], 'write'),
            Kit::build($stage, 'task.build.mangent', 'Anne and Paul eat a delicious set menu.', 'Anne et Paul mangent un menu délicieux.', ['mange', 'mangeons'], [Kit::word('le menu', 'menu'), Kit::word('délicieux'), Kit::form('mangent', true)], 'write'),
            Kit::translate($stage, 'task.translate.travaille', 'Luc works in a vegetarian restaurant.', ['Luc travaille dans un restaurant végétarien.'], [Kit::word('le restaurant', 'restaurant'), Kit::word('végétarien'), Kit::form('travaille')], 'write'),
            Kit::translate($stage, 'task.translate.laissons', 'We leave the tip for the waiter.', ['Nous laissons le pourboire pour le serveur.'], [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur'), Kit::form('laissons')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.addition', [
                Kit::line('Luc', 'Bonsoir. L\'addition, s\'il vous plaît.'),
                Kit::line('Serveur', 'Voici l\'addition, Luc.'),
                Kit::line('Luc', 'Merci. Le menu est délicieux.'),
                Kit::line('Serveur', 'Merci beaucoup.'),
                Kit::line('Luc', 'Et voici le pourboire pour vous.'),
                Kit::line('Serveur', 'Merci, Luc. Au revoir.'),
            ], [
                Kit::question('What does Luc ask for?', ['The bill', 'The menu', 'Something to drink'], 'The bill'),
                Kit::question('What does Luc say about the menu?', ['It is delicious.', 'It is not good.', 'It is expensive.'], 'It is delicious.'),
                Kit::question('Who is the tip for?', ['The waiter', 'Luc', 'Nobody'], 'The waiter'),
            ], [
                Kit::question('Does Luc say good evening?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Who says goodbye?', ['Luc', 'The waiter', 'Nobody'], 'The waiter'),
            ], [Kit::word('l\'addition'), Kit::word('le menu', 'menu'), Kit::word('délicieux'), Kit::word('le pourboire', 'pourboire')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.commandons', 'Nous commandons à manger.', 'We are ordering something to eat.', [Kit::word('à manger'), Kit::form('commandons')], 'listen', homophoneNote: 'À (to, for) has an accent and a (has) does not, but they sound the same. The sentence tells you which one is meant.'),
            Kit::listenType($stage, 'task.listen_type.restaurant', 'Le menu du restaurant végétarien est délicieux.', 'The menu of the vegetarian restaurant is delicious.', [Kit::word('le menu', 'menu'), Kit::word('le restaurant', 'restaurant'), Kit::word('végétarien'), Kit::word('délicieux')], 'listen', homophoneNote: 'Est (is, the verb) and et (and) sound very close, and the sentence tells you which is which: here est is the verb.'),
            Kit::listenType($stage, 'task.listen_type.parle', 'Marie parle avec le serveur.', 'Marie speaks with the waiter.', [Kit::word('le serveur', 'serveur'), Kit::form('parle')], 'listen'),

            Kit::speakAnswer($stage, 'task.speak_answer.boire', 'Vous commandez quelque chose à boire ?', 'Are you ordering something to drink?', [['oui', 'non', 'je', 'voudrais'], ['voudrais', 'commande', 'boire', 'merci', 'plaît', 'quelque']], 'Oui, je voudrais quelque chose à boire.', [Kit::word('je voudrais'), Kit::word('à boire')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.travaille', 'Qui travaille dans le restaurant ?', 'Who works in the restaurant?', [[...$people, 'moi']], 'Le serveur travaille dans le restaurant.', [Kit::word('le restaurant', 'restaurant'), Kit::word('le serveur', 'serveur'), Kit::form('travaille')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.delicieux', 'Le menu végétarien est délicieux ?', 'Is the vegetarian set menu delicious?', [['oui', 'non', 'est'], ['délicieux', 'délicieuse', 'menu']], 'Oui, le menu végétarien est délicieux.', [Kit::word('le menu', 'menu'), Kit::word('délicieux')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.pourboire', 'Le pourboire est pour le serveur ?', 'Is the tip for the waiter?', [['oui', 'non', 'est'], ['pourboire', 'serveur', 'pour']], 'Oui, le pourboire est pour le serveur.', [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.menu', 'Bonjour, je voudrais un menu végétarien.', 'Hello, I would like a vegetarian menu.', [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.pourboire', 'L\'addition, s\'il vous plaît. Le pourboire est pour vous.', 'The bill, please. The tip is for you.', [Kit::word('l\'addition'), Kit::word('le pourboire', 'pourboire')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';
        $people = ['je', 'tu', 'il', 'elle', 'nous', 'vous', 'ils', 'elles', 'on', 'serveur', 'serveuse', 'anne', 'paul', 'marie', 'luc'];

        return [
            Kit::translate($stage, 'check.a.translate.serveur', 'The waiter of the restaurant eats a vegetarian set menu.', ['Le serveur du restaurant mange un menu végétarien.'], [Kit::word('le serveur', 'serveur'), Kit::word('le restaurant', 'restaurant'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::form('mange')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.menu', 'I would like a delicious vegetarian set menu.', ['Je voudrais un menu végétarien délicieux.', 'Je voudrais un délicieux menu végétarien.'], [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('délicieux')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.commandent', 'They order something to drink and to eat.', ['Ils commandent à boire et à manger.', 'Ils commandent quelque chose à boire et à manger.'], [Kit::word('à boire'), Kit::word('à manger'), Kit::form('commandent', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.addition', 'Here are the bill and the tip.', ['Voici l\'addition et le pourboire.'], [Kit::word('l\'addition'), Kit::word('le pourboire', 'pourboire')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.commande', 'Luc ___ le menu.', 'Luc orders the set menu.', 'commande', Kit::form('commande'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.parlent', 'Anne et Luc ___ avec le serveur.', 'Anne and Luc speak with the waiter.', 'parlent', Kit::form('parlent', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.laissons', 'Nous laissons un pourboire pour le serveur du restaurant.', 'We leave a tip for the waiter of the restaurant.', [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur'), Kit::word('le restaurant', 'restaurant'), Kit::form('laissons')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.delicieux', 'Je voudrais quelque chose de délicieux à manger.', 'I would like something delicious to eat.', [Kit::word('je voudrais'), Kit::word('délicieux'), Kit::word('à manger')], 'dictation', $set, homophoneNote: 'À (to, for) has an accent and a (has) does not, but they sound the same. The sentence tells you which one is meant.'),
            Kit::listenType($stage, 'check.a.listen_type.demandons', 'Voici l\'addition. Nous commandons quelque chose à boire.', 'Here is the bill. We order something to drink.', [Kit::word('l\'addition'), Kit::word('à boire'), Kit::form('commandons')], 'dictation', $set, homophoneNote: 'À (to, for) has an accent and a (has) does not, but they sound the same. The sentence tells you which one is meant.'),
            Kit::listenPassage($stage, 'check.a.listen_passage.commande', [
                Kit::line('Serveur', 'Bonsoir. Vous commandez ?'),
                Kit::line('Marie', 'Je voudrais quelque chose à boire, s\'il vous plaît.'),
                Kit::line('Serveur', 'Et quelque chose à manger ?'),
                Kit::line('Marie', 'Oui, un menu végétarien, s\'il vous plaît.'),
                Kit::line('Serveur', 'Très bien. Il est délicieux.'),
                Kit::line('Marie', 'Merci.'),
            ], [
                Kit::question('Does Marie want something to drink?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('What kind of menu does Marie order?', ['A vegetarian one', 'A big one', 'A cheap one'], 'A vegetarian one'),
                Kit::question('What does the waiter say about the menu?', ['It is delicious.', 'It is expensive.', 'It is not ready.'], 'It is delicious.'),
            ], [
                Kit::question('Who asks about something to eat?', ['The waiter', 'Marie', 'Nobody'], 'The waiter'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
                Kit::question('Who says thank you at the end?', ['Marie', 'The waiter', 'Nobody'], 'Marie'),
            ], [Kit::word('je voudrais'), Kit::word('à boire'), Kit::word('à manger'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('délicieux')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.manger', 'Read the conversation.', [
                Kit::line('Paul', 'Bonsoir. Il y a quelque chose à manger ?'),
                Kit::line('Serveur', 'Oui, c\'est un restaurant végétarien. Et le menu est délicieux.'),
                Kit::line('Paul', 'Très bien. Je voudrais un menu, merci.'),
            ], [
                Kit::question('What does Paul ask about?', ['Something to eat', 'Something to drink', 'The bill'], 'Something to eat'),
                Kit::question('What kind of restaurant is it?', ['A vegetarian one', 'A big one', 'A cheap one'], 'A vegetarian one'),
            ], [Kit::word('à manger'), Kit::word('le restaurant', 'restaurant'), Kit::word('végétarien'), Kit::word('délicieux'), Kit::word('le menu', 'menu'), Kit::word('je voudrais')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.boire', 'Il y a quelque chose à boire ici ?', 'Is there something to drink here?', [['oui', 'non', 'il'], ['boire', 'quelque', 'chose', 'ici']], 'Oui, il y a quelque chose à boire ici.', [Kit::word('à boire')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vegetarien', 'Vous êtes végétarien ?', 'Are you vegetarian?', [['oui', 'non', 'suis'], ['végétarien', 'végétarienne', 'suis']], 'Oui, je suis végétarien.', [Kit::word('végétarien')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pourboire', 'Qui laisse le pourboire ?', 'Who leaves the tip?', [[...$people, 'moi']], 'Anne laisse le pourboire.', [Kit::word('le pourboire', 'pourboire')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.menu', 'Anne orders a vegetarian set menu and I would like the bill.', ['Anne commande un menu végétarien et je voudrais l\'addition.'], [Kit::word('je voudrais'), Kit::word('le menu', 'menu'), Kit::word('végétarien'), Kit::word('l\'addition'), Kit::form('commande')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.commandons', 'We order something to drink and to eat.', ['Nous commandons à boire et à manger.', 'Nous commandons quelque chose à boire et à manger.'], [Kit::word('à boire'), Kit::word('à manger'), Kit::form('commandons', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.laisse', 'I leave a tip for the waiter.', ['Je laisse un pourboire pour le serveur.'], [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur'), Kit::form('laisse')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.restaurant', 'The vegetarian restaurant has a delicious set menu.', ['Le restaurant végétarien a un menu délicieux.', 'Le restaurant végétarien a un délicieux menu.'], [Kit::word('le restaurant', 'restaurant'), Kit::word('végétarien'), Kit::word('le menu', 'menu'), Kit::word('délicieux')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.parle', 'Marie ___ avec Luc.', 'Marie speaks with Luc.', 'parle', Kit::form('parle'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.travaillent', 'Marie et Anne ___ au restaurant.', 'Marie and Anne work at the restaurant.', 'travaillent', Kit::form('travaillent', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.laissez', 'Vous laissez le pourboire pour le serveur ?', 'Are you leaving the tip for the waiter?', [Kit::word('le pourboire', 'pourboire'), Kit::word('le serveur', 'serveur'), Kit::form('laissez')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.addition', 'Je voudrais l\'addition et quelque chose à boire.', 'I would like the bill and something to drink.', [Kit::word('je voudrais'), Kit::word('l\'addition'), Kit::word('à boire')], 'dictation', $set, homophoneNote: 'À (to, for) has an accent and a (has) does not, but they sound the same. The sentence tells you which one is meant.'),
            Kit::listenType($stage, 'check.b.listen_type.restaurant', 'Il y a quelque chose à manger au restaurant. C\'est délicieux.', 'There is something to eat at the restaurant. It is delicious.', [Kit::word('le restaurant', 'restaurant'), Kit::word('à manger'), Kit::word('délicieux')], 'dictation', $set, homophoneNote: 'A (has) has no accent and à (to, for) has one, but they sound the same. Here a is part of il y a, and à comes before manger and au.'),
        ];
    }
}
