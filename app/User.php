<?php

namespace App;

use Carbon\Carbon;
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

    public function threads()
    {
        return $this->hasMany('App\Thread', 'user_id')->latest();
    }

    public function activity()
    {
        return $this->hasMany('App\Activity', 'user_id');
    }

    public function threadCacheKey($thread)
    {
        return sprintf('user.%s.thread%s',auth()->id(),$thread->id);
    }

    public function read($thread)
    {
        $key = $this->threadCacheKey($thread);

        cache()->forever($key,Carbon::now());
    }



}
