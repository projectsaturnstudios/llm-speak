<?php

namespace LLMSpeak\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Builders\ChatRequestor;

/**
 * @see ChatRequestor
 */
class CreateChatRequest extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'llm-request-builder';
    }
}
