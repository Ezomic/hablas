<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form Italian placement test items, structurally identical to
 * PlacementTestSeeder (Spanish) and Portuguese, not adaptive, but tagged with an
 * approximate CEFR sub-level difficulty for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class ItalianPlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $italian = Language::query()->where('code', 'it')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $italian->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
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
                'prompt' => "Che cosa significa 'l'aeroporto'?",
                'options' => ['Airport', 'Hotel', 'Restaurant', 'Street'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Mio fratello è alto e mia sorella è bassa.' Chi è bassa?",
                'options' => ['Mia sorella', 'Mio fratello', 'Mio padre', 'Mia madre'],
                'correct_answer' => 'Mia sorella',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Qual è la traduzione di 'il conto, per favore'?",
                'options' => ['The check, please', 'The menu, please', 'The key, please', 'The room, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi il cartello: 'Camere libere. Colazione inclusa.' Che cosa dice il cartello?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Che cosa significa 'diciassette'?",
                'options' => ['Seventeen', 'Seventy', 'Seven', 'Sixteen'],
                'correct_answer' => 'Seventeen',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'La farmacia apre alle nove e chiude alle venti, ma la domenica è chiusa.' Quando è chiusa la farmacia?",
                'options' => ['La domenica', 'Tutti i giorni', 'La mattina', 'Mai'],
                'correct_answer' => 'La domenica',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Che cosa significa il cartello 'Vietato l'ingresso'?",
                'options' => ['No entry', 'Free entry', 'Ticket office', 'Fire exit'],
                'correct_answer' => 'No entry',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Devo comprare latte, pane e uova al supermercato.' Che cosa deve comprare?",
                'options' => ['Latte, pane e uova', 'Solo latte', 'Vestiti', 'Medicine'],
                'correct_answer' => 'Latte, pane e uova',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Sabato scorso, prima di andare al cinema, abbiamo cenato in un ristorante giapponese.' Che cosa hanno fatto dopo cena?",
                'options' => ['Sono andati al cinema', 'Hanno cenato ancora', 'Sono tornati a casa', 'Sono andati al ristorante'],
                'correct_answer' => 'Sono andati al cinema',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Domani vado a Roma in treno: costa meno dell'aereo, anche se ci metto due ore di più.' Perché sceglie il treno nonostante il tempo?",
                'options' => ['Per risparmiare, anche se è più lento', "Perché è più veloce dell'aereo", 'Perché ci mette due ore di meno', "Perché il biglietto dell'aereo costa meno"],
                'correct_answer' => 'Per risparmiare, anche se è più lento',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Faccio il maestro perché mi piace aiutare i bambini, anche se guadagno poco.' Che rapporto ha con il suo lavoro?",
                'options' => ['Guadagna poco, ma gli piace', 'Gli piace perché guadagna molto', 'Guadagna poco perché non gli piace', 'Lavora di meno perché aiuta i bambini'],
                'correct_answer' => 'Guadagna poco, ma gli piace',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Nonostante piovesse a dirotto, siamo usciti a camminare perché avevamo bisogno di fare movimento.' Che cosa ha spinto a uscire?",
                'options' => ['La necessità di muoversi ha prevalso sul maltempo', 'Sono usciti proprio perché pioveva a dirotto', 'Hanno aspettato che smettesse di piovere', 'Hanno rinunciato a camminare per il maltempo'],
                'correct_answer' => 'La necessità di muoversi ha prevalso sul maltempo',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Molti giovani preferiscono vivere in città perché ci sono più opportunità di lavoro, anche se il costo della vita è più alto.' Perché i giovani scelgono la città nonostante lo svantaggio?",
                'options' => ['Le prospettive di lavoro compensano le spese maggiori', 'Il costo della vita è più basso, ma ci sono meno opportunità di lavoro', "Il costo della vita è più alto e non c'è lavoro", 'Le opportunità di lavoro sono poche, ma la vita costa meno'],
                'correct_answer' => 'Le prospettive di lavoro compensano le spese maggiori',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Siccome il treno era in ritardo, Luca ha preso un taxi, ma è arrivato comunque dopo l'inizio della riunione.' Che cosa è successo a Luca?",
                'options' => ['Ha cercato di recuperare con un taxi, ma è arrivato tardi', 'Ha preso il taxi ed è arrivato prima della riunione', 'Ha aspettato il treno ed è arrivato in orario', 'Non ha preso il taxi e ha saltato la riunione'],
                'correct_answer' => 'Ha cercato di recuperare con un taxi, ma è arrivato tardi',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Da bambino passavo le estati dai nonni, dove ho imparato a pescare e a cucinare i piatti tradizionali.' Che cosa si può dedurre?",
                'options' => ['Andava dai nonni ogni estate e lì ha acquisito abilità pratiche', 'Ci è andato una volta sola e ha imparato a pescare', 'Imparava a cucinare in città e a pescare dai nonni', 'Passava le estati in città e ha imparato a pescare dai nonni'],
                'correct_answer' => 'Andava dai nonni ogni estate e lì ha acquisito abilità pratiche',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'È importante che gli studenti si esercitino ogni giorno, anche solo per dieci minuti, per non dimenticare ciò che hanno imparato.' Che cosa conta di più secondo il testo?",
                'options' => ['La regolarità conta più della durata', 'La durata conta più della regolarità', 'Dieci minuti al giorno non servono a niente', 'Basta esercitarsi una volta alla settimana per molto tempo'],
                'correct_answer' => 'La regolarità conta più della durata',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Pur attraversando un periodo di crisi economica, l'azienda è riuscita ad aumentare le vendite grazie a una nuova strategia di marketing.' Che cosa ha permesso all'azienda di crescere?",
                'options' => ['Un cambio di strategia commerciale, nonostante la crisi', 'La crisi, che ha fatto crescere le vendite', 'Una riduzione dei prezzi dovuta alla crisi', 'Una nuova strategia che ha causato la crisi'],
                'correct_answer' => 'Un cambio di strategia commerciale, nonostante la crisi',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Il governo ha rinviato la riforma, il che, secondo gli analisti, rischia di minare la fiducia degli investitori.' Che cosa rischia di accadere?",
                'options' => ['Il rinvio potrebbe scoraggiare chi investe', 'Gli analisti hanno rinviato la riforma per la fiducia degli investitori', 'La riforma ha minato la fiducia degli analisti', 'Gli investitori hanno ottenuto il rinvio della riforma'],
                'correct_answer' => 'Il rinvio potrebbe scoraggiare chi investe',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Benché la tecnologia abbia semplificato molte attività quotidiane, ha anche generato una dipendenza che alcuni giudicano preoccupante.' Qual è l'idea di fondo del testo?",
                'options' => ['Un vantaggio quotidiano può diventare anche un problema', 'Alcuni giudicano preoccupante la tecnologia perché ha complicato le attività quotidiane', 'Tutti giudicano preoccupante la tecnologia semplificata', 'La dipendenza ha semplificato la tecnologia'],
                'correct_answer' => 'Un vantaggio quotidiano può diventare anche un problema',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Leggi: 'Il sindaco ha ammesso che, per quanto il progetto fosse ambizioso, i fondi stanziati non sarebbero bastati a completarlo.' Che cosa ha ammesso il sindaco?",
                'options' => ['Il progetto costava più dei fondi disponibili', 'Il progetto, pur ambizioso, è già stato completato con i fondi stanziati', 'Il progetto era troppo modesto per usare i fondi', 'Il sindaco ha stanziato altri fondi per completarlo'],
                'correct_answer' => 'Il progetto costava più dei fondi disponibili',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Buongiorno, come sta?' Che cosa si chiede?",
                'options' => ['How are you (formal)', 'How are you (informal)', 'What is your name', 'Where are you from'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'L'uscita d'imbarco è la numero dodici.' Quale informazione viene data?",
                'options' => ['Gate number twelve', 'Gate number two', 'Gate number twenty', 'Platform number twelve'],
                'correct_answer' => 'Gate number twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Vorrei una camera per due notti.' Che cosa si chiede?",
                'options' => ['A room for two nights', 'A room for two people', 'Two rooms for one night', 'A table for two people'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Giri a sinistra all'angolo.' Quale indicazione viene data?",
                'options' => ['Turn left at the corner', 'Turn right at the corner', 'Go straight until the corner', 'Turn left after the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Di solito mi alzo alle sette e mezza.' A che ora si alza di solito?",
                'options' => ['7:30', '7:15', '6:30', '8:30'],
                'correct_answer' => '7:30',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Il treno parte dal binario tre alle dieci e un quarto.' Che cosa viene annunciato?",
                'options' => ['Dal tre, alle dieci e un quarto', 'Dal tre, alle dieci e mezza', 'Dal quattro, alle dieci e un quarto', "Dall'uno, alle tre e un quarto"],
                'correct_answer' => 'Dal tre, alle dieci e un quarto',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Vorrei un tavolo per quattro persone, per favore.' Che cosa si chiede?",
                'options' => ['Posti a sedere per quattro clienti', 'Quattro tavoli per una persona', 'Un tavolo per due persone alle quattro', 'Un menù per quattro persone'],
                'correct_answer' => 'Posti a sedere per quattro clienti',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'La riunione inizia alle nove in punto, non fare tardi.' Che cosa si raccomanda?",
                'options' => ['Di essere puntuale per la riunione delle nove', "Di arrivare un'ora prima delle nove", 'Di spostare la riunione alle nove e mezza', 'Di non venire alla riunione delle nove'],
                'correct_answer' => 'Di essere puntuale per la riunione delle nove',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Lo scorso fine settimana sono andato a trovare i miei genitori e abbiamo pranzato insieme.' Che cosa ha fatto?",
                'options' => ['Ha pranzato con i suoi genitori', 'Ha invitato i genitori a cena', 'Ha pranzato da solo dopo aver visto i genitori', 'Ha accompagnato i genitori in viaggio'],
                'correct_answer' => 'Ha pranzato con i suoi genitori',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'La settimana prossima comincio un corso di inglese perché mi serve per il lavoro.' Che cosa dice del corso di inglese?",
                'options' => ['Lo inizierà la settimana prossima per motivi di lavoro', 'Lo ha iniziato la settimana scorsa per motivi di lavoro', 'Lo inizierà la settimana prossima per andare in vacanza', 'Lo ha finito la settimana scorsa per motivi di lavoro'],
                'correct_answer' => 'Lo inizierà la settimana prossima per motivi di lavoro',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Il caffè lo prendo senza zucchero, ma con un po' di latte.' Come preferisce il caffè?",
                'options' => ['Aggiunge solo del latte', 'Aggiunge solo lo zucchero', 'Aggiunge zucchero e latte', 'Lo beve amaro, senza niente'],
                'correct_answer' => 'Aggiunge solo del latte',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Anche se il volo ha avuto due ore di ritardo, siamo arrivati in tempo per la conferenza.' Che cosa è successo?",
                'options' => ['Nonostante il ritardo, sono arrivati alla conferenza in orario', "Il volo è partito in orario, ma sono arrivati dopo l'inizio della conferenza", 'Hanno perso la conferenza a causa del ritardo di due ore', 'Il volo è stato anticipato di due ore'],
                'correct_answer' => 'Nonostante il ritardo, sono arrivati alla conferenza in orario',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Se il tempo lo permette, faremo la gita sabato mattina.' Che cosa è certo?",
                'options' => ['La gita di sabato mattina dipende dal meteo', 'La gita di sabato mattina è già stata annullata per il meteo', 'La gita si farà comunque, anche se piove', 'La gita dipende dal meteo, ma si farà di sera'],
                'correct_answer' => 'La gita di sabato mattina dipende dal meteo',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Mi piacerebbe cambiare lavoro, ma finora non ho trovato niente di meglio.' Qual è la sua situazione?",
                'options' => ["Vorrebbe cambiare, ma non ha ancora trovato un'alternativa migliore", 'Ha già cambiato lavoro e ne è soddisfatto', 'Ha trovato di meglio, ma non vuole cambiare', 'Non cambia perché il suo lavoro è molto pagato'],
                'correct_answer' => "Vorrebbe cambiare, ma non ha ancora trovato un'alternativa migliore",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Quando vivevamo al paese, ci conoscevamo tutti e ci aiutavamo molto più che in città.' Che cosa afferma chi parla?",
                'options' => ['Un tempo, al paese, la solidarietà tra vicini era maggiore che in città', 'In città la gente si conosceva di più che al paese', 'Al paese si conoscevano tutti, ma ci si aiutava meno che in città', 'Vivevano in città e aiutavano i vicini del paese'],
                'correct_answer' => 'Un tempo, al paese, la solidarietà tra vicini era maggiore che in città',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'È probabile che arriviamo in ritardo a causa del traffico, quindi cominciate pure senza di noi.' Che cosa fa chi parla?",
                'options' => ['Chiede ai colleghi di iniziare anche in loro assenza', 'Chiede ai colleghi di aspettare il loro arrivo', 'Avvisa che arriveranno in anticipo a causa del traffico', 'Propone di rimandare la riunione per il traffico'],
                'correct_answer' => 'Chiede ai colleghi di iniziare anche in loro assenza',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Dopo averci pensato a lungo, ho deciso di accettare il lavoro all'estero, anche se significa lasciare la mia famiglia per un po'.' Che cosa ha deciso?",
                'options' => ['Dopo una lunga riflessione ha accettato, sapendo che starà lontano dai suoi', "Ha rifiutato il lavoro all'estero per non lasciare la famiglia", 'Ha accettato senza pensarci troppo e porterà la famiglia con sé', 'Ha accettato il lavoro, ma solo dopo che la famiglia lo ha raggiunto'],
                'correct_answer' => 'Dopo una lunga riflessione ha accettato, sapendo che starà lontano dai suoi',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Nonostante le critiche iniziali, il progetto si è rivelato un successo clamoroso una volta avviato.' Che cosa si può concludere?",
                'options' => ['Il risultato ha smentito i dubbi iniziali', 'Le critiche iniziali hanno poi portato alla chiusura del progetto', "Il progetto è stato elogiato fin dall'inizio", 'Il progetto ha avuto successo solo grazie alle critiche iniziali'],
                'correct_answer' => 'Il risultato ha smentito i dubbi iniziali',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Sarebbe opportuno ripensare la nostra strategia, se vogliamo restare competitivi in un mercato così mutevole.' Che cosa pensa chi parla?",
                'options' => ['Un cambio di rotta è necessario per non perdere competitività', 'La strategia attuale basta a restare competitivi', "L'azienda ha già perso la propria competitività", 'Il mercato non cambierà, quindi conviene aspettare'],
                'correct_answer' => 'Un cambio di rotta è necessario per non perdere competitività',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Senti: 'Non è che io sia contrario alla proposta, semplicemente credo che ci servano più dati prima di decidere.' Qual è la posizione di chi parla?",
                'options' => ['Non rifiuta la proposta, ma vuole prima più informazioni', 'Rifiuta la proposta perché mancano dati', 'Accetta la proposta senza bisogno di altri dati', 'Propone di decidere subito con i dati disponibili'],
                'correct_answer' => 'Non rifiuta la proposta, ma vuole prima più informazioni',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Qualcuno ti chiede 'Come ti chiami?' Ti chiami Anna. Che cosa rispondi?",
                'options' => ['Mi chiamo Anna', 'Sono di Anna', 'Ho Anna', 'Sta Anna'],
                'correct_answer' => 'Mi chiamo Anna',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi chiedere dell'acqua al ristorante. Che cosa dici?",
                'options' => ["Vorrei dell'acqua, per favore", 'Vorrei il conto, per favore', 'Vorrei una camera, per favore', 'Vorrei una mappa, per favore'],
                'correct_answer' => "Vorrei dell'acqua, per favore",
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi chiedere se in albergo c'è una camera libera. Che cosa dici?",
                'options' => ["C'è una camera libera?", "Dov'è il bagno?", 'Quanto costa la colazione?', 'A che ora è la partenza?'],
                'correct_answer' => "C'è una camera libera?",
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi dire che tua sorella è più grande di te. Quale frase è corretta?',
                'options' => ['Mia sorella è più grande di me', 'Mia sorella è più grande di io', 'Mia sorella ha più grande di me', 'Mia sorella sono più grande'],
                'correct_answer' => 'Mia sorella è più grande di me',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi dire 'I get up early every day.' Quale frase è corretta?",
                'options' => ['Mi alzo presto ogni giorno', 'Alzo presto ogni giorno', 'Mi alzo presto ogni giorni', 'Io alzo mi presto ogni giorno'],
                'correct_answer' => 'Mi alzo presto ogni giorno',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi chiedere il prezzo di un maglione in un negozio. Che cosa dici?',
                'options' => ['Quanto costa questo maglione?', "Dov'è il camerino?", 'Fate lo sconto?', 'A che ora chiudete?'],
                'correct_answer' => 'Quanto costa questo maglione?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi chiedere come arrivare alla stazione dei treni. Che cosa dici?',
                'options' => ['Come arrivo alla stazione?', 'Che ore sono?', 'Quanto costa il biglietto?', 'Di dove sei?'],
                'correct_answer' => 'Come arrivo alla stazione?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi annullare una prenotazione al ristorante. Che cosa dici?',
                'options' => ['Vorrei annullare la mia prenotazione', 'Vorrei fare una prenotazione', 'Vorrei vedere il menù', 'Vorrei pagare il conto'],
                'correct_answer' => 'Vorrei annullare la mia prenotazione',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi spiegare perché ieri sei arrivato in ritardo al lavoro. Quale frase è corretta?',
                'options' => ["Ieri sono arrivato in ritardo perché ho perso l'autobus", "Ieri arrivo in ritardo perché perdo l'autobus", "Ieri arriverò in ritardo perché perderò l'autobus", "Ieri sono arrivato in ritardo perché perderò l'autobus"],
                'correct_answer' => "Ieri sono arrivato in ritardo perché ho perso l'autobus",
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi invitare un amico al cinema questo fine settimana. Che cosa dici?',
                'options' => ['Vuoi venire al cinema con me questo fine settimana?', 'Sono andato al cinema lo scorso fine settimana', 'Mi piace molto il cinema', 'Il cinema è chiuso'],
                'correct_answer' => 'Vuoi venire al cinema con me questo fine settimana?',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi dire che hai l'abitudine di fare colazione prima di uscire di casa. Quale frase è corretta?",
                'options' => ['Faccio sempre colazione prima di uscire di casa', 'Faccio sempre colazione prima di esco di casa', 'Faccio sempre colazione prima che uscire di casa', 'Faccio sempre colazione dopo di uscire di casa'],
                'correct_answer' => 'Faccio sempre colazione prima di uscire di casa',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi dire che un film non ti è piaciuto perché la trama era lenta. Quale frase è corretta?',
                'options' => ['Il film non mi è piaciuto perché la trama era troppo lenta', 'Il film non mi ha piaciuto perché la trama era troppo lenta', 'Il film non ho piaciuto perché la trama era troppo lenta', 'Il film non mi è piaciuto perché la trama fosse troppo lenta'],
                'correct_answer' => 'Il film non mi è piaciuto perché la trama era troppo lenta',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi proporre un'alternativa a un piano che non ti convince. Quale frase è corretta?",
                'options' => ["E se invece facessimo qualcos'altro?", "E se invece faremmo qualcos'altro?", "E se invece facemmo qualcos'altro?", "E se invece fossimo fare qualcos'altro?"],
                'correct_answer' => "E se invece facessimo qualcos'altro?",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi raccontare un'esperienza che hai vissuto nel passato. Quale frase è corretta?",
                'options' => ["Quando ho vissuto all'estero, ho imparato ad apprezzare la mia cultura", "Quando ho vivuto all'estero, ho imparato ad apprezzare la mia cultura", "Quando vivrei all'estero, ho imparato ad apprezzare la mia cultura", "Quando vivrò all'estero, ho imparato ad apprezzare la mia cultura"],
                'correct_answer' => "Quando ho vissuto all'estero, ho imparato ad apprezzare la mia cultura",
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi dire di essere solo in parte d'accordo con qualcuno. Quale frase è corretta?",
                'options' => ["Sono d'accordo, ma credo che si debba considerare anche il prezzo", "Sono d'accordo, ma credo che si deve considerare anche il prezzo", "Sono d'accordo, ma credo che si dovesse considerare anche il prezzo", "Sono d'accordo, ma credo che si devo considerare anche il prezzo"],
                'correct_answer' => "Sono d'accordo, ma credo che si debba considerare anche il prezzo",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Vuoi esprimere un'ipotesi irreale sul presente. Quale frase è corretta?",
                'options' => ['Se avessi più soldi, viaggerei per il mondo', 'Se avrei più soldi, viaggerei per il mondo', 'Se avessi più soldi, viaggiassi per il mondo', 'Se avrò più soldi, viaggiassi per il mondo'],
                'correct_answer' => 'Se avessi più soldi, viaggerei per il mondo',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi scusarti con la tua direttrice, a cui dai del Lei, per un errore sul lavoro. Quale frase è corretta?',
                'options' => ["Mi scusi per l'errore, non succederà più", "Scusami per l'errore, non succederà più", "Scusa per l'errore, non succederà più", "Scusatemi per l'errore, non succederà più"],
                'correct_answer' => "Mi scusi per l'errore, non succederà più",
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi sostenere una posizione in un dibattito formale. Quale frase è corretta?',
                'options' => ['Ritengo che la scuola pubblica debba ricevere maggiori investimenti', 'Ritengo che la scuola pubblica deve ricevere maggiori investimenti', 'Ritengo che la scuola pubblica dovesse ricevere maggiori investimenti', 'Ritengo che la scuola pubblica ha dovuto ricevere maggiori investimenti'],
                'correct_answer' => 'Ritengo che la scuola pubblica debba ricevere maggiori investimenti',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi attenuare una critica perché non suoni troppo dura. Quale frase è corretta?',
                'options' => ['Mi sembra che il progetto abbia qualche punto debole, ma nel complesso è valido', 'Mi sembra che il progetto ha qualche punto debole, ma nel complesso è valido', 'Mi sembra che il progetto ebbe qualche punto debole, ma nel complesso è valido', 'Mi sembra che il progetto avere qualche punto debole, ma nel complesso è valido'],
                'correct_answer' => 'Mi sembra che il progetto abbia qualche punto debole, ma nel complesso è valido',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Vuoi esprimere rimpianto per una scelta passata. Quale frase è corretta?',
                'options' => ['Magari avessi studiato di più da giovane', 'Magari studiassi di più da giovane', 'Se solo avrei studiato di più da giovane', 'Ho studiato molto da giovane'],
                'correct_answer' => 'Magari avessi studiato di più da giovane',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Lei ___ professoressa.'",
                'options' => ['è', 'sono', 'sei', 'siamo'],
                'correct_answer' => 'è',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nella stanza ___ due letti.'",
                'options' => ['ci sono', "c'è", 'ci è', 'siamo'],
                'correct_answer' => 'ci sono',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Scegli la forma corretta: 'la camicia ___' (red)",
                'options' => ['rossa', 'rosso', 'rossi', 'rosse'],
                'correct_answer' => 'rossa',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Noi ___ (mangiare) alle due.'",
                'options' => ['mangiamo', 'mangiano', 'mangia', 'mangiare'],
                'correct_answer' => 'mangiamo',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Voi ___ (parlare) italiano molto bene.'",
                'options' => ['parlate', 'parlano', 'parla', 'parlare'],
                'correct_answer' => 'parlate',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ieri ___ al mercato e ho comprato la frutta.'",
                'options' => ['sono andato', 'ho andato', 'vado', 'andrò'],
                'correct_answer' => 'sono andato',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Vivo a Milano ___ tre anni, quindi la conosco bene.'",
                'options' => ['da', 'fa', 'tra', 'dopo'],
                'correct_answer' => 'da',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Stasera Anna ___ (uscire) con le amiche.'",
                'options' => ['esce', 'usce', 'uscisce', 'uscia'],
                'correct_answer' => 'esce',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Da piccolo ___ (giocare) al parco tutti i giorni.'",
                'options' => ['giocavo', 'ho giocato', 'gioco', 'giocherò'],
                'correct_answer' => 'giocavo',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Il mese prossimo ___ (viaggiare) in Argentina.'",
                'options' => ['viaggerò', 'ho viaggiato', 'viaggiavo', 'viaggiato'],
                'correct_answer' => 'viaggerò',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Mentre io cucinavo, mio fratello ___ (apparecchiare) la tavola.'",
                'options' => ['apparecchiava', 'apparecchia', 'apparecchierà', 'apparecchiare'],
                'correct_answer' => 'apparecchiava',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Se domani ___ (piovere), non andremo al mare.'",
                'options' => ['piove', 'pioveva', 'piovesse', 'è piovuto'],
                'correct_answer' => 'piove',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Hai invitato degli amici? Sì, ___ invitati cinque.'",
                'options' => ['ne ho', 'li ho', 'ci ho', 'gli ho'],
                'correct_answer' => 'ne ho',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'È possibile che loro ___ (finire) il progetto ieri sera.'",
                'options' => ['abbiano finito', 'hanno finito', 'finiscono', 'finirebbero'],
                'correct_answer' => 'abbiano finito',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'La città ___ sono nato è molto piccola.'",
                'options' => ['in cui', 'che', 'di cui', 'con cui'],
                'correct_answer' => 'in cui',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Benché ___ (essere) stanco, ho finito la relazione ieri sera.'",
                'options' => ['fossi', 'sono', 'ero', 'sarei'],
                'correct_answer' => 'fossi',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Maria mi ha detto ieri che alla festa di sabato prossimo ___ (arrivare) in ritardo.'",
                'options' => ['sarebbe arrivata', 'è arrivata', 'arrivasse', 'ha arrivato'],
                'correct_answer' => 'sarebbe arrivata',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Hai restituito la penna a Marco? Sì, ___ già restituita.'",
                'options' => ["gliel'ho", 'gliele ho', 'glielo ho', "le l'ho"],
                'correct_answer' => "gliel'ho",
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ieri non credevo che la situazione ___ (migliorare) senza un vero cambiamento.'",
                'options' => ['migliorasse', 'migliori', 'migliora', 'migliorò'],
                'correct_answer' => 'migliorasse',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Se me lo avessi detto allora, ieri ti ___ (aiutare).'",
                'options' => ['avrei aiutato', 'aiuterei', 'avessi aiutato', 'aiutassi'],
                'correct_answer' => 'avrei aiutato',
                'cefr_sublevel_tag' => 'B2',
            ],
        ];
    }
}
