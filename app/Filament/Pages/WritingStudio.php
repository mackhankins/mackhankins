<?php

namespace App\Filament\Pages;

use App\Ai\Agents\WritingStudioAgent;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\Post;
use App\Models\WritingStudioAttachment;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laravel\Ai\Files;
use Laravel\Ai\Files\Document;
use Laravel\Ai\Files\File as AiFile;
use Laravel\Ai\Files\Image;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
use Throwable;

class WritingStudio extends Page
{
    use WithFileUploads;

    protected const ATTACHMENTS_DIRECTORY = 'writing-studio';

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

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
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
        if (! $this->ownsConversation($conversationId)) {
            $this->startFreshConversation();

            return;
        }

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
                rescue(
                    fn (): null => Files::delete($attachment->provider_file_id),
                    report: true,
                );
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
        if (! Post::query()->published()->whereKey($postId)->exists()) {
            return;
        }

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
            'composerUpload' => ['file', 'extensions:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
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
            'composerUpload' => ['nullable', 'file', 'extensions:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
            'composerUploads.*' => ['file', 'extensions:'.implode(',', $this->supportedAttachmentExtensions()), 'max:4096'],
            'selectedPostIds' => ['array'],
        ]);

        if (blank($this->composerMessage) && $this->composerUploads === []) {
            $this->addError('composerMessage', 'Write a message or attach a document.');

            return;
        }

        $activeConversationId = $this->resolveActiveConversationId();
        $agent = new WritingStudioAgent;
        $prompt = $this->composePrompt();
        $freshUploadAttachments = [];

        try {
            $freshUploadAttachments = $this->storeUploadsWithProvider();
            $attachments = [
                ...$this->persistentAttachmentsForConversation($activeConversationId),
                ...$freshUploadAttachments,
            ];

            $response = filled($activeConversationId)
                ? $agent->continue($activeConversationId, as: auth()->user())->prompt($prompt, attachments: $attachments)
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
        } catch (Throwable $exception) {
            $this->cleanupFreshUploadAttachments($freshUploadAttachments);

            report($exception);

            Notification::make()
                ->title('Message failed')
                ->body('The writing studio could not send that message. Try again in a moment.')
                ->danger()
                ->send();
        }
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
        $activeConversationId = $this->resolveActiveConversationId();

        if (! filled($activeConversationId)) {
            return collect();
        }

