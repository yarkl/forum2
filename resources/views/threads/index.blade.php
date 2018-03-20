@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            @foreach ($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">

                                <h4>
                                    <a href="{{ $thread->path() }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>
                                <a href="{{ $thread->path() }}"><strong>{{ $thread->replies_count }} {{ str_plural('comments',$thread->replies_count ) }}</strong></a>

                        </div>
                    </div>

                    <div class="panel-body">

                            <article>
                                <div class="body">{{ $thread->body }}</div>
                            </article>

                            <hr>

                        </div>
                    </div>
            @endforeach
                </div>

        </div>
    </div>
@endsection