<?php

namespace LLMSpeak\Core\Support\Requests;

use Spatie\LaravelData\Data;

class LLMSpeakEmbeddingsRequest extends LLMSpeakRequest
{
    /**
     * @param string $model
     * @param string|array $input - Text(s) to embed
     * @param string|null $encoding_format - 'float'|'base64'
     * @param int|null $dimensions - Vector dimensions
     * @param string|null $task_type - Task optimization
     */
    public function __construct(
        string $model,
        public string|array $input,
        public ?string $encoding_format,
        public ?int $dimensions,
        public ?string $task_type
    )
    {
        parent::__construct($model);
    }

    public function type(): string
    {
        return 'embeddings';
    }
}
