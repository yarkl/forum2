<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function a_user_can_fetch_most_recent_reply()
    {
        $user = create(User::class);

        $reply = create(Reply::class,['user_id' => $user->id]);

        $this->assertEquals($reply->id,$user->lastReply->id);
    }

    /**
     * @test
     */
    public function a_user_must_be_confirmed()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $this->post('/threads',$thread->toArray())
            ->assertRedirect('/threads');
    }
}
