<?php

namespace App\Services;

use App\Http\Controllers\AchievementsController;
use App\Models\Achievement;
use App\Models\User;

class Achievements
{

    public function getAchievements(User $user){ // Gets Achievements Acquired by User
        return $user->currentAchievements()->first();
    }

    public function sortAchievements(User $user){
        $sorted_achievements = $this->achievements($this->getAchievements($user));

        return [
            'unlocked' => array_merge($sorted_achievements['lessons']['unlocked'],$sorted_achievements['comments']['unlocked']),
            'next' => $sorted_achievements['lessons']['next'].','.$sorted_achievements['comments']['next'],
            'current_badge'  => $sorted_achievements['badge']['unlocked'],
            'next_badge' => $sorted_achievements['badge']['next'],
            'remainder' => $sorted_achievements['badge']['remaining'],
        ];
    }

    public function achievements($achievements){
        return [
            'lessons' => $this->getTypeAchievements($achievements->lw_achieved,'lesson'),
            'comments' => $this->getTypeAchievements($achievements->cw_achieved, 'comments'),
            'badge' => $this->getBadgeAchievements($achievements),
        ];
    }

    public function getAchievementFromDb($achievement_id){ // Gets all achievement information
        return Achievement::find($achievement_id);
    }

    public function getTypeAchievements($value, $type){
        $achievement = $value !== 0 ? $this->getAchievementFromDb($value) : Achievement::where('type', $type)->first();

        $unlocked = Achievement::select('achievement')
            ->where('id', '<=', $value)
            ->where('type', $type)
            ->get();
        return[
            'unlocked' => empty($unlocked) ? array() : explode(',', $unlocked->implode('achievement',',')),
            'next' => !is_null($achievement->next) ? Achievement::select('achievement')->where('id', $achievement->next)->first()->achievement : ''
        ];
    }


    public function getBadgeAchievements($value){
        $achieved = $this->noOfAchievements($value);
        $badge = $value->b_achieved !== 0 ? $this->getAchievementFromDb($value->b_achieved) : null;
//            Achievement::where('type', 'badge')->first();
        if (!is_null($badge)) {
            $next = !is_null($badge->next) ? $this->getAchievementFromDb($badge->next) : '';
        } else {
            $next = Achievement::where('type', 'badge')->first();;
        }

        return [
            'unlocked' => !empty($badge) ? $badge->achievement : '',
            'next' => !empty($next) ? $next->achievement : '',
            'remaining' => !empty($next) ? $next->required - $achieved : 0
        ];
    }

    public function noOfAchievements($value){
        $comments = Achievement::where('id', '<=', $value->cw_achieved)->where('type', 'comments')->count();

        $lessons = Achievement::where('id', '<=', $value->lw_achieved)->where('type', 'lesson')->count();

        return $comments + $lessons;
    }
}
