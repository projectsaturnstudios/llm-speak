<?php

namespace LLMSpeak\Managers;

use Illuminate\Support\Manager;
use LLMSpeak\Drivers\AnthropicClaudeDriver;
use LLMSpeak\Drivers\GoogleGeminiDriver;
use LLMSpeak\Drivers\LLMServiceDriver;
use LLMSpeak\Drivers\OpenAIChatGPTDriver;
use LLMSpeak\Drivers\OpenRouterDriver;

class LLMServiceManager extends Manager
{
    public function getBaseUrl(?string $driver = null): string
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->getBaseUrl();
    }

    public function getApiKey(?string $driver = null): string
    {
        $driver = $driver ?? $this->getDefaultDriver();
        return $this->driver($driver)->getApiKey();
    }

    public function createOpenAIDriver(): LLMServiceDriver
    {
        return new OpenAIChatGPTDriver(config('llms.services.open-ai'));
    }

    public function createGeminiDriver(): LLMServiceDriver
    {
        return new GoogleGeminiDriver(config('llms.services.gemini'));
    }

    public function createAnthropicDriver(): LLMServiceDriver
    {
        return new AnthropicClaudeDriver(config('llms.services.anthropic'));
    }

    public function createOpenRouterDriver(): LLMServiceDriver
    {
        return new OpenRouterDriver(config('llms.services.open-router'));
    }

    public function createNoneDriver(): null
    {
        return null;
    }

    public function getDefaultDriver(): string
    {
        return 'none';
    }

    public static function boot(): void
    {
        app()->singleton('llm-service-manager', function ($app) {
            $results = new self($app);

            foreach(config('llms.services.add-ons') as $abstract => $config)
            {
                $results->extend($abstract, fn() => new $config['driver_class']($config));
            }

            return $results;
        });
    }
}
