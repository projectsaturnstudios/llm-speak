<?php

use LLMSpeak\Core\DTO\Primitives\VectorEmbedding;
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;

it('initializes with defaults from embeddings config', function () {
    // Configure embeddings defaults
    config()->set('vector-embeddings.default', 'oaic');
    config()->set('vector-embeddings.connections.oaic', [
        'driver' => 'open-ai-compatible',
        'url' => 'https://api.openai.com',
        'model' => 'text-embedding-ada-002',
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer test',
        ],
    ]);

    $model = new EmbeddingsModel();

    expect($model->connection())->toBe('oaic')
        ->and($model->modelId())->toBe('text-embedding-ada-002');
});

it('sets input, usage and latest response, exposes embedding and raw array', function () {
    $model = new EmbeddingsModel('oaic', 'text-embedding-3-small');

    $embedding = new VectorEmbedding([0.1, 0.2, 0.3]);
    $usage = ['prompt_tokens' => 5];

    $model->setInput(['hello'])->setUsage($usage)->setLatestResponse($embedding);

    expect($model->getEmbeddings())->toBe($embedding)
        ->and($model->toArray())->toBe(['embeddings' => $embedding]);
});


