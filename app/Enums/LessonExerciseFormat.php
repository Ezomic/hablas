<?php

declare(strict_types=1);

namespace App\Enums;

enum LessonExerciseFormat: string
{
    case TeachWord = 'teach_word';
    case TeachGrammar = 'teach_grammar';
    case ChooseMeaning = 'choose_meaning';
    case ChooseWord = 'choose_word';
    case MatchPairs = 'match_pairs';
    case ChooseGap = 'choose_gap';
    case ReadPassage = 'read_passage';
    case TypeWord = 'type_word';
    case TypeGap = 'type_gap';
    case BuildSentence = 'build_sentence';
    case TranslateSentence = 'translate_sentence';
    case TransformSentence = 'transform_sentence';
    case WriteGuided = 'write_guided';
    case ListenChoose = 'listen_choose';
    case ListenPair = 'listen_pair';
    case ListenType = 'listen_type';
    case ListenPassage = 'listen_passage';
    case SpeakRepeat = 'speak_repeat';
    case SpeakAnswer = 'speak_answer';

    public function family(): ?ExerciseFamily
    {
        return match ($this) {
            self::TeachWord, self::TeachGrammar => null,
            self::ChooseMeaning, self::ChooseWord, self::MatchPairs, self::ChooseGap, self::ReadPassage => ExerciseFamily::Choice,
            self::TypeWord, self::TypeGap, self::BuildSentence, self::TranslateSentence, self::TransformSentence, self::WriteGuided => ExerciseFamily::Writing,
            self::ListenChoose, self::ListenPair, self::ListenType, self::ListenPassage => ExerciseFamily::Listening,
            self::SpeakRepeat, self::SpeakAnswer => ExerciseFamily::Speaking,
        };
    }

    public function skill(): ?Skill
    {
        return $this->family()?->skill();
    }

    public function isTeach(): bool
    {
        return $this->family() === null;
    }

    public function answersInLearnedLanguage(): bool
    {
        return in_array($this, [
            self::TypeWord,
            self::TypeGap,
            self::BuildSentence,
            self::TranslateSentence,
            self::TransformSentence,
            self::WriteGuided,
            self::ListenType,
            self::ListenPair,
            self::ChooseWord,
            self::ChooseGap,
            self::SpeakRepeat,
            self::SpeakAnswer,
        ], true);
    }

    public function isSkippable(): bool
    {
        return in_array($this->family(), [ExerciseFamily::Listening, ExerciseFamily::Speaking], true);
    }

    /**
     * Graded word by word against accepted answers, which is what lets a
     * format act as a mastery probe in a check.
     */
    public function isExactMatch(): bool
    {
        return in_array($this, [
            self::TypeWord,
            self::TypeGap,
            self::TranslateSentence,
            self::TransformSentence,
            self::ListenType,
            self::BuildSentence,
        ], true);
    }

    /**
     * Whether the format may probe mastery. Tiles show every word of the
     * answer, so build_sentence is exact-match for grading but never a probe.
     */
    public function canProbe(): bool
    {
        return $this->isExactMatch() && $this !== self::BuildSentence;
    }

    /**
     * Whether the answer is chosen, rebuilt or copied rather than produced,
     * so it cannot be evidence for a skill level.
     */
    public function isScaffolded(): bool
    {
        return in_array($this, [
            self::TeachWord,
            self::TeachGrammar,
            self::ChooseMeaning,
            self::ChooseWord,
            self::MatchPairs,
            self::ChooseGap,
            self::BuildSentence,
            self::ListenChoose,
            self::ListenPair,
            self::SpeakRepeat,
        ], true);
    }

    public function isChoice(): bool
    {
        return in_array($this, [
            self::ChooseMeaning,
            self::ChooseWord,
            self::ChooseGap,
            self::ListenChoose,
            self::ListenPair,
        ], true);
    }

    public function isPassage(): bool
    {
        return in_array($this, [self::ReadPassage, self::ListenPassage], true);
    }

    public function isSpeaking(): bool
    {
        return $this->family() === ExerciseFamily::Speaking;
    }
}
