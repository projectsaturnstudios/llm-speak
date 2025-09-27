<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Embeddings Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the embeddings connections below you wish
    | to use as your default connection for embeddings conversion. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you vectorize prompts on EmbeddingsModels.
    |
    */
    'default' => env('VE_CONNECTION', 'oaic'),

    /*
    |--------------------------------------------------------------------------
    | Embeddings Connections
    |--------------------------------------------------------------------------
    |
    | Below are all the embeddings connections defined for your application.
    | An example configuration is provided for each embeddings provider which
    | is supported by LLMSpeak. You're free to add / remove connections.
    |
    */
    'connections' => [
        'gemini' => [
            'driver' => 'gemini',
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models/%model%'),
            'model' => env('GEMINI_EMBED_MODEL', 'gemini-embedding-001'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-goog-api-key' => env('GEMINI_API_KEY'),
            ],
        ],
        'lm-studio' => [
            'driver' => 'lm-studio',
            'url' => env('LMS_URL', 'http://localhost:1234/api'),
            'model' => env('LMS_EMBED_MODEL', 'text-embedding-nomic-embed-text-v1.5'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ],
        'mistral-ai' => [
            'driver' => 'mistral-ai',
            'url' => env('MISTRAL_URL', 'https://api.mistral.ai'),
            'model' => env('MISTAL_EMBED_MODEL', 'mistral-embed'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('MISTRAL_API_KEY', ''),
            ],
        ],
        'oaic' => [
            'driver' => 'open-ai-compatible',
            'url' => env('OAIC_URL', 'https://api.openai.com'),
            'model' => env('OAIC_EMBED_MODEL', 'text-embedding-ada-002'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
            ],
        ],
        'ollama' => [
            'driver' => 'ollama',
            'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
            'model' => env('OLLAMA_EMBED_MODEL', 'gemma3:4b'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ],
    ]
];
