<?php

namespace LLMSpeak\Core\Support\Schema\SystemInstructions;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;
use LLMSpeak\Core\Support\Schema\SystemInstructions\SystemInstruction;

class SystemInstructions extends Data
{
    protected Collection $entries;

    /**
     * @param array<SystemInstruction> $starting_entries
     */
    public function __construct(
        array $starting_entries = [],
    ) {
        $this->entries = collect($starting_entries);
    }

    public function addSystemInstruction(SystemInstruction $message): static
    {
        $this->entries->push($message);

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
