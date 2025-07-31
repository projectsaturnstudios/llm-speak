<?php

namespace LLMSpeak\Core\Providers;

use Illuminate\Support\ServiceProvider;
use LLMSpeak\Core\Managers\LLMChatProviderManager;
use LLMSpeak\Core\UniversalCommunicator;

class LLMSpeakServiceProvider extends ServiceProvider
{
    protected array $config = [
        'llms' => __DIR__ .'/../../config/llms.php',
    ];

    protected array $commands = [

    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->registerManagers();
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $this->commands($this->commands);
    }

    protected function registerManagers(): void
    {
        UniversalCommunicator::boot();
    }

    protected function publishConfigs() : void
    {
        $this->publishes([
            $this->config['llms'] => config_path('llms.php'),
        ], 'llms');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }

}
