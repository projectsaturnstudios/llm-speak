<?php

namespace LLMSpeak\Contracts;

interface LLMChatRequestContract
{
    public function toPayload(): array;
}
