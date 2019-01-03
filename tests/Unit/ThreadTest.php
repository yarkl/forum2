<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    protected $threads;

    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->threads = factory('App\Thread')->create();
    }


    function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->threads->creator);
    }


    function test_can_add_reply()
    {
        $this->threads->addReply([
            'body' => 'Vokzal',
            'user_id' => 1
        ]);

        $this->assertCount(1 ,$this->threads->replies);
    }


    function test_a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    function test_thread_have_a_slug()
    {
        $thread = create('App\Thread');
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }



}
