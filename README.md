

```php

use LLMSpeak\Support\Facades\SpeakWith;

$llm = speak_with('anthropic', $request)
$llm = speak_with('google', $request)
$llm = speak_with('open-ai', $request, 'v1'|'v2')
$llm = speak_with('ollama', $request)
$llm = speak_with('deepseek', $request)
$llm = speak_with('minstrel', $request)
$llm = speak_with('hugging-face', $request)
$llm = speak_with('open-router', $request)

$llm = stream_with('anthropic', $request)
$llm = stream_with('google', $request)
$llm = stream_with('open-ai', $request, 'v1'|'v2')
$llm = stream_with('ollama', $request)
$llm = stream_with('deepseek', $request)
$llm = stream_with('minstrel', $request)
$llm = stream_with('hugging-face', $request)
$llm = stream_with('open-router', $request)

$llm = show_structure_to('anthropic', $request)
$llm = show_structure_to('google', $request)
$llm = show_structure_to('open-ai', $request, 'v1'|'v2')
$llm = show_structure_to('ollama', $request)
$llm = show_structure_to('deepseek', $request)
$llm = show_structure_to('minstrel', $request)
$llm = show_structure_to('hugging-face', $request)
$llm = show_structure_to('open-router', $request)

```