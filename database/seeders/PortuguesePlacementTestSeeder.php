<?php

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form Portuguese placement test items, structurally identical to
 * PlacementTestSeeder (Spanish) — not adaptive, but tagged with an
 * approximate CEFR sub-level difficulty for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class PortuguesePlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $portuguese->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
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
                'prompt' => "O que significa 'o aeroporto'?",
                'options' => ['Airport', 'Hotel', 'Restaurant', 'Street'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'O meu irmão é alto e a minha irmã é baixa.' Quem é baixa?",
                'options' => ['O meu irmão', 'A minha irmã', 'O meu pai', 'A minha mãe'],
                'correct_answer' => 'A minha irmã',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Qual é a tradução correta de 'a conta, por favor'?",
                'options' => ['The check, please', 'The menu, please', 'The key, please', 'The room, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê o cartaz: 'Quartos disponíveis. Pequeno-almoço incluído.' O que diz o cartaz?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Completa: 'Eu ___ estudante.'",
                'options' => ['sou', 'estou', 'é', 'está'],
                'correct_answer' => 'sou',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'A farmácia abre às nove e fecha às oito, mas aos domingos está fechada.' Quando está fechada a farmácia?",
                'options' => ['Aos domingos', 'Todos os dias', 'De manhã', 'Nunca'],
                'correct_answer' => 'Aos domingos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "O que significa o cartaz 'Proibido fumar'?",
                'options' => ['No smoking', 'Please smoke', 'Smoking area', 'Fire exit'],
                'correct_answer' => 'No smoking',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Preciso de comprar leite, pão e ovos no supermercado.' O que vai comprar?",
                'options' => ['Leite, pão e ovos', 'Só leite', 'Roupa', 'Medicamentos'],
                'correct_answer' => 'Leite, pão e ovos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'No sábado passado fomos ao cinema e depois jantámos num restaurante italiano.' O que fizeram primeiro?",
                'options' => ['Foram ao cinema', 'Jantaram', 'Foram ao restaurante', 'Ficaram em casa'],
                'correct_answer' => 'Foram ao cinema',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Amanhã vou viajar para Lisboa de comboio porque é mais barato do que o avião.' Porque viaja de comboio?",
                'options' => ['Porque é mais barato', 'Porque é mais rápido', 'Porque não gosta de voar', 'Porque o avião está cheio'],
                'correct_answer' => 'Porque é mais barato',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'O meu trabalho preferido é o de professor porque gosto de ajudar os alunos.' Porque gosta de ser professor?",
                'options' => ['Porque gosta de ajudar os alunos', 'Porque ganha muito dinheiro', 'Porque trabalha pouco', 'Porque viaja muito'],
                'correct_answer' => 'Porque gosta de ajudar os alunos',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Apesar de estar a chover muito, decidimos sair para caminhar porque precisávamos de fazer exercício.' Porque saíram apesar da chuva?",
                'options' => ['Precisavam de fazer exercício', 'Gostam da chuva', 'Não tinham guarda-chuva', 'Queriam molhar-se'],
                'correct_answer' => 'Precisavam de fazer exercício',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Muitos jovens preferem viver na cidade porque há mais oportunidades de trabalho, embora o custo de vida seja mais alto.' Qual é a desvantagem mencionada?",
                'options' => ['O custo de vida é mais alto', 'Não há trabalho', 'A cidade é aborrecida', 'Há pouco transporte'],
                'correct_answer' => 'O custo de vida é mais alto',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Se tivesse mais tempo, aprenderia a tocar piano.' O que o impede de aprender piano?",
                'options' => ['A falta de tempo', 'A falta de dinheiro', 'A falta de interesse', 'A falta de um piano'],
                'correct_answer' => 'A falta de tempo',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Quando era criança, costumava passar os verões em casa dos meus avós, onde aprendi a pescar e a cozinhar pratos tradicionais.' O que aprendeu em criança?",
                'options' => ['A pescar e a cozinhar', 'A nadar e a dançar', 'A ler e a escrever', 'A conduzir'],
                'correct_answer' => 'A pescar e a cozinhar',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'É importante que os alunos pratiquem todos os dias, mesmo que seja só dez minutos, para não perder o que aprenderam.' O que se recomenda?",
                'options' => ['Praticar diariamente mesmo que seja pouco tempo', 'Estudar só ao fim de semana', 'Praticar uma vez por mês', 'Não é necessário praticar'],
                'correct_answer' => 'Praticar diariamente mesmo que seja pouco tempo',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Apesar das dificuldades económicas, a empresa conseguiu aumentar as suas vendas graças a uma nova estratégia de marketing.' Como conseguiu aumentar as vendas a empresa?",
                'options' => ['Com uma nova estratégia de marketing', 'Baixando os preços', 'Fechando lojas', 'Despedindo funcionários'],
                'correct_answer' => 'Com uma nova estratégia de marketing',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'As alterações climáticas colocam desafios sem precedentes que exigem uma cooperação internacional sem falhas.' O que é necessário para enfrentar as alterações climáticas segundo o texto?",
                'options' => ['Cooperação internacional', 'Mais fábricas', 'Menos regulamentação', 'Turismo espacial'],
                'correct_answer' => 'Cooperação internacional',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Embora a tecnologia tenha simplificado muitas tarefas do dia a dia, também gerou uma dependência que alguns consideram preocupante.' Qual é a preocupação mencionada?",
                'options' => ['A dependência da tecnologia', 'O custo da tecnologia', 'A falta de tecnologia', 'A velocidade da internet'],
                'correct_answer' => 'A dependência da tecnologia',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lê: 'Não há dúvida de que a educação bilingue oferece vantagens cognitivas, embora a sua implementação exija recursos consideráveis.' O que exige a educação bilingue?",
                'options' => ['Recursos consideráveis', 'Pouco esforço', 'Nenhum recurso', 'Só uma língua'],
                'correct_answer' => 'Recursos consideráveis',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Bom dia, como está?' O que se pergunta?",
                'options' => ['How are you (formal)', 'What is your name', 'Where are you from', 'How old are you'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'A porta de embarque é a número doze.' Que número se menciona?",
                'options' => ['Twelve', 'Two', 'Twenty', 'Twenty-two'],
                'correct_answer' => 'Twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Queria um quarto para duas noites.' O que se pede?",
                'options' => ['A room for two nights', 'A table for two people', 'A ticket for two people', 'A discount for two nights'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Vire à esquerda na esquina.' Que direção se dá?",
                'options' => ['Turn left at the corner', 'Turn right at the corner', 'Go straight ahead', 'Stop at the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Normalmente levanto-me às sete e meia.' A que horas se levanta normalmente?",
                'options' => ['7:30', '7:00', '8:30', '6:30'],
                'correct_answer' => '7:30',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'O comboio parte da linha três às dez e um quarto.' De que linha parte o comboio?",
                'options' => ['Três', 'Um', 'Dez', 'Quatro'],
                'correct_answer' => 'Três',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Preciso de uma mesa para quatro pessoas, por favor.' Para quantas pessoas é a mesa?",
                'options' => ['Quatro', 'Duas', 'Três', 'Cinco'],
                'correct_answer' => 'Quatro',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'A reunião começa às nove em ponto, não chegues atrasado.' A que horas começa a reunião?",
                'options' => ['Às nove', 'Às dez', 'Às oito', 'Às nove e meia'],
                'correct_answer' => 'Às nove',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'No fim de semana passado visitei os meus pais e almoçámos juntos.' O que fez no fim de semana passado?",
                'options' => ['Visitou os pais', 'Foi de viagem', 'Trabalhou o dia todo', 'Ficou em casa sozinho'],
                'correct_answer' => 'Visitou os pais',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Vou começar um curso de inglês na próxima semana porque preciso dele para o meu trabalho.' Porque vai estudar inglês?",
                'options' => ['Precisa dele para o trabalho', 'Gosta de viajar', 'É um passatempo', 'Pediu-lhe um amigo'],
                'correct_answer' => 'Precisa dele para o trabalho',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Prefiro o café sem açúcar, mas com um pouco de leite.' Como prefere o café?",
                'options' => ['Sem açúcar, com leite', 'Com açúcar, sem leite', 'Sem açúcar nem leite', 'Com açúcar e leite'],
                'correct_answer' => 'Sem açúcar, com leite',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Apesar de o voo se ter atrasado duas horas, chegámos a tempo para a conferência.' O que aconteceu com o voo?",
                'options' => ['Atrasou-se duas horas', 'Foi cancelado', 'Chegou mais cedo', 'Não houve problemas'],
                'correct_answer' => 'Atrasou-se duas horas',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Se o tempo o permitir, faremos o passeio no sábado de manhã.' Quando fariam o passeio?",
                'options' => ['No sábado de manhã', 'No domingo', 'Na sexta-feira', 'No sábado à noite'],
                'correct_answer' => 'No sábado de manhã',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Gostava de mudar de trabalho, mas ainda não encontrei nada melhor.' Porque não mudou de trabalho?",
                'options' => ['Não encontrou nada melhor', 'Gosta muito do trabalho atual', 'Pagam-lhe muito bem', 'Não quer mudar'],
                'correct_answer' => 'Não encontrou nada melhor',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Quando vivíamos na aldeia, todos nos conhecíamos e ajudávamo-nos muito mais do que na cidade.' Que diferença menciona entre a aldeia e a cidade?",
                'options' => ['Na aldeia ajudavam-se mais', 'Na cidade há mais ajuda', 'Não há diferença', 'A aldeia é maior'],
                'correct_answer' => 'Na aldeia ajudavam-se mais',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'É provável que cheguemos atrasados devido ao trânsito, por isso comecem sem nós.' O que se pede?",
                'options' => ['Que comecem sem eles', 'Que os esperem', 'Que cancelem a reunião', 'Que mudem de lugar'],
                'correct_answer' => 'Que comecem sem eles',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Depois de pensar muito, decidi aceitar o trabalho no estrangeiro, embora signifique deixar a minha família por um tempo.' O que decidiu fazer?",
                'options' => ['Aceitar o trabalho no estrangeiro', 'Recusar o trabalho', 'Ficar com a família', 'Procurar outro trabalho'],
                'correct_answer' => 'Aceitar o trabalho no estrangeiro',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Apesar das críticas iniciais, o projeto acabou por ser um sucesso rotundo depois de implementado.' Como resultou o projeto no final?",
                'options' => ['Um sucesso rotundo', 'Um fracasso', 'Algo medíocre', 'Cancelado'],
                'correct_answer' => 'Um sucesso rotundo',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Convinha repensar a nossa estratégia se quisermos continuar competitivos neste mercado tão mutável.' O que se sugere fazer?",
                'options' => ['Repensar a estratégia', 'Manter tudo igual', 'Fechar o negócio', 'Ignorar o mercado'],
                'correct_answer' => 'Repensar a estratégia',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Ouves: 'Não é que esteja em desacordo com a proposta, simplesmente acho que precisamos de mais dados antes de decidir.' Qual é a posição do orador?",
                'options' => ['Quer mais dados antes de decidir', 'Está totalmente em desacordo', 'Está totalmente de acordo', 'Não tem opinião'],
                'correct_answer' => 'Quer mais dados antes de decidir',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Alguém pergunta 'Como te chamas?' Chamas-te Ana. O que respondes?",
                'options' => ['Chamo-me Ana', 'Sou de Ana', 'Tenho Ana', 'Está Ana'],
                'correct_answer' => 'Chamo-me Ana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres pedir água num restaurante. O que dizes?',
                'options' => ['Queria água, por favor', 'Queria a conta, por favor', 'Queria um quarto, por favor', 'Queria um mapa, por favor'],
                'correct_answer' => 'Queria água, por favor',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres perguntar se há um quarto de hotel disponível. O que dizes?',
                'options' => ['Há um quarto disponível?', 'Onde fica a casa de banho?', 'Quanto custa o pequeno-almoço?', 'A que horas é a saída?'],
                'correct_answer' => 'Há um quarto disponível?',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres dizer que a tua irmã é mais velha do que tu. Qual é correto?',
                'options' => ['A minha irmã é mais velha do que eu', 'A minha irmã está mais velha do que eu', 'A minha irmã é mais velha do que mim', 'A minha irmã sou mais velha'],
                'correct_answer' => 'A minha irmã é mais velha do que eu',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Queres dizer 'I get up early every day.' Qual é correto?",
                'options' => ['Levanto-me cedo todos os dias', 'Me levanto cedo todos os dias', 'Levanto-me cedo todo o dia', 'Eu levanto cedo todos os dias'],
                'correct_answer' => 'Levanto-me cedo todos os dias',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres perguntar o preço de uma camisola numa loja. O que dizes?',
                'options' => ['Quanto custa esta camisola?', 'Onde fica o provador?', 'Têm desconto?', 'A que horas fecham?'],
                'correct_answer' => 'Quanto custa esta camisola?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres pedir indicações para chegar à estação de comboio. O que dizes?',
                'options' => ['Como chego à estação de comboio?', 'Que horas são?', 'Quanto custa o bilhete?', 'De onde és?'],
                'correct_answer' => 'Como chego à estação de comboio?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres cancelar uma reserva num restaurante. O que dizes?',
                'options' => ['Queria cancelar a minha reserva', 'Queria fazer uma reserva', 'Queria ver a ementa', 'Queria pagar a conta'],
                'correct_answer' => 'Queria cancelar a minha reserva',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres explicar porque chegaste atrasado ao trabalho. Qual é correto?',
                'options' => ['Cheguei atrasado porque perdi o autocarro', 'Chego atrasado porque perco o autocarro', 'Chegarei atrasado porque perderei o autocarro', 'Chegava atrasado porque perdia o autocarro'],
                'correct_answer' => 'Cheguei atrasado porque perdi o autocarro',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres convidar um amigo para o cinema este fim de semana. O que dizes?',
                'options' => ['Queres ir ao cinema comigo este fim de semana?', 'Fui ao cinema no fim de semana passado', 'Gosto muito de cinema', 'O cinema está fechado'],
                'correct_answer' => 'Queres ir ao cinema comigo este fim de semana?',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres descrever a tua rotina diária. Qual é correto?',
                'options' => ['Todos os dias acordo às sete', 'Ontem acordei às sete', 'Amanhã acordarei às sete', 'Nunca acordo cedo'],
                'correct_answer' => 'Todos os dias acordo às sete',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres dar a tua opinião sobre um filme que não gostaste. Qual é correto?',
                'options' => ['Não gostei do filme porque o enredo era muito lento', 'Adorei o filme', 'Não vi o filme', 'O filme dura duas horas'],
                'correct_answer' => 'Não gostei do filme porque o enredo era muito lento',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres sugerir uma alternativa a um plano que não te convence. O que dizes?',
                'options' => ['E se em vez disso fizermos outra coisa?', 'Parece-me perfeito o plano', 'Não tenho opinião nenhuma', 'Faz o que quiseres'],
                'correct_answer' => 'E se em vez disso fizermos outra coisa?',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres expressar que concordas parcialmente com alguém. Qual é correto?',
                'options' => ['Concordo, mas acho que também é preciso considerar o preço', 'Concordo totalmente', 'Não concordo nada', 'Não percebo a pergunta'],
                'correct_answer' => 'Concordo, mas acho que também é preciso considerar o preço',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres contar uma experiência passada que mudou a tua forma de pensar. Qual é correto?',
                'options' => ['Quando vivi no estrangeiro, aprendi a valorizar a minha cultura', 'Vou viver no estrangeiro um dia', 'Vivo no estrangeiro agora', 'Se viver no estrangeiro, vou aprender muito'],
                'correct_answer' => 'Quando vivi no estrangeiro, aprendi a valorizar a minha cultura',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres expressar uma condição hipotética sobre o futuro. Qual é correto?',
                'options' => ['Se tivesse mais dinheiro, viajaria pelo mundo', 'Se tenho mais dinheiro, viajo pelo mundo', 'Tenho mais dinheiro e viajo', 'Tinha mais dinheiro e viajava'],
                'correct_answer' => 'Se tivesse mais dinheiro, viajaria pelo mundo',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres pedir desculpa formalmente por um erro no trabalho. Qual é correto?',
                'options' => ['Lamento muito o erro, não voltará a acontecer', 'Desculpa, é que sou assim', 'Não foi culpa minha', 'Tanto faz, não importa'],
                'correct_answer' => 'Lamento muito o erro, não voltará a acontecer',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres argumentar a favor de uma posição num debate. Qual é correto?',
                'options' => ['Defendo que a educação pública deveria receber mais investimento', 'A educação pública é má', 'Não me importo com a educação', 'Todos deveriam pagar para estudar'],
                'correct_answer' => 'Defendo que a educação pública deveria receber mais investimento',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres suavizar uma crítica para que não soe demasiado dura. Qual é correto?',
                'options' => ['Não é que o projeto esteja mal, mas acho que se poderia melhorar nalguns aspetos', 'O projeto é um desastre', 'O projeto é perfeito', 'Não tenho nada a dizer'],
                'correct_answer' => 'Não é que o projeto esteja mal, mas acho que se poderia melhorar nalguns aspetos',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Queres expressar arrependimento sobre uma decisão passada. Qual é correto?',
                'options' => ['Quem me dera ter estudado mais quando era jovem', 'Quem me dera estudar mais', 'Estudei muito quando era jovem', 'Vou estudar mais'],
                'correct_answer' => 'Quem me dera ter estudado mais quando era jovem',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ela ___ professora.'",
                'options' => ['é', 'está', 'sou', 'és'],
                'correct_answer' => 'é',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'O hotel ___ perto do aeroporto.'",
                'options' => ['está', 'é', 'sou', 'são'],
                'correct_answer' => 'está',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Escolhe a forma correta: 'a camisa ___' (vermelha)",
                'options' => ['vermelha', 'vermelho', 'vermelhos', 'vermelhas'],
                'correct_answer' => 'vermelha',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nós ___ (comer) às duas.'",
                'options' => ['comemos', 'comeram', 'come', 'comer'],
                'correct_answer' => 'comemos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nós ___ (falar) português.'",
                'options' => ['falamos', 'falam', 'fala', 'falar'],
                'correct_answer' => 'falamos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ontem ___ (ir) ao mercado e comprei fruta.'",
                'options' => ['fui', 'vou', 'irei', 'ia'],
                'correct_answer' => 'fui',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ela ___ (ter) dois irmãos.'",
                'options' => ['tem', 'tenho', 'tens', 'temos'],
                'correct_answer' => 'tem',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nós ___ (viver) em Lisboa há três anos.'",
                'options' => ['vivemos', 'vivo', 'vive', 'vivem'],
                'correct_answer' => 'vivemos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Quando era pequeno, ___ (brincar) no parque todos os dias.'",
                'options' => ['brincava', 'brinquei', 'brinco', 'brincarei'],
                'correct_answer' => 'brincava',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'No próximo mês ___ (viajar) para a Argentina.'",
                'options' => ['viajarei', 'viajo', 'viajava', 'viajei'],
                'correct_answer' => 'viajarei',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Enquanto cozinhava, o meu irmão ___ (pôr) a mesa.'",
                'options' => ['punha', 'põe', 'pôs', 'porá'],
                'correct_answer' => 'punha',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Se ___ (chover) amanhã, não iremos à praia.'",
                'options' => ['chover', 'chovia', 'chovesse', 'choveu'],
                'correct_answer' => 'chover',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ela disse que ___ (chegar) atrasada à festa.'",
                'options' => ['chegaria', 'chega', 'chegou', 'chegue'],
                'correct_answer' => 'chegaria',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'É possível que eles já ___ (terminar) o projeto.'",
                'options' => ['tenham terminado', 'têm terminado', 'terminaram', 'terminarão'],
                'correct_answer' => 'tenham terminado',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Se ___ (ter) mais tempo, aprenderia a tocar guitarra.'",
                'options' => ['tivesse', 'tenho', 'tive', 'terei'],
                'correct_answer' => 'tivesse',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Quem me dera ___ (poder) vir ao casamento no mês que vem.'",
                'options' => ['poder', 'posso', 'poderei', 'pude'],
                'correct_answer' => 'poder',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Embora ___ (estar) cansado, terminei o relatório ontem à noite.'",
                'options' => ['estivesse', 'esteja', 'estava', 'estou'],
                'correct_answer' => 'estivesse',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Se tivesse sabido a verdade, não ___ (agir) dessa maneira.'",
                'options' => ['teria agido', 'agiria', 'agi', 'agia'],
                'correct_answer' => 'teria agido',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Não acho que a situação ___ (melhorar) sem uma mudança real de política.'",
                'options' => ['melhore', 'melhora', 'melhorou', 'melhorará'],
                'correct_answer' => 'melhore',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Por mais que o ___ (tentar), não conseguiu convencê-los.'",
                'options' => ['tentasse', 'tenta', 'tentou', 'tentava'],
                'correct_answer' => 'tentasse',
                'cefr_sublevel_tag' => 'B2',
            ],
        ];
    }
}
