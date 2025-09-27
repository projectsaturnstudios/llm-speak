<?php

namespace LLMSpeak\Core\Actions\Endpoints;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use LLMSpeak\Core\DTO\Api\APIResponse;

class PostEndpoint
{
    /**
     * @param array $headers
     * @param string $url
     */
    public function __construct(public array $headers, public string $url, public string $model) {}

    /**
     * @param array $request
     * @return array
     * @throws ConnectionException
     */
    public function handle(array $request) : APIResponse
    {
        $response = Http::withHeaders($this->headers)->post($this->url, $request);

        return APIResponse::from([
            'status' => $response->status(),
            'headers' => ['model'=> [$this->model], ...$response->headers()],
            'body' => $response->json()
        ]);
    }
}
