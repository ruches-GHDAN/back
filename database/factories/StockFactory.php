<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word,
            'nbToClean' => $this->faker->numberBetween(1, 50),
            'nbClean' => $this->faker->numberBetween(0, 50),
            'nbInUse' => $this->faker->numberBetween(0, 50),
        ];
    }
}
