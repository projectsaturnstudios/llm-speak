<?php

namespace LLMSpeak\Enums;

enum RoleEnum: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case TOOL = 'tool';
    case DEVELOPER = 'developer';
    case SYSTEM = 'system';
    case MODEL = 'model';
}
