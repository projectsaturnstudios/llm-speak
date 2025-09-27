<?php

namespace LLMSpeak\Core\DTO\Schema\Completions;

use LLMSpeak\Core\DTO\Primitives\TextObject;
use LLMSpeak\Core\DTO\Primitives\ToolCallObject;
use LLMSpeak\Core\Enums\ConversationRole;

class ToolCallsMessage extends ContentMessage
{
    public readonly ConversationRole $role;

    /**
     * @param array<ToolCallObject> $tool_calls
     * @param ?TextObject $content
     * @param array|null $metadata
     */
    public function __construct(
        public readonly array $tool_calls,
        public readonly ?TextObject $content = null,
        public readonly ?array $metadata = null,
    ) {
        $this->role = ConversationRole::ASSISTANT;
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->content->toValue(),
            'tool_calls' => $this->tool_calls,
        ];
    }
}
