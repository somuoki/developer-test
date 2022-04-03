<?php

namespace App\Services;

use App\Models\Achievement;

class Achievements
{

    public function achievements($achievements){
        return [
            'lessons' => $this->getTypeAchievements($achievements->lw_achieved,'lesson'),
            'comments' => $this->getTypeAchievements($achievements->cw_achieved, 'comments'),
            'badge' => $this->getBadgeAchievements($achievements),
        ];
    }

    public function getAchievementFromDb($achievement_id){
        return Achievement::find($achievement_id);
    }

    public function getTypeAchievements($value, $type){
        $achievement = $value !== 0 ? $this->getAchievementFromDb($value) : Achievement::where('type', $type)->first();

        $unlocked = Achievement::select('achievement')
            ->where('id', '<=', $value)
            ->where('type', $type)
            ->get();

        return[
            'unlocked' => $unlocked->implode('achievement',','),
            'next' => !is_null($achievement->next) ? Achievement::select('achievement')->where('id', $achievement->next)->first()->achievement : ''
        ];
    }


    public function getBadgeAchievements($value){
        $achieved = $this->noOfAchievements($value);
        $badge = $value->b_achieved !== 0 ? $this->getAchievementFromDb($value->b_achieved) : Achievement::where('type', 'badge')->first();
        $next = !is_null($badge->next) ? $this->getAchievementFromDb($badge->next) : '';

        return [
            'unlocked' => $badge->achievement,
            'next' => $next->achievement,
            'remaining' => !empty($next) ? $next->required - $achieved : 0
        ];
    }

    private function noOfAchievements($value){
        $comments = Achievement::where('id', '<', $value->cw_achieved)->where('type', 'comments')->count();

        $lessons = Achievement::where('id', '<', $value->lw_achieved)->where('type', 'lesson')->count();

        return $comments + $lessons;
    }
}
