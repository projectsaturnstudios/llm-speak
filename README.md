# LLM Speak
### A Laravel package to interact with LLMs using Eloquent-like syntax.

## Installation

### 1) Install via Composer

```bash
composer require llm-speak/core
```

### 2) Publish configuration (optional)

```bash
# Publish all LLM Speak configs and installed provider configs
php artisan vendor:publish --tag="llms"

# Publish Tokenization configs and installed provider Tokenization configs
php artisan vendor:publish --tag="llms.tt"

# Publish Embeddings configs and installed provider Embeddings configs
php artisan vendor:publish --tag="llms.ve"

# Publish OneShot Inferencing configs and installed provider Inferencing configs
php artisan vendor:publish --tag="llms.mi"
```

### 3) Install Drivers

Drivers include:
  - OpenAI Compatible — Embeddings, Inferencing, Chat Completions
  - Ollama            — Embeddings, Inferencing, Chat Completions
  - LMStudio          — Embeddings, Inferencing, Chat Completions
  - MistralAI         — Embeddings, Inferencing, Chat Completions
  - Gemini            — Embeddings, Inferencing, Chat Completions
  - xAI               — Tokenization, Chat Completions
  - OpenRouter        — Inferencing, Chat Completions
  - Anthropic         — Chat Completions
  - OpenAI Responses  — Chat Completions
  - HuggingFace       — Chat Completions

## Usage

### Create a Neural Model
1. Create a Neural Model that extends the type of interaction you want (e.g., Tokenization, Embeddings, Inference, Completions).
2. Available Neural Models: `TokenizationModel`, `EmbeddingsModel`, `InferenceModel`, `CompletionsModel`.
3. When creating the model, the default provider and model are read from the model's config.
4. You can override by setting the model's `$connection` and `$model_id` properties.

```php
use LLMSpeak\Core\NeuralModels\CompletionsModel;

class SomeModel extends CompletionsModel
{
    // Optional: override the default provider/model from config
    protected string $connection = 'oaic';
    protected string $model_id = 'gpt-3.5-turbo-instruct';
}
```

```php
use LLMSpeak\Core\NeuralModels\InferenceModel;

class SomeModel extends InferenceModel
{
    // Optional: override the default provider/model from config
    protected string $connection = 'oaic';
    protected string $model_id = 'gpt-3.5-turbo-instruct';
}
```

```php
use LLMSpeak\Core\NeuralModels\EmbeddingsModel;

class SomeModel extends EmbeddingsModel
{
    // Optional: override the default provider/model from config
    protected string $connection = 'oaic';
    protected string $model_id = 'text-embedding-ada-002';
}
```

```php
use LLMSpeak\Core\NeuralModels\TokenizationModel;

class SomeTokenizerModel extends TokenizationModel
{
    // Optional: override the default provider/model from config
    protected string $connection = 'x-ai';
    protected string $model_id = 'grok-4-0709';
}
```

### Chat Completions

Configure your provider in `config/chat-completions.php`:

```php
// Sample OpenAICompatible config in config/text-tokenization.php
'connections' => [
    'oaic' => [
        'driver' => 'open-ai-compatible',
        'url' => env('OAIC_URL', 'https://api.openai.com'),
        'model' => env('OAIC_EMBED_MODEL', 'gpt-5'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
        ],
    ],
    'local' => [
        'driver' => 'ollama',
        'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
        'model' => env('OLLAMA_EMBED_MODEL', 'llama3:latest'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ],
]
```

Use the Completions model to have a model reply to your input.

#### Instance usage
```php
use App\NeuralModels\Embeddings\OllamaEmbeddingsModel;

// Start tokenization query
$model = new OneShotModel();

// Build prompts
$query = $model->where('prompt', 'Why is the sky blue?');
$query = $model->wherePrompt('Why is the sky blue?');
$query = $model->whereIn('prompt', ['Why is the sky blue?', 'What is Rayleigh scattering?']);
$query = $model->wherePrompt('Why is the sky blue?')->wherePrompt('What is Rayleigh scattering?');

// Execute
$modelCollection = $model->get();        // Collection of models (responses)
$tokensModel     = $model->first();      // Single model (latest response)
```

