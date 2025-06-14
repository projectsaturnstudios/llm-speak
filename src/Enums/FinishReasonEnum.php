<?php

namespace LLMSpeak\Enums;

enum FinishReasonEnum: string
{
    case FINISH_REASON_UNSPECIFIED = 'FINISH_REASON_UNSPECIFIED';
    case STOP = 'STOP';
    case MAX_TOKENS = 'MAX_TOKENS';
    case SAFETY = 'SAFETY';
    case RECITATION = 'RECITATION';
    case LANGUAGE = 'LANGUAGE';
    case OTHER = 'OTHER';
    case BLOCKLIST = 'BLOCKLIST';
    case PROHIBITED_CONTENT = 'PROHIBITED_CONTENT';
    case SPII = 'SPII';
    case MALFORMED_FUNCTION_CALL = 'MALFORMED_FUNCTION_CALL';
    case IMAGE_SAFETY = 'IMAGE_SAFETY';
}
