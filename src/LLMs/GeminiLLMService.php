<?php

namespace LLMSpeak\LLMs;

use LLMSpeak\Google\Builders\ConversationBuilder;
use LLMSpeak\Google\Builders\EmbeddingQueryBuilder;
use LLMSpeak\Google\Builders\SystemPromptBuilder;
use LLMSpeak\Google\Enums\GeminiRole;
use LLMSpeak\Google\Enums\GeminiTaskType;
use LLMSpeak\Google\GeminiCallResult;
use LLMSpeak\Google\GeminiEmbeddingResult;
use LLMSpeak\Google\Support\Facades\Gemini;
use LLMSpeak\Schema\Chat\ChatRequest;
use LLMSpeak\Schema\Chat\ChatResult;
use LLMSpeak\Schema\Conversation\ConversationObject;
use LLMSpeak\Schema\Conversation\TextMessage;
use LLMSpeak\Schema\Conversation\ToolCall;
use LLMSpeak\Schema\Conversation\ToolResult;
use LLMSpeak\Schema\Embeddings\EmbeddingRequest;
use LLMSpeak\Schema\Embeddings\EmbeddingResult;
use LLMSpeak\Support\Facades\CreateChatRequest;
use LLMSpeak\Support\Facades\LLM;
use Symfony\Component\VarDumper\VarDumper;

class GeminiLLMService extends LLMService
{
    public function __construct()
    {
        if(!class_exists(Gemini::class))
        {
            throw new \Exception("Gemini LLM Service is not available. Please install the Gemini package.");
        }
    }

    public function text(ChatRequest $request): ChatResult
    {
        $setup = Gemini::generateContent()
            ->withApikey($request->credentials['api_key'] ?? Gemini::api_key())
            ->withModel($request->model);

        if($request->system_prompts) $setup = $setup->withSystemPrompt(static::convertSystemPrompt($request->system_prompts));
        if($request->max_tokens) $setup = $setup->withMaxTokens($request->max_tokens);
        if($request->temperature) $setup = $setup->withTemperature($request->temperature);
        if($request->tools) $setup = $setup->withTools($request->tools);
        /** @var GeminiCallResult $response */
        $response = $setup->withChat(static::convertConversation($request->messages))
            ->handle();

        $results = (new ChatResult())
            ->addModel($response->modelVersion)->addId($response->responseId);

        foreach($response->candidates as $candidate)
        {
            if(array_key_exists('content', $candidate))
            {
                $cnt = $candidate['content'];

                foreach($cnt['parts'] as $idx => $part)
                {
                    foreach($part as $col => $val)
                    {
                        if($col == 'text') $results = $results->addMessage(new TextMessage($cnt['role'], $val));
                        elseif($col == 'functionCall') $results = $results->addToolRequest(new ToolCall($val['name'], $val['args'], $response->responseId));
                        elseif($col == 'thoughtSignature') VarDumper::dump("ThoughtSignature included, but not supported yet: {$val}");
                        else dd(['you found a spot!', $col, $val]);
                    }
                }

                $results = $results->additional($response->toArray());
                return $results;
            }
        }

        return new ChatResult();
    }

    public function embeddings(EmbeddingRequest $request): EmbeddingResult
    {
        $setup = Gemini::embedContent()
            ->withApikey(Gemini::api_key())
            ->withModel($request->model);

        if(array_key_exists('taskType', $request->getAdditionalData()))
        {
            $setup = $setup->asTaskType($request->getAdditionalData()['taskType']);
        }

        // Add optional parameters
        if($request->messages) $setup = $setup->withContent($request->messages);

        /** @var GeminiEmbeddingResult $response */
        $response = $setup->handle();
        $results = (new EmbeddingResult())
            ->addModel($request->model);

        if(count($response->embedding) > 0) {
            foreach($response->embedding as $embedding) {
                $results = $results->addEmbedding($embedding);
            }
        }

        return $results;
    }