#### Fluent usage
```php
$modelCollection = (new Chatmodel())
    ->where('message', 'Why is the sky blue?')
    ->get();

$model = (new Chatmodel())
    ->where('message', 'Why is the sky blue?')
    ->first();
```

#### Manual builder usage
```php
$query = (new Chatmodel())->newQuery();
$query = $query->where('message', 'Why is the sky blue?');
$modelCollection = $query->get();
```

#### Advanced usage

These additional parameters can be set on the Completions model like so.

```php
$model = (new Chatmodel())
    ->where('message', 'Why is the sky blue?')
    ->whereTemperature(0.7)  // Set temperature that controls randomness
    ->first();

// Max tokens to use in response
$model->whereMaxTokens($max_tokens); 
// Penalize new tokens based on their existing frequency in the text so far
$model->whereFrequencyPenalty($frequency_penalty); 
// Penalize new tokens based on whether they appear in the text so far
$model->wherePresencePenalty($presence_penalty);
// Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
$model->whereSeed($seed);
// Number of completions to generate for each prompt
$model->whereN($n);
// Modify the behavior of the model with system instructions
$model->whereSystemInstructions($instructions); 
// Add tool functions for the model to request.
$model->whereTools($user_id);

```
NOTE: Not all providers support all these parameters.

### OneShot Inferencing

Configure your provider in `config/inferencing.php`:

```php
// Sample OpenAICompatible config in config/text-tokenization.php
'connections' => [
    'oaic' => [
        'driver' => 'open-ai-compatible',
        'url' => env('OAIC_URL', 'https://api.openai.com'),
        'model' => env('OAIC_EMBED_MODEL', 'gpt-3.5-turbo-instruct'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
        ],
    ],
    'local' => [
        'driver' => 'ollama',
        'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
        'model' => env('OLLAMA_EMBED_MODEL', 'llama3:latest'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ],
]
```

Use the Inference model to have a model response to your input.

#### Instance usage
```php
use App\NeuralModels\Embeddings\OllamaEmbeddingsModel;

// Start tokenization query
$model = new OneShotModel();

// Build prompts
$query = $model->where('prompt', 'Why is the sky blue?');
$query = $model->wherePrompt('Why is the sky blue?');
$query = $model->whereIn('prompt', ['Why is the sky blue?', 'What is Rayleigh scattering?']);
$query = $model->wherePrompt('Why is the sky blue?')->wherePrompt('What is Rayleigh scattering?');

// Execute
$modelCollection = $model->get();        // Collection of models (responses)
$tokensModel     = $model->first();      // Single model (latest response)
```

#### Fluent usage
```php
$modelCollection = (new OneShotModel())
    ->where('prompt', 'Why is the sky blue?')
    ->get();

$model = (new OneShotModel())
    ->where('prompt', 'Why is the sky blue?')
    ->first();
```

#### Manual builder usage
```php
$query = (new OneShotModel())->newQuery();
$query = $query->where('prompt', 'Why is the sky blue?');
$modelCollection = $query->get();
```

#### Advanced usage

These additional parameters can be set on the Embeddings model like so.

```php
$model = (new OneShotModel())
    ->where('prompt', 'Why is the sky blue?')
    ->whereTemperature(0.7)  // Set temperature that controls randomness
    ->first();

// Max tokens to use in response
$model->whereMaxTokens($max_tokens); 
// Penalize new tokens based on their existing frequency in the text so far
$model->whereFrequencyPenalty($frequency_penalty); 
// Penalize new tokens based on whether they appear in the text so far
$model->wherePresencePenalty($presence_penalty);
// Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.
$model->whereSeed($seed);
// Number of completions to generate for each prompt
$model->whereN($n);

```
NOTE: Not all providers support all these parameters.

