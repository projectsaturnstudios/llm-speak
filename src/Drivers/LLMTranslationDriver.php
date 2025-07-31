<?php

namespace LLMSpeak\Core\Drivers;

use LLMSpeak\Core\Contracts\LLMTranslatorContract;
use LLMSpeak\Core\Support\Requests\LLMSpeakChatRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakChatResponse;

abstract class LLMTranslationDriver implements LLMTranslatorContract
{
    public function convertRequest(LLMSpeakChatRequest $communique): mixed
    {
        // This method should be implemented by the concrete driver classes.
        throw new \BadMethodCallException('convertRequest method not implemented.');
    }

    public function convertResponse(LLMSpeakChatResponse $communique): mixed
    {
        throw new \BadMethodCallException('convertResponse method not implemented.');
    }

    public function translateRequest(mixed $communique): LLMSpeakChatRequest
    {
        // This method should be implemented by the concrete driver classes.
        throw new \BadMethodCallException('translateRequest method not implemented.');
    }

    public function translateResponse(mixed $communique): LLMSpeakChatResponse
    {
        throw new \BadMethodCallException('translateResponse method not implemented.');
    }
}
