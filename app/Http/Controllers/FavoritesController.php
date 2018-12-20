<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use Illuminate\Support\Facades\DB;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Reply $reply)
    {
       $reply->favorite();
       if(\request()->wantsJson())return response([],200);
       return redirect()->back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
