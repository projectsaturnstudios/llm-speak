<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use LLMSpeak\Support\Facades\LLM;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Anthropic\ClaudeCallResult;
use LLMSpeak\Anthropic\Enums\ClaudeRole;
use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Anthropic\Support\Facades\Claude;
use LLMSpeak\Support\Facades\CreateChatRequest;
use LLMSpeak\Anthropic\Builders\ConversationBuilder;
use LLMSpeak\Schema\Conversation\ConversationObject;
use LLMSpeak\Anthropic\Builders\SystemPromptBuilder;
use Symfony\Component\VarDumper\VarDumper;

class AnthropicLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(Claude::class))
        {
            throw new \Exception("Anthropic LLM Service is not available. Please install the Anthropic package.");
        }
    }
    public function text(ChatRequest $request): ChatResult
    {
        $setup = Claude::messages()
            ->withApikey($request->credentials['api_key'] ?? Claude::api_key())
            ->withAnthropicVersion($request->credentials['anthropic_version'] ?? Claude::anthropic_version())
            ->withModel($request->model);

        if($request->system_prompts) $setup = $setup->withSystemPrompt($request->system_prompts);
        if($request->max_tokens) $setup = $setup->withMaxTokens($request->max_tokens);
        if($request->temperature) $setup = $setup->withTemperature($request->temperature);
        if($request->tools) $setup = $setup->withTools($request->tools);

        /** @var ClaudeCallResult $response */
        $response = $setup->withMessages(static::convertConversation($request->messages))
            ->handle();
        VarDumper::dump(["Fucking shit dude", $response]);
        if($response->type == 'message')
        {
            $results = (new ChatResult())
                ->addModel($response->model)->addId($response->id);

            foreach($response->content as $message)
            {
                if($message['type'] == 'text') $results = $results->addMessage(new TextMessage($response->role->value, $message['text']));
                if($message['type'] == 'tool_use') $results = $results->addToolRequest(new ToolCall($message['name'], $message['input'], $message['id']));
            }

            $results = $results->additional($response->toArray());
            return $results;
        }
        elseif($response->type == 'error')
        {

        }

        // @todo - decide what to do here especially with tool calls and results.

        return new ChatResult();
    }

    /**
     * @param array<ConversationObject> $convo
     * @return array
     */
    public static function convertConversation(array $convo): array
    {

        $converted_convo = array_map(function(ConversationObject $entry){
            if($entry instanceof TextMessage) return ['role' => ClaudeRole::from($entry->role), 'content' => $entry->content];
            elseif($entry instanceof ToolCall) return ['id' => $entry->id, 'name' => $entry->tool, 'input' => $entry->input];
            elseif($entry instanceof ToolResult)
            {
                VarDumper::dump(["Tool Result Entry", $entry]);
                $content = is_string($entry->result) ? $entry->result : $entry->result[0]['text'];
                return ['tool_use_id' => $entry->id, 'content' => $content];
            }
            else throw new \Exception("Unsupported conversation object type: " . get_class($entry));
        }, $convo);

        $final_convo = (new ConversationBuilder());
        foreach($converted_convo as $entry)
        {
            if(array_key_exists('tool_use_id', $entry)) $final_convo = $final_convo->addToolResult(...$entry);
            elseif(array_key_exists('content', $entry) && is_string($entry['content'])) $final_convo = $final_convo->addText(...$entry);
            elseif(array_key_exists('input', $entry)) $final_convo = $final_convo->addToolRequest(...$entry);
        }
        return $final_convo->render();
    }

    public static function convertSystemPrompt(array $convo): array
    {
        $converted_convo = array_map(function(ConversationObject $entry){
            if($entry instanceof TextMessage) return $entry->content;

            else throw new \Exception("Unsupported system prompt object type: " . get_class($entry));
        }, $convo);

        $final_convo = (new SystemPromptBuilder());
        foreach($converted_convo as $entry)
        {
            if(is_string($entry)) $final_convo = $final_convo->addText($entry);
        }

        return $final_convo->render();
    }

    public static function defaultCredentials(): array
    {
        return [
            'api_key' => Claude::api_key(),
            'anthropic_version' => Claude::anthropic_version(),
        ];
    }

    public static function test(): ?ChatResult
    {
        $convo = [
            new TextMessage('user', 'What is the meaning of life?'),
            new TextMessage('assistant', 'Meow.'),
            new TextMessage('user', 'What is your favorite food?'),
        ];

        $system = [
            new TextMessage('system','You are a cat.'),
            new TextMessage('system',"All of your responses are in the form of 'meow'"),
            new TextMessage('system',"Meow."),
        ];

        $request = CreateChatRequest::usingModel('claude-sonnet-4-20250514')
            ->supplyCredentials(['api_key' => Claude::api_key(), 'anthropic_version' => Claude::anthropic_version()])
            ->instillASystemPrompt(static::convertSystemPrompt($system))
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('anthropic')->text($request);
    }

    public static function test2(): ?ChatResult
    {
        $convo = [
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Say something funny with the echo tool."),
        ];

        $system = [
            new TextMessage('system','You love to use tools.'),
        ];

        $request = CreateChatRequest::usingModel('claude-sonnet-4-20250514')
            ->supplyCredentials(['api_key' => Claude::api_key(), 'anthropic_version' => Claude::anthropic_version()])
            ->instillASystemPrompt(static::convertSystemPrompt($system))
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->allowAccessToTools([
                [
                    "name" => "echo",
                    "description" => "Echoes back the request data for testing purposes",
                    "input_schema" => [
                        "type" => "object",
                        "properties" => [
                            "intended_output" => [
                                "type" => "string",
                                "description" => "The intended output of the echo.",
                            ],
                        ],
                        "required" => [
                            "intended_output",
                        ],
                    ],
                ]
            ])
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('anthropic')->text($request);
    }

    public static function test3(): ?ChatResult
    {
        $convo = [
            new TextMessage('assistant', 'Hi!?.'),
            new TextMessage('user', "Say something funny with the echo tool."),
            new ToolCall('echo', ['intended_output' => "Why don't scientists trust atoms? Because they make up everything! 🧪⚛️"], 'toolu_01W39gNFsnuBLEgRn7Nn5tAJ'),
            new ToolResult('user' , 'echo', "Why don't scientists trust atoms? Because they make up everything! 🧪⚛️", 'toolu_01W39gNFsnuBLEgRn7Nn5tAJ'), //tool_use_id, content
        ];

        $system = [
            new TextMessage('system','You love to use tools.'),
        ];

        $request = CreateChatRequest::usingModel('claude-sonnet-4-20250514')
            ->supplyCredentials(['api_key' => Claude::api_key(), 'anthropic_version' => Claude::anthropic_version()])
            ->instillASystemPrompt(static::convertSystemPrompt($system))
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->allowAccessToTools([
                [
                    "name" => "echo",
                    "description" => "Echoes back the request data for testing purposes",
                    "input_schema" => [
                        "type" => "object",
                        "properties" => [
                            "intended_output" => [
                                "type" => "string",
                                "description" => "The intended output of the echo.",
                            ],
                        ],
                        "required" => [
                            "intended_output",
                        ],
                    ],
                ]
            ])
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('anthropic')->text($request);
    }
}
