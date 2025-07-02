<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\HuggingFace\Support\Facades\HuggingFace;

class HuggingFaceLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(HuggingFace::class))
        {
            throw new \Exception("HuggingFace LLM Service is not available. Please install the Hugging Face package.");
        }
    }
    public function text()
    {

    }
}
