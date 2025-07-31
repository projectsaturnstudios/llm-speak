<?php

namespace LLMSpeak\Core\Support\Schema\Tools;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ToolKit extends Data
{
    protected Collection $tools;

    public function __construct(
        array $starting_tools = [],
    ) {
        $this->tools = collect($starting_tools);
    }

    public function addTool(ToolDefinition $tool): static
    {
        $this->tools->push($tool);

        return $this;
    }

    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function toArray(): array
    {
        return $this->tools->toArray();
    }
}
