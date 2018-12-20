<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Thread;
use App\Channel;
use App\Filters\ThreadsFilter;
class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show','destroy']);
    }


    public function index(Channel $channel, ThreadsFilter $filters){

        if ($channel->exists) {
            //dd($channel->threads());
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }
        $threads = $threads->filter($filters)->get();

        if(request()->wantsJson()){
            return $threads;
        }


       return view('threads.index', compact('threads'));
    }


    public function show($channel,Thread $thread){

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10)
        ]);
    }

    public function store(Request $request){
        //dd(request()->all());
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
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
