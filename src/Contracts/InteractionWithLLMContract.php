<?php

namespace LLMSpeak\Contracts;

use LLMSpeak\Communication\LLMChatRequest;
use LLMSpeak\Communication\LLMChatResponse;

interface InteractionWithLLMContract
{
    function handle(LLMChatRequest $request): LLMChatResponse;
}
