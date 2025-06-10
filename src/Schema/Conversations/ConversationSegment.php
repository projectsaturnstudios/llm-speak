<?php

namespace LLMSpeak\Schema\Conversations;

use Spatie\LaravelData\Data;

abstract class ConversationSegment extends Data
{
    // @todo - support more than a string in the $content
    public function __construct(
        public readonly string $role,
        public readonly string $content)
    {

    }

    public function toProviderArray(string $provider): array
    {
        if($provider == 'gemini') return $this->toParts();
        return $this->toValue();
    }

    public function toValue(): array
    {
        $results = [
            'role' => $this->role,
        ];

        if(is_string($this->content))
        {
            $results['content'] = $this->content;
        }

        return $results;
    }

    public function toParts(): array
    {
        $results = [
            'role' => $this->role,
            'parts' => []
        ];

        if(is_string($this->content))
        {
            $results['parts'][] = ['text' => $this->content];
        }

        return $results;
    }

}
