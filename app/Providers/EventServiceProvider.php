<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\ModelLiked;
use App\Listeners\SendNewCommentNotification;
use App\Listeners\SendNewLikeNotificaion;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ModelLiked::class => [
          SendNewLikeNotificaion::class,
        ],
        CommentCreated::class =>[
            SendNewCommentNotification::class
        ]
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
