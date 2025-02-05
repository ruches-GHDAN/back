<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hive>
 */
class HiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration' => $this->faker->randomNumber(5),
            'status' => $this->faker->randomElement(['active', 'inactive', 'maintenance']),
            'size' => $this->faker->numberBetween(1, 10),
            'race' => $this->faker->word,
            'queenYear' => $this->faker->year,
            'temperature' => $this->faker->numberBetween(20, 40),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
