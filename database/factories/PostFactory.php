<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(rand(4, 8));

        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'content' => $this->generateMarkdownContent(),
            'excerpt' => fake()->paragraph(),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    private function generateMarkdownContent(): string
    {
        $paragraphs = fake()->paragraphs(rand(4, 8));
        $content = '';

        foreach ($paragraphs as $i => $paragraph) {
            if ($i === 2) {
                $content .= '## '.fake()->sentence(4)."\n\n";
            }
            if ($i === 5) {
                $content .= '### '.fake()->sentence(3)."\n\n";
            }
            $content .= $paragraph."\n\n";
        }

        return trim($content);
    }
}
