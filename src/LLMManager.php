<?php

namespace LLMSpeak;

use Illuminate\Support\Manager;
use LLMSpeak\LLMs\AnthropicLLMService;
use LLMSpeak\LLMs\GeminiLLMService;
use LLMSpeak\LLMs\HuggingFaceLLMService;
use LLMSpeak\LLMs\LLMService;
use LLMSpeak\LLMs\OllamaLLMService;

class LLMManager extends Manager
{
    public function createAnthropicDriver(): LLMService
    {
        return new AnthropicLLMService();
    }

    public function createGeminiDriver(): LLMService
    {
        return new GeminiLLMService();
    }

    public function createHuggingFaceDriver(): LLMService
    {
        return new HuggingFaceLLMService();
    }

    public function createOllamaDriver(): LLMService
    {
        return new OllamaLLMService();
    }

    public function getDefaultDriver(): string
    {
        return 'none';
    }

    public static function boot(): void
    {
        app()->singleton('llm-manager', function ($app) {
            $results = new static($app);

            return $results;
        });
    }
}
