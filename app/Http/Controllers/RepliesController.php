<?php

namespace App\Http\Controllers;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        //dd(request());

        $this->validate(request(), ['body' => 'required']);
        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return redirect($thread->path());
    }
}
