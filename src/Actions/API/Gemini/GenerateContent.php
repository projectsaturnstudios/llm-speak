<?php

namespace LLMSpeak\Actions\API\Gemini;

use LLMSpeak\Support\Facades\LLMs;
use Illuminate\Support\Facades\Http;
use LLMSpeak\Actions\API\InteractionWithAnLLM;
use Illuminate\Http\Client\ConnectionException;

class GenerateContent extends InteractionWithAnLLM
{
    public function __construct()
    {
        parent::__construct(
            LLMs::getApiKey('gemini'),
            LLMs::getBaseUrl('gemini'),

        );
    }

    /**
     * @param string $model
     * @param array $contents
     * @param array|null $system_instruction
     * @param array|null $generationConfig
     * @return ?array
     * @throws ConnectionException
     */
    public function handle(
        string $model,
        array $contents,
        ?array $system_instruction = null,
        ?array $tools = null,
        ?array $generationConfig = null,
    ): ?array
    {
        $results = null;

        $url = "{$this->base_url}models/{$model}:generateContent?key={$this->api_key}";

        $payload = [
            'contents' => $contents,
        ];
        if($system_instruction)
        {
            $payload['system_instruction'] = [
                'parts'=> array_map(function($item){
                    return ['text' => $item];
                }, $system_instruction)
            ];
        }

        if($tools)
        {
            $payload['tools'] = [
                'functionDeclarations' => array_map(function($tool) {
                    return [
                        'name' => $tool['name'],
                        'description' => $tool['description'],
                        'parameters' => $tool['inputSchema'],
                    ];
                }, $tools),
            ];
        }

        if($generationConfig)
        {
            $payload['generationConfig'] = $generationConfig;
        }

        $response = Http::
            withHeader('Content-Type', 'application/json')
            ->post($url, $payload);
        if($response->successful()) $results = $response->json();
        else dd($payload, $response->json());

        return $results;
    }
}
