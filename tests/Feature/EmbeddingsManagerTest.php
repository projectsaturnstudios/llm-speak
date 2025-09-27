<?php

use LLMSpeak\Core\Managers\EmbeddingsManager;
use Tests\Support\TestEmbeddingsDriver;
use LLMSpeak\Core\Support\Facades\AIEmbeddings;

beforeEach(function () {
    // Minimal embeddings config
    config()->set('vector-embeddings', [
        'default' => 'oaic',
        'connections' => [
            'oaic' => [
                'driver' => 'open-ai-compatible',
                'url' => 'https://api.openai.com',
                'model' => 'text-embedding-ada-002',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer test',
                ],
            ],
            'test' => [
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

    // Register a test driver
    AIEmbeddings::extend('test-embeddings-driver', fn() => new TestEmbeddingsDriver());
});

it('resolves connection details from embeddings config', function () {
    $manager = app(EmbeddingsManager::class);

    expect($manager->defaultConnection())->toBe('oaic')
        ->and($manager->connectionDriverName('oaic'))->toBe('open-ai-compatible')
        ->and($manager->getDefaultDriver())->toBe('open-ai-compatible')
        ->and($manager->connectionUrl('oaic'))->toBe('https://api.openai.com')
        ->and($manager->connectionDefaultModelID('oaic'))->toBe('text-embedding-ada-002');
});

it('returns the extended test embeddings driver instance', function () {
    $manager = app(EmbeddingsManager::class);

    $driver = $manager->connection('test');
    expect($driver)->toBeInstanceOf(TestEmbeddingsDriver::class);
});


