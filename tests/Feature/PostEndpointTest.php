<?php

use Illuminate\Support\Facades\Http;
use LLMSpeak\Core\Actions\Endpoints\PostEndpoint;

it('posts request and returns APIResponse with merged headers', function () {
    Http::fake([
        'https://example.test/*' => Http::response(
            body: ['tokens' => [['token_id' => 1, 'string_token' => 'a', 'token_bytes' => [97]]]],
            status: 200,
            headers: ['x-test' => ['ok']]
        ),
    ]);

    $endpoint = new PostEndpoint(
        headers: ['Authorization' => 'Bearer test'],
        url: 'https://example.test/v1/tokenize',
        model: 'model-1'
    );

    $resp = $endpoint->handle(['text' => 'hi', 'model' => 'model-1']);

    expect($resp->status)->toBe(200)
        ->and($resp->headers['model'])->toBe(['model-1'])
        ->and($resp->headers['x-test'])->toBe(['ok'])
        ->and($resp->body)->toBe(['tokens' => [['token_id' => 1, 'string_token' => 'a', 'token_bytes' => [97]]]]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://example.test/v1/tokenize'
            && $request['text'] === 'hi'
            && $request['model'] === 'model-1'
            && $request->hasHeader('Authorization', 'Bearer test');
    });
});


