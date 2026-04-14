<?php

namespace App\Ai\Tools;

use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateDraftPostTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Create a new draft blog post in the posts table. Only use this when the user explicitly asks to create a draft.';
    }

    public function handle(Request $request): Stringable|string
    {
        $title = trim((string) $request->string('title'));
        $post = new Post;
        $post->fill([
            'title' => $title,
            'slug' => $this->uniqueSlugFor($title),
            'excerpt' => trim((string) $request->string('excerpt')),
            'content' => trim((string) $request->string('content')),
            'status' => 'draft',
            'published_at' => null,
        ]);
        $post->save();

        return json_encode([
            'created' => true,
            'post_id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->required(),
            'excerpt' => $schema->string()->required(),
            'content' => $schema->string()->required(),
            'slug' => $schema->string()->nullable()->required(),
        ];
    }

    private function uniqueSlugFor(string $title): string
    {
        $slugBase = Str::slug($title);
        $baseSlug = filled($slugBase) ? $slugBase : 'article-draft';
        $slug = $baseSlug;
        $suffix = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
