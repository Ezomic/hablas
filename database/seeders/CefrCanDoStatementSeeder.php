<?php

namespace Database\Seeders;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\CefrCanDoStatement;
use Illuminate\Database\Seeder;

/**
 * Global-scale CEFR self-assessment "I can..." statements (Council of Europe
 * grid), one per skill per level. Not language-specific — the same six
 * levels apply regardless of which language is active.
 */
class CefrCanDoStatementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->statements() as $statement) {
            CefrCanDoStatement::query()->updateOrCreate(
                ['cefr_level' => $statement['cefr_level'], 'skill' => $statement['skill']],
                ['statement_text' => $statement['statement_text']],
            );
        }
    }

    /**
     * @return array<int, array{cefr_level: CefrLevel, skill: Skill, statement_text: string}>
     */
    private function statements(): array
    {
        return [
            ['cefr_level' => CefrLevel::A1, 'skill' => Skill::Reading, 'statement_text' => 'I can understand familiar names, words and very simple sentences, for example on notices and posters or in catalogues.'],
            ['cefr_level' => CefrLevel::A2, 'skill' => Skill::Reading, 'statement_text' => 'I can read very short, simple texts and find specific, predictable information in simple everyday material.'],
            ['cefr_level' => CefrLevel::B1, 'skill' => Skill::Reading, 'statement_text' => 'I can understand texts that consist mainly of high frequency everyday or job-related language.'],
            ['cefr_level' => CefrLevel::B2, 'skill' => Skill::Reading, 'statement_text' => 'I can read articles and reports concerned with contemporary problems in which the writers adopt particular attitudes or viewpoints.'],
            ['cefr_level' => CefrLevel::C1, 'skill' => Skill::Reading, 'statement_text' => 'I can understand long and complex factual and literary texts, appreciating distinctions of style.'],
            ['cefr_level' => CefrLevel::C2, 'skill' => Skill::Reading, 'statement_text' => 'I can read with ease virtually all forms of the written language, including abstract, structurally complex texts.'],

            ['cefr_level' => CefrLevel::A1, 'skill' => Skill::Listening, 'statement_text' => 'I can recognise familiar words and very basic phrases concerning myself, my family and immediate concrete surroundings when people speak slowly and clearly.'],
            ['cefr_level' => CefrLevel::A2, 'skill' => Skill::Listening, 'statement_text' => 'I can understand phrases and the highest frequency vocabulary related to areas of most immediate personal relevance.'],
            ['cefr_level' => CefrLevel::B1, 'skill' => Skill::Listening, 'statement_text' => 'I can understand the main points of clear standard speech on familiar matters regularly encountered in work, school, leisure, etc.'],
            ['cefr_level' => CefrLevel::B2, 'skill' => Skill::Listening, 'statement_text' => 'I can understand extended speech and lectures and follow even complex lines of argument provided the topic is reasonably familiar.'],
            ['cefr_level' => CefrLevel::C1, 'skill' => Skill::Listening, 'statement_text' => 'I can understand extended speech even when it is not clearly structured and when relationships are only implied.'],
            ['cefr_level' => CefrLevel::C2, 'skill' => Skill::Listening, 'statement_text' => 'I can understand any kind of spoken language, whether live or broadcast, delivered at fast native speed.'],

            ['cefr_level' => CefrLevel::A1, 'skill' => Skill::Speaking, 'statement_text' => 'I can interact in a simple way provided the other person talks slowly and is prepared to repeat or rephrase things.'],
            ['cefr_level' => CefrLevel::A2, 'skill' => Skill::Speaking, 'statement_text' => 'I can communicate in simple and routine tasks requiring a simple and direct exchange of information.'],
            ['cefr_level' => CefrLevel::B1, 'skill' => Skill::Speaking, 'statement_text' => 'I can deal with most situations likely to arise while travelling in an area where the language is spoken.'],
            ['cefr_level' => CefrLevel::B2, 'skill' => Skill::Speaking, 'statement_text' => 'I can interact with a degree of fluency and spontaneity that makes regular interaction with native speakers quite possible.'],
            ['cefr_level' => CefrLevel::C1, 'skill' => Skill::Speaking, 'statement_text' => 'I can express myself fluently and spontaneously without much obvious searching for expressions.'],
            ['cefr_level' => CefrLevel::C2, 'skill' => Skill::Speaking, 'statement_text' => 'I can take part effortlessly in any conversation or discussion and have a good familiarity with idiomatic expressions.'],

            ['cefr_level' => CefrLevel::A1, 'skill' => Skill::Writing, 'statement_text' => 'I can write a short, simple postcard and fill in forms with personal details.'],
            ['cefr_level' => CefrLevel::A2, 'skill' => Skill::Writing, 'statement_text' => 'I can write short, simple notes and messages and a very simple personal letter.'],
            ['cefr_level' => CefrLevel::B1, 'skill' => Skill::Writing, 'statement_text' => 'I can write simple connected text on topics which are familiar or of personal interest.'],
            ['cefr_level' => CefrLevel::B2, 'skill' => Skill::Writing, 'statement_text' => 'I can write clear, detailed text on a wide range of subjects related to my interests.'],
            ['cefr_level' => CefrLevel::C1, 'skill' => Skill::Writing, 'statement_text' => 'I can express myself in clear, well-structured text, expressing points of view at some length.'],
            ['cefr_level' => CefrLevel::C2, 'skill' => Skill::Writing, 'statement_text' => 'I can write clear, smoothly-flowing text in an appropriate style.'],
        ];
    }
}
