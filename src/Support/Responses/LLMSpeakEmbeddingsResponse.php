<?php

namespace LLMSpeak\Core\Support\Responses;

use Spatie\LaravelData\Data;

/**
 * Universal Embeddings Response Structure
 * 
 * Standardized response format that all LLM embedding providers translate to.
 * Based on OpenAI's Embeddings API structure with enhancements for universal compatibility.
 */
class LLMSpeakEmbeddingsResponse extends LLMSpeakResponse
{
    public function __construct(
        public readonly string $model,
        public readonly array $data,
        public readonly array $usage,
        
        // Optional provider-specific fields
        public readonly ?string $object = null,
        public readonly ?array $metadata = null,
    )
    {
        
    }

    /**
     * Get the first embedding vector
     */
    public function getFirstEmbedding(): ?array
    {
        if (empty($this->data)) {
            return null;
        }
        
        $firstItem = $this->data[0];
        return $firstItem['embedding'] ?? $firstItem ?? null;
    }

    /**
     * Get all embedding vectors as a flat array
     */
    public function getAllEmbeddings(): array
    {
        return array_map(function($item) {
            return $item['embedding'] ?? $item;
        }, $this->data);
    }

    /**
     * Get the number of embeddings in the response
     */
    public function getEmbeddingCount(): int
    {
        return count($this->data);
    }

    /**
     * Check if there are multiple embeddings
     */
    public function hasMultipleEmbeddings(): bool
    {
        return $this->getEmbeddingCount() > 1;
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
     * Get the dimensions of the first embedding vector
     */
    public function getDimensions(): ?int
    {
        $firstEmbedding = $this->getFirstEmbedding();
        return $firstEmbedding ? count($firstEmbedding) : null;
    }

    public function type(): string
    {
        return 'embeddings';
    }
}
