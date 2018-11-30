<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded =  [];


    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads(){
        return $this->hasMany('App\Thread', 'user_id')->latest();
    }

}