<?php

namespace LLMSpeak\Core\DTO\Schema\Completions;

use Spatie\LaravelData\Data;
use LLMSpeak\Core\Enums\ConversationRole;
use LLMSpeak\Core\DTO\Primitives\TextObject;

class TextOnlyContentMessage extends ContentMessage
{
    public function __construct(
        public readonly ConversationRole $role,
        public readonly TextObject $content,
        public readonly ?array $metadata = null,

    ) {}

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->content->toValue(),
        ];
    }

}
