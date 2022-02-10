<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->sentence(2);
        $name = substr($name, 0, strlen($name) - 1);
        return [
            'name' => $name,
            'description' => $this->faker->text(),
            'price' => $this->faker->randomNumber(4)
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (\App\Models\Product $user) {
            //
        })->afterCreating(function (\App\Models\Product $product) {
            $product->reports()->saveMany(\App\Models\Report::factory($this->faker->randomNumber(2))->make());
            $product->reportViews()->saveMany(\App\Models\ReportView::factory($this->faker->randomNumber(2))->make());
        });
    }
}
