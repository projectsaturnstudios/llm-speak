<?php

namespace LLMSpeak\Core\Support\Schema\Conversation;

use LLMSpeak\Core\Enums\ChatRole;
use LLMSpeak\Core\Support\Schema\Tools\ToolDefinition;
use Spatie\LaravelData\Data;

class ToolRequest extends LLMConversationSchema
{
    public function __construct(
        public readonly string $request_id,
        public readonly ToolDefinition $tool,
        public readonly array $params,

    ) {
        parent::__construct(ChatRole::TOOL_CALL, [
            'request_id' => $request_id,
            'tool_name' => $this->tool->tool,
            'params' => $params,
        ]);
    }
}
