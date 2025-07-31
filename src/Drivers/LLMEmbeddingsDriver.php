<?php

namespace LLMSpeak\Core\Drivers;

use LLMSpeak\Core\Contracts\LLMEmbeddingsContract;
use LLMSpeak\Core\Support\Requests\LLMSpeakEmbeddingsRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakEmbeddingsResponse;

abstract class LLMEmbeddingsDriver implements LLMEmbeddingsContract
{
    public function convertRequest(LLMSpeakEmbeddingsRequest $communique): mixed
    {
        // This method should be implemented by the concrete driver classes.
        throw new \BadMethodCallException('convertRequest method not implemented.');
    }

    public function convertResponse(LLMSpeakEmbeddingsResponse $communique): mixed
    {
        throw new \BadMethodCallException('convertResponse method not implemented.');
    }

    public function translateRequest(mixed $communique): LLMSpeakEmbeddingsRequest
    {
        // This method should be implemented by the concrete driver classes.
        throw new \BadMethodCallException('translateRequest method not implemented.');
    }

    public function translateResponse(mixed $communique): LLMSpeakEmbeddingsResponse
    {
        throw new \BadMethodCallException('translateResponse method not implemented.');
    }
}
