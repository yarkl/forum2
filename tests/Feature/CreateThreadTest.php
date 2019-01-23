<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_an_auth_can_make_threads()
    {
        //$this->actingAs(factory('App\User')->create());
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }


    public function test_guest_may_not_create()
    {
        $this->expectException(AuthenticationException::class);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

    }


    function test_guest_cannot_see_create_thread_page()
    {
        $this->withExceptionHandling()->get('thread/create')
            ->assertRedirect('/login');
    }

    function test_a_thread_requires_a_title()
    {
        $this->publishThreads(['title' => null])
            ->assertSessionHasErrors('title');
    }


    public function publishThreads($overrides = [])
    {
        $this->withExceptionHandling()->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray());
    }

    function test_a_thread_requires_a_channel()
    {
        factory('App\Channel');

        $this->publishThreads(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publishThreads(['channel_id' => 1000])
            ->assertSessionHasErrors('channel_id');
    }

    function unauthorized_users_cant_delete_a_thread(){
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $response = $this->delete($thread->path());
        $response->assertRedirect('/login');
        $this->signIn();
        $response->assertRedirect('/login');
        $this->delete($thread->path())->assertStatus(403);
    }

    function test_a_thread_can_be_deleted(){
        $this->signIn();
        $thread = create('App\Thread',['user_id' => auth()->id()]);
        $reply = create('App\Reply',['thread_id' => $thread->id]);
        $this->post($thread->path());
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertEquals(0,Activity::count());
        /*$this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('replies', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);*/
    }

}
