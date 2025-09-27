<?php

namespace LLMSpeak\Core\Drivers\Interaction;

use LLMSpeak\Core\Collections\ChatConversation;
use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\NeuralModels\InferenceModel;
use LLMSpeak\Core\NeuralModels\CompletionsModel;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Exceptions\ModelCompletionsException;

abstract class ModelCompletionsDriver extends ModelInteractionDriver
{
    protected string $config_name = 'chat-completions';

    private array $conversations = [];

    abstract protected function generateRequestBody(array $input, CompletionsModel $neural_model);
    abstract protected function generateCompletion(array $output): array;

    /**
     * @param array<ChatConversation> $input
     * @param InferenceModel $neural_model
     * @return array
     * @throws ModelCompletionsException
     */
    public function request(array $input, NeuralModel &$neural_model): array
    {
        if(!$neural_model instanceof CompletionsModel) throw ModelCompletionsException::invalidModelType($neural_model::class);

        // We need this for processing after the request is made.
        $this->conversations = array_map(fn(ChatConversation $convo) => [
            'input' => $convo,
            'model' => clone $neural_model,
        ], $input);
        // @todo - don't support "n" until you can predict how many messages will be returned.

        $results = $this->generateRequestBody($input, $neural_model);

        foreach($this->conversations as $idx => $conversation)
        {
            $this->conversations[$idx]['model'] = $this->conversations[$idx]['model']->addToOriginal('raw_request', $results[$idx]);
        }

        return $results;
    }

    /**
     * @param APIResponse $output
     * @param InferenceModel $neural_model
     * @return array
     * @throws ModelCompletionsException
     */
    public function generateModelsFromResponse(APIResponse $output, NeuralModel $neural_model): array
    {
        return $this->generateModelsFromResponseCustom($output, $neural_model, 0);
    }

    /**
     * @param APIResponse $output
     * @param InferenceModel $neural_model
     * @return array
     * @throws ModelCompletionsException
     */
    public function generateModelsFromResponseCustom(APIResponse $output, NeuralModel $neural_model, int $index): array
    {
        if(!$neural_model instanceof CompletionsModel) throw ModelCompletionsException::invalidModelType($neural_model::class);

        [$id, $usage, $created_at, $metadata, $messages] = $this->generateCompletion($output->body);

        //dd(['op' => $output->body, 'messages' => $messages, 'count' => count($messages)]);
        $results = [];
        foreach($messages as $idx => $message)
        {

            $convo = clone $this->conversations[$index]['input'];
            $convo = $convo->push($message);
            $model = clone $this->conversations[$index]['model'];
            $results[$idx] =  ($model)
                ->setId($id)
                ->setCurrentConversation($convo)
                ->setLatestResponseMessage($message, $output->body)
                ->setCreatedDate($created_at)
                ->setMetadata($metadata)
                ->setUsage($usage);
        }

        return $results;

    }
}
