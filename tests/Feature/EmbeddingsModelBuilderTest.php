<?php

use Illuminate\Support\Facades\Http;
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;
use Tests\Support\TestEmbeddingsDriver;
use LLMSpeak\Core\Support\Facades\AIEmbeddings;

beforeEach(function () {
    config()->set('vector-embeddings', [
        'default' => 'oaic',
        'connections' => [
            'oaic' => [
                'driver' => 'test-embeddings-driver',
                'url' => 'https://example.test',
                'model' => 'text-embedding-3-small',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ],
        ],
        'drivers' => [
            'test-embeddings-driver' => [
                'driver_class' => \Tests\Support\TestEmbeddingsDriver::class,
                'config' => [
                    'endpoint_uri' => '/v1/embeddings',
                ],
            ],
        ],
    ]);

    AIEmbeddings::extend('test-embeddings-driver', fn() => new TestEmbeddingsDriver());
});

it('supports where, whereIn, and whereInput via magic for embeddings', function () {
    $model = new EmbeddingsModel();

    $query = $model->where('input', 'a')->whereIn('input', ['b'])->whereInput('c')->whereDimensions(256);

    expect($query)->not->toBeNull();
});

it('performs get/first calling embeddings driver and endpoint and maps response to models', function () {
    Http::fake([
        'https://example.test/*' => Http::response(
            body: [
                'usage' => ['prompt_tokens' => 10],
                'vectorized' => [
                    [0.1, 0.2, 0.3],
                ],
            ],
            status: 200,
            headers: []
        ),
    ]);

    $model = new EmbeddingsModel();
    $collection = $model->whereInput('hello')->get();

    expect($collection->count())->toBe(1);
    $first = $collection->first();

    expect($first)->toBeInstanceOf(EmbeddingsModel::class)
        ->and($first->getEmbeddings())->not->toBeNull();

    $single = (new EmbeddingsModel())->whereInput('hello')->first();
    expect($single)->toBeInstanceOf(EmbeddingsModel::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/v1/embeddings')
            && $request->method() === 'POST'
            && is_array($request->data())
            && ($request->data()['input'] ?? null) === 'hello'
            && ($request->data()['model'] ?? null) === 'text-embedding-3-small';
    });
});


