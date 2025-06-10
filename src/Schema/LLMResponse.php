<?php

namespace LLMSpeak\Schema;

use Spatie\LaravelData\Data;

class LLMResponse extends Data
{
    public array $tool_request = [];

    public function __construct(
        public readonly string $message,
        public readonly string $provider,
        public readonly string $model,
    )
    {

    }

    public function addToolRequest(array $request): static
    {
        $this->tool_request = $request;
        return $this;

    }

    public function hasToolRequest(): bool
    {
        return !empty($this->tool_request);
    }
}
