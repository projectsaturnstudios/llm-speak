<?php

namespace LLMSpeak\Schema\Conversation;

use Spatie\LaravelData\Data;

class TextMessage extends ConversationObject
{
    public function __construct(
        public readonly string $role,
        public readonly string $content
    ) {}
}
