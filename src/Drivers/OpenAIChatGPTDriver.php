<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Actions\API\ChatGPT\ChatCompletion;
use LLMSpeak\Actions\Responses\GenerateServiceResponse;
use LLMSpeak\Schema\LLMResponse;

class OpenAIChatGPTDriver extends LLMServiceDriver
{
    public function generate(string $model, array $params): LLMResponse|false
    {
        $results = false;

        if($response = (new ChatCompletion())->handle($model, ...$params))
        {
            $resp = (new GenerateServiceResponse)->handle('open-ai', $response);
            $results = $resp->toLLMResponse();
        }

        return $results;
    }
}
