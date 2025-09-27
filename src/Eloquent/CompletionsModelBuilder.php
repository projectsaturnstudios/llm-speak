<?php

namespace LLMSpeak\Core\Eloquent;

use Illuminate\Http\Client\ConnectionException;
use LLMSpeak\Core\Actions\Endpoints\PostEndpoint;
use LLMSpeak\Core\Collections\ChatConversation;
use LLMSpeak\Core\Collections\NeuralModelCollection;
use LLMSpeak\Core\Drivers\Interaction\ModelCompletionsDriver;
use LLMSpeak\Core\Drivers\Interaction\ModelInteractionDriver;
use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\DTO\Primitives\TextObject;
use LLMSpeak\Core\DTO\Schema\Completions\ContentMessage;
use LLMSpeak\Core\DTO\Schema\Completions\TextOnlyContentMessage;
use LLMSpeak\Core\Enums\ConversationRole;
use LLMSpeak\Core\Exceptions\ModelTokenizationException;
use LLMSpeak\Core\NeuralModels\CompletionsModel;
use LLMSpeak\Core\Managers\ChatCompletionsManager;
use LLMSpeak\Core\Exceptions\NeuralModelException;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelCompletionsException;

/**
 * Class CompletionsModelBuilder
 * @method static whereMessage(string|array $text)
 * @method static whereMessages(string|array $text)
 * @method static whereTemperature(float $temperature)
 * @method static whereMaxTokens(int $max_tokens)
 * @method static whereFrequencyPenalty(float $frequency_penalty)
 * @method static wherePresencePenalty(float $presence_penalty)
 * @method static whereSeed(int $seed)
 * @method static whereN(int $n)
 *
 * @package LLMSpeak\Core\Eloquent
 */
class CompletionsModelBuilder extends NeuralModelBuilder
{
    protected array $messages = [];

    public function __construct(
        ChatCompletionsManager $manager
    )
    {
        parent::__construct($manager);
    }

    protected function addMessages(array $messages): void
    {
        foreach($messages as $message)
        {
            $this->addMessage($message);
        }
    }

    protected function compileMessage(string|array $message): ContentMessage|array
    {
        if(is_string($message))
        {
            return (new TextOnlyContentMessage(ConversationRole::USER, new TextObject($message)));
        }
        elseif(is_array($message))
        {
            return array_map([$this, 'compileMessage'], $message);
        }
        else
        {
            dd("Support exotics as the message", $message);
        }
    }

    protected function addMessage(string|array|ContentMessage $message): void
    {
        /** @var CompletionsModel $neural_model */
        $neural_model = $this->neural_model;

        if(is_string($message))
        {
            $message = (clone $neural_model)->getCurrentConversation()->add(
                $this->compileMessage($message)
            );
        }
        elseif(is_array($message))
        {
            $message = (clone $neural_model)->getCurrentConversation()->add(
                $this->compileMessage($message)
            );
        }
        else
        {
            dd("Support exotics as the message", $message);
        }

        $this->messages[] = $message;

    }

    /**
     * @param string $column
     * @param mixed|null $value
     * @return $this
     * @throws NeuralModelException
     */
    public function where(string $column, mixed $value = null): static
    {
        /** @var CompletionsModel $neural_model */
        $neural_model = &$this->neural_model;
        match ($column) {
            'message' => $this->addMessage($value),
            'temperature' => $neural_model = $neural_model->setTemperature($value),
            'frequency_penalty' => $neural_model = $neural_model->setFrequencyPenalty($value),
            'presence_penalty' => $neural_model = $neural_model->setPresencePenalty($value),
            'seed' => $neural_model = $neural_model->setSeed($value),
            'max_tokens' => $neural_model = $neural_model->setMaxTokens($value),
            'n' => $neural_model = $neural_model->setN($value),
            'system_instructions' => $neural_model = $neural_model->setSystemInstructions(is_array($value) ? $value : [$value]),
            'tools' => $neural_model = $neural_model->setTools(is_array($value) ? $value : [$value]),

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
            'message' => $this->addMessages($values),
            'system_instructions' => $this->where('system_instructions', $values),
            'tools' => $this->where('tools', $values),
            default => throw NeuralModelException::BadWhereMethodCall($column),
        };

        return $this;
    }

    public function continue(): NeuralModelCollection
    {
        $results = new NeuralModelCollection();

        $this->messages[] =  $this->neural_model->getCurrentConversation();
        [[$requests, $model], $headers, $url] = $this->prepareRequest();

        /** @var array<APIResponse> $responses */
        $responses = array_map(function(array $request) use ($model, $headers, $url) {
            return ((new PostEndpoint($headers, $url, $model))->handle($request));
        }, $requests);

        if(count($responses) > 0)
        {
            $this->processResults($responses, $results);
        };

        return $results;

    }
    /**
     * @return NeuralModelCollection
     * @throws ConnectionException
     * @throws ModelTokenizationException
     */
    public function get(): NeuralModelCollection
    {
        $results = new NeuralModelCollection();

        /**
         * @var array<array> $requests
         * @var array $headers
         * @var string $url
         * @var string $model
         */
        [[$requests, $model], $headers, $url] = $this->prepareRequest();

        /** @var array<APIResponse> $responses */
        $responses = array_map(function(array $request) use ($model, $headers, $url) {
            return ((new PostEndpoint($headers, $url, $model))->handle($request));
        }, $requests);

        if(count($responses) > 0)
        {
            $this->processResults($responses, $results);
        };

        return $results;
    }

    protected function processResults(array $responses, NeuralModelCollection &$results): void
    {
        $connection = $this->neural_model->connection();
        /** @var ModelCompletionsDriver $driver */
        $driver = $this->manager->connection($connection);
        foreach($responses as $idx => $response) {
            /** @var array<CompletionsModel> $models */
            $models = $driver->generateModelsFromResponseCustom($response, $this->neural_model, $idx);
            foreach($models as $model) {
                $results = $results->push($model);
            }
        }
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getInput(): array
    {
        return $this->getMessages();
    }

    /**
     * @param CompletionsModel $model
     * @return $this
     * @throws ModelCompletionsException
     */
    public function setNeuralModel(NeuralModel $model): static
    {
        if(!$model instanceof CompletionsModel) throw ModelCompletionsException::invalidModelType($model::class);

        $this->messages = [];
        return parent::setNeuralModel(
            $model->setLatestRequestMessage($this->messages)
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
                'Message' => $this->where('message', $parameters[0]),
                'Temperature' => $this->where('temperature', $parameters[0]),
                'MaxTokens' => $this->where('max_tokens', $parameters[0]),
                'FrequencyPenalty' => $this->where('frequency_penalty', $parameters[0]),
                'PresencePenalty' => $this->where('presence_penalty', $parameters[0]),
                'Seed' => $this->where('seed', $parameters[0]),
                'N' => $this->where('n', $parameters[0]),
                'Messages' => $this->whereIn('message', $parameters[0]),
                'SystemInstructions' => $this->whereIn('system_instructions', $parameters[0]),
                'Tools' => $this->whereIn('tools', $parameters[0]),
                'SystemInstruction' => $this->where('system_instructions', $parameters[0]),
                'Tool' => $this->where('tools', $parameters[0]),
                default => throw NeuralModelException::BadWhereMethodCall($method),
            };
        }

        throw NeuralModelException::BadMagicFunctionCall($method);
    }

    public static function boot() : void
    {
        app()->instance(static::class, new static(app(ChatCompletionsManager::class)));
    }
}
