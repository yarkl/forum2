@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                           <span><a href="{{ route('profile',$thread->creator) }}">{{ $thread->creator->name }}</a>:posted
                               {{ $thread->title }}
                           </span>
                         @can('update',$thread)
                            <form method="POST" action="{{ $thread->path()}}">
                                {{ csrf_field() }}

                                <button class="btn btn-danger">Delete</button>
                            </form>
                         @endcan
                        </div>
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>


                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach



        @if(auth()->check())


                <form method="POST" action="{{ $thread->path(). '/replies' }}">
                    <div class="form-group">
                        {{csrf_field()}}
                        <textarea name="body" id="body" cols="30" rows="10" class="form-control"
                                  placeholder="Have something to say?"></textarea>
                    </div>
                        <button type="submit" class="btn btn-default">Post</button>
                </form>



        @else

              <p class="text"><a href="{{ route('login') }}">Sign in</a> to participate</p>

       @endif()
        </div>
            <div class="col-md-4">
                <div class="panel panel-default">


                    <div class="panel-body">
                        <p> This  thread was published {{ $thread->created_at->diffForHumans() }}</p>by
                        <a href="">{{ $thread->creator->name }}</a> and has {{ $thread->replies_count}} {{ str_plural('replies',$thread->replies_count ) }}
                    </div>
                </div>
            </div>
        </div>
        {{ $replies->links() }}
    </div>
@endsection
