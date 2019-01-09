<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;

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

    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->id;
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
        /*$this->subscription
            ->where('user_id','=',$userId != null ? $userId : auth()->id())[0]
            ->user->notificatons->where('notifiable_type','=',self::class)
            ->delete();*/
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

    public function addReply($reply)
    {
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
        return '/threads/' . $this->channel->slug . '/' . $this->id .'/replies';
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
