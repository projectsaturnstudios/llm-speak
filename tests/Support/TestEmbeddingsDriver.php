<?php

namespace Tests\Support;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\DTO\Primitives\VectorEmbedding;
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Drivers\Interaction\ModelEmbeddingsDriver;

class TestEmbeddingsDriver extends ModelEmbeddingsDriver
{
    protected string $driver_name = 'test-embeddings-driver';

    protected function generateRequestBody(array $input, EmbeddingsModel $neural_model)
    {
        return array_map(fn(string $text) => [
            'input' => $text,
            'model' => $neural_model->modelId(),
        ], $input);
    }

    protected function generateEmbeddings(array $output): array
    {
        $usage = $output['usage'] ?? [];
        $vectors = array_map(fn(array $arr) => new VectorEmbedding($arr), $output['vectorized'] ?? []);

        return [$usage, $vectors];
    }
}






