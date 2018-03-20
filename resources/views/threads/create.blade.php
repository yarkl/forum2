@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create new thread</div>

                    <div class="panel-body">
                        <form action="/threads" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel">Channel:</label>
                                <select name="channel_id" id="#channel" class="form-control" required>
                                    <option value="">Choose One</option>
                                    @foreach(\App\Channel::all() as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id' ? 'selected' : '') }}>{{ $channel->slug }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" >
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="title" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea type="text" class="form-control" id="title" name="body" placeholder="body" rows="8" value="{{ old('body') }}" required></textarea>
                            </div>
                        <div class="form-group">
                            <button class="btn btn-danger">Publish</button>
                        </div>
                        @if(count($errors))
                            <ul class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection