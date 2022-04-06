<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeUnlockedEventTest extends TestCase
{
    /**
     * A basic feature test for badge unlocked event if dispatchable.
     *
     * @return void
     */
    public function test_BadgeUnlockedEvent()
    {
        Event::fake();
        $user = User::factory()->create();

        BadgeUnlocked::dispatch('Beginner: 0 Achievements', $user);

        Event::assertDispatched(BadgeUnlocked::class);

    }
}
