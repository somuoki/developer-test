<?php

namespace App\Http\Controllers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Comment;
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

    public function lessonAchievements(User $user){ // count lessons and update achievements if accomplished
        $watched = $user->watched()->count();
        $achievements = $this->getAchievements($user);

        if ($watched >= 50 && $achievements->lw_achieved < 50){
            $this->updateAchievements($user, 50,'lw_achieved');
        }elseif ($watched >= 25 && $achievements->lw_achieved < 25){
            $this->updateAchievements($user, 25,'lw_achieved');
        }elseif ($watched >= 10 && $achievements->lw_achieved < 10){
            $this->updateAchievements($user, 10,'lw_achieved');
        }elseif ($watched >= 5 && $achievements->lw_achieved < 5){
            $this->updateAchievements($user, 5,'lw_achieved');
        }elseif ($watched >= 1 && $achievements->lw_achieved < 1){
            $this->updateAchievements($user, 1,'lw_achieved');
        }

    }

    public function commentAchievements(Comment $comment, User $user=null){ // count comments and update achievements if accomplished
        $user = $user ?? $comment->user()->first();
        $no_comments = $user->comments()->count();
        $achievements = $this->getAchievements($user);

        if ($no_comments >= 20 && $achievements->cw_achieved < 20){
            $this->updateAchievements($user, 20,'cw_achieved');
        }elseif ($no_comments >= 10 && $achievements->cw_achieved < 10){
            $this->updateAchievements($user, 10,'cw_achieved');
        }elseif ($no_comments >= 5 && $achievements->cw_achieved < 5){
            $this->updateAchievements($user, 5,'cw_achieved');
        }elseif ($no_comments >= 3 && $achievements->cw_achieved < 3){
            $this->updateAchievements($user, 3,'cw_achieved');
        }elseif ($no_comments >= 1 && $achievements->cw_achieved < 1){
            $this->updateAchievements($user, 1,'cw_achieved');
        }

    }

    public function badgeUnlocking(User $user){ // count achievements and update badge if acquired
        $achievements = $this->getAchievements($user);
        $total_achievements = $achievements->lw_achieved + $achievements->cw_achieved;
        if ($total_achievements >= 4 && $achievements->b_achieved == 0){
            $this->updateBadge($user, 4);
        }elseif ($total_achievements >= 8 && $achievements->b_achieved == 4){
            $this->updateBadge($user, 8);
        }elseif ($total_achievements >= 10 && $achievements->b_achieved == 8){
            $this->updateBadge($user, 10);
        }
    }

    private function updateAchievements(User $user, $achievement, $type){
        $user->achievements()->update([$type => $achievement]);
        $achievementName = $this->sortAchievements($user);
        AchievementUnlocked::dispatch($achievementName, $user);
    }

    private function updateBadge(User $user, $achievement){
        $user->achievements()->update(['b_achieved' => $achievement]);
        $achievementName = $this->sortAchievements($user);
        BadgeUnlocked::dispatch($achievementName, $user);
    }


}
