<?php

namespace Database\Factories;

use App\Models\Domain\Joke;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JokeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->numberBetween(1, 100000),
            'type' => fake()->word(),
            'setup' => fake()->sentence(),
            'punchline' => fake()->sentence(),
        ];
    }

    public function sequenced(): Factory
    {
        return $this->state()
            ->afterMaking(function (Joke $joke) {
                $joke->type = 'programming';
                $joke->setup = 'setup ' . $joke->id;
                $joke->punchline = 'punchline ' . $joke->id;
            });
    }
}
