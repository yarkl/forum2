<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(User $user)
    {
        return $user->unreadNotifications;
    }

    public function destroy(User $user,$id)
    {
        $user->notifications()->where(['id' => $id])->delete();
    }
}
