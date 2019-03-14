@forelse ($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">

                <h4 style="<?php echo $thread->hasUpdates() ? 'font-weight:bold' : '';?>">
                    <a href="{{ $thread->path() }}">
                        {{ $thread->title }}
                    </a>
                </h4>
                <a href="{{ $thread->path() }}"><strong>{{ $thread->replies_count }}
                        {{ str_plural('comments',$thread->replies_count ) }}</strong></a>

            </div>
        </div>

        <div class="panel-body">

            <article>
                <div class="body">{{ $thread->body }}</div>
            </article>

            <hr>

        </div>

        <div class="panel-footer">
            Views {{ $thread->visits() }}
        </div>
    </div>
@empty
    <p>There are no records</p>
@endforelse