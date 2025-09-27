<?php

namespace LLMSpeak\Core\DTO\Primitives;

use LLMSpeak\Core\Enums\PrimitiveType;

readonly class VectorEmbedding extends ModelPrimitive
{
    /**
     * @param array<float> $numbers
     */
    public function __construct(
        public array $numbers,
        public ?array $metadata = null,
    ) {
        parent::__construct(PrimitiveType::VECTOR);
    }

    /**
     * @return array<float>
     */
    public function toValue(): array
    {
        return $this->numbers;
    }
}
