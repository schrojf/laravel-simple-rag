<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\McpLog>
 */
class McpLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => fake()->uuid(),
            'primitive_type' => fake()->randomElement(['tool', 'prompt', 'resource']),
            'primitive_name' => fake()->randomElement([
                'SearchEntriesTool', 'GetEntryTool', 'ListTypesTool',
                'CreateEntryTool', 'AnswerQuestionPrompt', 'EntryResource',
            ]),
            'input' => null,
        ];
    }
}
