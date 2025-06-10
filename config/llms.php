<?php

return [
    'services' => [
        'gemini' => [
            'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/'),
            'api_key' => ''
        ],
        'anthropic' => [
            'url' => env('CLAUDE_URL', 'https://api.anthropic.com/v1/'),
            'api_key' => '',
            'extra_headers' => [
                'anthropic-version' => '2023-06-01',
                'anthropic-beta' => null,
            ]
        ],
        'open-ai' => [
            'url' => env('OPEN_AI_URL', 'https://api.openai.com/v1'),
            'api_key' => ''
        ],
        'open-router' => [
            'url' => env('OPEN_ROUTER_URL', 'https://openrouter.ai/api/v1/'),
            'api_key' => ''
        ],
        'add-ons' => []
    ]
];
