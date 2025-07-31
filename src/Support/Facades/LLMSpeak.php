<?php

namespace LLMSpeak\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use LLMSpeak\Core\Contracts\LLMCommuniqueContract;
use LLMSpeak\Core\Support\Requests\LLMSpeakChatRequest;
use LLMSpeak\Core\Support\Requests\LLMSpeakEmbeddingsRequest;
use LLMSpeak\Core\Support\Requests\LLMSpeakRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakChatResponse;
use LLMSpeak\Core\Support\Responses\LLMSpeakEmbeddingsResponse;
use LLMSpeak\Core\Support\Responses\LLMSpeakResponse;
use LLMSpeak\Core\UniversalCommunicator;

/**
 * @method static mixed translateTo(string $driver, LLMSpeakChatRequest $request)
 * @method static LLMCommuniqueContract|false translateFrom(string $driver, mixed $communique, string $type, string $direction)
 * @method static LLMSpeakChatResponse|false chatWith(string $driver, LLMSpeakChatRequest $request)
 * @method static LLMSpeakEmbeddingsResponse|false embeddingsFrom(string $driver, LLMSpeakEmbeddingsRequest $request)
 * @method static LLMSpeakResponse|false send(LLMSpeakRequest $request, string $driver)
 *
 * @see UniversalCommunicator
 */
class LLMSpeak extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'universal-communicator';
    }
}
