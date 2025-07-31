<?php

namespace LLMSpeak\Core\Enums;

enum ChatRole: string
{
    case SYSTEM = 'system';
    case USER = 'user';
    case MODEL = 'model';
    case TOOL_CALL = 'tool';
    case TOOL_RESULT = 'tool-result';
}
