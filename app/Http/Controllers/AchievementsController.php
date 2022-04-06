<?php

namespace App\Http\Controllers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use App\Services\Achievements;
use App\Services\UpdateAchievements;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $achievements = (new Achievements)->sortAchievements($user);

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

    /**
     * count lessons and update achievements if accomplished.
     */
    public function lessonAchievements(User $user){
        $watched = $user->watched()->count();

        (new UpdateAchievements)->checkIfUpdatable($watched,$user, 'lesson');
    }

    /**
     * count comments and update achievements if accomplished.
     */
    public function commentAchievements(Comment $comment){
        $user = $comment->user()->first();
        $no_comments = $user->comments()->count();

        (new UpdateAchievements)->checkIfUpdatable($no_comments, $user, 'comments');
    }

    /**
     * count achievements and update badge if acquired.
     */
    public function badgeUnlocking(User $user){
        $achievements = $this->getAchievements($user);
        $total_achievements = (new Achievements)->noOfAchievements($achievements);

        (new UpdateAchievements)->checkIfUpdatable($total_achievements, $user, 'badge');

    }


}
