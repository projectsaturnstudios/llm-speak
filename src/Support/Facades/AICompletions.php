<?php

namespace LLMSpeak\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Core\Managers\ChatCompletionsManager;
use LLMSpeak\Core\Managers\InferenceManager;
use LLMSpeak\Core\Drivers\Interaction\ModelCompletionsDriver;

/**
 * @method static ModelCompletionsDriver driver(?string $name = null)
 * @method static array|null connectionHeaders(?string $connection = null)
 * @method static string|null connectionUrl(?string $connection = null)
 * @method static string|null connectionDefaultModelID(?string $connection = null)
 * @method static ModelCompletionsDriver connection(string $name)
 * @method static string defaultConnection(?string $name = null)
 * @method static string connectionDriverName(string $connection)
 *
 * @see \LLMSpeak\Core\Managers\InferenceManager
 *
 */
class AICompletions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChatCompletionsManager::class;
    }
}
