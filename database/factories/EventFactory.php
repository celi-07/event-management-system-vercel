<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all()->where('is_organizer', true);

        return [
            'title' => $this->faker->sentence(3),
            'date' => $this->faker->dateTimeBetween('+1 week', '+3 months'),
            'host_id' => $users->random()->id,
            'visitor_count' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['Draft', 'Published']),
            'location' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
