<?php

namespace LLMSpeak\Actions\API;

use Lorisleiva\Actions\Concerns\AsAction;

abstract class InteractionWithAnLLM
{
    use AsAction;

    public function __construct(
        protected string $api_key,
        protected string $base_url
    )
    {

    }
}
