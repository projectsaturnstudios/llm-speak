<?php

namespace LLMSpeak\Core\Exceptions;

use Exception;

class NeuralModelException extends LLMSpeakException
{
    /**
     * @param string $method
     * @return self
     */
    public static function BadMagicFunctionCall(string $method): self
    {
        return new self("Bad call to magic method: {$method}");
    }

    /**
     * @param string $method
     * @return self
     */
    public static function BadWhereMethodCall(string $method): self
    {
        return new self("Bad call to where method: {$method}");
    }
}
