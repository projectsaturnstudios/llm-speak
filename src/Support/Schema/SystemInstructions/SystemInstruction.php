<?php

namespace LLMSpeak\Core\Support\Schema\SystemInstructions;

use LLMSpeak\Core\Enums\ChatRole;
use LLMSpeak\Core\Support\Schema\Conversation\LLMConversationSchema;

class SystemInstruction extends LLMConversationSchema
{
    public function __construct(
        string $text,
    ) {
        parent::__construct(ChatRole::SYSTEM, $text);
    }
}


