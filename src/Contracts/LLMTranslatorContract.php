<?php

namespace LLMSpeak\Core\Contracts;

use LLMSpeak\Core\Support\Requests\LLMSpeakChatRequest;
use LLMSpeak\Core\Support\Responses\LLMSpeakChatResponse;

interface LLMTranslatorContract
{
    /**
     * Converts a request to the driver's format.
     *
     * @param mixed $communique The request to convert.
     * @return mixed
     */
    public function convertRequest(LLMSpeakChatRequest $communique): mixed;

    /**
     * Converts a response to the driver's format.
     *
     * @param mixed $communique The response to convert.
     * @return mixed
     */
    public function convertResponse(LLMSpeakChatResponse $communique): mixed;

    /**
     * Translates a request to the driver's format.
     *
     * @param mixed $communique The request to translate.
     * @return mixed
     */
    public function translateRequest(mixed $communique): LLMSpeakChatRequest;

    /**
     * Translates a response to the driver's format.
     *
     * @param mixed $communique The response to translate.
     * @return mixed
     */
    public function translateResponse(mixed $communique): LLMSpeakChatResponse;

}
