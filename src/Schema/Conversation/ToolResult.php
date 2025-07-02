<?php

namespace LLMSpeak\Schema\Conversation;


class ToolResult extends ConversationObject
{
    public function __construct(
        public readonly string $role,
        public readonly string $tool,
        public readonly array|string $result,
        public readonly string|int|null $id,
    ) {}
}
