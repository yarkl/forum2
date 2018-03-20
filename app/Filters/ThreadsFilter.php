<?php
/**
 * Created by PhpStorm.
 * User: yar
 * Date: 09.03.18
 * Time: 18:13
 */
namespace  App\Filters;
use App\User;
use App\Thread;
class ThreadsFilter
{
    private $request;
    protected $builder;
    public function __construct(\Illuminate\Http\Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        if ($username = $this->request->by){
            return $this->by($username);
        }elseif($popular = $this->request->popular){
            return $this->popular();
        }



    }

    public function by($username){
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('id', $user->id);
    }

    public function popular(){
        $threads = Thread::orderBy('replies_count', 'desc');
        return $threads;
    }





}