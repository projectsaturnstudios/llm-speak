<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait RandomSeed
{
    protected ?int $seed = null;

    public function setSeed(?int $seed): static
    {
        $this->seed = $seed;

        return $this->addToOriginal("seed", $seed);
    }

    public function seed(): ?int
    {
        return $this->seed;
    }
}
