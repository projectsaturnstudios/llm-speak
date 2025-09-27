<?php

namespace LLMSpeak\Core\NeuralModels;

use Illuminate\Support\Collection;
use LLMSpeak\Core\Collections\ChatConversation;
use LLMSpeak\Core\Support\Facades\AICompletions;
use LLMSpeak\Core\Concerns\NeuralModels\MaxTokens;
use LLMSpeak\Core\Concerns\NeuralModels\RandomSeed;
use LLMSpeak\Core\Eloquent\CompletionsModelBuilder;
use LLMSpeak\Core\Concerns\NeuralModels\Temperature;
use LLMSpeak\Core\Concerns\NeuralModels\PresencePenalty;
use LLMSpeak\Core\DTO\Schema\Completions\ContentMessage;
use LLMSpeak\Core\Concerns\NeuralModels\FrequencyPenalty;
use LLMSpeak\Core\Concerns\NeuralModels\MultipleGoesAtIt;
use LLMSpeak\Core\Concerns\NeuralModels\StreamingSettings;

class CompletionsModel extends NeuralModel
{
    /** Request Control Concerns */
    use FrequencyPenalty, Temperature, PresencePenalty, RandomSeed;
    use MaxTokens, MultipleGoesAtIt, StreamingSettings;

    protected static string $builder = CompletionsModelBuilder::class;

    protected string|int|null $id = null;

    protected ChatConversation|null $current_conversation = null;

    protected array $system_instructions = [];

    protected array $tools = [];

    /**
     * The latest prompt sent to the model.
     * @var string|array|null
     */
    protected string|array|null $latest_request_message = null;

    /**
     * @var array<ContentMessage>|null
     */
    protected ?array $latest_response_message = null;
    protected ?string $created_at = null;
    protected ?array $raw_message = null;
    protected ?array $usage = null;

    public function __construct(?string $conn = null, ?string $model_name = null) {
        $this->connection = $conn ?? ($this->connection ?? AICompletions::defaultConnection());
        $this->model_id = $model_name ?? ($this->model_id ?? AICompletions::connectionDefaultModelID($this->connection));
    }

    /** Response Controls  */
    public function setId(string|int $id): static
    {
        $this->id = $id;
        return $this->addAttribute("id", $id);
    }

    public function setLatestRequestMessage(string|array $prompt): static
    {
        $this->latest_request_message = $prompt;

        return $this;
    }

    public function setLatestResponseMessage(ContentMessage|array $message, array $raw_response): static
    {
        if(!is_array($message)) $message = [$message];
        $this->latest_response_message = $message;
        $this->raw_message = $raw_response;

        return $this->addAttribute("messages", $message);
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

    public function getId(): string|int|null
    {
        return $this->id;
    }

    public function getLatestRequestMessage(): string|array|null
    {
        return $this->latest_request_message;
    }

    public function getLatestResponseMessage(): ?array
    {
        return $this->latest_response_message;
    }

    public function getCreatedDate(): ?string
    {
        return $this->created_at;
    }

    public function getCurrentConversation(): ?Collection
    {
        return $this->current_conversation ?? new ChatConversation();
    }

    public function addToCurrentConversation(ContentMessage $message): static
    {
        $this->current_conversation = $this->current_conversation->add($message);
        return $this;
    }

    public function setCurrentConversation(ChatConversation $conversation): static
    {
        $this->current_conversation = $conversation;
        return $this;
    }

    public function setSystemInstructions(array $instructions): static
    {
        $this->system_instructions = $instructions;
        return $this->addAttribute("system_instructions", $instructions);
    }

    public function getSystemInstructions(): array
    {
        return $this->system_instructions;
    }

    public function setTools(array $tools): static
    {
        $this->tools = $tools;
        return $this->addAttribute("tools", $tools);
    }

    public function getTools(): array
    {
        return $this->tools;
    }
}
