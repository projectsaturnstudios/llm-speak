<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait MaxTokens
{
    protected ?int $max_tokens = null;

    public function setMaxTokens(?int $max_tokens): static
    {
        $this->max_tokens = $max_tokens;
        return $this->addToOriginal("max_tokens", $max_tokens);
    }

    public function maxTokens(): ?int
    {
        return $this->max_tokens;
    }


}
