<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
   use DatabaseMigrations;

    function test_an_auth_user_can_partispate_in_forum()
   {

       $this->signIn();

       $thread = create(Thread::class);

       $reply = create(Reply::class);

       $this->post($thread->repliesPath(), $reply->toArray());

       $this->assertDatabaseHas('replies',['id' => $reply->id,'body'=>$reply->body]);
   }

   public function test_a_reply_requires_a_body()
   {
       $this->withExceptionHandling()->signIn();

       $thread = create('App\Thread');

       $reply = make('App\Reply', ['body' => null]);

       $this->post($thread->path().'/replies', $reply->toArray())
           ->assertSessionHasErrors('body');
   }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply',['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply',['user_id' => auth()->id()]);

        $this->patch("/replies/{$reply->id}",['body' => $reply->body])->assertStatus(200);

        $this->assertDatabaseHas('replies', ['id' => $reply->id,'body' => $reply->body]);
    }

    /**
     * @test
     */
    public function reply_that_contains_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class,['body' => 'Yahoo customer support','thread_id' => $thread->id]);

        $this->withExceptionHandling();

        $this->post($thread->path().'/replies', $reply->toArray())
           ->assertStatus(302);
    }

    /**
     * @test
     */

    public function a_user_may_not_create_more_than_one_reply_per_minute()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class);

        $this->json("POST",$thread->path() . '/replies',$reply->toArray())
            ->assertStatus(200);
        //dd(auth()->user()->fresh()->lastReply->wasJustPublished());
        $this->json("POST",$thread->path() . '/replies',$reply->toArray())
            ->assertStatus(422);
    }

}
