<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function store(User $user)
    {
        $this->validate(\request(),['avatar' => 'required|image']);
        $user->update([
            'avatar_path' => request()->file('avatar')->store('avatars','public')
        ]);
        return response('', [204]);
    }
}
