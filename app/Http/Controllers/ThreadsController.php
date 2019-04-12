<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Channel;
use App\Filters\ThreadsFilter;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','user_confirmed'])->except(['index', 'show','destroy']);
    }


    public function index(Channel $channel, ThreadsFilter $filters){
        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }
        $threads = $threads->filter($filters)->paginate(5);

        if(request()->wantsJson()){
            return $threads;
        }

        $trending = array_map(function($item){
            return json_decode($item);
        },Redis::zrevrange('trending_threads' , 0 , -1));

        return view('threads.index', compact('threads','trending'));
    }


    public function show($channel,Thread $thread)
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

        $thread->recordVisits();

        Redis::zincrby('trending_threads', 1 , json_encode([
                'title' => $thread->title,
                'path'  => $thread->path(),
             ]
        ));

        return view('threads.show', [
            'thread' => $thread,
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
            'slug' => request('title')
        ]);
        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }


    public function create(){
        return view('threads.create')
            ;
    }

    public function destroy($channel,Thread $thread)
    {
       $this->authorize('update',$thread);
        if(request()->wantsJson()){
            return response([],204);
        }
        $thread->delete();
        return redirect('threads');
    }
}
