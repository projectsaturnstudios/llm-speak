<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait Temperature
{
    protected ?float $temperature = null;
    protected ?float $top_p = null;

    public function setTemperature(?float $temperature): static
    {
        $this->temperature = $temperature;
        return $this->addToOriginal("temperature", $temperature);
    }

    public function setTopP(?float $top_p): static
    {
        $this->top_p = $top_p;
        return $this->addToOriginal("top_p", $top_p);
    }

    public function temperature(): ?float
    {
        return $this->temperature;
    }

    public function topP(): ?float
    {
        return $this->top_p;
    }
}
