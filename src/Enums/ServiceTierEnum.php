<?php

namespace LLMSpeak\Enums;

enum ServiceTierEnum: string
{
    case AUTO = 'auto';
    case STANDARD_ONLY = 'standard_only';
    case PRIORITY = 'priority';
    case BATCH = 'batch';
    case STANDARD = 'standard';
    case DEFAULT = 'default';
    case FLEX = 'flex';
}
