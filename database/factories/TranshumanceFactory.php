<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transhumance>
 */
class TranshumanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'oldLatitude' => $this->faker->latitude(),
            'oldLongitude' => $this->faker->longitude(),
            'reason' => $this->faker->sentence,
            'date' => $this->faker->date,
        ];
    }
}
