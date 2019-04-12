<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 * @package App
 */
class Reply extends Model
{
    use Favoritable,RecordsActivity,Wrappable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $with = ['owner','favorites'];

    /**
     * @var array
     */
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function getIsBestAttribute()
    {
        return $this->thread->best_reply == $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class,'thread_id');
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#{$this->id}";
    }


    /**
     * Check if reply was just published
     * @return mixed
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     *
     */
    public function wrap()
    {
        $this->body = preg_replace('/@([\w]+)/','<a href="/profiles/$1">$0</a>',$this->body);
    }

    public function markASBest()
    {
        $this->thread->best_reply = $this->id;

        return $this->thread;
    }

}
