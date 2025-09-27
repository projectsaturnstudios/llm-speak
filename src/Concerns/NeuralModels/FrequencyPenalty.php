<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait FrequencyPenalty
{
    protected ?float $frequency_penalty = null;

    public function setFrequencyPenalty(?float $frequency_penalty): static
    {
        $this->frequency_penalty = $frequency_penalty;
        return $this->addToOriginal("frequency_penalty", $frequency_penalty);
    }

    public function frequencyPenalty(): ?float
    {
        return $this->frequency_penalty;
    }
}
