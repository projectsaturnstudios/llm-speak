<?php

if(!function_exists('default_chat_completions_connection_name'))
{
    function default_chat_completions_connection_name(): string
    {
        return config('chat-completions.default');
    }
}

if(!function_exists('chat_completions_connection_driver_name'))
{
    function chat_completions_connection_driver_name(string $connection): string
    {
        return config("chat-completions.connections.{$connection}.driver");
    }
}

if(!function_exists('default_chat_completions_driver_class'))
{
    function default_chat_completions_driver_class(string $driver): string
    {
        return config("chat-completions.drivers.{$driver}.driver_class");
    }
}

if(!function_exists('chat_completions_connection_headers'))
{
    function chat_completions_connection_headers(string $connection): ?array
    {
        return config("chat-completions.connections.{$connection}.headers");
    }
}

if(!function_exists('chat_completions_connection_url'))
{
    function chat_completions_connection_url(string $connection): ?string
    {
        return config("chat-completions.connections.{$connection}.url");
    }
}

if(!function_exists('chat_completions_connection_default_model'))
{
    function chat_completions_connection_default_model(string $connection): string
    {
        return config("chat-completions.connections.{$connection}.model");
    }
}
