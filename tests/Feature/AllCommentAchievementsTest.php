<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class AllCommentAchievementsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_comments()
    {
        $user = User::factory()->create();

        for ($i = 0; $i <= 20; $i++) {
            Comment::factory()->create([
                'user_id' => $user->id
            ]);

            if ($i == 1){
                $this->checkResponse($user->id, 'First Comment Written');
            }elseif ($i == 3){
                $this->checkResponse($user->id, '3 Comments Written');
            }
            elseif ($i == 5){
                $this->checkResponse($user->id, '5 Comments Written');
            }elseif ($i == 10){
                $this->checkResponse($user->id, '10 Comments Written');
            }elseif ($i == 20){
                $this->checkResponse($user->id, '20 Comments Written');
            }
        }
        $this->checkBadge($user->id);
    }

    public function checkResponse($user, $expectedResults){
        $data = $this->get("/users/{$user}/achievements")->getData();
        Assert::assertTrue(in_array($expectedResults, $data->unlocked_achievements ));
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
