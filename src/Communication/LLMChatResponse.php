<?php

namespace LLMSpeak\Communication;

use LLMSpeak\Contracts\LLMChatResponseContract;
use LLMSpeak\StandardCompletionResponse;

abstract class LLMChatResponse extends LLMCommunicationObject implements LLMChatResponseContract
{
    public static function make(array $response): static
    {
        $payload = [
            ...$response
        ];
        return static::from($payload);
    }

    public static function error(array $request_payload, array $response): static {
        dd([$response, $request_payload], "Take a quick look at this");
        return new static();
    }

    public function toStandardResponse(): StandardCompletionResponse
    {

    }
}
