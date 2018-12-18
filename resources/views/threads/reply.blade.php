<div class="panel panel-default">
    <div class="panel-heading">
            <div class="level">
                {{ $reply->owner->name }}
                {{ $reply->created_at->diffForHumans() }}
                <form action="{{ route('favorite',['reply' => $reply->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->getFavoritesCount() }} {{ str_plural('Favorite',$reply->getFavoritesCount()) }}
                    </button>
                </form>
            </div>

    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>

    @can ('update', $reply)
        <div class="panel-footer">
            <form method="POST" action="/replies/{{ $reply->id }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>
        </div>
    @endcan
</div>