<?php

namespace LLMSpeak\Schema\Chat;

use Spatie\LaravelData\Data;

class ChatRequest extends Data
{
    public function __construct(
        public readonly string $model,
        public readonly array $messages,
        public readonly array $credentials = [],
        public readonly ?array $system_prompts = null,
        public readonly ?int $max_tokens = null,
        public readonly ?array $tools = null,
        public readonly ?float $temperature = null,
    ) {}
}
