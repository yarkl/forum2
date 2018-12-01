<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class ProfileController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activity()->with('subject')->get()->groupBy(function($activity){
            return $activity->created_at->format('Y-m-d');
        });
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => $activities
        ]);
    }
}
