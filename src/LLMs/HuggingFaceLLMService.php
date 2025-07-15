<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\HuggingFace\Builders\EmbeddingQueryBuilder;
use LLMSpeak\HuggingFace\Enums\HuggingFaceRole;
use LLMSpeak\HuggingFace\Builders\ConversationBuilder;
use LLMSpeak\HuggingFace\HuggingFaceEmbeddingsResult;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\HuggingFace\HuggingFaceCallResult;
use LLMSpeak\HuggingFace\Support\Facades\HuggingFace;
use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use LLMSpeak\Schema\Embeddings\EmbeddingRequest;
use LLMSpeak\Schema\Embeddings\EmbeddingResult;
use LLMSpeak\Support\Facades\CreateChatRequest;
use LLMSpeak\Support\Facades\LLM;

class HuggingFaceLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(HuggingFace::class))
        {
            throw new \Exception("HuggingFace LLM Service is not available. Please install the Hugging Face package.");
        }
    }

    public function text(ChatRequest $request): ChatResult
    {
        $setup = HuggingFace::completions()
            ->withApikey($request->credentials['api_key'] ?? HuggingFace::api_key())
            ->withModel($request->model);

        if($request->max_tokens) $setup = $setup->withMaxTokens($request->max_tokens);
        if($request->temperature) $setup = $setup->withTemperature($request->temperature);
        if($request->tools) $setup = $setup->withTools($request->tools);

        /** @var HuggingFaceCallResult $response */
        $response = $setup->withMessages(static::convertConversation($request->messages))
            ->handle();
        if(!empty($response->choices))
        {
            $results = (new ChatResult())
                ->addModel($response->model)->addId($response->id);

            foreach($response->choices as $choice)
            {
                if(is_string($choice['message']['content']) && !empty($choice['message']['content'])) $results = $results->addMessage(new TextMessage(...$choice['message']));
                elseif(array_key_exists('tool_calls', $choice['message']))
                {
                    foreach($choice['message']['tool_calls'] as $tool_call)
                    {
                        $results = $results->addToolRequest(new ToolCall($tool_call['function']['name'], json_decode($tool_call['function']['arguments'], true), $tool_call['id']));
                    }
                }
                else dd($choice, 'not finished yet');
            }

            $results = $results->additional($response->toArray());
            return $results;
        }
        else
        {

        }

        return new ChatResult();
    }

    public function embeddings(EmbeddingRequest $request): EmbeddingResult
    {
        $setup = HuggingFace::embeddings()
            ->withApikey(HuggingFace::api_key())
            ->withModel($request->model);

        // Add optional parameters
        if($request->messages) $setup = $setup->withInputs($request->messages);

        /** @var HuggingFaceEmbeddingsResult $response */
        $response = $setup->handle();

        $results = (new EmbeddingResult())
            ->addModel($request->model);

        if(count($response->embeddings['data']) > 0) {
            foreach($response->embeddings['data'] as $embedding) {
                $results = $results->addEmbedding($embedding['embedding']);
            }
        }

        return $results;
    }

    public static function convertConversation(array $convo): array
    {
        $conversation = new ConversationBuilder();

        foreach($convo as $message) {
            if($message instanceof TextMessage) {
                $conversation->addText(HuggingFaceRole::from($message->role), $message->content);
            } elseif($message instanceof ToolCall) {
                $conversation->addToolRequest($message->id, $message->tool, $message->input);
            } elseif($message instanceof ToolResult) {
                $conversation->addToolResult($message->id, $message->tool, $message->result);
            }
        }

        return $conversation->render();
    }

    public static function convertSystemPrompt(array $convo): array
    {
        return [];
    }

    public static function defaultCredentials(): array
    {
        return [
            'api_key' => HuggingFace::api_key(),
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

        $chatRequest = CreateChatRequest::usingModel('meta-llama/llama-3.2-3b-instruct')
            ->supplyCredentials(['api_key' => HuggingFace::api_key()])
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('huggingface')->text($chatRequest);
    }

    public static function test2(): ?ChatResult
    {
        $convo = [
            new TextMessage('system','You love to use tools.'),
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
        ];

        $chatRequest = CreateChatRequest::usingModel('meta-llama/llama-3.2-3b-instruct')
            ->supplyCredentials(['api_key' => HuggingFace::api_key()])
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
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('huggingface')->text($chatRequest);
    }

    public static function test3(): ?ChatResult
    {
        $convo = [
            new TextMessage('system','You love to use tools.'),
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
            new ToolCall('lights_off', ["off" => true], '3chatcmpl-tool-0e5128646f0a4bbfaa0a4fb37378d7c4'),
            new ToolResult('tool', 'lights_off', ['output' => "The lights have been shut off. Simply tell the user 'Sick, bro. They shut shut off.'"], 'chatcmpl-tool-0e5128646f0a4bbfaa0a4fb37378d7c4'), // name, response<array>
        ];

        $chatRequest = CreateChatRequest::usingModel('llama-3.3-70b-versatile')
            ->supplyCredentials(['api_key' => HuggingFace::api_key()])
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('huggingface')->text($chatRequest);
    }

    public static function test4(): ?EmbeddingResult
    {
        $convo = (new EmbeddingQueryBuilder())
            ->addQuery("What happens if I get pulled over for speeding?")
            ->render();

        $request = new EmbeddingRequest(
            'Qwen/Qwen3-Embedding-8B',
            $convo
        );

        return LLM::driver('huggingface')->embeddings($request);
    }


    public static function test5(): ?EmbeddingResult
    {
        $convo = (new EmbeddingQueryBuilder())
            ->addQuery("What happens if I get pulled over for speeding?")
            ->addQuery("If I go to jail do I get my one phone call?")
            ->render();

        $request = new EmbeddingRequest(
            'Qwen/Qwen3-Embedding-8B',
            $convo
        );

        return LLM::driver('huggingface')->embeddings($request);
    }
}
