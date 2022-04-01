<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLessonAchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // create user, lessons and null achievements
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $user->achievements()->create();

        LessonWatched::dispatch($lesson, $user); // dispatch new lesson event

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertSee('First Lesson Watched');
    }
}
