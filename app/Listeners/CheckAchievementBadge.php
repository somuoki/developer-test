<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Http\Controllers\AchievementsController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckAchievementBadge
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        (new AchievementsController)->badgeUnlocking($event->user);

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AchievementUnlocked  $event
     * @return void
     */
    public function handle(AchievementUnlocked $event)
    {
        (new AchievementsController)->badgeUnlocking($event->user);
    }
}
