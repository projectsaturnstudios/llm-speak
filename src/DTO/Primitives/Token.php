<?php

namespace LLMSpeak\Core\DTO\Primitives;

use LLMSpeak\Core\Enums\PrimitiveType;

readonly class Token extends ModelPrimitive
{
    public function __construct(
        public array $integers,
        public ?array $metadata = null,
    ) {
        parent::__construct(PrimitiveType::TOKEN);
    }

    public function toValue(): array
    {
        return $this->integers;
    }
}
