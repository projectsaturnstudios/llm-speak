<?php

if(!function_exists('use_ai'))
{
    function use_ai(string $driver): \LLMSpeak\Drivers\LLMServiceDriver
    {
        return \LLMSpeak\Support\Facades\LLMs::driver($driver);
    }
}
