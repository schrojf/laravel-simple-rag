<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Response>
 */
class ResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'entry_id' => Entry::factory(),
            'user_id' => User::factory(),
            'content' => fake()->paragraphs(2, true),
            'mime_type' => 'text/markdown',
            'meta' => null,
        ];
    }
}
