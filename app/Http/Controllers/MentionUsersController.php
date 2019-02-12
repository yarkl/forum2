<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class MentionUsersController extends Controller
{
 		
 	public function index()
 	{
 		$name = request('name');

 		return User::where('name', 'like', "%$name%")->limit(5)->pluck('name');
 	}
}
