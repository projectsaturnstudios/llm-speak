<?php

if(!function_exists('default_embeddings_connection_name'))
{
    function default_embeddings_connection_name(): string
    {
        return config('vector-embeddings.default');
    }
}

if(!function_exists('embeddings_connection_driver_name'))
{
    function embeddings_connection_driver_name(string $connection): string
    {
        return config("vector-embeddings.connections.{$connection}.driver");
    }
}

if(!function_exists('default_embeddings_driver_class'))
{
    function default_embeddings_driver_class(string $driver): string
    {
        return config("vector-embeddings.drivers.{$driver}.driver_class");
    }
}

if(!function_exists('embeddings_connection_headers'))
{
    function embeddings_connection_headers(string $connection): ?array
    {
        return config("vector-embeddings.connections.{$connection}.headers");
    }
}

if(!function_exists('embeddings_connection_url'))
{
    function embeddings_connection_url(string $connection): ?string
    {
        return config("vector-embeddings.connections.{$connection}.url");
    }
}

if(!function_exists('embeddings_connection_default_model'))
{
    function embeddings_connection_default_model(string $connection): string
    {
        return config("vector-embeddings.connections.{$connection}.model");
    }
}
