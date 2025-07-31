<?php

namespace LLMSpeak\Core\Support\Schema\Conversation;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;
use LLMSpeak\Core\Support\Schema\Conversation\ChatMessage;

class Conversation extends Data
{
    protected Collection $entries;

    /**
     * @param array<ChatMessage|ToolRequest|ToolResult> $starting_entries
     */
    public function __construct(
        array $starting_entries = [],
    ) {
        $this->entries = collect($starting_entries);
    }

    public function addTextMessage(ChatMessage $message): static
    {
        $this->entries->push($message);

        return $this;
    }

    public function addToolRequest(ToolRequest $request): static
    {
        $this->entries->push($request);

        return $this;
    }

    public function addToolResult(ToolResult $result): static
    {
        $this->entries->push($result);

        return $this;
    }

    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function toArray(): array
    {
        return $this->entries->toArray();
    }
}
