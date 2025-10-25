<?php

namespace Database\Factories\RealMultiplayerGames;

use App\Models\Platform;
use Database\Factories\RealMultiplayerGames\Game;
use Database\Factories\RealMultiplayerGames\Plateform;

class GamesCsvReader
{

    private array $unnamedPlatforms = [];
    private array $unpicturedPlatforms = [];
    private array $platforms = [];
    private array $games = [];

    public function __construct()
    {
        $this->readFile('database/factories/RealMultiplayerGames/multiplayer_games.csv');
    }

    private function readFile(string $fileUrl): void
    {
        $header = true;

        if (($handle = fopen($fileUrl, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                        $platformsData = explode('$', $data[3]);
                $platforms = [];

                foreach ($platformsData as $platformData) {
                    $platformData = explode('|', $platformData);
                    $platformName = $platformData[0] ?? null;
                    $platformImage = $platformData[1] ?? null;

                    $platform = new Plateform(
                        name: $platformName,
                        imageUrl: $platformImage
                    );

                    if (isset($platformName) && !isset($this->platforms[$platformName])) {
                                        $this->platforms[$platformName] = $platform;
                    }
                    else if (!isset($platformName)) {
                                        $this->unnamedPlatforms[$platformImage] = $platform;
                    }
                    else if (!isset($platformImage)) {
                                        $this->unpicturedPlatforms[$platformName] = $platform;
                    }

                    $platforms[] = $platform;
                }

                        $this->games[] = new Game(
                    id: (int) $data[0],
                    name: $data[1],
                    hype: (int) $data[2],
                    platforms: $platforms,
                    coverUrl: $data[4]
                );
            }
            fclose($handle);
        }
    }

    /* === GETTERS === */
    public function getGames(): array
    {
        return $this->games;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    public function getUnnamedPlatforms(): array
    {
        return $this->unnamedPlatforms;
    }

    public function getUnpicturedPlatforms(): array
    {
        return $this->unpicturedPlatforms;
    }
}
