<?php

namespace LLMSpeak\Contracts;

use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Chat\ChatResult;

interface LLMServiceContract
{
    public function text(ChatRequest $request): ChatResult;
    public function structured();
    public function stream();
}
