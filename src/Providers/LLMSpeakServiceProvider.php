<?php

namespace LLMSpeak\Providers;

use Illuminate\Support\ServiceProvider;
use LLMSpeak\Managers\LLMCommunicationManager;

class LLMSpeakServiceProvider extends ServiceProvider
{
    protected array $config = [
        'llms' => __DIR__ .'/../../config/llms.php',
    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->registerManagers();
    }

    protected function registerManagers(): void
    {
        LLMCommunicationManager::boot();
    }

    protected function publishConfigs() : void
    {
        $this->publishes([
            $this->config['llms'] => config_path('llms.php'),
        ], 'llm');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }

}
