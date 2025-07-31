<?php

namespace LLMSpeak\Core;

use LLMSpeak\Core\Drivers\LLMEmbeddingsDriver;
use LLMSpeak\Core\Drivers\LLMTranslationDriver;
use LLMSpeak\Core\Contracts\LLMCommuniqueContract;
use LLMSpeak\Core\Managers\LLMChatProviderManager;
use LLMSpeak\Core\Support\Requests\LLMSpeakRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakResponse;
use LLMSpeak\Core\Support\Requests\LLMSpeakChatRequest;
use LLMSpeak\Core\Managers\LLMEmbeddingsProviderManager;
use LLMSpeak\Core\Support\Responses\LLMSpeakChatResponse;
use LLMSpeak\Core\Support\Requests\LLMSpeakEmbeddingsRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakEmbeddingsResponse;

class UniversalCommunicator
{
    public function __construct(
        protected LLMChatProviderManager $chat,
        protected LLMEmbeddingsProviderManager $embeddings
    )
    {

    }

    /**
     * Translates an LLMSpeakRequest or LLMSpeakResponse to the respective object in the supported driver's format.
     * @param string $driver The driver to translate to.
     * @param LLMCommuniqueContract $communique The request or response to translate.
     * @return mixed
     */
    public function translateTo(string $driver, LLMCommuniqueContract $communique): mixed
    {
        $results = false;

        if($communique instanceof LLMSpeakChatRequest) {
            /** @var LLMTranslationDriver $driver */
            $driver = $this->chat->driver($driver);
            $results = $driver->convertRequest($communique);
        }
        elseif($communique instanceof LLMSpeakEmbeddingsRequest) {
            /** @var LLMEmbeddingsDriver $driver */
            $driver = $this->embeddings->driver($driver);
            $results = $driver->convertRequest($communique);
        }
        elseif($communique instanceof LLMSpeakChatResponse) {
            /** @var LLMTranslationDriver $driver */
            $driver = $this->chat->driver($driver);
            $results = $driver->convertResponse($communique);
        }
        elseif($communique instanceof LLMSpeakEmbeddingsResponse) {
            /** @var LLMEmbeddingsDriver $driver */
            $driver = $this->embeddings->driver($driver);
            $results = $driver->convertResponse($communique);
        }

        return $results;
    }

    /**
     * Translates a supported driver's request or response to LLMSpeakRequest or LLMSpeakResponse respectively.
     * @param string $driver The driver to translate from.
     * @param mixed $communique The request or response to translate.
     * @return mixed
     */
    public function translateFrom(string $driver, mixed $communique, string $type, string $direction): LLMCommuniqueContract|false
    {
        $results = false;

        if($type === 'chat')
        {
            $driver =  $this->chat->driver($driver);
            if($direction === 'request')
            {
                $results = $driver->translateRequest($communique);
            }
            elseif($direction === 'response')
            {
                $results = $driver->translateResponse($communique);
            }

        }
        elseif($type === 'embeddings')
        {
            $driver = $this->embeddings->driver($driver);
            if($direction === 'request')
            {
                $results = $driver->translateRequest($communique);
            }
            elseif($direction === 'response')
            {
                $results = $driver->translateResponse($communique);
            }
        }

        return $results;
    }

    /**
     * Sends a chat request to the specified driver and returns the response.
     * @param string $driver
     * @param LLMSpeakChatRequest $request
     * @return LLMSpeakChatResponse|false
     */
    public function chatWith(string $driver, LLMSpeakChatRequest $request): LLMSpeakChatResponse|false
    {
        $results = false;

        // Translate the request to the driver's format
        if($translatedRequest = $this->translateTo($driver, $request))
        {
            $response = $translatedRequest->post();
            $results = $this->translateFrom($driver, $response, 'chat', 'response');
        }

        return $results;
    }

    /**
     * Sends an embeddings request to the specified driver and returns the response.
     * @param string $driver
     * @param LLMSpeakEmbeddingsRequest $request
     * @return LLMSpeakEmbeddingsResponse|false
     */
    public function embeddingsFrom(string $driver, LLMSpeakEmbeddingsRequest $request): LLMSpeakEmbeddingsResponse|false
    {
        $results = false;

        // Translate the request to the driver's format
        if($translatedRequest = $this->translateTo($driver, $request))
        {
            $response = $translatedRequest->post();
            $results = $this->translateFrom($driver, $response, 'embeddings', 'response');
        }

        return $results;
    }

    /**
     * Sends a generic request to the specified driver and returns the response.
     * @param LLMSpeakRequest $request
     * @param string $driver
     * @return LLMSpeakResponse|false
     */
    public function send(LLMSpeakRequest $request, string $driver): LLMSpeakResponse|false
    {
        $results = false;

        if($request instanceof LLMSpeakChatRequest) {
            $results = $this->chatWith($driver, $request);
        }
        elseif($request instanceof LLMSpeakEmbeddingsRequest) {
            $results = $this->embeddingsFrom($driver, $request);
        }

        return $results;
    }


    public static function boot(): void
    {
        LLMChatProviderManager::boot();
        LLMEmbeddingsProviderManager::boot();
        //LLMVoiceProviderManager::boot();
        //LLMDiffusionProviderManager::boot();

        app()->singleton('universal-communicator', function () {
            return  new self(
                app('llm-providers'),
                app('llm-embeddings-providers'),
            );
        });
    }
}
