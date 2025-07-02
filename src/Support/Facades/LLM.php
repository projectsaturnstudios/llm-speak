<?php

namespace LLMSpeak\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\LLMs\LLMService;

/**
 *
 * @method static LLMService driver(string $name = null)
 *
 * @see \LLMSpeak\LLMManager
 */
class LLM extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'llm-manager';
    }
}