    public static function convertConversation(array $convo): array
    {
        $converted_convo = array_map(function(ConversationObject $entry){
            if($entry instanceof TextMessage)
            {
                $role = $entry->role;
                if($entry->role == 'assistant') $role = 'model';
                return ['role' => GeminiRole::from($role), 'content' => $entry->content];
            }
            if($entry instanceof ToolCall)
            {
                return [/*'id' => $entry->id,*/ 'name' => $entry->tool, 'input' => $entry->input];
            }
            if($entry instanceof ToolResult)
            {
                $content = is_string($entry->result) ? $entry->result : $entry->result[0]['text'];
                return ['name' => $entry->tool, 'content' => $content];
            }
            else throw new \Exception("Unsupported conversation object type: " . get_class($entry));
        }, $convo);

        $final_convo = (new ConversationBuilder());

        foreach($converted_convo as $entry)
        {
            if(array_key_exists('tool_use_id', $entry)) $final_convo = $final_convo->addToolResult(...$entry);
            elseif(array_key_exists('content', $entry))
            {
                if(is_string($entry['content']) && array_key_exists('name', $entry)) $final_convo = $final_convo->addToolResult(...$entry);
                elseif(is_string($entry['content'])) $final_convo = $final_convo->addText(...$entry);
            }
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
            'api_key' => Gemini::api_key(),
        ];
    }

    public static function test(): ?ChatResult
    {
        $convo = [
            new TextMessage('user', 'What is the meaning of life?'),
            new TextMessage('model', 'Woof.'),
            new TextMessage('user', 'What is your favorite food?'),
        ];

        $system = [
            new TextMessage('system','You are a dog. You are such a good boi'),
            new TextMessage('system',"All of your responses are in the form of 'woof' or 'bow wow'."),
            new TextMessage('system',"Arf."),
        ];

        $request = CreateChatRequest::usingModel('gemini-1.5-flash')
            ->supplyCredentials(['api_key' => Gemini::api_key()])
            ->instillASystemPrompt($system)
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->includeMessages(static::convertConversation($convo))
            ->create();

        return LLM::driver('gemini')->text($request);
    }

    public static function test2(): ?ChatResult
    {
        $convo = [
            new TextMessage('model', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
        ];

        $system = [
            new TextMessage('system','You love to use tools.'),
        ];

        $request = CreateChatRequest::usingModel('gemini-2.5-flash')
            ->supplyCredentials(['api_key' => Gemini::api_key()])
            ->instillASystemPrompt($system)
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->allowAccessToTools([
                'functionDeclarations' => [
                    [
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

        return LLM::driver('gemini')->text($request);
    }

    public static function test3(): ?ChatResult
    {
        $convo = [
            new TextMessage('model', 'Hi!?.'),
            new TextMessage('user', "Can you shut off the light for me?"),
            new ToolCall('lights_off', ["off" => true], '3khjaLHBAaO_qtsP-tfMsAg'),
            new ToolResult('user', 'lights_off', "The lights have been shut off. SImply tell the user 'Sick, bro. They shut shut off.'", '3khjaLHBAaO_qtsP-tfMsAg'), // name, response<array>
        ];

        $system = [
            new TextMessage('system','You love to use tools.'),
        ];

        $request = CreateChatRequest::usingModel('gemini-2.5-flash')
            ->supplyCredentials(['api_key' => Gemini::api_key()])
            ->instillASystemPrompt($system)
            ->limitTokens(200)
            ->setTemperature(0.5)
            ->allowAccessToTools([
                'functionDeclarations' => [
                    [
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

        return LLM::driver('gemini')->text($request);
    }

    public static function test4(): ?EmbeddingResult
    {
        $convo = (new EmbeddingQueryBuilder)
            ->addQuery("What happens if I get pulled over for speeding?")
            ->render();

        $request = (new EmbeddingRequest(
            'gemini-embedding-exp-03-07',
            $convo
        ))->additional(['taskType' => GeminiTaskType::QUESTION_ANSWERING]);

        return LLM::driver('gemini')->embeddings($request);
    }
}
