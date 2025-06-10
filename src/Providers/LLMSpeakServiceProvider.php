<?php

namespace LLMSpeak\Providers;

use Illuminate\Support\ServiceProvider;
use LLMSpeak\Managers\LLMServiceManager;

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
        LLMServiceManager::boot();
    }

    protected function publishConfigs() : void
    {
        $this->publishes([
            $this->config['llms'] => config_path('llms.php'),
        ], 'mcp');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }

}
