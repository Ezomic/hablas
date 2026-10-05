<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form French (France) placement test items, structurally identical to
 * PlacementTestSeeder (Spanish): not adaptive, but tagged with an approximate
 * CEFR sub-level difficulty for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class FrenchPlacementTestSeeder extends Seeder
{
    public function run(): void
    {
        $french = Language::query()->where('code', 'fr')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $french->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
                [
                    'options' => $item['options'],
                    'correct_answer' => $item['correct_answer'],
                    'cefr_sublevel_tag' => $item['cefr_sublevel_tag'],
                    'sort_order' => $sortOrder + 1,
                ],
            );
        }
    }

    /**
     * @return array<int, array{skill: Skill, prompt: string, options: array<int, string>, correct_answer: string, cefr_sublevel_tag: string}>
     */
    private function items(): array
    {
        return [
            // Reading
            [
                'skill' => Skill::Reading,
                'prompt' => "Que signifie « l'aéroport » ?",
                'options' => ['Street', 'Airport', 'Hotel', 'Restaurant'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => 'Lis : « Mon frère est grand et ma sœur est petite. » Qui est petite ?',
                'options' => ['Mon père', 'Ma mère', 'Ma sœur', 'Mon frère'],
                'correct_answer' => 'Ma sœur',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Que signifie « l'addition, s'il vous plaît » ?",
                'options' => ['The menu, please', 'The key, please', 'The room, please', 'The check, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis l'affiche : « Chambres libres. Petit-déjeuner compris. » Que dit l'affiche ?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Dans ma chambre, il y a un lit, une table et deux chaises. Il n'y a pas de télévision. » Qu'est-ce qu'il n'y a pas dans la chambre ?",
                'options' => ['Des chaises', 'Une télévision', 'Un lit', 'Une table'],
                'correct_answer' => 'Une télévision',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « La pharmacie du centre est ouverte du lundi au samedi, de neuf heures à vingt heures. Elle n'ouvre pas le dimanche. » Quand peut-on y acheter des médicaments ?",
                'options' => ['Lundi à huit heures', 'Samedi à vingt et une heures', 'Samedi à dix-neuf heures', 'Dimanche à dix heures'],
                'correct_answer' => 'Samedi à dix-neuf heures',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Que signifie l'affiche « Interdit de fumer » ?",
                'options' => ['No parking', 'No entry', 'No photos', 'No smoking'],
                'correct_answer' => 'No smoking',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Je dois acheter du lait, du pain et des œufs au supermarché. » Qu'est-ce qui n'est pas sur sa liste ?",
                'options' => ['Du fromage', 'Du lait', 'Du pain', 'Des œufs'],
                'correct_answer' => 'Du fromage',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Samedi dernier, nous sommes allés au cinéma, puis nous avons dîné dans un restaurant italien. » Qu'ont-ils fait après le film ?",
                'options' => ['Ils ont cuisiné à la maison', 'Ils ont mangé au restaurant', 'Ils sont retournés au cinéma', 'Ils ont acheté des places'],
                'correct_answer' => 'Ils ont mangé au restaurant',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => 'Lis : « Demain, je pars pour Lyon en train. Le billet coûte moitié moins cher que le vol. » Pourquoi cette personne prend-elle le train ?',
                'options' => ['Pour voyager avec des amis', 'Pour éviter la fatigue', 'Pour dépenser moins', 'Pour arriver plus vite'],
                'correct_answer' => 'Pour dépenser moins',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Plus tard, je veux enseigner : voir un élève comprendre enfin une leçon, c'est ma plus grande joie. » Pourquoi cette personne veut-elle enseigner ?",
                'options' => ['Elle veut un bon salaire', 'Elle aime avoir de longues vacances', 'Elle aime préparer des leçons', 'Elle aime voir les élèves progresser'],
                'correct_answer' => 'Elle aime voir les élèves progresser',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Même s'il pleuvait à verse, nous sommes sortis marcher : après une semaine enfermés, nous avions besoin de prendre l'air. » Pourquoi sont-ils sortis malgré la pluie ?",
                'options' => ["Ils étaient restés trop longtemps à l'intérieur", "La pluie s'était arrêtée", "Ils n'avaient pas de parapluie", 'Ils attendaient un ami dehors'],
                'correct_answer' => "Ils étaient restés trop longtemps à l'intérieur",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Beaucoup de jeunes s'installent en ville : les loyers y sont élevés, mais les offres d'emploi y sont bien plus nombreuses. » Qu'est-ce qui attire les jeunes en ville ?",
                'options' => ['Le calme de la vie', 'Les possibilités de trouver un emploi', 'Les loyers peu élevés', 'Les prix moins chers'],
                'correct_answer' => 'Les possibilités de trouver un emploi',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Léa rêvait de partir à Lisbonne, mais elle a renoncé : son entreprise lui a proposé une promotion qu'elle ne pouvait pas refuser. » Qu'est-ce qui l'a fait renoncer ?",
                'options' => ['Un refus de son entreprise', 'Le climat de Lisbonne', 'Une opportunité professionnelle chez elle', "Le prix du billet d'avion"],
                'correct_answer' => 'Une opportunité professionnelle chez elle',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Enfant, j'adorais les étés chez mes grands-parents : mon grand-père m'emmenait pêcher et ma grand-mère me montrait ses recettes. » Qu'est-ce qu'on comprend de cette période ?",
                'options' => ['Elle y a perdu le goût de la pêche', "Elle s'y est ennuyée avec ses grands-parents", 'Elle y a rencontré son futur mari', 'Elle y a appris des savoir-faire familiaux'],
                'correct_answer' => 'Elle y a appris des savoir-faire familiaux',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Il vaut mieux s'entraîner dix minutes chaque jour que deux heures le dimanche : on oublie moins. » Que recommande-t-on ?",
                'options' => ['De courtes séances régulières', 'Une longue séance le dimanche', 'Dix minutes de temps en temps', 'Deux heures chaque jour'],
                'correct_answer' => 'De courtes séances régulières',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Alors que le secteur traversait une crise, l'entreprise a vu ses ventes progresser après avoir revu sa communication. » Comment expliquer la hausse des ventes ?",
                'options' => ['Par la fermeture de magasins', 'Par un changement de communication', 'Par la reprise du secteur', 'Par une baisse des prix'],
                'correct_answer' => 'Par un changement de communication',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Face à un réchauffement dont l'ampleur n'a pas de précédent, aucun pays ne peut agir seul. » Que suggère le texte ?",
                'options' => ['Le réchauffement ralentit peu à peu', 'Seuls les pays riches sont concernés', 'Les États doivent agir ensemble', 'Chaque pays doit agir à sa façon'],
                'correct_answer' => 'Les États doivent agir ensemble',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Le rapport, dont les conclusions ont surpris plus d'un expert, laisse entendre que la réforme aurait produit l'effet inverse de celui qui était escompté. » Que laisse entendre le rapport ?",
                'options' => ['La réforme a atteint son objectif sans surprise', "La réforme n'a presque rien changé", 'La réforme a été mal appliquée selon les experts', "La réforme a produit l'effet contraire à l'effet visé"],
                'correct_answer' => "La réforme a produit l'effet contraire à l'effet visé",
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lis : « Nul ne conteste les atouts cognitifs de l'enseignement bilingue, mais sa généralisation supposerait des investissements que peu de budgets permettent. » Quel obstacle est évoqué ?",
                'options' => ['Le coût de sa mise en place', 'Le manque de preuves de son utilité', 'Le refus des familles', 'La difficulté pour les élèves'],
                'correct_answer' => 'Le coût de sa mise en place',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Bonjour, comment allez-vous ? » Que demande-t-on ?',
                'options' => ['What is your name', 'Where are you from', 'How old are you', 'How are you (formal)'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Votre vol part de la porte numéro douze. » Quel numéro est mentionné ?',
                'options' => ['Twelve', 'Two', 'Twenty', 'Twenty-two'],
                'correct_answer' => 'Twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Je voudrais une chambre pour deux nuits. » Que demande-t-on ?',
                'options' => ['A ticket for two nights', 'A room for two nights', 'A room for two people', 'A table for two people'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Tournez à gauche au coin de la rue. » Quelle direction donne-t-on ?',
                'options' => ['Go straight on past the corner', 'Stop at the corner', 'Turn left at the corner', 'Turn right at the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Le samedi, je fais mes courses au marché, puis je prépare le déjeuner pour toute la famille. » Que fait cette personne le samedi ?',
                'options' => ['Cooks lunch, then shops at the market', 'Eats at the market with friends', 'Works at the market all day', 'Shops at the market, then cooks lunch'],
                'correct_answer' => 'Shops at the market, then cooks lunch',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Le bus pour le centre s'arrête juste en face de la poste. » Où s'arrête le bus ?",
                'options' => ['Opposite the post office', 'Inside the post office', 'Behind the post office', 'Next to the station'],
                'correct_answer' => 'Opposite the post office',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Je voudrais échanger cette chemise : elle est trop petite et je n'ai pas gardé le ticket. » Pourquoi veut-elle l'échanger ?",
                'options' => ['The shirt has a hole', 'The shirt is too small', 'The shirt is too expensive', 'The shirt is the wrong colour'],
                'correct_answer' => 'The shirt is too small',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Le médecin n'est pas là aujourd'hui. Vous pouvez revenir demain matin ou prendre rendez-vous sur Internet. » Que peut-on faire ?",
                'options' => ['Revenir ce soir sans rendez-vous', "Téléphoner à l'hôpital", 'Revenir demain ou réserver en ligne', "Attendre le médecin aujourd'hui"],
                'correct_answer' => 'Revenir demain ou réserver en ligne',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Dimanche, j'ai pris le train pour aller voir mes parents. On a déjeuné tous ensemble, puis je suis rentrée le soir. » Pourquoi a-t-elle pris le train dimanche ?",
                'options' => ['Pour partir en vacances', 'Pour aller travailler', 'Pour rendre visite à un ami', 'Pour passer la journée en famille'],
                'correct_answer' => 'Pour passer la journée en famille',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Je m'inscris à des cours d'anglais : mon entreprise veut que je parle avec nos clients américains. » Pourquoi cette personne étudie-t-elle l'anglais ?",
                'options' => ['Pour mieux communiquer avec des clients', 'Pour préparer un voyage aux États-Unis', 'Pour passer un examen', 'Pour suivre un ami'],
                'correct_answer' => 'Pour mieux communiquer avec des clients',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Je prends mon café sans sucre, mais avec juste un peu de lait. » Comment cette personne prend-elle son café ?',
                'options' => ['Avec du sucre et du lait', 'Sans sucre, avec un peu de lait', 'Avec du sucre, sans lait', 'Sans sucre et sans lait'],
                'correct_answer' => 'Sans sucre, avec un peu de lait',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Comme le vol a été retardé de deux heures, nous avons raté le début de la conférence. » Quelle a été la conséquence du retard ?',
                'options' => ["Ils n'ont pas pu y assister du tout", 'La conférence a été retardée aussi', 'Ils sont arrivés quand la conférence avait déjà commencé', 'Ils sont arrivés au moment où elle commençait'],
                'correct_answer' => 'Ils sont arrivés quand la conférence avait déjà commencé',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Si le temps le permet, nous ferons une promenade samedi matin ; sinon, nous irons au musée. » Que feront-ils s'il fait mauvais ?",
                'options' => ['Ils resteront chez eux', 'Ils partiront dimanche', 'Ils feront la promenade quand même', 'Ils iront au musée'],
                'correct_answer' => 'Ils iront au musée',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Mon travail ne me plaît plus vraiment, mais tant que je n'ai pas de meilleure offre, je reste. » Pourquoi cette personne reste-t-elle dans son travail ?",
                'options' => ["Elle n'a pas encore de meilleure proposition", 'Elle est très bien payée', "Elle vient d'être promue", 'Elle craint de perdre ses collègues'],
                'correct_answer' => "Elle n'a pas encore de meilleure proposition",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Au village, on se connaissait tous, alors on se rendait service sans compter. Depuis que nous vivons en ville, je ne connais même pas mes voisins. » Que regrette cette personne ?',
                'options' => ["Le temps libre qu'elle avait", "La solidarité d'avant entre voisins", 'Le calme de la campagne', 'Le prix des loyers au village'],
                'correct_answer' => "La solidarité d'avant entre voisins",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => 'Tu entends : « Il se peut que nous arrivions en retard à cause de la circulation, alors commencez sans nous. » Que demande-t-on ?',
                'options' => ["D'annuler la réunion", 'De reporter la réunion', 'De commencer sans eux', 'De les attendre dehors'],
                'correct_answer' => 'De commencer sans eux',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « J'ai beaucoup hésité : ce poste à l'étranger est une belle chance, et pourtant ma famille va me manquer. Finalement, j'ai dit oui. » Qu'a décidé cette personne ?",
                'options' => ['De refuser pour rester en famille', 'De retarder sa réponse', 'De négocier un poste plus proche', "D'accepter l'offre malgré la séparation"],
                'correct_answer' => "D'accepter l'offre malgré la séparation",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Les critiques initiales ont été vives, mais, une fois le projet mis en œuvre, même ses détracteurs ont reconnu qu'il avait dépassé les attentes. » Comment le projet a-t-il été jugé au final ?",
                'options' => ['Il a été reconnu comme une réussite, y compris par ses opposants', 'Les critiques ont persisté malgré tout', "Ses partisans l'ont jugé décevant", 'Il a reçu un accueil mitigé'],
                'correct_answer' => 'Il a été reconnu comme une réussite, y compris par ses opposants',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Si nous continuons à vendre comme il y a dix ans, nous serons dépassés d'ici deux ans par des concurrents plus agiles. » Que sous-entend la personne qui parle ?",
                'options' => ['Il faut baisser les prix', 'Il faut adapter la façon de vendre', 'Il faut vendre davantage de produits', 'Il faut racheter un concurrent'],
                'correct_answer' => 'Il faut adapter la façon de vendre',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Tu entends : « Je vous suis sur le principe, mais sur le calendrier, j'ai de sérieux doutes. » Quelle est la position de la personne qui parle ?",
                'options' => ["Elle approuve l'idée et le délai", 'Elle refuse de se prononcer', "Elle approuve l'idée mais doute du délai", "Elle rejette l'idée mais accepte le délai"],
                'correct_answer' => "Elle approuve l'idée mais doute du délai",
                'cefr_sublevel_tag' => 'B2',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Quelqu'un te demande : « Comment tu t'appelles ? » Tu t'appelles Ana. Que réponds-tu ?",
                'options' => ["Je m'appelle Ana", "Je suis d'Ana", "Je m'appelles Ana", 'Je me appelle Ana'],
                'correct_answer' => "Je m'appelle Ana",
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You are at a restaurant and would like some tap water. Que dis-tu ?',
                'options' => ["Je voudrais une carafe de l'eau, s'il vous plaît", "Je voudrais une carafe d'eau, s'il vous plaît", "Je voudrais un carafe d'eau, s'il vous plaît", "Je voudrais de carafe d'eau, s'il vous plaît"],
                'correct_answer' => "Je voudrais une carafe d'eau, s'il vous plaît",
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You arrive at a hotel and need a room for tonight. Que dis-tu à la réception ?',
                'options' => ['Bonsoir, avez une chambre pour ce soir ?', 'Bonsoir, vous êtes une chambre pour ce soir ?', 'Bonsoir, vous avez une chambre pour ce soir ?', 'Bonsoir, vous avez un chambre pour ce soir ?'],
                'correct_answer' => 'Bonsoir, vous avez une chambre pour ce soir ?',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Tu veux dire que ta sœur est plus âgée que toi. Que dis-tu ?',
                'options' => ['Ma sœur est plus âgée que je', "Ma sœur a plus d'âge que moi", 'Ma sœur est plus âgé que moi', 'Ma sœur est plus âgée que moi'],
                'correct_answer' => 'Ma sœur est plus âgée que moi',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You want to say that you like playing tennis at the weekend. Que dis-tu ?',
                'options' => ["J'aime jouer au tennis le week-end", "J'aime jouer du tennis le week-end", "J'aime jouer à tennis le week-end", "J'aime joue au tennis le week-end"],
                'correct_answer' => "J'aime jouer au tennis le week-end",
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'In a clothes shop, you want to know how much a jumper costs. Que dis-tu ?',
                'options' => ['Combien coûter ce pull ?', 'Combien coûte ce pull ?', 'Combien coûtent ce pull ?', 'Quel coûte ce pull ?'],
                'correct_answer' => 'Combien coûte ce pull ?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You are lost in town and ask a passer-by the way to the train station. Que dis-tu ?',
                'options' => ['Excusez-moi, pour aller à le gare ?', 'Excusez-moi, pour allez à la gare ?', 'Excusez-moi, pour aller à la gare ?', 'Excusez-moi, pour aller au gare ?'],
                'correct_answer' => 'Excusez-moi, pour aller à la gare ?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "You phone a restaurant to cancel tonight's booking. Que dis-tu ?",
                'options' => ['Bonjour, je voudrais annuler mon réservation de ce soir', 'Bonjour, je voudrais annule ma réservation de ce soir', 'Bonjour, je voudrais annuler ma réserver de ce soir', 'Bonjour, je voudrais annuler ma réservation de ce soir'],
                'correct_answer' => 'Bonjour, je voudrais annuler ma réservation de ce soir',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'This morning your bus left without you, so you reached the office late. Explain it to a colleague. Que dis-tu ?',
                'options' => ["Je suis arrivé en retard parce que j'ai raté le bus", "J'arrivais en retard parce que j'ai raté le bus", 'Je suis arrivé en retard parce que je suis raté le bus', "Je suis arrivé en retard parce que j'ai rater le bus"],
                'correct_answer' => "Je suis arrivé en retard parce que j'ai raté le bus",
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You want to invite a friend to the cinema this weekend. Que dis-tu ?',
                'options' => ['Tu veut aller au cinéma avec moi ce week-end ?', 'Tu veux aller au cinéma avec moi ce week-end ?', 'Tu veux aller à cinéma avec moi ce week-end ?', 'Tu veux allez au cinéma avec moi ce week-end ?'],
                'correct_answer' => 'Tu veux aller au cinéma avec moi ce week-end ?',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "At the doctor's, you explain that your head has hurt since yesterday. Que dis-tu ?",
                'options' => ['Je suis mal à la tête depuis hier', "J'ai mal à la tête pendant hier", "J'ai mal à la tête depuis hier", "J'ai mal à tête depuis hier"],
                'correct_answer' => "J'ai mal à la tête depuis hier",
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'A friend asks if the new film is as good as the novel it comes from. You think the book is better, but the film is still worth seeing. Que réponds-tu ?',
                'options' => ['Le film est meilleur, mais le livre vaut quand même le coup', 'Le livre est meilleur, donc le film ne vaut pas le coup', 'Le film est aussi bon, mais le livre ne vaut pas le coup', 'Le livre est meilleur, mais le film vaut quand même le coup'],
                'correct_answer' => 'Le livre est meilleur, mais le film vaut quand même le coup',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Your friend planned a picnic in the park, but the forecast is bad. You suggest having it at your place instead. Que dis-tu ?',
                'options' => ["Et si on pique-niquait chez moi plutôt qu'au parc ?", "Et si on pique-niquer chez moi plutôt qu'au parc ?", "Et si on pique-niquera chez moi plutôt qu'au parc ?", "Et si on a pique-niqué chez moi plutôt qu'au parc ?"],
                'correct_answer' => "Et si on pique-niquait chez moi plutôt qu'au parc ?",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'A colleague proposes a cheaper supplier. You agree about the cost but worry about quality. Que réponds-tu ?',
                'options' => ["Sur le prix, vous avez raison, donc la qualité m'inquiète", "Sur le prix, vous avez raison, mais la qualité m'inquiète", "Sur le prix, vous avez tort, mais la qualité m'inquiète", "Sur la qualité, vous avez raison, mais le prix m'inquiète"],
                'correct_answer' => "Sur le prix, vous avez raison, mais la qualité m'inquiète",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You want to say that, during your years abroad, you learned to appreciate your own culture. Que dis-tu ?',
                'options' => ["Quand je vivais à l'étranger, j'ai apprendre à apprécier ma culture", "Quand je vivais à l'étranger, j'ai appris d'apprécier ma culture", "Quand je vivais à l'étranger, j'ai appris à apprécier ma culture", "Quand je vivrai à l'étranger, j'ai appris à apprécier ma culture"],
                'correct_answer' => "Quand je vivais à l'étranger, j'ai appris à apprécier ma culture",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You do not have much money, but you dream of travelling. Que dis-tu ?',
                'options' => ["Si j'aurais plus d'argent, je ferais le tour du monde", "Si j'avais plus d'argent, je voyage", "Si j'ai plus d'argent, je ferais le tour du monde", "Si j'avais plus d'argent, je ferais le tour du monde"],
                'correct_answer' => "Si j'avais plus d'argent, je ferais le tour du monde",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You sent a client the wrong invoice and apologise by formal email. Que dis-tu ?',
                'options' => ["Je vous prie d'excuser cette erreur, elle sera corrigée aujourd'hui", "Je vous prie d'excuser cette erreur, elle serait corrigé aujourd'hui", "Je vous prie excuser cette erreur, elle sera corrigée aujourd'hui", "Je vous prie d'excuser cette erreur, elle a corrigée aujourd'hui"],
                'correct_answer' => "Je vous prie d'excuser cette erreur, elle sera corrigée aujourd'hui",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'In a formal debate, you must state your thesis that public schools deserve more funding. Que dis-tu ?',
                'options' => ["Merci de m'avoir donné la parole pour parler de l'école publique", "Je soutiens que l'école publique devrait bénéficier de davantage de moyens", "Je voudrais savoir si l'école publique bénéficie de davantage de moyens", "Il paraît que l'école publique bénéficiera de davantage de moyens"],
                'correct_answer' => "Je soutiens que l'école publique devrait bénéficier de davantage de moyens",
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'A colleague asks for feedback on a report that has real weaknesses, and you want to stay constructive. Que dis-tu ?',
                'options' => ['Le fond est faible, mais la conclusion gagnerait à être étayée', 'La conclusion gagnerait à être étayée, car le fond est parfait', 'Le fond est solide, mais la conclusion gagnerait à être étayée', 'Le fond est solide, mais la conclusion est à refaire entièrement'],
                'correct_answer' => 'Le fond est solide, mais la conclusion gagnerait à être étayée',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'You want to express regret about not having studied enough when you were young. Que dis-tu ?',
                'options' => ["Je regrette de ne pas être davantage étudié quand j'étais jeune", "Je regrette de ne pas avoir davantage étudier quand j'étais jeune", "Je regrette que je n'ai pas davantage étudié quand j'étais jeune", "Je regrette de ne pas avoir davantage étudié quand j'étais jeune"],
                'correct_answer' => "Je regrette de ne pas avoir davantage étudié quand j'étais jeune",
                'cefr_sublevel_tag' => 'B2',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Marie ___ vingt-cinq ans. »',
                'options' => ['as', 'a', 'est', 'ai'],
                'correct_answer' => 'a',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « L'hôtel ___ près de l'aéroport. »",
                'options' => ['suis', 'sont', 'est', 'a'],
                'correct_answer' => 'est',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Choisis la forme correcte : « la chemise ___ » (blanc)',
                'options' => ['blanc', 'blancs', 'blanches', 'blanche'],
                'correct_answer' => 'blanche',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Nous ___ (manger) à deux heures. »',
                'options' => ['mangeons', 'mangent', 'mange', 'manger'],
                'correct_answer' => 'mangeons',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Vous ___ (finir) à cinq heures ? »',
                'options' => ['finir', 'finissez', 'finis', 'finit'],
                'correct_answer' => 'finissez',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Hier, je ___ (aller) au marché. »',
                'options' => ['irai', 'allé', 'suis allé', 'vais'],
                'correct_answer' => 'suis allé',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Vous ___ (faire) du sport le dimanche ? »',
                'options' => ['faisez', 'faisons', 'font', 'faites'],
                'correct_answer' => 'faites',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Je ___ (devoir) partir à six heures demain. »',
                'options' => ['dois', 'doit', 'devez', 'doivent'],
                'correct_answer' => 'dois',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « Quand j'étais petit, je ___ (jouer) au parc tous les jours. »",
                'options' => ['jouer', 'jouais', 'jouerai', 'joue'],
                'correct_answer' => 'jouais',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « L'année prochaine, nous ___ (voyager) en Argentine. »",
                'options' => ['avons voyagé', 'voyagez', 'voyagerons', 'voyagions'],
                'correct_answer' => 'voyagerons',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « S'il ___ (pleuvoir) demain, nous n'irons pas à la plage. »",
                'options' => ['pleuvait', 'pleuvrait', 'pleuvra', 'pleut'],
                'correct_answer' => 'pleut',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Tu as parlé à Marie ? Oui, je ___ ai parlé ce matin. »',
                'options' => ['lui', 'la', 'leur', 'y'],
                'correct_answer' => 'lui',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Quand je suis arrivé à la gare, le train ___ déjà parti. »',
                'options' => ['avait', 'était', 'est', 'serait'],
                'correct_answer' => 'était',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « Le film ___ nous avons vu hier était excellent. »',
                'options' => ['dont', 'où', 'que', 'qui'],
                'correct_answer' => 'que',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « C'est un livre ___ tout le monde parle. »",
                'options' => ['que', 'qui', 'où', 'dont'],
                'correct_answer' => 'dont',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « Les lettres que j'ai ___ (écrire) hier sont parties. »",
                'options' => ['écrites', 'écrit', 'écrits', 'écrire'],
                'correct_answer' => 'écrites',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « Il s'est blessé en ___ (jouer) au football. »",
                'options' => ['joue', 'jouant', 'jouer', 'joué'],
                'correct_answer' => 'jouant',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « Bien qu'il ___ (être) fatigué, il a fini le rapport. »",
                'options' => ['était', 'sera', 'soit', 'est'],
                'correct_answer' => 'soit',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => 'Complète : « La raison pour ___ il a démissionné reste floue. »',
                'options' => ['lequel', 'lesquelles', 'dont', 'laquelle'],
                'correct_answer' => 'laquelle',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Complète : « Si j'avais su la vérité, je n'___ pas agi ainsi. »",
                'options' => ['aurais', 'avais', 'ai', 'aie'],
                'correct_answer' => 'aurais',
                'cefr_sublevel_tag' => 'B2',
            ],
        ];
    }
}
