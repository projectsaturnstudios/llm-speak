<?php

use LLMSpeak\Core\DTO\Primitives\Token;
use LLMSpeak\Core\DTO\Schema\Tokenization\TokenizedText;
use LLMSpeak\Core\NeuralModels\TokenizationModel;

it('initializes with defaults from config', function () {
    $model = new TokenizationModel();

    expect($model->connection())->toBe('test')
        ->and($model->modelId())->toBe('model-1');
});

it('sets prompt and latest response, exposes tokens and raw tokens', function () {
    $model = new TokenizationModel();

    $tokens = [
        new TokenizedText('1', 'a', new Token([97])),
        new TokenizedText('2', 'b', new Token([98])),
    ];

    $model->setPrompt('hi')->setLatestResponse($tokens);

    expect($model->getTokens())->toBe($tokens)
        ->and($model->toArray())->toBe(['tokens' => $tokens])
        ->and($model->getRawTokens())->toBe([
            ['id' => '1', 'string_represented' => 'a', 'token' => [97]],
            ['id' => '2', 'string_represented' => 'b', 'token' => [98]],
        ]);
});




