<?php

namespace LLMSpeak\Schema\Responses;

use LLMSpeak\Schema\LLMResponse;

class AnthropicResponse extends BaseResponseObject
{
    public function toLLMResponse(): LLMResponse
    {
        $chat = (new LLMResponse(
            $this->getMessage(),
            'anthropic',
            $this->raw_llm_response['model'])
        );

        $tool_call = $this->getToolRequest() ?? false;
        if($tool_call)
        {
            $chat = $chat->addToolRequest($tool_call);
        }

        return $chat;
    }

    protected function getToolRequest(): ?array
    {
        $results = null;

        if(is_array($this->raw_llm_response["content"]))
        {
            foreach($this->raw_llm_response["content"] as $item)
            {
                if(array_key_exists ('type', $item))
                {
                    if($item['type'] == 'tool_use')
                    {
                        $results = $item;
                        break;
                    }
                }
            }
        }

        return $results;
    }

    protected function getMessage(): string
    {
        $text = "";
        if(array_key_exists('text', $this->raw_llm_response['content'])) $text = $this->raw_llm_response['content']['text'];
        else
        {
            foreach($this->raw_llm_response['content'] as $content)
            {
                if(array_key_exists('text', $content))
                {
                    $text .= $content['text'];
                }
            }
        }
        return $text;
    }

}
