<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
        $user = factory(User::class)->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make(Thread::class);

        $this->post('/threads',$thread->toArray())
            ->assertRedirect('/threads');
    }

}
