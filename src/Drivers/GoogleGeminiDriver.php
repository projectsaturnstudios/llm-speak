<?php

namespace LLMSpeak\Drivers;

use LLMSpeak\Actions\API\Gemini\GenerateContent;
use LLMSpeak\Actions\Responses\GenerateServiceResponse;
use LLMSpeak\Schema\LLMResponse;

class GoogleGeminiDriver extends LLMServiceDriver
{
    public function generate(string $model, array $params): LLMResponse|false
    {
        $results = false;

        if($response = (new GenerateContent)->handle($model, ...$params))
        {
            $resp = (new GenerateServiceResponse)->handle('gemini', $response);
            $results = $resp->toLLMResponse();
        }

        return $results;
    }

}
