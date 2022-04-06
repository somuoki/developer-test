<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Assert;
use PHPUnit\TextUI\XmlConfiguration\PHPUnit;
use Tests\TestCase;

class CreateCommentsAchievementTest extends TestCase
{
    /**
     * Test to assert if comment is stored in db
     *
     * @return void
     */
    public function test_comment()
    {
        // create user and null achievements
        $user = User::factory()->create();

        $comment = Comment::create([
            'body' =>'acd',
            'user_id' => $user['id']
        ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'acd',
            'user_id' => $user['id']
        ]);
//        $data = $this->get("/users/{$user->id}/achievements")->getData();
//
//        Assert::assertTrue(in_array('First Comment Written', $data->unlocked_achievements ));
    }
}
