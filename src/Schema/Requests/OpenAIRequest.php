<?php

namespace LLMSpeak\Schema\Requests;

use LLMSpeak\Exceptions\AnthropicRequestException;
use LLMSpeak\Exceptions\OpenAIRequestException;
use LLMSpeak\Schema\Conversations\ConversationSegment;
use LLMSpeak\Schema\Conversations\DeveloperDecrees;

class OpenAIRequest extends BaseRequestObject
{
    protected string $provider = 'open-ai';

    public function prepareInstructions(array $instructions): static
    {
        foreach($instructions as $instruction) {
            $this->conversation[] = new DeveloperDecrees($instruction);
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

        if(!empty($this->tools))
        {
            $payload['params']['tools'] = $this->tools;
            $payload['params']['tool_choice'] = 'auto';
        }

        // modalities
        // audio
        // frequency_penalty
        // logic_bias
        // logprobs

        if(!empty($this->max_tokens))
        {
            $payload['params']['max_completion_tokens'] = $this->max_tokens;
        }

        return $payload;
    }
}
