<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $events = Event::all();
        $users = User::all();

        return [
            'event_id' => $events->random()->id,
            'invitee_id' => $users->random()->id,
            'status' => $this->faker->randomElement(['Pending','Accepted','Declined']),
            'sent_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'responded_at' => null,
        ];
    }
}
