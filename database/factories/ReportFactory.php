<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomNumber(4),
            'quantity' => $this->faker->randomNumber(2),
            'date' => $this->faker->date('Y-m-d'),
        ];
    }
}
