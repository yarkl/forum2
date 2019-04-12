<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 03.04.19
 * Time: 8:04
 */

namespace Tests\Feature;


use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function test_a_thread_owner_can_mark_the_best_reply()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $replies = create(Reply::class,['thread_id' => $thread->id],2);

        $this->postJson(route('mark_best_reply',['id' => $replies[0]->id ]),[$replies[0]->toArray()]);

        $this->assertEquals($thread->fresh()->best_reply, $replies[0]->id);

        $this->assertNotEquals($thread->fresh()->best_reply, $replies[1]->id);
    }
}