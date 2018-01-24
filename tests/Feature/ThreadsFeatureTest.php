<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsFeatureTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->threads = factory('App\Thread')->create();
    }


    public function test_a_user_can_browse_a_thread()
    {

        $response = $this->get('threads');
        $response->assertSee($this->threads->title);


    }

    public function test_a_user_can_browse_a_single_thread()
    {
        $response = $this->get('threads/' . $this->threads->id);
        $response->assertSee($this->threads->title);
    }


    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->threads->id]);
        $this->get('threads/' . $this->threads->id)
            ->assertSee($reply->body);
    }




}
