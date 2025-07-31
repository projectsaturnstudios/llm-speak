<?php

namespace LLMSpeak\Core\Support\Schema\Conversation;

use LLMSpeak\Core\Enums\ChatRole;

class ChatMessage extends LLMConversationSchema
{
    public function __construct(
        ChatRole $role,
        string $text,
    ) {
        parent::__construct($role, $text);
    }
}


