<?php

namespace App\Filament\Pages;

use App\Ai\Agents\WritingStudioAgent;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\Post;
use App\Models\WritingStudioAttachment;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Ai\Files;
use Laravel\Ai\Files\Document;
use Laravel\Ai\Files\File as AiFile;
use Laravel\Ai\Files\Image;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

class WritingStudio extends Page
{
    use WithFileUploads;

    protected const ATTACHMENTS_DIRECTORY = 'writing-studio';

    protected const TEXT_ATTACHMENT_EXTENSIONS = ['md', 'markdown', 'txt', 'sh'];

    protected const IMAGE_ATTACHMENT_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    protected const TEXT_ATTACHMENT_MIME_TYPES = [
        'text/markdown',
        'text/x-markdown',
        'application/x-markdown',
        'text/plain',
        'application/x-sh',
        'application/x-shellscript',
    ];

    protected const IMAGE_ATTACHMENT_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    protected string $view = 'filament.pages.writing-studio';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Writing Studio';

    public ?string $activeConversationId = null;

    public ?string $composerMessage = null;

    public ?string $postReferenceSearch = null;

    public ?string $editingConversationId = null;

    public ?string $editingConversationTitle = null;

    public mixed $composerUpload = null;

    /**
     * @var array<int, int|string>
     */
    public array $selectedPostIds = [];

    /**
     * @var array<int, UploadedFile>
     */
    public array $composerUploads = [];

    public function mount(): void
    {
        $this->activeConversationId = $this->conversations()->first()?->id;

        if (filled($this->activeConversationId)) {
            $this->dispatch('writing-studio-scroll-bottom');
        }
    }

    public function startFreshConversation(): void
    {
        $this->activeConversationId = null;
        $this->composerMessage = null;
        $this->composerUpload = null;
        $this->postReferenceSearch = null;
        $this->editingConversationId = null;
        $this->editingConversationTitle = null;
        $this->selectedPostIds = [];
        $this->composerUploads = [];
        $this->resetErrorBag();

        $this->dispatch('writing-studio-focus-composer');
    }

    public function openConversation(string $conversationId): void
    {
        $this->activeConversationId = $conversationId;
        $this->composerMessage = null;
        $this->composerUpload = null;
        $this->postReferenceSearch = null;
        $this->editingConversationId = null;
        $this->editingConversationTitle = null;
        $this->selectedPostIds = [];
        $this->composerUploads = [];

        $this->dispatch('writing-studio-scroll-bottom');
    }

    public function beginConversationRename(string $conversationId): void
    {
        $conversation = AgentConversation::query()
            ->where('id', $conversationId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $conversation) {
            return;
        }

        $this->editingConversationId = $conversation->id;
        $this->editingConversationTitle = $conversation->title;
    }

    public function cancelConversationRename(): void
    {
        $this->editingConversationId = null;
        $this->editingConversationTitle = null;
        $this->resetErrorBag('editingConversationTitle');
    }

    public function saveConversationRename(): void
    {
        $this->validate([
            'editingConversationTitle' => ['required', 'string', 'max:120'],
        ]);

        $conversation = AgentConversation::query()
            ->where('id', $this->editingConversationId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $conversation) {
            $this->cancelConversationRename();

            return;
        }

        $conversation->update([
            'title' => trim((string) $this->editingConversationTitle),
        ]);

        $this->cancelConversationRename();

        Notification::make()
            ->title('Conversation renamed')
            ->success()
            ->send();
    }

    public function deleteConversation(string $conversationId): void
    {
        $conversation = AgentConversation::query()
            ->where('id', $conversationId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $conversation) {
            return;
        }

        $attachments = WritingStudioAttachment::query()
            ->where('conversation_id', $conversation->id)
            ->get();

        foreach ($attachments as $attachment) {
            Storage::disk($attachment->storageDisk())->delete($attachment->storage_path);

            if (filled($attachment->provider_file_id)) {
                Files::delete($attachment->provider_file_id);
            }
        }

        WritingStudioAttachment::query()
            ->where('conversation_id', $conversation->id)
            ->delete();

        AgentConversationMessage::query()
            ->where('conversation_id', $conversation->id)
            ->delete();

        $conversation->delete();

        if ($this->activeConversationId === $conversationId) {
            $this->startFreshConversation();
            $this->activeConversationId = $this->conversations()->first()?->id;
        }

        if ($this->editingConversationId === $conversationId) {
            $this->cancelConversationRename();
        }

        Notification::make()
            ->title('Conversation deleted')
            ->success()
            ->send();
    }

