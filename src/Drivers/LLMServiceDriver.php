<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Contracts\LLMServiceInterface;
use LLMSpeak\Schema\LLMResponse;

abstract class LLMServiceDriver implements LLMServiceInterface
{
    protected string $api_url;
    protected string $api_key;

    public function __construct(array $config)
    {
        $this->api_url = $config['url'];
        $this->api_key = $config['api_key'];
    }

    abstract public function generate(string $model, array $params): LLMResponse|false;

    public function getBaseUrl(): string
    {
        return $this->api_url;
    }

    public function getApiKey(): string
    {
        return $this->api_key;
    }
}
