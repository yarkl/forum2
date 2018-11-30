<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 30.11.18
 * Time: 18:40
 */

namespace App;


trait Favouritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }


    public function isFavorited()
    {
        return ! ! $this->favorites->where('user_id', auth()->id())->count();
    }


    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }


    public function getFavoritesCount()
    {
        return $this->favorites->count();
    }

}