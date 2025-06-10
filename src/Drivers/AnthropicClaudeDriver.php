<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Actions\API\Claude\Messages;
use LLMSpeak\Actions\Responses\GenerateServiceResponse;
use LLMSpeak\Schema\LLMResponse;

class AnthropicClaudeDriver extends LLMServiceDriver
{
    public function generate(string $model, array $params): LLMResponse|false
    {
        $results = false;

        if($response = (new Messages)->handle($model, ...$params))
        {
            $resp = (new GenerateServiceResponse)->handle('anthropic', $response);
            $results = $resp->toLLMResponse();
        }

        return $results;
    }

    public function getAnthropicVersion(): string
    {
        return config('llms.services.anthropic.extra_headers.anthropic-version');
    }

    public function getAnthropicBeta(): string|false
    {
        return config('llms.services.anthropic.extra_headers.anthropic-beta', false) ?? false;
    }
}
