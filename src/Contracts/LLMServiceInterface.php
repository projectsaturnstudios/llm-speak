<?php

namespace LLMSpeak\Contracts;

interface LLMServiceInterface
{
    function getBaseUrl(): string;
    function generate(string $model, array $params);
}
