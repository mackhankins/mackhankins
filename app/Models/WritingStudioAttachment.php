<?php

namespace App\Models;

use Database\Factories\WritingStudioAttachmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Ai\Files\Document;
use Laravel\Ai\Files\File;
use Laravel\Ai\Files\Image;

class WritingStudioAttachment extends Model
{
    /** @use HasFactory<WritingStudioAttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'original_name',
        'storage_disk',
        'storage_path',
        'mime_type',
        'provider_file_id',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AgentConversation::class, 'conversation_id');
    }

    public function toAiAttachment(): File
    {
        if (filled($this->provider_file_id)) {
            if ($this->isImageAttachment()) {
                return Image::fromId($this->provider_file_id)->as($this->original_name);
            }

            return Document::fromId($this->provider_file_id)->as($this->original_name);
        }

        $contents = Storage::disk($this->storageDisk())->get($this->storage_path);

        if ($this->isImageAttachment()) {
            return Image::fromBase64(
                base64_encode($contents),
                $this->aiMimeType(),
            )->as($this->original_name);
        }

        return Document::fromString($contents, $this->aiMimeType())
            ->as($this->original_name);
    }

    public function storageDisk(): string
    {
        return $this->storage_disk ?: config('filesystems.default', Storage::getDefaultDriver());
    }

    public function aiMimeType(): string
    {
        if ($this->isImageAttachment()) {
            return $this->mime_type ?: 'application/octet-stream';
        }

        if (
            in_array($this->mime_type, ['text/markdown', 'text/x-markdown', 'application/x-markdown', 'text/plain', 'application/x-sh', 'application/x-shellscript'], true) ||
            Str::endsWith(Str::lower($this->original_name), ['.md', '.markdown', '.txt', '.sh'])
        ) {
            return 'text/plain';
        }

        return $this->mime_type ?: 'application/octet-stream';
    }

    public function isImageAttachment(): bool
    {
        return str($this->mime_type)->startsWith('image/') ||
            Str::endsWith(Str::lower($this->original_name), ['.jpg', '.jpeg', '.png', '.webp', '.gif']);
    }
}
