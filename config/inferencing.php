<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default One-Shot Inferencing Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the inference connections below you wish
    | to use as your default connection for inference inferencing. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you perform inferencing using InferenceModels.
    |
    */
    'default' => env('MI_CONNECTION', 'oaic'),

    /*
    |--------------------------------------------------------------------------
    | Tokenization Connections
    |--------------------------------------------------------------------------
    |
    | Below are all the tokenization connections defined for your application.
    | An example configuration is provided for each tokenization provider which
    | is supported by LLMSpeak. You're free to add / remove connections.
    |
    */
    'connections' => [
        'oaic' => [
            'driver' => 'open-ai-compatible',
            'url' => env('OAIC_URL', 'https://api.openai.com'),
            'model' => env('OAIC_INFERENCE_MODEL', 'gpt-3.5-turbo-instruct'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
            ],
        ],
        'ollama' => [
            'driver' => 'ollama',
            'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
            'model' => env('OLLAMA_INFERENCE_MODEL', 'gemma3:4b'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ],
        'lm-studio' => [
            'driver' => 'lm-studio',
            'url' => env('LMS_URL', 'http://localhost:1234/api'),
            'model' => env('LMS_INFERENCE_MODEL', 'openai-gpt-oss-20b-abliterated-uncensored-neo-imatrix'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ],
        'mistral-ai' => [
            'driver' => 'mistral-ai',
            'url' => env('MISTRAL_URL', 'https://api.mistral.ai'),
            'model' => env('MISTRAL_INFERENCE_MODEL', 'codestral-2405'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY', ''),
            ],
        ],

        'open-router' => [
            'driver' => 'open-router',
            'url' => env('OPENROUTER_URL', 'https://openrouter.ai/api'),
            'model' => env('OPENROUTER_INFERENCE_MODEL', 'nvidia/nemotron-nano-9b-v2:free'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY', ''),
            ],
        ],
        'gemini' => [
            'driver' => 'gemini',
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models/%model%'),
            'model' => env('GEMINI_INFERENCE_MODEL', 'gemini-2.0-flash'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ],
        ],

    ]
];
