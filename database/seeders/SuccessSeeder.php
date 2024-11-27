<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Success;

class SuccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $successes = [
            [
                'name' => 'Premiers pas',
                'description' => 'Créer un compte sur Gamehub.'
            ],
            [
                'name' => 'Premier jeu',
                'description' => 'Ajouter un jeu favori.'
            ],
            [
                'name' => 'Gamer passionné',
                'description' => 'Ajouter 5 jeux favoris.'
            ],
            [
                'name' => 'Collectionneur de jeux',
                'description' => 'Ajouter 10 jeux favoris.'
            ],
            [
                'name' => 'Première participation',
                'description' => 'Participer à un événement.'
            ],
            [
                'name' => 'Organisateur débutant',
                'description' => 'Créer un événement.'
            ],
            [
                'name' => 'Organisateur confirmé',
                'description' => 'Créer 5 événements.'
            ],
            [
                'name' => 'Organisateur expert',
                'description' => 'Créer 10 événements.'
            ],
            [
                'name' => 'Vétéran des événements',
                'description' => 'Participer à 10 événements.'
            ],
            [
                'name' => 'Première réplique',
                'description' => 'Répondre à un sujet dans le forum.'
            ],
            [
                'name' => 'Animateur',
                'description' => 'Poster 10 messages sur le forum.'
            ],
            [
                'name' => 'Communicateur aguerri',
                'description' => 'Poster 50 messages sur le forum.'
            ],
            [
                'name' => 'Premier sujet',
                'description' => 'Créer votre premier sujet.'
            ],
            [
                'name' => 'Créateur débutant',
                'description' => 'Créer 3 sujets.'
            ],
            [
                'name' => 'Maître de la discussion',
                'description' => 'Créer 5 sujets.'
            ],
            [
                'name' => 'Grand orateur',
                'description' => 'Créer 15 sujets.'
            ],
            [
                'name' => 'Première réaction',
                'description' => 'Réagir à un message.'
            ],
            [
                'name' => 'Réaction en chaîne',
                'description' => 'Réagir à 20 messages.'
            ],
            [
                'name' => 'Expert en réactions',
                'description' => 'Réagir à 50 messages.'
            ],
            [
                'name' => 'Maître des émotions',
                'description' => 'Réagir à 100 messages.'
            ],
            [
                'name' => 'Collectionneur de succès',
                'description' => 'Débloquer 10 succès.'
            ],
            [
                'name' => 'Légende',
                'description' => 'Débloquer tous les succès.'
            ],
        ];

        foreach ($successes as $success) {
            Success::create($success);
        }
    }
}
