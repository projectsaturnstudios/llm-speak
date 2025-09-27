<?php

namespace LLMSpeak\Core\Eloquent;

use LLMSpeak\Core\Drivers\Interaction\ModelInteractionDriver;
use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\Exceptions\ModelInteractionException;
use LLMSpeak\Core\Exceptions\ModelTokenizationException;
use LLMSpeak\Core\Managers\InteractionManager;
use Illuminate\Http\Client\ConnectionException;
use LLMSpeak\Core\Actions\Endpoints\PostEndpoint;
use LLMSpeak\Core\Collections\NeuralModelCollection;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Contracts\Eloquent\NeuralModelBuilder as NeuralModelBuilderContract;
use Symfony\Component\VarDumper\VarDumper;

abstract class NeuralModelBuilder implements NeuralModelBuilderContract
{
    protected ?NeuralModel $neural_model = null;

    public function __construct(
        protected InteractionManager $manager
    ) {}

    /**
     * @return array
     */
    abstract public function getInput(): array;
    /**
     * @param string $column
     * @param mixed|null $value
     * @return $this
     */
    abstract public function where(string $column, mixed $value = null): static;

    /**
     * @param string $column
     * @param array $values
     * @return $this
     */
    abstract public function whereIn(string $column, array $values): static;



    /**
     * @return NeuralModelCollection
     * @throws ConnectionException
     * @throws ModelInteractionException
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

        if(count($responses) > 0) $this->processResults($responses, $results);

        return $results;
    }

    /**
     * @return NeuralModel|null
     * @throws ConnectionException
     * @throws ModelTokenizationException
     */
    public function first(): ?NeuralModel
    {
        return $this->get()->first();
    }

    /**
     * @param NeuralModel $model
     * @return $this
     */
    public function setNeuralModel(NeuralModel $model): static
    {
        $this->neural_model = $model;

        return $this;
    }

    /**
     * Get the affected Eloquent model.
     *
     * @return NeuralModel|null
     */
    public function getNeuralModel(): ?NeuralModel
    {
        return $this->neural_model;
    }

    /**
     * @return array
     * @throws ModelTokenizationException
     */
    protected function prepareRequest(): array
    {
        $connection = $this->neural_model->connection();
        $driver = $this->manager->connection($connection);

        return [
            [
                $driver->request($this->getInput(), $this->neural_model),
                $this->neural_model->modelId()
            ],
            $this->manager->connectionHeaders($connection),
            $driver->generateEndpointUrl($this->manager->connectionUrl($connection))
        ];
    }

    /**
     * @param array<APIResponse> $responses
     * @param NeuralModelCollection $results
     * @return void
     */
    protected function processResults(array $responses, NeuralModelCollection &$results): void
    {
        $connection = $this->neural_model->connection();
        /** @var ModelInteractionDriver $driver */
        $driver = $this->manager->connection($connection);
        foreach($responses as  $response) {
            /** @var array<NeuralModel> $models */
            $models = $driver->generateModelsFromResponse($response, $this->neural_model);
            foreach($models as $model) {
                $results = $results->push($model);
            }
        }
    }


    /**
     * @param $method
     * @param $parameters
     * @return $this
     */
    abstract public function __call($method, $parameters): static;
}
