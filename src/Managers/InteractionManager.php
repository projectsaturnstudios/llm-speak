<?php

namespace LLMSpeak\Core\Managers;

use Illuminate\Support\Manager;
use Illuminate\Container\Attributes\Singleton;
use LLMSpeak\Core\Drivers\Interaction\ModelInteractionDriver;
use LLMSpeak\Core\Drivers\Interaction\ModelTokenizationDriver;

abstract class InteractionManager extends Manager
{
    abstract public function connectionHeaders(?string $connection = null): ?array;

    abstract public function connectionUrl(?string $connection = null): ?string;

    abstract public function connectionDefaultModelID(?string $connection = null): ?string;

    abstract public function connection(string $name): ?ModelInteractionDriver;

    abstract public function defaultConnection(): string;

    abstract public function connectionDriverName(string $connection): string;
}
