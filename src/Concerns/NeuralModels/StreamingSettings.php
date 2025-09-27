<?php

namespace LLMSpeak\Core\Concerns\NeuralModels;

trait StreamingSettings
{
    protected bool $stream_response = false;

    public function willStreamResponse(): bool
    {
        return $this->stream_response;
    }
}
