<?php

namespace LLMSpeak\Enums;

enum ModalityEnum: string
{
    case MODALITY_UNSPECIFIED = 'MODALITY_UNSPECIFIED';
    case TEXT = 'TEXT';
    case IMAGE = 'IMAGE';
    case AUDIO = 'AUDIO';
}
