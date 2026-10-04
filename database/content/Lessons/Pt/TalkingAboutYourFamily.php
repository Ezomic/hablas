<?php

declare(strict_types=1);

namespace Database\Content\Lessons\Pt;

use App\Enums\LessonStage as Stage;
use App\Lessons\AuthoredExercise;
use App\Lessons\ExerciseKit as Kit;
use App\Lessons\UnitContent;
use App\Lessons\WordData;

final class TalkingAboutYourFamily implements UnitContent
{
    public function languageCode(): string
    {
        return 'pt';
    }

    public function unitSlug(): string
    {
        return 'talking-about-your-family';
    }

    public function words(): array
    {
        return [
            new WordData('a família', cue: 'family'),
            new WordData('o pai', cue: 'father', portunolSlips: ['padre'], questions: ['Should the child-speech forms "o papá" and "a mamã" be accepted as answers for pai and mãe, or is plain "o pai" and "a mãe" the right answer for an adult learner?']),
            new WordData('a mãe', cue: 'mother', portunolSlips: ['madre'], questions: ['Does everyday speech in Portugal ever drop the article before a singular kinship term after a possessive ("meu pai", "minha mãe"), and should a bare form like that be accepted, or is the article required in the drilled answers?']),
            new WordData('o irmão', cue: 'brother', forms: ['os irmãos'], portunolSlips: ['hermano'], questions: ['The drills use "o seu irmão" for his or her brother. Would a native say "o irmão dele" or "o irmão dela" instead, and is it right to teach and accept "o seu" for the third person at A1?']),
            new WordData('a irmã', cue: 'sister', forms: ['as irmãs'], portunolSlips: ['hermana']),
            new WordData('o filho', cue: 'son', forms: ['os filhos'], portunolSlips: ['hijo']),
            new WordData('os avós', cue: 'grandparents', portunolSlips: ['abuelos'], note: 'Os avós means grandparents. Be careful with the accents: o avô is the grandfather and a avó is the grandmother, which are different words.', questions: ['Is "os avós" the natural everyday word for grandparents, and do "o avô" and "a avó" sound distinct enough in a dictation that the accent on the last vowel is a fair thing to grade?']),
            new WordData('casado', cue: 'married (masculine)', forms: ['casada', 'casados', 'casadas'], questions: ['Is "é casado" or "está casado" the more natural way to say someone is married in Portugal? Both are accepted in a few answers here, so is that right, or should only one be accepted?']),
            new WordData('solteiro', cue: 'single, not married (masculine)', forms: ['solteira', 'solteiros', 'solteiras'], portunolSlips: ['soltero', 'soltera']),
            new WordData('mais velho', cue: 'older (than someone else, masculine)', forms: ['mais velha', 'mais velhos', 'mais velhas'], portunolSlips: ['mayor'], questions: ['Is plain "mais velho" a fine way to say my brother is older at A1, even though people also say "mais velho que" or "mais velho do que" with a comparison? Is "mais novo" worth adding later?']),
        ];
    }

