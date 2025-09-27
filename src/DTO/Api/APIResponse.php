<?php

namespace LLMSpeak\Core\DTO\Api;

use Spatie\LaravelData\Data;

class APIResponse extends Data
{
    public function __construct(
        public readonly int $status,
        public readonly array $headers = [],
        public readonly array $body = [],
    ) {}
}
