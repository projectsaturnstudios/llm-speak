<?php

namespace LLMSpeak\Providers;

use Agents\GoodBuddy\Console\Commands\AgentTestCommand;
use Illuminate\Support\ServiceProvider;
use LLMSpeak\Builders\ChatRequestor;
use LLMSpeak\LLMManager;

//use LLMSpeak\Managers\LLMCommunicationManager;

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
        LLMManager::boot();
        ChatRequestor::boot();
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
