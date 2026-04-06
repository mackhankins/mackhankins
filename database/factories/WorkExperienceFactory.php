<?php

namespace Database\Factories;

use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkExperience>
 */
class WorkExperienceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-1 year');

        return [
            'company' => fake()->company(),
            'title' => fake()->jobTitle(),
            'description' => fake()->optional()->paragraph(),
            'company_url' => fake()->optional()->url(),
            'start_date' => $startDate,
            'end_date' => fake()->optional(0.7)->dateTimeBetween($startDate),
            'sort_order' => 0,
        ];
    }
}
