<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Communication\LLMChatRequest;
use LLMSpeak\Communication\LLMChatResponse;
use LLMSpeak\Contracts\LLMCommunicationDriverInterface;

abstract class LLMCommunicationServiceDriver implements LLMCommunicationDriverInterface
{
    protected string $api_url;
    protected string $api_key;
    protected string $chat_class;

    public function chat(LLMChatRequest $request): LLMChatResponse
    {
        return (new $this->chat_class)->handle($request);
    }

    public function say(LLMChatRequest $request): LLMChatResponse
    {
        return $this->chat($request);
    }

    public function __construct(array $config)
    {
        $this->api_url = $config['url'];
        $this->api_key = $config['api_key'];
    }

    public function getBaseUrl(): string
    {
        return $this->api_url;
    }

    public function getApiKey(): string
    {
        return $this->api_key;
    }
}
