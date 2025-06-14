<?php

use LLMSpeak\Support\Facades\LLMSpeak;
use LLMSpeak\Communication\LLMChatRequest;
use LLMSpeak\Communication\LLMChatResponse;
use LLMSpeak\Drivers\LLMCommunicationServiceDriver;

if(!function_exists('speak_with'))
{
    function speak_with(string $llm_driver, ?LLMChatRequest $request = null): LLMCommunicationServiceDriver|LLMChatResponse
    {
        $driver = LLMSpeak::driver($llm_driver);
        if(!is_null($request)) return $driver->chat($request);
        return $driver;
    }
}

if(!function_exists('chat_with'))
{
    function chat_with(string $llm_driver, ?LLMChatRequest $request = null): LLMCommunicationServiceDriver|LLMChatResponse
    {
        return speak_with($llm_driver, $request);
    }
}
