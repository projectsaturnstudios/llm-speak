<?php

namespace LLMSpeak\Core\Drivers\Interaction;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;
use LLMSpeak\Core\DTO\Primitives\VectorEmbedding;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelEmbeddingsException;

abstract class ModelEmbeddingsDriver extends ModelInteractionDriver
{
    protected string $config_name = 'vector-embeddings';

    abstract protected function generateRequestBody(array $input, EmbeddingsModel $neural_model);
    abstract protected function generateEmbeddings(array $output): array;

    /**
     * @param array $input
     * @param NeuralModel $neural_model
     * @return array
     * @throws ModelTokenizationException
     */
    public function request(array $input, NeuralModel &$neural_model): array
    {
        if(!$neural_model instanceof EmbeddingsModel) throw ModelEmbeddingsException::invalidModelType($neural_model::class);

        $results = $this->generateRequestBody($input, $neural_model);
        $neural_model = $neural_model->addToOriginal('raw_request', $results);
        return $results;
    }

    /**
     * @param APIResponse $output
     * @param NeuralModel $neural_model
     * @return array
     * @throws ModelTokenizationException
     */
    public function generateModelsFromResponse(APIResponse $output, NeuralModel $neural_model): array
    {
        if(!$neural_model instanceof EmbeddingsModel) throw ModelEmbeddingsException::invalidModelType($neural_model::class);

        [$usage, $vectors] = $this->generateEmbeddings($output->body);

        return array_map(
            fn(VectorEmbedding $embeddings) => (clone $neural_model)
                ->setLatestResponse($embeddings)
                ->setUsage($usage)
                ->addToOriginal('raw_response', $output->body),
            $vectors
        );
    }
}
