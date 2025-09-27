<?php

namespace LLMSpeak\Core\NeuralModels;

use LLMSpeak\Core\Support\Facades\AIEmbeddings;
use LLMSpeak\Core\DTO\Primitives\VectorEmbedding;
use LLMSpeak\Core\Eloquent\EmbeddingsModelBuilder;

class EmbeddingsModel extends NeuralModel
{
    protected static string $builder = EmbeddingsModelBuilder::class;

    /**
     * The latest prompt sent to the model.
     * @var string|array<string>|null
     */
    protected string|array|null $input = null;

    /**
     * The latest response from the model.
     * @var VectorEmbedding|null
     */
    protected ?VectorEmbedding $latest_response = null;

    public ?array $usage = null;

    public function __construct(?string $conn = null, ?string $model_name = null) {
        $this->connection = $conn ?? ($this->connection ?? AIEmbeddings::defaultConnection());
        $this->model_id = $model_name ?? ($this->model_id ?? AIEmbeddings::connectionDefaultModelID($this->connection));
    }


    public function setInput(string|array $input): static
    {
        $this->input = $input;
        return $this;
    }

    public function setUsage(array $usage): static
    {
        $this->usage = $usage;
        return $this;
    }

    /**
     * @param array<VectorEmbedding> $latest_response
     * @return $this
     */
    public function setLatestResponse(VectorEmbedding $latest_response): static
    {
        $this->latest_response = $latest_response;
        $this->attributes = [
            'embeddings' => $this->latest_response
        ];
        return $this;
    }

    /**
     * Get the tokenized text as an array of VectorEmbedding objects.
     *
     * @return ?VectorEmbedding
     */
    public function getEmbeddings(): ?VectorEmbedding
    {
        return $this->latest_response ?? null;
    }

    /**
     * Get the raw token data as an array.
     *
     * @return array
     */
    public function getRawEmbeddings(): array
    {
        return array_map(fn(VectorEmbedding $embedding) => $embedding->toArray(), $this->latest_response ?? []);
    }
}
