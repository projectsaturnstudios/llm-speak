<?php

namespace LLMSpeak\Schema\Requests;

use LLMSpeak\Exceptions\GeminiRequestException;
use LLMSpeak\Schema\Conversations\ConversationSegment;

class GeminiRequest extends BaseRequestObject
{
    protected string $provider = 'gemini';

    public function prepareInstructions(array $instructions): static
    {
        $this->system_prompt = $instructions;

        return $this;
    }
    /**
     * @return array
     * @throws GeminiRequestException
     */
    public function toParams(): array
    {
        $payload = [
            'model' => $this->model,
            'params' => []
        ];

        if(!empty($this->conversation))
        {
            $payload['params']['contents'] = $this->transformConvo();
        }
        else throw GeminiRequestException::emptyConversation();

        if(!empty($this->system_prompt))
        {
            $payload['params']['system_instruction'] = $this->system_prompt;
        }

        if(!empty($this->tools))
        {
            $payload['params']['tools'] = $this->tools;
        }

        return $payload;
    }

    protected function transformConvo(): array
    {
        return array_map(function(ConversationSegment|array $segment){
            return !is_array($segment)
                ? $segment->toParts()
                : ['role' => $segment['role'], 'parts' => [$segment['content'][0]]
                ];
        }, $this->conversation);
    }
}
