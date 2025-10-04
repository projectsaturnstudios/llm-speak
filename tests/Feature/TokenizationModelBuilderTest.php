<?php

use Illuminate\Support\Facades\Http;
use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\NeuralModels\TokenizationModel;

it('supports where, whereIn, and whereText via magic', function () {
    $model = new TokenizationModel();

    $query = $model->where('text', 'a')->whereIn('text', ['b'])->whereText('c');

    // No direct getter for prompts except via builder pipeline; ensure no exceptions
    expect($query)->not->toBeNull();
});

it('performs get/first calling driver and endpoint and maps response to models', function () {
    Http::fake([
        'https://example.test/*' => Http::response(
            body: [
                'tokens' => [
                    ['token_id' => '1', 'string_token' => 'a', 'token_bytes' => [97]],
                    ['token_id' => '2', 'string_token' => 'b', 'token_bytes' => [98]],
                ],
            ],
            status: 200,
            headers: []
        ),
    ]);

    $model = new TokenizationModel();
    $collection = $model->whereText('hello')->get();

    expect($collection->count())->toBe(1);
    $first = $collection->first();

    expect($first)->toBeInstanceOf(TokenizationModel::class)
        ->and($first->getRawTokens())->toBe([
            ['id' => '1', 'string_represented' => 'a', 'token' => [97]],
            ['id' => '2', 'string_represented' => 'b', 'token' => [98]],
        ]);

    $single = (new TokenizationModel())->whereText('hello')->first();
    expect($single)->toBeInstanceOf(TokenizationModel::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/v1/tokenize')
            && $request->method() === 'POST'
            && is_array($request->data())
            && ($request->data()['text'] ?? null) === 'hello';
    });
});




