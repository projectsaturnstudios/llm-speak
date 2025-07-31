<?php

namespace LLMSpeak\Core\Support\Schema\Tools;

use Spatie\LaravelData\Data;

class ToolDefinition extends Data
{
    public function __construct(
        public readonly string $tool,
        public readonly string $description,
        public readonly array $inputSchema = [],
        public readonly ?string $name = null,
    ) {}
}
