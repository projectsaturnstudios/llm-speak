<?php

namespace LLMSpeak\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Exceptions\LLMSpeakRequestException;
use LLMSpeak\Managers\LLMServiceManager;
use LLMSpeak\Schema\Requests\AnthropicRequest;
use LLMSpeak\Schema\Requests\BaseRequestObject;
use LLMSpeak\Schema\Requests\GeminiRequest;
use LLMSpeak\Schema\Requests\OpenAIRequest;
use LLMSpeak\Schema\Requests\OpenRouterRequest;

/**
 * @method static driver(?string $name = null)
 * @method static getBaseUrl(?string $driver = null)
 * @method static getApiKey(?string $driver = null)
 *
 * @see LLMServiceManager
 */
class LLMs extends Facade
{
    /**
     * @param string $provider
     * @param string $model
     * @return BaseRequestObject
     * @throws LLMSpeakRequestException
     */
    public static function prepare(string $provider, string $model): BaseRequestObject
    {
        return match ($provider) {
            'anthropic' => new AnthropicRequest($model),
            'open-ai' => new OpenAIRequest($model),
            'open-router' => new OpenRouterRequest($model),
            'gemini' => new GeminiRequest($model),
            default => throw LLMSpeakRequestException::invalidProvider()
        };
    }

    protected static function getFacadeAccessor(): string
    {
        return 'llm-service-manager';
    }
}
