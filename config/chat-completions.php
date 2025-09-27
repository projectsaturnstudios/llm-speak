<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Chat Completions Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the completion connections below you wish
    | to use as your default connection for chat-based inferencing. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you chat with LLMs using CompletionModels.
    |
    */
    'default' => env('CC_CONNECTION', 'oaic'),

    /*
    |--------------------------------------------------------------------------
    | Chat Completion Connections
    |--------------------------------------------------------------------------
    |
    | Below are all the completion connections defined for your application.
    | An example configuration is provided for each completion provider which
    | is supported by LLMSpeak. You're free to add / remove connections.
    |
    */
    'connections' => [
        'claude' => [
            'driver' => 'anthropic',
            'url' => env('ANTHROPIC_URL', 'https://api.anthropic.com'),
            'model' => env('ANTHROPIC_CHAT_MODEL', 'claude-sonnet-4-20250514'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'anthropic-version' => '2023-06-01',
                'x-api-key' => env('ANTHROPIC_API_KEY'),
            ],
        ],
        'gemini' => [
            'driver' => 'gemini',
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models/%model%'),
            'model' => env('GEMINI_CHAT_MODEL', 'gemini-2.0-flash'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ],
        ],
        'lm-studio' => [
            'driver' => 'lm-studio',
            'url' => env('LMS_URL', 'http://localhost:1234/api'),
            'model' => env('LMS_CHAT_MODEL', 'openai-gpt-oss-20b-abliterated-uncensored-neo-imatrix'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ],
        'mistral-ai' => [
            'driver' => 'mistral-ai',
            'url' => env('MISTRAL_URL', 'https://api.mistral.ai'),
            'model' => env('MISTRAL_CHAT_MODEL', 'codestral-2405'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY', ''),
            ],
        ],
        'oaic' => [
            'driver' => 'open-ai-compatible',
            'url' => env('OAIC_URL', 'https://api.openai.com'),
            'model' => env('OAIC_CHAT_MODEL', 'gpt-5'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
            ],
        ],
        'ollama' => [
            'driver' => 'ollama',
            'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
            'model' => env('OLLAMA_CHAT_MODEL', 'gemma3:4b'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
        ],
        'open-ai' => [
            'driver' => 'open-ai',
            'url' => env('OPENAI_URL', 'https://api.openai.com'),
            'model' => env('OPENAI_CHAT_MODEL', 'gpt-5'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY', ''),
            ],
        ],
        'open-router' => [
            'driver' => 'open-router',
            'url' => env('OPENROUTER_URL', 'https://openrouter.ai/api'),
            'model' => env('OPENROUTER_CHAT_MODEL', 'nvidia/nemotron-nano-9b-v2:free'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY', ''),
            ],
        ],
        'x-ai' => [
            'driver' => 'x-ai',
            'url' => env('XAI_URL', 'https://api.x.ai'),
            'model' => env('XAI_TOKEN_MODEL', 'grok-4-0709'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('XAI_API_KEY', ''),
            ],
        ]
    ]
];
