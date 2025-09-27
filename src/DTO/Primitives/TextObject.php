<?php

namespace LLMSpeak\Core\DTO\Primitives;

use LLMSpeak\Core\Enums\PrimitiveType;

readonly class TextObject extends ModelPrimitive
{
    public function __construct(
        public string $text,
        public ?array $metadata = null,
    ) {
        parent::__construct(PrimitiveType::TEXT);
    }

    public function toValue(): string
    {
        return $this->text;
    }

    public function toArray() : array
    {
        return [
            'type' => $this->type->value,
            $this->type->value => $this->toValue()
        ];
    }
}
