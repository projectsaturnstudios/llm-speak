<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Actions\API\OpenRouter\ChatCompletion;
use LLMSpeak\Actions\Responses\GenerateServiceResponse;
use LLMSpeak\Schema\LLMResponse;

class OpenRouterDriver extends LLMServiceDriver
{
    public function generate(string $model, array $params): LLMResponse|false
    {
        $results = false;

        if($response = (new ChatCompletion)->handle($model, ...$params))
        {
            $resp = (new GenerateServiceResponse)->handle('open-router', $response);
            $results = $resp->toLLMResponse();
        }

        return $results;
    }
}
