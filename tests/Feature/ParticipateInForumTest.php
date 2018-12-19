<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
   use DatabaseMigrations;

    function test_an_auth_user_can_partispate_in_forum()
   {

       $this->be(factory('App\User')->create());
       $thread = factory('App\Thread')->create();
       $reply = factory('App\Reply')->create();
       $this->post($thread->repliesPath(), $reply->toArray());
       $this->get($thread->path())
           ->assertSee($reply->body);
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
    function authorized_users_cann_delete_replies()
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

}
