<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 28.12.18
 * Time: 14:06
 */

namespace Tests\Feature;


use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_subscribe_to_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->assertFalse($thread->isSubscribedTo);

        $this->post($thread->path() . '/subscribe');

        $this->assertTrue($thread->isSubscribedTo);

        $this->assertEquals(1,$thread->subscription->count());
    }

    public function test_a_user_can_unsubscribe_to_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscribe');

        $this->assertTrue($thread->isSubscribedTo);

        $this->delete($thread->path() . '/subscribe');

        $this->assertFalse($thread->isSubscribedTo);

        $this->assertEquals(0,$thread->subscription->count());
    }


}