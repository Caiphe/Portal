<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $password = $this->faker->password() . $this->faker->password();

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->freeEmail(),
            'locations' => $this->faker->randomElements(['ao', 'bf', 'bw', 'cd', 'cf', 'ci', 'cm', 'dz', 'gh', 'ng', 'sz', 'tg', 'tn', 'ug', 'uy', 'za', 'zm'], 2),
            'terms' => true,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }
}