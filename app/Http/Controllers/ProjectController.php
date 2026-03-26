<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()
            ->where('status', 'published')
            ->with('tags')
            ->orderBy('sort_order');

        $activeTag = null;

        if ($request->filled('tag')) {
            $activeTag = Tag::query()->where('slug', $request->get('tag'))->first();

            if ($activeTag) {
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $activeTag->id));
            }
        }

        return view('projects.index', [
            'projects' => $query->get(),
            'activeTag' => $activeTag,
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        $project->load('tags');

        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
