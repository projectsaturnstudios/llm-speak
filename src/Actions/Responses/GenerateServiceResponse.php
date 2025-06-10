<?php

namespace LLMSpeak\Actions\Responses;

use LLMSpeak\Schema\LLMResponse;
use LLMSpeak\Schema\Responses\AnthropicResponse;
use LLMSpeak\Schema\Responses\BaseResponseObject;
use LLMSpeak\Schema\Responses\ChatGPTResponse;
use LLMSpeak\Schema\Responses\GeminiResponse;
use LLMSpeak\Schema\Responses\OpenRouterResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\VarDumper\VarDumper;

class GenerateServiceResponse
{
    use AsAction;

    public function handle(string $service, array $raw_llm_response): ?BaseResponseObject
    {
        switch($service)
        {
            case 'anthropic':
                $results = new AnthropicResponse($raw_llm_response);
                break;

            case 'gemini':
                VarDumper::dump(['gemini - response', $raw_llm_response]);
                $results = new GeminiResponse($raw_llm_response);
                break;

            case 'open-ai':
                $results = new ChatGPTResponse($raw_llm_response);
                break;

            case 'open-router':
                $results = new OpenRouterResponse($raw_llm_response);
                break;

            default:
                $results =  null;
        }

        return $results;
    }
}
