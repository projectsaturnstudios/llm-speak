<?php

namespace LLMSpeak\Schema\Responses;

use LLMSpeak\Schema\LLMResponse;

class GeminiResponse extends BaseResponseObject
{
    public function toLLMResponse(): LLMResponse
    {
        $chat = (new LLMResponse(
            $this->getMessage(),
            'gemini',
            $this->raw_llm_response['modelVersion']
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

        foreach($this->raw_llm_response['candidates'] as $candidate)
        {
            foreach($candidate['content']['parts'] as $part)
            {
                if(array_key_exists('functionCall', $part))
                {
                    $results = $part['functionCall'];
                    break;
                }
            }
        }

        return $results;
    }

    protected function getMessage(): string
    {
        $text = "";
        foreach($this->raw_llm_response['candidates'] as $candidate)
        {
            foreach($candidate['content']['parts'] as $part)
            {
                if(array_key_exists('text', $part)) $text .= "{$part['text']} ";
            }
        }

        return $text;
    }
}
