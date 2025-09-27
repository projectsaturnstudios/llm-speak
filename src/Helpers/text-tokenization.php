<?php

if(!function_exists('default_tokenization_connection_name'))
{
    function default_tokenization_connection_name(): string
    {
        return config('text-tokenization.default');
    }
}

if(!function_exists('tokenization_connection_driver_name'))
{
    function tokenization_connection_driver_name(string $connection): string
    {
        return config("text-tokenization.connections.{$connection}.driver");
    }
}

if(!function_exists('default_tokenization_driver_class'))
{
    function default_tokenization_driver_class(string $driver): string
    {
        return config("text-tokenization.drivers.{$driver}.driver_class");
    }
}

if(!function_exists('tokenization_connection_headers'))
{
    function tokenization_connection_headers(string $connection): ?array
    {
        return config("text-tokenization.connections.{$connection}.headers");
    }
}

if(!function_exists('tokenization_connection_url'))
{
    function tokenization_connection_url(string $connection): ?string
    {
        return config("text-tokenization.connections.{$connection}.url");
    }
}

if(!function_exists('tokenization_connection_default_model'))
{
    function tokenization_connection_default_model(string $connection): string
    {
        return config("text-tokenization.connections.{$connection}.model");
    }
}
