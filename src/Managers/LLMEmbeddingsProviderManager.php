<?php

namespace LLMSpeak\Core\Managers;

use Exception;
use Illuminate\Support\Manager;

class LLMEmbeddingsProviderManager extends Manager
{
    /**
     * @return mixed
     * @throws Exception
     */
    public function createNoneDriver(): mixed
    {
        throw new Exception('No LLM provider configured. Please set a default provider in the llms configuration.');
    }

    public function getDefaultDriver(): string
    {
        return config('llms.embeddings-providers.default', 'none');
    }

    public static function boot(): void
    {
        app()->singleton('llm-embeddings-providers', function ($app) {
            $results = new static($app);

            $add_ons = config('llms.embeddings-providers.drivers', []);
            foreach($add_ons as $add_on => $config)
            {
                $results->extend($add_on, fn() => new $config['llm_speak_driver']($config));
            }

            return $results;
        });
    }
}
