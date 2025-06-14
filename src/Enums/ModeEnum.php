<?php

namespace LLMSpeak\Enums;

enum ModeEnum: string
{
    case MODE_UNSPECIFIED = 'MODE_UNSPECIFIED';
    case AUTO = 'AUTO';
    case ANY = 'ANY';
    case NONE = 'NONE';
    case VALIDATED = 'VALIDATED';
}
