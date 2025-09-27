<?php

use Illuminate\Support\Facades\Http;
use LLMSpeak\Core\Eloquent\TokenizationModelBuilder;
use LLMSpeak\Core\NeuralModels\TokenizationModel;

it('returns a TokenizationModelBuilder from newQuery()', function () {
    $builder = (new TokenizationModel())->newQuery();
    expect($builder)->toBeInstanceOf(TokenizationModelBuilder::class);
});

it('supports where/whereIn with get/first when using manual builder', function () {
    Http::fake([
        'https://example.test/*' => Http::response(
            body: [
                'tokens' => [
                    ['token_id' => '1', 'string_token' => 'a', 'token_bytes' => [97]],
                ],
            ],
            status: 200,
            headers: []
        ),
    ]);

    $model = new TokenizationModel();
    $query = $model->newQuery();

    $query->where('text', 'hello')->whereIn('text', ['world']);

    $first = $query->first();
    expect($first)->toBeInstanceOf(TokenizationModel::class)
        ->and($first->getRawTokens())->toBe([
            ['id' => '1', 'string_represented' => 'a', 'token' => [97]],
        ]);

    $collection = $query->get();
    expect($collection->count())->toBe(2); // two prompts: 'hello' and 'world'
});
