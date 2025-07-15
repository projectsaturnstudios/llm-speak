<?php

namespace LLMSpeak\Schema\Embeddings;

use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use Spatie\LaravelData\Data;

class EmbeddingResult extends Data
{
    public array $embeddings = [];
    public ?string $model = null;

    public function __construct() {}

    public function addEmbedding(array $embedding): static
    {
        $this->embeddings[] = $embedding;
        return $this;
    }

    public function addModel(string $model): static
    {
        $this->model = $model;
        return $this;
    }
}
