<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Contracts\LLMServiceContract;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Chat\ChatResult;

abstract class LLMService implements LLMServiceContract
{
    abstract public static function convertConversation(array $convo): array;
    abstract public static function convertSystemPrompt(array $convo): array;
    abstract public static function defaultCredentials(): array;

    public function text(ChatRequest $request): ChatResult
    {
        throw new \Exception("Text responses are not supported by this LLM service.");
    }

    public function structured()
    {
        throw new \Exception("Structured responses are not supported by this LLM service.");
    }

    public function stream()
    {
        throw new \Exception("Streaming responses are not supported by this LLM service.");
    }
}
