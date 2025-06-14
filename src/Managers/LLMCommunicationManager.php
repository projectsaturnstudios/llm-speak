<?php

namespace LLMSpeak\Managers;

use Illuminate\Support\Manager;
use LLMSpeak\Drivers\LLMCommunicationServiceDriver;

class LLMCommunicationManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'none';
    }

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

    public static function boot(): void
    {
        app()->singleton('llm-communication-manager', function ($app) {
            $results = new self($app);

            foreach(config('llms.services') as $abstract => $config)
            {
                $driver_class = $config['driver_class'];
                $settings = $config['settings'];
                $results->extend($abstract, fn() => new $driver_class($settings));
            }

            return $results;
        });
    }
}
