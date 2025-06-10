<?php

namespace LLMSpeak\Exceptions;

class AnthropicRequestException extends LLMSpeakRequestException
{
    public static function maxTokensRequired(): static
    {
        return new static('Max tokens field is required');
    }
}
