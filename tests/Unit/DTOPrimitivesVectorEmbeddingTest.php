<?php

use LLMSpeak\Core\DTO\Primitives\VectorEmbedding;
use LLMSpeak\Core\DTO\Primitives\ModelPrimitive;
use LLMSpeak\Core\Enums\PrimitiveType;

it('constructs with numbers, exposes value and type', function () {
    $numbers = [0.1, 0.2, 0.3];
    $embedding = new VectorEmbedding($numbers);

    expect($embedding)->toBeInstanceOf(ModelPrimitive::class)
        ->and($embedding->type)->toBe(PrimitiveType::VECTOR)
        ->and($embedding->toValue())->toBe($numbers);
});

it('stores optional metadata without affecting value', function () {
    $numbers = [0.9, 0.8];
    $meta = ['source' => 'test'];
    $embedding = new VectorEmbedding($numbers, $meta);

    expect($embedding->toValue())->toBe($numbers)
        ->and($embedding->metadata)->toBe($meta);
});