    public function grammarExamples(): array
    {
        return [
            ['text' => 'O meu irmão é mais velho.', 'english' => 'My brother is older.'],
            ['text' => 'As minhas irmãs são solteiras.', 'english' => 'My sisters are single.'],
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
            Kit::gap($stage, 'sentences.choose_gap.o-meu-irmao', '___ irmão é casado.', ['O meu', 'Os meus', 'A minha'], 'O meu', Kit::form('o meu', true), 'Irmão is masculine and singular, so the possessive is masculine and singular too, with the article: o meu irmão.', 'choose', 'My brother is married.'),
            Kit::gap($stage, 'sentences.choose_gap.a-minha-mae', '___ mãe está aqui.', ['A minha', 'O meu', 'As minhas'], 'A minha', Kit::form('a minha'), 'Mãe is feminine, so the possessive is feminine too, with the article: a minha mãe.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.os-meus-irmaos', '___ irmãos estão aqui.', ['Os meus', 'O meu', 'As minhas'], 'Os meus', Kit::form('os meus', true), 'The possessive agrees with the thing owned: irmãos is masculine and plural, so os meus.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.irma-solteira', 'A minha irmã é ___.', ['solteira', 'solteiro'], 'solteira', Kit::word('solteiro', 'solteira'), 'Irmã is feminine, so the adjective ends in -a.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.rui-casado', 'O Rui é ___.', ['casado', 'casada'], 'casado', Kit::word('casado'), 'Rui is a man, so the adjective ends in -o.', 'choose'),
            Kit::gap($stage, 'sentences.choose_gap.marta-irma', 'A Marta é a minha ___.', ['irmã', 'irmão'], 'irmã', Kit::word('a irmã', 'irmã'), 'Marta is a woman, so the feminine irmã, with the nasal ã. Irmão is the brother.', 'choose'),

            Kit::typeGap($stage, 'sentences.type_gap.o-meu-pai', '___ pai está aqui.', 'My father is here.', 'o meu', Kit::form('o meu'), 'Pai is masculine and singular, so o meu. Keep the article, unlike Spanish.'),
            Kit::typeGap($stage, 'sentences.type_gap.familia', 'A ___ está aqui.', 'The family is here.', 'família', Kit::word('a família', 'família')),
            Kit::typeGap($stage, 'sentences.type_gap.os-meus-avos', '___ avós estão aqui.', 'My grandparents are here.', 'os meus', Kit::form('os meus', true), 'Avós is plural, so the possessive and the article are plural too: os meus.'),
            Kit::typeGap($stage, 'sentences.type_gap.os-seus', 'A Marta e ___ irmãos estão aqui.', 'Marta and her brothers are here.', 'os seus', Kit::form('os seus', true), 'Seu agrees with the thing owned: more than one brother, so os seus.'),
            Kit::typeGap($stage, 'sentences.type_gap.o-teu', 'Ana, ___ pai está aqui?', 'Ana, is your father here?', 'o teu', Kit::form('o teu'), 'Your, to a friend, is teu or tua, and with the article: o teu pai.'),

            Kit::translate($stage, 'sentences.translate.mae', 'My mother is here.', ['A minha mãe está aqui.'], [Kit::word('a mãe', 'mãe'), Kit::form('a minha')]),
            Kit::translate($stage, 'sentences.translate.irma', 'Your sister is married. (To a friend.)', ['A tua irmã é casada.', 'A tua irmã está casada.'], [Kit::word('a irmã', 'irmã'), Kit::word('casado', 'casada'), Kit::form('a tua')]),
            Kit::translate($stage, 'sentences.translate.filhos', 'My sons are single.', ['Os meus filhos são solteiros.', 'Os meus filhos estão solteiros.'], [Kit::word('o filho', 'filhos'), Kit::word('solteiro', 'solteiros'), Kit::form('os meus', true)]),
            Kit::build($stage, 'sentences.build.filho-solteiro', 'Her son is single.', 'O seu filho é solteiro.', ['sua'], [Kit::word('o filho', 'filho'), Kit::word('solteiro'), Kit::form('o seu')]),
            Kit::build($stage, 'sentences.build.avos', 'My grandparents are here.', 'Os meus avós estão aqui.', ['meu'], [Kit::word('os avós', 'avós'), Kit::form('os meus', true)]),
            Kit::build($stage, 'sentences.build.irmao-mais-velho', 'My brother is older.', 'O meu irmão é mais velho.', ['maior'], [Kit::word('o irmão', 'irmão'), Kit::word('mais velho'), Kit::form('o meu')], glosses: ['maior' => 'bigger']),

            Kit::listenChoose($stage, 'sentences.listen_choose.irma', 'A minha irmã está aqui.', ['My sister is here.', 'My brother is here.', 'My mother is here.', 'My daughter is here.'], 'My sister is here.', [Kit::word('a irmã', 'irmã'), Kit::form('a minha')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.irmao', 'O meu irmão está aqui.', ['My brother is here.', 'My sister is here.', 'My father is here.', 'My son is here.'], 'My brother is here.', [Kit::word('o irmão', 'irmão'), Kit::form('o meu')]),
            Kit::listenChoose($stage, 'sentences.listen_choose.avos', 'Os meus avós são casados.', ['My grandparents are married.', 'My grandparents are older.', 'My parents are married.', 'My brothers are married.'], 'My grandparents are married.', [Kit::word('os avós', 'avós'), Kit::word('casado', 'casados'), Kit::form('os meus', true)]),
            Kit::listenType($stage, 'sentences.listen_type.mae-casada', 'A minha mãe é casada.', 'My mother is married.', [Kit::word('a mãe', 'mãe'), Kit::word('casado', 'casada'), Kit::form('a minha')], homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.familia', 'A família está aqui.', 'The family is here.', [Kit::word('a família')], homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'sentences.listen_type.filho-solteiro', 'O meu filho é solteiro.', 'My son is single.', [Kit::word('o filho', 'filho'), Kit::word('solteiro'), Kit::form('o meu')]),
            Kit::listenType($stage, 'sentences.listen_type.pai-mae', 'O pai e a mãe estão aqui.', 'The father and the mother are here.', [Kit::word('o pai', 'pai'), Kit::word('a mãe', 'mãe')], homophoneNote: 'The a before mãe is the article a (the), not à (to the) and not há (there is).'),

            Kit::speakRepeat($stage, 'sentences.speak_repeat.irma-mais-velha', 'A minha irmã é mais velha.', 'My sister is older.', [Kit::word('a irmã', 'irmã'), Kit::word('mais velho', 'mais velha'), Kit::form('a minha')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.filho-casado', 'O seu filho é casado.', 'Her son is married.', [Kit::word('o filho', 'filho'), Kit::word('casado'), Kit::form('o seu')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.irma-do-rui', 'A Marta é a irmã do Rui.', 'Marta is Rui\'s sister.', [Kit::word('a irmã', 'irmã')]),
            Kit::speakRepeat($stage, 'sentences.speak_repeat.pai-casado', 'O meu pai é casado.', 'My father is married.', [Kit::word('o pai', 'pai'), Kit::word('casado'), Kit::form('o meu')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.irmaos', 'Tens irmãos?', 'Do you have brothers or sisters?', [['sim', 'não', 'tenho', 'irmão', 'irmãos', 'irmã', 'uma', 'um', 'dois', 'duas']], 'Sim, tenho uma irmã.', [Kit::word('a irmã', 'irmã')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.irmao-casado', 'O teu irmão é casado?', 'Is your brother married?', [['sim', 'não', 'é', 'meu', 'casado', 'solteiro']], 'Sim, o meu irmão é casado.', [Kit::word('o irmão', 'irmão'), Kit::word('casado'), Kit::form('o meu')]),
            Kit::speakAnswer($stage, 'sentences.speak_answer.marta', 'Quem é a Marta?', 'Who is Marta?', [['a', 'minha', 'é', 'marta'], ['irmã', 'mãe', 'família']], 'A Marta é a minha irmã.', [Kit::word('a irmã', 'irmã'), Kit::form('a minha')]),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function task(): array
    {
        $stage = Stage::Task;

        return [
            Kit::readPassage($stage, 'task.read_passage.familia', 'Read the conversation about Marta\'s family.', [
                Kit::line('Rui', 'Marta, tens irmãos?'),
                Kit::line('Marta', 'Tenho uma irmã e um irmão. A minha irmã é solteira.'),
                Kit::line('Rui', 'E o teu irmão?'),
                Kit::line('Marta', 'O meu irmão é mais velho e é casado. O seu filho está aqui.'),
                Kit::line('Rui', 'E o teu pai e a tua mãe?'),
                Kit::line('Marta', 'O meu pai e a minha mãe estão aqui. Os meus avós também.'),
            ], [
                Kit::question('Who is single?', ['Marta\'s sister', 'Marta\'s brother', 'Marta\'s mother'], 'Marta\'s sister'),
                Kit::question('Who is older and married?', ['Her sister', 'Her brother', 'Her father'], 'Her brother'),
                Kit::question('Who is here with Marta?', ['Only her brother', 'Her father, her mother and her grandparents', 'Nobody'], 'Her father, her mother and her grandparents'),
            ], [Kit::word('o pai'), Kit::word('a mãe'), Kit::word('o irmão'), Kit::word('a irmã'), Kit::word('o filho'), Kit::word('os avós', 'avós'), Kit::word('casado'), Kit::word('solteiro'), Kit::word('mais velho')]),
            Kit::gap($stage, 'task.choose_gap.pai', 'O João é o ___ da Ana.', ['pai', 'mãe'], 'pai', Kit::word('o pai', 'pai'), 'O goes with a masculine noun, so the father.', 'read'),
            Kit::gap($stage, 'task.choose_gap.mae', 'A Marta é a ___ do Rui.', ['mãe', 'pai'], 'mãe', Kit::word('a mãe', 'mãe'), 'A goes with a feminine noun, so the mother.', 'read'),

            Kit::transform($stage, 'task.transform.irmaos', 'Make it plural.', 'O meu irmão é casado.', ['Os meus irmãos são casados.', 'Os meus irmãos estão casados.'], [Kit::word('o irmão', 'irmãos'), Kit::word('casado', 'casados'), Kit::form('os meus', true)]),
            Kit::transform($stage, 'task.transform.irmas', 'Make it plural.', 'A minha irmã é solteira.', ['As minhas irmãs são solteiras.', 'As minhas irmãs estão solteiras.'], [Kit::word('a irmã', 'irmãs'), Kit::word('solteiro', 'solteiras'), Kit::form('as minhas', true)]),
            Kit::transform($stage, 'task.transform.tua', 'Talk to a friend: change minha to your (tu).', 'A minha mãe está aqui.', ['A tua mãe está aqui.'], [Kit::word('a mãe', 'mãe'), Kit::form('a tua')]),
            Kit::writeGuided($stage, 'task.write_guided.irma', 'Say that your sister is older and single.', ['irmã', 'mais velha', 'solteira'], 'A minha irmã é mais velha e solteira.', [
                ['forms' => ['irmã'], 'term' => 'a irmã'],
                ['forms' => ['velha', 'velho'], 'term' => 'mais velho'],
                ['forms' => ['solteira', 'solteiro'], 'term' => 'solteiro'],
            ], [Kit::word('a irmã'), Kit::word('mais velho'), Kit::word('solteiro')]),
            Kit::writeGuided($stage, 'task.write_guided.pai', 'Say that your father is married and that your grandparents are here.', ['pai', 'casado', 'avós', 'aqui'], 'O meu pai é casado. Os meus avós estão aqui.', [
                ['forms' => ['pai'], 'term' => 'o pai'],
                ['forms' => ['casado'], 'term' => 'casado'],
                ['forms' => ['avós'], 'term' => 'os avós'],
            ], [Kit::word('o pai'), Kit::word('casado'), Kit::word('os avós', 'avós')]),
            Kit::build($stage, 'task.build.mae-avos', 'My mother is here with my grandparents.', 'A minha mãe está aqui com os meus avós.', ['meu', 'as'], [Kit::word('a mãe', 'mãe'), Kit::word('os avós', 'avós'), Kit::form('os meus', true)], 'write'),
            Kit::build($stage, 'task.build.filho-velho', 'Her son is older and single.', 'O seu filho é mais velho e solteiro.', ['maior', 'casado'], [Kit::word('o filho', 'filho'), Kit::word('mais velho'), Kit::word('solteiro'), Kit::form('o seu')], 'write', ['maior' => 'bigger']),
            Kit::build($stage, 'task.build.irmaos-casados', 'Ana, your brother and your sister are married.', 'O teu irmão e a tua irmã são casados.', ['é', 'teus'], [Kit::word('o irmão', 'irmão'), Kit::word('a irmã', 'irmã'), Kit::word('casado', 'casados'), Kit::form('o teu')], 'write'),
            Kit::translate($stage, 'task.translate.joao-marta', 'João is my brother. Marta is my sister.', ['O João é o meu irmão. A Marta é a minha irmã.', 'O João é o meu irmão. Marta é a minha irmã.', 'João é o meu irmão. A Marta é a minha irmã.', 'João é o meu irmão. Marta é a minha irmã.'], [Kit::word('o irmão', 'irmão'), Kit::word('a irmã', 'irmã'), Kit::form('o meu')], 'write'),
            Kit::translate($stage, 'task.translate.casada', 'My mother is married and my father is too.', ['A minha mãe é casada e o meu pai também.', 'A minha mãe é casada e o meu pai também é casado.', 'A minha mãe é casada e o meu pai é casado também.', 'A minha mãe está casada e o meu pai também.', 'A minha mãe está casada e o meu pai também está casado.', 'A minha mãe está casada e o meu pai está casado também.', 'A minha mãe é casada e o meu pai também está casado.', 'A minha mãe é casada e o meu pai também é.', 'A minha mãe é casada e o meu pai também está.', 'A minha mãe está casada e o meu pai também é.', 'A minha mãe está casada e o meu pai também está.'], [Kit::word('a mãe', 'mãe'), Kit::word('o pai', 'pai'), Kit::word('casado', 'casada'), Kit::form('a minha')], 'write'),

            Kit::listenPassage($stage, 'task.listen_passage.irmao', [
                Kit::line('João', 'Ana, o teu irmão é casado?'),
                Kit::line('Ana', 'Não, é solteiro. A minha irmã é casada.'),
                Kit::line('João', 'E os teus avós?'),
                Kit::line('Ana', 'Os meus avós estão aqui com a minha família.'),
                Kit::line('João', 'Muito bem. O meu pai também está aqui.'),
            ], [
                Kit::question('Is Ana\'s brother married?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Who is married?', ['Ana\'s sister', 'Ana\'s brother', 'Ana\'s mother'], 'Ana\'s sister'),
                Kit::question('Whose grandparents are here?', ['Ana\'s', 'João\'s', 'Marta\'s'], 'Ana\'s'),
            ], [
                Kit::question('Who asks about the brother?', ['Ana', 'João', 'Marta'], 'João'),
                Kit::question('Whose father is here?', ['Ana\'s', 'João\'s', 'Rui\'s'], 'João\'s'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('o irmão'), Kit::word('casado'), Kit::word('solteiro'), Kit::word('a irmã'), Kit::word('os avós', 'avós'), Kit::word('a família'), Kit::word('o pai')], 'listen'),
            Kit::listenType($stage, 'task.listen_type.irmao-velho', 'O meu irmão mais velho é casado.', 'My older brother is married.', [Kit::word('o irmão', 'irmão'), Kit::word('mais velho'), Kit::word('casado'), Kit::form('o meu')]),
            Kit::listenType($stage, 'task.listen_type.familia', 'A família da Marta está aqui.', 'Marta\'s family is here.', [Kit::word('a família')], homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'task.listen_type.avos-pai', 'Os teus avós e o teu pai estão aqui.', 'Your grandparents and your father are here.', [Kit::word('os avós', 'avós'), Kit::word('o pai', 'pai'), Kit::form('os teus', true)]),

            Kit::speakAnswer($stage, 'task.speak_answer.mae', 'A tua mãe é casada?', 'Is your mother married?', [['sim', 'não', 'é', 'minha', 'casada', 'solteira']], 'Sim, a minha mãe é casada.', [Kit::word('a mãe', 'mãe'), Kit::word('casado', 'casada'), Kit::form('a minha')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.familia', 'Tens família aqui?', 'Do you have family here?', [['sim', 'não', 'tenho', 'minha', 'família', 'irmão', 'irmã', 'pai', 'mãe', 'avós']], 'Sim, a minha família está aqui.', [Kit::word('a família', 'família'), Kit::form('a minha')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.mais-velho', 'O teu irmão é mais velho?', 'Is your brother older?', [['sim', 'não', 'é', 'meu', 'mais', 'velho', 'irmão']], 'Sim, o meu irmão é mais velho.', [Kit::word('o irmão', 'irmão'), Kit::word('mais velho'), Kit::form('o meu')], 'speak'),
            Kit::speakAnswer($stage, 'task.speak_answer.avos', 'Onde estão os teus avós?', 'Where are your grandparents?', [['os', 'meus', 'avós', 'estão', 'aqui', 'ali', 'lá']], 'Os meus avós estão ali.', [Kit::word('os avós', 'avós'), Kit::form('os meus', true)], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.pai', 'Onde está o teu pai?', 'Where is your father?', [Kit::word('o pai', 'pai'), Kit::form('o teu')], 'speak'),
            Kit::speakRepeat($stage, 'task.speak_repeat.filho', 'O teu filho é solteiro.', 'Your son is single.', [Kit::word('o filho', 'filho'), Kit::word('solteiro'), Kit::form('o teu')], 'speak'),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkA(): array
    {
        $stage = Stage::Check;
        $set = 'a';

        return [
            Kit::translate($stage, 'check.a.translate.irma', 'Your sister is older. (To a friend.)', ['A tua irmã é mais velha.'], [Kit::word('a irmã', 'irmã'), Kit::word('mais velho', 'mais velha'), Kit::form('a tua')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.pai', 'Her father is married.', ['O seu pai é casado.', 'O seu pai está casado.'], [Kit::word('o pai', 'pai'), Kit::word('casado'), Kit::form('o seu')], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.irmaos', 'My brothers are single.', ['Os meus irmãos são solteiros.', 'Os meus irmãos estão solteiros.'], [Kit::word('o irmão', 'irmãos'), Kit::word('solteiro', 'solteiros'), Kit::form('os meus', true)], 'sentences', $set),
            Kit::translate($stage, 'check.a.translate.filho', 'My son is with my grandparents.', ['O meu filho está com os meus avós.'], [Kit::word('o filho', 'filho'), Kit::word('os avós', 'avós')], 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.a-sua', 'A Marta está com ___ mãe.', 'Marta is with her mother.', 'a sua', Kit::form('a sua'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.a.type_gap.as-minhas', '___ irmãs estão aqui.', 'My sisters are here.', 'as minhas', Kit::form('as minhas', true), null, 'sentences', $set),
            Kit::listenType($stage, 'check.a.listen_type.mae-familia', 'A minha mãe está com a família.', 'My mother is with the family.', [Kit::word('a mãe', 'mãe'), Kit::word('a família', 'família'), Kit::form('a minha')], 'dictation', $set, homophoneNote: 'Both a words are the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.a.listen_type.irmao-casado', 'O irmão do Rui é casado.', 'Rui\'s brother is married.', [Kit::word('o irmão', 'irmão'), Kit::word('casado')], 'dictation', $set),
            Kit::listenType($stage, 'check.a.listen_type.irma-solteira', 'A irmã da Marta é solteira.', 'Marta\'s sister is single.', [Kit::word('a irmã', 'irmã'), Kit::word('solteiro', 'solteira')], 'dictation', $set, homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenPassage($stage, 'check.a.listen_passage.irma', [
                Kit::line('Marta', 'Rui, a tua irmã é casada?'),
                Kit::line('Rui', 'Não, é solteira. Mas o meu irmão é casado.'),
                Kit::line('Marta', 'E o teu irmão é mais velho?'),
                Kit::line('Rui', 'Sim. O seu filho está com os meus avós.'),
            ], [
                Kit::question('Is Rui\'s sister married?', ['Yes', 'No', 'The conversation does not say.'], 'No'),
                Kit::question('Who is married?', ['Rui\'s sister', 'Rui\'s brother', 'Marta\'s brother'], 'Rui\'s brother'),
                Kit::question('Who is with Rui\'s grandparents?', ['His brother\'s son', 'Marta', 'His father'], 'His brother\'s son'),
            ], [
                Kit::question('Who asks the questions?', ['Marta', 'Rui', 'Nobody'], 'Marta'),
                Kit::question('Is Rui\'s brother older?', ['Yes', 'No', 'The conversation does not say.'], 'Yes'),
                Kit::question('How many people speak?', ['One', 'Two', 'Three'], 'Two'),
            ], [Kit::word('a irmã'), Kit::word('casado'), Kit::word('solteiro'), Kit::word('o irmão'), Kit::word('mais velho'), Kit::word('o filho'), Kit::word('os avós', 'avós')], 'passages', $set),
            Kit::readPassage($stage, 'check.a.read_passage.pai', 'Read the conversation.', [
                Kit::line('Ana', 'Marta, quem é o Rui?'),
                Kit::line('Marta', 'É o meu irmão. A minha família está aqui.'),
                Kit::line('Ana', 'E o teu pai?'),
                Kit::line('Marta', 'O meu pai está aqui com a minha mãe.'),
            ], [
                Kit::question('Who is Rui?', ['Marta\'s brother', 'Marta\'s son', 'Marta\'s father'], 'Marta\'s brother'),
                Kit::question('Who is here with Marta\'s father?', ['Her mother', 'Her sister', 'Her grandparents'], 'Her mother'),
            ], [Kit::word('a família'), Kit::word('o pai'), Kit::word('a mãe'), Kit::word('o irmão')], 'passages', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.irma', 'Tens uma irmã ou um irmão?', 'Do you have a sister or a brother?', [['sim', 'não', 'tenho', 'irmã', 'irmão', 'irmãos', 'uma', 'um']], 'Tenho uma irmã.', [Kit::word('a irmã', 'irmã')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.solteiro', 'O teu pai é solteiro?', 'Is your father single?', [['sim', 'não', 'é', 'solteiro', 'casado']], 'Não, o meu pai é casado.', [Kit::word('solteiro')], 'speaking', $set),
            Kit::speakAnswer($stage, 'check.a.speak_answer.mais-velho', 'Quem é mais velho, o Rui ou a Ana?', 'Who is older, Rui or Ana?', [['rui', 'ana', 'é'], ['velho', 'velha', 'mais']], 'O Rui é mais velho.', [Kit::word('mais velho')], 'speaking', $set),
        ];
    }

    /** @return list<AuthoredExercise> */
    private function checkB(): array
    {
        $stage = Stage::Check;
        $set = 'b';

        return [
            Kit::translate($stage, 'check.b.translate.mae-pai', 'My mother is here with my father.', ['A minha mãe está aqui com o meu pai.'], [Kit::word('a mãe', 'mãe'), Kit::word('o pai', 'pai'), Kit::form('a minha')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.irma', 'Her sister is married.', ['A sua irmã é casada.', 'A sua irmã está casada.'], [Kit::word('a irmã', 'irmã'), Kit::word('casado', 'casada'), Kit::form('a sua')], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.filhos', 'My sons are older.', ['Os meus filhos são mais velhos.'], [Kit::word('o filho', 'filhos'), Kit::word('mais velho', 'mais velhos'), Kit::form('os meus', true)], 'sentences', $set),
            Kit::translate($stage, 'check.b.translate.avos', 'Your grandparents are married. (To a friend.)', ['Os teus avós são casados.', 'Os teus avós estão casados.'], [Kit::word('os avós', 'avós'), Kit::word('casado', 'casados'), Kit::form('os teus', true)], 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.a-tua', '___ família está aqui.', 'Your family is here. (To a friend.)', 'a tua', Kit::form('a tua'), null, 'sentences', $set),
            Kit::typeGap($stage, 'check.b.type_gap.o-seu', 'O Rui está com ___ irmão.', 'Rui is with his brother.', 'o seu', Kit::form('o seu'), null, 'sentences', $set),
            Kit::listenType($stage, 'check.b.listen_type.familia', 'A família da Ana está aqui.', 'Ana\'s family is here.', [Kit::word('a família', 'família')], 'dictation', $set, homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
            Kit::listenType($stage, 'check.b.listen_type.irmao-solteiro', 'O irmão da Ana é solteiro.', 'Ana\'s brother is single.', [Kit::word('o irmão', 'irmão'), Kit::word('solteiro')], 'dictation', $set),
            Kit::listenType($stage, 'check.b.listen_type.mae-casada', 'A mãe do Rui é casada.', 'Rui\'s mother is married.', [Kit::word('a mãe', 'mãe'), Kit::word('casado', 'casada')], 'dictation', $set, homophoneNote: 'The first word is the article a (the), not à (to the) and not há (there is).'),
        ];
    }
}
