<?php

namespace LLMSpeak\Enums;

enum SchedulingEnum: string
{
    case SCHEDULING_UNSPECIFIED = 'SCHEDULING_UNSPECIFIED';
    case SILENT = 'SILENT';
    case WHEN_IDLE = 'WHEN_IDLE';
    case INTERRUPT  = 'INTERRUPT';
}
