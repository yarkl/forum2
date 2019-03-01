@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ asset($profileUser->avatar_path) }}" alt="{{ $profileUser->name }}">
                <div class="page-header">
                    <h3>{{ $profileUser->name }}</h3>
                </div>

                @can('update',$profileUser)
                    <form method="post" action="{{ route('avatar',$profileUser->name) }}" enctype="multipart/form-data">
                        <input name="avatar" type="file">
                        {{ csrf_field()}}
                        <button type="submit" class="btn btn-default">Save</button>
                    </form>
                @endcan
                @forelse ($activities as $date => $activity)
                    <h3 class="page-header">{{ $date }}</h3>

                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection