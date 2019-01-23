<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Support\Facades\Gate;


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

    public function store($channelId, Thread $thread)
    {
        if (Gate::denies('create', new Reply)) {
            return response("Wait",422);
        }

        //$this->authorize('create',new Reply);

       $this->validate(request(), ['body' => 'required|spamfree']);

       $reply = $thread->addReply([
           'body' => request('body'),
           'user_id' => auth()->id() ? auth()->id() : request("user_id")
       ]);

       if (request()->expectsJson()) {
          return $reply->load('owner');
       }

        return redirect($thread->path())->with('flash', 'Your reply has been published!');
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
