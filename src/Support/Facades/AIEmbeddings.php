<?php

namespace LLMSpeak\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Core\Managers\EmbeddingsManager;
use LLMSpeak\Core\Drivers\Interaction\ModelEmbeddingsDriver;

/**
 * @method static ModelEmbeddingsDriver driver(?string $name = null)
 * @method static array|null connectionHeaders(?string $connection = null)
 * @method static string|null connectionUrl(?string $connection = null)
 * @method static string|null connectionDefaultModelID(?string $connection = null)
 * @method static ModelEmbeddingsDriver connection(string $name)
 * @method static string defaultConnection(?string $name = null)
 * @method static string connectionDriverName(string $connection)
 *
 * @see \LLMSpeak\Core\Managers\TokenizationManager
 *
 */
class AIEmbeddings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EmbeddingsManager::class;
    }
}
