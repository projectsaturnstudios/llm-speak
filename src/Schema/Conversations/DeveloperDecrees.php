<?php

namespace LLMSpeak\Schema\Conversations;

class DeveloperDecrees extends ConversationSegment
{
    public function __construct(
        string $content
    )
    {
        parent::__construct('developer', $content);
    }
}
