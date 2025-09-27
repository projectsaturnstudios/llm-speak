<?php

namespace LLMSpeak\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Core\Managers\InferenceManager;
use LLMSpeak\Core\Drivers\Interaction\ModelInferenceDriver;

/**
 * @method static ModelInferenceDriver driver(?string $name = null)
 * @method static array|null connectionHeaders(?string $connection = null)
 * @method static string|null connectionUrl(?string $connection = null)
 * @method static string|null connectionDefaultModelID(?string $connection = null)
 * @method static ModelInferenceDriver connection(string $name)
 * @method static string defaultConnection(?string $name = null)
 * @method static string connectionDriverName(string $connection)
 *
 * @see \LLMSpeak\Core\Managers\InferenceManager
 *
 */
class AIInference extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InferenceManager::class;
    }
}
