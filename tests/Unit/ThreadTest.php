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
           'body' => 'Vokazal',
            'user_id' => 1
        ]);

        $this->assertCount(1 ,$this->threads->replies);
    }
}
