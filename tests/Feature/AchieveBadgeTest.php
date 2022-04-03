<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchieveBadgeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @throws \Exception
     */
    public function test_example()
    { // Tests Badge Unlocking

        $this->expectsEvents(BadgeUnlocked::class);
        $user = User::factory()->create();

        $user->currentAchievements()->create();

        Comment::factory()->count(19)->make([
            'user_id' => $user['id']
        ]);

        $lesson = Lesson::factory()->create();
        LessonWatched::dispatch($lesson, $user);

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertSee('Intermediate: 4 Achievements');
    }
}
