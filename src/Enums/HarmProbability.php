<?php

namespace LLMSpeak\Enums;

enum HarmProbability: string
{
    case HARM_PROBABILITY_UNSPECIFIED = 'HARM_PROBABILITY_UNSPECIFIED';
    case NEGLIGIBLE = 'NEGLIGIBLE';
    case LOW = 'LOW';
    case HIGH = 'HIGH';
    case MEDIUM  = 'MEDIUM';
}
