<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function store($channelId,Thread $thread)
    {
        $thread->subscribe(request('user_id'));
    }

    public function destroy($channelId,Thread $thread)
    {
        $thread->unsubscribe(request('user_id'));
    }
}
