<?php

namespace App\Http\Controllers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use App\Services\Achievements;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $achievements = $this->sortAchievements($user);

        return response()->json([
            'unlocked_achievements' => $achievements['unlocked'],
            'next_available_achievements' => $achievements['next'],
            'current_badge' => $achievements['current_badge'],
            'next_badge' => $achievements['next_badge'],
            'remaining_to_unlock_next_badge' => $achievements['remainder']
        ]);
    }

    public function getAchievements(User $user){
        return $user->currentAchievements()->first();
    }

    public function sortAchievements(User $user){
        $sorted_achievements = (new Achievements)->achievements($this->getAchievements($user));

        return [
            'unlocked' => [$sorted_achievements['lessons']['unlocked'], $sorted_achievements['comments']['unlocked']],
            'next' => $sorted_achievements['lessons']['next'].','.$sorted_achievements['comments']['next'],
            'current_badge'  => $sorted_achievements['badge']['unlocked'],
            'next_badge' => $sorted_achievements['badge']['next'],
            'remainder' => $sorted_achievements['badge']['remaining'],
        ];
    }

    public function lessonAchievements(User $user){ // count lessons and update achievements if accomplished
        $watched = $user->watched()->count();
        $achievements = $this->getAchievements($user)->lw_achieved;
        $prev_achievement = $achievements > 0 ? Achievement::find($achievements) : Achievement::where('type', 'lesson')->first();
        $next_achievement = !is_null($prev_achievement->next) ? Achievement::find($prev_achievement->next) : null;

        if ($watched >= $next_achievement->required && !is_null($next_achievement->required)){
            $this->updateAchievements($user, $next_achievement->id,'lw_achieved');
        }

    }

    public function commentAchievements(Comment $comment){ // count comments and update achievements if accomplished
        $user = $comment->user()->first();
        $no_comments = $user->comments()->count();
        $achievements = $this->getAchievements($user)->cw_achieved;

        $prev_achievement = $achievements > 0 ? Achievement::find($achievements) : Achievement::where('type', 'comments')->first();
        $next_achievement = !is_null($prev_achievement->next) ? Achievement::find($prev_achievement->next) : null;

        if ($no_comments >= $next_achievement->required && !is_null($next_achievement->required)){
            $this->updateAchievements($user, $next_achievement->id,'cw_achieved');
        }

    }

    public function badgeUnlocking(User $user){ // count achievements and update badge if acquired
        $achievements = $this->getAchievements($user);
        $total_achievements = $achievements->lw_achieved + $achievements->cw_achieved;

        $prev_achievement = $achievements->b_achieved > 0 ? Achievement::find($achievements->b_achieved) : Achievement::where('type', 'badge')->first();
        $next_achievement = !is_null($prev_achievement->next) ? Achievement::find($prev_achievement->next) : null;

        if ($total_achievements >= $next_achievement->required && !is_null($next_achievement->required)){
            $this->updateAchievements($user, $next_achievement->id,'cw_achieved');
        }

    }

    private function updateAchievements(User $user, $achievement, $type){
        $user->currentAchievements()->update([$type => $achievement]);
        $achievementName = $this->sortAchievements($user);
        AchievementUnlocked::dispatch($achievementName, $user);
    }

    private function updateBadge(User $user, $achievement){
        $user->currentAchievements()->update(['b_achieved' => $achievement]);
        $achievementName = $this->sortAchievements($user);
        BadgeUnlocked::dispatch($achievementName, $user);
    }


}
