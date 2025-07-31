<?php

namespace LLMSpeak\Core\Support\Responses;

use Spatie\LaravelData\Data;

/**
 * Universal Chat Response Structure
 * 
 * Standardized response format that all LLM providers translate to.
 * Based on OpenAI's Chat Completions API structure for maximum compatibility.
 */
class LLMSpeakChatResponse extends LLMSpeakResponse
{
    public function __construct(
        public readonly string $id,
        public readonly string $model,
        public readonly int $created,
        public readonly array $choices,
        public readonly array $usage,
        
        // Optional provider-specific fields
        public readonly ?string $finish_reason = null,
        public readonly ?string $object = null,
        public readonly ?array $system_fingerprint = null,
        public readonly ?array $metadata = null,
    )
    {
        
    }

    /**
     * Get the main text content from the first choice
     */
    public function getContent(): ?string
    {
        if (empty($this->choices)) {
            return null;
        }
        
        $firstChoice = $this->choices[0];
        return $firstChoice['message']['content'] ?? $firstChoice['content'] ?? null;
    }

    /**
     * Get the finish reason from the first choice or fallback to main property
     */
    public function getFinishReason(): ?string
    {
        if (!empty($this->choices) && isset($this->choices[0]['finish_reason'])) {
            return $this->choices[0]['finish_reason'];
        }
        
        return $this->finish_reason;
    }

    /**
     * Check if the response used tools
     */
    public function usedTools(): bool
    {
        return in_array($this->getFinishReason(), ['tool_calls', 'function_call']);
    }

    /**
     * Get total tokens used
     */
    public function getTotalTokens(): ?int
    {
        return $this->usage['total_tokens'] ?? null;
    }

    /**
     * Get prompt tokens used
     */
    public function getPromptTokens(): ?int
    {
        return $this->usage['prompt_tokens'] ?? null;
    }

    /**
     * Get completion tokens used
     */
    public function getCompletionTokens(): ?int
    {
        return $this->usage['completion_tokens'] ?? null;
    }

    public function type(): string
    {
        return 'chat';
    }
}
