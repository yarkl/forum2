<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
   use DatabaseMigrations;

    function test_an_auth_user_can_partispate_in_forun()
   {

       $this->be(factory('App\User')->create());
       $thread = factory('App\Thread')->create();
       $reply = factory('App\Reply')->create();
       $this->post('threads/'.$thread->id.'/replies', $reply->toArray());
       $this->get($thread->path())
           ->assertSee($reply->body);
   }
}
