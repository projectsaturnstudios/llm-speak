<?php

namespace LLMSpeak\Core\Drivers\Interaction;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\NeuralModels\InferenceModel;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelInferencingException;

abstract class ModelInferenceDriver extends ModelInteractionDriver
{
    protected string $config_name = 'inferencing';

    abstract protected function generateRequestBody(array $input, InferenceModel $neural_model);
    abstract protected function generateInference(array $output): array;

    /**
     * @param array $input
     * @param InferenceModel $neural_model
     * @return array
     * @throws ModelInferencingException
     */
    public function request(array $input, NeuralModel &$neural_model): array
    {
        if(!$neural_model instanceof InferenceModel) throw ModelInferencingException::invalidModelType($neural_model::class);

        $results = $this->generateRequestBody($input, $neural_model);

        $neural_model = $neural_model->addToOriginal('raw_request', $results);
        return $results;
    }

    /**
     * @param APIResponse $output
     * @param InferenceModel $neural_model
     * @return array
     * @throws ModelInferencingException
     */
    public function generateModelsFromResponse(APIResponse $output, NeuralModel $neural_model): array
    {
        if(!$neural_model instanceof InferenceModel) throw ModelInferencingException::invalidModelType($neural_model::class);

        [$id, $usage, $created_at, $metadata, $messages] = $this->generateInference($output->body);

        return array_map(
            fn($message) => (clone $neural_model)
                ->setId($id)
                ->setLatestMessage($message, $output->body)
                ->setCreatedDate($created_at)
                ->setMetadata($metadata)
                ->setUsage($usage),
            $messages
        );
    }
}