### Vector Embeddings

Configure your provider in `config/vector-embeddings.php`:

```php
// Sample OpenAICompatible config in config/text-tokenization.php
'connections' => [
    'oaic' => [
        'driver' => 'open-ai-compatible',
        'url' => env('OAIC_URL', 'https://api.openai.com'),
        'model' => env('OAIC_EMBED_MODEL', 'text-embedding-ada-002'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('OAIC_API_KEY', ''),
        ],
    ],
    'local' => [
        'driver' => 'ollama',
        'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
        'model' => env('OLLAMA_EMBED_MODEL', 'llama3:latest'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ],
]
```

Use the Embeddings model to convert text to vectors.

#### Instance usage
```php
use App\NeuralModels\Embeddings\OllamaEmbeddingsModel;

// Start tokenization query
$model = new OllamaEmbeddingsModel();

// Build prompts
$query = $model->where('text', 'Why is the sky blue?');
$query = $model->whereInput('Why is the sky blue?');
$query = $model->whereIn('text', ['Why is the sky blue?', 'What is Rayleigh scattering?']);
$query = $model->whereInput('Why is the sky blue?')->whereInput('What is Rayleigh scattering?');

// Execute
$modelCollection = $model->get();        // Collection of models (responses)
$tokensModel     = $model->first();      // Single model (latest response)
```

#### Fluent usage
```php
$modelCollection = (new OllamaEmbeddingsModel())
    ->where('input', 'Why is the sky blue?')
    ->get();

$model = (new OllamaEmbeddingsModel())
    ->where('input', 'Why is the sky blue?')
    ->first();
```

#### Manual builder usage
```php
$query = (new OllamaEmbeddingsModel())->newQuery();
$query = $query->where('input', 'Why is the sky blue?');
$modelCollection = $query->get();
```

#### Advanced usage

These additional parameters can be set on the Embeddings model like so.

```php
$model = (new OAICEmbeddingsModel())
    ->where('input', 'Why is the sky blue?')
    ->whereDimensions(1536)  // Set dimension if needed
    ->first();

$model->whereEncodingFormat($encoding_format);
$model->whereUser($user_id);
$model->whereTitle($title);
$model->whereTaskType($task_type);
```
NOTE: Not all providers support all these parameters.

### Tokenization

Configure your provider in `config/text-tokenization.php`:

```php
// Sample xAI config in config/text-tokenization.php
'connections' => [
    'x-ai' => [
        'driver' => 'x-ai',
        'url' => env('XAI_URL', 'https://api.x.ai'),
        'model' => env('XAI_TOKEN_MODEL', 'grok-4-0709'),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('XAI_API_KEY', ''),
        ],
    ],
]
```

Use the Tokenization model to convert text to tokens.

#### Instance usage
```php
use App\NeuralModels\Tokenizations\GrokTokensModel;

// Start tokenization query
$model = new GrokTokensModel();

// Build prompts
$query = $model->where('text', 'Why is the sky blue?');
$query = $model->whereText('Why is the sky blue?');
$query = $model->whereIn('text', ['Why is the sky blue?', 'What is Rayleigh scattering?']);
$query = $model->whereText('Why is the sky blue?')->whereText('What is Rayleigh scattering?');

// Execute
$modelCollection = $model->get();        // Collection of models (responses)
$tokensModel     = $model->first();      // Single model (latest response)
```

#### Fluent usage
```php
$modelCollection = (new GrokTokensModel())
    ->where('text', 'Why is the sky blue?')
    ->get();

$model = (new GrokTokensModel())
    ->where('text', 'Why is the sky blue?')
    ->first();
```

#### Manual builder usage
```php
$query = (new GrokTokensModel())->newQuery();
$query = $query->where('text', 'Why is the sky blue?');
$modelCollection = $query->get();
```
