<?php

namespace LLMSpeak\Enums;

enum StepReasonEnum: string
{
    case END_TURN = 'end_turn';
    case MAX_TOKENS = 'max_tokens';
    case STOP_SEQUENCE = 'stop_sequence';
    case TOOL_USE = 'tool_use';
    case PAUSE_TURN = 'pause_turn';
    case REFUSAL = 'refusal';
}
