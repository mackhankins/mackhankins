<?php

namespace App\Models;

use Database\Factories\WritingStudioAttachmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Ai\Files\Document;

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

    public function toDocumentAttachment(): Document
    {
        if (filled($this->provider_file_id)) {
            return Document::fromId($this->provider_file_id)->as($this->original_name);
        }

        return Document::fromString(
            Storage::disk($this->storageDisk())->get($this->storage_path),
            $this->aiMimeType(),
        )->as($this->original_name);
    }

    public function storageDisk(): string
    {
        return $this->storage_disk ?: config('filesystems.default', Storage::getDefaultDriver());
    }

    public function aiMimeType(): string
    {
        if (
            in_array($this->mime_type, ['text/markdown', 'text/x-markdown', 'application/x-markdown'], true) ||
            Str::endsWith(Str::lower($this->original_name), ['.md', '.markdown', '.txt'])
        ) {
            return 'text/plain';
        }

        return $this->mime_type ?: 'application/octet-stream';
    }
}
