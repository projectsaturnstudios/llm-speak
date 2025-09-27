<?php

namespace LLMSpeak\Core\Enums;

enum ConversationRole: string
{
    case USER = 'user';
    case TOOL = 'tool';
    case MODEL = 'model';
    case SYSTEM = 'system';
    case ASSISTANT = 'assistant';
    case DEVELOPER = 'developer';
    case TOOL_CALL = 'tool_call';
    case TOOL_RESULT = 'tool_result';
}
