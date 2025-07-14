<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Google\Support\Facades\Gemini;
use LLMSpeak\HuggingFace\HuggingFaceCallResult;
use LLMSpeak\HuggingFace\Support\Facades\HuggingFace;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Conversation\ToolCall;

class HuggingFaceLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(HuggingFace::class))
        {
            throw new \Exception("HuggingFace LLM Service is not available. Please install the Hugging Face package.");
        }
    }
    public function text(ChatRequest $request): ChatResult
    {
        $setup = HuggingFace::completions()
            ->withApikey($request->credentials['api_key'] ?? HuggingFace::api_key())
            ->withModel($request->model);

        if($request->max_tokens) $setup = $setup->withMaxTokens($request->max_tokens);
        if($request->temperature) $setup = $setup->withTemperature($request->temperature);
        if($request->tools) $setup = $setup->withTools($request->tools);

        /** @var HuggingFaceCallResult $response */
        $response = $setup->withMessages($request->messages)
            ->handle();

        foreach($response->choices as $choice)
        {

        }

    }
}
