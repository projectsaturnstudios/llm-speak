<?php

use LLMSpeak\Core\Managers\TokenizationManager;
use Tests\Support\TestTokenizationDriver;

it('resolves connection details from config', function () {
    $manager = app(TokenizationManager::class);

    expect($manager->defaultConnection())->toBe('test')
        ->and($manager->connectionDriverName('test'))->toBe('test-driver')
        ->and($manager->getDefaultDriver())->toBe('test-driver')
        ->and($manager->connectionUrl('test'))->toBe('https://example.test')
        ->and($manager->connectionDefaultModelID('test'))->toBe('model-1');
});

it('returns the extended test driver instance', function () {
    $manager = app(TokenizationManager::class);

    $driver = $manager->connection('test');
    expect($driver)->toBeInstanceOf(TestTokenizationDriver::class);
});




