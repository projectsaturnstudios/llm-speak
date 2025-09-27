<?php

namespace LLMSpeak\Core\Eloquent;

use LLMSpeak\Core\Managers\TokenizationManager;
use LLMSpeak\Core\NeuralModels\TokenizationModel;
use LLMSpeak\Core\Exceptions\NeuralModelException;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelTokenizationException;

/**
 * Class TokenizationModelBuilder
 * @method static whereText(string $text)
 *
 * @package LLMSpeak\Core\Eloquent
 */
class TokenizationModelBuilder extends NeuralModelBuilder
{
    protected array $prompts = [];

    public function __construct(
        TokenizationManager $manager
    )
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
        $this->prompts[] = match ($column) {
            'text' => $value,
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
        $this->prompts = match ($column) {
            'text' => array_merge($this->prompts, $values),
            default => throw NeuralModelException::BadWhereMethodCall($column),
        };
        return $this;
    }

    public function getPrompts(): array
    {
        return $this->prompts;
    }

    public function getInput(): array
    {
        return $this->getPrompts();
    }

    /**
     * @param NeuralModel $model
     * @return $this
     * @throws ModelTokenizationException
     */
    public function setNeuralModel(NeuralModel $model): static
    {
        if(!$model instanceof TokenizationModel) throw ModelTokenizationException::invalidModelType($model::class);

        return parent::setNeuralModel(
            $model->setPrompt($this->prompts)
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
                'Text' => $this->where('text', $parameters[0]),
                default => throw NeuralModelException::BadWhereMethodCall($method),
            };
        }

        throw NeuralModelException::BadMagicFunctionCall($method);
    }

    public static function boot() : void
    {
        app()->instance(static::class, new static(app(TokenizationManager::class)));
    }
}
