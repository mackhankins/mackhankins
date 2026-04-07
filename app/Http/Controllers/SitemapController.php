<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create(route('projects.index'))->setPriority(0.8)->setChangeFrequency('weekly'))
            ->add(Url::create(route('blog.index'))->setPriority(0.8)->setChangeFrequency('daily'))
            ->add(Url::create(url('/#about'))->setPriority(0.5)->setChangeFrequency('monthly'));

        Project::query()
            ->where('status', 'published')
            ->get()
            ->each(fn (Project $project) => $sitemap->add(
                Url::create(route('projects.show', $project))
                    ->setLastModificationDate($project->updated_at)
                    ->setChangeFrequency('monthly')
            ));

        Post::query()
            ->published()
            ->get()
            ->each(fn (Post $post) => $sitemap->add(
                Url::create(route('blog.show', $post))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency('monthly')
            ));

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
