<?php

use LLMSpeak\Core\DTO\Primitives\Token;
use LLMSpeak\Core\Enums\PrimitiveType;

it('converts token to value array', function () {
    $token = new Token([1, 2, 3]);
    expect($token->toValue())->toBe([1, 2, 3]);
});

it('has TOKEN primitive type', function () {
    $token = new Token([7]);
    expect($token->type)->toBe(PrimitiveType::TOKEN);
});




