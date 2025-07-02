<?php

namespace LLMSpeak\Schema\SystemPrompts;

use Spatie\LaravelData\Data;

class TextPrompt extends Data
{
    public function __construct(
        public readonly string $content
    ) {}
}
