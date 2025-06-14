<?php

namespace LLMSpeak\Exceptions;

use DomainException;

class InteractionWithAnLLMException extends DomainException
{
    public static function IncompatibleLLMChatRequest(string $class_name): static
    {
        return new static("Using this Actions requires an LLMChatRequest object of {$class_name} type.");
    }

    public static function missingDependency(string $variable): static
    {
        return new static("Class is missing required variable '{$variable}'");
    }
}
