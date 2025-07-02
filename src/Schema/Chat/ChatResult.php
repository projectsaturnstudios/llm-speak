<?php

namespace LLMSpeak\Schema\Chat;

use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use Spatie\LaravelData\Data;

class ChatResult extends Data
{
    public array $messages = [];
    public ?string $model = null;
    public ?string $id = null;

    public function __construct() {}

    public function hasAToolCall(): bool
    {
        foreach ($this->messages as $message) {
            if ($message instanceof ToolCall) {
                return true;
            }
        }
        return false;
    }

    public function  hasATextMessage(): bool
    {
        foreach ($this->messages as $message) {
            if ($message instanceof TextMessage) {
                return true;
            }
        }
        return false;
    }

    public function addMessage(TextMessage $message): static
    {
        $this->messages[] = $message;
        return $this;
    }

    public function addToolRequest(ToolCall $tool): static
    {
        $this->messages[] = $tool;
        return $this;
    }

    public function addToolResult(ToolResult $result): static
    {
        $this->messages[] = $result;
        return $this;
    }

    public function addModel(string $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function addId(string $id): static
    {
        $this->id = $id;
        return $this;
    }
}