    public function selectPostReference(int $postId): void
    {
        if (in_array($postId, array_map('intval', $this->selectedPostIds), true)) {
            return;
        }

        $this->selectedPostIds[] = $postId;
    }

    public function removeSelectedPostReference(int $postId): void
    {
        $this->selectedPostIds = array_values(array_filter(
            $this->selectedPostIds,
            fn (int|string $selectedPostId): bool => (int) $selectedPostId !== $postId,
        ));
    }

    public function updatedComposerUpload(): void
    {
        if (! $this->composerUpload instanceof UploadedFile) {
            return;
        }

        $this->validate([
            'composerUpload' => ['file', 'mimes:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
        ]);

        $this->composerUploads[] = $this->composerUpload;
        $this->composerUpload = null;

        $this->dispatch('writing-studio-reset-upload-input');
    }

    public function removeComposerUpload(int $index): void
    {
        unset($this->composerUploads[$index]);

        $this->composerUploads = array_values($this->composerUploads);
    }

    public function sendMessage(): void
    {
        $this->validate([
            'composerMessage' => ['nullable', 'string'],
            'composerUpload' => ['nullable', 'file', 'mimes:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
            'composerUploads.*' => ['file', 'mimes:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
            'selectedPostIds' => ['array'],
        ]);

        if (blank($this->composerMessage) && $this->composerUploads === []) {
            $this->addError('composerMessage', 'Write a message or attach a document.');

            return;
        }

        $agent = new WritingStudioAgent;
        $prompt = $this->composePrompt();
        $freshUploadAttachments = $this->storeUploadsWithProvider();
        $attachments = [
            ...$this->persistentAttachmentsForCurrentConversation(),
            ...$freshUploadAttachments,
        ];

        $response = filled($this->activeConversationId)
            ? $agent->continue($this->activeConversationId, as: auth()->user())->prompt($prompt, attachments: $attachments)
            : $agent->forUser(auth()->user())->prompt($prompt, attachments: $attachments);

        $this->activeConversationId = $response->conversationId;
        $this->persistUploadedFiles($response->conversationId, $freshUploadAttachments);

        $this->composerMessage = null;
        $this->composerUpload = null;
        $this->postReferenceSearch = null;
        $this->selectedPostIds = [];
        $this->composerUploads = [];

        $this->dispatch('writing-studio-scroll-bottom');

        Notification::make()
            ->title('Message sent')
            ->success()
            ->send();
    }

    #[Computed]
    public function conversations(): Collection
    {
        return AgentConversation::query()
            ->addSelect([
                'first_prompt_preview' => AgentConversationMessage::query()
                    ->select('content')
                    ->whereColumn('conversation_id', 'agent_conversations.id')
                    ->where('role', 'user')
                    ->oldest('created_at')
                    ->limit(1),
            ])
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->get();
    }

    #[Computed]
    public function activeMessages(): Collection
    {
        if (! filled($this->activeConversationId)) {
            return collect();
        }

        return AgentConversationMessage::query()
            ->where('conversation_id', $this->activeConversationId)
            ->orderBy('created_at')
            ->get();
    }

    #[Computed]
    public function activeAttachments(): Collection
    {
        if (! filled($this->activeConversationId)) {
            return collect();
        }

        return WritingStudioAttachment::query()
            ->where('conversation_id', $this->activeConversationId)
            ->latest()
            ->get();
    }

    #[Computed]
    public function availablePostReferences(): Collection
    {
        return Post::query()
            ->published()
            ->when(
                $this->selectedPostIds !== [],
                fn ($query) => $query->whereNotIn('id', array_map('intval', $this->selectedPostIds)),
            )
            ->when(
                filled($this->postReferenceSearch),
                fn ($query) => $query->where(function ($builder): void {
                    $builder
                        ->where('title', 'like', '%'.$this->postReferenceSearch.'%')
                        ->orWhere('excerpt', 'like', '%'.$this->postReferenceSearch.'%');
                })
            )
            ->latest('published_at')
            ->limit(8)
            ->get(['id', 'title', 'excerpt', 'published_at']);
    }

    #[Computed]
    public function selectedPostReferences(): Collection
    {
        if ($this->selectedPostIds === []) {
            return collect();
        }

        return Post::query()
            ->whereIn('id', array_map('intval', $this->selectedPostIds))
            ->get(['id', 'title']);
    }

    /**
     * @return array<int, AiFile>
     */
    private function persistentAttachmentsForCurrentConversation(): array
    {
        if (! filled($this->activeConversationId)) {
            return [];
        }

        return WritingStudioAttachment::query()
            ->where('conversation_id', $this->activeConversationId)
            ->get()
            ->map(fn (WritingStudioAttachment $attachment): AiFile => $attachment->toAiAttachment())
            ->all();
    }

    private function composePrompt(): string
    {
        $message = trim((string) $this->composerMessage);
        $message = $message !== '' ? $message : 'Use the attached document as writing context.';

        if ($this->selectedPostIds === []) {
            return $message;
        }

        $posts = Post::query()
            ->whereIn('id', array_map('intval', $this->selectedPostIds))
            ->get(['id', 'title', 'slug', 'excerpt', 'content']);

        $referenceBlock = $posts->map(function (Post $post): string {
            return implode("\n", [
                "Post ID: {$post->id}",
                "Title: {$post->title}",
                "Slug: {$post->slug}",
                'Excerpt: '.($post->excerpt ?: 'None provided.'),
                'Content sample:',
                Str::limit($post->content, 3000),
            ]);
        })->implode("\n\n---\n\n");

        return "{$message}\n\nReferenced posts for this message:\n{$referenceBlock}";
    }

    /**
     * @return array<int, AiFile>
     */
    private function storeUploadsWithProvider(): array
    {
        return collect($this->composerUploads)
            ->map(function (UploadedFile $upload): AiFile {
                $stored = $this->aiAttachmentFromUpload($upload)->put();

                if ($this->isImageUpload($upload)) {
                    return Image::fromId($stored->id)->as($upload->getClientOriginalName());
                }

                return Document::fromId($stored->id)->as($upload->getClientOriginalName());
            })
            ->all();
    }

    /**
     * @param  array<int, AiFile>  $freshUploadAttachments
     */
    private function persistUploadedFiles(string $conversationId, array $freshUploadAttachments): void
    {
        foreach ($this->composerUploads as $index => $upload) {
            $disk = config('filesystems.default');
            $path = $upload->store(self::ATTACHMENTS_DIRECTORY.'/'.$conversationId, $disk);
            $providerFileId = $freshUploadAttachments[$index] instanceof AiFile && method_exists($freshUploadAttachments[$index], 'id')
                ? $freshUploadAttachments[$index]->id()
                : null;

            WritingStudioAttachment::query()->create([
                'conversation_id' => $conversationId,
                'user_id' => auth()->id(),
                'original_name' => $upload->getClientOriginalName(),
                'storage_disk' => $disk,
                'storage_path' => $path,
                'mime_type' => $upload->getClientMimeType(),
                'provider_file_id' => $providerFileId,
            ]);
        }
    }

    private function aiAttachmentFromUpload(UploadedFile $upload): AiFile
    {
        if ($this->isImageUpload($upload)) {
            return Image::fromBase64(
                base64_encode($this->uploadContents($upload)),
                $upload->getClientMimeType() ?: 'application/octet-stream',
            )->as($upload->getClientOriginalName());
        }

        $mimeType = in_array($upload->getClientMimeType(), self::TEXT_ATTACHMENT_MIME_TYPES, true) ||
            Str::endsWith(Str::lower($upload->getClientOriginalName()), ['.md', '.markdown', '.txt', '.sh'])
            ? 'text/plain'
            : ($upload->getClientMimeType() ?: 'application/octet-stream');

        return Document::fromString($this->uploadContents($upload), $mimeType)
            ->as($upload->getClientOriginalName());
    }

    /**
     * @return array<int, string>
     */
    private function supportedAttachmentExtensions(): array
    {
        return [
            ...self::TEXT_ATTACHMENT_EXTENSIONS,
            ...self::IMAGE_ATTACHMENT_EXTENSIONS,
        ];
    }

    private function isImageUpload(UploadedFile $upload): bool
    {
        return in_array($upload->getClientMimeType(), self::IMAGE_ATTACHMENT_MIME_TYPES, true) ||
            Str::endsWith(Str::lower($upload->getClientOriginalName()), ['.jpg', '.jpeg', '.png', '.webp', '.gif']);
    }

    private function uploadContents(UploadedFile $upload): string
    {
        return method_exists($upload, 'get')
            ? $upload->get()
            : $upload->getContent();
    }

    public function conversationLabel(AgentConversation $conversation): string
    {
        $title = trim((string) $conversation->title);
        $preview = trim((string) $conversation->getAttribute('first_prompt_preview'));

        if ($title !== '' && ! str($title)->lower()->is('new conversation')) {
            return Str::limit($title, 56);
        }

        if ($preview !== '') {
            return Str::limit($preview, 56);
        }

        return 'Untitled chat';
    }
}
