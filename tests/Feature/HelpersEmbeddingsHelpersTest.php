<?php

it('reads default connection and driver name from config for embeddings', function () {
    config()->set('vector-embeddings.default', 'oaic');
    config()->set('vector-embeddings.connections.oaic.driver', 'open-ai-compatible');

    expect(default_embeddings_connection_name())->toBe('oaic')
        ->and(embeddings_connection_driver_name('oaic'))->toBe('open-ai-compatible');
});

it('reads headers, url, and default model from embeddings config', function () {
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

    expect(embeddings_connection_headers('oaic'))
        ->toMatchArray([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer test',
        ]);

    expect(embeddings_connection_url('oaic'))->toBe('https://api.openai.com')
        ->and(embeddings_connection_default_model('oaic'))->toBe('text-embedding-ada-002');
});






