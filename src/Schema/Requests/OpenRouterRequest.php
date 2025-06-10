<?php

namespace LLMSpeak\Schema\Requests;

use LLMSpeak\Exceptions\AnthropicRequestException;
use LLMSpeak\Exceptions\OpenAIRequestException;
use LLMSpeak\Schema\Conversations\ConversationSegment;

class OpenRouterRequest extends BaseRequestObject
{
    protected string $provider = 'open-router';

    public function prepareInstructions(array $instructions): static
    {
        foreach($instructions as $instruction)
        {
            $this->addTextToConversation('user', $instruction);
        }

        return $this;
    }

    /**
     * @return array
     * @throws OpenAIRequestException
     */
    public function toParams(): array
    {
        $payload = [
            'model' => $this->model,
            'params' => []
        ];

        if(!empty($this->conversation))
        {
            $payload['params']['messages'] = $this->transformConvo();
        }
        else throw OpenAIRequestException::emptyConversation();

        if(!empty($this->tools)) $payload['params']['tools'] = $this->tools;

        // models
        // provider
        // reasoning
        // usage
        // transforms

        if(!empty($this->max_tokens))
        {
            $payload['params']['max_tokens'] = $this->max_tokens;
        }

        return $payload;
    }


}
