<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Http\Controllers\AchievementsController;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleLessonWatched
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $user = User::find($event->user['id']);

        $user->lessons()->attach($event->lesson, ['watched' => true]);

        (new AchievementsController)->lessonAchievements($event->user);

    }
}
