<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RegistrationConfirmController extends Controller
{
    public function index($token)
    {
        $user = User::where('confirmation_token', '=' , $token)->firstOrFail();
        $user->confirmed = true;
        $user->confirmation_token = '';
        $user->save();
    }
}
