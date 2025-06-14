<?php

namespace LLMSpeak\Enums;

enum StatusEnum: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case INCOMPLETE = 'incomplete';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case QUEUED = 'queued';
}
