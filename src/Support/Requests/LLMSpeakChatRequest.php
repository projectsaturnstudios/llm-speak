<?php

namespace LLMSpeak\Core\Support\Requests;

use LLMSpeak\Core\Support\Schema\Conversation\Conversation;
use LLMSpeak\Core\Support\Schema\Tools\ToolKit;
use LLMSpeak\Core\Support\Schema\SystemInstructions\SystemInstructions;

class LLMSpeakChatRequest extends LLMSpeakRequest
{
    public function __construct(
        string $model,
        public readonly ?Conversation $messages = null,
        public readonly ?ToolKit $tools = null,

        public readonly ?SystemInstructions $system_instructions = null,
        public readonly ?int $max_tokens = null,
        public readonly ?float $temperature = null,
        public readonly ?string $tool_choice = null,
        public readonly ?object $response_format = null,
        public readonly ?bool $stream = null,
        public readonly ?bool $parallel_function_calling = null,
        
        // Advanced sampling parameters
        public readonly ?float $top_p = null,
        public readonly ?int $top_k = null,
        public readonly ?float $frequency_penalty = null,
        public readonly ?float $presence_penalty = null,
        
        // Stop sequences
        public readonly ?array $stop = null,
        
        // Thinking/reasoning parameters
        public readonly ?array $reasoning = null,
    )
    {
        parent::__construct($model);
    }

    /**
     * Generic method to update any property and return a new instance
     */
    private function set(string $property, mixed $value): static
    {
        $data = [
            'model' => $this->model,
            'messages' => $this->messages,
            'tools' => $this->tools,
            'system_instructions' => $this->system_instructions,
            'max_tokens' => $this->max_tokens,
            'temperature' => $this->temperature,
            'tool_choice' => $this->tool_choice,
            'response_format' => $this->response_format,
            'stream' => $this->stream,
            'parallel_function_calling' => $this->parallel_function_calling,
            'top_p' => $this->top_p,
            'top_k' => $this->top_k,
            'frequency_penalty' => $this->frequency_penalty,
            'presence_penalty' => $this->presence_penalty,
            'stop' => $this->stop,
            'reasoning' => $this->reasoning,
        ];

        $data[$property] = $value;

        return new self(...$data);
    }

    public function updateConversation(Conversation $messages): static
    {
        return $this->set('messages', $messages);
    }

    public function updateTools(ToolKit $tools): static
    {
        return $this->set('tools', $tools);
    }

    public function updateSystemInstructions(SystemInstructions $system_instructions): static
    {
        return $this->set('system_instructions', $system_instructions);
    }

    public function updateMaxTokens(int $max_tokens): static
    {
        return $this->set('max_tokens', $max_tokens);
    }

    public function updateTemperature(float $temperature): static
    {
        return $this->set('temperature', $temperature);
    }

    public function updateToolChoice(string $tool_choice): static
    {
        return $this->set('tool_choice', $tool_choice);
    }

    public function updateResponseFormat(object $response_format): static
    {
        return $this->set('response_format', $response_format);
    }

    public function updateStream(bool $stream): static
    {
        return $this->set('stream', $stream);
    }

    public function updateParallelFunctionCalling(bool $parallel_function_calling): static
    {
        return $this->set('parallel_function_calling', $parallel_function_calling);
    }

    public function updateTopP(float $top_p): static
    {
        return $this->set('top_p', $top_p);
    }

    public function updateTopK(int $top_k): static
    {
        return $this->set('top_k', $top_k);
    }

    public function updateFrequencyPenalty(float $frequency_penalty): static
    {
        return $this->set('frequency_penalty', $frequency_penalty);
    }

    public function updatePresencePenalty(float $presence_penalty): static
    {
        return $this->set('presence_penalty', $presence_penalty);
    }

    public function updateStop(array $stop): static
    {
        return $this->set('stop', $stop);
    }

    public function updateReasoning(array $reasoning): static
    {
        return $this->set('reasoning', $reasoning);
    }

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'messages' => $this->messages?->toArray(),
            'tools' => $this->tools?->toArray(),
            'system_instructions' => $this->system_instructions?->toArray(),
            'max_tokens' => $this->max_tokens,
            'temperature' => $this->temperature,
            'tool_choice' => $this->tool_choice,
            'response_format' => $this->response_format,
            'stream' => $this->stream,
            'parallel_function_calling' => $this->parallel_function_calling,
            'top_p' => $this->top_p,
            'top_k' => $this->top_k,
            'frequency_penalty' => $this->frequency_penalty,
            'presence_penalty' => $this->presence_penalty,
            'stop' => $this->stop,
            'reasoning' => $this->reasoning,
        ];
    }

    public function type(): string
    {
        return 'chat';
    }
}
