<?php

namespace App\Ai\Tools;

use App\Models\Post;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchPostsTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search existing blog posts by title, excerpt, or content to check overlap, tone, or prior coverage.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = trim((string) $request->string('query'));
        $includeDrafts = $request->boolean('include_drafts');
        $limit = min(max((int) $request->integer('limit', 5), 1), 10);

        $posts = Post::query()
            ->when(! $includeDrafts, fn ($builder) => $builder->published())
            ->where(function ($builder) use ($query): void {
                $builder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->latest('updated_at')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'excerpt', 'status', 'published_at']);

        if ($posts->isEmpty()) {
            return json_encode([
                'query' => $query,
                'matches' => [],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        return json_encode([
            'query' => $query,
            'matches' => $posts->map(fn (Post $post): array => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
                'published_at' => $post->published_at?->toAtomString(),
                'excerpt' => Str::limit($post->excerpt ?: strip_tags($post->content), 220),
            ])->all(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->required(),
            'include_drafts' => $schema->boolean()->nullable()->required(),
            'limit' => $schema->integer()->min(1)->max(10)->nullable()->required(),
        ];
    }
}
