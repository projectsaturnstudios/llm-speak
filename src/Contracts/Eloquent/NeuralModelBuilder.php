<?php

namespace LLMSpeak\Core\Contracts\Eloquent;

use LLMSpeak\Core\Collections\NeuralModelCollection;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;

interface NeuralModelBuilder
{
    public function where(string $column, mixed $value = null): static;
    public function whereIn(string $column, array $values): static;
    public function get(): NeuralModelCollection;
    public function setNeuralModel(NeuralModel $model): static;
    public function getNeuralModel(): ?NeuralModel;
    public function __call($method, $parameters): static;
}
