<?php

namespace LLMSpeak\Core\Contracts\NeuralModels;

use LLMSpeak\Core\Contracts\Eloquent\NeuralModelBuilder;

interface NeuralModel
{
    public function connection(): string;
    public function modelId(): string;
    public function setModelId(string $model_id): static;
    public function setConnection(string $connection): static;
    public function newQuery(): NeuralModelBuilder;
    public function newModelQuery(): NeuralModelBuilder;
    public function newNeuralModelBuilder() : NeuralModelBuilder;
}
