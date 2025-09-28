<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('-1 month', '+2 months'));
        $end = (clone $start)->addHours($this->faker->numberBetween(1, 4));

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'start_at' => $start,
            'end_at' => $this->faker->boolean(80) ? $end : null,
            'location' => $this->faker->city() . ', ' . $this->faker->stateAbbr(),
        ];
    }
}
