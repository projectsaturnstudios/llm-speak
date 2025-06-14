<?php

namespace LLMSpeak\Enums;

enum OpenFinishReasonEnum: string
{
    case STOP = 'stop';
    case LENGTH = 'length';
    case CONTENT_FILTER = 'content_filter';
    case TOOL_CALLS = 'tool_calls';
    case FUNCTION_CALL = 'function_call';
}
