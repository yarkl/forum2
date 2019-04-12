<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use function PHPSTORM_META\type;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator','channel'];

    protected $appends = ['isSubscribedTo'];

    public static function boot(){

        parent::boot();

        static::addGlobalScope('replyCount' , function($builder){
            $builder->withCount('replies');
        });

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

    }

    public function getRouteKeyName()
    {
       return 'slug';
    }

    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->slug;
    }

    public function lock()
    {
        $this->update(['locked' => true]);
    }

    public function isLocked()
    {
        return $this->locked == true;
    }

    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function subscribe($userId = null)
    {
        $this->subscription()->create(
            [
                'thread_id' => $this->id,
                'user_id' => $userId != null ? $userId : auth()->id()
            ]
        );
    }

    public function unsubscribe($userId = null)
    {
        $this->subscription()->where(['user_id' => $userId != null ? $userId : auth()->id()])->delete();
    }

    public function subscription()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscription()->where(['user_id' => auth()->id()])->exists();
    }


    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function addReply($reply, Thread $thread)
    {
        if ($thread->isLocked()) {
            throw new \Exception('Thread is locked');
        }

        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this,$reply));

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function repliesPath()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->slug .'/replies';
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function hasUpdates()
    {
        $key = auth()->user() ? auth()->user()->threadCacheKey($this): '';

        return $this->updated_at > cache($key);
    }

    public function recordVisits()
    {
        return Redis::incr("threads.{$this->id}.visits");
    }

    public function visits()
    {
        return (int) Redis::get("threads.{$this->id}.visits");
    }

    public function setSlugAttribute($value)
    {
        $id = auth()->id();
        $value =  "{$id}-". $value;
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($value)
    {
        $max = static::whereTitle($this->title)->latest('id')->value('slug');
        if (is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/',function($match){
                return $match[1] + 1;
            },$max);

        }

        return "{$value}-2";
    }

}
