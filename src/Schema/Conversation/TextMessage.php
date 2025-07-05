<?php

namespace LLMSpeak\Schema\Conversation;

use Spatie\LaravelData\Data;

class TextMessage extends ConversationObject
{
    public ?string $connection = null;

    public function __construct(
        public readonly string $role,
        public readonly string $content
    ) {}

    public function addConnection(string $connection): static
    {
        $this->connection = $connection;
        return $this;
    }
}
