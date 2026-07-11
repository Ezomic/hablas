<?php

namespace Database\Seeders;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Database\Seeder;

/**
 * Fixed-form Spanish placement test items, per category 1 of the Feature
 * Brainstorm doc: not adaptive, but tagged with an approximate CEFR
 * sub-level difficulty from day one for a future adaptive/IRT upgrade.
 * AI-drafted; needs a human review pass before being authoritative.
 */
class PlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spanish = Language::query()->where('code', 'es')->firstOrFail();

        foreach ($this->items() as $sortOrder => $item) {
            PlacementTestItem::query()->updateOrCreate(
                ['language_id' => $spanish->id, 'skill' => $item['skill'], 'prompt' => $item['prompt']],
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
                'prompt' => "¿Qué significa 'el aeropuerto'?",
                'options' => ['Airport', 'Hotel', 'Restaurant', 'Street'],
                'correct_answer' => 'Airport',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Mi hermano es alto y mi hermana es baja.' ¿Quién es baja?",
                'options' => ['Mi hermano', 'Mi hermana', 'Mi padre', 'Mi madre'],
                'correct_answer' => 'Mi hermana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "¿Cuál es la traducción correcta de 'la cuenta, por favor'?",
                'options' => ['The check, please', 'The menu, please', 'The key, please', 'The room, please'],
                'correct_answer' => 'The check, please',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee el cartel: 'Habitaciones disponibles. Desayuno incluido.' ¿Qué dice el cartel?",
                'options' => ['Rooms available, breakfast included', 'No rooms available', 'Breakfast not included', 'Restaurant closed'],
                'correct_answer' => 'Rooms available, breakfast included',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Completa: 'Yo ___ estudiante.'",
                'options' => ['soy', 'estoy', 'es', 'está'],
                'correct_answer' => 'soy',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'La farmacia abre a las nueve y cierra a las ocho, pero los domingos está cerrada.' ¿Cuándo está cerrada la farmacia?",
                'options' => ['Los domingos', 'Todos los días', 'Por la mañana', 'Nunca'],
                'correct_answer' => 'Los domingos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "¿Qué significa el cartel 'Prohibido fumar'?",
                'options' => ['No smoking', 'Please smoke', 'Smoking area', 'Fire exit'],
                'correct_answer' => 'No smoking',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Necesito comprar leche, pan y huevos en el supermercado.' ¿Qué va a comprar?",
                'options' => ['Leche, pan y huevos', 'Solo leche', 'Ropa', 'Medicinas'],
                'correct_answer' => 'Leche, pan y huevos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'El sábado pasado fuimos al cine y después cenamos en un restaurante italiano.' ¿Qué hicieron primero?",
                'options' => ['Fueron al cine', 'Cenaron', 'Fueron al restaurante', 'Se quedaron en casa'],
                'correct_answer' => 'Fueron al cine',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Mañana voy a viajar a Madrid en tren porque es más barato que el avión.' ¿Por qué viaja en tren?",
                'options' => ['Porque es más barato', 'Porque es más rápido', 'Porque no le gusta volar', 'Porque el avión está lleno'],
                'correct_answer' => 'Porque es más barato',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Mi trabajo favorito es el de profesor porque me gusta ayudar a los estudiantes.' ¿Por qué le gusta ser profesor?",
                'options' => ['Porque le gusta ayudar a los estudiantes', 'Porque gana mucho dinero', 'Porque trabaja poco', 'Porque viaja mucho'],
                'correct_answer' => 'Porque le gusta ayudar a los estudiantes',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Aunque llovía mucho, decidimos salir a caminar porque necesitábamos hacer ejercicio.' ¿Por qué salieron a pesar de la lluvia?",
                'options' => ['Necesitaban hacer ejercicio', 'Les gusta la lluvia', 'No tenían paraguas', 'Querían mojarse'],
                'correct_answer' => 'Necesitaban hacer ejercicio',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Muchos jóvenes prefieren vivir en la ciudad porque hay más oportunidades de trabajo, aunque el coste de vida es más alto.' ¿Cuál es la desventaja mencionada?",
                'options' => ['El coste de vida es más alto', 'No hay trabajo', 'La ciudad es aburrida', 'Hay poco transporte'],
                'correct_answer' => 'El coste de vida es más alto',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Si tuviera más tiempo, aprendería a tocar el piano.' ¿Qué le impide aprender piano?",
                'options' => ['La falta de tiempo', 'La falta de dinero', 'La falta de interés', 'La falta de un piano'],
                'correct_answer' => 'La falta de tiempo',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Cuando era niño, solía pasar los veranos en casa de mis abuelos, donde aprendí a pescar y a cocinar platos tradicionales.' ¿Qué aprendió de niño?",
                'options' => ['A pescar y cocinar', 'A nadar y bailar', 'A leer y escribir', 'A conducir'],
                'correct_answer' => 'A pescar y cocinar',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Es importante que los estudiantes practiquen todos los días, aunque sea solo diez minutos, para no perder lo aprendido.' ¿Qué se recomienda?",
                'options' => ['Practicar diariamente aunque sea poco tiempo', 'Estudiar solo los fines de semana', 'Practicar una vez al mes', 'No es necesario practicar'],
                'correct_answer' => 'Practicar diariamente aunque sea poco tiempo',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'A pesar de las dificultades económicas, la empresa logró aumentar sus ventas gracias a una nueva estrategia de marketing.' ¿Cómo logró aumentar las ventas la empresa?",
                'options' => ['Con una nueva estrategia de marketing', 'Bajando los precios', 'Cerrando tiendas', 'Despidiendo empleados'],
                'correct_answer' => 'Con una nueva estrategia de marketing',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'El cambio climático plantea desafíos sin precedentes que requieren una cooperación internacional sin fisuras.' ¿Qué se necesita para enfrentar el cambio climático según el texto?",
                'options' => ['Cooperación internacional', 'Más fábricas', 'Menos regulaciones', 'Turismo espacial'],
                'correct_answer' => 'Cooperación internacional',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'Si bien la tecnología ha simplificado muchas tareas cotidianas, también ha generado una dependencia que algunos consideran preocupante.' ¿Cuál es la preocupación mencionada?",
                'options' => ['La dependencia de la tecnología', 'El coste de la tecnología', 'La falta de tecnología', 'La velocidad de internet'],
                'correct_answer' => 'La dependencia de la tecnología',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Reading,
                'prompt' => "Lee: 'No cabe duda de que la educación bilingüe ofrece ventajas cognitivas, aunque su implementación exige recursos considerables.' ¿Qué exige la educación bilingüe?",
                'options' => ['Recursos considerables', 'Poco esfuerzo', 'Ningún recurso', 'Solo un idioma'],
                'correct_answer' => 'Recursos considerables',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Listening
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Buenos días, ¿cómo está usted?' ¿Qué se pregunta?",
                'options' => ['How are you (formal)', 'What is your name', 'Where are you from', 'How old are you'],
                'correct_answer' => 'How are you (formal)',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'La puerta de embarque es la número doce.' ¿Qué número se menciona?",
                'options' => ['Twelve', 'Two', 'Twenty', 'Twenty-two'],
                'correct_answer' => 'Twelve',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Quisiera una habitación para dos noches.' ¿Qué se pide?",
                'options' => ['A room for two nights', 'A table for two people', 'A ticket for two people', 'A discount for two nights'],
                'correct_answer' => 'A room for two nights',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Gire a la izquierda en la esquina.' ¿Qué dirección se da?",
                'options' => ['Turn left at the corner', 'Turn right at the corner', 'Go straight ahead', 'Stop at the corner'],
                'correct_answer' => 'Turn left at the corner',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Normalmente me levanto a las siete y media.' ¿A qué hora se levanta normalmente?",
                'options' => ['7:30', '7:00', '8:30', '6:30'],
                'correct_answer' => '7:30',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'El tren sale del andén tres a las diez y cuarto.' ¿De qué andén sale el tren?",
                'options' => ['Tres', 'Uno', 'Diez', 'Cuatro'],
                'correct_answer' => 'Tres',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Necesito una mesa para cuatro personas, por favor.' ¿Para cuántas personas es la mesa?",
                'options' => ['Cuatro', 'Dos', 'Tres', 'Cinco'],
                'correct_answer' => 'Cuatro',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'La reunión empieza a las nueve en punto, no llegues tarde.' ¿A qué hora empieza la reunión?",
                'options' => ['A las nueve', 'A las diez', 'A las ocho', 'A las nueve y media'],
                'correct_answer' => 'A las nueve',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'El fin de semana pasado visité a mis padres y comimos juntos.' ¿Qué hizo el fin de semana pasado?",
                'options' => ['Visitó a sus padres', 'Fue de viaje', 'Trabajó todo el día', 'Se quedó en casa solo'],
                'correct_answer' => 'Visitó a sus padres',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Voy a empezar un curso de inglés la próxima semana porque lo necesito para mi trabajo.' ¿Por qué va a estudiar inglés?",
                'options' => ['Lo necesita para su trabajo', 'Le gusta viajar', 'Es un hobby', 'Se lo pidió un amigo'],
                'correct_answer' => 'Lo necesita para su trabajo',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Prefiero el café sin azúcar, pero con un poco de leche.' ¿Cómo prefiere el café?",
                'options' => ['Sin azúcar, con leche', 'Con azúcar, sin leche', 'Sin azúcar ni leche', 'Con azúcar y leche'],
                'correct_answer' => 'Sin azúcar, con leche',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Aunque el vuelo se retrasó dos horas, llegamos a tiempo para la conferencia.' ¿Qué pasó con el vuelo?",
                'options' => ['Se retrasó dos horas', 'Se canceló', 'Llegó antes', 'No hubo problemas'],
                'correct_answer' => 'Se retrasó dos horas',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Si el tiempo lo permite, haremos la excursión el sábado por la mañana.' ¿Cuándo harían la excursión?",
                'options' => ['El sábado por la mañana', 'El domingo', 'El viernes', 'El sábado por la noche'],
                'correct_answer' => 'El sábado por la mañana',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Me gustaría cambiar de trabajo, pero todavía no he encontrado nada mejor.' ¿Por qué no ha cambiado de trabajo?",
                'options' => ['No ha encontrado nada mejor', 'Le gusta mucho su trabajo actual', 'Le pagan muy bien', 'No quiere cambiar'],
                'correct_answer' => 'No ha encontrado nada mejor',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Cuando vivíamos en el pueblo, todos nos conocíamos y nos ayudábamos mucho más que en la ciudad.' ¿Qué diferencia menciona entre el pueblo y la ciudad?",
                'options' => ['En el pueblo se ayudaban más', 'En la ciudad hay más ayuda', 'No hay diferencia', 'El pueblo es más grande'],
                'correct_answer' => 'En el pueblo se ayudaban más',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Es probable que lleguemos tarde debido al tráfico, así que empiecen sin nosotros.' ¿Qué se pide?",
                'options' => ['Que empiecen sin ellos', 'Que los esperen', 'Que cancelen la reunión', 'Que cambien de lugar'],
                'correct_answer' => 'Que empiecen sin ellos',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Después de mucho pensarlo, decidí aceptar el trabajo en el extranjero, aunque significa dejar a mi familia por un tiempo.' ¿Qué decidió hacer?",
                'options' => ['Aceptar el trabajo en el extranjero', 'Rechazar el trabajo', 'Quedarse con su familia', 'Buscar otro trabajo'],
                'correct_answer' => 'Aceptar el trabajo en el extranjero',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'A pesar de las críticas iniciales, el proyecto resultó ser un éxito rotundo una vez implementado.' ¿Cómo resultó el proyecto al final?",
                'options' => ['Un éxito rotundo', 'Un fracaso', 'Algo mediocre', 'Cancelado'],
                'correct_answer' => 'Un éxito rotundo',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'Convendría replantear nuestra estrategia si queremos seguir siendo competitivos en este mercado tan cambiante.' ¿Qué se sugiere hacer?",
                'options' => ['Replantear la estrategia', 'Mantener todo igual', 'Cerrar el negocio', 'Ignorar el mercado'],
                'correct_answer' => 'Replantear la estrategia',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Listening,
                'prompt' => "Escuchas: 'No es que esté en desacuerdo con la propuesta, simplemente creo que necesitamos más datos antes de decidir.' ¿Cuál es la postura del hablante?",
                'options' => ['Quiere más datos antes de decidir', 'Está totalmente en desacuerdo', 'Está totalmente de acuerdo', 'No tiene opinión'],
                'correct_answer' => 'Quiere más datos antes de decidir',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Speaking
            [
                'skill' => Skill::Speaking,
                'prompt' => "Alguien pregunta '¿Cómo te llamas?' Te llamas Ana. ¿Qué respondes?",
                'options' => ['Me llamo Ana', 'Soy de Ana', 'Tengo Ana', 'Está Ana'],
                'correct_answer' => 'Me llamo Ana',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres pedir agua en un restaurante. ¿Qué dices?',
                'options' => ['Quisiera agua, por favor', 'Quisiera la cuenta, por favor', 'Quisiera una habitación, por favor', 'Quisiera un mapa, por favor'],
                'correct_answer' => 'Quisiera agua, por favor',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres preguntar si hay una habitación de hotel disponible. ¿Qué dices?',
                'options' => ['¿Hay una habitación disponible?', '¿Dónde está el baño?', '¿Cuánto cuesta el desayuno?', '¿A qué hora es la salida?'],
                'correct_answer' => '¿Hay una habitación disponible?',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres decir que tu hermana es mayor que tú. ¿Cuál es correcto?',
                'options' => ['Mi hermana es mayor que yo', 'Mi hermana está mayor que yo', 'Mi hermana es mayor que mí', 'Mi hermana soy mayor'],
                'correct_answer' => 'Mi hermana es mayor que yo',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => "Quieres decir 'I get up early every day.' ¿Cuál es correcto?",
                'options' => ['Me levanto temprano todos los días', 'Levanto me temprano todos los días', 'Me levanto temprano todo el día', 'Yo levanto temprano todos los días'],
                'correct_answer' => 'Me levanto temprano todos los días',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres preguntar el precio de una camiseta en una tienda. ¿Qué dices?',
                'options' => ['¿Cuánto cuesta esta camiseta?', '¿Dónde está el probador?', '¿Tienen descuento?', '¿A qué hora cierran?'],
                'correct_answer' => '¿Cuánto cuesta esta camiseta?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres pedir indicaciones para llegar a la estación de tren. ¿Qué dices?',
                'options' => ['¿Cómo llego a la estación de tren?', '¿Qué hora es?', '¿Cuánto cuesta el billete?', '¿De dónde eres?'],
                'correct_answer' => '¿Cómo llego a la estación de tren?',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres cancelar una reserva en un restaurante. ¿Qué dices?',
                'options' => ['Quisiera cancelar mi reserva', 'Quisiera hacer una reserva', 'Quisiera ver el menú', 'Quisiera pagar la cuenta'],
                'correct_answer' => 'Quisiera cancelar mi reserva',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres explicar por qué llegaste tarde al trabajo. ¿Cuál es correcto?',
                'options' => ['Llegué tarde porque perdí el autobús', 'Llego tarde porque pierdo el autobús', 'Llegaré tarde porque perderé el autobús', 'Llegaba tarde porque perdía el autobús'],
                'correct_answer' => 'Llegué tarde porque perdí el autobús',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres invitar a un amigo al cine este fin de semana. ¿Qué dices?',
                'options' => ['¿Quieres ir al cine conmigo este fin de semana?', 'Fui al cine el fin de semana pasado', 'Me gusta mucho el cine', 'El cine está cerrado'],
                'correct_answer' => '¿Quieres ir al cine conmigo este fin de semana?',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres describir tu rutina diaria. ¿Cuál es correcto?',
                'options' => ['Todos los días me despierto a las siete', 'Ayer me desperté a las siete', 'Mañana me despertaré a las siete', 'Nunca me despierto temprano'],
                'correct_answer' => 'Todos los días me despierto a las siete',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres dar tu opinión sobre una película que no te gustó. ¿Cuál es correcto?',
                'options' => ['No me gustó la película porque el argumento era muy lento', 'Me encantó la película', 'No he visto la película', 'La película dura dos horas'],
                'correct_answer' => 'No me gustó la película porque el argumento era muy lento',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres sugerir una alternativa a un plan que no te convence. ¿Qué dices?',
                'options' => ['¿Y si en vez de eso hacemos otra cosa?', 'Me parece perfecto el plan', 'No tengo ninguna opinión', 'Haz lo que quieras'],
                'correct_answer' => '¿Y si en vez de eso hacemos otra cosa?',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres expresar que estás de acuerdo parcialmente con alguien. ¿Cuál es correcto?',
                'options' => ['Estoy de acuerdo, pero creo que también hay que considerar el precio', 'Estoy totalmente de acuerdo', 'No estoy nada de acuerdo', 'No entiendo la pregunta'],
                'correct_answer' => 'Estoy de acuerdo, pero creo que también hay que considerar el precio',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres contar una experiencia pasada que cambió tu forma de pensar. ¿Cuál es correcto?',
                'options' => ['Cuando viví en el extranjero, aprendí a valorar mi cultura', 'Viviré en el extranjero algún día', 'Vivo en el extranjero ahora', 'Si vivo en el extranjero, aprenderé mucho'],
                'correct_answer' => 'Cuando viví en el extranjero, aprendí a valorar mi cultura',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres expresar una condición hipotética sobre el futuro. ¿Cuál es correcto?',
                'options' => ['Si tuviera más dinero, viajaría por el mundo', 'Si tengo más dinero, viajo por el mundo', 'Tengo más dinero y viajo', 'Tenía más dinero y viajaba'],
                'correct_answer' => 'Si tuviera más dinero, viajaría por el mundo',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres pedir disculpas formalmente por un error en el trabajo. ¿Cuál es correcto?',
                'options' => ['Lamento mucho el error, no volverá a suceder', 'Perdón, es que soy así', 'No fue mi culpa', 'Da igual, no importa'],
                'correct_answer' => 'Lamento mucho el error, no volverá a suceder',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres argumentar a favor de una postura en un debate. ¿Cuál es correcto?',
                'options' => ['Sostengo que la educación pública debería recibir más inversión', 'La educación pública es mala', 'No me importa la educación', 'Todos deberían pagar por estudiar'],
                'correct_answer' => 'Sostengo que la educación pública debería recibir más inversión',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres matizar una crítica para que no suene demasiado dura. ¿Cuál es correcto?',
                'options' => ['No es que el proyecto esté mal, pero creo que se podría mejorar en algunos aspectos', 'El proyecto es un desastre', 'El proyecto es perfecto', 'No tengo nada que decir'],
                'correct_answer' => 'No es que el proyecto esté mal, pero creo que se podría mejorar en algunos aspectos',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Speaking,
                'prompt' => 'Quieres expresar arrepentimiento sobre una decisión pasada. ¿Cuál es correcto?',
                'options' => ['Ojalá hubiera estudiado más cuando era joven', 'Ojalá estudie más', 'Estudié mucho de joven', 'Voy a estudiar más'],
                'correct_answer' => 'Ojalá hubiera estudiado más cuando era joven',
                'cefr_sublevel_tag' => 'B2',
            ],

            // Writing
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ella ___ profesora.'",
                'options' => ['es', 'está', 'soy', 'eres'],
                'correct_answer' => 'es',
                'cefr_sublevel_tag' => 'A1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'El hotel ___ cerca del aeropuerto.'",
                'options' => ['está', 'es', 'soy', 'son'],
                'correct_answer' => 'está',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Elige la forma correcta: 'la camisa ___' (roja)",
                'options' => ['roja', 'rojo', 'rojos', 'rojas'],
                'correct_answer' => 'roja',
                'cefr_sublevel_tag' => 'A1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nosotros ___ (comer) a las dos.'",
                'options' => ['comemos', 'comimos', 'come', 'comer'],
                'correct_answer' => 'comemos',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Mis padres se ___ Juan y María.' (llamarse)",
                'options' => ['llaman', 'llama', 'llamamos', 'llamas'],
                'correct_answer' => 'llaman',
                'cefr_sublevel_tag' => 'A1.3',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ayer ___ (ir) al mercado y compré fruta.'",
                'options' => ['fui', 'voy', 'iré', 'iba'],
                'correct_answer' => 'fui',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ella ___ (tener) dos hermanos.'",
                'options' => ['tiene', 'tengo', 'tienes', 'tenemos'],
                'correct_answer' => 'tiene',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Nosotros ___ (vivir) en Madrid desde hace tres años.'",
                'options' => ['vivimos', 'vivo', 'vive', 'viven'],
                'correct_answer' => 'vivimos',
                'cefr_sublevel_tag' => 'A2.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Cuando era pequeño, ___ (jugar) en el parque todos los días.'",
                'options' => ['jugaba', 'jugué', 'juego', 'jugaré'],
                'correct_answer' => 'jugaba',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'El próximo mes ___ (viajar) a Argentina.'",
                'options' => ['viajaré', 'viajo', 'viajaba', 'viajé'],
                'correct_answer' => 'viajaré',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Mientras cocinaba, mi hermano ___ (poner) la mesa.'",
                'options' => ['ponía', 'pone', 'puso', 'pondrá'],
                'correct_answer' => 'ponía',
                'cefr_sublevel_tag' => 'A2.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Si ___ (llover) mañana, no iremos a la playa.'",
                'options' => ['llueve', 'llovía', 'lloviera', 'llovió'],
                'correct_answer' => 'llueve',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ella dijo que ___ (llegar) tarde a la fiesta.'",
                'options' => ['llegaría', 'llega', 'llegó', 'llegue'],
                'correct_answer' => 'llegaría',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Es posible que ellos ya ___ (terminar) el proyecto.'",
                'options' => ['hayan terminado', 'han terminado', 'terminaron', 'terminarán'],
                'correct_answer' => 'hayan terminado',
                'cefr_sublevel_tag' => 'B1.1',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Si ___ (tener) más tiempo, aprendería a tocar la guitarra.'",
                'options' => ['tuviera', 'tengo', 'tuve', 'tendré'],
                'correct_answer' => 'tuviera',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Ojalá ___ (poder) venir a la boda el mes que viene.'",
                'options' => ['pueda', 'puedo', 'podré', 'pude'],
                'correct_answer' => 'pueda',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Aunque ___ (estar) cansado, terminé el informe anoche.'",
                'options' => ['estaba', 'esté', 'estuviera', 'estoy'],
                'correct_answer' => 'estaba',
                'cefr_sublevel_tag' => 'B1.2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'De haber sabido la verdad, no ___ (actuar) de esa manera.'",
                'options' => ['habría actuado', 'actuaría', 'actué', 'actuaba'],
                'correct_answer' => 'habría actuado',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'No creo que la situación ___ (mejorar) sin un cambio real de política.'",
                'options' => ['mejore', 'mejora', 'mejoró', 'mejorará'],
                'correct_answer' => 'mejore',
                'cefr_sublevel_tag' => 'B2',
            ],
            [
                'skill' => Skill::Writing,
                'prompt' => "Completa: 'Por más que lo ___ (intentar), no logró convencerlos.'",
                'options' => ['intentara', 'intenta', 'intentó', 'intentaba'],
                'correct_answer' => 'intentara',
                'cefr_sublevel_tag' => 'B2',
            ],
        ];
    }
}
