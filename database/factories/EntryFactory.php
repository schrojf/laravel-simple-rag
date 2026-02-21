<?php

namespace Database\Factories;

use App\Models\EntryType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type_id' => EntryType::factory(),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'meta' => null,
        ];
    }
}
