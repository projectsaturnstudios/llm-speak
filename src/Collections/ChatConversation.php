<?php

namespace LLMSpeak\Core\Collections;

use Illuminate\Support\Collection;
use LLMSpeak\Core\DTO\Schema\Completions\ContentMessage;
use LLMSpeak\Core\NeuralModels\NeuralModel;

class ChatConversation extends Collection
{
    /**
     * @param<ContentMessage> $items
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }
}
