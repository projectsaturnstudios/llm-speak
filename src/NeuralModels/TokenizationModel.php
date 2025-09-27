<?php

namespace LLMSpeak\Core\NeuralModels;

use LLMSpeak\Core\Eloquent\TokenizationModelBuilder;
use LLMSpeak\Core\DTO\Schema\Tokenization\TokenizedText;
use LLMSpeak\Core\Support\Facades\AITokenizer;

class TokenizationModel extends NeuralModel
{
    protected static string $builder = TokenizationModelBuilder::class;
    /**
     * The latest prompt sent to the model.
     * @var string|array<string>|null
     */
    protected string|array|null $prompt = null;

    /**
     * The latest response from the model.
     * @var TokenizedText[]|null
     */
    protected ?array $latest_response = null;

    public function __construct(?string $conn = null, ?string $model_name = null) {
        $this->connection = $conn ?? ($this->connection ?? AITokenizer::defaultConnection());
        $this->model_id = $model_name ?? ($this->model_id ?? AITokenizer::connectionDefaultModelID($this->connection));
    }

    public function setPrompt(string|array $prompt): static
    {
        $this->prompt = $prompt;
        return $this;
    }

    /**
     * @param array<TokenizedText> $latest_response
     * @return $this
     */
    public function setLatestResponse(array $latest_response): static
    {
        $this->latest_response = $latest_response;
        $this->attributes = [
            'tokens' => $this->latest_response
        ];
        return $this;
    }

    /**
     * Get the tokenized text as an array of TokenizedText objects.
     *
     * @return TokenizedText[]
     */
    public function getTokens(): array
    {
        return $this->latest_response ?? [];
    }

    /**
     * Get the raw token data as an array.
     *
     * @return array
     */
    public function getRawTokens(): array
    {
        return array_map(fn(TokenizedText $token) => $token->toArray(), $this->latest_response ?? []);
    }
}
