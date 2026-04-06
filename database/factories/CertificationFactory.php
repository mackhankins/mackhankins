<?php

namespace Database\Factories;

use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'issuer' => fake()->company(),
            'credential_url' => fake()->optional()->url(),
            'icon' => null,
            'earned_at' => fake()->dateTimeBetween('-2 years'),
            'sort_order' => 0,
        ];
    }
}
