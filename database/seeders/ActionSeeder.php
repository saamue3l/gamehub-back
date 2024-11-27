<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Action;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $actions = [
            [
                'actionType' => 'CREATE_EVENT',
                'xpEarned' => 20,
            ],
            [
                'actionType' => 'JOIN_EVENT',
                'xpEarned' => 10,
            ],
            [
                'actionType' => 'CREATE_TOPIC',
                'xpEarned' => 15,
            ],
            [
                'actionType' => 'POST_MESSAGE',
                'xpEarned' => 5,
            ],
            [
                'actionType' => 'REACT_TO_MESSAGE',
                'xpEarned' => 2,
            ],
            [
                'actionType' => 'ADD_GAME',
                'xpEarned' => 8,
            ],
            [
                'actionType' => 'UPDATE_AVAILABILITY',
                'xpEarned' => 5,
            ],
            [
                'actionType' => 'UPDATE_ALIAS',
                'xpEarned' => 5,
            ],
            [
                'actionType' => 'GET_SUCCESS',
                'xpEarned' => 50,
            ],
        ];

        foreach ($actions as $action) {
            Action::create($action);
        }
    }
}
