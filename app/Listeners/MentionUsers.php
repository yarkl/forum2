<?php

namespace App\Listeners;

use App\Notifications\MentionUserNotification;
use App\User;

class MentionUsers
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        preg_match_all('#\@([^\s<]+)#',$event->reply->body,$matches);

        foreach ($matches[1] as $name){
            $user = User::where(['name' => $name])->first();

            if($user){
                $user->notify(new MentionUserNotification($event->reply));
            }
        }
    }
}
