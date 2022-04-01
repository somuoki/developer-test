<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Listeners\CheckAchievementBadge;
use App\Listeners\HandleCommentWritten;
use App\Listeners\HandleLessonWatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            HandleCommentWritten::class,
        ],
        LessonWatched::class => [
            HandleLessonWatched::class,
        ],
        AchievementUnlocked::class => [
            CheckAchievementBadge::class,
        ],
        BadgeUnlocked::class => [
            //
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
