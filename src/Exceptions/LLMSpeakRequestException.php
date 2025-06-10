<?php

namespace LLMSpeak\Exceptions;

class LLMSpeakRequestException extends \Exception
{
    public static function emptyConversation(): static
    {
        return new static('A conversation cannot be empty');
    }

    public static function invalidProvider(): static
    {
        return new static('Invalid provider');
    }
}
