<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class CreateLessonAchievementTest extends TestCase
{
    /**
     * Check if lessons watched is inserted in db
     *
     * @return void
     */
    public function test_lesson()
    {
        // create user, lessons and null achievements
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        LessonWatched::dispatch($lesson, $user); // dispatch new lesson event

        $this->assertDatabaseHas('lesson_user', [
            'user_id' => $user['id'],
            'lesson_id' => $lesson['id'],
            'watched' => 1
        ]);

//        $data = $this->get("/users/{$user->id}/achievements")->getData();
//        Assert::assertTrue(in_array('First Lesson Watched', $data->unlocked_achievements ));
    }
}
