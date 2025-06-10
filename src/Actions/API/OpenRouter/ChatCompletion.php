<?php

namespace LLMSpeak\Actions\API\OpenRouter;

use Illuminate\Support\Facades\Http;
use LLMSpeak\Actions\API\InteractionWithAnLLM;
use LLMSpeak\Support\Facades\LLMs;

class ChatCompletion extends InteractionWithAnLLM
{

    public function __construct()
    {
        parent::__construct(
            LLMs::getApiKey('open-router'),
            LLMs::getBaseUrl('open-router')
        );
    }

    public function handle(
        string $model,
        array $messages,
        ?array $tools = null,
        ?array $models = null,
        ?array $provider = null,
        ?array $reasoning = null,
        ?array $usage = null,
        ?array $transforms = null,
        ?int $max_tokens = null,
        ?float $temperature = null,
        ?int $seed = null,
        ?float $top_p = null,
        ?float $top_k = null,
        ?float $frequency_penalty = null,
        ?float $presence_penalty = null,
        ?float $repetition_penalty = null,
        ?array $logit_bias = null,
        ?int $top_logprobs = null,
        ?float $min_p = null,
        ?float $top_a = null,
        ?string $user = null,
    ): ?array
    {
        $results = null;

        $url = "{$this->base_url}chat/completions";

        $payload = [
            'model' => $model,
            'messages' => $messages,
        ];

        if($tools)
        {
            $payload['tools'] = array_map(function($tool) {
                return [
                    'type' => 'function',
                    'function' => [
                        'name' => $tool['name'],
                        'description' => $tool['description'],
                        'parameters' => $tool['inputSchema'],
                    ],

                ];
            }, $tools);
        }
        if($models) $payload['models'] = $model;
        if($provider) $payload['provider'] = $provider;
        if($reasoning) $payload['reasoning'] = $reasoning;
        if($usage) $payload['usage'] = $usage;
        if($transforms) $payload['transforms'] = $transforms;
        if($seed) $payload['seed'] = $seed;
        if($top_p) $payload['top_p'] = $top_p;
        if($top_k) $payload['top_k'] = $top_k;
        if($max_tokens) $payload['max_tokens'] = $max_tokens;
        if($temperature) $payload['temperature'] = $temperature;
        if($seed) $payload['seed'] = $seed;
        if($top_logprobs) $payload['top_logprobs'] = $top_logprobs;
        if($min_p) $payload['min_p'] = $min_p;
        if($top_a) $payload['top_a'] = $top_a;
        if($user) $payload['user'] = $user;
        if($frequency_penalty) $payload['frequency_penalty'] = $frequency_penalty;
        if($presence_penalty) $payload['presence_penalty'] = $presence_penalty;
        if($repetition_penalty) $payload['repetition_penalty'] = $repetition_penalty;
        if($logit_bias) $payload['logit_bias'] = $logit_bias;

        $response = Http::withHeaders([
            'Content-Type'=> 'application/json',
            "Authorization" => "Bearer {$this->api_key}"
        ])->post($url, $payload);

        if($response->successful()) $results = $response->json();
        else dd($response->status(), ['resp'=> $response->json(), 'payload'=> $payload ]);

        return $results;
    }
}
