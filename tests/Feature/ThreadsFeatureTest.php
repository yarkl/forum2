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


    public function test_a_user_can_browse_a_threads()
    {
        $response = $this->get('threads');

        $response->assertSee($this->threads->title);
    }

    public function test_a_user_can_browse_a_single_thread()
    {
        $thread = create('App\Thread');

        $response = $this->get($thread->path());

        $response->assertSee($thread->title);
    }


    public function test_a_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');

        $threadWithChannel = create('App\Thread',['channel_id' => $channel->id]);

        $thread = create('App\Thread',['channel_id' => $channel->id + 1]);


        $this->get('threads/'. $channel->slug)
            ->assertSee($threadWithChannel->title)
            ->assertDontSee($thread->title);
    }

    public function test_a_user_can_filter_threads_by_name()
    {
        $this->signIn(create('App\User',['name' => 'Yaroslav']));

        $threadByYaroslav = create('App\Thread',['user_id' => auth()->id()]);

        $threadNot = create('App\Thread');

        $name = auth()->user()->name;

        $this->get("/threads?by={$name}")
            ->assertSee($threadByYaroslav->title)
            ->assertDontSee($threadNot->title);
    }

    public function test_a_user_can_filter_threads_by_popularity()
    {

        $threadWithTwoReplies = create('App\Thread');

        create('App\Reply',['thread_id' => $threadWithTwoReplies], 2);

        $threadWithThreeReplies = create('App\Thread');

        create('App\Reply',['thread_id' => $threadWithThreeReplies], 3);

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response,'replies_count'));
    }

    public function test_a_user_can_filter_unanswered_threads()
    {
        create('App\Thread',[],2);

        $threadWithTwoReplies = create('App\Thread');

        create('App\Reply',['thread_id' => $threadWithTwoReplies], 2);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertEquals([0,0,0], array_column($response,'replies_count'));

        $this->assertCount(3,$response);
    }




}
