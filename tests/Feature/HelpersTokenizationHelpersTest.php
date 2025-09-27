<?php

it('reads default connection and driver name from config', function () {
    expect(default_tokenization_connection_name())->toBe('test')
        ->and(tokenization_connection_driver_name('test'))->toBe('test-driver');
});

it('reads headers, url, and default model from config', function () {
    expect(tokenization_connection_headers('test'))
        ->toMatchArray([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer test',
        ]);

    expect(tokenization_connection_url('test'))->toBe('https://example.test')
        ->and(tokenization_connection_default_model('test'))->toBe('model-1');
});


