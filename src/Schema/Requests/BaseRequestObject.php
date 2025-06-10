<?php

namespace LLMSpeak\Schema\Requests;


use Spatie\LaravelData\Data;
use LLMSpeak\Schema\LLMResponse;
use LLMSpeak\Support\Facades\LLMs;
use LLMSpeak\Schema\Conversations\UserTalks;
use LLMSpeak\Schema\Conversations\AssistantSpeaks;
use LLMSpeak\Schema\Conversations\ConversationSegment;

abstract class BaseRequestObject extends Data
{
    protected string $provider;
    public array $system_prompt = [];
    public array $conversation = [];
    public array $tools = [];

    public ?int $max_tokens = null;

    abstract public function toParams(): array;
    abstract public function prepareInstructions(array $instructions): static;

    public function __construct(public readonly string $model)
    {

    }

    public function addTextToConversation(string $role, string $message): static
    {
        $this->conversation[] = ($role == 'user')
            ? (new UserTalks($message))
            : (new AssistantSpeaks($message))
        ;

        return $this;
    }

    public function addArrayToConversation(string $role, array $message): static
    {
        $this->conversation[] = [
            'role' => $role,
            'content' => [$message]
        ];

        return $this;
    }

    public function addWholeObjectToConversation(string $role, array $message): static
    {
        $this->conversation[] = array_merge(['role' => $role], $message);
        return $this;
    }

    public function setMaxTokens(int $max_tokens): static
    {
        $this->max_tokens = $max_tokens;
        return $this;
    }

    public function addTools(array $tool_definition): static
    {
        $this->tools[] = $tool_definition;
        return $this;
    }

    protected function transformConvo(): array
    {
        return array_map(function(ConversationSegment|array $segment){
            return !is_array($segment)
            ? $segment->toValue()
            : $segment;
        }, $this->conversation);
    }

    public function send(): LLMResponse|false
    {
        return LLMs::driver($this->provider)->generate(...$this->toParams());
    }

    public function getProvider(): string
    {
        return $this->provider;
    }
}
