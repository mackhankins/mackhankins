<?php

use App\Ai\Agents\WritingStudioAgent;
use App\Ai\Tools\CreateDraftPostTool;
use App\Ai\Tools\GetPostTool;
use App\Ai\Tools\SearchPostsTool;
use App\Ai\Tools\UpdateDraftPostTool;
use App\Filament\Pages\WritingStudio;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\Post;
use App\Models\User;
use App\Models\WritingStudioAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\JsonSchema\JsonSchemaTypeFactory;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files;
use Laravel\Ai\Files\Base64Document;
use Laravel\Ai\Files\Base64Image;
use Laravel\Ai\ObjectSchema;
use Laravel\Ai\Tools\Request;
use Livewire\Livewire;

test('authenticated users can load the writing studio page', function () {
    $user = User::factory()->withAppAuthentication()->create();

    $this->actingAs($user)
        ->get(WritingStudio::getUrl())
        ->assertOk()
        ->assertSee('Writing Studio');
});

test('conversation labels fall back to the first user prompt when needed', function () {
    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'New Conversation',
    ]);

    AgentConversationMessage::query()->create([
        'id' => (string) str()->uuid(),
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
        'agent' => WritingStudioAgent::class,
        'role' => 'user',
        'content' => 'Write a post about MySQL restore drills for small teams.',
        'attachments' => [],
        'tool_calls' => [],
        'tool_results' => [],
        'usage' => [],
        'meta' => [],
    ]);

    $this->actingAs($user);

    $component = Livewire::test(WritingStudio::class);
    $conversationWithPreview = $component->instance()->conversations()->firstWhere('id', $conversation->id);

    expect($component->instance()->conversationLabel($conversationWithPreview))
        ->toBe('Write a post about MySQL restore drills for small teams.');
});

test('writing studio tools expose strict-compatible schemas', function () {
    $factory = new JsonSchemaTypeFactory;

    $toolSchemas = [
        SearchPostsTool::class => ['query', 'include_drafts', 'limit'],
        GetPostTool::class => ['id', 'slug'],
        CreateDraftPostTool::class => ['title', 'excerpt', 'content', 'slug'],
        UpdateDraftPostTool::class => ['post_id', 'title', 'excerpt', 'content'],
    ];

    foreach ($toolSchemas as $toolClass => $expectedKeys) {
        $schema = (new ObjectSchema(app($toolClass)->schema($factory)))->toSchema();

        expect(array_keys($schema['properties']))->toBe($expectedKeys)
            ->and($schema['required'])->toBe($expectedKeys);
    }
});

test('writing studio agent instructions require typed code fences and genericized sensitive details', function () {
    $instructions = (string) (new WritingStudioAgent)->instructions();

    expect($instructions)
        ->toContain('always include an explicit language tag')
        ->toContain('do not repeat them verbatim')
        ->toContain('generic placeholders or generic instructions');
});

test('users can rename their conversations', function () {
    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'New Conversation',
    ]);

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->call('beginConversationRename', $conversation->id)
        ->set('editingConversationTitle', 'MySQL restore article ideas')
        ->call('saveConversationRename')
        ->assertSet('editingConversationId', null)
        ->assertSet('editingConversationTitle', null);

    expect($conversation->fresh()->title)->toBe('MySQL restore article ideas');
});

test('sending a message creates and continues a conversation', function () {
    $user = User::factory()->create();

    WritingStudioAgent::fake([
        'MySQL backup ideas',
        'Frame it around restore confidence instead of backup setup.',
        'Use @post with your operations-heavy posts for tone.',
    ])->preventStrayPrompts();

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->set('composerMessage', 'I want to write about mysql backups.')
        ->call('sendMessage')
        ->assertSet('composerMessage', null)
        ->set('composerMessage', 'How should I frame it?')
        ->call('sendMessage');

    expect(AgentConversation::query()->where('user_id', $user->id)->count())->toBe(1)
        ->and(AgentConversationMessage::query()->count())->toBe(4);
});

