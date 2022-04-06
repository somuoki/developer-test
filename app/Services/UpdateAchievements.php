<?php

namespace App\Services;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\User;

class UpdateAchievements
{

    public function getAchievements(User $user){
        return $user->currentAchievements()->first();
    }

    public function checkIfUpdatable($number, User $user, $type){
        $achievements = match ($type) {
            'comments' => $this->getAchievements($user)->cw_achieved,
            'lesson' => $this->getAchievements($user)->lw_achieved,
            default => $this->getAchievements($user)->b_achieved,
        };

        $prev_achievement = $achievements > 0 ? Achievement::find($achievements) : null;

        if (!is_null($prev_achievement)){
            $next_achievement = !is_null($prev_achievement->next) ? Achievement::find($prev_achievement->next) : null ;
        }else{
            $next_achievement = Achievement::where('type', $type)->first();
        }


        if (!is_null($next_achievement) && $number >= $next_achievement->required){
            $type = match ($type) {
                'comments' => 'cw_achieved',
                'badge' => 'b_achieved',
                default => 'lw_achieved',
            };
            $this->updateAchievements($user, $next_achievement->id, $type);
        }
    }

    private function updateAchievements(User $user, $achievement, $type){
        $user->currentAchievements()->update([$type => $achievement]);
        $achievementName = (new Achievements)->sortAchievements($user);
        if ($type == 'badge') {
            BadgeUnlocked::dispatch($achievementName, $user);
        }else{
            AchievementUnlocked::dispatch($achievementName, $user);
        }
    }




}
