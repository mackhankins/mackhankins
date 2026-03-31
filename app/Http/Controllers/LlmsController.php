<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class LlmsController extends Controller
{
    public function index(): Response
    {
        $posts = Post::query()->published()->latest('published_at')->get();
        $projects = Project::query()->where('status', 'published')->latest()->get();

        $content = <<<'LLMS'
        # Mack Hankins — Stuff & Things

        > Developer & Creator. Building tools, applications, and systems that solve real problems.

        ## About

        Mack Hankins is a full-stack developer specializing in Laravel, Vue.js, and internal tooling. Most of his work is proprietary internal tools and systems. This site showcases public work and writing.

        ## Sections

        - [Stuff (Projects)]({url}/projects): Portfolio of public projects and tools
        - [Things (Blog)]({url}/blog): Writing about development, tools, and technology
        - [About]({url}/about): Background and skills
        - [RSS Feed]({url}/feed): Atom feed of blog posts

        ## Blog Posts (Things)

        {posts}

        ## Projects (Stuff)

        {projects}

        ## Full Content

        For complete article content, see: [{url}/llms-full.txt]({url}/llms-full.txt)
        LLMS;

        $url = rtrim(config('app.url'), '/');

        $postList = $posts->map(fn (Post $post) => "- [{$post->title}]({$url}/blog/{$post->slug}): {$post->excerpt}")->implode("\n");

        $projectList = $projects->map(fn (Project $project) => "- [{$project->name}]({$url}/projects/{$project->slug}): {$project->short_description}")->implode("\n");

        $content = str_replace(
            ['{url}', '{posts}', '{projects}'],
            [$url, $postList ?: 'No posts published yet.', $projectList ?: 'No projects published yet.'],
            $content,
        );

        return response(trim(preg_replace('/^        /m', '', $content)), 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function full(): Response
    {
        $posts = Post::query()->published()->latest('published_at')->get();
        $url = rtrim(config('app.url'), '/');

        $content = "# Mack Hankins — Full Content\n\n";

        foreach ($posts as $post) {
            $content .= "## {$post->title}\n\n";
            $content .= "URL: {$url}/blog/{$post->slug}\n";
            $content .= "Published: {$post->published_at->toDateString()}\n";
            $content .= "Reading time: {$post->reading_time} min\n\n";
            $content .= strip_tags($post->content)."\n\n---\n\n";
        }

        return response(trim($content), 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
