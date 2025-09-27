<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Tokenization Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the tokenization connections below you wish
    | to use as your default connection for tokenization inferencing. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute prompts on TokenizationModels.
    |
    */
    'default' => env('TT_CONNECTION', 'x-ai'),

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
