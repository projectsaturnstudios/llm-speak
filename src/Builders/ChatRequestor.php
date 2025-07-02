<?php

namespace LLMSpeak\Builders;

use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;

class ChatRequestor
{
    protected $payload = [
        'model' => null,
        'messages' => null,
        'credentials' => null,
        'system' => null,
        'max_tokens' => null,
        'tools' => null,
        'temperature' => null,
    ];
    public static function boot(): void
    {
        app()->instance('llm-request-builder', new static());
    }

    public function usingModel(string $model): self
    {
        $this->payload['model'] = $model;
        return $this;
    }

    public function includeMessages(array $messages): self
    {
        $tool_name = null;
        $this->payload['messages'] = array_map(function($entry) use(&$tool_name) {
            if(is_string($entry['content'])) return new TextMessage($entry['role'], $entry['content']);
            else{
                $role = $entry['role'];
                if(array_key_exists(0, $entry['content']))
                {
                    if($entry['content'][0]['type'] == 'tool_use')
                    {
                        $tool = $entry['content'][0];
                        $tool_name = $tool['name'];
                        return new ToolCall($tool['name'], $tool['input'], $tool['id']);
                    }
                    elseif($entry['content'][0]['type'] == 'tool_result')
                    {
                        $tool = $entry['content'][0];
                        $id = 0;
                        if(array_key_exists('tool_use_id', $tool)) $id = $tool['tool_use_id'];
                        else dd('suppies', $entry);

                        return new ToolResult($role, $tool_name, $tool['content'], $id);
                    }
                    dd($entry, 'dumbass');
                }
                dd($entry, 'shit head');
            }
            dd($entry, 'fuck face');
        }, $messages);
        return $this;
    }

    public function supplyCredentials(array $deets): self
    {
        $this->payload['credentials'] = $deets;
        return $this;
    }

    public function instillASystemPrompt(array $system): self
    {
        $this->payload['system'] = $system;
        return $this;
    }

    public function limitTokens(int $max_tokens): self
    {
        $this->payload['max_tokens'] = $max_tokens;
        return $this;
    }

    public function allowAccessToTools(array $tools): self
    {
        $this->payload['tools'] = $tools;
        return $this;
    }

    public function setTemperature(float $temp): self
    {
        $this->payload['temperature'] = $temp;
        return $this;
    }

    public function create(): ChatRequest
    {
        return new ChatRequest(
            model: $this->payload['model'],
            messages: $this->payload['messages'],
            credentials: $this->payload['credentials'] ?? [],
            system_prompts: $this->payload['system'] ?? null,
            max_tokens: $this->payload['max_tokens'] ?? null,
            tools: $this->payload['tools'] ?? null,
            temperature: $this->payload['temperature'] ?? null
        );
    }
}
