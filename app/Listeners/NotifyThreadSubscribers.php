<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;


class NotifyThreadSubscribers
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
     * @param  object  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $event->thread->subscription
            ->where('user_id' ,'!=' ,$event->reply->user_id)->each
            ->notify($event->reply);
    }
}
