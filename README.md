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


require with composer:
```shell
  composer require projectsaturnstudios/llm-speak 
```

require drivers also with composer

```shell
  composer require llm-speak/anthropic-claude
  composer require llm-speak/google-gemini
  composer require llm-speak/ollama
  composer require llm-speak/openai-chatgpt
```