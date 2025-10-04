<?php

use LLMSpeak\Core\Collections\NeuralModelCollection;

it('holds items and supports first/push', function () {
    $collection = new NeuralModelCollection();
    expect($collection->count())->toBe(0);

    $collection = $collection->push('a');
    expect($collection->count())->toBe(1)
        ->and($collection->first())->toBe('a');
});






