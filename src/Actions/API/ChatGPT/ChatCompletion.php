<?php

namespace LLMSpeak\Actions\API\ChatGPT;


use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use LLMSpeak\Actions\API\InteractionWithAnLLM;
use LLMSpeak\Support\Facades\LLMs;

class ChatCompletion extends InteractionWithAnLLM
{

    public function __construct()
    {
        parent::__construct(
            LLMs::getApiKey('open-ai'),
            LLMs::getBaseUrl('open-ai'),
        );
    }

    /**
     * @param string $model
     * @param array $messages
     * @param array|null $modalities
     * @param array|null $audio
     * @param float|null $frequency_penalty
     * @param array|null $logit_bias
     * @param bool|null $logprobs
     * @param int|null $max_completion_tokens
     * @param array|null $meta_data
     * @param int|null $n
     * @param bool|null $parallel_tool_calls
     * @param array|null $prediction
     * @param float|null $presence_penalty
     * @param string|null $reasoning_effort
     * @param array|null $response_format
     * @param int|null $seed
     * @param string|null $service_tier
     * @param array|string|null $stop
     * @param bool|null $store
     * @param float|null $temperature
     * @param string|null $tool_choice
     * @param array|null $tools
     * @param int|null $top_logprobs
     * @param float|null $top_p
     * @param string|null $user
     * @param array|null $web_search_options
     * @return array|null
     * @throws ConnectionException
     */
    public function handle(
        string $model,
        array $messages,
        ?array $tools = null,
        ?string $tool_choice = null,
        ?array $modalities = null,
        ?array $audio = null, // used with modalities
        ?float $frequency_penalty = null,
        ?array $logit_bias = null,
        ?bool $logprobs = null,
        ?int $max_completion_tokens = null,
        ?array $meta_data = null,
        ?int $n = null,
        ?bool $parallel_tool_calls = null,
        ?array $prediction = null,
        ?float $presence_penalty = null,
        ?string $reasoning_effort = null,
        ?array $response_format = null,
        ?int $seed = null,
        ?string $service_tier = null,
        array|null|string $stop = null,
        ?bool $store = null,
        ?float $temperature = null, // or use top_p or nothing
        ?int $top_logprobs = null,
        ?float $top_p = null, // or use temperature or nothing
        ?string $user = null,
        ?array $web_search_options = null,
    ): ?array
    {
        $results = null;

        $url = "{$this->base_url}chat/completions";

        $payload = [
            'model' => $model,
            'messages' => $messages,
        ];
        if($modalities)
        {
            $payload['modalities'] = $modalities;

            if($audio) $payload['audio'] = $audio;
        }

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

            if($tool_choice) $payload['tool_choice'] = $tool_choice;
            else $payload['tool_choice'] = 'auto';
        };
        if($frequency_penalty) $payload['frequency_penalty'] = $frequency_penalty;
        if($logit_bias) $payload['logit_bias'] = $logit_bias;
        if($logprobs) $payload['logprobs'] = $logprobs;
        if($max_completion_tokens) $payload['max_completion_tokens'] = $max_completion_tokens;
        if($meta_data) $payload['meta_data'] = $meta_data;
        if($n) $payload['n'] = $n;
        if($parallel_tool_calls) $payload['parallel_tool_calls'] = $parallel_tool_calls;
        if($prediction) $payload['prediction'] = $prediction;
        if($presence_penalty) $payload['presence_penalty'] = $presence_penalty;
        if($reasoning_effort) $payload['reasoning_effort'] = $reasoning_effort;
        if($response_format) $payload['response_format'] = $response_format;
        if($seed) $payload['seed'] = $seed;
        if($service_tier) $payload['service_tier'] = $service_tier;
        if($stop) $payload['stop'] = $stop;
        if($store) $payload['store'] = $store;
        if($temperature) $payload['temperature'] = $temperature;
        if($top_logprobs) $payload['top_logprobs'] = $top_logprobs;
        if($top_p) $payload['top_p'] = $top_p;
        if($user) $payload['user'] = $user;
        if($web_search_options) $payload['web_search_options'] = $web_search_options;

        $response = Http::withHeaders([
            'Content-Type'=> 'application/json',
            "Authorization" => "Bearer {$this->api_key}"
        ])->post($url, $payload);
        if($response->successful()) $results = $response->json();
        else dd($response, 'wtf');

        return $results;
    }
}
