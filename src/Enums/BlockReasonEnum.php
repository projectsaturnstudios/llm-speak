<?php

namespace LLMSpeak\Enums;

enum BlockReasonEnum: string
{
    case BLOCK_REASON_UNSPECIFIED = 'BLOCK_REASON_UNSPECIFIED';
    case SAFETY = 'SAFETY';
    case OTHER = 'OTHER';
    case BLOCKLIST = 'BLOCKLIST';
    case PROHIBITED_CONTENT = 'PROHIBITED_CONTENT';
    case IMAGE_SAFETY = 'IMAGE_SAFETY';
}
