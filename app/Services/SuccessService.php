<?php

namespace App\Services;

use App\Models\Success;
use App\Models\Action;
use App\Models\ActionHistory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SuccessService
{
    public function handleAction(Authenticatable $user, string $actionType): array
    {
        $result = [
            'xpGained' => null,
            'newSuccess' => null
        ];

        DB::transaction(function () use ($user, $actionType, &$result) {
            // Gérer le gain d'XP pour l'action
            if ($action = Action::where('actionType', $actionType)->first()) {
                $user->increment('xp', $action->xpEarned);
                $result['xpGained'] = $action->xpEarned;

                // Enregistrer l'action dans ActionHistory
                ActionHistory::updateOrCreate(
                    ['userId' => $user->id, 'actionId' => $action->id],
                    ['actionDate' => Carbon::now()]
                );
            }

            // Vérifier les succès
            if ($newSuccess = $this->checkSuccessesForAction($user, $actionType)) {
                $result['newSuccess'] = [
                    'name' => $newSuccess['success']['name'],
                    'description' => $newSuccess['success']['description']
                ];

                // Ajouter l'XP du succès
                if ($successAction = Action::where('actionType', 'GET_SUCCESS')->first()) {
                    $user->increment('xp', $successAction->xpEarned);
                    // Ajouter l'XP du succès à l'XP déjà gagnée
                    $result['xpGained'] += $successAction->xpEarned;

                    // Enregistrer l'action de succès dans ActionHistory
                    ActionHistory::updateOrCreate(
                        ['userId' => $user->id, 'actionId' => $successAction->id],
                        ['actionDate' => Carbon::now()]
                    );
                }
            }

            $this->checkMultipleSuccesses($user);
        });

        return $result;
    }

    private function checkSuccessesForAction(Authenticatable $user, string $actionType): ?array
    {
        switch ($actionType) {
            case 'ADD_GAME':
                $gamesCount = $user->favoritegames()->count();
                if ($gamesCount === 1) {
                    return $this->unlockSuccess($user, 'Premier jeu');
                }
                if ($gamesCount === 5) {
                    return $this->unlockSuccess($user, 'Gamer passionné');
                }
                if ($gamesCount === 10) {
                    return $this->unlockSuccess($user, 'Collectionneur de jeux');
                }
                break;

            case 'JOIN_EVENT':
                $participationCount = $user->participations()->count();
                if ($participationCount === 1) {
                    return $this->unlockSuccess($user, 'Première participation');
                } elseif ($participationCount === 10) {
                    return $this->unlockSuccess($user, 'Vétéran des événements');
                }
                break;

            case 'CREATE_EVENT':
                $eventCount = $user->createdEvents()->count();
                if ($eventCount === 1) {
                    return $this->unlockSuccess($user, 'Organisateur débutant');
                } elseif ($eventCount === 5) {
                    return $this->unlockSuccess($user, 'Organisateur confirmé');
                } elseif ($eventCount === 10) {
                    return $this->unlockSuccess($user, 'Organisateur expert');
                }
                break;

            case 'POST_MESSAGE':
                $postCount = $user->posts()
                    ->whereNotIn('id', function($query) {
                        $query->select('p1.id')
                            ->from('post as p1')
                            ->join('topic', 'topic.id', '=', 'p1.topicId')
                            ->whereRaw('p1.userId = topic.creatorId')
                            ->whereRaw('p1.creationDate = (
                    SELECT MIN(p2.creationDate)
                    FROM post as p2
                    WHERE p2.topicId = p1.topicId
                )');
                    })
                    ->count();

                if ($postCount === 1) {
                    return $this->unlockSuccess($user, 'Première réplique');
                } elseif ($postCount === 10) {
                    return $this->unlockSuccess($user, 'Animateur');
                } elseif ($postCount === 50) {
                    return $this->unlockSuccess($user, 'Communicateur aguerri');
                }
                break;

            case 'CREATE_TOPIC':
                $topicCount = $user->createdTopics()->count();
                if ($topicCount === 1) {
                    return $this->unlockSuccess($user, 'Premier sujet');
                }
                if ($topicCount === 3) {
                    return $this->unlockSuccess($user, 'Créateur débutant');
                }
                if ($topicCount === 5) {
                    return $this->unlockSuccess($user, 'Maître de la discussion');
                }
                if ($topicCount === 15) {
                    return $this->unlockSuccess($user, 'Grand orateur');
                }
                break;

            case 'REACT_TO_MESSAGE':
                $reactionCount = $user->reactions()->count();
                if ($reactionCount === 1) {
                    return $this->unlockSuccess($user, 'Première réaction');
                }
                if ($reactionCount === 20) {
                    return $this->unlockSuccess($user, 'Réaction en chaîne');
                }
                if ($reactionCount === 50) {
                    return $this->unlockSuccess($user, 'Expert en réactions');
                }
                if ($reactionCount === 100) {
                    return $this->unlockSuccess($user, 'Maître des émotions');
                }
                break;
        }

        return null;
    }

    public function checkMultipleSuccesses(Authenticatable $user): void
    {
        $unlockedCount = $this->getUnlockedCount($user);

        // Vérifier "Collectionneur de succès"
        if ($unlockedCount >= 10) {
            $this->unlockSuccess($user, 'Collectionneur de succès');
        }

        // Vérifier "Légende"
        $totalSuccesses = Success::count();
        if ($unlockedCount === $totalSuccesses - 1) {
            $this->unlockSuccess($user, 'Légende');
        }
    }

    public function unlockSuccess(Authenticatable $user, string $successName): ?array
    {
        $success = Success::where('name', $successName)->first();

        if (!$success || $user->successes()->where('success.id', $success->id)->exists()) {
            return null;
        }

        $user->successes()->attach($success->id, [
            'achievementDate' => Carbon::now()
        ]);

        return [
            'success' => [
                'name' => $success->name,
                'description' => $success->description
            ],
        ];
    }

    public function getUnlockedCount(Authenticatable $user): int
    {
        return $user->successes()->count();
    }
}
