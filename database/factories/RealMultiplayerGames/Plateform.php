<?php

namespace Database\Factories\RealMultiplayerGames;

class Plateform
{

    /**
     * @var array Manually complete the incomplete platforms from csv file
     */
    private array $missingPlatformsData = array(

    );

    public function __construct(
        public ?string $name = null,
        public ?string $imageUrl = null,
    )
    {
        // TODO : Add the missign data from $missingPlatformsData
    }
}
