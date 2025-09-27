<?php

namespace LLMSpeak\Core\Providers;

use LLMSpeak\Core\Eloquent\InferenceModelBuilder;
use LLMSpeak\Core\Eloquent\EmbeddingsModelBuilder;
use LLMSpeak\Core\Eloquent\CompletionsModelBuilder;
use LLMSpeak\Core\Eloquent\TokenizationModelBuilder;
use ProjectSaturnStudios\LaravelDesignPatterns\Providers\BaseServiceProvider;

class LLMSpeakCoreServiceProvider extends BaseServiceProvider
{
    protected array $config = [
        'text-tokenization' => __DIR__ . '/../../config/text-tokenization.php',
        'vector-embeddings' => __DIR__ . '/../../config/vector-embeddings.php',
        'inferencing' => __DIR__ . '/../../config/inferencing.php',
        'chat-completions' => __DIR__ . '/../../config/chat-completions.php',
    ];
    protected array $publishable_config = [
        ['key' => 'text-tokenization', 'file_path' => 'text-tokenization.php', 'groups' => ['llms', 'llms.tt']],
        ['key' => 'vector-embeddings', 'file_path' => 'vector-embeddings.php', 'groups' => ['llms', 'llms.ve']],
        ['key' => 'inferencing', 'file_path' => 'inferencing.php', 'groups' => ['llms', 'llms.mi']],
        ['key' => 'chat-completions', 'file_path' => 'chat-completions.php', 'groups' => ['llms', 'llms.cc']],
    ];

    protected array $bootables = [
        TokenizationModelBuilder::class,
        EmbeddingsModelBuilder::class,
        InferenceModelBuilder::class,
        CompletionsModelBuilder::class,
    ];

}
