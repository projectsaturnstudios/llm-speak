<?php

namespace LLMSpeak\Core\NeuralModels;

use ReflectionClass;
use Illuminate\Support\Traits\ForwardsCalls;
use LLMSpeak\Core\Support\Facades\AITokenizer;
use LLMSpeak\Core\Contracts\Eloquent\NeuralModelBuilder;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel as NeuralModelContract;

abstract class NeuralModel implements NeuralModelContract
{
    use ForwardsCalls;
    /**
     * The provider connection name for the model.
     * @var string
     */
    protected string $connection;

    /**
     * The model name or identifier.
     * @var string
     */
    protected string $model_id;

    public ?array $original = null;

    public ?array $attributes = null;

    public ?array $metadata = null;

    protected static string $builder = NeuralModelBuilder::class;

    public function connection(): string
    {
        return $this->connection;
    }

    public function modelId(): string
    {
        return $this->model_id;
    }

    public function setModelId(string $model_id): static
    {
        $this->model_id = $model_id;
        return $this;
    }

    public function setConnection(string $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function newQuery(): NeuralModelBuilder
    {
        return $this->newModelQuery();
    }

    public function newModelQuery(): NeuralModelBuilder
    {
        return $this->newNeuralModelBuilder()
            ->setNeuralModel($this);
    }

    public function newNeuralModelBuilder(): NeuralModelBuilder
    {
        /*$builderClass = $this->resolveCustomBuilderClass();

        if ($builderClass && is_subclass_of($builderClass, NeuralModelBuilder::class)) {
            return new $builderClass($query);
        }*/

        $class = static::$builder;
        return app($class);
    }

    protected function resolveCustomBuilderClass()
    {
        $attributes = (new ReflectionClass($this));

        return ! empty($attributes)
            ? $attributes[0]->newInstance()->builderClass
            : false;
    }

    public function addToOriginal(string $key, mixed $original): static
    {
        $this->original[$key] = $original;
        return $this;
    }

    public function addAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getOriginal(): ?array
    {
        return $this->original;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }
}
