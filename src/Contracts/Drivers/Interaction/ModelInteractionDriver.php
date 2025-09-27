<?php

namespace LLMSpeak\Core\Contracts\Drivers\Interaction;

interface ModelInteractionDriver
{
    function endpointUri(): ?string;
    function generateEndpointUrl(string $base_url): string;
}
