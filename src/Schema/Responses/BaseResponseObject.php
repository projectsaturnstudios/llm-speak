<?php

namespace LLMSpeak\Schema\Responses;

use LLMSpeak\Schema\LLMResponse;
use Spatie\LaravelData\Data;

abstract class BaseResponseObject extends Data
{
    abstract public function toLLMResponse(): LLMResponse;

    public function __construct(public readonly array $raw_llm_response)
    {

    }
}
