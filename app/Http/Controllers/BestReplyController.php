<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function index(Reply $reply)
    {
        //$this->authorize('update',$reply->thread);
        $reply->markASBest()->save();
    }
}
