<?php

namespace LLMSpeak\Core\DTO\Primitives;

use LLMSpeak\Core\Enums\PrimitiveType;

abstract readonly class ModelPrimitive
{
    public function __construct(
        public readonly PrimitiveType $type,
    ) {}

    abstract public function toValue(): array|string;

    public function toArray() : array
    {
        return [$this->type->value => $this->toValue()];
    }
}
