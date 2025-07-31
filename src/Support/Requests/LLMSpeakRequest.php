<?php

namespace LLMSpeak\Core\Support\Requests;

use LLMSpeak\Core\Contracts\LLMCommuniqueContract;
use Spatie\LaravelData\Data;

abstract class LLMSpeakRequest extends Data implements LLMCommuniqueContract
{
    abstract public function type(): string;

    public function __construct(
        public readonly string $model
    )
    {

    }
}
