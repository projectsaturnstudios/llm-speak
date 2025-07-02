<?php

namespace LLMSpeak\Schema\Conversation;

use Spatie\LaravelData\Data;

class ToolCall extends ConversationObject
{
    public function __construct(
        public readonly string $tool,
        public readonly ?array $input = null,
        public readonly string|int|null $id = null,
    ) {}
}
