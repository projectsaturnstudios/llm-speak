<?php

namespace LLMSpeak\Actions\API;

use LLMSpeak\Support\Facades\LLMSpeak;
use Lorisleiva\Actions\Concerns\AsAction;

abstract class AnLLMStreamingInteraction
{
    use AsAction;

    protected string $provider;

    public function __construct()
    {
        LLMSpeak::getApiKey($this->provider);
        LLMSpeak::getBaseUrl($this->provider);
    }


}
