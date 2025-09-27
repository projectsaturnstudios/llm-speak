<?php

namespace LLMSpeak\Core\Drivers\Interaction;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\NeuralModels\TokenizationModel;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelTokenizationException;

abstract class ModelTokenizationDriver extends ModelInteractionDriver
{
    protected string $config_name = 'text-tokenization';

    abstract protected function generateRequestBody(array $input, TokenizationModel $neural_model);
    abstract protected function generateTokenizedText(array $output): array;

    /**
     * @param array $input
     * @param NeuralModel $neural_model
     * @return array
     * @throws ModelTokenizationException
     */
    public function request(array $input, NeuralModel &$neural_model): array
    {
        if(!$neural_model instanceof TokenizationModel) throw ModelTokenizationException::invalidModelType($neural_model::class);

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
        if(!$neural_model instanceof TokenizationModel) throw ModelTokenizationException::invalidModelType($neural_model::class);

        $tokenized = $this->generateTokenizedText($output->body);

        return array_map(fn(array $tokens) => (clone $neural_model)
            ->setLatestResponse($tokens)
            ->addToOriginal('raw_response', $output->body),
            $tokenized);
    }
}
