<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Models\GrammarPoint;
use App\Models\VocabularyItem;
use Illuminate\Database\Eloquent\Model;

/**
 * Points at a vocabulary item or grammar point. The key is how accepted
 * answers name the words that belong to a target.
 */
final readonly class TargetRef
{
    public function __construct(
        public string $type,
        public int $id,
    ) {}

    public static function for(Model $model): self
    {
        $id = $model->getKey();

        return new self($model->getMorphClass(), is_int($id) ? $id : (int) (is_scalar($id) ? $id : 0));
    }

    public static function vocabulary(int $id): self
    {
        return new self((new VocabularyItem)->getMorphClass(), $id);
    }

    public static function grammar(int $id): self
    {
        return new self((new GrammarPoint)->getMorphClass(), $id);
    }

    public static function keyFor(string $type, int $id): string
    {
        return $type.':'.$id;
    }

    public function key(): string
    {
        return self::keyFor($this->type, $this->id);
    }

    public function isGrammar(): bool
    {
        return $this->type === (new GrammarPoint)->getMorphClass();
    }
}
