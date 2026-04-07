<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\View\View;

class TagController extends Controller
{
    public function show(Tag $tag): View
    {
        $posts = $tag->posts()
            ->published()
            ->with('tags')
            ->latest('published_at')
            ->get();

        $projects = $tag->projects()
            ->where('status', 'published')
            ->with('tags')
            ->orderBy('sort_order')
            ->get();

        return view('tags.show', [
            'tag' => $tag,
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
