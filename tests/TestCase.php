<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use LLMSpeak\Core\Providers\LLMSpeakCoreServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\LaravelData\Support\Creation\ValidationStrategy;
use LLMSpeak\Core\Support\Facades\AITokenizer;
use Tests\Support\TestTokenizationDriver;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register test driver with the Tokenization manager
        AITokenizer::extend('test-driver', fn() => new TestTokenizationDriver());
    }

    protected function getPackageProviders($app)
    {
        return [
            LLMSpeakCoreServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Minimal yet complete config for spatie/laravel-data
        $app['config']->set('data', [
            'date_format' => DATE_ATOM,
            'date_timezone' => null,
            'features' => [
                'cast_and_transform_iterables' => false,
                'ignore_exception_when_trying_to_set_computed_property_value' => false,
            ],
            'transformers' => [
                DateTimeInterface::class => \Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer::class,
                \Illuminate\Contracts\Support\Arrayable::class => \Spatie\LaravelData\Transformers\ArrayableTransformer::class,
                \BackedEnum::class => \Spatie\LaravelData\Transformers\EnumTransformer::class,
            ],
            'casts' => [
                DateTimeInterface::class => \Spatie\LaravelData\Casts\DateTimeInterfaceCast::class,
                \BackedEnum::class => \Spatie\LaravelData\Casts\EnumCast::class,
            ],
            'rule_inferrers' => [
                \Spatie\LaravelData\RuleInferrers\SometimesRuleInferrer::class,
                \Spatie\LaravelData\RuleInferrers\NullableRuleInferrer::class,
                \Spatie\LaravelData\RuleInferrers\RequiredRuleInferrer::class,
                \Spatie\LaravelData\RuleInferrers\BuiltInTypesRuleInferrer::class,
                \Spatie\LaravelData\RuleInferrers\AttributesRuleInferrer::class,
            ],
            'normalizers' => [
                \Spatie\LaravelData\Normalizers\ModelNormalizer::class,
                // \Spatie\LaravelData\Normalizers\FormRequestNormalizer::class,
                \Spatie\LaravelData\Normalizers\ArrayableNormalizer::class,
                \Spatie\LaravelData\Normalizers\ObjectNormalizer::class,
                \Spatie\LaravelData\Normalizers\ArrayNormalizer::class,
                \Spatie\LaravelData\Normalizers\JsonNormalizer::class,
            ],
            'wrap' => null,
            'var_dumper_caster_mode' => 'development',
            'structure_caching' => [
                'enabled' => false,
                'directories' => [],
                'cache' => [
                    'store' => 'file',
                    'prefix' => 'laravel-data',
                    'duration' => null,
                ],
                'reflection_discovery' => [
                    'enabled' => false,
                    'base_path' => base_path(),
                    'root_namespace' => null,
                ],
            ],
            'validation_strategy' => ValidationStrategy::Disabled->value,
            'name_mapping_strategy' => [
                'input' => null,
                'output' => null,
            ],
            'ignore_invalid_partials' => false,
            'max_transformation_depth' => null,
            'throw_when_max_transformation_depth_reached' => true,
            'commands' => [
                'make' => [
                    'namespace' => 'Data',
                    'suffix' => 'Data',
                ],
            ],
            'livewire' => [
                'enable_synths' => false,
            ],
        ]);

        $app['config']->set('text-tokenization', [
            'default' => 'test',
            'connections' => [
                'test' => [
                    'driver' => 'test-driver',
                    'url' => 'https://example.test',
                    'model' => 'model-1',
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer test',
                    ],
                ],
            ],
            'drivers' => [
                'test-driver' => [
                    'driver_class' => \Tests\Support\TestTokenizationDriver::class,
                    'config' => [
                        'endpoint_uri' => '/v1/tokenize',
                    ],
                ],
            ],
        ]);
    }
}


