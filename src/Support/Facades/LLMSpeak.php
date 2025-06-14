<?php

namespace LLMSpeak\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Managers\LLMCommunicationManager;

/**
 * @method static driver(?string $name = null)
 * @method static getApiKey(?string $driver = null)
 * @method static getBaseUrl(?string $driver = null)
 *
 * @see LLMCommunicationManager
 */
class LLMSpeak extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'llm-communication-manager';
    }
}
