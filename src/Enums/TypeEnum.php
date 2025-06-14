<?php

namespace LLMSpeak\Enums;

enum TypeEnum: string
{
    case TYPE_UNSPECIFIED = 'TYPE_UNSPECIFIED';
    case STRING = 'STRING';
    case NUMBER = 'NUMBER';
    case INTEGER = 'INTEGER';
    case BOOLEAN = 'BOOLEAN';
    case ARRAY = 'ARRAY';
    case OBJECT = 'OBJECT';
    case NULL = 'NULL';
    case AUTO = 'auto';
    case ANY = 'any';
    case TOOL = 'tool';
    case FUNCTION = 'function';
    case NONE = 'none';
    case URL = 'url';
    case TEXT = 'text';
    case INPUT_TEXT = 'input_text';
    case MESSAGE = 'message';
    case INPUT_FILE = 'input_file';
    case INPUT_IMAGE = 'input_image';
    case ITEM_REFERENCE = 'item_reference';
    case SERVER_TOOL_USE = 'server_tool_use';
    case ENABLED = 'enabled';
    case DISABLED = 'disabled';
}
