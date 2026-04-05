<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'featuredProjects' => Project::query()
                ->where('status', 'published')
                ->orderBy('sort_order')
                ->limit(8)
                ->get(),
            'latestPosts' => Post::query()
                ->published()
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
