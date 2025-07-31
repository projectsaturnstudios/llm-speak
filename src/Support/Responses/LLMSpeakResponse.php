<?php

namespace LLMSpeak\Core\Support\Responses;

use Spatie\LaravelData\Data;
use LLMSpeak\Core\Contracts\LLMCommuniqueContract;

abstract class LLMSpeakResponse extends Data implements LLMCommuniqueContract
{
    abstract public function type(): string;
}
