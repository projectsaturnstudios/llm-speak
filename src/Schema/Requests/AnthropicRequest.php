<?php

namespace LLMSpeak\Schema\Requests;

use LLMSpeak\Exceptions\AnthropicRequestException;

class AnthropicRequest extends BaseRequestObject
{
    protected string $provider = 'anthropic';

    public function prepareInstructions(array $instructions): static
    {
        $this->system_prompt = $instructions;

        return $this;
    }
    /**
     * @return array
     * @throws AnthropicRequestException
     */
    public function toParams(): array
    {
        $payload = [
            'model' => $this->model,
            'params' => []
        ];

        if(empty($this->max_tokens)) $this->max_tokens = config('llms.services.anthropic.max_tokens_default', null);
        if(!empty($this->max_tokens))
        {
            $payload['params']['max_tokens'] = $this->max_tokens;
        }
        else throw AnthropicRequestException::maxTokensRequired();

        if(!empty($this->conversation))
        {
            $payload['params']['contents'] = $this->transformConvo();
        }
        else throw AnthropicRequestException::emptyConversation();

        if(!empty($this->system_prompt))
        {
            $payload['params']['system_instruction'] = array_map(function($item) {
                return [
                    'type' => 'text',
                    'text' => $item
                ];
            }, $this->system_prompt);
        }
        if(!empty($this->tools)) $payload['params']['tools'] = $this->tools;

        return $payload;
    }


}