        return AgentConversationMessage::query()
            ->where('conversation_id', $activeConversationId)
            ->orderBy('created_at')
            ->get();
    }

    #[Computed]
    public function activeAttachments(): Collection
    {
        $activeConversationId = $this->resolveActiveConversationId();

        if (! filled($activeConversationId)) {
            return collect();
        }

        return WritingStudioAttachment::query()
            ->where('conversation_id', $activeConversationId)
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
            ->published()
            ->whereIn('id', array_map('intval', $this->selectedPostIds))
            ->get(['id', 'title']);
    }

    public function attachmentAcceptAttribute(): string
    {
        return implode(',', [
            ...$this->prefixedExtensions($this->supportedAttachmentExtensions()),
            ...$this->textAttachmentMimeTypes(),
            ...$this->imageAttachmentMimeTypes(),
        ]);
    }

    public function renderMessageContent(string $content): Htmlable
    {
        $segments = preg_split('/```mermaid[ \t]*\r?\n(.*?)```/is', $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($segments === false) {
            return new HtmlString((string) Str::markdown($content, ['html_input' => 'strip', 'allow_unsafe_links' => false]));
        }

        $html = collect($segments)
            ->values()
            ->map(function (string $segment, int $index): string {
                if ($index % 2 === 1) {
                    return $this->renderMermaidBlock($segment);
                }

                if (blank(trim($segment))) {
                    return '';
                }

                return (string) Str::markdown($segment, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
            })
            ->implode('');

        return new HtmlString($html);
    }

    /**
     * @return array<int, AiFile>
     */
    private function persistentAttachmentsForConversation(?string $conversationId): array
    {
        if (! filled($conversationId)) {
            return [];
        }

        return WritingStudioAttachment::query()
            ->where('conversation_id', $conversationId)
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
            ->published()
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

    private function renderMermaidBlock(string $definition): string
    {
        $definition = trim($definition);

        if ($definition === '') {
            return '';
        }

        $hash = sha1($definition);
        $escapedDefinition = e($definition);

        return <<<HTML
            <div data-writing-studio-mermaid-block class="my-6 rounded-[1.5rem] border border-gray-200 bg-gray-50/80 p-4 dark:border-white/10 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Mermaid diagram</div>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            x-on:click="downloadMermaidSvg(\$el)"
                            class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-1.5 text-[11px] font-medium uppercase tracking-[0.16em] text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-300 dark:hover:bg-white/[0.08]"
                        >
                            Download SVG
                        </button>
                        <div class="text-[11px] text-gray-400 dark:text-gray-500">Preview + source</div>
                    </div>
                </div>
                <div wire:replace class="mt-3 overflow-hidden rounded-[1.25rem] border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-[#10151b]">
                    <div
                        data-writing-studio-mermaid
                        data-mermaid-hash="{$hash}"
                        class="writing-studio-mermaid-preview overflow-x-auto text-sm text-gray-500 dark:text-gray-400"
                    >
                        <div class="rounded-xl border border-dashed border-gray-200 px-4 py-6 text-center dark:border-white/10">
                            Rendering diagram...
                        </div>
                    </div>
                    <pre hidden data-mermaid-source>{$escapedDefinition}</pre>
                </div>
                <details class="mt-3 group">
                    <summary class="cursor-pointer text-xs font-medium text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        View Mermaid source
                    </summary>
                    <pre class="mt-3 overflow-x-auto rounded-[1.15rem] bg-gray-950 p-4 text-sm text-gray-100"><code>{$escapedDefinition}</code></pre>
                </details>
            </div>
        HTML;
    }

    private function resolveActiveConversationId(): ?string
    {
        if (! filled($this->activeConversationId)) {
            return null;
        }

        if ($this->ownsConversation($this->activeConversationId)) {
            return $this->activeConversationId;
        }

        $this->activeConversationId = null;

        return null;
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

    /**
     * @param  array<int, AiFile>  $freshUploadAttachments
     */
    private function cleanupFreshUploadAttachments(array $freshUploadAttachments): void
    {
        foreach ($freshUploadAttachments as $attachment) {
            if (! method_exists($attachment, 'id')) {
                continue;
            }

            $fileId = $attachment->id();

            if (! filled($fileId)) {
                continue;
            }

            rescue(
                fn (): null => Files::delete($fileId),
                report: true,
            );
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

        $mimeType = in_array($upload->getClientMimeType(), $this->textAttachmentMimeTypes(), true) ||
            Str::endsWith(Str::lower($upload->getClientOriginalName()), $this->textAttachmentSuffixes())
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
            ...$this->textAttachmentExtensions(),
            ...$this->imageAttachmentExtensions(),
        ];
    }

    private function isImageUpload(UploadedFile $upload): bool
    {
        return in_array($upload->getClientMimeType(), $this->imageAttachmentMimeTypes(), true) ||
            Str::endsWith(Str::lower($upload->getClientOriginalName()), $this->prefixedExtensions($this->imageAttachmentExtensions()));
    }

    /**
     * @return array<int, string>
     */
    private function textAttachmentSuffixes(): array
    {
        return $this->prefixedExtensions($this->textAttachmentExtensions());
    }

    /**
     * @return array<int, string>
     */
    private function textAttachmentExtensions(): array
    {
        return config('writing-studio.validation.attachments.extensions', []);
    }

    /**
     * @return array<int, string>
     */
    private function imageAttachmentExtensions(): array
    {
        return config('writing-studio.classification.images.extensions', []);
    }

    /**
     * @return array<int, string>
     */
    private function textAttachmentMimeTypes(): array
    {
        return config('writing-studio.classification.text.mime_types', []);
    }

    /**
     * @return array<int, string>
     */
    private function imageAttachmentMimeTypes(): array
    {
        return config('writing-studio.classification.images.mime_types', []);
    }

    /**
     * @param  array<int, string>  $extensions
     * @return array<int, string>
     */
    private function prefixedExtensions(array $extensions): array
    {
        return array_map(
            static fn (string $extension): string => '.'.$extension,
            $extensions,
        );
    }

    private function uploadContents(UploadedFile $upload): string
    {
        return method_exists($upload, 'get')
            ? $upload->get()
            : $upload->getContent();
    }

    private function ownsConversation(string $conversationId): bool
    {
        return AgentConversation::query()
            ->where('id', $conversationId)
            ->where('user_id', auth()->id())
            ->exists();
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
