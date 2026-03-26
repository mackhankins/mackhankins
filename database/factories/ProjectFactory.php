<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(rand(2, 4), true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(12),
            'url' => fake()->optional(0.7)->url(),
            'repository_url' => fake()->optional(0.3)->url(),
            'tech_stack' => fake()->randomElements(
                ['Laravel', 'PHP', 'Vue.js', 'React', 'Tailwind CSS', 'Alpine.js', 'Livewire', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'TypeScript'],
                rand(2, 5)
            ),
            'is_featured' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(0, 100),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
