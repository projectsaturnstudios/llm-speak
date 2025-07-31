<?php

namespace LLMSpeak\Core\Contracts;

use LLMSpeak\Core\Support\Requests\LLMSpeakEmbeddingsRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakEmbeddingsResponse;

interface LLMEmbeddingsContract
{
    /**
     * Converts a request to the driver's format.
     *
     * @param mixed $communique The request to convert.
     * @return mixed
     */
    public function convertRequest(LLMSpeakEmbeddingsRequest $communique): mixed;

    /**
     * Converts a response to the driver's format.
     *
     * @param mixed $communique The response to convert.
     * @return mixed
     */
    public function convertResponse(LLMSpeakEmbeddingsResponse $communique): mixed;

    /**
     * Translates a request to the driver's format.
     *
     * @param mixed $communique The request to translate.
     * @return mixed
     */
    public function translateRequest(mixed $communique): LLMSpeakEmbeddingsRequest;

    /**
     * Translates a response to the driver's format.
     *
     * @param mixed $communique The response to translate.
     * @return mixed
     */
    public function translateResponse(mixed $communique): LLMSpeakEmbeddingsResponse;
}
