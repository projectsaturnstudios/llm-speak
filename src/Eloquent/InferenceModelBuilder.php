<?php

namespace LLMSpeak\Core\Eloquent;

use LLMSpeak\Core\Managers\InferenceManager;
use LLMSpeak\Core\NeuralModels\InferenceModel;
use LLMSpeak\Core\Exceptions\NeuralModelException;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelInferencingException;

/**
 * Class InferenceModelBuilder
 * @method static wherePrompt(string|array $text)
 * @method static whereTemperature(float $temperature)
 * @method static whereMaxTokens(int $max_tokens)
 * @method static whereFrequencyPenalty(float $frequency_penalty)
 * @method static wherePresencePenalty(float $presence_penalty)
 * @method static whereSeed(int $seed)
 * @method static whereN(int $n)
 *
 * @package LLMSpeak\Core\Eloquent
 */
class InferenceModelBuilder extends NeuralModelBuilder
{
    /**
     * An array of elements made of either strings or arrays or strings.
     * Each element represents an individual prompt to be sent to the model for inference.
     * @var array<string|array<string>> $prompts
     */
    protected array $prompts = [];

    public function __construct(InferenceManager $manager)
    {
        parent::__construct($manager);
    }

    /**
     * @param string $column
     * @param mixed|null $value
     * @return $this
     * @throws NeuralModelException
     */
    public function where(string $column, mixed $value = null): static
    {
        /** @var InferenceModel $neural_model */
        $neural_model = &$this->neural_model;
        match ($column) {
            'prompt' => $this->prompts[] = $value,
            'temperature' => $neural_model = $neural_model->setTemperature($value),
            'frequency_penalty' => $neural_model = $neural_model->setFrequencyPenalty($value),
            'presence_penalty' => $neural_model = $neural_model->setPresencePenalty($value),
            'seed' => $neural_model = $neural_model->setSeed($value),
            'max_tokens' => $neural_model = $neural_model->setMaxTokens($value),
            'n' => $neural_model = $neural_model->setN($value),

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
            'prompt' => $this->prompts = array_merge($this->prompts, $values),
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
     * @param InferenceModel $model
     * @return $this
     * @throws ModelInferencingException
     */
    public function setNeuralModel(NeuralModel $model): static
    {
        if(!$model instanceof InferenceModel) throw ModelInferencingException::invalidModelType($model::class);

        return parent::setNeuralModel(
            $model->setLatestPrompt($this->prompts)
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
                'Prompt' => $this->where('prompt', $parameters[0]),
                'Temperature' => $this->where('temperature', $parameters[0]),
                'MaxTokens' => $this->where('max_tokens', $parameters[0]),
                'FrequencyPenalty' => $this->where('frequency_penalty', $parameters[0]),
                'PresencePenalty' => $this->where('presence_penalty', $parameters[0]),
                'Seed' => $this->where('seed', $parameters[0]),
                'N' => $this->where('n', $parameters[0]),

                default => throw NeuralModelException::BadWhereMethodCall($method),
            };
        }

        throw NeuralModelException::BadMagicFunctionCall($method);
    }

    public static function boot() : void
    {
        app()->instance(static::class, new static(app(InferenceManager::class)));
    }
}
