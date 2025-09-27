<?php

namespace LLMSpeak\Core\Exceptions;

use Exception;

class ModelInteractionException extends LLMSpeakException
{
    public static function driver_name_missing(string $driver_class): static
    {
        return new self("Driver name not set in class: {$driver_class}");
    }

    public static function config_name_missing(string $driver_class): static
    {
        return new self("Config name not set in class: {$driver_class}");
    }

    public static function invalidModelType(string $driver_class): static
    {
        return new self("Invalid model type provided to driver: {$driver_class}");
    }
}
