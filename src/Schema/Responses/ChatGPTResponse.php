<?php

namespace LLMSpeak\Schema\Responses;

use LLMSpeak\Schema\LLMResponse;

class ChatGPTResponse extends BaseResponseObject
{
    public function toLLMResponse(): LLMResponse
    {
        $chat = (new LLMResponse(
            $this->getMessage(),
            'open-ai',
            $this->raw_llm_response['model']
        ));

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

        if(array_key_exists('choices', $this->raw_llm_response))
        {
            foreach($this->raw_llm_response['choices'] as $choice)
            {
                if(array_key_exists('message', $choice))
                {
                    if(array_key_exists('tool_calls', $choice['message']))
                    {
                        $results = $choice['message']['tool_calls'][0];
                        break;
                    }
                }
                elseif(array_key_exists('tool_calls', $choice))
                {
                    $results = $choice['tool_calls'][0];
                    break;
                }

            }

            return  $results;
        }

        if(array_key_exists('message', $this->raw_llm_response))
        {
            if(array_key_exists('tool_calls', $this->raw_llm_response['message']))
            {
                $results = $this->raw_llm_response['message']['tool_calls'][0];
            }
        }

        return $results;
    }

    protected function getMessage(): string
    {
        $text = "";
        foreach($this->raw_llm_response['choices'] as $choice)
        {
            if(array_key_exists('message', $choice))
            {
                $text .= "{$choice['message']['content']} ";
            }

        }

        return $text;
    }
}
