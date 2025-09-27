<?php

namespace LLMSpeak\Core\DTO\Schema\Tokenization;

use Spatie\LaravelData\Data;
use LLMSpeak\Core\DTO\Primitives\Token;

class TokenizedText extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $string_represented,
        public readonly Token $token,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'string_represented' => $this->string_represented,
            'token' => $this->token->toValue(),
        ];
    }
}
