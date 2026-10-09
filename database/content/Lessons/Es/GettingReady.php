<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Es;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class GettingReady implements UnitContent
{
    public function languageCode(): string
    {
        return 'es';
    }

    public function unitSlug(): string
    {
        return 'getting-ready';
    }

    public function words(): array
    {
        return [
            new WordData('vestirse', cue: 'to get dressed', forms: ['me visto', 'te vistes', 'se viste', 'nos vestimos', 'se visten'], note: 'Vestirse changes e to i in most forms (me visto, te vistes, se viste, se visten), but not in nos vestimos.'),
            new WordData('ponerse', cue: 'to put on (clothes)', forms: ['me pongo', 'te pones', 'se pone', 'nos ponemos', 'se ponen'], note: 'Ponerse la chaqueta is to put on a jacket. Poner la mesa, with no pronoun, is to set the table.'),
            new WordData('quitarse', cue: 'to take off (clothes)', forms: ['me quito', 'te quitas', 'se quita', 'nos quitamos', 'se quitan']),
            new WordData('peinarse', cue: 'to comb your hair', forms: ['me peino', 'te peinas', 'se peina', 'nos peinamos', 'se peinan']),
            new WordData('cepillarse', cue: 'to brush (your teeth or hair)', forms: ['me cepillo', 'te cepillas', 'se cepilla', 'nos cepillamos', 'se cepillan']),
            new WordData('maquillarse', cue: 'to put on make-up', forms: ['me maquillo', 'te maquillas', 'se maquilla', 'nos maquillamos', 'se maquillan']),
            new WordData('el espejo', cue: 'mirror'),
            new WordData('los dientes', cue: 'teeth', accepted: ['el diente'], note: 'Spanish uses the article with teeth and clothes: me cepillo los dientes, not mis dientes.'),
            new WordData('los zapatos', cue: 'shoes', accepted: ['el zapato']),
            new WordData('la chaqueta', cue: 'jacket'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Me visto y me peino.', 'english' => 'I get dressed and I comb my hair.'],
            ['text' => 'Ana se pone la chaqueta.', 'english' => 'Ana puts on her jacket.'],
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
        return [];
    }

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.yo-pongo', 'Yo ___ la camisa.', ['me pongo', 'te pones', 'se pone'], 'me pongo', Kit::form('me pongo'), 'Yo goes with me: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.ana-pone', 'Ana ___ los zapatos.', ['se pone', 'me pongo', 'se ponen'], 'se pone', Kit::form('se pone'), 'Ana is one person, so se pone. Se ponen is for more than one.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.tu-vistes', 'Tú ___ a las ocho.', ['te vistes', 'me vistes', 'se vistes'], 'te vistes', Kit::form('te vistes'), 'Tú goes with te: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.nosotros-cepillamos', 'Nosotros ___ los dientes.', ['nos cepillamos', 'nos cepillo', 'me cepillamos'], 'nos cepillamos', Kit::form('nos cepillamos'), 'Nosotros goes with nos: the pronoun matches the person.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.yo-pongo-mesa', 'Yo ___ la mesa.', ['pongo', 'me pongo'], 'pongo', Kit::form('pongo', true), 'Poner la mesa is to set the table, and the table is not you, so there is no me. Me pongo is for putting on something yourself.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.pablo-desayuna', 'Pablo ___ a las ocho.', ['desayuna', 'se desayuna'], 'desayuna', Kit::form('desayuna', true), 'Desayunar is not reflexive, so there is no se. Compare se viste and se peina.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.me-pongo', '___ la chaqueta.', 'I put on my jacket.', 'Me pongo', Kit::form('me pongo'), 'Yo goes with me: me pongo.'),
            Kit::typeGap($stage, 'sentences.type_gap.se-cepilla', 'Ella ___ los dientes.', 'She brushes her teeth.', 'se cepilla', Kit::form('se cepilla'), 'Ella goes with se: se cepilla.'),
            Kit::typeGap($stage, 'sentences.type_gap.me-visto', 'Yo ___ a las siete.', 'I get dressed at seven.', 'me visto', Kit::form('me visto'), 'Yo goes with me: me visto.'),
            Kit::typeGap($stage, 'sentences.type_gap.se-maquillan', 'Ellos ___ en el dormitorio.', 'They put on make-up in the bedroom.', 'se maquillan', Kit::form('se maquillan'), 'Ellos goes with se, and the ending -n shows more than one person: se maquillan.'),
            Kit::typeGap($stage, 'sentences.type_gap.te-quitas', 'Tú ___ los zapatos.', 'You take off your shoes.', 'te quitas', Kit::form('te quitas'), 'Tú goes with te: te quitas.'),

            Kit::translate($stage, 'sentences.translate.ana-pone', 'Ana puts on her jacket.', ['Ana se pone la chaqueta.', 'Se pone la chaqueta.'], [Kit::word('ponerse', 'se pone'), Kit::word('la chaqueta'), Kit::form('se pone')]),
            Kit::translate($stage, 'sentences.translate.nos-quitamos', 'We take off our shoes.', ['Nos quitamos los zapatos.', 'Nosotros nos quitamos los zapatos.'], [Kit::word('quitarse', 'nos quitamos'), Kit::word('los zapatos'), Kit::form('nos quitamos')]),
            Kit::translate($stage, 'sentences.translate.marta-maquilla', 'Marta puts on make-up at seven.', ['Marta se maquilla a las siete.', 'Se maquilla a las siete.'], [Kit::word('maquillarse', 'se maquilla'), Kit::form('se maquilla')]),

            Kit::build($stage, 'sentences.build.pablo-viste', 'Pablo gets dressed at eight.', 'Pablo se viste a las ocho.', ['me'], [Kit::word('vestirse', 'se viste'), Kit::form('se viste')]),
            Kit::build($stage, 'sentences.build.espejo', 'There is a mirror in the bedroom.', 'Hay un espejo en el dormitorio.', ['una'], [Kit::word('el espejo', 'espejo')]),
            Kit::build($stage, 'sentences.build.cepillo-peino', 'I brush my teeth and I comb my hair.', 'Me cepillo los dientes y me peino.', ['se'], [Kit::word('cepillarse', 'me cepillo'), Kit::word('los dientes'), Kit::word('peinarse', 'me peino'), Kit::form('me peino')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.zapatos-chaqueta', 'Me pongo los zapatos y la chaqueta.', ['I put on my shoes and my jacket.', 'I take off my shoes and my jacket.', 'She puts on her shoes and her jacket.', 'I put on my shirt and my jacket.'], 'I put on my shoes and my jacket.', [Kit::word('ponerse', 'me pongo'), Kit::word('los zapatos'), Kit::word('la chaqueta'), Kit::form('me pongo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.peina-maquilla', 'Ana se peina y se maquilla.', ['Ana combs her hair and puts on make-up.', 'Ana combs her hair and brushes her teeth.', 'Ana gets dressed and puts on make-up.', 'I comb my hair and put on make-up.'], 'Ana combs her hair and puts on make-up.', [Kit::word('peinarse', 'se peina'), Kit::word('maquillarse', 'se maquilla'), Kit::form('se peina')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.vestimos-cepillamos', 'Nos vestimos y nos cepillamos los dientes.', ['We get dressed and brush our teeth.', 'We get dressed and comb our hair.', 'They get dressed and brush their teeth.', 'We take off our clothes and brush our teeth.'], 'We get dressed and brush our teeth.', [Kit::word('vestirse', 'nos vestimos'), Kit::word('cepillarse', 'nos cepillamos'), Kit::word('los dientes'), Kit::form('nos vestimos')]),
            Kit::listenType($stage, 'sentences.listen_type.te-quitas', 'Te quitas la chaqueta.', 'You take off your jacket.', [Kit::word('quitarse', 'te quitas'), Kit::word('la chaqueta'), Kit::form('te quitas')]),
            Kit::listenType($stage, 'sentences.listen_type.se-visten', 'Ellos se visten en el dormitorio.', 'They get dressed in the bedroom.', [Kit::word('vestirse', 'se visten'), Kit::form('se visten')]),
            Kit::listenType($stage, 'sentences.listen_type.se-cepilla', 'Pablo se cepilla los dientes.', 'Pablo brushes his teeth.', [Kit::word('cepillarse', 'se cepilla'), Kit::word('los dientes'), Kit::form('se cepilla')]),
            Kit::listenType($stage, 'sentences.listen_type.espejo', 'El espejo está en el salón.', 'The mirror is in the living room.', [Kit::word('el espejo', 'espejo')]),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.normalmente-cepillo', 'Normalmente me cepillo los dientes.', 'I normally brush my teeth.', [Kit::word('cepillarse', 'me cepillo'), Kit::word('los dientes'), Kit::form('me cepillo')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.se-pone', 'Se pone la chaqueta y los zapatos.', 'She puts on her jacket and her shoes.', [Kit::word('ponerse', 'se pone'), Kit::word('la chaqueta'), Kit::word('los zapatos'), Kit::form('se pone')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.nos-vestimos', 'Nos vestimos en el dormitorio.', 'We get dressed in the bedroom.', [Kit::word('vestirse', 'nos vestimos'), Kit::form('nos vestimos')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.espejo', 'Hay un espejo en el salón.', 'There is a mirror in the living room.', [Kit::word('el espejo', 'espejo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.peinas', '¿Te peinas todos los días?', 'Do you comb your hair every day?', [['peino', 'días', 'todos', 'cada']], 'Sí, me peino todos los días.', [Kit::word('peinarse', 'me peino'), Kit::form('me peino')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.cepillas', '¿Te cepillas los dientes?', 'Do you brush your teeth?', [['cepillo', 'dientes']], 'Sí, me cepillo los dientes.', [Kit::word('cepillarse', 'me cepillo'), Kit::word('los dientes'), Kit::form('me cepillo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.pones', '¿Te pones los zapatos?', 'Do you put on your shoes?', [['pongo', 'zapatos']], 'Sí, me pongo los zapatos.', [Kit::word('ponerse', 'me pongo'), Kit::word('los zapatos'), Kit::form('me pongo')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.fiesta', 'Read the conversation about getting ready for a party.', [
                Kit::line('Ana', 'Hola, Marta. ¿Qué te pones para la fiesta?'),
                Kit::line('Marta', 'Me pongo la camisa y los zapatos. ¿Y tú?'),
                Kit::line('Ana', 'Yo me pongo la chaqueta. Me peino y me maquillo en el dormitorio.'),
                Kit::line('Marta', 'Hay un espejo en el salón.'),
                Kit::line('Ana', 'Gracias. Me visto a las siete.'),
                Kit::line('Marta', 'Muy bien, salimos a las ocho.'),
            ], [
                Kit::question('What does Marta put on for the party?', ['A shirt and shoes', 'A jacket and shoes', 'A jacket'], 'A shirt and shoes'),
                Kit::question('Who puts on make-up?', ['Marta', 'Ana', 'Both of them'], 'Ana'),
                Kit::question('When do they go out?', ['At seven', 'At eight', 'At nine'], 'At eight'),
            ], [Kit::word('ponerse', 'me pongo'), Kit::word('los zapatos'), Kit::word('la chaqueta'), Kit::word('peinarse', 'me peino'), Kit::word('maquillarse', 'me maquillo'), Kit::word('el espejo', 'espejo'), Kit::word('vestirse', 'me visto')]),
            Kit::gap($stage, 'task.choose_gap.calor', 'Tengo calor. Me ___ la chaqueta.', ['quito', 'pongo'], 'quito', Kit::word('quitarse', 'me quito'), 'When you are hot you take off your jacket: me quito. Me pongo is to put it on.', 'read', 'I am hot. I take off my jacket.'),
            Kit::gap($stage, 'task.choose_gap.espejo', 'Ana se peina en el dormitorio. Hay un ___ allí.', ['espejo', 'dientes'], 'espejo', Kit::word('el espejo', 'espejo'), 'You look in a mirror to comb your hair: un espejo. Dientes are teeth.', 'read', 'Ana combs her hair in the bedroom. There is a mirror there.'),

            Kit::transform($stage, 'task.transform.ella', 'Change the subject to she.', 'Me pongo la chaqueta.', ['Ella se pone la chaqueta.', 'Se pone la chaqueta.'], [Kit::word('ponerse', 'se pone'), Kit::word('la chaqueta'), Kit::form('se pone')]),
            Kit::transform($stage, 'task.transform.nosotros', 'Change the subject to we.', 'Me cepillo los dientes.', ['Nos cepillamos los dientes.', 'Nosotros nos cepillamos los dientes.'], [Kit::word('cepillarse', 'nos cepillamos'), Kit::word('los dientes'), Kit::form('nos cepillamos')]),
            Kit::transform($stage, 'task.transform.tu', 'Change the subject to you (tú).', 'Me visto temprano.', ['Te vistes temprano.', 'Tú te vistes temprano.'], [Kit::word('vestirse', 'te vistes'), Kit::form('te vistes')]),
            Kit::writeGuided($stage, 'task.write_guided.manana', 'Describe how you get ready. Use the words get dressed, brush and comb.', ['me visto', 'me cepillo', 'me peino'], 'Me visto, me cepillo los dientes y me peino.', [
                ['forms' => ['visto', 'vestimos', 'viste', 'visten'], 'term' => 'vestirse'],
                ['forms' => ['cepillo', 'cepillamos', 'cepilla', 'cepillan'], 'term' => 'cepillarse'],
                ['forms' => ['peino', 'peinamos', 'peina', 'peinan'], 'term' => 'peinarse'],
            ], [Kit::word('vestirse', 'me visto'), Kit::word('cepillarse', 'me cepillo'), Kit::word('peinarse', 'me peino')]),
            Kit::writeGuided($stage, 'task.write_guided.fiesta', 'Say what you put on for a party. Use the words jacket and shoes.', ['me pongo', 'la chaqueta', 'los zapatos'], 'Me pongo la chaqueta y los zapatos.', [
                ['forms' => ['pongo', 'ponemos', 'pone', 'ponen'], 'term' => 'ponerse'],
                ['forms' => ['chaqueta'], 'term' => 'la chaqueta'],
                ['forms' => ['zapatos'], 'term' => 'los zapatos'],
            ], [Kit::word('ponerse', 'me pongo'), Kit::word('la chaqueta'), Kit::word('los zapatos')]),
            Kit::build($stage, 'task.build.maquillan-peinan', 'They put on make-up and comb their hair.', 'Ellos se maquillan y se peinan.', ['me', 'peina'], [Kit::word('maquillarse', 'se maquillan'), Kit::word('peinarse', 'se peinan'), Kit::form('se peinan')], 'write'),
            Kit::build($stage, 'task.build.luis-quita', 'Luis takes off his shoes at home.', 'Luis se quita los zapatos en casa.', ['me', 'quita'], [Kit::word('quitarse', 'se quita'), Kit::word('los zapatos'), Kit::form('se quita')], 'write'),
            Kit::build($stage, 'task.build.visto-cepillo', 'I get dressed and I brush my teeth.', 'Me visto y me cepillo los dientes.', ['se', 'visten'], [Kit::word('vestirse', 'me visto'), Kit::word('cepillarse', 'me cepillo'), Kit::word('los dientes'), Kit::form('me cepillo')], 'write'),
            Kit::translate($stage, 'task.translate.fiesta', 'For the party I put on my jacket and my shoes.', ['Para la fiesta me pongo la chaqueta y los zapatos.', 'Me pongo la chaqueta y los zapatos para la fiesta.', 'Para la fiesta yo me pongo la chaqueta y los zapatos.'], [Kit::word('ponerse', 'me pongo'), Kit::word('la chaqueta'), Kit::word('los zapatos'), Kit::form('me pongo')], 'write'),
            Kit::translate($stage, 'task.translate.pone-mesa', 'Pablo sets the table and gets dressed.', ['Pablo pone la mesa y se viste.', 'Pone la mesa y se viste.'], [Kit::word('vestirse', 'se viste'), Kit::form('pone', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.cena', [
                Kit::line('Luis', '¿Qué te pones para la cena, Marta?'),
                Kit::line('Marta', 'Yo me pongo la chaqueta y los zapatos. ¿Y tú?'),
                Kit::line('Luis', 'Yo me pongo la camisa. Me peino en el dormitorio.'),
                Kit::line('Marta', 'Yo me maquillo en el salón. Salimos a las ocho.'),
            ], [
                Kit::question('What does Marta put on?', ['A shirt', 'A jacket and shoes', 'A jacket and a shirt'], 'A jacket and shoes'),
                Kit::question('Where does Luis comb his hair?', ['In the bedroom', 'In the living room', 'At the party'], 'In the bedroom'),
                Kit::question('When do they go out?', ['At seven', 'At eight', 'At nine'], 'At eight'),
            ], [
                Kit::question('Who puts on make-up?', ['Marta', 'Luis', 'Both of them'], 'Marta'),
                Kit::question('What does Luis put on?', ['A shirt', 'A jacket', 'Shoes'], 'A shirt'),
                Kit::question('What are they getting ready for?', ['A party', 'A dinner', 'The cinema'], 'A dinner'),
            ], [Kit::word('ponerse', 'me pongo'), Kit::word('la chaqueta'), Kit::word('los zapatos'), Kit::word('peinarse', 'me peino'), Kit::word('maquillarse', 'me maquillo')]),
            Kit::listenType($stage, 'task.listen_type.nos-quitamos', 'Nos quitamos los zapatos en casa.', 'We take off our shoes at home.', [Kit::word('quitarse', 'nos quitamos'), Kit::word('los zapatos'), Kit::form('nos quitamos')]),
            Kit::listenType($stage, 'task.listen_type.se-peina', 'Marta se peina y se pone la chaqueta.', 'Marta combs her hair and puts on her jacket.', [Kit::word('peinarse', 'se peina'), Kit::word('ponerse', 'se pone'), Kit::word('la chaqueta'), Kit::form('se pone')]),
            Kit::listenType($stage, 'task.listen_type.te-vistes', 'Tú te cepillas los dientes y te vistes.', 'You brush your teeth and get dressed.', [Kit::word('cepillarse', 'te cepillas'), Kit::word('los dientes'), Kit::word('vestirse', 'te vistes'), Kit::form('te vistes')]),

            Kit::speakAnswer($stage, 'task.speak_answer.fiesta', '¿Qué te pones para una fiesta?', 'What do you put on for a party?', [['pongo', 'camisa', 'chaqueta', 'zapatos', 'pantalones', 'ropa']], 'Me pongo la camisa y los zapatos.', [Kit::word('ponerse', 'me pongo'), Kit::form('me pongo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.vistes', '¿Cuándo te vistes?', 'When do you get dressed?', [['visto', 'seis', 'siete', 'ocho', 'nueve', 'temprano', 'tarde']], 'Me visto a las ocho.', [Kit::word('vestirse', 'me visto'), Kit::form('me visto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.maquillas', '¿Te maquillas todos los días?', 'Do you put on make-up every day?', [['maquillo', 'días', 'todos', 'cada']], 'Sí, me maquillo todos los días.', [Kit::word('maquillarse', 'me maquillo')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.peinas', '¿Dónde te peinas?', 'Where do you comb your hair?', [['peino', 'dormitorio', 'salón', 'casa', 'espejo']], 'Me peino en el dormitorio.', [Kit::word('peinarse', 'me peino'), Kit::form('me peino')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.quito', 'Me quito la chaqueta en casa.', 'I take off my jacket at home.', [Kit::word('quitarse', 'me quito'), Kit::word('la chaqueta'), Kit::form('me quito')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.espejo', 'El espejo está en el dormitorio.', 'The mirror is in the bedroom.', [Kit::word('el espejo', 'espejo')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.cepilla-peina', 'She brushes her teeth and combs her hair.', ['Ella se cepilla los dientes y se peina.', 'Se cepilla los dientes y se peina.'], [Kit::word('cepillarse', 'se cepilla'), Kit::word('los dientes'), Kit::word('peinarse', 'se peina'), Kit::form('se cepilla')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vestimos-ponemos', 'We get dressed and put on our shoes.', ['Nos vestimos y nos ponemos los zapatos.', 'Nosotros nos vestimos y nos ponemos los zapatos.'], [Kit::word('vestirse', 'nos vestimos'), Kit::word('ponerse', 'nos ponemos'), Kit::word('los zapatos'), Kit::form('nos ponemos')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pone-quita', 'Pablo sets the table and takes off his jacket.', ['Pablo pone la mesa y se quita la chaqueta.', 'Pone la mesa y se quita la chaqueta.'], [Kit::word('quitarse', 'se quita'), Kit::word('la chaqueta'), Kit::form('pone', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.maquilla', 'Ana puts on make-up. There is a mirror in the bedroom.', ['Ana se maquilla. Hay un espejo en el dormitorio.', 'Se maquilla. Hay un espejo en el dormitorio.'], [Kit::word('maquillarse', 'se maquilla'), Kit::word('el espejo', 'espejo'), Kit::form('se maquilla')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pones', 'Tú ___ la chaqueta.', 'You put on your jacket.', 'te pones', Kit::form('te pones'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.pone-mesa', 'Ella ___ la mesa.', 'She sets the table.', 'pone', Kit::form('pone', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.visto-peino', 'Me visto y me peino todos los días.', 'I get dressed and I comb my hair every day.', [Kit::word('vestirse', 'me visto'), Kit::word('peinarse', 'me peino')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.cepillas-quitas', 'Te cepillas los dientes y te quitas los zapatos.', 'You brush your teeth and take off your shoes.', [Kit::word('cepillarse', 'te cepillas'), Kit::word('los dientes'), Kit::word('quitarse', 'te quitas'), Kit::word('los zapatos')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.se-ponen', 'Ellos se ponen la chaqueta.', 'They put on their jackets.', [Kit::word('ponerse', 'se ponen'), Kit::word('la chaqueta')], 'dictation', $set),
            Kit::listenPassage($stage, 'check.a.listen_passage.cine', [
                Kit::line('Ana', 'Hola, Luis. ¿Qué te pones para el cine?'),
                Kit::line('Luis', 'Me pongo la camisa y los pantalones. Me peino en casa.'),
                Kit::line('Ana', 'Yo me visto a las ocho.'),
            ], [
                Kit::question('What does Luis put on?', ['A shirt and trousers', 'A jacket and shoes', 'A shirt and shoes'], 'A shirt and trousers'),
                Kit::question('Where does Luis comb his hair?', ['At home', 'At the cinema', 'In the shop'], 'At home'),
                Kit::question('When does Ana get dressed?', ['At seven', 'At eight', 'At nine'], 'At eight'),
            ], [
                Kit::question('Who asks the question?', ['Ana', 'Luis', 'Nobody'], 'Ana'),
                Kit::question('Where are they going?', ['To the cinema', 'To a party', 'To work'], 'To the cinema'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('ponerse', 'me pongo'), Kit::word('peinarse', 'me peino'), Kit::word('vestirse', 'me visto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.fiesta', 'Read the conversation.', [
                Kit::line('Luis', 'Ana, ¿te maquillas para la fiesta?'),
                Kit::line('Ana', 'Sí, me maquillo y me peino. Tengo un espejo en el dormitorio.'),
                Kit::line('Luis', 'Yo me pongo la chaqueta y salimos.'),
            ], [
                Kit::question('Who puts on make-up?', ['Luis', 'Ana', 'The text does not say.'], 'Ana'),
                Kit::question('Where is Ana\'s mirror?', ['In the living room', 'In the bedroom', 'At the party'], 'In the bedroom'),
            ], [Kit::word('maquillarse', 'me maquillo'), Kit::word('peinarse', 'me peino'), Kit::word('el espejo', 'espejo')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.quitas', '¿Te quitas los zapatos en casa?', 'Do you take off your shoes at home?', [['quito', 'zapatos', 'casa']], 'Sí, me quito los zapatos.', [Kit::word('quitarse', 'me quito'), Kit::word('los zapatos')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.pones', '¿Te pones la chaqueta?', 'Do you put on a jacket?', [['pongo', 'chaqueta']], 'Sí, me pongo la chaqueta.', [Kit::word('ponerse', 'me pongo'), Kit::word('la chaqueta')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.vistes', '¿Dónde te vistes?', 'Where do you get dressed?', [['visto', 'casa', 'dormitorio', 'salón']], 'Me visto en el dormitorio.', [Kit::word('vestirse', 'me visto')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.viste-peina', 'He gets dressed and combs his hair.', ['Se viste y se peina.', 'Él se viste y se peina.'], [Kit::word('vestirse', 'se viste'), Kit::word('peinarse', 'se peina'), Kit::form('se viste')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.pongo', 'I put on my jacket and my shoes.', ['Me pongo la chaqueta y los zapatos.', 'Yo me pongo la chaqueta y los zapatos.'], [Kit::word('ponerse', 'me pongo'), Kit::word('la chaqueta'), Kit::word('los zapatos'), Kit::form('me pongo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.marta-cepilla', 'Marta brushes her teeth and puts on make-up.', ['Marta se cepilla los dientes y se maquilla.', 'Se cepilla los dientes y se maquilla.'], [Kit::word('cepillarse', 'se cepilla'), Kit::word('los dientes'), Kit::word('maquillarse', 'se maquilla'), Kit::form('se cepilla')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.ponemos-mesa', 'We set the table and take off our jackets.', ['Ponemos la mesa y nos quitamos la chaqueta.', 'Nosotros ponemos la mesa y nos quitamos la chaqueta.'], [Kit::word('quitarse', 'nos quitamos'), Kit::word('la chaqueta'), Kit::form('ponemos', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.visten', 'Ellos ___ temprano.', 'They get dressed early.', 'se visten', Kit::form('se visten'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.pones-mesa', 'Tú ___ la mesa.', 'You set the table.', 'pones', Kit::form('pones', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.visto-peino', 'Me visto y me peino. El espejo está en casa.', 'I get dressed and I comb my hair. The mirror is at home.', [Kit::word('vestirse', 'me visto'), Kit::word('peinarse', 'me peino'), Kit::word('el espejo', 'espejo')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.pones-cepillas', 'Te pones los zapatos y te cepillas los dientes.', 'You put on your shoes and brush your teeth.', [Kit::word('ponerse', 'te pones'), Kit::word('los zapatos'), Kit::word('cepillarse', 'te cepillas'), Kit::word('los dientes')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.quita-maquilla', 'Ana se quita la chaqueta y se maquilla. El espejo es caro.', 'Ana takes off her jacket and puts on make-up. The mirror is expensive.', [Kit::word('quitarse', 'se quita'), Kit::word('maquillarse', 'se maquilla'), Kit::word('el espejo', 'espejo')], 'dictation', $set),
        ];
    }
}
