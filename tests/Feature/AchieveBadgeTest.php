<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchieveBadgeTest extends TestCase
{
    /**
     * Achieve All Badges Test
     * The test works by creating equal achievements for both comments and lessons
     *
     * This means it tests for badge unlocking using both
     *
     * @return void
     * @throws \Exception
     */
    public function test_badges()
    { // Tests Badge Unlocking

        $user = User::factory()->create();

        //Beginner
        $this->checkBadge($user->id, 'Beginner: 0 Achievements');

        // intermediate
        $this->comments(3, $user->id);
        $this->lessons(5, $user);
        $this->checkBadge($user->id, 'Intermediate: 4 Achievements');

        // Advanced
        $this->lessons(20, $user);
        $this->comments(7, $user->id);
        $this->checkBadge($user->id, 'Advanced: 8 Achievements');

        //Master
        $this->comments(10, $user->id);
        $this->lessons(30, $user);
        $this->checkBadge($user->id, 'Master: 10 Achievements');
    }

    public function comments($number,$user){
        Comment::factory()->count($number)->create([
            'user_id' => $user
        ]);
    }

    public function lessons($number, User $user){
        $lessons = Lesson::factory()->count($number)->create();
        foreach ($lessons as $lesson) {
            LessonWatched::dispatch($lesson, $user);
        }
    }

    public function checkBadge($user, $badge){
        $response = $this->get("/users/{$user}/achievements");
        $response
            ->assertStatus(200)
            ->assertJson([
                'current_badge' => $badge,
            ]);
    }
}
