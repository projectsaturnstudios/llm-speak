<?php

namespace LLMSpeak\Core\Exceptions;

use Exception;

class LLMSpeakException extends Exception
{
    public static function featureNotSupported(string $feature, ?string $model = null): self
    {
        $message = $model
            ? "The feature '{$feature}' is not supported by the model '{$model}'."
            : "The feature '{$feature}' is not supported by the current model.";

        return new self($message);
    }
}
