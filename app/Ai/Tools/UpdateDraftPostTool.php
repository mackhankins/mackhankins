<?php

namespace App\Ai\Tools;

use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class UpdateDraftPostTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Update an existing draft blog post. Only use this when the user explicitly asks to revise or update a draft.';
    }

    public function handle(Request $request): Stringable|string
    {
        $post = Post::query()
            ->whereKey((int) $request->integer('post_id'))
            ->first();

        if (! $post) {
            return json_encode(['error' => 'No draft post could be found to update.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        if ($post->status !== 'draft') {
            return json_encode(['error' => 'Only draft posts can be updated.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $post->fill(array_filter([
            'title' => $request->filled('title') ? trim((string) $request->string('title')) : null,
            'excerpt' => $request->filled('excerpt') ? trim((string) $request->string('excerpt')) : null,
            'content' => $request->filled('content') ? trim((string) $request->string('content')) : null,
        ], fn (mixed $value): bool => $value !== null));

        if ($request->filled('title')) {
            $post->slug = $this->uniqueSlugFor($post->title, $post->id);
        }

        $post->save();

        return json_encode([
            'updated' => true,
            'post_id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'post_id' => $schema->integer()->required(),
            'title' => $schema->string()->nullable()->required(),
            'excerpt' => $schema->string()->nullable()->required(),
            'content' => $schema->string()->nullable()->required(),
        ];
    }

    private function uniqueSlugFor(string $title, int $exceptPostId): string
    {
        $slugBase = Str::slug($title);
        $baseSlug = filled($slugBase) ? $slugBase : 'article-draft';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Post::query()
                ->where('slug', $slug)
                ->whereKeyNot($exceptPostId)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
