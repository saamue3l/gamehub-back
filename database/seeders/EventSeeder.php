<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Database\Factories\EventFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::factory()->count(30)->create();
        foreach ($events as $event) {
            $event->participants()->attach($event->creator);
                $event->participants()->attach(User::inRandomOrder()->where('id', '!=', $event->creator->id)->limit(rand(0, $event->maxPlayers - 1))->get());
        }
    }
}
