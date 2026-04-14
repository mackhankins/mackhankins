<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WritingStudioAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WritingStudioAttachment>
 */
class WritingStudioAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => (string) fake()->uuid(),
            'user_id' => User::factory(),
            'original_name' => 'notes.md',
            'storage_path' => 'writing-studio/notes.md',
            'mime_type' => 'text/markdown',
        ];
    }
}
