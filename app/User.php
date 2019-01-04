<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded =  [];


    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads(){
        return $this->hasMany('App\Thread', 'user_id')->latest();
    }

    public function activity(){
        return $this->hasMany('App\Activity', 'user_id');
    }



}
