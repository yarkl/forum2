<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Notifications\MentionUserNotification;
use App\Reply;
use App\Thread;
use App\User;



class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channelId,Thread $thread)
    {
        return $thread->replies()->paginate(3);
    }

    public function store($channelId, Thread $thread,ReplyRequest $replyRequest)
    {
        $reply = $thread->addReply([
           'body' => request('body'),
           'user_id' => auth()->id() ? auth()->id() : request("user_id")
        ]);


        return  $reply->load('owner');

    }



    public function update(Reply $reply)
    {
        try{
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(['body' => \request('body')]);

        }catch (\Exception $e){
            return $e->getMessage();
        }


    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update',$reply);

        $reply->delete();

        if(\request()->wantsJson()){
            return response('Reply has been deleted',200);
        }

        return back(302);
    }

}
