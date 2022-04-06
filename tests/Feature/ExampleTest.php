<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test to see if endpoint returns results.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->create(); //create User

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }
}
