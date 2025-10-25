<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    protected $model = Availability::class;

    public function definition()
    {
        static $dayOfWeekIndex = 0;
        $daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

        return [
            'dayOfWeek' => $daysOfWeek[$dayOfWeekIndex++ % count($daysOfWeek)],
            'morning' => $this->faker->boolean,
            'afternoon' => $this->faker->boolean,
            'evening' => $this->faker->boolean,
            'night' => $this->faker->boolean,
            'userId' => $this->faker->numberBetween(1, 10),
        ];
    }
}
