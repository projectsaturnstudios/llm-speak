<?php

namespace Tests\Support;

use LLMSpeak\Core\DTO\Api\APIResponse;
use LLMSpeak\Core\DTO\Primitives\Token;
use LLMSpeak\Core\NeuralModels\TokenizationModel;
use LLMSpeak\Core\Contracts\NeuralModels\NeuralModel;
use LLMSpeak\Core\Drivers\Interaction\ModelTokenizationDriver;
use LLMSpeak\Core\DTO\Schema\Tokenization\TokenizedText;

class TestTokenizationDriver extends ModelTokenizationDriver
{
    protected string $driver_name = 'test-driver';

    protected function generateRequestBody(array $input, TokenizationModel $neural_model)
    {
        return array_map(fn(string $text) => [
            'text' => $text,
            'model' => $neural_model->modelId(),
        ], $input);
    }

    protected function generateTokenizedText(array $output): array
    {
        $tokens = $output['tokens'] ?? [];
        $token_set = array_map(fn(array $raw) => new TokenizedText(
            id: (string) ($raw['token_id'] ?? '0'),
            string_represented: (string) ($raw['string_token'] ?? ''),
            token: new Token($raw['token_bytes'] ?? []),
        ), $tokens);

        return [$token_set];
    }
}






