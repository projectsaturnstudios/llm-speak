<?php

namespace LLMSpeak\Contracts;

use LLMSpeak\Communication\LLMChatRequest;
use LLMSpeak\Communication\LLMChatResponse;

interface LLMCommunicationDriverInterface
{
    function getBaseUrl(): string;
    function chat(LLMChatRequest $request): LLMChatResponse;
}
