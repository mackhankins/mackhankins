<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'type' => 'general',
        ];
    }

    public function tech(): static
    {
        return $this->state(fn () => ['type' => 'tech']);
    }

    public function topic(): static
    {
        return $this->state(fn () => ['type' => 'topic']);
    }
}
