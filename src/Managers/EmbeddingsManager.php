<?php

namespace LLMSpeak\Core\Managers;

use Illuminate\Container\Attributes\Singleton;
use LLMSpeak\Core\Drivers\Interaction\ModelEmbeddingsDriver;

#[Singleton]
class EmbeddingsManager extends InteractionManager
{
    public function connectionHeaders(?string $connection = null): ?array
    {
        $connection = $connection ?? $this->defaultConnection();
        return embeddings_connection_headers($connection);
    }

    public function connectionUrl(?string $connection = null): ?string
    {
        $connection = $connection ?? $this->defaultConnection();
        return embeddings_connection_url($connection);
    }

    public function connectionDefaultModelID(?string $connection = null): ?string
    {
        $connection = $connection ?? $this->defaultConnection();
        return embeddings_connection_default_model($connection);
    }

    public function connection(string $name): ?ModelEmbeddingsDriver
    {
        $driver_name = $this->connectionDriverName($name);
        return $this->driver($driver_name);
    }

    public function defaultConnection(): string
    {
        return default_embeddings_connection_name();
    }

    public function connectionDriverName(string $connection): string
    {
        return embeddings_connection_driver_name($connection);
    }

    public function getDefaultDriver(): string
    {
        return $this->connectionDriverName($this->defaultConnection());
    }
}
