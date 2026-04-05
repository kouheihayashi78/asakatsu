<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WakeUpRecord>
 */
class WakeUpRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recorded_at' => fake()->dateTimeThisMonth(),
            'is_achieved' => fake()->boolean(),
        ];
    }

    /**
     * 達成済みの状態
     */
    public function achieved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_achieved' => true,
        ]);
    }

    /**
     * 未達成の状態
     */
    public function notAchieved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_achieved' => false,
        ]);
    }
}
