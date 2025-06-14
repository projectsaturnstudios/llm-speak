<?php

namespace LLMSpeak;

use LLMSpeak\Communication\LLMChatResponse;

/**
 * The standardized response that all LLMChatResponse should convert to
 * when utilizing driver-agnostic communications with an LLMSpeak-compatible
 * model integration.
 */
class StandardCompletionResponse extends LLMChatResponse
{
    public ?string $id = null;
    public ?array $output = null;

    public function __construct(
        public readonly string $model,
    )
    {

    }

    public function addId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function addOutput(array $output): static
    {
        $this->output = $output;
        return $this;
    }


    public function toStandardResponse(): StandardCompletionResponse
    {
        return $this;
    }
}
