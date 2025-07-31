# LLMSpeak Core

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/releases/)
[![Laravel](https://img.shields.io/badge/Laravel-10.x%7C11.x%7C12.x-red.svg)](https://laravel.com)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/projectsaturnstudios/llm-speak.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/llm-speak)
[![Total Downloads](https://img.shields.io/packagist/dt/projectsaturnstudios/llm-speak.svg?style=flat-square)](https://packagist.org/packages/projectsaturnstudios/llm-speak)

**LLMSpeak Core** is the foundation of the LLMSpeak ecosystem - a revolutionary **Universal LLM Provider Translation Layer** that enables seamless provider switching through intelligent translation while preserving authentic API structures. Never be locked into a single LLM provider again.

> **🚀 The Holy Grail of LLM Development**: Write once, run on ANY LLM provider. Switch between Google Gemini, Anthropic Claude, Mistral AI, OpenAI, Ollama, and more with **zero code changes**.

## Table of Contents
- [🎯 Vision](#-vision)
- [⭐ Features](#-features)
- [🏛️ Architecture](#️-architecture)
- [🚀 Get Started](#-get-started)
- [💡 Quick Start](#-quick-start)
- [🔥 Universal Interface](#-universal-interface)
- [🌟 Chat Completions](#-chat-completions)
- [📊 Embeddings](#-embeddings)
- [🔧 Provider Management](#-provider-management)
- [🔄 Translation Layer](#-translation-layer)
- [🧪 Testing Infrastructure](#-testing-infrastructure)
- [⚙️ Configuration](#️-configuration)
- [🎨 Extending the System](#-extending-the-system)
- [📝 Real-World Examples](#-real-world-examples)
- [❓ FAQ](#-faq)
- [🙏 Credits](#-credits)
- [📄 License](#-license)

## 🎯 Vision

**Goal**: Enable seamless translation between LLM providers through a universal standardization layer where each provider can maintain its authentic API structure while LLMSpeak provides intelligent translation and compatibility.

**Key Principle**: All providers are standalone but can snap into LLMSpeak for standardized requests and responses with conversations and embeddings.

### Before LLMSpeak
```php
// Provider lock-in nightmare 😩
$geminiRequest = new GeminiGenerateRequest($model, $contents);
$anthropicRequest = new AnthropicMessageRequest($model, $messages);  
$mistralRequest = new MistralCompletionsRequest($model, $messages);

// Different APIs, different formats, vendor lock-in
```

### After LLMSpeak ✨
```php
// Universal freedom! 🚀
$request = new LLMSpeakChatRequest($model, $conversation);

$geminiResponse = LLMSpeak::chatWith('gemini', $request);     // Google AI
$anthropicResponse = LLMSpeak::chatWith('anthropic', $request); // Anthropic
$mistralResponse = LLMSpeak::chatWith('mistral', $request);   // Mistral AI
$ollamaResponse = LLMSpeak::chatWith('ollama', $request);     // Local models

// Same code, ANY provider! 🤯
```

## ⭐ Features

### 🌍 **Universal Provider Support**
- **9+ Major Providers**: Google Gemini, Anthropic Claude, Mistral AI, OpenAI, Ollama, HuggingFace, OpenRouter, xAI, and more
- **Zero Vendor Lock-in**: Switch providers with a single parameter change
- **Future-Proof**: New providers automatically supported through driver pattern

### 🧠 **Intelligent Translation Layer**
- **Bidirectional Translation**: Universal ↔ Provider format conversion
- **Feature Mapping**: Automatic parameter translation (e.g., `encoding_format` → `outputDtype`)
- **Graceful Degradation**: Unsupported features handled elegantly
- **Provider Optimization**: Leverage unique capabilities while maintaining compatibility

### 💬 **Complete Chat & Embeddings Support**
- **Universal Chat Interface**: System instructions, tool calling, streaming, thinking modes
- **Universal Embeddings Interface**: Batch processing, format optimization, task-specific embeddings
- **Type Safety**: Full PHP 8.2+ type declarations and IDE support
- **Laravel Native**: Deep Laravel integration with automatic service discovery

### 🔧 **Developer Experience**
- **Fluent Interface**: Expressive request builders with method chaining
- **Laravel Data**: Powered by Spatie Laravel Data for robust validation
- **Facade Pattern**: Clean `LLMSpeak::chatWith()` and `LLMSpeak::embeddingsFrom()` APIs
- **Comprehensive Testing**: 90+ console commands for testing all providers and features

### ⚡ **Performance & Reliability**
- **Lazy Loading**: Providers only instantiated when needed
- **Error Handling**: Graceful failure handling and fallback mechanisms
- **Performance Metrics**: Token usage, timing, and quality analytics
- **Resource Management**: Efficient memory usage and connection pooling

## 🏛️ Architecture

LLMSpeak Core implements a sophisticated **Universal Translation Layer** that sits between your application and LLM providers:

```
┌─────────────────────────────────────────────────────────────────┐
│                    Your Laravel Application                      │
└─────────────────────┬───────────────────────────────────────────┘
                      │
              ┌───────▼────────┐
              │  LLMSpeak      │  ◄── Clean Facade API
              │    Facade      │
              └───────┬────────┘
                      │
         ┌────────────▼─────────────┐
         │  UniversalCommunicator   │  ◄── Orchestration Engine
         └────────────┬─────────────┘
                      │
        ┌─────────────▼──────────────┐
        │     Manager System         │  ◄── Dynamic Driver Loading
        │  (Chat + Embeddings)       │
        └─────────────┬──────────────┘
                      │
          ┌───────────▼────────────┐
          │   Translation Layer    │    ◄── Intelligent Conversion
          │  (Provider Drivers)    │
          └───────────┬────────────┘
                      │
    ┌─────────────────▼──────────────────┐
    │           LLM Providers            │
    │  Gemini │ Anthropic │ Mistral │... │
    └────────────────────────────────────┘
```

### Core Components

#### **1. UniversalCommunicator** - The Heart
```php
class UniversalCommunicator {
    // Universal API methods
    public function chatWith(string $driver, LLMSpeakChatRequest $request): LLMSpeakChatResponse|false;
    public function embeddingsFrom(string $driver, LLMSpeakEmbeddingsRequest $request): LLMSpeakEmbeddingsResponse|false;
    
    // Translation methods
    public function translateTo(string $driver, LLMCommuniqueContract $communique): mixed;
    public function translateFrom(string $driver, mixed $communique, string $type, string $direction): LLMCommuniqueContract|false;
}
```

#### **2. Manager System** - Dynamic Provider Loading
```php
// Automatic provider discovery and registration
$providers = config('llms.chat-providers.drivers', []);
foreach($providers as $name => $config) {
    $manager->extend($name, fn() => new $config['llm_speak_driver']($config));
}
```

#### **3. Translation Drivers** - Provider Intelligence
```php
interface LLMEmbeddingsContract {
    // Universal → Provider format
    public function convertRequest(LLMSpeakEmbeddingsRequest $request): mixed;
    public function convertResponse(LLMSpeakEmbeddingsResponse $response): mixed;
    
    // Provider → Universal format  
    public function translateRequest(mixed $request): LLMSpeakEmbeddingsRequest;
    public function translateResponse(mixed $response): LLMSpeakEmbeddingsResponse;
}
```

## 🚀 Get Started

> **Requires [PHP 8.2+](https://php.net/releases/) and Laravel 10.x/11.x/12.x**

### Installation

Install the core package via [Composer](https://getcomposer.org/):

```bash
composer require projectsaturnstudios/llm-speak
```

The package will automatically register itself via Laravel's package discovery.

### Install Provider Packages

Choose the providers you need:

```bash
# Google Gemini
composer require llm-speak/google-gemini

# Anthropic Claude  
composer require llm-speak/anthropic-claude

# Mistral AI
composer require llm-speak/mistral-ai

# Local Ollama models
composer require llm-speak/ollama

# OpenRouter
composer require llm-speak/open-router
```

### Environment Configuration

Configure your provider API keys:

```env
# Google Gemini
GEMINI_API_KEY=your_google_api_key_here

# Anthropic Claude
ANTHROPIC_API_KEY=your_anthropic_api_key_here

# Mistral AI
MISTRAL_API_KEY=your_mistral_api_key_here

# Ollama (local, no key needed)
OLLAMA_BASE_URL=http://localhost:11434/api/
```

### Publish Configuration

```bash
php artisan vendor:publish --tag=llms
```

## 💡 Quick Start

### Universal Chat Example

```php
use LLMSpeak\Core\Support\Facades\LLMSpeak;
use LLMSpeak\Core\Support\Requests\LLMSpeakChatRequest;
use LLMSpeak\Core\Support\Schema\Conversation\{Conversation, ChatMessage};
use LLMSpeak\Core\Enums\ChatRole;

// Build universal conversation
$conversation = new Conversation([
    new ChatMessage(ChatRole::USER, 'Explain quantum computing in simple terms')
]);

// Create universal request
$request = new LLMSpeakChatRequest(
    model: 'claude-3-5-sonnet-20241022',
    messages: $conversation
);

// Use with ANY provider!
$anthropicResponse = LLMSpeak::chatWith('anthropic', $request);
$geminiResponse = LLMSpeak::chatWith('gemini', $request);
$ollamaResponse = LLMSpeak::chatWith('ollama', $request);

echo $anthropicResponse->getTextContent();
```

### Universal Embeddings Example

```php
use LLMSpeak\Core\Support\Requests\LLMSpeakEmbeddingsRequest;

// Create universal embedding request
$request = new LLMSpeakEmbeddingsRequest(
    model: 'text-embedding-004',
    input: 'Generate embeddings for this text',
    encoding_format: 'float',
    dimensions: 512,
    task_type: 'SEMANTIC_SIMILARITY'
);

// Works with any embedding provider!
$geminiResponse = LLMSpeak::embeddingsFrom('gemini', $request);   // Google
$mistralResponse = LLMSpeak::embeddingsFrom('mistral', $request); // Mistral
$ollamaResponse = LLMSpeak::embeddingsFrom('ollama', $request);   // Local

$embeddings = $geminiResponse->getAllEmbeddings();
$dimensions = $geminiResponse->getDimensions();
```

## 🔥 Universal Interface

The LLMSpeak universal interface provides **one API for all providers** while preserving access to provider-specific features.

### Provider-Agnostic Code

```php
class AIService 
{
    public function generateEmbeddings(string $provider, string $text): array
    {
        $request = new LLMSpeakEmbeddingsRequest(
            model: $this->getModelForProvider($provider),
            input: $text,
            encoding_format: 'float',
            dimensions: 1024,
            task_type: null
        );
        
        $response = LLMSpeak::embeddingsFrom($provider, $request);
        
        return $response->getAllEmbeddings();
    }
    
    private function getModelForProvider(string $provider): string
    {
        return match($provider) {
            'gemini' => 'text-embedding-004',
            'mistral' => 'mistral-embed', 
            'ollama' => 'nomic-embed-text',
            default => throw new InvalidArgumentException("Unknown provider: {$provider}")
        };
    }
}

// Usage - same code, any provider!
$service = new AIService();
$embeddings = $service->generateEmbeddings('gemini', 'Hello world');
$embeddings = $service->generateEmbeddings('mistral', 'Hello world');  
$embeddings = $service->generateEmbeddings('ollama', 'Hello world');
```

### Dynamic Provider Selection

```php
class SmartLLMClient
{
    public function chat(string $message, array $preferredProviders = ['anthropic', 'gemini']): string
    {
        $conversation = new Conversation([
            new ChatMessage(ChatRole::USER, $message)
        ]);
        
        $request = new LLMSpeakChatRequest(
            model: 'best-available',
            messages: $conversation
        );
        
        foreach ($preferredProviders as $provider) {
            try {
                $response = LLMSpeak::chatWith($provider, $request);
                if ($response) {
                    return $response->getTextContent();
                }
            } catch (Exception $e) {
                // Try next provider
                continue;
            }
        }
        
        throw new RuntimeException('All providers failed');
    }
}
```

## 🌟 Chat Completions

### Basic Chat

```php
$conversation = new Conversation([
    new ChatMessage(ChatRole::USER, 'Write a haiku about Laravel')
]);

$request = new LLMSpeakChatRequest(
    model: 'claude-3-5-sonnet-20241022',
    messages: $conversation
);

$response = LLMSpeak::chatWith('anthropic', $request);
echo $response->getTextContent();
```

### System Instructions

```php
use LLMSpeak\Core\Support\Schema\SystemInstructions\{SystemInstructions, SystemInstruction};

$systemInstructions = new SystemInstructions([
    new SystemInstruction('You are an expert PHP developer with deep Laravel knowledge.'),
    new SystemInstruction('Always provide working code examples.'),
    new SystemInstruction('Focus on best practices and performance.')
]);

$request = new LLMSpeakChatRequest(
    model: 'claude-3-5-sonnet-20241022',
    messages: $conversation,
    system_instructions: $systemInstructions
);

$response = LLMSpeak::chatWith('anthropic', $request);
```

### Tool Calling

```php
use LLMSpeak\Core\Support\Schema\Tools\{ToolKit, ToolDefinition};

$tools = new ToolKit([
    new ToolDefinition(
        name: 'get_weather',
        description: 'Get weather for a location',
        parameters: [
            'type' => 'object',
            'properties' => [
                'location' => ['type' => 'string', 'description' => 'City name'],
                'unit' => ['type' => 'string', 'enum' => ['celsius', 'fahrenheit']]
            ],
            'required' => ['location']
        ]
    )
]);

$request = new LLMSpeakChatRequest(
    model: 'claude-3-5-sonnet-20241022',
    messages: $conversation,
    tools: $tools,
    tool_choice: 'auto'
);

$response = LLMSpeak::chatWith('anthropic', $request);

if ($response->usedTools()) {
    $toolCalls = $response->getToolCalls();
    // Handle tool calls...
}
```

### Advanced Configuration

```php
$request = new LLMSpeakChatRequest(
    model: 'claude-3-5-sonnet-20241022',
    messages: $conversation,
    system_instructions: $systemInstructions,
    tools: $tools,
    max_tokens: 4000,
    temperature: 0.7,
    tool_choice: 'auto',
    parallel_function_calling: true,
    stream: false
);

$response = LLMSpeak::chatWith('anthropic', $request);

// Access comprehensive response data
echo "Model: " . $response->model;
echo "Tokens: " . $response->getTotalTokens(); 
echo "Finish Reason: " . $response->getFinishReason();
```

## 📊 Embeddings

### Single Text Embedding

```php
$request = new LLMSpeakEmbeddingsRequest(
    model: 'text-embedding-004',
    input: 'Machine learning is transforming how we build software',
    encoding_format: 'float',
    dimensions: 768,
    task_type: 'SEMANTIC_SIMILARITY'
);

$response = LLMSpeak::embeddingsFrom('gemini', $request);

$embedding = $response->getFirstEmbedding();
$dimensions = $response->getDimensions();
$tokenUsage = $response->getTotalTokens();
```

### Batch Processing

```php
$documents = [
    'Artificial intelligence is revolutionizing industries',
    'Machine learning enables automated decision making',
    'Natural language processing understands human text',
    'Computer vision interprets visual information',
    'Deep learning uses neural networks for complex patterns'
];

$request = new LLMSpeakEmbeddingsRequest(
    model: 'mistral-embed',
    input: $documents,
    encoding_format: 'float', 
    dimensions: 1024,
    task_type: null
);

$response = LLMSpeak::embeddingsFrom('mistral', $request);

echo "Generated {$response->getEmbeddingCount()} embeddings";
$allEmbeddings = $response->getAllEmbeddings();
```

### Format Optimization

```php
// High precision for similarity search
$precisionRequest = new LLMSpeakEmbeddingsRequest(
    model: 'nomic-embed-text',
    input: $text,
    encoding_format: 'float',    // High precision
    dimensions: null,
    task_type: null
);

// Compact format for storage
$compactRequest = new LLMSpeakEmbeddingsRequest(
    model: 'mistral-embed',
    input: $text,
    encoding_format: 'base64',   // Maps to int8/binary
    dimensions: 256,             // Reduced dimensions
    task_type: null
);

$precisionResponse = LLMSpeak::embeddingsFrom('ollama', $precisionRequest);
$compactResponse = LLMSpeak::embeddingsFrom('mistral', $compactRequest);
```

### Provider-Specific Features

```php
// Gemini: Task-specific optimization
$geminiRequest = new LLMSpeakEmbeddingsRequest(
    model: 'text-embedding-004',
    input: 'Research document for retrieval',
    encoding_format: 'float',
    dimensions: 768,
    task_type: 'RETRIEVAL_DOCUMENT'  // Gemini-specific optimization
);

// Mistral: Advanced format control  
$mistralRequest = new LLMSpeakEmbeddingsRequest(
    model: 'mistral-embed',
    input: $batchTexts,
    encoding_format: 'base64',       // Maps to outputDtype: 'int8'
    dimensions: 512,                 // Maps to outputDimension
    task_type: null
);

// Ollama: Local processing with performance metrics
$ollamaRequest = new LLMSpeakEmbeddingsRequest(
    model: 'nomic-embed-text',
    input: $sensitiveText,           // Never leaves your machine!
    encoding_format: 'float',
    dimensions: null,
    task_type: null
);

$ollamaResponse = LLMSpeak::embeddingsFrom('ollama', $ollamaRequest);
$performanceData = $ollamaResponse->metadata; // Timing metrics included
```

## 🔧 Provider Management

### Dynamic Provider Loading

LLMSpeak uses Laravel's Manager pattern for dynamic provider loading:

```php
// Providers auto-register from configuration
// config/llms/chat-providers/drivers/anthropic.php
return [
    'base_url' => 'https://api.anthropic.com/v1/',
    'llm_speak_driver' => \LLMSpeak\Anthropic\Drivers\AnthropicLLMSpeakTranslationDriver::class,
];

// Manager automatically discovers and loads
$chatManager = app('llm-providers');
$anthropicDriver = $chatManager->driver('anthropic');
```

### Provider Configuration

```php
// Main configuration in config/llms.php
return [
    'chat-providers' => [
        'default' => 'anthropic',
        'drivers' => [
            // Automatically populated from provider configs
        ],
    ],
    'embeddings-providers' => [
        'default' => 'gemini',
        'drivers' => [
            // Automatically populated from provider configs  
        ],
    ]
];
```

### Runtime Provider Discovery

```php
// Get available providers
$chatProviders = app('llm-providers')->getDrivers();
$embeddingProviders = app('llm-embeddings-providers')->getDrivers();

// Check if provider is available
if (app('llm-providers')->hasDriver('anthropic')) {
    $response = LLMSpeak::chatWith('anthropic', $request);
}
```

## 🔄 Translation Layer

The translation layer is the **magic** that makes universal compatibility possible. Each provider implements intelligent bidirectional translation:

### Translation Flow

```
Universal Request → convertRequest() → Provider Request → API Call
Provider Response → translateResponse() → Universal Response ← Universal Request
```

### Example: Gemini Translation

```php
class GeminiLLMSpeakEmbeddingsDriver extends LLMEmbeddingsDriver
{
    public function convertRequest(LLMSpeakEmbeddingsRequest $communique): GeminiEmbeddingsRequest
    {
        // Universal input → Gemini content structure
        $content = is_string($communique->input) 
            ? ['parts' => [['text' => $communique->input]]]
            : ['parts' => array_map(fn($text) => ['text' => $text], $communique->input)];

        return new GeminiEmbeddingsRequest(
            model: $communique->model,
            content: $content,
            taskType: $communique->task_type,      // Direct mapping
            outputDimensionality: $communique->dimensions
        );
    }

    public function translateResponse(mixed $communique): LLMSpeakEmbeddingsResponse
    {
        // Gemini response → Universal format
        $data = [];
        if ($communique->hasEmbedding()) {
            $data[] = [
                'object' => 'embedding',
                'embedding' => $communique->getEmbeddingValues(),
                'index' => 0
            ];
        }

        return new LLMSpeakEmbeddingsResponse(
            model: 'text-embedding-004',
            data: $data,
            usage: ['prompt_tokens' => 0, 'total_tokens' => 0],
            metadata: ['embedding_dimensions' => $communique->getDimensions()]
        );
    }
}
```

### Intelligent Format Mapping

```php
// Mistral: Automatic format translation
'float' → outputDtype: 'float'      // High precision
'base64' → outputDtype: 'int8'      // Quantized format

// Ollama: Options array mapping  
$options = [
    'dimensions' => $communique->dimensions,    // If specified
    'task_type' => $communique->task_type,      // If specified
];
```

## 🧪 Testing Infrastructure

LLMSpeak includes **90+ console commands** for comprehensive testing:

### Universal Interface Testing

```bash
# Test universal chat interfaces
php artisan llmspeak:universal-anthropic --test=all
php artisan llmspeak:universal-gemini --test=all
php artisan llmspeak:universal-ollama --test=all

# Test universal embedding interfaces  
php artisan gemini:m-embedding --test=all
php artisan mistral:m-embeddings --test=all
php artisan ollama:m-embed --test=all
```

### Provider-Specific Testing

```bash
# Native provider APIs
php artisan anthropic:r-message-r --test=advanced
php artisan gemini:r-generate-r --test=tools
php artisan ollama:r-chat --test=streaming

# Translation layer testing
php artisan llmspeak:request-conversion anthropic chat
php artisan llmspeak:request-translation gemini embeddings
```

### Test Categories

**Basic Tests:**
- Single requests and responses
- Simple parameter validation
- Basic error handling

**Advanced Tests:**
- Batch processing
- Complex parameter combinations  
- Tool calling and function execution
- Streaming responses

**Translation Tests:**
- Universal → Provider format conversion
- Provider → Universal format conversion
- Edge case handling
- Format compatibility

**Performance Tests:**
- Token usage analysis
- Response timing
- Memory efficiency
- Concurrent request handling

### Custom Test Commands

```php
// Create your own test commands
class CustomProviderTest extends Command
{
    public function handle()
    {
        $request = new LLMSpeakChatRequest(
            model: 'test-model',
            messages: $this->buildTestConversation()
        );
        
        $providers = ['anthropic', 'gemini', 'mistral'];
        
        foreach ($providers as $provider) {
            $response = LLMSpeak::chatWith($provider, $request);
            $this->analyzeResponse($provider, $response);
        }
    }
}
```

## ⚙️ Configuration

### Core Configuration

```php
// config/llms.php (published via vendor:publish)
return [
    'chat-providers' => [
        'default' => env('LLM_CHAT_DEFAULT', 'anthropic'),
        'drivers' => [
            // Auto-populated from provider packages
        ],
    ],
    'embeddings-providers' => [
        'default' => env('LLM_EMBEDDINGS_DEFAULT', 'gemini'),
        'drivers' => [
            // Auto-populated from provider packages
        ],
    ]
];
```

### Provider Registration

Each provider automatically registers itself:

```php
// config/llms/chat-providers/drivers/anthropic.php
return [
    'base_url' => 'https://api.anthropic.com/v1/',
    'llm_speak_driver' => \LLMSpeak\Anthropic\Drivers\AnthropicLLMSpeakTranslationDriver::class,
    'timeout' => 30,
    'max_retries' => 3,
];

// config/llms/embeddings-providers/drivers/gemini.php  
return [
    'base_url' => 'https://generativelanguage.googleapis.com/v1beta/',
    'llm_speak_driver' => \LLMSpeak\Gemini\Drivers\GeminiLLMSpeakEmbeddingsDriver::class,
];
```

### Environment Variables

```env
# Default providers
LLM_CHAT_DEFAULT=anthropic
LLM_EMBEDDINGS_DEFAULT=gemini

# Provider API keys
ANTHROPIC_API_KEY=your_anthropic_key
GEMINI_API_KEY=your_google_key  
MISTRAL_API_KEY=your_mistral_key
OLLAMA_BASE_URL=http://localhost:11434/api/

# Advanced configuration
LLM_TIMEOUT=30
LLM_MAX_RETRIES=3
LLM_ENABLE_CACHING=true
```

## 🎨 Extending the System

### Adding a New Provider

1. **Create Driver Class**

```php
class NewProviderLLMSpeakDriver extends LLMEmbeddingsDriver
{
    public function convertRequest(LLMSpeakEmbeddingsRequest $communique): NewProviderRequest
    {
        return new NewProviderRequest(
            model: $communique->model,
            text: $communique->input,
            format: $this->mapEncodingFormat($communique->encoding_format)
        );
    }
    
    public function translateResponse(mixed $communique): LLMSpeakEmbeddingsResponse
    {
        return new LLMSpeakEmbeddingsResponse(
            model: $communique->model,
            data: $this->formatEmbeddings($communique->embeddings),
            usage: $this->mapUsage($communique->usage)
        );
    }
    
    private function mapEncodingFormat(?string $format): string
    {
        return match($format) {
            'float' => 'high_precision',
            'base64' => 'compressed', 
            default => 'standard'
        };
    }
}
```

2. **Register Provider**

```php
// config/llms/embeddings-providers/drivers/newprovider.php
return [
    'base_url' => 'https://api.newprovider.com/v1/',
    'llm_speak_driver' => \App\LLMProviders\NewProviderLLMSpeakDriver::class,
];
```

3. **Use Immediately**

```php
$response = LLMSpeak::embeddingsFrom('newprovider', $request);
```

### Custom Request/Response Schemas

```php
class CustomEmbeddingsRequest extends LLMSpeakEmbeddingsRequest
{
    public function __construct(
        string $model,
        string|array $input,
        ?string $encoding_format,
        ?int $dimensions,
        ?string $task_type,
        
        // Custom fields
        public ?string $custom_parameter = null,
        public ?array $advanced_options = null
    ) {
        parent::__construct($model, $input, $encoding_format, $dimensions, $task_type);
    }
}
```

### Provider Capabilities Detection

```php
class ProviderCapabilities
{
    public static function detect(string $provider): array
    {
        $driver = app('llm-embeddings-providers')->driver($provider);
        
        return [
            'supports_batch' => $driver->supportsBatchProcessing(),
            'supports_dimensions' => $driver->supportsDimensions(),
            'supports_task_types' => $driver->supportsTaskTypes(),
            'max_input_length' => $driver->getMaxInputLength(),
            'available_formats' => $driver->getAvailableFormats(),
        ];
    }
}
```

## 📝 Real-World Examples

### AI-Powered Document Search

```php
class DocumentSearchService
{
    public function buildSearchIndex(array $documents): array
    {
        $embeddings = [];
        
        foreach (array_chunk($documents, 100) as $batch) {
            $request = new LLMSpeakEmbeddingsRequest(
                model: 'text-embedding-004',
                input: array_column($batch, 'content'),
                encoding_format: 'float',
                dimensions: 1024,
                task_type: 'RETRIEVAL_DOCUMENT'
            );
            
            $response = LLMSpeak::embeddingsFrom('gemini', $request);
            $embeddings = array_merge($embeddings, $response->getAllEmbeddings());
        }
        
        return $embeddings;
    }
    
    public function search(string $query, array $documentEmbeddings): array
    {
        $queryRequest = new LLMSpeakEmbeddingsRequest(
            model: 'text-embedding-004', 
            input: $query,
            encoding_format: 'float',
            dimensions: 1024,
            task_type: 'RETRIEVAL_QUERY'
        );
        
        $queryResponse = LLMSpeak::embeddingsFrom('gemini', $queryRequest);
        $queryEmbedding = $queryResponse->getFirstEmbedding();
        
        // Calculate similarities and return ranked results
        return $this->rankBySimilarity($queryEmbedding, $documentEmbeddings);
    }
}
```

### Multi-Provider Chat Assistant

```php
class SmartChatAssistant
{
    private array $providerPriority = ['anthropic', 'gemini', 'mistral'];
    
    public function chat(string $message, ?string $systemPrompt = null): string
    {
        $conversation = new Conversation([
            new ChatMessage(ChatRole::USER, $message)
        ]);
        
        $systemInstructions = $systemPrompt 
            ? new SystemInstructions([new SystemInstruction($systemPrompt)])
            : null;
            
        $request = new LLMSpeakChatRequest(
            model: $this->getBestModel(),
            messages: $conversation,
            system_instructions: $systemInstructions,
            max_tokens: 2000,
            temperature: 0.7
        );
        
        foreach ($this->providerPriority as $provider) {
            try {
                $response = LLMSpeak::chatWith($provider, $request);
                
                if ($response && $response->completedNaturally()) {
                    return $response->getTextContent();
                }
            } catch (Exception $e) {
                Log::warning("Provider {$provider} failed: " . $e->getMessage());
                continue;
            }
        }
        
        throw new RuntimeException('All chat providers failed');
    }
    
    private function getBestModel(): string
    {
        // Dynamic model selection based on current availability
        return match($this->getAvailableProvider()) {
            'anthropic' => 'claude-3-5-sonnet-20241022',
            'gemini' => 'gemini-2.0-flash-exp',
            'mistral' => 'mistral-large-latest',
            default => 'claude-3-5-sonnet-20241022'
        };
    }
}
```

### Embedding-Based Content Recommendation

```php
class ContentRecommendationEngine
{
    public function getRecommendations(User $user, int $limit = 10): array
    {
        // Get user's reading history
        $userContent = $user->readArticles->pluck('content')->toArray();
        
        // Generate user profile embedding
        $profileRequest = new LLMSpeakEmbeddingsRequest(
            model: 'mistral-embed',
            input: $userContent,
            encoding_format: 'float',
            dimensions: 1024,
            task_type: null
        );
        
        $profileResponse = LLMSpeak::embeddingsFrom('mistral', $profileRequest);
        $userProfile = $this->averageEmbeddings($profileResponse->getAllEmbeddings());
        
        // Get candidate articles
        $candidates = Article::whereNotIn('id', $user->readArticles->pluck('id'))->get();
        
        // Generate embeddings for candidates  
        $candidateTexts = $candidates->pluck('content')->toArray();
        $candidateRequest = new LLMSpeakEmbeddingsRequest(
            model: 'mistral-embed',
            input: $candidateTexts,
            encoding_format: 'float', 
            dimensions: 1024,
            task_type: null
        );
        
        $candidateResponse = LLMSpeak::embeddingsFrom('mistral', $candidateRequest);
        $candidateEmbeddings = $candidateResponse->getAllEmbeddings();
        
        // Rank by similarity to user profile
        $scored = collect($candidates)->zip($candidateEmbeddings)
            ->map(fn($pair) => [
                'article' => $pair[0],
                'similarity' => $this->cosineSimilarity($userProfile, $pair[1])
            ])
            ->sortByDesc('similarity')
            ->take($limit);
            
        return $scored->pluck('article')->toArray();
    }
}
```

## ❓ FAQ

### **Q: How does LLMSpeak handle provider-specific features?**

A: LLMSpeak uses intelligent translation that maps universal parameters to provider-specific equivalents. For example:

- Universal `encoding_format: 'base64'` → Mistral `outputDtype: 'int8'`
- Universal `task_type: 'RETRIEVAL_DOCUMENT'` → Gemini `taskType: 'RETRIEVAL_DOCUMENT'`
- Universal `dimensions: 512` → Provider-specific dimension parameters

Features not supported by a provider are gracefully ignored rather than causing errors.

### **Q: What happens if a provider is unavailable?**

A: LLMSpeak methods return `false` on failure, allowing for graceful fallback handling:

```php
$response = LLMSpeak::chatWith('primary-provider', $request);
if (!$response) {
    $response = LLMSpeak::chatWith('fallback-provider', $request);
}
```

### **Q: Can I use multiple providers simultaneously?**

A: Yes! You can send the same request to multiple providers for comparison or redundancy:

```php
$providers = ['anthropic', 'gemini', 'mistral'];
$responses = [];

foreach ($providers as $provider) {
    $responses[$provider] = LLMSpeak::chatWith($provider, $request);
}
```

### **Q: How do I add a custom provider?**

A: Implement the `LLMEmbeddingsContract` or `LLMTranslationContract`, create a config file, and LLMSpeak will automatically discover it. See the [Extending the System](#-extending-the-system) section.

### **Q: Does LLMSpeak support streaming responses?**

A: Yes, through the `stream` parameter in universal requests. Each provider handles streaming according to their native capabilities.

### **Q: How does pricing work across providers?**

A: LLMSpeak doesn't affect pricing - you pay each provider according to their standard rates. The universal interface can actually help optimize costs by making it easy to switch to more cost-effective providers.

### **Q: Is there performance overhead?**

A: Minimal. The translation layer adds approximately <50ms per request, which is negligible compared to network latency and model inference time.

### **Q: Can I access provider-specific response data?**

A: Yes, through the `metadata` field in universal responses, which preserves provider-specific information while maintaining compatibility.

## 🙏 Credits

- **[Project Saturn Studios](https://github.com/projectsaturnstudios)** - Core development and architecture
- **[Laravel](https://laravel.com)** - Framework foundation and Manager pattern
- **[Spatie Laravel Data](https://spatie.be/docs/laravel-data/)** - Data validation and transformation
- **The LLM Provider Ecosystem** - Google, Anthropic, Mistral, Meta, and all the amazing AI companies pushing the boundaries

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---

## 🚀 Join the Universal LLM Revolution

LLMSpeak Core represents the **future of LLM integration** - where developers are free to choose the best AI for each task without being locked into vendor-specific APIs. 

**Never be held hostage by a single LLM provider again.** 

Build once. Run everywhere. Scale infinitely.

---

**Part of the LLMSpeak Ecosystem** - Engineered with passion by [Project Saturn Studios](https://projectsaturnstudios.com) 🌟

*"Making AI integration as universal as HTTP"* ✨
