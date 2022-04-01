<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Http\Controllers\AchievementsController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleCommentWritten
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        (new AchievementsController)->commentAchievements($event->comment);
    }
}
