<?php

namespace LLMSpeak\Core\Support\Schema\Conversation;

use LLMSpeak\Core\Enums\ChatRole;
use Spatie\LaravelData\Data;

class ToolResult extends LLMConversationSchema
{
    public function __construct(
        public readonly string $request_id,
        public readonly string $tool_name,
        public readonly array $results,
    ) {
        parent::__construct(ChatRole::TOOL_RESULT, [
            'request_id' => $request_id,
            'tool_name' => $tool_name,
            'results' => $results,
        ]);
    }
}
