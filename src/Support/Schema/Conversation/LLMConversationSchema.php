<?php

namespace LLMSpeak\Core\Support\Schema\Conversation;

use LLMSpeak\Core\Enums\ChatRole;
use Spatie\LaravelData\Data;

abstract class LLMConversationSchema extends Data
{
    public function __construct(
        public readonly ChatRole $role,
        public readonly mixed $content,
    ) {}
}
