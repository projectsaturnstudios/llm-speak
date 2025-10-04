<?php

use LLMSpeak\Core\DTO\Primitives\Token;
use LLMSpeak\Core\DTO\Schema\Tokenization\TokenizedText;

it('serializes to array with token primitive value', function () {
    $dto = new TokenizedText(
        id: 'abc',
        string_represented: 'hello',
        token: new Token([10, 20])
    );

    expect($dto->toArray())->toBe([
        'id' => 'abc',
        'string_represented' => 'hello',
        'token' => [10, 20],
    ]);
});






