<?php

namespace LLMSpeak\Core\DTO\Schema\Completions;

use Spatie\LaravelData\Data;
use LLMSpeak\Core\Enums\ConversationRole;
use LLMSpeak\Core\DTO\Primitives\TextObject;

class MultiTextContentMessage extends ContentMessage
{
    /**
     * @param ConversationRole $role
     * @param array<TextObject> $content
     * @param array|null $metadata
     */
    public function __construct(
        public readonly ConversationRole $role,
        public readonly array $content,
        public readonly ?array $metadata = null,

    ) {}

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => array_map(fn(TextObject $text) => $text->toArray(), $this->content),
        ];
    }

}
