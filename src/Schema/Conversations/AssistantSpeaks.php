<?php

namespace LLMSpeak\Schema\Conversations;

class AssistantSpeaks extends ConversationSegment
{
    public function __construct(
        string $content
    )
    {
        parent::__construct('assistant', $content);
    }
}
