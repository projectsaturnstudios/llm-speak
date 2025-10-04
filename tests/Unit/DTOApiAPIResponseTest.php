<?php

use LLMSpeak\Core\DTO\Api\APIResponse;

it('creates APIResponse with provided data', function () {
    $resp = new APIResponse(
        status: 200,
        headers: ['h' => ['v']],
        body: ['ok' => true],
    );

    expect($resp->status)->toBe(200)
        ->and($resp->headers)->toBe(['h' => ['v']])
        ->and($resp->body)->toBe(['ok' => true]);
});






