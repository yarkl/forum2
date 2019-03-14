@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include("threads._list-thread")

            </div>
            <div class="col-md-4">
                <ul>
                    <h3>Trending threads</h3>
                    @foreach($trending as $thread)
                        <li>
                            <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            {{ $threads->render() }}
        </div>
    </div>
@endsection