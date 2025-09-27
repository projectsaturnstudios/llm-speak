<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait PresencePenalty
{
    protected ?float $presence_penalty = null;

    public function setPresencePenalty(?float $presence_penalty): static
    {
        $this->presence_penalty = $presence_penalty;
        return $this->addToOriginal("presence_penalty", $presence_penalty);
    }

    public function presencePenalty(): ?float
    {
        return $this->presence_penalty;
    }
}
