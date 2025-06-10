<?php

namespace LLMSpeak\Schema\Conversations;

class UserTalks extends ConversationSegment
{
    public function __construct(
        string $content
    )
    {
        parent::__construct('user', $content);
    }
}
