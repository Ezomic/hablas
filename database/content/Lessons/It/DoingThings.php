<?php

declare(strict_types=1);

namespace Database\Content\Lessons\It;

use App\Enums\LessonStage as Stage;
use App\Enums\ReviewKind;
use App\Enums\ReviewScope;
use App\Lessons\AuthoredExercise;
use App\Lessons\ContentReview;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class DoingThings implements UnitContent
{
    private const A_NOTE = 'A (to, at) sounds the same as ha (has), which is written with an h. Here it is the preposition a.';

    private const E_NOTE = 'E (and) sounds close to è (is); the accent is the written difference. Here it is plain e, meaning and.';

    private const EGRAVE_NOTE = 'È (is) is written with an accent. It sounds close to e (and). Here it is è, the verb is.';

    private const DA_NOTE = 'Dà (gives) is written with an accent. Da without an accent means from or by, but here it is dà.';

    private const LI_NOTE = 'Lì (there) is written with an accent. Here it is lì, meaning there.';

    public function languageCode(): string
    {
        return 'it';
    }

    public function unitSlug(): string
    {
        return 'doing-things';
    }

    public function words(): array
    {
        return [
            new WordData('fare', cue: 'to do / to make', forms: ['faccio', 'fai', 'fa', 'facciamo', 'fate', 'fanno']),
            new WordData('vedere', cue: 'to see', forms: ['vedo', 'vedi', 'vede', 'vediamo', 'vedete', 'vedono']),
            new WordData('venire', cue: 'to come', forms: ['vengo', 'vieni', 'viene', 'veniamo', 'venite', 'vengono']),
            new WordData('sapere', cue: 'to know (a fact)', forms: ['so', 'sai', 'sa', 'sappiamo', 'sapete', 'sanno'], note: 'Sapere is to know a fact: Non so dove è Anna.'),
            new WordData('uscire', cue: 'to leave / to go out', forms: ['esco', 'esci', 'esce', 'usciamo', 'uscite', 'escono']),
            new WordData('mettere', cue: 'to put', forms: ['metto', 'metti', 'mette', 'mettiamo', 'mettete', 'mettono']),
            new WordData('dire', cue: 'to say / to tell', forms: ['dico', 'dici', 'dice', 'diciamo', 'dite', 'dicono']),
            new WordData('dare', cue: 'to give', forms: ['do', 'dai', 'dà', 'diamo', 'date', 'danno']),
            new WordData('portare', cue: 'to bring', forms: ['porto', 'porti', 'porta', 'portiamo', 'portate', 'portano']),
            new WordData('sentire', cue: 'to hear', forms: ['sento', 'senti', 'sente', 'sentiamo', 'sentite', 'sentono'], note: 'Sentire is to hear (and also to feel). To listen to something on purpose is ascoltare.'),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'Esco alle otto.', 'english' => 'I leave at eight.'],
            ['text' => 'Non so dove è Anna.', 'english' => 'I do not know where Anna is.'],
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

    /** @return list<AuthoredExercise> */
    private function sentences(): array
    {
        $stage = Stage::Sentences;

        return [
            Kit::gap($stage, 'sentences.choose_gap.vedo', 'Io ___ Anna.', ['vedo', 'vedi', 'vede'], 'vedo', Kit::word('vedere', 'vedo'), 'Io goes with vedo. Vedi is for tu and vede is for lui or lei. There is no little word before Anna.', 'choose', 'I see Anna.'),
            Kit::gap($stage, 'sentences.choose_gap.vengo', 'Io ___ con Paolo.', ['vengo', 'viene'], 'vengo', Kit::form('vengo', true), 'Io goes with vengo, which is irregular. Viene is for lui or lei.', 'choose', 'I come with Paolo.'),
            Kit::gap($stage, 'sentences.choose_gap.sai', 'Tu ___ dove è Anna?', ['sai', 'so'], 'sai', Kit::form('sai', true), 'Tu goes with sai. So is only for io.', 'choose', 'Do you know where Anna is?'),
            Kit::gap($stage, 'sentences.choose_gap.esce', 'Anna ___ alle sette.', ['esce', 'esco'], 'esce', Kit::word('uscire', 'esce'), 'Anna is she, so the verb is esce. Esco is only for io.', 'choose', 'Anna leaves at seven.'),
            Kit::gap($stage, 'sentences.choose_gap.do', 'Io ___ un libro a Marta.', ['do', 'dà'], 'do', Kit::word('dare', 'do'), 'Io goes with do. Dà is for lui or lei.', 'choose', 'I give a book to Marta.', ['libro' => 'book']),
            Kit::gap($stage, 'sentences.choose_gap.sento', 'Io non ___ bene.', ['sento', 'sente'], 'sento', Kit::word('sentire', 'sento'), 'Io goes with sento. Sente is for lui or lei.', 'choose', 'I do not hear well.'),

            Kit::typeGap($stage, 'sentences.type_gap.esco', 'Io ___ alle otto.', 'I leave at eight.', 'esco', Kit::form('esco'), 'Io goes with esco, which is irregular. Esci is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.porto', 'Io ___ Marta qui.', 'I bring Marta here.', 'porto', Kit::word('portare', 'porto'), 'Io goes with porto, the regular io form of portare. There is no little word before Marta.'),
            Kit::typeGap($stage, 'sentences.type_gap.metto', 'Io ___ due qui.', 'I put two here.', 'metto', Kit::word('mettere', 'metto'), 'Io goes with metto. Metti is for tu.'),
            Kit::typeGap($stage, 'sentences.type_gap.dico', 'Io ___ ciao a Luca.', 'I say hello to Luca.', 'dico', Kit::word('dire', 'dico'), 'Io goes with dico, the irregular form of dire.'),
            Kit::typeGap($stage, 'sentences.type_gap.fa', 'Che ___ Anna?', 'What does Anna do?', 'fa', Kit::form('fa'), 'Anna is she, so the verb is fa. Faccio is only for io.'),

            Kit::translate($stage, 'sentences.translate.non-so', 'I do not know.', ['Non so.', 'Io non so.'], [Kit::word('sapere', 'so'), Kit::form('so')]),
            Kit::translate($stage, 'sentences.translate.vedo-anna', 'I see Anna here.', ['Vedo Anna qui.', 'Io vedo Anna qui.', 'Qui vedo Anna.', 'Qui io vedo Anna.'], [Kit::word('vedere', 'vedo')]),
            Kit::translate($stage, 'sentences.translate.fai', 'What do you do here? (informal you)', ['Che fai qui?', 'Tu che fai qui?', 'Che fai tu qui?'], [Kit::word('fare', 'fai')]),

            Kit::build($stage, 'sentences.build.vengo', 'I come with Anna.', 'Vengo con Anna.', ['vieni'], [Kit::word('venire', 'vengo'), Kit::form('vengo')]),
            Kit::build($stage, 'sentences.build.esci', 'Do you leave at seven? (informal you)', 'Esci alle sette?', ['esco'], [Kit::word('uscire', 'esci')]),
            Kit::build($stage, 'sentences.build.mette', 'She puts two here.', 'Lei mette due qui.', ['metto'], [Kit::word('mettere', 'mette')]),

            Kit::listenChoose($stage, 'sentences.listen_choose.vedo', 'Vedo Anna e Paolo.', ['I see Anna and Paolo.', 'I hear Anna and Paolo.', 'You see Anna and Paolo.', 'I bring Anna and Paolo.'], 'I see Anna and Paolo.', [Kit::word('vedere', 'vedo')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.sai', 'Sai dove è Luca?', ['Do you know where Luca is?', 'Do I know where Luca is?', 'Do you see Luca here?', 'Is Luca here?'], 'Do you know where Luca is?', [Kit::word('sapere', 'sai')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.viene', 'Anna viene con Paolo.', ['Anna comes with Paolo.', 'Anna leaves with Paolo.', 'Anna brings Paolo.', 'I come with Paolo.'], 'Anna comes with Paolo.', [Kit::word('venire', 'viene')]),
            Kit::listenType($stage, 'sentences.listen_type.non-so-dove', 'Non so dove è Marta.', 'I do not know where Marta is.', [Kit::word('sapere', 'so'), Kit::form('so')], homophoneNote: self::EGRAVE_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.esco', 'Esco alle otto con Luca.', 'I leave at eight with Luca.', [Kit::word('uscire', 'esco'), Kit::form('esco')]),
            Kit::listenType($stage, 'sentences.listen_type.faccio', 'Io faccio uno e tu fai due.', 'I do one and you do two. (informal you)', [Kit::word('fare', 'faccio'), Kit::form('faccio')], homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'sentences.listen_type.dico', 'Dico ciao e arrivederci.', 'I say hello and goodbye.', [Kit::word('dire', 'dico')], homophoneNote: self::E_NOTE),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.fai', 'Che fai qui?', 'What are you doing here? (informal you)', [Kit::word('fare', 'fai')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.porto', 'Porto Marta qui.', 'I bring Marta here.', [Kit::word('portare', 'porto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.metto', 'Metto uno qui e due lì.', 'I put one here and two there.', [Kit::word('mettere', 'metto')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.do', 'Do tre a Paolo.', 'I give three to Paolo.', [Kit::word('dare', 'do'), Kit::form('do')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vedi', 'Vedi Anna?', 'Do you see Anna? (informal you)', [['sì', 'no'], ['vedo']], 'Sì, vedo Anna.', [Kit::word('vedere', 'vedo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.vieni', 'Vieni con Paolo?', 'Are you coming with Paolo? (informal you)', [['sì', 'no'], ['vengo']], 'Sì, vengo con Paolo.', [Kit::word('venire', 'vengo'), Kit::form('vengo')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.senti', 'Senti bene?', 'Do you hear well? (informal you)', [['sì', 'no'], ['sento']], 'Sì, sento bene.', [Kit::word('sentire', 'sento')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.cena', 'Read the conversation.', [
                Kit::line('Anna', 'Paolo, che fai qui?'),
                Kit::line('Paolo', 'Vengo con Luca e porto la cena.'),
                Kit::line('Anna', 'Molto bene. Io metto il vino in tavola. Sai dove è Marta?'),
                Kit::line('Paolo', 'Non so. Qui non vedo Marta.'),
                Kit::line('Anna', 'Io sento Marta. È lì.'),
            ], [
                Kit::question('What does Paolo bring?', ['The dinner', 'The wine', 'Marta'], 'The dinner'),
                Kit::question('What does Anna do for the dinner?', ['She puts the wine on the table.', 'She brings the dinner.', 'She sees Marta.'], 'She puts the wine on the table.'),
                Kit::question('Where is Marta?', ['There', 'Here', 'The text does not say.'], 'There'),
            ], [Kit::word('fare', 'fai'), Kit::word('venire', 'vengo'), Kit::word('portare', 'porto'), Kit::word('mettere', 'metto'), Kit::word('sapere', 'sai'), Kit::word('vedere', 'vedo'), Kit::word('sentire', 'sento')], 'read', glosses: ['cena' => 'dinner', 'vino' => 'wine', 'tavola' => 'table']),
            Kit::gap($stage, 'task.choose_gap.vedo-marta', 'Io non ___ Marta qui.', ['vedo', 'vedi'], 'vedo', Kit::word('vedere', 'vedo'), 'Io goes with vedo. Vedi is for tu.', 'read', 'I do not see Marta here.'),
            Kit::gap($stage, 'task.choose_gap.da', 'Luca ___ un libro a Marta.', ['dà', 'do'], 'dà', Kit::word('dare', 'dà'), 'Luca is he, so the verb is dà, with an accent. Do is only for io.', 'read', 'Luca gives a book to Marta.', ['libro' => 'book']),

            Kit::transform($stage, 'task.transform.lei-viene', 'Change the subject to she.', 'Vengo con Marta.', ['Lei viene con Marta.', 'Viene con Marta.'], [Kit::word('venire', 'viene'), Kit::form('viene')]),
            Kit::transform($stage, 'task.transform.usciamo', 'Change the subject to we.', 'Esco alle otto.', ['Usciamo alle otto.', 'Noi usciamo alle otto.'], [Kit::word('uscire', 'usciamo'), Kit::form('usciamo')]),
            Kit::transform($stage, 'task.transform.faccio', 'Change the subject to I.', 'Anna fa due qui.', ['Faccio due qui.', 'Io faccio due qui.'], [Kit::word('fare', 'faccio'), Kit::form('faccio', true)]),
            Kit::writeGuided($stage, 'task.write_guided.vengo-porto', 'Say that you come with Luca and that you bring Anna.', ['vengo con', 'porto'], 'Vengo con Luca e porto Anna.', [
                ['forms' => ['vengo', 'veniamo'], 'term' => 'venire'],
                ['forms' => ['porto', 'portiamo'], 'term' => 'portare'],
            ], [Kit::word('venire', 'vengo'), Kit::word('portare', 'porto')]),
            Kit::writeGuided($stage, 'task.write_guided.non-so-non-vedo', 'Say that you do not know where Marta is and that you do not see Luca here.', ['non so', 'non vedo', 'dove'], 'Non so dove è Marta e non vedo Luca qui.', [
                ['forms' => ['so', 'sappiamo'], 'term' => 'sapere'],
                ['forms' => ['vedo', 'vediamo'], 'term' => 'vedere'],
            ], [Kit::word('sapere', 'so'), Kit::word('vedere', 'vedo')]),
            Kit::build($stage, 'task.build.dico', 'I say hello to Marta.', 'Dico ciao a Marta.', ['dici', 'dice'], [Kit::word('dire', 'dico'), Kit::form('dico')], 'write'),
            Kit::build($stage, 'task.build.veniamo', 'We come with Luca.', 'Veniamo con Luca.', ['vengo', 'viene'], [Kit::word('venire', 'veniamo')], 'write'),
            Kit::build($stage, 'task.build.senti', 'Do you hear Marta? (informal you)', 'Senti Marta?', ['sento', 'sente'], [Kit::word('sentire', 'senti')], 'write'),
            Kit::translate($stage, 'task.translate.vedo-sento', 'I see Anna, but I do not hear Marta.', ['Vedo Anna, ma non sento Marta.', 'Io vedo Anna, ma non sento Marta.'], [Kit::word('vedere', 'vedo'), Kit::word('sentire', 'sento')], 'write'),
            Kit::translate($stage, 'task.translate.fai-esci', 'What do you do and when do you leave? (informal you)', ['Che fai e quando esci?', 'Tu che fai e quando esci?'], [Kit::word('fare', 'fai'), Kit::word('uscire', 'esci'), Kit::form('fai', true)], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.uscire', [
                Kit::line('Marta', 'Quando esci, Luca?'),
                Kit::line('Luca', 'Esco alle otto. Dico arrivederci a Marta e a Paolo.'),
                Kit::line('Marta', 'Anche io esco alle otto. Dico ciao a Paolo.'),
            ], [
                Kit::question('When does Luca leave?', ['At seven', 'At eight', 'At nine'], 'At eight'),
                Kit::question('Who does Luca say goodbye to?', ['Marta and Paolo', 'Anna', 'Nobody'], 'Marta and Paolo'),
                Kit::question('Who says hello to Paolo?', ['Marta', 'Luca', 'Paolo'], 'Marta'),
            ], [
                Kit::question('Who asks when Luca leaves?', ['Marta', 'Luca', 'Anna'], 'Marta'),
                Kit::question('Does Marta leave too?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('uscire', 'esco'), Kit::word('dire', 'dico')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.vengo-porto', 'Vengo con Luca e porto Marta.', 'I come with Luca and I bring Marta.', [Kit::word('venire', 'vengo'), Kit::word('portare', 'porto'), Kit::form('vengo')], 'listen', homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'task.listen_type.dice-dico', 'Anna dice ciao e io dico arrivederci.', 'Anna says hello and I say goodbye.', [Kit::word('dire', 'dice'), Kit::form('dico')], 'listen', homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'task.listen_type.non-sento', 'Non sento bene, ma vedo Paolo lì.', 'I do not hear well, but I see Paolo there.', [Kit::word('sentire', 'sento'), Kit::word('vedere', 'vedo')], 'listen', homophoneNote: self::LI_NOTE),

            Kit::speakAnswer($stage, 'task.speak_answer.esci', 'Esci alle nove?', 'Do you leave at nine? (informal you)', [['sì', 'no'], ['esco']], 'Sì, esco alle nove.', [Kit::word('uscire', 'esco'), Kit::form('esco')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.dici', 'Dici ciao a Paolo?', 'Do you say hello to Paolo? (informal you)', [['sì', 'no'], ['dico']], 'Sì, dico ciao a Paolo.', [Kit::word('dire', 'dico')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.porti', 'Porti Luca?', 'Are you bringing Luca? (informal you)', [['sì', 'no'], ['porto']], 'Sì, porto Luca.', [Kit::word('portare', 'porto')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.metti', 'Metti due qui?', 'Do you put two here? (informal you)', [['sì', 'no'], ['metto']], 'Sì, metto due qui.', [Kit::word('mettere', 'metto')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.do', 'Do due a Marta e a Paolo.', 'I give two to Marta and Paolo.', [Kit::word('dare', 'do')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.non-so-fa', 'Non so che fa Anna qui.', 'I do not know what Anna is doing here.', [Kit::word('sapere', 'so'), Kit::word('fare', 'fa'), Kit::form('fa')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.marta-fai', 'Marta, what do you do here? (informal you)', ['Marta, che fai qui?', 'Che fai qui, Marta?'], [Kit::word('fare', 'fai'), Kit::form('fai', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.vengo-porto', 'I come with Marta and I bring two.', ['Vengo con Marta e porto due.', 'Io vengo con Marta e porto due.'], [Kit::word('venire', 'vengo'), Kit::word('portare', 'porto'), Kit::form('vengo')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.esco-dico', 'I leave at nine and I say goodbye.', ['Esco alle nove e dico arrivederci.', 'Io esco alle nove e dico arrivederci.'], [Kit::word('uscire', 'esco'), Kit::word('dire', 'dico'), Kit::form('esco', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.non-so-vedo', 'I do not know, but I see Luca here.', ['Non so, ma vedo Luca qui.', 'Io non so, ma vedo Luca qui.', 'Non so, ma qui vedo Luca.'], [Kit::word('sapere', 'so'), Kit::word('vedere', 'vedo'), Kit::form('so')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.do', 'Io ___ tre a Marta.', 'I give three to Marta.', 'do', Kit::form('do', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.dico', 'Io ___ ciao a Paolo.', 'I say hello to Paolo.', 'dico', Kit::form('dico'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.sento', 'Non sento bene Anna.', 'I do not hear Anna well.', [Kit::word('sentire', 'sento')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.dico-metto', 'Dico ciao a Luca e metto due qui.', 'I say hello to Luca and I put two here.', [Kit::word('dire', 'dico'), Kit::word('mettere', 'metto')], 'dictation', $set, homophoneNote: self::A_NOTE.' '.self::E_NOTE),
            Kit::listenType($stage, 'check.a.listen_type.da', 'Marta dà due a Paolo e a Luca.', 'Marta gives two to Paolo and Luca.', [Kit::word('dare', 'dà')], 'dictation', $set, homophoneNote: self::DA_NOTE.' '.self::A_NOTE.' '.self::E_NOTE),
            Kit::listenPassage($stage, 'check.a.listen_passage.porti', [
                Kit::line('Anna', 'Che porti, Luca?'),
                Kit::line('Luca', 'Porto due. Metto uno qui?'),
                Kit::line('Anna', 'No, lì, per favore.'),
            ], [
                Kit::question('What does Luca bring?', ['Two', 'Three', 'Nothing'], 'Two'),
                Kit::question('Where does Anna say to put it?', ['There', 'Here', 'The conversation does not say.'], 'There'),
                Kit::question('Who asks the first question?', ['Anna', 'Luca', 'Nobody'], 'Anna'),
            ], [
                Kit::question('Who brings something?', ['Luca', 'Anna', 'Nobody'], 'Luca'),
                Kit::question('Does Luca know where to put it?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('portare', 'porto'), Kit::word('mettere', 'metto')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.sai', 'Read the conversation.', [
                Kit::line('Marta', 'Paolo, sai dove è Anna?'),
                Kit::line('Paolo', 'Non so. Qui non vedo Anna, ma sento Luca.'),
                Kit::line('Marta', 'Anche io sento Luca. Viene da lì.'),
            ], [
                Kit::question('What does Marta ask Paolo?', ['Where Anna is', 'Where Luca is', 'Who is coming'], 'Where Anna is'),
                Kit::question('Does Paolo see Anna?', ['Yes', 'No', 'The text does not say.'], 'No'),
            ], [Kit::word('sapere', 'sai'), Kit::word('vedere', 'vedo'), Kit::word('sentire', 'sento'), Kit::word('venire', 'viene')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.fai', 'Che fai?', 'What do you do? (informal you)', [['faccio', 'vengo', 'esco', 'metto', 'porto', 'vedo', 'dico'], ['qui', 'lì', 'con', 'a', 'uno', 'due', 'tre', 'ciao', 'arrivederci']], 'Vedo Anna qui.', [Kit::word('fare', 'fai')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.dai', 'Dai due a Marta?', 'Do you give two to Marta? (informal you)', [['sì', 'no'], ['do']], 'Sì, do due a Marta.', [Kit::word('dare', 'dai')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.esci', 'Esci alle sette o alle otto?', 'Do you leave at seven or at eight? (informal you)', [['esco'], ['sette', 'otto']], 'Esco alle otto.', [Kit::word('uscire', 'esci')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.vengo-porto-dico', 'I come with Luca, I bring Marta and I say hello.', ['Vengo con Luca, porto Marta e dico ciao.', 'Io vengo con Luca, porto Marta e dico ciao.'], [Kit::word('venire', 'vengo'), Kit::word('portare', 'porto'), Kit::word('dire', 'dico'), Kit::form('vengo')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.esco-do', 'I leave with Luca and I give two to Marta.', ['Esco con Luca e do due a Marta.', 'Io esco con Luca e do due a Marta.'], [Kit::word('uscire', 'esco'), Kit::word('dare', 'do'), Kit::form('esco', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.non-so-fai', 'I do not know what you do and I do not see Anna. (informal you)', ['Non so che fai e non vedo Anna.', 'Io non so che fai e non vedo Anna.'], [Kit::word('sapere', 'so'), Kit::word('fare', 'fai'), Kit::word('vedere', 'vedo'), Kit::form('so', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.metto-sento', 'I put two here and I do not hear Anna.', ['Metto due qui e non sento Anna.', 'Io metto due qui e non sento Anna.'], [Kit::word('mettere', 'metto'), Kit::word('sentire', 'sento')], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.da', 'Anna ___ due a Paolo.', 'Anna gives two to Paolo.', 'dà', Kit::form('dà', true), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.mette', 'Marta ___ uno qui.', 'Marta puts one here.', 'mette', Kit::word('mettere', 'mette'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.vedo-sento-dico', 'Vedo Anna, sento Paolo e dico ciao.', 'I see Anna, I hear Paolo and I say hello.', [Kit::word('vedere', 'vedo'), Kit::word('sentire', 'sento'), Kit::word('dire', 'dico')], 'dictation', $set, homophoneNote: self::E_NOTE),
            Kit::listenType($stage, 'check.b.listen_type.sa-fa-esce', 'Anna sa che Paolo fa due, ma esce con Luca.', 'Anna knows that Paolo does two, but she leaves with Luca.', [Kit::word('sapere', 'sa'), Kit::word('fare', 'fa'), Kit::word('uscire', 'esce'), Kit::form('fa')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.viene-porta', 'Marta viene con Luca e porta due.', 'Marta comes with Luca and brings two.', [Kit::word('venire', 'viene'), Kit::word('portare', 'porta'), Kit::form('viene')], 'dictation', $set, homophoneNote: self::E_NOTE),
        ];
    }
}
