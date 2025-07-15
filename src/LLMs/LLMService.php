<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Contracts\LLMServiceContract;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Embeddings\EmbeddingRequest;
use LLMSpeak\Schema\Embeddings\EmbeddingResult;

abstract class LLMService implements LLMServiceContract
{
    abstract public static function convertConversation(array $convo): array;
    abstract public static function convertSystemPrompt(array $convo): array|string;
    abstract public static function defaultCredentials(): array;

    public function text(ChatRequest $request): ChatResult
    {
        throw new \Exception("Text responses are not supported by this LLM service.");
    }

    public function embeddings(EmbeddingRequest $request): EmbeddingResult
    {
        throw new \Exception("Embeddings are not supported by this LLM service.");
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
