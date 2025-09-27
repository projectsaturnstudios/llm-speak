<?php

namespace LLMSpeak\Core\DTO\Primitives;

use LLMSpeak\Core\Enums\PrimitiveType;

readonly class ToolCallObject extends ModelPrimitive
{
    public function __construct(
        public string $name,
        public array $args,
        public string $id,
        public ?array $metadata = null,
    ) {
        parent::__construct(PrimitiveType::FUNCTION);
    }

    public function toValue(): array
    {
        return $this->toArray();
    }

    public function toArray() : array
    {
        return [
            'type' => $this->type->value,
            $this->type->value => $this->toValue()
        ];
    }
}
