<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCommentsAchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // create user and null achievements
        $user = User::factory()->create();
        $user->currentAchievements()->create();

        Comment::create([
            'body' =>'acd',
            'user_id' => $user['id']
        ]);

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertSee('First Comment Written');
    }
}
