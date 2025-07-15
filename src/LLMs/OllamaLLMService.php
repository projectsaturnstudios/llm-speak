<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Ollama\Enums\OllamaRole;
use LLMSpeak\Ollama\OllamaEmbeddingResult;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use LLMSpeak\Schema\Embeddings\EmbeddingRequest;
use LLMSpeak\Schema\Embeddings\EmbeddingResult;
use LLMSpeak\Support\Facades\LLM;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Ollama\OllamaCallResult;
use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Ollama\Support\Facades\Ollama;
use LLMSpeak\Support\Facades\CreateChatRequest;
use LLMSpeak\Ollama\Builders\ConversationBuilder;
use LLMSpeak\Ollama\Builders\SystemPromptBuilder;

class OllamaLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(Ollama::class))
        {
            throw new \Exception("Ollama LLM Service is not available. Please install the Ollama package.");
        }
    }

    public function text(ChatRequest $request): ChatResult
    {
        $setup = Ollama::chat_completions()
            ->withModel($request->model);

        // Add optional parameters
        if($request->tools) $setup = $setup->withTools($request->tools);

        /** @var OllamaCallResult $response */
        $response = $setup->withMessages(static::convertConversation($request->messages))
            ->handle();

        if($response->done)
        {
            $results = (new ChatResult())
                ->addModel($response->model)
                ->addId(uniqid()); // Ollama doesn't provide message IDs, so generate one

            // Handle regular text response
            if($response->message['content']) {
                $results = $results->addMessage(new TextMessage(...$response->message));
            }

            // Handle tool calls if present
            if(isset($response->message->toolCalls) && !empty($response->message->toolCalls)) {
                foreach($response->message->toolCalls as $toolCall) {
                    $results = $results->addToolRequest(
                        new ToolCall(
                            $toolCall['function']['name'],
                            $toolCall['function']['arguments']
                        )
                    );
                }
            }

            return $results;
        }

        throw new \Exception("Ollama request failed or was incomplete.");
    }

    public function embeddings(EmbeddingRequest $request): EmbeddingResult
    {
        $setup = Ollama::embeddings()
            ->withModel($request->model);

        // Add optional parameters
        if($request->messages) $setup = $setup->withInput($request->messages);

        /** @var OllamaEmbeddingResult $response */
        $response = $setup->handle();

        $results = (new EmbeddingResult())
            ->addModel($request->model);

        if(count($response->embeddings) > 0) {
            foreach($response->embeddings as $embedding) {
                $results = $results->addEmbedding($embedding);
            }
        }

        return $results;
    }

    public static function convertConversation(array $convo): array
    {
        $conversation = new ConversationBuilder();

        foreach($convo as $message) {
            if($message instanceof TextMessage) {
                $conversation->addText(OllamaRole::from($message->role), $message->content);
            } elseif($message instanceof ToolCall) {
                $conversation->addToolRequest('0', $message->tool, $message->input);
            } elseif($message instanceof ToolResult) {
                $conversation->addToolResult($message->tool, $message->result);
            }
        }

        return $conversation->render();
    }

    public static function convertSystemPrompt(array $convo): string|array
    {
        $systemPrompt = new SystemPromptBuilder();

        foreach($convo as $message) {
            if($message instanceof TextMessage && $message->role === 'system') {
                $systemPrompt->addText($message->content);
            }
        }

        return $systemPrompt->render();
    }

    public static function defaultCredentials(): array
    {
        return [
            'base_url' => config('ollama.base_url', 'http://localhost:11434'),
            // Ollama typically doesn't use API keys for local instances
        ];
    }

    public static function test(): ?ChatResult
    {
        $convo = [
            new TextMessage('system','You are a dog. You are such a good boi'),
            new TextMessage('system',"All of your responses are in the form of 'woof' or 'bow wow'."),
            new TextMessage('system',"Arf."),
            new TextMessage('user', 'What is the meaning of life?'),
            new TextMessage('assistant', 'Woof.'),
            new TextMessage('user', 'What is your favorite food?'),
        ];

        $chatRequest = CreateChatRequest::usingModel('llama3.2')
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('ollama')->text($chatRequest);
    }

    public static function test2(): ?ChatResult
    {
        $convo = [
            new TextMessage('system','You love to use tools.'),
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
        ];

        $request = CreateChatRequest::usingModel('llama3.2')
            ->allowAccessToTools([
                [
                    'type' => 'function',
                    'function' => [
                        "name" => "lights_off",
                        "description" => "Turns off the user's lights.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "off" => [
                                    "type" => "boolean",
                                    "description" => "Set to true",
                                ],
                            ],
                            "required" => [
                                "off",
                            ],
                        ],
                    ]
                ]
            ])
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('ollama')->text($request);
    }

    public static function test3(): ?ChatResult
    {
        $convo = [
            new TextMessage('system','You love to use tools.'),
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
            new ToolCall('lights_off', ["off" => true], '3khjaLHBAaO_qtsP-tfMsAg'),
            new ToolResult('tool', 'lights_off', "The lights have been shut off. Simply tell the user 'Sick, bro. They shut shut off.'", '3khjaLHBAaO_qtsP-tfMsAg'), // name, response<array>
        ];

        $request = CreateChatRequest::usingModel('llama3.2')
            ->allowAccessToTools([
                [
                    'type' => 'function',
                    'function' => [
                        "name" => "lights_off",
                        "description" => "Turns off the user's lights.",
                        "parameters" => [
                            "type" => "object",
                            "properties" => [
                                "off" => [
                                    "type" => "boolean",
                                    "description" => "Set to true",
                                ],
                            ],
                            "required" => [
                                "off",
                            ],
                        ],
                    ]
                ]
            ])
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('ollama')->text($request);
    }

    public static function test4(): ?EmbeddingResult
    {
        $convo = [
            "What happens if I get pulled over for speeding?",
        ];

        $request = new EmbeddingRequest(
            'hf.co/mariadjadi/fine_tuned_mistral_legal_V2_merged-GGUF:latest',
            $convo
        );

        return LLM::driver('ollama')->embeddings($request);
    }


    public static function test5(): ?EmbeddingResult
    {
        $convo = [
            "What happens if I get pulled over for speeding?",
            "If I go to jail do I get my one phone call?"
        ];

        $request = new EmbeddingRequest(
            'hf.co/mariadjadi/fine_tuned_mistral_legal_V2_merged-GGUF:latest',
            $convo
        );

        return LLM::driver('ollama')->embeddings($request);
    }
}
