<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class AllLessonAchievementsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_lessons()
    {


        //Make User to test lessons on
        $user = User::factory()->create();

        //Make all 50 lessons to test all achievements
        $lessons = Lesson::factory()->count(50)->create();

        //loop through all created lessons adding them to watch list for user
        $loop = 0;
        foreach ($lessons as $lesson){
            ++$loop;
            LessonWatched::dispatch($lesson, $user);

            if ($loop == 1){
                $this->checkResponse($user->id, 'First Lesson Watched');
            }elseif ($loop == 5){
                $this->checkResponse($user->id, '5 Lessons Watched');
            }elseif ($loop == 10){
                $this->checkResponse($user->id, '10 Lessons Watched');
            }elseif ($loop == 25){
                $this->checkResponse($user->id, '25 Lessons Watched');
            }elseif ($loop == 50){
                $this->checkResponse($user->id, '50 Lessons Watched');
            }
        }

        $this->checkBadge($user->id);

    }

    public function checkResponse($user, $expectedResult){
        $data = $this->get("/users/{$user}/achievements")->getData();
        Assert::assertTrue(in_array($expectedResult, $data->unlocked_achievements ));
    }

    private function checkBadge($user){
        $response = $this->get("/users/{$user}/achievements");
        $response
            ->assertStatus(200)
            ->assertJson([
                'current_badge' => 'Intermediate: 4 Achievements',
            ]);
    }
}
