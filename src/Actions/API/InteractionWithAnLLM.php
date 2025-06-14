<?php

namespace LLMSpeak\Actions\API;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LLMSpeak\Gemini\SchemaReference\Part;
use LLMSpeak\Support\Facades\LLMSpeak;
use Lorisleiva\Actions\Concerns\AsAction;
use LLMSpeak\Communication\LLMChatRequest;
use LLMSpeak\Communication\LLMChatResponse;
use LLMSpeak\Contracts\InteractionWithLLMContract;
use LLMSpeak\Exceptions\InteractionWithAnLLMException;

abstract class InteractionWithAnLLM implements InteractionWithLLMContract
{
    use AsAction;

    protected string $api_key;
    protected string $base_url;
    protected string $provider;
    protected string $endpoint;
    protected string $expected_request;
    protected string $expected_response;

    abstract protected function formHeaders(): array;
    abstract protected function getRequestUrl(LLMChatRequest $request): string;

    public function __construct()
    {
        $this->api_key = LLMSpeak::getApiKey($this->provider);
        $this->base_url = LLMSpeak::getBaseUrl($this->provider);
        if(!isset($this->provider)) throw InteractionWithAnLLMException::missingDependency('provider');
        if(!isset($this->endpoint)) throw InteractionWithAnLLMException::missingDependency('endpoint');
        if(!isset($this->expected_request)) throw InteractionWithAnLLMException::missingDependency('expected_request');
        if(!isset($this->expected_response)) throw InteractionWithAnLLMException::missingDependency('expected_response');
    }

    public function handle(LLMChatRequest $request): LLMChatResponse
    {
        $this->validateRequest($request);
        $url = $this->getRequestUrl($request);
        $headers = $this->formHeaders();

        $response = $this->fire($headers, $url, $payload = $request->toPayload());
        if($response->successful()) return $this->expected_response::make($response->json());
        else return $this->expected_response::error($payload, $response->json());
    }

    protected function validateRequest(LLMChatRequest $request): void
    {
        if(!($request instanceof $this->expected_request)) throw InteractionWithAnLLMException::IncompatibleLLMChatRequest(static::class);
    }

    public function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function withDefaultHeaders(array $additional_headers): array
    {
        return array_merge($this->defaultHeaders(), $additional_headers);
    }

    /**
     * @param array $headers
     * @param string $url
     * @param array $payload
     * @return Response
     * @throws ConnectionException
     */
    protected function fire(array $headers, string $url, array $payload): Response
    {
        return Http::withHeaders($headers)->post($url, $payload);
    }
}