test('the composer stores markdown files against the conversation', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');
    Files::fake();

    $user = User::factory()->create();

    WritingStudioAgent::fake([
        'Internal docs article',
        'Use the attached documentation as source material.',
    ])->preventStrayPrompts();

    $upload = UploadedFile::fake()->createWithContent('ops-notes.md', "# Notes\n\nFocus on restore drills.");

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->set('composerMessage', 'Turn this into a blog idea.')
        ->set('composerUploads', [$upload])
        ->call('sendMessage')
        ->assertSet('composerUploads', []);

    expect(WritingStudioAttachment::query()->count())->toBe(1);
    expect(WritingStudioAttachment::query()->firstOrFail()->provider_file_id)->not->toBeNull();
    Storage::disk('public')->assertExists(WritingStudioAttachment::query()->firstOrFail()->storage_path);
});

test('the composer accepts shell scripts as text attachments', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');
    Files::fake();

    $user = User::factory()->create();

    WritingStudioAgent::fake([
        'Shell script article',
        'Use the script as source material for the post.',
    ])->preventStrayPrompts();

    $upload = UploadedFile::fake()->createWithContent('deploy.sh', "#!/usr/bin/env bash\n\necho 'deploying'\n");

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->set('composerMessage', 'Turn this script into a blog idea.')
        ->set('composerUploads', [$upload])
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSet('composerUploads', []);

    expect(WritingStudioAttachment::query()->count())->toBe(1)
        ->and(WritingStudioAttachment::query()->firstOrFail()->original_name)->toBe('deploy.sh')
        ->and(WritingStudioAttachment::query()->firstOrFail()->provider_file_id)->not->toBeNull();
});

test('the composer accepts images as attachments', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');
    Files::fake();

    $user = User::factory()->create();

    WritingStudioAgent::fake([
        'Graph-driven article',
        'Use the attached chart to explain the main trend.',
    ])->preventStrayPrompts();

    $upload = UploadedFile::fake()->image('traffic-graph.png');

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->set('composerMessage', 'Turn this graph into a blog outline.')
        ->set('composerUploads', [$upload])
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSet('composerUploads', []);

    expect(WritingStudioAttachment::query()->count())->toBe(1)
        ->and(WritingStudioAttachment::query()->firstOrFail()->original_name)->toBe('traffic-graph.png')
        ->and(WritingStudioAttachment::query()->firstOrFail()->provider_file_id)->not->toBeNull();
});

test('markdown attachments are normalized to text plain for ai prompts', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');

    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'Markdown chat',
    ]);

    Storage::disk('public')->put('writing-studio/'.$conversation->id.'/notes.md', "# Notes\n\nRestore drills.");

    $attachment = WritingStudioAttachment::query()->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
        'original_name' => 'notes.md',
        'storage_disk' => 'public',
        'storage_path' => 'writing-studio/'.$conversation->id.'/notes.md',
        'mime_type' => 'text/markdown',
    ]);

    $document = $attachment->toAiAttachment();

    expect($document)->toBeInstanceOf(Base64Document::class)
        ->and($document->mime)->toBe('text/plain')
        ->and($document->name())->toBe('notes.md');
});

test('shell script attachments are normalized to text plain for ai prompts', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');

    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'Shell script chat',
    ]);

    Storage::disk('public')->put('writing-studio/'.$conversation->id.'/deploy.sh', "#!/usr/bin/env bash\n\necho 'deploy'\n");

    $attachment = WritingStudioAttachment::query()->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
        'original_name' => 'deploy.sh',
        'storage_disk' => 'public',
        'storage_path' => 'writing-studio/'.$conversation->id.'/deploy.sh',
        'mime_type' => 'application/x-sh',
    ]);

    $document = $attachment->toAiAttachment();

    expect($document)->toBeInstanceOf(Base64Document::class)
        ->and($document->mime)->toBe('text/plain')
        ->and($document->name())->toBe('deploy.sh');
});

