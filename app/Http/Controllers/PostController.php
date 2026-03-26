<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()
            ->published()
            ->with('tags')
            ->latest('published_at');

        $activeTag = null;

        if ($request->filled('tag')) {
            $activeTag = Tag::query()->where('slug', $request->get('tag'))->first();

            if ($activeTag) {
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $activeTag->id));
            }
        }

        return view('blog.index', [
            'posts' => $query->paginate(9)->withQueryString(),
            'activeTag' => $activeTag,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        $post->load('tags');

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
