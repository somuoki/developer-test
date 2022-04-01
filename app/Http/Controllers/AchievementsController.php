<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }

    public function getAchievements(User $user){
        return $user->achievements()->first();
    }

    public function sortAchievements(User $user){
        $achievements = $this->getAchievements($user);

        return [
            'lw_achieved' => (new Achievements)->getLessonAchievements($achievements->lw_achieved),
            'cw_achieved' => (new Achievements)->getCommentAchievements($achievements->cw_achieved),
            'b_achieved'  => (new Achievements)->getBadgeAchievements($achievements)
        ];
    }
}
