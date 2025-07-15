<?php

namespace LLMSpeak\Schema\Embeddings;

use Spatie\LaravelData\Data;

class EmbeddingRequest extends Data
{
    public function __construct(
        public readonly string $model,
        public readonly array|string $messages,
    ) {}
}
