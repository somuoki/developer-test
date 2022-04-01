<?php

namespace App\Services;

class Achievements
{

    public function getLessonAchievements($value){
        $achievements['unlocked'] = $value == 0 ? 'No Lessons Watched' : $value.' Lessons Watched';

        $achievements['unlocked'] = match ($value){
            0 => ['No Lessons Watched'],
            1 => ['First Lesson Watched'],
            5 => ['5 Lessons Watched', 'First Lesson Watched'],
            10 =>['10 Lessons Watched', '5 Lessons Watched', 'First Lesson Watched'],
            25 =>['25 Lessons Watched', '10 Lessons Watched', '5 Lessons Watched', 'First Lesson Watched'],
            50 =>['50 Lessons Watched', '25 Lessons Watched', '10 Lessons Watched', '5 Lessons Watched', 'First Lesson Watched']
        };

        $achievements['next'] = match($value){
            0 => 'First Lesson Watched',
            1 => '5 Lessons Watched',
            5 => '10 Lessons Watched',
            10 => '25 Lessons Watched',
            25 => '50 Lessons Watched',
            50 => '',
            default => 'Current Achievement Does not exist'
        };

        return $achievements;
    }

    public function getCommentAchievements($value){

        $achievements['unlocked'] = match ($value){
            0 => ['No Comments Written'],
            1 => ['First Comment Written'],
            3 => ['3 Comments Written', 'First Comment Written'],
            5 => ['5 Comments Written', '3 Comments Written', 'First Comment Written'],
            10 => ['10 Comments Written', '5 Comments Written', '3 Comments Written', 'First Comment Written'],
            20 => ['20 Comments Written', '10 Comments Written', '5 Comments Written', '3 Comments Written', 'First Comment Written']
        };

        $achievements['next'] = match ($value){
            0 => 'First Comment Written',
            1 => '3 Comments Written',
            3 => '5 Comments Written',
            5 => '10 Comments Written',
            10 => '20 Comments Written',
            20 => '',
            default => 'Current Achievement Does not exist'
        };
        return $achievements;
    }

    public function getBadgeAchievements($value){
        $achieved = $this->noOfAchievements($value);
        switch ($value->b_achieved){
            case 0:
                $achievements['unlocked'] = 'Beginner: 0 Achievements';
                $achievements['next'] = 'Intermediate: 4 Achievements';
                $achievements['remaining'] = 4 - $achieved;
                break;
            case 4:
                $achievements['unlocked'] = 'Intermediate: 4 Achievements';
                $achievements['next'] = 'Advanced: 8 Achievements';
                $achievements['remaining'] = 8 - $achieved;
                break;
            case 8:
                $achievements['unlocked'] = 'Advanced: 8 Achievements';
                $achievements['next'] = 'Master: 10 Achievements';
                $achievements['remaining'] = 10 - $achieved;
                break;
            case 10:
                $achievements['unlocked'] = 'Master: 10 Achievements';
                $achievements['next'] = 'All Badges Achieved';
                $achievements['remaining'] = '0';
                break;
            default:
                $achievements['unlocked'] = 'Current Badge Does Not Exist';
                $achievements['next'] = 'Current Badge Does Not Exist';
                $achievements['remaining'] = 'Current Badge Does Not Exist';
        }

        return $achievements;
    }

    private function noOfAchievements($value){
        $comments = match ($value->cw_achieved){
            0 => 0, 1 => 1, 3 => 2, 5 => 3, 10 => 4, 20 => 5
        };
        $lessons = match ($value->lw_achieved){
            0 => 0, 1 => 1, 5 => 2, 10 => 3, 25 => 4, 50 => 5,
        };

        return $comments + $lessons;
    }
}
