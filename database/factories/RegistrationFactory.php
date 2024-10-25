<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['not scheduled', 'scheduled', 'vaccined']);
        return [
            'user_id' => User::factory(),
            'vaccine_center_id'=> VaccineCenter::get()->random()->id,
            // 'scheduled_date' => fake()->time('now', '+1 month'),
            'scheduled_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => $status,
        ];
    }
}
