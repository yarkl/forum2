<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 01.02.19
 * Time: 17:39
 */

namespace App;


trait Wrappable
{
    protected static function bootWrappable()
    {
        if (auth()->guest()) return;

        foreach (static::wrapActivities() as $event) {
            static::$event(function ($model){
                $model->wrap();
            });
        }

    }

    protected static function wrapActivities()
    {
        return ['creating','updating'];
    }

}