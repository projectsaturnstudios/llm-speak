<?php

namespace LLMSpeak\Actions\API\Claude;

use LLMSpeak\Support\Facades\LLMs;
use Illuminate\Support\Facades\Http;
use LLMSpeak\Actions\API\InteractionWithAnLLM;

class Messages extends InteractionWithAnLLM
{
    protected string $anthropic_version;
    protected string|false $experimental_tools = false;

    public function __construct()
    {
        parent::__construct(
            LLMs::getApiKey('anthropic'),
            LLMs::getBaseUrl('anthropic')
        );

        $this->anthropic_version = LLMs::driver('anthropic')->getAnthropicVersion();
        $this->experimental_tools = LLMs::driver('anthropic')->getAnthropicBeta();
    }

    public function handle(
        string $model,
        int $max_tokens,
        array $contents,
        ?array $system_instruction = null,
        ?array $tools = null,

        ?string $container = null,
        ?array $mcp_servers = null,
        ?array $meta_data = null,
        ?string $service_tier = null,
        ?array $stop_sequences = null,
        ?float $temperature = null,
        ?float $top_k = null,
        ?float $top_p = null,
        ?array $thinking = null,
        ?array $tool_choice = null,

    ): ?array
    {
        $results = null;

        $url = "{$this->base_url}messages";
        $headers = [
            'content-type' => 'application/json',
            'anthropic-version' => $this->anthropic_version,
            'x-api-key' => $this->api_key,
        ];
        if($this->experimental_tools){
            //$headers['anthropic-beta'] = $this->experimental_tools;
        }
        $payload = [
            'model' => $model,
            'max_tokens' => $max_tokens,
            'messages' => $contents,
        ];
        if($system_instruction)
        {
            $payload['system'] = $system_instruction;
        }
        if($tools)
        {
            $payload['tools'] = array_map(function($tool) {
                if(array_key_exists('inputSchema', $tool))
                {
                    $tool['input_schema'] = $tool['inputSchema'];
                    unset($tool['inputSchema']);
                }

                return $tool;
            }, $tools);
        }
        if($container)
        {
            $payload['container'] = $container;
        }
        if($mcp_servers)
        {
            $payload['mcp_servers'] = $mcp_servers;
        }
        if($meta_data)
        {
            $payload['meta_data'] = $meta_data;
        }
        if($service_tier)
        {
            $payload['service_tier'] = $service_tier;
        }
        if($stop_sequences)
        {
            $payload['stop_sequences'] = $stop_sequences;
        }
        if($temperature)
        {
            $payload['temperature'] = $temperature;
        }
        if($top_k)
        {
            $payload['top_k'] = $top_k;
        }
        if($top_p)
        {
            $payload['top_p'] = $top_p;
        }
        if($thinking)
        {
            $payload['thinking'] = $thinking;
        }
        if($tool_choice)
        {
            $payload['tool_choice'] = $tool_choice;
        }

        $response = Http::withHeaders($headers)
            ->post($url, $payload);
        if($response->successful()) $results = $response->json();
        else dd($response->status(), ['resp'=> $response->json(), 'payload'=> $payload ]);

        return $results;
    }
}
