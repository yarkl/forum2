<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 27.12.18
 * Time: 11:13
 */

namespace Tests\Unit;


use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_thread_can_be_subscribed()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertDatabaseHas('thread_subscriptions',[
            'thread_id' => $thread->id,
            'user_id' => 1
        ]);
    }

    public function test_a_thread_can_be_unsubscribed()
    {
        $thread = create(Thread::class);

        $thread->unsubscribe($userId = 1);

        $this->assertDatabaseMissing('thread_subscriptions',[
            'thread_id' => $thread->id,
            'user_id' => 1
        ]);
    }
}