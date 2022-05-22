<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->unique()->text(10),
            "title" => $this->faker->text(100),
            "content" => $this->faker->text(),
            "timestamp" => $this->faker->dateTimeBetween("-2 minutes","2 minutes")->getTimestamp(),
            "views" => $this->faker->randomDigit()
        ];
    }
}
