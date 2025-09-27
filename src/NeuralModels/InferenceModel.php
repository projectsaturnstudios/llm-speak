<?php

namespace LLMSpeak\Core\NeuralModels;

use LLMSpeak\Core\DTO\Primitives\TextObject;
use LLMSpeak\Core\Support\Facades\AIInference;
use LLMSpeak\Core\Eloquent\InferenceModelBuilder;
use LLMSpeak\Core\Concerns\NeuralModels\MaxTokens;
use LLMSpeak\Core\Concerns\NeuralModels\RandomSeed;
use LLMSpeak\Core\Concerns\NeuralModels\Temperature;
use LLMSpeak\Core\Concerns\NeuralModels\PresencePenalty;
use LLMSpeak\Core\Concerns\NeuralModels\MultipleGoesAtIt;
use LLMSpeak\Core\Concerns\NeuralModels\FrequencyPenalty;
use LLMSpeak\Core\Concerns\NeuralModels\StreamingSettings;

class InferenceModel extends NeuralModel
{
    /** Request Control Concerns */
    use FrequencyPenalty, Temperature, PresencePenalty, RandomSeed;
    use MaxTokens, MultipleGoesAtIt, StreamingSettings;

    protected static string $builder = InferenceModelBuilder::class;

    protected string|int|null $id = null;

    /**
     * The latest prompt sent to the model.
     * @var string|array|null
     */
    protected string|array|null $latest_prompt = null;

    protected ?TextObject $latest_message = null;
    protected ?string $created_at = null;
    protected ?array $raw_message = null;
    protected ?array $usage = null;

    public function __construct(?string $conn = null, ?string $model_name = null) {
        $this->connection = $conn ?? ($this->connection ?? AIInference::defaultConnection());
        $this->model_id = $model_name ?? ($this->model_id ?? AIInference::connectionDefaultModelID($this->connection));
    }

    /** Response Controls  */
    public function setId(string|int $id): static
    {
        $this->id = $id;
        return $this->addAttribute("id", $id);
    }

    public function setLatestPrompt(string|array $prompt): static
    {
        $this->latest_prompt = $prompt;

        return $this;
    }

    public function setLatestMessage(TextObject $message, array $raw_response): static
    {
        $this->latest_message = $message;
        $this->raw_message = $raw_response;

        return $this->addAttribute("message", $message->toValue());
    }

    public function setCreatedDate(?string $created_at): static
    {
        $this->created_at = $created_at;
        return $this->addAttribute("created_at", $created_at);
    }

    public function setUsage(?array $usage): static
    {
        return $this->addAttribute("usage", $usage);
    }

}
