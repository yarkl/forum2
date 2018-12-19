<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable,RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner','favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class,'thread_id');
    }

    public function path()
    {
        return $this->thread->path() . "#{$this->id}";
    }


}
