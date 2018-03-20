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

}
