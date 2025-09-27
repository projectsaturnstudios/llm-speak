<?php

if(!function_exists('default_inference_connection_name'))
{
    function default_inference_connection_name(): string
    {
        return config('inferencing.default');
    }
}

if(!function_exists('inference_connection_driver_name'))
{
    function inference_connection_driver_name(string $connection): string
    {
        return config("inferencing.connections.{$connection}.driver");
    }
}

if(!function_exists('default_inference_driver_class'))
{
    function default_inference_driver_class(string $driver): string
    {
        return config("inferencing.drivers.{$driver}.driver_class");
    }
}

if(!function_exists('inference_connection_headers'))
{
    function inference_connection_headers(string $connection): ?array
    {
        return config("inferencing.connections.{$connection}.headers");
    }
}

if(!function_exists('inference_connection_url'))
{
    function inference_connection_url(string $connection): ?string
    {
        return config("inferencing.connections.{$connection}.url");
    }
}

if(!function_exists('inference_connection_default_model'))
{
    function inference_connection_default_model(string $connection): string
    {
        return config("inferencing.connections.{$connection}.model");
    }
}
