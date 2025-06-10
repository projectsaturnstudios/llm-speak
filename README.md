Intended Usage

## I want all of these to do the same thing
```php
use_ai('anthropic')->generate([...$args]);
LLMs::service('anthropic')->model('claude-4-sonnet')->generate([...$args])
SpeakWith::llm('anthropic', 'claude-4-sonnet', [...$args])
```



```php
// gemini, anthropic, open-router, open-ai
LLMs::getBaseUrl($service);
LLMs::getApiKey($service);
```

Steps
1. Build a BaseRequestObject like AnthropicRequest like a Factory
2. Then send it. Get a Response.

The more complicated I want to use it, the more the comprehensive the BaseRequest Objects 
and the LLMResponses need to be.

And that's why there are response parsers in the schema!  


require with composer:
```shell
  composer require progjectsaturnstudios/llm-speak 
```
