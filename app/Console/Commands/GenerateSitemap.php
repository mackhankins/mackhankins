<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate the sitemap.xml file';

    public function handle(): void
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create(route('projects.index'))->setPriority(0.8)->setChangeFrequency('weekly'))
            ->add(Url::create(route('blog.index'))->setPriority(0.8)->setChangeFrequency('daily'))
            ->add(Url::create(route('about'))->setPriority(0.5)->setChangeFrequency('monthly'));

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

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
