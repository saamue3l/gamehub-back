<?php

namespace Database\Factories\RealMultiplayerGames;

class Game
{

    public function __construct(
        public int $id,
        public string $name,
        public int $hype,
        public array $platforms,
        public string $coverUrl,
    )
    {
    }

}
