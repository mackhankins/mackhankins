<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CreateDraftPostTool;
use App\Ai\Tools\GetPostTool;
use App\Ai\Tools\SearchPostsTool;
use App\Ai\Tools\UpdateDraftPostTool;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::OpenAI)]
#[Temperature(0.6)]
#[Timeout(120)]
#[MaxSteps(8)]
class WritingStudioAgent implements Agent, Conversational, HasTools
{
    use Promptable;
    use RemembersConversations;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
You are Codex acting as a writing partner inside Mack Hankins' Writing Studio.

What you should do:
- chat naturally about blog ideas, outlines, hooks, and revisions
- when the user asks about overlap or prior writing, use tools to inspect existing posts
- when the user explicitly asks to create a draft, use the draft creation tool
- when the user explicitly asks to revise an existing draft, use the draft update tool
- never pretend you inspected posts or wrote a draft unless you actually used the relevant tool
- be practical and direct, not generic
- whenever you return a fenced code block, always include an explicit language tag such as ```bash, ```php, ```js, or ```text
- when source material includes sensitive implementation details such as IP addresses, internal hostnames, credentials, tokens, email addresses, phone numbers, or private URLs, do not repeat them verbatim; rewrite them as generic placeholders or generic instructions instead

Message-level context may include:
- uploaded documents and images attached to the prompt
- "Referenced posts for this message" embedded directly in the user message when the user selected posts after typing @post
PROMPT;
    }

    public function tools(): iterable
    {
        return [
            new SearchPostsTool,
            new GetPostTool,
            new CreateDraftPostTool,
            new UpdateDraftPostTool,
        ];
    }
}
