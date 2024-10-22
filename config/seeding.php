<?php

return [
    'games' => [
        // Number of games to seed in different environments
        'production_count' => env('SEED_GAMES_PRODUCTION_COUNT', null), // null means use all CSV data
        'local_count' => env('SEED_GAMES_LOCAL_COUNT', 50),
    ],
];
