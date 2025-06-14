<?php

namespace LLMSpeak\Communication;

use LLMSpeak\Contracts\LLMChatRequestContract;

abstract class LLMChatRequest extends LLMCommunicationObject implements LLMChatRequestContract
{
    public function __construct(
        public readonly string $model,
        public readonly array  $messages,
        public readonly ?array $tools = null,
        public readonly ?int   $max_tokens = null,
        public readonly ?float $top_p = null,
    )
    {
    }

    abstract public function toPayload(): array;

    abstract protected function unwrapMessages(array $messages): array;

    abstract protected function applyTools(array $tools): array;

    /** API **/
    public function update(string $col, mixed $val): static
    {
        $raw = $this->toRaw();
        $raw[$col] = $val;
        try {

            return $this->render($raw);
        }
        catch(\Error $error)
        {
            dd($error);
            return $this;
        }
    }

    public function updateModel(string $model): static
    {
        return $this->update('model', $model);
    }

    public function updateMaxTokens(string $max_tokens): static
    {
        return $this->update('max_tokens', $max_tokens);
    }

    public function updateTopP(string $top_p): static
    {
        return $this->update('top_p', $top_p);
    }

    public function addMessage(mixed $message, string $col = 'messages'): static
    {
        return $this->addMessages([$message], $col);
    }

    public function addMessages(array $messages, string $col = 'messages'): static
    {
        $msgs = $this->getMessages();
        foreach ($messages as $message) {
            $msgs[] = $message;
        }
        return $this->update($col, $msgs);
    }

    public function clearMessages( string $col = 'messages'): static
    {
        return $this->update($col, []);
    }

    public function getMessages(string $col = 'messages'): array
    {
        return $this->$col;
    }

    public function addTool(mixed $tool): static
    {
        return $this->addTools([$tool]);
    }
    public function addTools(array $tools): static
    {
        $kit = $this->getTools();
        foreach ($tools as $tool) {
            $kit[] = $tool;
        }
        return $this->update("tools", $kit);
    }
    public function clearTools(): static
    {
        return $this->update("tools", []);
    }
    public function getTools(): array
    {
        return $this->tools;
    }

    public function toRaw(): array
    {
        return [
            'model' => $this->model,
            'messages' => $this->messages,
            'tools' => $this->tools,
            'max_tokens' => $this->max_tokens,
            'top_p' => $this->top_p,
        ];
    }

    public function render(array $payload): static
    {
        return new static(...$payload);
    }


}
