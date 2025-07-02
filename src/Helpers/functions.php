<?php

use LLMSpeak\LLMs\LLMService;
use LLMSpeak\Support\Facades\LLM;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Chat\ChatRequest;

if(!function_exists('speak_with'))
{
    /**
     * @param string $llm_service
     * @param ChatRequest $request
     * @return ChatResult
     * @throws Exception
     */
    function speak_with(string $llm_service, ChatRequest $request, ?string $version = null): ChatResult
    {
        return LLM::driver($llm_service)->text($request, $version);
    }
}

if(!function_exists('stream_with'))
{
    /**
     * @param string $llm_service
     * @param ChatRequest $request
     * @return ChatResult
     * @throws Exception
     */
    function stream_with(string $llm_service, ChatRequest $request, ?string $version = null): ChatResult
    {
        return LLM::driver($llm_service)->stream($request, $version);
    }
}

if(!function_exists('show_structure_to'))
{
    /**
     * @param string $llm_service
     * @param ChatRequest $request
     * @return ChatResult
     * @throws Exception
     */
    function show_structure_to(string $llm_service, ChatRequest $request, ?string $version = null): ChatResult
    {
        return LLM::driver($llm_service)->structured($request, $version);
    }
}
