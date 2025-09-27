<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait MultipleGoesAtIt
{
    protected ?int $n = null;

    public function setN(?int $n): static
    {
        $this->n = $n;

        return $this->addToOriginal("n", $n);
    }

    public function n(): ?float
    {
        return $this->n;
    }
}
