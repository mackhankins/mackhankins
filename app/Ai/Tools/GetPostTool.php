<?php

namespace App\Ai\Tools;

use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetPostTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Fetch a specific existing blog post by ID or slug when deeper context is needed.';
    }

    public function handle(Request $request): Stringable|string
    {
        $post = Post::query()
            ->when(
                $request->filled('id'),
                fn ($builder) => $builder->whereKey((int) $request->integer('id')),
                fn ($builder) => $builder->where('slug', (string) $request->string('slug')),
            )
            ->first();

        if (! $post) {
            return json_encode(['error' => 'Post not found.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        return json_encode([
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status,
            'published_at' => $post->published_at?->toAtomString(),
            'excerpt' => $post->excerpt,
            'content' => Str::limit($post->content, 12000),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->nullable()->required(),
            'slug' => $schema->string()->nullable()->required(),
        ];
    }
}
