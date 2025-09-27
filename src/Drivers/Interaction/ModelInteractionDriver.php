<?php

namespace LLMSpeak\Core\Drivers\Interaction;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelInteractionException;
use LLMSpeak\Core\Contracts\Drivers\Interaction\ModelInteractionDriver as ModelInteractionDriverContract;

abstract class ModelInteractionDriver implements ModelInteractionDriverContract
{
    protected string $config_name;
    protected string $driver_name;

    /**
     * @throws ModelInteractionException
     */
    public function __construct()
    {
        if(!isset($this->config_name)) throw ModelInteractionException::config_name_missing($this::class);
        if(!isset($this->driver_name)) throw ModelInteractionException::driver_name_missing($this::class);
    }

    abstract public function request(array $input, NeuralModel &$neural_model): array;
    abstract public function generateModelsFromResponse(APIResponse $output, NeuralModel $neural_model): array;

    public function endpointUri(): ?string
    {
        return config("{$this->config_name}.drivers.{$this->driver_name}.config.endpoint_uri");
    }

    public function generateEndpointUrl(string $base_url): string
    {
        return "{$base_url}{$this->endpointUri()}";
    }
}
