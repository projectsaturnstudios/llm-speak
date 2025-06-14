<?php

namespace LLMSpeak\Enums;

enum BehaviorEnum: string
{
    case UNSPECIFIED = 'UNSPECIFIED';
    case BLOCKING = 'BLOCKING';
    case NON_BLOCKING = 'NON_BLOCKING';
}
