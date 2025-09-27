<?php

namespace LLMSpeak\Core\Enums;

enum PrimitiveType: string
{
    case TOKEN = 'token';
    case VECTOR = 'vector';
    case TEXT = 'text';
    case FUNCTION = 'function';
}
