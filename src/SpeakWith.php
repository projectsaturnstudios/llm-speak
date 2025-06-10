<?php

namespace LLMSpeak;

use LLMSpeak\Schema\LLMResponse;
use LLMSpeak\Schema\Requests\BaseRequestObject;
use LLMSpeak\Support\Facades\LLMs;

class SpeakWith
{
    public static function llm(BaseRequestObject $message): LLMResponse|false
    {
        return LLMs::driver($message->getProvider())->generate(...$message->toParams());
    }
}
