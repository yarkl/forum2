@extends('layouts.app')

@section('content')
 <div class="container">
     <div class="page-heading">
         <h1>{{ $profileUser->name }} <small>Since {{ $profileUser->created_at->diffForHumans() }}</small></h1>
         <hr>
     </div>
     <div class="row">
         <div class="col-md-8 ">
             @foreach ($threads as $thread)
             <div class="panel panel-default">
                 <div class="panel-heading">
                     <div class="level">
                         <span>
                             <a href="">{{ $thread->creator->name }}</a> Posted: {{ $thread->title }}
                         </span>
                         <span>{{ $thread->created_at->diffForHumans() }}</span>
                     </div>
                 </div>
                 <div class="panel-body">

                         <article>
                             <div class="level">
                                 <h4>
                                     <a href="{{ $thread->path() }}">
                                         {{ $thread->title }}
                                     </a>
                                 </h4>
                                 <a href="{{ $thread->path() }}"><strong>{{ $thread->replies_count }} {{ str_plural('comments',$thread->replies_count ) }}</strong></a>
                             </div>

                             <div class="body">{{ $thread->body }}</div>
                         </article>

                         <hr>

                 </div>
             </div>
             @endforeach
         </div>
         {{ $threads->links() }}
     </div>



@endsection