test('image attachments are kept as image attachments for ai prompts', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');

    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'Image chat',
    ]);

    $image = base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wn7i4sAAAAASUVORK5CYII=',
        true,
    );

    Storage::disk('public')->put('writing-studio/'.$conversation->id.'/graph.png', $image);

    $attachment = WritingStudioAttachment::query()->create([
        'conversation_id' => $conversation->id,
        'user_id' => $user->id,
        'original_name' => 'graph.png',
        'storage_disk' => 'public',
        'storage_path' => 'writing-studio/'.$conversation->id.'/graph.png',
        'mime_type' => 'image/png',
    ]);

    $imageAttachment = $attachment->toAiAttachment();

    expect($imageAttachment)->toBeInstanceOf(Base64Image::class)
        ->and($imageAttachment->mime)->toBe('image/png')
        ->and($imageAttachment->name())->toBe('graph.png');
});

test('starting a fresh conversation clears the active chat state', function () {
    $user = User::factory()->create();

    $conversation = AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'Existing chat',
    ]);

    $this->actingAs($user);

    Livewire::test(WritingStudio::class)
        ->set('activeConversationId', $conversation->id)
        ->set('composerMessage', 'Keep this draft idea around')
        ->set('selectedPostIds', [1, 2])
        ->call('startFreshConversation')
        ->assertSet('activeConversationId', null)
        ->assertSet('composerMessage', null)
        ->assertSet('selectedPostIds', []);
});

test('users can delete their conversations and stored attachments', function () {
    config()->set('filesystems.default', 'public');
    Storage::fake('public');
    Files::fake();

    $user = User::factory()->create();

    WritingStudioAgent::fake([
        'Draft a post from these notes.',
        'Here is a first pass.',
    ])->preventStrayPrompts();

    $upload = UploadedFile::fake()->createWithContent('brief.md', "# Brief\n\nFocus on practical restore drills.");

    $this->actingAs($user);

    $component = Livewire::test(WritingStudio::class)
        ->set('composerMessage', 'Draft a post from this brief.')
        ->set('composerUploads', [$upload])
        ->call('sendMessage');

    $conversationId = AgentConversation::query()->where('user_id', $user->id)->sole()->id;
    $attachmentPath = WritingStudioAttachment::query()->where('conversation_id', $conversationId)->sole()->storage_path;

    $component
        ->call('deleteConversation', $conversationId)
        ->assertSet('activeConversationId', null);

    expect(AgentConversation::query()->where('id', $conversationId)->exists())->toBeFalse()
        ->and(AgentConversationMessage::query()->where('conversation_id', $conversationId)->exists())->toBeFalse()
        ->and(WritingStudioAttachment::query()->where('conversation_id', $conversationId)->exists())->toBeFalse();

    Storage::disk('public')->assertMissing($attachmentPath);
});

test('post tools can inspect posts and create or update drafts', function () {
    $publishedPost = Post::factory()->create([
        'title' => 'Practical Tips for Laravel Queue Performance',
        'slug' => 'practical-tips-for-laravel-queue-performance',
        'excerpt' => 'Queue tuning notes.',
        'content' => 'Queue tuning, retries, and worker sizing.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $searchResults = json_decode((string) app(SearchPostsTool::class)->handle(new Request([
        'query' => 'queue performance',
    ])), true, flags: JSON_THROW_ON_ERROR);

    expect($searchResults['matches'][0]['id'])->toBe($publishedPost->id);

    $postDetails = json_decode((string) app(GetPostTool::class)->handle(new Request([
        'id' => $publishedPost->id,
    ])), true, flags: JSON_THROW_ON_ERROR);

    expect($postDetails['slug'])->toBe($publishedPost->slug);

    $draftResults = json_decode((string) app(CreateDraftPostTool::class)->handle(new Request([
        'title' => 'MySQL Backups You Can Actually Restore',
        'excerpt' => 'A restore-first backup guide.',
        'content' => "# MySQL Backups You Can Actually Restore\n\nDraft content.",
    ])), true, flags: JSON_THROW_ON_ERROR);

    $draft = Post::query()->findOrFail($draftResults['post_id']);

    expect($draft->status)->toBe('draft');

    app(UpdateDraftPostTool::class)->handle(new Request([
        'post_id' => $draft->id,
        'content' => "# MySQL Backups You Can Actually Restore\n\nUpdated content.",
    ]));

    expect($draft->fresh()->content)->toContain('Updated content.');
});
