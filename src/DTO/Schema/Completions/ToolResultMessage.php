<?php

namespace LLMSpeak\Core\DTO\Schema\Completions;

use LLMSpeak\Core\Enums\ConversationRole;

class ToolResultMessage extends ContentMessage
{
    public readonly ConversationRole $role;

    public function __construct(
        public readonly string $id,
        public readonly string $content,
        public readonly ?array $metadata = null,

    ) {
        $this->role = ConversationRole::TOOL;
    }
}
