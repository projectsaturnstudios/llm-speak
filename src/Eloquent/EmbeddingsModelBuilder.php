<?php

namespace LLMSpeak\Core\Eloquent;

use LLMSpeak\Core\Managers\EmbeddingsManager;
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;
use LLMSpeak\Core\Exceptions\NeuralModelException;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelEmbeddingsException;

/**
 * Class EmbeddingsModelBuilder
 * @method static whereInput(string $text)
 * @method static whereDimensions(int $dimensions)
 * @method static whereEncodingFormat(string $encoding_format)
 * @method static whereUser(int|string $user_id)
 * @method static whereTitle(string $title)
 * @method static whereTaskType(string $task_type)
 *
 * @package LLMSpeak\Core\Eloquent
 */
class EmbeddingsModelBuilder extends NeuralModelBuilder
{
    protected array $inputs = [];

    public function __construct(EmbeddingsManager $manager)
    {
        parent::__construct($manager);
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return $this
     * @throws NeuralModelException
     */
    public function where(string $column, mixed $value = null): static
    {
        match ($column) {
            'input' => $this->inputs[] = $value,
            'dimensions' => $this->neural_model = $this->neural_model->addToOriginal('dimensions', $value),
            'encoding_format' => $this->neural_model = $this->neural_model->addToOriginal('encoding_format', $value),
            'user' => $this->neural_model = $this->neural_model->addToOriginal('user', $value),
            'title' => $this->neural_model = $this->neural_model->addToOriginal('title', $value),
            'task_type' => $this->neural_model = $this->neural_model->addToOriginal('task_type', $value),
            default => throw NeuralModelException::BadWhereMethodCall($column),
        };
        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     * @throws NeuralModelException
     */
    public function whereIn(string $column, array $values): static
    {
        match ($column) {
            'input' => array_merge($this->inputs, $values),
            default => throw NeuralModelException::BadWhereMethodCall($column),
        };
        return $this;
    }

    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function getInput(): array
    {
        return $this->getInputs();
    }

    /**
     * @param NeuralModel $model
     * @return $this
     * @throws ModelTokenizationException
     */
    public function setNeuralModel(NeuralModel $model): static
    {
        if(!$model instanceof EmbeddingsModel) throw ModelEmbeddingsException::invalidModelType($model::class);

        return parent::setNeuralModel(
            $model->setInput($this->inputs)
        );
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws NeuralModelException
     */
    public function __call($method, $parameters): static
    {
        if(str_contains($method, 'where')) {
            $action = str_replace('where', '', $method);
            return match ($action) {
                'Input' => $this->where('input', $parameters[0]),
                'Dimensions' => $this->where('dimensions', $parameters[0]),
                'EncodingFormat' => $this->where('encoding_format', $parameters[0]),
                'User' => $this->where('user', $parameters[0]),
                'Title' => $this->where('title', $parameters[0]),
                'TaskType' => $this->where('task_type', $parameters[0]),
                default => throw NeuralModelException::BadWhereMethodCall($method),
            };
        }

        throw NeuralModelException::BadMagicFunctionCall($method);
    }

    public static function boot() : void
    {
        app()->instance(static::class, new static(app(EmbeddingsManager::class)));
    }
}
